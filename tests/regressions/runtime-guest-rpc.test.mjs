import test from 'node:test'
import assert from 'node:assert/strict'
import vm from 'node:vm'
import { build } from 'esbuild'
import { MessageChannel } from 'node:worker_threads'

// Exercise both real protocol endpoints and actual structured-clone MessagePorts.
// The browser engine is a deterministic fixture; this is not browser isolation proof.
test('guest transport preserves fs, fast process completion, package progress, terminal and teardown', async () => {
    const [clientBuild, hostBuild] = await Promise.all([
        build({ entryPoints: ['resources/js/lab/lib/guestRuntime.js'], bundle: true, write: false, format: 'iife', globalName: 'GuestClient' }),
        build({ entryPoints: ['resources/js/lab/guest/host.js'], bundle: true, write: false, format: 'iife', plugins: [{ name: 'engine-fixture', setup(builder) {
            builder.onResolve({ filter: /labRuntimeEngine$/ }, () => ({ path: 'engine', namespace: 'fixture' }))
            builder.onLoad({ filter: /.*/, namespace: 'fixture' }, () => ({ contents: 'export const LabRuntime=globalThis.engineFixture;' }))
        } }] }),
    ])
    const project = '9e25fea9-c4bb-476f-9a87-f645aa536600'
    const platform = 'http://localhost:8892'
    const parentListeners = new Map()
    const childListeners = new Map()
    const storage = new Map()
    const notifications = []
    let childContext, frame, engineOptions, teardownCount = 0, sink
    const childWindowProxy = { postMessage(data, origin, ports) {
        assert.equal(origin, new URL(frame.src).origin)
        childListeners.get('message')?.({ data, origin: platform, source: parentWindowProxy, ports })
    } }
    const parentWindowProxy = { postMessage(data, origin) {
        assert.equal(origin, platform)
        // Wrong-window and wrong-origin messages must not transfer the port.
        parentListeners.get('message')?.({ data, origin: new URL(frame.src).origin, source: {} })
        parentListeners.get('message')?.({ data, origin: platform, source: childWindowProxy })
        parentListeners.get('message')?.({ data, origin: new URL(frame.src).origin, source: childWindowProxy })
    } }
    const parentWindow = {
        location: new URL(`${platform}/lab/${project}/workspace`),
        addEventListener: (type, fn) => parentListeners.set(type, fn),
        removeEventListener: (type) => parentListeners.delete(type),
    }
    const document = {
        getElementById(id) { return id === 'lab-runtime-config' ? { textContent: JSON.stringify({ scheme: 'http:', host_suffix: '.runtime.localhost', port: 8892 }) } : null },
        createElement(tag) {
            assert.equal(tag, 'iframe')
            frame = { contentWindow: childWindowProxy, removed: false, remove() { this.removed = true } }
            return frame
        },
        body: { appendChild() {
            const url = new URL(frame.src)
            const childWindow = { parent: parentWindowProxy, addEventListener: (type, fn) => childListeners.set(type, fn), removeEventListener: type => childListeners.delete(type) }
            const engineFixture = { async boot(options) {
                engineOptions = options
                return {
                    instanceId: 'labfixture',
                    fs: {
                        writeFile: async (path, value) => { storage.set(path, value) },
                        readFile: async path => { if (!storage.has(path)) throw new Error('ENOENT fixture'); return storage.get(path) },
                        stat: async () => ({ isFile: true, isDirectory: false, size: 3 }),
                    },
                    async spawn() { return { on() {}, completion: Promise.resolve({ stdout: 'fast child', stderr: '', exitCode: 0 }) } },
                    packages: { async installFromManifest(path, flags) { flags.onProgress('fixture progress'); return { installed: 1 } } },
                    async setPreviewScript(script) { assert.equal(script, 'trusted script') },
                    async request() { return { status: 200, body: new TextEncoder().encode('guest HTML') } },
                    createTerminal(options) { return {
                        attach() { sink = new options.Terminal(); sink.onInput = text => sink.write(`echo:${text}`); sink.write('terminal ready') },
                        showPrompt() { sink.write('prompt$ ') }, detach() { sink.dispose() },
                    } },
                    async teardown() { teardownCount++ },
                }
            } }
            childContext = vm.createContext({ window: childWindow, location: url, URL, URLSearchParams, TextDecoder,
                document: { getElementById: id => id === 'runtime-host-config' ? { textContent: JSON.stringify({ parent_origins: [platform] }) } : {} },
                engineFixture, console, setTimeout, clearTimeout,
            })
            vm.runInContext(hostBuild.outputFiles[0].text, childContext)
        } },
    }
    const context = vm.createContext({ window: parentWindow, document, location: parentWindow.location, crypto: globalThis.crypto, MessageChannel,
        URL, URLSearchParams, Uint8Array, AbortController, console, setTimeout, clearTimeout, navigator: {},
    })
    vm.runInContext(clientBuild.outputFiles[0].text, context)
    const pod = await context.GuestClient.bootGuestRuntime({ files: { '/seed.txt': 'seed' }, workdir: '/', env: {}, onServerReady: (...args) => notifications.push(args) })
    assert.equal(frame.sandbox, 'allow-scripts allow-same-origin')
    assert.equal(parentListeners.has('message'), false, 'Handshake listener removed after connection')
    assert.notEqual(pod.origin, platform)
    await pod.fs.writeFile('/proof.txt', new Uint8Array([1, 2, 3]))
    assert.deepEqual(Array.from(await pod.fs.readFile('/proof.txt')), [1, 2, 3])
    assert.equal((await pod.fs.stat('/proof.txt')).isFile, true)
    await assert.rejects(pod.fs.readFile('/absent'), /ENOENT fixture/)
    const proc = await pod.spawn('node', ['fixture.js'])
    assert.equal((await proc.completion).stdout, 'fast child')
    assert.equal(proc.exited, true)
    const progress = []
    assert.equal((await pod.packages.installFromManifest('/package.json', { onProgress: text => progress.push(text) })).installed, 1)
    assert.deepEqual(progress, ['fixture progress'])
    await pod.setPreviewScript('trusted script')
    assert.equal((await pod.request(5173)).body, 'guest HTML')
    engineOptions.onServerReady(5173, `${platform}/lab`)
    engineOptions.onServerReady(5173, `${pod.origin}/__virtual__/labother/5173`)
    engineOptions.onServerReady(5173, `${pod.origin}/__virtual__/labfixture/5173`)
    await new Promise(resolve => setTimeout(resolve, 15))
    assert.equal(notifications.length, 1, 'Only own-instance guest URL is accepted')
    const output = []
    let inputHandler
    class Terminal {
        cols = 80; rows = 24
        onData(fn) { inputHandler = fn; return { dispose() {} } }
        onResize() { return { dispose() {} } }
        write(text) { output.push(text) }
        writeln(text) { output.push(text) }
        open() {}
        dispose() {}
        focus() {}
    }
    const terminal = await pod.createTerminal({ Terminal, autoPrompt: false })
    terminal.attach({})
    inputHandler('pwd\r')
    terminal.showPrompt()
    await new Promise(resolve => setTimeout(resolve, 15))
    assert.ok(output.join('').includes('terminal ready'))
    assert.ok(output.join('').includes('echo:pwd\r'))
    assert.ok(output.join('').includes('prompt$ '))
    await pod.teardown()
    assert.equal(teardownCount, 1)
    assert.equal(frame.removed, true)
    await assert.rejects(pod.fs.readFile('/proof.txt'), /Runtime closed/)
})
