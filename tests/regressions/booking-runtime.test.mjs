import { test } from 'node:test'
import assert from 'node:assert/strict'
import { createBookingSender, prepareBookingPayload } from '../../resources/js/lab/form-runtime/client.js'

test('Bangkok local time and numeric values become API payload', () => {
    const payload = prepareBookingPayload({ customer_name: 'มะลิ', phone: '0800000000', requested_at: '2030-01-01T12:00', party_size: '2' }, [], {})
    assert.equal(payload.core.requested_at, '2030-01-01T05:00:00.000Z')
    assert.equal(payload.core.party_size, 2)
})
test('timeout retries keep key, different answers get a new key, credentials omitted', async () => {
    const calls = []
    let fail = true
    const sender = createBookingSender('http://localhost:8889', 'fixture-form', async (url, init) => {
        calls.push({ url, init })
        if (fail) { fail = false; throw new Error('timeout') }
        return { ok: true, json: async () => ({ reference: 'saved', status: 'pending' }) }
    })
    const payload = { version: 1, core: { party_size: 2 }, answers: {}, website: '' }
    await assert.rejects(sender(payload))
    await sender(payload)
    await sender({ ...payload, core: { party_size: 3 } })
    const keys = calls.map(call => JSON.parse(call.init.body).idempotency_key)
    assert.equal(keys[0], keys[1])
    assert.notEqual(keys[1], keys[2])
    assert.equal(calls[0].init.credentials, 'omit')
})
test('API errors and malformed successes cannot become booking receipts', async () => {
    for (const response of [
        { ok: false, status: 422, json: async () => ({ message: 'Invalid fields', errors: { 'core.phone': ['Required'] } }) },
        { ok: true, status: 200, json: async () => ({}) },
    ]) {
        const sender = createBookingSender('http://localhost:8889', 'fixture-form', async () => response)
        await assert.rejects(sender({ version: 1, core: {}, answers: {} }))
    }
})
