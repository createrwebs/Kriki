import { PROTOCOL, UUID, SESSION, FS_METHODS, isHandshake, previewUrl, isPreviewUrl } from '../guest/protocol'

function readContext() {
    const config = JSON.parse(document.getElementById('lab-runtime-config')?.textContent || 'null')
    const project = location.pathname.match(/^\/lab\/([a-f0-9-]{36})(?:\/|$)/i)?.[1]
        || JSON.parse(document.getElementById('lab-bootstrap')?.textContent
        || document.getElementById('lab-preview-bootstrap')?.textContent || 'null')?.uuid
    if (!config || !UUID.test(project || '')) throw new Error('A dedicated cookie-free runtime origin is required. Open a saved project or configure the runtime domain.')
    return { config, project }
}

export async function removePlatformRuntimeWorkers() {
    if (!navigator.serviceWorker) return
    for (const registration of await navigator.serviceWorker.getRegistrations()) {
        const scripts = [registration.active, registration.waiting, registration.installing].filter(Boolean)
        if (scripts.some((worker) => {
            const url = new URL(worker.scriptURL)
            return url.origin === window.location.origin && ['/__krikkit_lab_sw__.js', '/__deepthought_sw__.js'].includes(url.pathname)
        })) await registration.unregister()
    }
    const controller = navigator.serviceWorker.controller
    if (controller) {
        const url = new URL(controller.scriptURL)
        if (url.origin === location.origin && ['/__krikkit_lab_sw__.js', '/__deepthought_sw__.js'].includes(url.pathname)) {
            throw new Error('The legacy runtime worker has been removed. Reload this workspace to start the isolated runtime; save pending edits first.')
        }
    }
}

