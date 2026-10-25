import assert from 'node:assert/strict'
import path from 'node:path'
import { createRequire } from 'node:module'
import { pathToFileURL } from 'node:url'
import { build } from 'esbuild'
const deps = path.resolve('.agent-work/test-deps')
const require = createRequire(path.join(deps,'package.json'))
const React = require('react'), {create,act} = require('react-test-renderer')
globalThis.IS_REACT_ACT_ENVIRONMENT = true
const windowEvents = new EventTarget(), documentEvents = new EventTarget()
let redirect = '', calls = [], closed = 0, mode = 'expired', tree, checks = 0
globalThis.window = Object.assign(windowEvents,{location:{pathname:'/lab/fictional/workspace',assign:url=>{redirect=url}},localStorage:{getItem:()=>null,removeItem:()=>{}}})
globalThis.document = Object.assign(documentEvents,{querySelector:()=>({getAttribute:()=> 'fictional-csrf'}),visibilityState:'visible'})
globalThis.BroadcastChannel = undefined
globalThis.CustomEvent = class extends Event { constructor(type,options={}) { super(type); this.detail=options.detail } }
const status = {oauth_ready:true,linked:true,ready:true,project_ref:'fictional-ref',host:'https://fictional-ref.supabase.co',login:'Fictional org'}
globalThis.fetch = async (url,options) => {
  calls.push({url,method:options.method,body:options.body})
  const body = url==='/lab/datastore/projects' ? (mode==='expired'?{message:'Could not refresh the Supabase session.'}:{projects:[{ref:'fictional-ref',name:'Fictional database'}]}) : status
  const ok = !(url.endsWith('/projects') && mode==='expired')
  return {ok,status:ok?200:422,json:async()=>body}
}
const outfile = path.join(deps,'datastore-reconnect.mjs')
await build({entryPoints:['resources/js/lab/components/ConnectDatastoreModal.jsx'],outfile,bundle:true,format:'esm',platform:'node',jsx:'automatic',external:['react','react/jsx-runtime']})
const {ConnectDatastoreModal} = await import(pathToFileURL(outfile))
const check=(ok,label)=>{assert.ok(ok,label);checks++}
const button=name=>tree.root.findAllByType('button').find(row=>row.children.join('')===name)
try {
  await act(async()=>{tree=create(React.createElement(ConnectDatastoreModal,{open:true,onClose:()=>closed++}))})
  check(tree.root.findAllByProps({role:'alert'}).length===1,'Expired refresh error remains visible in the connection dialog')
  check(!JSON.stringify(tree.toJSON()).includes('enable Projects read'),'Transport/auth failure does not masquerade as a missing permission diagnosis')
  check(Boolean(button('Reconnect Supabase')) && !button('Reconnect Supabase').props.disabled,'Linked expired connection exposes enabled reconnect action')
  check(calls.every(row=>row.method==='GET'),'Opening failed connection does not delete/rebind existing credentials')
  await act(async()=>button('Reconnect Supabase').props.onClick())
  check(redirect.startsWith('/lab/oauth/supabase/start?') && redirect.includes('return=%2Flab%2Ffictional%2Fworkspace'),'Reconnect uses normal OAuth start with current Lab return path')
  check(!calls.some(row=>row.method==='DELETE'),'Reconnect preserves existing link and module bindings')
  await act(async()=>tree.unmount()); tree=null
  mode='healthy';calls=[]
  await act(async()=>{tree=create(React.createElement(ConnectDatastoreModal,{open:true,onClose:()=>closed++}))})
  const refresh=button('Refresh connection')
  check(Boolean(refresh) && !refresh.props.disabled,'Same verified selected ref can refresh its saved API keys')
  await act(async()=>refresh.props.onClick())
  const attach=calls.find(row=>row.url==='/lab/datastore/attach')
  check(attach?.method==='POST' && JSON.parse(attach.body).project_ref==='fictional-ref','Refresh reuses exact selected project through existing checked owner API')
  check(closed===1,'Verified refresh closes the dialog normally')
  check(!calls.some(row=>row.url.includes('query')||row.url.includes('apply')),'Connection refresh does not install schemas or enable writes')
} finally { await act(async()=>tree?.unmount()) }
console.log(`PASS: datastore reconnect and same-ref credential refresh (${checks} checks).`)
