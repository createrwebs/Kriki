import assert from 'node:assert/strict'
import path from 'node:path'
import fs from 'node:fs/promises'
import { createRequire } from 'node:module'
import { pathToFileURL } from 'node:url'
import { build } from 'esbuild'
const deps=path.resolve('.agent-work/test-deps'),require=createRequire(path.join(deps,'package.json'))
const React=require('react'),{create,act}=require('react-test-renderer')
globalThis.IS_REACT_ACT_ENVIRONMENT=true
const output=path.join(deps,'owner-commerce-pages-test.mjs')
await build({entryPoints:['resources/js/lab/components/modules/CommerceDesk.jsx'],outfile:output,bundle:true,format:'esm',jsx:'automatic',external:['react','react/jsx-runtime']})
const {OrdersDesk}=await import(pathToFileURL(output))
const first={uuid:crypto.randomUUID(),reference:'FIRST-ORDER',status:'awaiting_payment',items:[],total_satang:100,version:1,payment_attempts:[]}
const older={...first,uuid:crypto.randomUUID(),reference:'OLDER-ORDER'}
const attempt=(name)=>({uuid:crypto.randomUUID(),payer_name:name,status:'rejected',review_reason:'Fictional',amount_satang:100,version:1,image_available:false})
const one=attempt('EVIDENCE-ONE'),two=attempt('EVIDENCE-TWO')
let calls=[],failHistory=true,tree,checks=0
const check=(value,label)=>{assert.ok(value,label);checks++}
globalThis.fetch=async(url)=>{
    calls.push(url)
    if(url.includes('/payment-attempts')){
        if(failHistory)return{ok:false,status:503,json:async()=>({message:'Fictional upstream unavailable'})}
        return{ok:true,json:async()=>url.includes('cursor=')?{items:[two],limit:25,next_cursor:null}:{items:[one],limit:25,next_cursor:'attempt-next'}}
    }
    return{ok:true,json:async()=>url.includes('cursor=')?{items:[first,older],limit:25,next_cursor:null}:{items:[first],limit:25,next_cursor:'orders-next'}}
}
const button=text=>tree.root.findAllByType('button').find(node=>node.children.includes(text))
const click=text=>act(async()=>button(text).props.onClick())
const props={projectUuid:crypto.randomUUID(),module:{uuid:crypto.randomUUID()},paymentModule:{uuid:crypto.randomUUID()},writable:false}
try{
    await act(async()=>{tree=create(React.createElement(OrdersDesk,props))})
    check(tree.root.findAllByType('article').length===1,'Initial order page renders')
    await click('โหลดคำสั่งซื้อเก่าถัดไป')
    check(tree.root.findAllByType('article').length===2&&!button('โหลดคำสั่งซื้อเก่าถัดไป'),'Older page appends unique orders and ends at null cursor')
    await click('ดูประวัติสลิปทั้งหมดของออร์เดอร์นี้')
    check(tree.root.findByProps({role:'alert'}).children.join('').includes('Fictional upstream unavailable'),'History failure is visible without discarding order list')
    failHistory=false;await click('ดูประวัติสลิปทั้งหมดของออร์เดอร์นี้')
    check(JSON.stringify(tree.toJSON()).includes('EVIDENCE-ONE')&&button('โหลดสลิปเก่าถัดไป'),'Explicit per-order history is rendered with next page control')
    await click('โหลดสลิปเก่าถัดไป')
    check(JSON.stringify(tree.toJSON()).includes('EVIDENCE-TWO')&&!button('โหลดสลิปเก่าถัดไป'),'All evidence pages append without replacing earlier attempts')
    check(calls.filter(url=>url.includes('/payment-attempts')).every(url=>url.includes(first.uuid)),'Attempt paging uses selected order scope')
    await click('โหลดคำสั่งซื้อล่าสุด')
    check(tree.root.findAllByType('article').length===1&&button('โหลดคำสั่งซื้อเก่าถัดไป'),'Refresh resets pagination and history cursors')
    console.log(`PASS: actual owner order/evidence pagination, errors, retry and refresh (${checks} checks).`)
}finally{if(tree)await act(async()=>tree.unmount());await fs.rm(output,{force:true})}
