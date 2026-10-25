import assert from 'node:assert/strict'
import path from 'node:path'
import fs from 'node:fs/promises'
import { createRequire } from 'node:module'
import { pathToFileURL } from 'node:url'
import { build } from 'esbuild'
const deps=path.resolve('.agent-work/test-deps'),require=createRequire(path.join(deps,'package.json'))
const React=require('react'),{create,act}=require('react-test-renderer')
globalThis.IS_REACT_ACT_ENVIRONMENT=true
const output=path.join(deps,'storefront-quotes-test.mjs')
await build({entryPoints:['resources/js/lab/widget-runtime/StorefrontWidget.jsx'],outfile:output,bundle:true,format:'esm',jsx:'automatic',external:['react','react/jsx-runtime']})
const module=crypto.randomUUID(),product=crypto.randomUUID(),order=crypto.randomUUID(),saved=[]
globalThis.window={localStorage:{getItem:()=>null,setItem:(_,value)=>saved.push(value)},sessionStorage:{getItem:()=>null,setItem:(_,value)=>saved.push(value)}}
let quoteCount=0,creationCount=0,mode='conflict',requests=[],tree,checks=0
const check=(value,label)=>{assert.ok(value,label);checks++}
const renderedText=node=>typeof node==='string'?node:node.children.map(renderedText).join('')
const response=(body,status=200)=>({ok:status<400,status,json:async()=>body})
const originalNow=Date.now
globalThis.fetch=async(url,options)=>{
    if(url.endsWith('/storefront'))return response({products:[{uuid:product,title:'Fictional coffee',sku:'QA',price_satang:25000}],checkout_ready:true})
    if(url.endsWith('/quotes')){quoteCount++;return response({quote:{quote_uuid:crypto.randomUUID(),items:[{product_uuid:product,title:'Fictional coffee',quantity:1,line_total_satang:28000}],total_satang:28000,expires_at:new Date(originalNow()+600000).toISOString()}},201)}
    if(url.endsWith('/orders')){
        creationCount++;requests.push(JSON.parse(options.body))
        if(mode==='conflict')return response({message:'Review changed prices'},409)
        if(mode==='lost')throw new TypeError('Lost response')
        return response({order:{uuid:order,reference:'FICTIONAL',created_at:new Date(originalNow()).toISOString(),status:'awaiting_payment',total_satang:28000},access_token:'a'.repeat(64)})
    }
    throw new Error('Unexpected URL '+url)
}
const {default:Storefront}=await import(pathToFileURL(output))
const submit=()=>act(async()=>tree.root.findByType('form').props.onSubmit({preventDefault(){}}))
try{
    await act(async()=>{tree=create(React.createElement(Storefront,{apiOrigin:'http://fictional.invalid',moduleUuid:module}))})
    await act(async()=>tree.root.findAllByType('button').find(n=>n.children.includes('หยิบใส่ตะกร้า')).props.onClick())
    await act(async()=>{for(const input of tree.root.findAllByType('input'))input.props.onChange({target:{value:input.props.type==='email'?'fictional@example.invalid':'Fictional'}})})
    await submit()
    check(quoteCount===1&&creationCount===0,'First submit obtains quote only; never creates order automatically')
    check(JSON.stringify(tree.toJSON()).includes('280.00'),'Confirmation shows server quote amount instead of displayed catalog amount')
    const summary=tree.root.findAllByType('p').find(node=>renderedText(node).startsWith('ยอดรวมทั้งหมด:'))
    check(renderedText(summary).includes('280.00') && tree.root.findAllByType('li').every(node=>!renderedText(node).includes('250.00')),'Checkout cart line and total must match the authoritative quote during review')
    await submit()
    check(creationCount===1&&tree.root.findAllByProps({className:'kw-quote-review'}).length===0,'409 clears old quote and requires fresh review')
    await submit()
    check(quoteCount===2&&creationCount===1,'Repricing requires another explicit confirmation')
    mode='lost';await submit()
    check(tree.root.findAllByType('input').every(n=>n.props.disabled),'Ambiguous creation freezes customer payload for safe retry')
    const lost=requests.at(-1)
    Date.now=()=>originalNow()+700000;mode='success';await submit()
    check(JSON.stringify(requests.at(-1))===JSON.stringify(lost),'Lost creation retries identical key, quote and payload even after quote expiry')
    check(JSON.stringify(tree.toJSON()).includes('FICTIONAL'),'Recovered order is shown')
    check(saved.every(value=>!value.includes('fictional@example.invalid')),'Customer PII is not persisted in browser storage')
    console.log(`PASS: actual storefront explicit quote, reprice and ambiguous creation recovery (${checks} checks).`)
}finally{Date.now=originalNow;if(tree)await act(async()=>tree.unmount());await fs.rm(output,{force:true})}
