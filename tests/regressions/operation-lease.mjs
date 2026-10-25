import assert from 'node:assert/strict'
import { mock } from 'node:test'
import { acquireBuildLease } from '../../resources/js/lab/lib/operationLease.js'

mock.timers.enable({ apis: ['setTimeout'] })
const states = []
let loseLease = false
const transport = async (_path, options) => {
    const body = JSON.parse(options.body)
    if (options.method === 'POST') return { uuid: crypto.randomUUID() }
    states.push(body.state)
    if (loseLease && body.state === 'active') throw new Error('Lease expired')
    return { state: body.state }
}
try {
    const lease = await acquireBuildLease('project', undefined, transport)
    let killed = false
    // The guest process receives this signal and kills its process on abort.
    lease.signal.addEventListener('abort', () => { killed = true })
    loseLease = true
    mock.timers.tick(60_000)
    await Promise.resolve(); await Promise.resolve()
    assert.equal(killed, true)
    assert.throws(() => lease.assertActive(), /Lease expired/)
    await lease.finish(true)
    assert.equal(states.at(-1), 'failed')

    loseLease = false
    const caller = new AbortController()
    const cancelled = await acquireBuildLease('project', caller.signal, transport)
    caller.abort()
    assert.equal(cancelled.signal.aborted, true)
    assert.throws(() => cancelled.assertActive(), { name: 'AbortError' })
    await cancelled.finish(true)
    assert.equal(states.at(-1), 'failed')

    const completed = await acquireBuildLease('project', undefined, transport)
    await completed.finish(true)
    assert.equal(states.at(-1), 'completed')
    const count = states.length
    mock.timers.tick(120_000)
    await Promise.resolve()
    assert.equal(states.length, count)
    console.log('PASS: build lease loss cancels running work, caller abort and finalization (8 checks).')
} finally { mock.timers.reset() }
