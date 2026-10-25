import assert from 'node:assert/strict'
import path from 'node:path'
import { createRequire } from 'node:module'
import { pathToFileURL } from 'node:url'
import { build } from 'esbuild'
import { surveyLabDatastore } from '../../resources/js/lab/lib/datastore.js'

const deps = path.resolve('.agent-work/test-deps')
const require = createRequire(path.join(deps, 'package.json'))
const React = require('react')
const { create, act } = require('react-test-renderer')
globalThis.IS_REACT_ACT_ENVIRONMENT = true
globalThis.document = { querySelector: () => ({ getAttribute: () => 'fixture-csrf' }) }
let calls = [], checks = 0, tree
const check = (ok, label) => { assert.ok(ok, label); checks++ }
const a = '00000000-0000-4000-8000-000000000001'
const b = '00000000-0000-4000-8000-000000000002'
const payload = project => ({ scope: 'project', survey_mode: 'registration_metadata', lab_project_uuid: project, ready: true, project_ref: 'fictional-ref', tables: [], client_env: [], suggested_files: [], modules: [{ uuid: project, key: 'contact', title: project === a ? 'Project A module' : 'Project B module', status: 'ready', schema_version: 1, required_schema_version: 1, connection_matches: true }] })
const response = body => ({ ok: true, status: 200, json: async () => body })
const capture = handler => { globalThis.fetch = async (url, options) => { const body = JSON.parse(options.body); calls.push({ url, options, body }); return handler(body) } }

capture(body => response(payload(body.project_uuid)))
await assert.rejects(surveyLabDatastore(), /saved Lab project/)
check(calls.length === 0, 'Missing project never calls transport')
await surveyLabDatastore({ projectUuid: a, fresh: true, owner_id: 99 })
check(JSON.stringify(calls[0].body) === JSON.stringify({ project_uuid: a, fresh: true }), 'Transport sends only current project and fresh')
check(calls[0].options.credentials === 'same-origin' && calls[0].options.headers['X-CSRF-TOKEN'] === 'fixture-csrf', 'Transport preserves platform session and CSRF')

const deskOutput = path.join(deps, 'project-datastore-desk.mjs')
await build({ entryPoints: ['resources/js/lab/components/DatastoreDesk.jsx'], outfile: deskOutput, bundle: true, format: 'esm', jsx: 'automatic', external: ['react', 'react/jsx-runtime'] })
const { DatastoreDesk } = await import(pathToFileURL(deskOutput))
let releaseA
capture(body => body.project_uuid === a ? new Promise(resolve => { releaseA = () => resolve(response(payload(a))) }) : response(payload(b)))
try {
    await act(async () => { tree = create(React.createElement(DatastoreDesk, { projectUuid: a })) })
    await act(async () => { tree.update(React.createElement(DatastoreDesk, { projectUuid: b })) })
    check(JSON.stringify(tree.toJSON()).includes('Project B module'), 'Project change loads B')
    await act(async () => { releaseA() })
    check(!JSON.stringify(tree.toJSON()).includes('Project A module'), 'Delayed A response cannot leak into B')
    check(tree.root.findByType('a').props.href === `/lab/${b}/workspace?panel=modules`, 'Actual module link binds current project')
    capture(() => response(payload(a)))
    await act(async () => { await tree.root.findByType('button').props.onClick() })
    check(tree.root.findAllByProps({ role: 'alert' }).length === 1 && !JSON.stringify(tree.toJSON()).includes('Project A module'), 'Mismatched response is hidden with retryable error')
    capture(body => response(payload(body.project_uuid)))
    await act(async () => { await tree.root.findByType('button').props.onClick() })
    check(JSON.stringify(tree.toJSON()).includes('Project B module') && tree.root.findAllByProps({ role: 'alert' }).length === 0, 'Refresh recovers after scope mismatch')
    await act(async () => { tree.update(React.createElement(DatastoreDesk, {})) })
    const before = calls.length
    check(JSON.stringify(tree.toJSON()).includes('บันทึกโปรเจกต์ก่อน') && calls.length === before, 'Unsaved project shows prerequisite without global fallback')
} finally { await act(async () => tree?.unmount()) }

const toolOutput = path.join(deps, 'project-datastore-runtime.mjs')
await build({ entryPoints: ['resources/js/lab/orchestration/toolRuntime.js'], outfile: toolOutput, bundle: true, format: 'esm', platform: 'node' })
const { applyToolCalls } = await import(pathToFileURL(toolOutput))
const run = projectUuid => applyToolCalls({ projectUuid, workingVfs: { touchedPaths: () => [] }, toolCalls: [{ id: 'scope-fixture', name: 'survey_datastore', arguments: { project_uuid: b, fresh: true } }] })
calls = []
capture(body => response(payload(body.project_uuid)))
const result = await run(a)
check(calls.length === 1 && calls[0].body.project_uuid === a, 'AI-supplied project argument cannot override runtime context')
check(result.observations[0].status === 'ok' && result.observations[0].artifacts.modules[0].title === 'Project A module', 'AI receives only bound module metadata')
const noProject = await run(null)
check(noProject.observations[0].hint === 'project_required' && calls.length === 1, 'AI no-project fails before OAuth or network')
capture(() => response(payload(b)))
const wrong = await run(a)
check(wrong.observations[0].status === 'error' && !JSON.stringify(wrong).includes('Project B module'), 'AI rejects mismatched server response')
capture(body => response({ ...payload(body.project_uuid), client_env: { private: 'must-not-forward' }, tables: [{ name: 'sibling-table' }] }))
const defensive = await run(a)
check(!JSON.stringify(defensive).includes('must-not-forward') && !JSON.stringify(defensive).includes('sibling-table'), 'AI never forwards generic tables or account client credentials')
console.log(`PASS: project datastore client and tool scope (${checks} checks).`)
