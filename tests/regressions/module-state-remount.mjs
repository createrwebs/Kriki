import assert from 'node:assert/strict'
import path from 'node:path'
import { createRequire } from 'node:module'
import { pathToFileURL } from 'node:url'
import { build } from 'esbuild'

// Unit-level REST model, separate from the PostgreSQL policy/CAS regressions.
const deps = path.resolve('.agent-work/test-deps')
const require = createRequire(path.join(deps, 'package.json'))
const React = require('react'), { create, act } = require('react-test-renderer')
const output = path.join(deps, 'module-state-remount.mjs')
await build({entryPoints:['resources/js/lab/components/modules/ContactInbox.jsx'],outfile:output,bundle:true,format:'esm',jsx:'automatic',external:['react','react/jsx-runtime']})
const { default: ContactInbox } = await import(pathToFileURL(output))
globalThis.IS_REACT_ACT_ENVIRONMENT = true
globalThis.document = {querySelector:()=>({getAttribute:()=> 'fixture-csrf'})}
const a = crypto.randomUUID(), b = crypto.randomUUID(), ma = crypto.randomUUID(), mb = crypto.randomUUID()
const row = name => ({uuid:crypto.randomUUID(),subject:name,customer_name:'Fictional',email:'state@example.invalid',message:'Preserve this saved record',status:'new',version:1,created_at:'2026-10-25T00:00:00Z'})
const records = new Map([[a,{module:ma,row:row('Contact A')}],[b,{module:mb,row:row('Contact B')}]])
let checks = 0, accepted = 0, trees = []
const check = (ok,label) => {assert.ok(ok,label);checks++}
globalThis.fetch = async (url, options={}) => {
    const match = url.match(/^\/lab\/([^/]+)\/modules\/([^/]+)\/contacts(?:\/([^/]+))?$/)
    const saved = match && records.get(match[1])
    if (!saved || saved.module !== match[2]) throw new Error('Unscoped request')
    let status=200, payload
    if (options.method === 'PATCH') {
        const command = JSON.parse(options.body)
        assert.deepEqual(Object.keys(command).sort(),['expected_version','status'])
        if (match[3] !== saved.row.uuid) throw new Error('Wrong record scope')
        if (command.expected_version !== saved.row.version) {status=409;payload={message:'Fictional version conflict'}}
        else {saved.row.status=command.status;saved.row.version++;accepted++;payload={item:saved.row}}
    } else payload={items:[saved.row]}
    return {ok:status===200,status,json:async()=>JSON.parse(JSON.stringify(payload))}
}
const mount = async (project,module) => {let tree;await act(async()=>{tree=create(React.createElement(ContactInbox,{projectUuid:project,module:{uuid:module},writable:true}))});trees.push(tree);return tree}
const button = (tree,label) => tree.root.findAllByType('button').find(node=>node.children.join('')===label)
try {
    const ta=await mount(a,ma), tb=await mount(b,mb)
    check(JSON.stringify(ta.toJSON()).includes('Contact A')&&!JSON.stringify(ta.toJSON()).includes('Contact B'),'Project A displays only A records')
    check(JSON.stringify(tb.toJSON()).includes('Contact B')&&!JSON.stringify(tb.toJSON()).includes('Contact A'),'Project B displays only B records')
    await act(async()=>{await Promise.all([button(ta,'อ่านแล้ว').props.onClick(),button(tb,'เก็บแล้ว').props.onClick()])})
    check(records.get(a).row.status==='read'&&records.get(b).row.status==='archived'&&accepted===2,'Independent event responses preserve project state')
    records.get(a).row.status='archived';records.get(a).row.version++ // Another client accepted a newer version.
    await act(async()=>{await button(ta,'ใหม่').props.onClick()})
    check(ta.root.findAllByProps({role:'alert'}).some(node=>node.children.join('').includes('version conflict')),'Stale command exposes a recoverable conflict')
    check(records.get(a).row.status==='archived'&&accepted===2,'Stale event cannot replace accepted state')
    check(ta.root.findByProps({'data-status':'archived'}),'Conflict refetch displays authoritative saved state')
    await act(async()=>{ta.unmount();tb.unmount()});trees=[]
    const reloaded=await mount(a,ma)
    check(reloaded.root.findByProps({'data-status':'archived'})&&JSON.stringify(reloaded.toJSON()).includes('Preserve this saved record'),'Remount reloads saved record/state rather than creating a new record')
    check(records.size===2&&records.get(a).row.version===3&&records.get(b).row.version===2,'Other project/version survives UI lifecycle')
    console.log(`PASS: module state scope, conflict and remount (${checks} checks, React unit REST model).`)
} finally {for(const tree of trees)await act(async()=>tree.unmount())}