export async function bootGuestRuntime(options) {
    const { config, project } = readContext()
    const session = Array.from(crypto.getRandomValues(new Uint8Array(16)), (byte) => byte.toString(16).padStart(2, '0')).join('')
    const origin = `${config.scheme}//r-${session}${config.host_suffix}${config.port ? `:${config.port}` : ''}`
    const parsed = new URL(origin)
    if (!SESSION.test(session) || parsed.origin === location.origin || !['http:', 'https:'].includes(parsed.protocol)) throw new Error('Invalid runtime origin.')
    const frame = document.createElement('iframe')
    frame.title = 'Isolated Lab runtime'
    frame.hidden = true
    frame.sandbox = 'allow-scripts allow-same-origin'
    frame.allow = 'cross-origin-isolated'
    frame.referrerPolicy = 'no-referrer'
    const source = new URL('/__runtime-host__', origin)
    source.search = new URLSearchParams({ project, session, parent: location.origin }).toString()
    frame.src = source.href
    const channel = new MessageChannel()
    let nextId = 0
    let closed = false
    let instance = null
    const pending = new Map()
    const processes = new Map()
    const terminals = new Map()
    const earlyProcessEvents = new Map()
    const earlyTerminalEvents = new Map()

    function close(error = new Error('Runtime closed.')) {
        if (closed) return
        closed = true
        for (const item of pending.values()) { clearTimeout(item.timer); item.reject(error) }
        pending.clear()
        for (const proc of processes.values()) proc.finish({ stdout: '', stderr: error.message, exitCode: 1 })
        processes.clear()
        for (const terminal of terminals.values()) terminal.disposeLocal()
        terminals.clear()
        channel.port1.close()
        frame.remove()
    }

    function call(method, args = [], onProgress = null) {
        if (closed) return Promise.reject(new Error('Runtime closed.'))
        const id = ++nextId
        return new Promise((resolve, reject) => {
            const timer = setTimeout(() => { pending.delete(id); reject(new Error(`Runtime ${method} timed out.`)) }, 300_000)
            pending.set(id, { resolve, reject, timer, onProgress })
            try { channel.port1.postMessage({ type: 'call', id, method, args }) }
            catch (error) { clearTimeout(timer); pending.delete(id); reject(error) }
        })
    }

    function processEvent(data) {
        const proc = processes.get(data.process)
        if (!proc) {
            // A fast process can finish before its spawn response arrives.
            if (Number.isSafeInteger(data.process) && earlyProcessEvents.size < 64) {
                const events = earlyProcessEvents.get(data.process) || []
                if (events.length < 64) events.push(data)
                earlyProcessEvents.set(data.process, events)
            }
            return
        }
        if (data.type === 'process-output' && ['output', 'error'].includes(data.stream) && typeof data.text === 'string') proc.emit(data.stream, data.text.slice(0, 65536))
        if (data.type === 'process-exit' && Number.isInteger(data.result?.exitCode)) {
            proc.finish({ stdout: String(data.result.stdout || '').slice(-4_194_304), stderr: String(data.result.stderr || '').slice(-4_194_304), exitCode: data.result.exitCode })
            processes.delete(data.process)
        }
    }

    channel.port1.onmessage = ({ data }) => {
        if (closed || !data || typeof data !== 'object') return
        if (data.type === 'result') {
            const item = pending.get(data.id)
            if (!item) return
            pending.delete(data.id)
            clearTimeout(item.timer)
            if (typeof data.error === 'string') item.reject(new Error(data.error.slice(0, 2000)))
            else item.resolve(data.value)
        } else if (data.type === 'server-ready') {
            // Never let a guest result navigate the editor iframe to the platform.
            if (instance && isPreviewUrl(data.url, origin, instance, data.port)) options.onServerReady?.(data.port, data.url)
        } else if (['process-output', 'process-exit'].includes(data.type)) processEvent(data)
        else if (data.type === 'package-progress' && typeof data.text === 'string') pending.get(data.request)?.onProgress?.(data.text.slice(0, 4096))
        else if (data.type === 'terminal-data' && typeof data.text === 'string') {
            const terminal = terminals.get(data.terminal)
            if (terminal) terminal.xterm.write(data.text.slice(0, 65536))
            else if (Number.isSafeInteger(data.terminal) && earlyTerminalEvents.size < 16) {
                earlyTerminalEvents.set(data.terminal, `${earlyTerminalEvents.get(data.terminal) || ''}${data.text}`.slice(-65536))
            }
        }
    }
    channel.port1.start()

    try {
        await new Promise((resolve, reject) => {
            const timer = setTimeout(() => { window.removeEventListener('message', ready); reject(new Error('Isolated runtime host did not respond.')) }, 30_000)
            function ready(event) {
                if (!isHandshake(event, { source: frame.contentWindow, origin, project, session, type: 'ready' })) return
                clearTimeout(timer)
                window.removeEventListener('message', ready)
                frame.contentWindow.postMessage({ protocol: PROTOCOL, type: 'connect', project, session }, origin, [channel.port2])
                resolve()
            }
            window.addEventListener('message', ready)
            document.body.appendChild(frame)
        })
        const result = await call('boot', [{ files: options.files, workdir: options.workdir, env: options.env }])
        if (!/^lab[a-zA-Z0-9_-]+$/.test(result?.instance)) throw new Error('Invalid runtime instance.')
        instance = result.instance
    } catch (error) { close(error); throw error }

    const fs = Object.fromEntries([...FS_METHODS].map((name) => [name, (...args) => call('fs', [name, ...args])]))
    const packages = Object.fromEntries(['install', 'installFromManifest'].map((name) => [name, (...args) => {
        const flags = args.pop() || {}
        const { onProgress, signal, ...serializable } = flags
        return call('package', [name, ...args, serializable], onProgress)
    }]))
    return {
        fs, packages, instanceId: instance, origin,
        port: (port) => previewUrl(origin, instance, port),
        setPreviewScript: (script) => call('preview-script', [script]),
        request: (...args) => call('request', args),
        async spawn(cmd, args = [], opts = {}) {
            const { signal, ...serializable } = opts
            const { process: id } = await call('spawn', [cmd, args, serializable])
            if (!Number.isSafeInteger(id) || processes.has(id)) throw new Error('Invalid process response.')
            const listeners = new Map()
            let resolveCompletion
            let exited = false
            const completion = new Promise((resolve) => { resolveCompletion = resolve })
            const controller = new AbortController()
            const proc = {
                completion, signal: controller.signal,
                get exited() { return exited },
                on(event, fn) { if (!listeners.has(event)) listeners.set(event, new Set()); listeners.get(event).add(fn); return proc },
                emit(event, value) { for (const fn of listeners.get(event) || []) fn(value) },
                finish(result) { if (exited) return; exited = true; signal?.removeEventListener('abort', abort); proc.emit('exit', result.exitCode); resolveCompletion(result) },
                write: (text) => { void call('process', [id, 'write', String(text)]).catch(() => {}) },
                kill: () => { controller.abort(); void call('process', [id, 'kill']).catch(() => {}) },
            }
            const abort = () => proc.kill()
            processes.set(id, proc)
            const early = earlyProcessEvents.get(id) || []
            earlyProcessEvents.delete(id)
            // Let spawn callers register output handlers before replay.
            setTimeout(() => { for (const event of early) processEvent(event) }, 0)
            if (signal?.aborted) abort()
            else signal?.addEventListener('abort', abort, { once: true })
            return proc
        },
        async createTerminal(opts) {
            const { terminal: id } = await call('terminal-create', [{ autoPrompt: opts.autoPrompt }])
            if (!Number.isSafeInteger(id) || terminals.has(id)) throw new Error('Invalid terminal response.')
            const xterm = new opts.Terminal({ theme: opts.theme, cursorBlink: true, fontSize: 13, fontFamily: 'Consolas, monospace' })
            const fitAddon = opts.FitAddon ? new opts.FitAddon() : null
            if (fitAddon) xterm.loadAddon(fitAddon)
            const send = (method, ...values) => { void call('terminal', [id, method, ...values]).catch(() => {}) }
            const input = xterm.onData((data) => send('input', data))
            const resize = xterm.onResize(({ cols, rows }) => send('resize', cols, rows))
            const terminal = {
                xterm, fitAddon,
                attach(host) { xterm.open(host); terminal.fit(); xterm.write(earlyTerminalEvents.get(id) || ''); earlyTerminalEvents.delete(id) },
                fit() { fitAddon?.fit(); send('resize', xterm.cols, xterm.rows) },
                focus: () => xterm.focus(),
                write: (text) => xterm.write(String(text)), writeln: (text) => xterm.writeln(String(text)),
                showPrompt: () => send('showPrompt'), input: (text) => send('input', text),
                disposeLocal() { input.dispose(); resize.dispose(); xterm.dispose() },
                dispose() { send('dispose'); terminal.disposeLocal(); terminals.delete(id) },
            }
            terminals.set(id, terminal)
            return terminal
        },
        async teardown() { if (closed) return; try { await call('teardown') } finally { close() } },
    }
}
