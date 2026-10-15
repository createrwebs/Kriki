import test from 'node:test'
import assert from 'node:assert/strict'
import { rolldown } from 'rolldown'
import { mkdtemp, rm } from 'node:fs/promises'
import { tmpdir } from 'node:os'
import { join } from 'node:path'
import { pathToFileURL } from 'node:url'

test('managed form read/proposal never forces file writes or commits', async () => {
    const dir = await mkdtemp(join(tmpdir(), 'krikkit-form-tools-'))
    const originalFetch = globalThis.fetch
    try {
        const outfile = join(dir, 'turn.mjs')
        const bundle = await rolldown({ input: 'resources/js/lab/orchestration/runTurn.js', platform: 'node', external: id => id.startsWith('wa-sqlite') })
        await bundle.write({ file: outfile, format: 'esm' })
        await bundle.close()
        const { runOrchestratedTurn } = await import(pathToFileURL(outfile))
        let requests = 0
        globalThis.fetch = async (_url, options) => {
            requests++
            return new Response(JSON.stringify(options.method === 'POST'
                ? { form_uuid: 'fixture', base_version: 2, definition: { fields: [] }, summary: ['เพิ่มแพ้อาหาร'] }
                : { uuid: 'fixture', latest_version: 2, definition: { fields: [] } }), { status: 200 })
        }
        let continuations = 0
        const result = await runOrchestratedTurn({
            text: 'เพิ่มช่องแพ้อาหารให้ฟอร์ม', explicitLane: 'build', workspaceOpen: true,
            canonicalContents: { 'src/App.jsx': 'export default function App() { return null }' },
            projectUuid: 'project-fixture', toolCalls: [{ name: 'read_form', arguments: { form_uuid: 'fixture' } }],
            continueAgent: async payload => {
                continuations++
                assert.equal(payload.formWorkflow, true)
                assert.ok(! payload.mandateWriteFile && ! payload.forceTools)
                return { toolCalls: [{ name: 'propose_form_change', arguments: { form_uuid: 'fixture', expected_version: 2, operations: [] } }] }
            },
            promoteToCanonical: () => assert.fail('Proposal must not commit VFS'),
        })
        assert.equal(result.ok, true, result.error)
        assert.equal(result.proposalPending, true)
        assert.equal(result.committed, false)
        assert.equal(continuations, 1)
        assert.equal(requests, 2)
    } finally {
        globalThis.fetch = originalFetch
        await rm(dir, { recursive: true, force: true })
    }
})
