import test from 'node:test'
import assert from 'node:assert/strict'
import { readFile } from 'node:fs/promises'
import path from 'node:path'
import { build } from 'esbuild'
import { renderToStaticMarkup } from 'react-dom/server'
import { createBlueprint } from '../../resources/js/builder/blueprint.js'
import { discoverGuestRoutes, guestRouteKnown } from '../../resources/js/lab/lib/previewRoutes.js'

const catalog = JSON.parse(await readFile('resources/builder/catalog.json', 'utf8'))
const blueprint = createBlueprint(catalog, { kind: 'business', templateId: 'business-studio', title: 'Fictional site', summary: '' })

test('exported pages are discovered by actual Lab preview route catalog', () => {
    const table = discoverGuestRoutes({ 'src/krikkit-site/site.json': JSON.stringify(blueprint) })
    for (const page of blueprint.pages) assert.equal(guestRouteKnown(table, page.route), true, page.route)
    assert.equal(guestRouteKnown(table, '/not-a-page'), false)
    assert.deepEqual([...discoverGuestRoutes({ 'src/krikkit-site/site.json': '{' }).routes], ['/'])
    const thai = discoverGuestRoutes({ 'src/krikkit-site/site.json': JSON.stringify({ pages: [{ route: '/เกี่ยวกับ' }] }) })
    assert.equal(guestRouteKnown(thai, new URL('https://fixture.invalid/เกี่ยวกับ').pathname), true)
    assert.equal(guestRouteKnown(thai, '/%E0%broken'), false)
})

test('generated app follows Lab intercepted-anchor popstate and deep routes', async () => {
    const previousWindow = globalThis.window
    const previousHooks = globalThis.__sourceHooks
    let state, cleanup
    const listeners = new Map()
    globalThis.window = {
        location: { pathname: '/' },
        addEventListener: (type, callback) => listeners.set(type, callback),
        removeEventListener: type => listeners.delete(type),
    }
    globalThis.__sourceHooks = {
        state(initial) { state ??= typeof initial === 'function' ? initial() : initial; return [state, next => { state = typeof next === 'function' ? next(state) : next }] },
        effect(callback) { cleanup ??= callback() },
    }
    try {
        const result = await build({
            entryPoints: ['resources/builder/generated-app.jsx'], bundle: true, write: false, format: 'esm', jsx: 'automatic',
            plugins: [{ name: 'trusted-source-fixture', setup(builder) {
                builder.onResolve({ filter: /^react$/ }, args => args.importer.endsWith('generated-app.jsx') ? { path: 'hooks', namespace: 'fixture' } : undefined)
                builder.onResolve({ filter: /krikkit-site\/SitePage\.jsx$/ }, () => ({ path: path.resolve('resources/js/builder/SitePage.jsx') }))
                builder.onResolve({ filter: /krikkit-site\/.*\.json$/ }, args => ({ path: args.path, namespace: 'fixture' }))
                builder.onLoad({ filter: /.*/, namespace: 'fixture' }, args => args.path === 'hooks' ? {
                    contents: 'export default {}; export const useState=(...a)=>globalThis.__sourceHooks.state(...a); export const useEffect=(...a)=>globalThis.__sourceHooks.effect(...a);',
                } : { loader: 'json', contents: JSON.stringify(args.path.endsWith('site.json') ? blueprint : catalog) })
            } }],
        })
        const { default: App } = await import(`data:text/javascript;base64,${Buffer.from(result.outputFiles[0].text).toString('base64')}`)
        assert.match(renderToStaticMarkup(App()), new RegExp(blueprint.pages[0].sections[0].heading))
        assert.equal(typeof listeners.get('popstate'), 'function', 'Lab navigation must cause React to select the new page')
        window.location.pathname = blueprint.pages[1].route
        listeners.get('popstate')()
        const next = renderToStaticMarkup(App())
        assert.ok(next.includes(blueprint.pages[1].sections[0].heading))
        assert.ok(!next.includes(blueprint.pages[0].sections[0].heading))
        window.location.pathname = '/not-a-page'
        listeners.get('popstate')()
        assert.match(renderToStaticMarkup(App()), /Page not found/)
        cleanup()
        assert.equal(listeners.has('popstate'), false)
    } finally {
        globalThis.window = previousWindow
        globalThis.__sourceHooks = previousHooks
    }
})
