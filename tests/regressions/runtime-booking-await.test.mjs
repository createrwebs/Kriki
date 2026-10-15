import test from 'node:test'
import assert from 'node:assert/strict'
import { rolldown } from 'rolldown'
import { readFile, mkdtemp, rm } from 'node:fs/promises'
import { tmpdir } from 'node:os'
import { join } from 'node:path'
import { pathToFileURL } from 'node:url'

test('runtime await transform preserves asynchronous JSX submission handlers', async () => {
    const dir = await mkdtemp(join(tmpdir(), 'krikkit-await-'))
    try {
        const bundle = await rolldown({ input: 'resources/js/lab/runtime/src/syntax-transforms.ts', platform: 'node' })
        const outfile = join(dir, 'syntax.mjs')
        await bundle.write({ file: outfile, format: 'esm' })
        await bundle.close()
        const { stripTopLevelAwait } = await import(pathToFileURL(outfile))
        const booking = await readFile('resources/js/lab/form-runtime/BookingForm.jsx', 'utf8')
        assert.equal(stripTopLevelAwait(booking, 'topLevelOnly'), booking, 'JSX parser fallback must not remove nested await')
        const plain = 'export async function save() { return await fetch("/api"); }'
        assert.equal(stripTopLevelAwait(plain), plain, 'Plain JS nested await preserved')
        assert.match(stripTopLevelAwait('const data = await fetch("/api");'), /__syncAwait\(fetch/, 'Node top-level await compatibility preserved')
    } finally {
        await rm(dir, { recursive: true, force: true })
    }
})

test('actual volume loader preserves browser awaits and confines sync rewriting to Node', async () => {
    const dir = await mkdtemp(join(tmpdir(), 'krikkit-volume-await-'))
    try {
        const bundle = await rolldown({
            input: 'resources/js/lab/runtime/src/polyfills/esbuild.ts', platform: 'node',
            plugins: [{
                name: 'test-engine-boundary',
                resolveId(id) {
                    if (id.endsWith('/helpers/esbuild-engine')) return '\0test-engine'
                    if (id.endsWith('/helpers/event-loop')) return '\0test-loop'
                },
                load(id) {
                    if (id === '\0test-loop') return 'export const getRegistry = () => ({ register: () => ({ close() {} }) });'
                    if (id === '\0test-engine') return `
                        const engine = { async build(config) {
                            let load;
                            config.plugins.at(-1).setup({ onResolve() {}, onLoad(filter, callback) { if (!filter.namespace) load = callback } });
                            const row = await load({ path: '/src/BookingForm.jsx', pluginData: { fromVolume: true } });
                            return { errors: row.errors || [], warnings: [], outputFiles: [{ path: '/out.js', text: row.contents, contents: new TextEncoder().encode(row.contents) }] };
                        } };
                        export const getEsbuildIfReady = () => engine;
                        export const getEsbuild = async () => engine;
                    `
                },
            }],
        })
        const outfile = join(dir, 'loader.mjs')
        await bundle.write({ file: outfile, format: 'esm' })
        await bundle.close()
        const runtime = await import(pathToFileURL(outfile))
        let source = await readFile('resources/js/lab/form-runtime/BookingForm.jsx', 'utf8')
        runtime.setVolume({ existsSync: () => true, readFileSync: () => source })
        for (const platform of ['browser', 'node', undefined]) {
            const result = await runtime.build({ platform, write: false })
            assert.equal(result.outputFiles[0].text, source, `JSX awaits preserved for ${platform}`)
        }
        source = 'const data = await fetch("/api");'
        assert.equal((await runtime.build({ platform: 'browser', write: false })).outputFiles[0].text, source)
        assert.match((await runtime.build({ platform: 'node', write: false })).outputFiles[0].text, /__syncAwait\(fetch/)
    } finally { await rm(dir, { recursive: true, force: true }) }
})
