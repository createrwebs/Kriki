import assert from 'node:assert/strict'
import path from 'node:path'
import fs from 'node:fs/promises'
import { createRequire } from 'node:module'
import { pathToFileURL } from 'node:url'
import { build } from 'esbuild'
const deps=path.resolve('.agent-work/test-deps'), require=createRequire(path.join(deps,'package.json'))
const React=require('react'), {create,act}=require('react-test-renderer')
globalThis.IS_REACT_ACT_ENVIRONMENT=true
const output=path.join(deps,'storefront-retry-test.mjs')
await build({entryPoints:['resources/js/lab/widget-runtime/StorefrontWidget.jsx'],outfile:output,bundle:true,format:'esm',jsx:'automatic',external:['react','react/jsx-runtime']})
const module='10000000-0000-4000-8000-000000000001', uuid='20000000-0000-4000-8000-000000000001'
const order={uuid,reference:'FICTIONAL',status:'awaiting_payment',total_satang:25000,created_at:new Date().toISOString(),items:[]}
globalThis.window={localStorage:{getItem:()=>null,setItem:()=>{}},sessionStorage:{getItem:()=>JSON.stringify({uuid,token:'a'.repeat(64),expiresAt:Date.now()+3_600_000})}}
globalThis.FileReader=class {readAsDataURL(){this.result='data:image/png;base64,fictional';this.onload()}}
const submissions=[]
globalThis.fetch=async(url,options)=>{
    if(url.endsWith('/storefront'))return {ok:true,json:async()=>({products:[],checkout_ready:true})}
    if(url.endsWith('/slips')){submissions.push(JSON.parse(options.body));order.status='pending_review';return{ok:true,json:async()=>({slip:{uuid:crypto.randomUUID(),order_status:'pending_review'}})}}
    return {ok:true,json:async()=>({order:structuredClone(order)})}
}
const {default:Storefront}=await import(pathToFileURL(output))
let tree
const selectFile=async()=>act(async()=>tree.root.findByProps({type:'file'}).props.onChange({target:{files:[{}]}}))
const submit=async()=>act(async()=>tree.root.findByType('form').props.onSubmit({preventDefault(){}}))
try{
    await act(async()=>{tree=create(React.createElement(Storefront,{apiOrigin:'http://fictional.invalid',moduleUuid:module}))})
    await selectFile(); await submit()
    assert.equal(tree.root.findAllByType('form').length,0,'Pending review must hide duplicate slip submission')
    order.status='rejected'
    await act(async()=>tree.root.findAllByType('button').find(node=>node.children.includes('เช็คสถานะล่าสุด')).props.onClick())
    assert.equal(tree.root.findAllByType('form').length,1)
    await selectFile(); await submit()
    assert.equal(submissions.length,2)
    assert.notEqual(submissions[0].idempotency_key,submissions[1].idempotency_key,'Resubmission after rejection is a new attempt even with the same file')
    await act(async()=>tree.unmount())
    globalThis.window={}
    for(const key of ['localStorage','sessionStorage'])Object.defineProperty(window,key,{get(){throw new DOMException('Storage denied','SecurityError')}})
    await act(async()=>{tree=create(React.createElement(Storefront,{apiOrigin:'http://fictional.invalid',moduleUuid:module}))})
    assert.ok(tree.root.findByProps({'aria-label':'ร้านค้าและตะกร้าสินค้า'}))
    console.log('PASS: actual storefront pending/rejected/new-slip retry lifecycle and blocked storage (5 checks).')
}finally{if(tree)await act(async()=>tree.unmount());await fs.rm(output,{force:true})}
