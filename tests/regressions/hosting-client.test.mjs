import test from 'node:test'
import assert from 'node:assert/strict'
import { build } from 'esbuild'
import { renderToStaticMarkup } from 'react-dom/server'
import { createElement } from 'react'

// Render the real panel with its HTTP result in state, without a live browser.
async function loadPanel(filename) {
    const bundle = await build({
        entryPoints: [`resources/js/lab/components/${filename}`],
        bundle: true, write: false, format: 'esm', jsx: 'automatic',
        plugins: [{ name: 'fixture-hooks', setup(builder) {
            builder.onResolve({ filter: /^react$/ }, (args) => args.importer.endsWith(filename) ? { path: 'fixture-hooks', namespace: 'fixture' } : undefined)
            builder.onLoad({ filter: /.*/, namespace: 'fixture' }, () => ({ contents: 'export const useState = (...args) => globalThis.__hostingState(...args); export const useEffect = () => {}; export const useCallback = fn => fn' }))
        } }],
    })
    return (await import(`data:text/javascript;base64,${Buffer.from(bundle.outputFiles[0].text + '\n//# sourceURL=hosting-panel-test.mjs').toString('base64')}`)).default
}
const Panel = await loadPanel('SecurityScanPanel.jsx')
const SecretPanel = await loadPanel('SecretsManager.jsx')
function renderResult(result) {
    const states = [false, result, null, 'low']
    globalThis.__hostingState = () => [states.shift(), () => {}]
    try { return renderToStaticMarkup(createElement(Panel, { projectUuid: 'fixture' })) }
    finally { delete globalThis.__hostingState }
}

test('scan panel displays real backend findings and counts', () => {
    const html = renderResult({ failed: true, violations: [{ severity: 'critical', title: 'Fixture finding', file: 'app.js', line: 3, recommendation: 'Remove fixture pattern' }], files_scanned: 1 })
    assert.match(html, /Fixture finding/)
    assert.match(html, /1 findings/)
    assert.match(html, /Remove fixture pattern/)
})
test('clean heuristic scan is not mislabeled failed or certified safe', () => {
    const html = renderResult({ failed: false, violations: [], files_scanned: 1 })
    assert.doesNotMatch(html, /✗ Failed/)
    assert.match(html, /No matching patterns/)
    assert.match(html, /not a security certification/i)
})

test('operational 422 is an error, not a clean scan result', async () => {
    const states = [false, null, null, 'low']
    let index = 0
    globalThis.__hostingState = () => {
        const key = index++
        return [states[key], (value) => { states[key] = value }]
    }
    const previousFetch = globalThis.fetch
    globalThis.fetch = async () => ({ ok: false, status: 422, json: async () => ({ message: 'Fixture scan unavailable' }) })
    globalThis.document = { querySelector: () => ({ content: 'fixture-csrf' }) }
    function scanButton(element) {
        if (!element || typeof element !== 'object') return null
        if (element.type === 'button') return element
        for (const child of [element.props?.children].flat()) {
            const found = scanButton(child)
            if (found) return found
        }
        return null
    }
    try {
        await scanButton(Panel({ projectUuid: 'fixture' })).props.onClick()
        assert.equal(states[1], null)
        assert.equal(states[2], 'Fixture scan unavailable')
        assert.equal(states[0], false)
    } finally {
        globalThis.fetch = previousFetch
        delete globalThis.document
        delete globalThis.__hostingState
    }
})

test('secret controls cannot discard new edits while saving', () => {
    const states = [['FIXTURE'], false, null, true, [{ key: 'A', value: 'fixture' }, { key: 'B', value: '' }], null]
    globalThis.__hostingState = () => [states.shift(), () => {}]
    const controls = []
    function visit(element) {
        if (!element || typeof element !== 'object') return
        if (['input', 'button'].includes(element.type)) controls.push(element)
        for (const child of [element.props?.children].flat()) visit(child)
    }
    try {
        visit(SecretPanel({ projectUuid: 'fixture' }))
        assert.ok(controls.length >= 8)
        assert.ok(controls.every((element) => element.props.disabled === true), 'All editing/add/remove/delete/save controls must stay disabled until Save settles')
    } finally { delete globalThis.__hostingState }
})
