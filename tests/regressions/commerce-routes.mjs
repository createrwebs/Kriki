import assert from 'node:assert/strict'
import path from 'node:path'
import fs from 'node:fs/promises'
import { createRequire } from 'node:module'
import { pathToFileURL } from 'node:url'
import { build } from 'esbuild'
import { commerceRoute, commercePath } from '../../resources/js/lab/widget-runtime/commerceRoutes.js'
import { moduleBufferConflicts } from '../../resources/js/lab/lib/moduleSource.js'
const deps=path.resolve('.agent-work/test-deps'),require=createRequire(path.join(deps,'package.json'))
const React=require('react'),{create,act}=require('react-test-renderer')
globalThis.IS_REACT_ACT_ENVIRONMENT=true
const output=path.join(deps,'commerce-routes-test.mjs')
const moduleUuid=crypto.randomUUID(),product=crypto.randomUUID(),orderUuid=crypto.randomUUID()
await build({entryPoints:['resources/js/lab/widget-runtime/app-wrapper.jsx'],outfile:output,bundle:true,format:'esm',jsx:'automatic',external:['react','react/jsx-runtime'],plugins:[{name:'generated-site-fixture',setup(builder){
    builder.onResolve({filter:/^\.\/krikkit-widgets\/(ContactForm|NewsFeed|StorefrontWidget|commerceRoutes)/},({path:target})=>({path:path.resolve('resources/js/lab/widget-runtime',path.basename(target))}))
    builder.onResolve({filter:/^\.\/SiteContent\.jsx$|config\.json$|widgets\.css$/},({path:target})=>({path:target,namespace:'fixture'}))
    builder.onLoad({filter:/.*/,namespace:'fixture'},({path:target})=>({loader:'js',contents:target.endsWith('config.json')?`export default ${JSON.stringify({apiOrigin:'https://fictional.invalid',widgets:[{key:'catalog',moduleUuid,route:'/shop'}]})}`:target.endsWith('.css')?'':`import React from 'react'; export default ()=>React.createElement('main',{'data-site':true},'Original site page')`}))
}}]})
const listeners=new Set(),local=new Map(),session=new Map(),history=['/shop']
const storage=map=>({getItem:key=>map.get(key)??null,setItem:(key,value)=>map.set(key,value),removeItem:key=>map.delete(key)})
globalThis.window={location:{pathname:'/shop'},localStorage:storage(local),sessionStorage:storage(session),history:{pushState(_state,_title,url){history.push(url);window.location.pathname=url}},addEventListener(type,fn){if(type==='popstate')listeners.add(fn)},removeEventListener(type,fn){listeners.delete(fn)},dispatchEvent(){for(const fn of [...listeners])fn()}}
globalThis.PopStateEvent=class {constructor(type){this.type=type}}
let tree,creations=0,quotes=0,reads=[],status='awaiting_payment',checks=0
const check=(value,label)=>{assert.ok(value,label);checks++}
const order=()=>({uuid:orderUuid,reference:'FICTIONAL-ROUTE',status,total_satang:25000,items:[],created_at:new Date().toISOString()})
globalThis.fetch=async(url,options)=>{
    if(url.endsWith('/storefront'))return{ok:true,json:async()=>({products:[{uuid:product,sku:'QA',title:'Fictional coffee',price_satang:25000}],checkout_ready:true})}
    if(url.endsWith('/quotes')){quotes++;return{ok:true,json:async()=>({quote:{quote_uuid:crypto.randomUUID(),items:[{product_uuid:product,title:'Fictional coffee',quantity:1,line_total_satang:25000}],total_satang:25000,expires_at:new Date(Date.now()+600000).toISOString()}})}}
    if(url.endsWith('/orders')){creations++;return{ok:true,status:201,json:async()=>({order:order(),access_token:'a'.repeat(64)})}}
    reads.push({url,options});return{ok:true,json:async()=>({order:order()})}
}
const {default:App}=await import(pathToFileURL(output))
const button=text=>tree.root.findAllByType('button').find(n=>n.children.some(child=>typeof child==='string'&&child.includes(text)))
const click=text=>act(async()=>button(text).props.onClick())
const mount=()=>act(async()=>{tree=create(React.createElement(App))})
const unmount=()=>act(async()=>{tree.unmount();tree=null})
const route=target=>act(async()=>{window.location.pathname=target;window.dispatchEvent(new PopStateEvent('popstate'))})
const submit=()=>act(async()=>tree.root.findByType('form').props.onSubmit({preventDefault(){}}))
try{
    await mount();await click('หยิบใส่ตะกร้า');await click('ตะกร้า (')
    check(window.location.pathname==='/shop/cart'&&tree.root.findAllByProps({'data-site':true}).length===0,'Cart has a real direct path without unrelated site404/content')
    await unmount();await mount()
    check(JSON.stringify(tree.toJSON()).includes('Fictional coffee')&&tree.root.findAllByType('form').length===0,'Direct cart reload restores product lines/quantity without jumping to checkout')
    await click('ไปชำระเงิน')
    check(window.location.pathname==='/shop/checkout'&&tree.root.findAllByType('form').length===1,'Checkout is a separate real route')
    await act(async()=>{for(const input of tree.root.findAllByType('input'))input.props.onChange({target:{value:input.props.type==='email'?'fictional@example.invalid':'Fictional'}})})
    await submit();check(quotes===1&&creations===0,'Checkout first requests quote without creating an order')
    await submit()
    check(window.location.pathname===`/shop/orders/${orderUuid}`&&JSON.stringify(tree.toJSON()).includes('FICTIONAL-ROUTE'),'Confirmed checkout navigates to a real scoped order path')
    check(!history.some(url=>url.includes('aaaa')||url.includes('token')),'Capability never enters a URL or history entry')
    status='paid';await unmount();await mount()
    check(JSON.stringify(tree.toJSON()).includes('ชำระเงินสำเร็จ')&&tree.root.findAllByProps({type:'file'}).length===0,'Direct order reload restores session access and latest paid status')
    check(reads.every(read=>read.url.endsWith(orderUuid)&&read.options.credentials==='omit'&&read.options.headers['X-Krikkit-Order-Token']==='a'.repeat(64)),'Only the session-bound order is requested with cookie-free header access')
    const readCount=reads.length;await route(`/shop/orders/${crypto.randomUUID()}`)
    check(reads.length===readCount&&!JSON.stringify(tree.toJSON()).includes('FICTIONAL-ROUTE')&&JSON.stringify(tree.toJSON()).includes('ไม่พบสิทธิ์'),'A different order UUID never reuses the saved capability or reveals another order')
    await route('/shop/checkout');check(JSON.stringify(tree.toJSON()).includes('ตะกร้าว่าง'),'Back/forward route state does not resurrect a submitted cart')
    await route('/shop');check(tree.root.findAllByProps({'data-site':true}).length===1,'Returning to catalog preserves original page rendering')
    await unmount();session.clear();window.location.pathname=`/shop/orders/${orderUuid}`;await mount()
    check(reads.length===readCount&&JSON.stringify(tree.toJSON()).includes('ไม่พบสิทธิ์'),'A copied order URL without session capability cannot read an order')
    const hash=[...new Uint8Array(await crypto.subtle.digest('SHA-256',new TextEncoder().encode('saved')))].map(byte=>byte.toString(16).padStart(2,'0')).join('')
    const expected={'src/App.jsx':hash,'src/krikkit-widgets/widgets.css':hash,'src/krikkit-widgets/new.js':null}
    check((await moduleBufferConflicts({'src/App.jsx':'saved','src/krikkit-widgets/widgets.css':'unsaved CSS','src/krikkit-widgets/new.js':'new draft'},expected)).length===2,'Every touched VFS buffer, including CSS and new paths, is protected')
    check(commerceRoute('/shop','/shop/orders/not-a-uuid')===null&&commercePath('/','cart')==='/cart','Malformed order paths are rejected; root placement stays valid')
    await unmount();check(listeners.size===0,'Route listeners clean up on unmount')
    console.log(`PASS: actual generated commerce routes/cart/quote/order/reload and all-file VFS checks (${checks} checks).`)
}finally{if(tree)await unmount();await fs.rm(output,{force:true})}
