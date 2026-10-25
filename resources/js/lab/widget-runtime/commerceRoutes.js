const UUID = /^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i
export function commerceRoute(baseRoute, path) {
    const prefix = baseRoute.replace(/\/$/, '')
    if (path === (prefix || '/')) return { view: 'catalog', orderUuid: null }
    if (path === `${prefix}/cart`) return { view: 'cart', orderUuid: null }
    if (path === `${prefix}/checkout`) return { view: 'checkout', orderUuid: null }
    const order = path?.startsWith(`${prefix}/orders/`) ? path.slice(`${prefix}/orders/`.length) : ''
    return UUID.test(order) ? { view: 'order', orderUuid: order } : null
}
export function commercePath(baseRoute, view, uuid = '') {
    const prefix = baseRoute.replace(/\/$/, '')
    if (view === 'catalog') return prefix || '/'
    if (view === 'order') {
        if (!UUID.test(uuid)) throw new Error('Invalid order route')
        return `${prefix}/orders/${uuid}`
    }
    if (!['cart','checkout'].includes(view)) throw new Error('Invalid store route')
    return `${prefix}/${view}`
}
