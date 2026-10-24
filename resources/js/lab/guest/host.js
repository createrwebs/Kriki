// This entry executes ONLY on a cookie-free guest host, never the editor origin.
import { LabRuntime } from '../lib/labRuntimeEngine'
import { PROTOCOL, UUID, SESSION, FS_METHODS, TERMINAL_METHODS, isHandshake } from './protocol'

const config = JSON.parse(document.getElementById('runtime-host-config')?.textContent || '{}')
const params = new URLSearchParams(location.search)
const project = params.get('project') || ''
const session = params.get('session') || ''
const parentOrigin = params.get('parent') || ''
let connected = false

// No referrer-based trust and no query-string expansion of the allowlist.
if (window.parent !== window && UUID.test(project) && SESSION.test(session)
    && location.hostname.startsWith(`r-${session}.`) && config.parent_origins?.includes(parentOrigin)) {
    window.addEventListener('message', connect)
    window.parent.postMessage({ protocol: PROTOCOL, type: 'ready', project, session }, parentOrigin)
}

function connect(event) {
    if (connected || !isHandshake(event, { source: window.parent, origin: parentOrigin, project, session, type: 'connect' })
        || event.ports?.length !== 1) return
    connected = true
    window.removeEventListener('message', connect)
    serve(event.ports[0])
}

function serve(port) {
    let pod = null
    let booting = false
    let nextProcess = 0
    let nextTerminal = 0
    const processes = new Map()
    const terminals = new Map()
    const send = (message) => port.postMessage(message)
    const notify = (type, payload) => send({ type, ...payload })

    // The existing SDK shell/line editor writes to this data-only sink. Real
    // xterm and its DOM remain in the editor; no host object enters this realm.
    function terminalSink(id) {
        return class {
            cols = 80
            rows = 24
            options = {}
            onInput = null
            onSize = null
            constructor() { terminals.get(id).sink = this }
            open() {}
            focus() {}
            write(text) { notify('terminal-data', { terminal: id, text: String(text) }) }
            writeln(text) { this.write(`${text}\r\n`) }
            clear() { this.write('\x1b[2J\x1b[H') }
            onData(fn) { this.onInput = fn; return { dispose: () => { this.onInput = null } } }
            onResize(fn) { this.onSize = fn; return { dispose: () => { this.onSize = null } } }
            dispose() { this.onInput = null; this.onSize = null }
        }
    }

    async function dispatch(method, args, requestId) {
        if (method === 'boot') {
            if (pod || booting) throw new Error('Runtime already booted.')
            booting = true
            const options = args[0]
            pod = await LabRuntime.boot({
                files: options.files, workdir: options.workdir, env: options.env,
                swUrl: '/__krikkit_lab_sw__.js?v=guest-origin-1',
                workerUrl: '/__worker__.js?v=preserve-browser-await-20261016',
                serviceWorker: true, watermark: false, preloadEsbuild: true,
                onServerReady: (portNumber, url) => notify('server-ready', { port: portNumber, url }),
            })
            return { instance: pod.instanceId }
        }
        if (!pod) throw new Error('Runtime not booted.')
        if (method === 'fs') {
            const [name, ...values] = args
            if (!FS_METHODS.has(name)) throw new Error('Unsupported filesystem operation.')
            return pod.fs[name](...values)
        }
        if (method === 'spawn') {
            const processId = ++nextProcess
            const proc = await pod.spawn(...args)
            processes.set(processId, proc)
            proc.on('output', (text) => notify('process-output', { process: processId, stream: 'output', text }))
            proc.on('error', (text) => notify('process-output', { process: processId, stream: 'error', text }))
            proc.completion.then((result) => {
                notify('process-exit', { process: processId, result })
                processes.delete(processId)
            })
            return { process: processId }
        }
        if (method === 'process') {
            const [id, action, data] = args
            const proc = processes.get(id)
            if (!proc) return
            if (action === 'kill') proc.kill()
            else if (action === 'write' && typeof data === 'string') proc.write(data)
            else throw new Error('Unsupported process operation.')
            return
        }
        if (method === 'package') {
            const [name, ...values] = args
            if (!['install', 'installFromManifest'].includes(name)) throw new Error('Unsupported package operation.')
            const options = values.pop() || {}
            return pod.packages[name](...values, { ...options,
                onProgress: (message) => notify('package-progress', { request: requestId, text: String(message) }),
            })
        }
        if (method === 'request') {
            const result = await pod.request(...args)
            return { ...result, body: typeof result.body === 'string' ? result.body : new TextDecoder().decode(result.body) }
        }
        if (method === 'preview-script') return pod.setPreviewScript(args[0])
        if (method === 'terminal-create') {
            const id = ++nextTerminal
            const entry = { terminal: null, sink: null }
            terminals.set(id, entry)
            entry.terminal = pod.createTerminal({ Terminal: terminalSink(id), autoPrompt: args[0]?.autoPrompt !== false })
            entry.terminal.attach(document.getElementById('terminal-sink'))
            return { terminal: id }
        }
        if (method === 'terminal') {
            const [id, action, ...values] = args
            const entry = terminals.get(id)
            if (!entry || !TERMINAL_METHODS.has(action)) throw new Error('Unsupported terminal operation.')
            if (action === 'resize') {
                entry.sink.cols = Math.max(1, Math.min(1000, values[0] | 0))
                entry.sink.rows = Math.max(1, Math.min(1000, values[1] | 0))
                entry.sink.onSize?.()
            } else if (action === 'dispose') {
                entry.terminal.detach()
                terminals.delete(id)
            } else if (action === 'input') entry.sink.onInput?.(String(values[0]))
            else entry.terminal[action](...values)
            return
        }
        if (method === 'teardown') {
            await pod.teardown()
            pod = null
            return
        }
        throw new Error('Unsupported runtime operation.')
    }

    port.onmessage = async ({ data }) => {
        if (!data || data.type !== 'call' || !Number.isSafeInteger(data.id) || data.id < 1 || !Array.isArray(data.args)) return
        try { send({ type: 'result', id: data.id, value: await dispatch(data.method, data.args, data.id) }) }
        catch (error) { send({ type: 'result', id: data.id, error: String(error?.message || 'Runtime operation failed.').slice(0, 2000) }) }
    }
    port.start()
}
