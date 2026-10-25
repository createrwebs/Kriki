import { formsRequest } from './forms.js'
export async function acquireBuildLease(projectUuid, signal, transport = formsRequest) {
    if (!projectUuid) throw new Error('Open a saved project before building.')
    const root = `/lab/${encodeURIComponent(projectUuid)}/operations`
    const lease = await transport(root, { method: 'POST', signal, body: JSON.stringify({ operation: 'build', operation_key: crypto.randomUUID() }) })
    const controller = new AbortController()
    const cancel = () => controller.abort(signal?.reason)
    signal?.addEventListener('abort', cancel, { once: true })
    if (signal?.aborted) cancel()
    let stopped = false, failure = null, heartbeat = null
    const update = state => transport(`${root}/${encodeURIComponent(lease.uuid)}`, { method: 'PATCH', body: JSON.stringify({ state }) })
    const renew = async () => {
        try { await update('active') } catch (error) {
            if (!stopped) { failure = error; controller.abort(error) }
        }
        if (!stopped && !failure) heartbeat = setTimeout(renew, 60_000)
    }
    heartbeat = setTimeout(renew, 60_000)
    return {
        signal: controller.signal,
        assertActive() { if (failure) throw failure; controller.signal.throwIfAborted() },
        async finish(successful) {
            stopped = true
            clearTimeout(heartbeat)
            signal?.removeEventListener('abort', cancel)
            await update(successful && !failure && !controller.signal.aborted ? 'completed' : 'failed')
        },
    }
}
