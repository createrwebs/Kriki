import assert from 'node:assert/strict'
import path from 'node:path'
import fs from 'node:fs/promises'
import { createRequire } from 'node:module'
import { pathToFileURL } from 'node:url'
import { build } from 'esbuild'
const deps=path.resolve('.agent-work/test-deps'),require=createRequire(path.join(deps,'package.json'))
const React=require('react'),{create,act}=require('react-test-renderer')
globalThis.IS_REACT_ACT_ENVIRONMENT=true
const output=path.join(deps,'private-slip-viewer-test.mjs')
await build({entryPoints:['resources/js/lab/components/modules/PrivateSlipImage.jsx'],outfile:output,bundle:true,format:'esm',jsx:'automatic',external:['react','react/jsx-runtime']})
const {default:Viewer}=await import(pathToFileURL(output))
let calls=[],denied=false,expire,tree,checks=0
const check=(value,label)=>{assert.ok(value,label);checks++}
const originalTimeout=globalThis.setTimeout
globalThis.setTimeout=(fn,ms,...args)=>{if(ms>50000)expire=fn;return originalTimeout(fn,ms,...args)}
globalThis.fetch=async(url,options)=>{
    calls.push({url,options})
    return {ok:!denied,status:denied?403:200,json:async()=>denied?{message:'Owner access unavailable'}:{url:'https://fictional.invalid/private.png?token=fictional',expires_at:new Date(Date.now()+300000).toISOString()}}
}
const props={projectUuid:crypto.randomUUID(),moduleUuid:crypto.randomUUID(),attempt:{uuid:crypto.randomUUID(),payer_name:'Fictional',image_available:true}}
const click=()=>act(async()=>tree.root.findByType('button').props.onClick())
try{
    await act(async()=>{tree=create(React.createElement(Viewer,props))})
    check(calls.length===0&&tree.root.findAllByType('img').length===0,'Private evidence is never fetched as part of the order list render')
    await click()
    check(calls.length===1&&calls[0].url.includes(props.attempt.uuid)&&calls[0].options.credentials==='same-origin','Explicit viewing uses scoped owner session endpoint')
    check(tree.root.findByType('img').props.referrerPolicy==='no-referrer','Signed token does not leak as image referrer')
    await act(async()=>expire())
    check(tree.root.findAllByType('img').length===0,'Five-minute expiry clears image URL from rendered DOM')
    await click()
    check(calls.length===2,'Viewing again requests fresh permission and signed URL')
    await act(async()=>tree.update(React.createElement(Viewer,{...props,projectUuid:crypto.randomUUID()})))
    check(tree.root.findAllByType('img').length===0&&calls.at(-1).options.signal.aborted,'Changing project clears previous signed image and aborts request')
    denied=true;await click()
    check(tree.root.findAllByType('img').length===0&&tree.root.findByProps({role:'alert'}).children.join('').includes('Owner access unavailable'),'Authorization errors leave no image behind')
    denied=false
    await act(async()=>tree.update(React.createElement(Viewer,{...props,attempt:{...props.attempt,image_available:false}})))
    check(tree.root.findByType('button').children.join('').includes('หลักฐานรุ่นเดิม'),'Historical evidence has an explicit view action instead of an unavailable placeholder')
    await click()
    await act(async()=>tree.root.findByType('img').props.onError())
    check(tree.root.findAllByType('img').length===0&&tree.root.findByProps({role:'alert'}).children.join('').includes('ไม่สามารถเปิดภาพ'),'Image download authorization/fetch failure clears evidence and offers retry')
    await act(async()=>tree.unmount());tree=null
    check(calls.at(-1).options.signal.aborted,'Unmount aborts outstanding image request')
    console.log(`PASS: actual private slip viewer explicit fetch, expiry, fresh scope and abort (${checks} checks).`)
}finally{globalThis.setTimeout=originalTimeout;if(tree)await act(async()=>tree.unmount());await fs.rm(output,{force:true})}
