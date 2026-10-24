import test from 'node:test'
import assert from 'node:assert/strict'
import vm from 'node:vm'
import * as bridge from '../../resources/js/lab/lib/previewChromeBridge.js'

const project = '10000000-0000-4000-8000-000000000001'
const platform = 'http://localhost:8892'
const guestOrigin = 'http://127.0.0.1:8893'
const marker = bridge.PREVIEW_CHROME_SOURCE
globalThis.window = { location: { href: `${platform}/lab`, origin: platform } }

function frame() {
    const sent = []
    return {
        src: `${guestOrigin}/__preview__/one/5173/`,
        contentWindow: { postMessage: (...args) => sent.push(args) },
        sent,
    }
}

function reply(iframe, packet) {
    return { source: iframe.contentWindow, origin: guestOrigin, data: { ...packet, type: 'location', path: '/' } }
}

test('public marker alone never authorizes a preview event', () => {
    assert.equal(bridge.isPreviewChromeMessage({ source: marker }, frame()), false)
})

test('host binds exact window, origin, project and token; reload revokes old replies', () => {
    const iframe = frame()
    assert.equal(bridge.postToPreview(iframe, { type: 'back' }), false)
    assert.equal(bridge.connectPreviewChrome(iframe, project), true)
    const [connection, target] = iframe.sent[0]
    assert.equal(target, guestOrigin)
    assert.equal(connection.projectUuid, project)
    assert.equal(connection.type, 'bridge-connect')
    assert.match(connection.token, /^[\da-f-]{36}$/)
    const event = reply(iframe, connection)
    assert.equal(bridge.isPreviewChromeMessage(event, iframe), true)
    for (const forged of [
        { ...event, source: frame().contentWindow },
        { ...event, origin: platform },
        { ...event, origin: 'null' },
        { ...event, data: { ...event.data, projectUuid: `${project}x` } },
        { ...event, data: { ...event.data, token: `${connection.token}x` } },
        { ...event, data: { source: marker, type: 'cover-painted' } },
    ]) assert.equal(bridge.isPreviewChromeMessage(forged, iframe), false)
    assert.equal(bridge.postToPreview(iframe, { type: 'back', source: 'fake', token: 'fake', projectUuid: 'fake' }), true)
    assert.equal(iframe.sent.at(-1)[1], guestOrigin)
    assert.equal(iframe.sent.at(-1)[0].token, connection.token)
    assert.equal(iframe.sent.at(-1)[0].projectUuid, project)
    assert.equal(iframe.sent.at(-1)[0].source, marker)
    bridge.connectPreviewChrome(iframe, project)
    assert.notEqual(iframe.sent.at(-1)[0].token, connection.token)
    assert.equal(bridge.isPreviewChromeMessage(event, iframe), false)
    const latest = reply(iframe, iframe.sent.at(-1)[0])
    iframe.src += 'another'
    assert.equal(bridge.isPreviewChromeMessage(latest, iframe), false)
    assert.equal(bridge.postToPreview(iframe, { type: 'back' }), false)
})

test('host fails closed for missing project, frame or unsafe URL', () => {
    assert.equal(bridge.connectPreviewChrome(null, project), false)
    for (const src of ['about:blank', 'data:text/html,hello', 'javascript:void(0)', 'https://user:pass@example.com/']) {
        const iframe = frame()
        iframe.src = src
        assert.equal(bridge.connectPreviewChrome(iframe, project), false)
        assert.equal(iframe.sent.length, 0)
    }
    assert.equal(bridge.connectPreviewChrome(frame(), null), false)
})

function guest({ overlay = false } = {}) {
    const handlers = new Map()
    const sent = []
    const intervals = []
    const parent = { postMessage: (...args) => sent.push(args) }
    let backs = 0
    const history = { state: null, pushState() {}, replaceState() {}, back() { backs++ }, forward() {} }
    const document = {
        documentElement: { getAttribute() { return null } },
        body: { getAttribute() { return null }, innerText: '', children: [] }, images: [], fonts: null,
        addEventListener() {}, querySelector(selector) {
            return overlay && selector === 'vite-error-overlay'
                ? { shadowRoot: { querySelector: () => ({ textContent: 'Fixture build failed' }) } } : null
        }, querySelectorAll() { return [] },
        getElementById() { return null },
    }
    const window = {
        parent, innerWidth: 1000, innerHeight: 800,
        addEventListener(type, callback) { handlers.set(type, callback) },
    }
    const context = vm.createContext({
        window, parent, history, document, URL, console: { error() {} },
        location: { pathname: '/', search: '', hash: '', origin: guestOrigin },
        setTimeout() {}, setInterval(callback) { intervals.push(callback) }, MutationObserver: class { observe() {} },
    })
    vm.runInContext(bridge.createPreviewChromeScript(platform), context)
    return { sent, parent, intervals, backs: () => backs, receive: (event) => handlers.get('message')(event) }
}

test('guest validates parent and origin before handshake and bound commands', () => {
    const child = guest()
    const iframe = frame()
    bridge.connectPreviewChrome(iframe, project)
    const packet = iframe.sent[0][0]
    assert.equal(child.sent.length, 0, 'no wildcard or unbound boot events')
    for (const event of [
        { source: {}, origin: platform, data: packet },
        { source: child.parent, origin: guestOrigin, data: packet },
        { source: child.parent, origin: platform, data: { ...packet, token: '' } },
    ]) child.receive(event)
    assert.equal(child.sent.length, 0)
    child.receive({ source: child.parent, origin: platform, data: { ...packet, type: 'back' } })
    assert.equal(child.backs(), 0)
    child.receive({ source: child.parent, origin: platform, data: packet })
    assert.equal(child.sent[0][0].type, 'location')
    assert.equal(child.sent[0][0].projectUuid, project)
    assert.equal(child.sent[0][0].token, packet.token)
    assert.ok(child.sent.every(([, origin]) => origin === platform))
    const command = { ...packet, type: 'back' }
    for (const event of [
        { source: {}, origin: platform, data: command },
        { source: child.parent, origin: guestOrigin, data: command },
        { source: child.parent, origin: platform, data: { ...command, projectUuid: 'another' } },
        { source: child.parent, origin: platform, data: { ...command, token: 'stale' } },
    ]) child.receive(event)
    assert.equal(child.backs(), 0)
    child.receive({ source: child.parent, origin: platform, data: command })
    assert.equal(child.backs(), 1)
})

test('build errors before handshake are delivered after paint and survive overlay dedupe', () => {
    const child = guest({ overlay: true })
    child.intervals.forEach((tick) => tick())
    assert.equal(child.sent.length, 0)
    const iframe = frame()
    bridge.connectPreviewChrome(iframe, project)
    child.receive({ source: child.parent, origin: platform, data: iframe.sent[0][0] })
    child.intervals.forEach((tick) => tick())
    const types = child.sent.map(([data]) => data.type)
    assert.equal(types.filter(type => type === 'guest-console-error').length, 1)
    assert.ok(types.indexOf('guest-console-error') > types.indexOf('cover-painted'))
})
