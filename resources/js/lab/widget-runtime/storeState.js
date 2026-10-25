const UUID = /^[a-f0-9]{8}-[a-f0-9]{4}-[1-5][a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}$/i
const keyFor = (kind, moduleUuid) => `krikkit:${kind}:v1:${moduleUuid}`

export function readCart(storage, moduleUuid) {
    try {
        const value = JSON.parse(storage.getItem(keyFor('cart', moduleUuid)) || '{}')
        if (!UUID.test(moduleUuid) || !value || typeof value !== 'object' || Array.isArray(value) || Object.keys(value).length > 25) return {}
        return Object.fromEntries(Object.entries(value).filter(([id, quantity]) => UUID.test(id) && Number.isInteger(quantity) && quantity >= 1 && quantity <= 99))
    } catch { return {} }
}

export function saveCart(storage, moduleUuid, cart) {
    try { if (UUID.test(moduleUuid)) storage.setItem(keyFor('cart', moduleUuid), JSON.stringify(cart)) } catch { /* Browsing remains usable when storage is unavailable. */ }
}

export function readOrderAccess(storage, moduleUuid, now = Date.now()) {
    try {
        const value = JSON.parse(storage.getItem(keyFor('order', moduleUuid)) || 'null')
        if (!UUID.test(moduleUuid) || !value || !UUID.test(value.uuid) || !/^[a-f0-9]{64}$/.test(value.token)
            || !Number.isFinite(value.expiresAt) || value.expiresAt <= now || value.expiresAt > now + 24*60*60*1000) return null
        return { uuid: value.uuid, token: value.token, expiresAt: value.expiresAt }
    } catch { return null }
}

export function saveOrderAccess(storage, moduleUuid, order, token) {
    try {
        if (!order) { storage.removeItem(keyFor('order', moduleUuid)); return }
        if (UUID.test(moduleUuid) && UUID.test(order.uuid) && /^[a-f0-9]{64}$/.test(token)) storage.setItem(keyFor('order', moduleUuid), JSON.stringify({ uuid: order.uuid, token, expiresAt: Date.parse(order.created_at) + 24*60*60*1000 }))
    } catch { /* Never fall back to a URL or persistent token store. */ }
}
