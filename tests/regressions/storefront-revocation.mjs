import assert from 'node:assert/strict'
import path from 'node:path'
import {createRequire} from 'node:module'
import {pathToFileURL} from 'node:url'
import {build} from 'esbuild'
const deps=path.resolve('.agent-work/test-deps'),require=createRequire(path.join(deps,'package.json'))
const React=require('react'),{create,act}=require('react-test-renderer')
globalThis.IS_REACT_ACT_ENVIRONMENT=true
const output=path.join(deps,'storefront-revocation.mjs')
await build({entryPoints:['resources/js/lab/widget-runtime/StorefrontWidget.jsx'],outfile:output,bundle:true,format:'esm',jsx:'automatic',external:['react','react/jsx-runtime']})
const {default:Storefront}=await import(pathToFileURL(output))
const module=crypto.randomUUID(),uuid=crypto.randomUUID(),session=new Map(),sessionKey=`krikkit:order:v1:${module}`
const order={uuid,reference:'FICTIONAL-REVOKE',status:'awaiting_payment',total_satang:19900,created_at:new Date().toISOString(),promptpay:{payee_label:'Fictional only',qr_svg:'<svg></svg>'}}
globalThis.window={localStorage:{getItem:()=>null,setItem:()=>{}},sessionStorage:{getItem:key=>session.get(key)??null,setItem:(key,value)=>session.set(key,value),removeItem:key=>session.delete(key)}}
globalThis.FileReader=class {readAsDataURL(){this.result='data:image/png;base64,fictional';this.onload()}}
const response=(body,status=200)=>({ok:status<400,status,json:async()=>structuredClone(body)})
let mode='healthy',tree,checks=0,reads=0,slipStatus=403,deferred
const check=(value,label)=>{assert.ok(value,label);checks++}
globalThis.fetch=async(url)=>{
  if(url.endsWith('/storefront'))return response({products:[],checkout_ready:true})
  if(url.endsWith('/slips'))return response({message:'Denied fictional submission'},slipStatus)
  reads++
  if(mode==='delayed')return new Promise(resolve=>{deferred=resolve})
  if(mode==='offline')throw new TypeError('Fictional network failure')
  if(mode==='html-denial')return {ok:false,status:403,json:async()=>{throw new SyntaxError('Fictional HTML')}}
  return response(mode==='healthy'?{order}: {message:'Fictional failure'},mode==='healthy'?200:Number(mode))
}
const mount=async()=>{
  session.set(sessionKey,JSON.stringify({uuid,token:'a'.repeat(64),expiresAt:Date.now()+3600000}));mode='healthy'
  await act(async()=>{tree=create(React.createElement(Storefront,{apiOrigin:'http://fictional.invalid',moduleUuid:module,baseRoute:'/',routeView:'order',routeOrderUuid:uuid}))})
}
const unmount=()=>act(async()=>{tree?.unmount();tree=null})
const refresh=()=>tree.root.findAllByType('button').find(button=>button.children.includes('เช็คสถานะล่าสุด')).props.onClick()
const hidden=label=>{
  check(!JSON.stringify(tree.toJSON()).includes('FICTIONAL-REVOKE'),label+' clears order snapshot')
  check(tree.root.findAllByProps({'aria-label':'PromptPay QR Code'}).length===0 && tree.root.findAllByType('form').length===0,label+' hides QR and upload form')
  check(!session.has(sessionKey),label+' removes session capability')
  check(JSON.stringify(tree.toJSON()).includes('ไม่พบสิทธิ์'),label+' explains unavailable order')
}
try{
  for(const denied of ['401','403','404','html-denial']){
    await mount();check(tree.root.findAllByType('form').length===1,'Authorized snapshot has upload form')
    mode=denied;await act(async()=>refresh());hidden(denied);await unmount()
    const before=reads
    await act(async()=>{tree=create(React.createElement(Storefront,{apiOrigin:'http://fictional.invalid',moduleUuid:module,baseRoute:'/',routeView:'order',routeOrderUuid:uuid}))})
    check(reads===before,'Fresh mount cannot reuse denied session access');await unmount()
  }
  for(const temporary of ['503','offline']){
    await mount();mode=temporary;await act(async()=>refresh())
    check(session.has(sessionKey)&&JSON.stringify(tree.toJSON()).includes('FICTIONAL-REVOKE'),'Transient failure retains retry access')
    check(tree.root.findAllByProps({role:'alert'}).length===1,'Transient failure has visible error');await unmount()
  }
  await mount();mode='delayed';let pending
  await act(async()=>{pending=refresh()})
  mode='404';await act(async()=>refresh());hidden('Latest denied read')
  await act(async()=>{deferred(response({order}));await pending});hidden('Late earlier success');await unmount()
  for(const denied of [401,403,404]){
    await mount();slipStatus=denied
    await act(async()=>tree.root.findByProps({type:'file'}).props.onChange({target:{files:[{}]}}))
    await act(async()=>tree.root.findByType('form').props.onSubmit({preventDefault(){}}));hidden('Denied slip '+denied);await unmount()
  }
  console.log(`PASS: storefront terminal denial, retry and stale-response revocation (${checks} checks).`)
}finally{await unmount()}
