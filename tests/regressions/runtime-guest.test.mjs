import test from 'node:test'
import assert from 'node:assert/strict'
import { isHandshake, previewUrl, isPreviewUrl, PROTOCOL, FS_METHODS, TERMINAL_METHODS } from '../../resources/js/lab/guest/protocol.js'

test('runtime handshake binds exact window, origin, project and session', () => {
    const source = {}
    const binding = { source, origin: 'http://r-session.runtime.localhost:8892', project: '9e25fea9-c4bb-476f-9a87-f645aa536600', session: 'a'.repeat(32), type: 'ready' }
    const event = { source, origin: binding.origin, data: { protocol: PROTOCOL, type: 'ready', project: binding.project, session: binding.session } }
    assert.equal(isHandshake(event, binding), true)
    for (const alternate of [{ source: {} }, { origin: 'null' }, { origin: 'http://localhost:8892' }, { data: { ...event.data, project: '23a079d0-bf80-4c9b-b498-791cc8a408fe' } }, { data: { ...event.data, session: 'b'.repeat(32) } }, { data: { ...event.data, type: 'connect' } }, { data: { ...event.data, protocol: 'other' } }]) {
        assert.equal(isHandshake({ ...event, ...alternate }, binding), false)
    }
})

test('guest notifications cannot substitute platform, sibling or arbitrary preview URLs', () => {
    const origin = 'https://r-session.guest.example.net'
    const expected = previewUrl(origin, 'lab1234', 5173)
    assert.equal(expected, `${origin}/__virtual__/lab1234/5173`)
    assert.equal(isPreviewUrl(`${expected}/`, origin, 'lab1234', 5173), true)
    for (const value of ['https://app.example.com/lab', `${origin}/login`, `${origin}/__virtual__/labother/5173`, `${expected}?redirect=1`, `${expected}#fragment`, 'javascript:alert(1)', `${origin}/__virtual__/lab1234/3000`]) assert.equal(isPreviewUrl(value, origin, 'lab1234', 5173), false)
    for (const port of [0, -1, 65536, 3.5, '5173']) assert.equal(previewUrl(origin, 'lab1234', port), null)
    assert.equal(previewUrl(origin, '../owner', 5173), null)
    assert.equal(FS_METHODS.has('constructor'), false)
    assert.equal(TERMINAL_METHODS.has('__proto__'), false)
})
