import React, { useEffect, useRef, useState } from 'react'
import { readCart, saveCart, readOrderAccess, saveOrderAccess } from './storeState.js'
import { commercePath } from './commerceRoutes.js'

function formatBaht(satang) {
    const num = Number(satang || 0) / 100
    return num.toLocaleString('th-TH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ฿'
}

const ORDER_STATUS_LABELS = {
    awaiting_payment: 'รอชำระเงินผ่าน PromptPay',
    pending_review: 'ส่งสลิปแล้ว • รอร้านค้าตรวจสอบ',
    paid: 'ชำระเงินสำเร็จ (อนุมัติแล้ว)',
    rejected: 'สลิปไม่ผ่านการตรวจสอบ กรุณาแนบสลิปใหม่',
    cancelled: 'ยกเลิกคำสั่งซื้อ',
}
const ORDER_ACCESS_DENIED = 'ไม่พบสิทธิ์ดูคำสั่งซื้อนี้ สิทธิ์อาจหมดอายุหรือถูกเพิกถอนแล้ว กรุณาติดต่อร้านค้า'
const deniedAccess = response => [401, 403, 404].includes(response.status)

function browserStore(name) { try { return window[name] } catch { return null } }

export default function StorefrontWidget({ apiOrigin, moduleUuid, baseRoute = null, routeView = 'catalog', routeOrderUuid = null }) {
    const [storefront, setStorefront] = useState(null)
    const [loading, setLoading] = useState(true)
    const [error, setError] = useState('')
    const [cart, setCart] = useState(() => readCart(browserStore('localStorage'), moduleUuid))
    const [restoredAccess] = useState(() => readOrderAccess(browserStore('sessionStorage'), moduleUuid))
    const [checkoutForm, setCheckoutForm] = useState({ customer_name: '', customer_email: '', customer_phone: '', note: '' })
    const [orderKey, setOrderKey] = useState(() => crypto.randomUUID())
    const [submittingOrder, setSubmittingOrder] = useState(false)
    const [quote, setQuote] = useState(null)
    const [pendingCreation, setPendingCreation] = useState(null)
    const orderRunning = useRef(false)
    const [activeOrder, setActiveOrder] = useState(restoredAccess ? { uuid: restoredAccess.uuid } : null)
    const [orderToken, setOrderToken] = useState(restoredAccess?.token || '')
    const [slipForm, setSlipForm] = useState({ payer_name: '', payer_phone: '', transfer_ref: '', claimed_transferred_at: '', slip_data_url: '' })
    const [slipKey, setSlipKey] = useState(() => crypto.randomUUID())
    const [submittingSlip, setSubmittingSlip] = useState(false)
    const [slipFeedback, setSlipFeedback] = useState('')
    const accessGeneration = useRef(0)
    const orderReadSequence = useRef(0)

    function forgetOrderAccess(message = '') {
        ++accessGeneration.current
        ++orderReadSequence.current
        setActiveOrder(null)
        setOrderToken('')
        setSlipForm({ payer_name: '', payer_phone: '', transfer_ref: '', claimed_transferred_at: '', slip_data_url: '' })
        setSlipFeedback('')
        setSubmittingSlip(false)
        setError(message)
        saveOrderAccess(browserStore('sessionStorage'), moduleUuid, null, '')
    }

    async function loadStorefront() {
        setLoading(true); setError('')
        try {
            const response = await fetch(`${apiOrigin}/api/lab-widgets/${encodeURIComponent(moduleUuid)}/storefront`, {
                method: 'GET', credentials: 'omit', headers: { Accept: 'application/json' },
            })
            const data = await response.json()
            if (!response.ok) throw new Error(data.message || 'โหลดรายการสินค้าไม่สำเร็จ')
            setStorefront(data)
        } catch (err) {
            setError(err.message || 'เชื่อมต่อร้านค้าไม่ได้ กรุณาลองอีกครั้ง')
        } finally {
            setLoading(false)
        }
    }

    useEffect(() => { loadStorefront() }, [apiOrigin, moduleUuid])
    useEffect(() => { saveCart(browserStore('localStorage'), moduleUuid, cart) }, [moduleUuid, cart])
    useEffect(() => { if ((restoredAccess || activeOrder) && (baseRoute === null || routeView === 'order') && (!routeOrderUuid || activeOrder?.uuid === routeOrderUuid)) void refreshOrderStatus() }, [apiOrigin, moduleUuid, routeView, routeOrderUuid])

    function navigate(view, order = '') {
        if (baseRoute === null) return
        window.history.pushState({}, '', commercePath(baseRoute, view, order))
        window.dispatchEvent(new PopStateEvent('popstate'))
    }

    function adjustCart(productUuid, delta) {
        setCart(prev => {
            const nextQty = (prev[productUuid] || 0) + delta
            const copy = { ...prev }
            if (nextQty <= 0) delete copy[productUuid]
            else copy[productUuid] = Math.min(99, nextQty)
            return copy
        })
        setOrderKey(crypto.randomUUID())
        setQuote(null)
    }

    const products = storefront?.products || []
    const cartLines = products
        .filter(p => (cart[p.uuid] || 0) > 0)
        .map(p => ({ ...p, quantity: cart[p.uuid], line_satang: p.price_satang * cart[p.uuid] }))
    const displayedCartLines = quote ? quote.items.map(line => ({
        uuid: line.product_uuid, title: line.title, quantity: line.quantity, line_satang: line.line_total_satang,
    })) : cartLines
    const totalSatang = quote ? quote.total_satang : cartLines.reduce((sum, item) => sum + item.line_satang, 0)

    async function placeOrder(event) {
        event.preventDefault()
        if (cartLines.length === 0 || orderRunning.current) return
        orderRunning.current = true
        setSubmittingOrder(true); setError('')
        try {
            if (!quote) {
                const response = await fetch(`${apiOrigin}/api/lab-widgets/${encodeURIComponent(moduleUuid)}/quotes`, {
                    method: 'POST', credentials: 'omit', headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                    body: JSON.stringify({ items: cartLines.map(item => ({ product_uuid: item.uuid, quantity: item.quantity })) }),
                })
                const data = await response.json()
                if (!response.ok) throw new Error(data.message || 'ตรวจราคาไม่สำเร็จ')
                setQuote(data.quote)
                return
            }
            if (!pendingCreation && Date.parse(quote.expires_at) <= Date.now()) { setQuote(null); throw new Error('ราคาที่ตรวจไว้หมดอายุ กรุณาตรวจราคาอีกครั้งก่อนยืนยัน') }
            const creation = pendingCreation || {
                idempotency_key: orderKey,
                quote_uuid: quote.quote_uuid,
                customer_name: checkoutForm.customer_name,
                customer_email: checkoutForm.customer_email,
                customer_phone: checkoutForm.customer_phone,
                note: checkoutForm.note,
                items: cartLines.map(item => ({ product_uuid: item.uuid, quantity: item.quantity })),
            }
            setPendingCreation(creation)
            const response = await fetch(`${apiOrigin}/api/lab-widgets/${encodeURIComponent(moduleUuid)}/orders`, {
                method: 'POST', credentials: 'omit',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body: JSON.stringify(creation),
            })
            const data = await response.json()
            if ([400,409,422].includes(response.status)) setPendingCreation(null)
            if (response.status === 409) { setQuote(null); setOrderKey(crypto.randomUUID()) }
            if (!response.ok) throw new Error(data.message || 'ไม่สามารถสร้างคำสั่งซื้อได้')
            ++accessGeneration.current
            ++orderReadSequence.current
            setActiveOrder(data.order)
            setOrderToken(data.access_token || '')
            saveOrderAccess(browserStore('sessionStorage'), moduleUuid, data.order, data.access_token)
            setSlipForm({ payer_name: checkoutForm.customer_name, payer_phone: checkoutForm.customer_phone, transfer_ref: '', claimed_transferred_at: '', slip_data_url: '' })
            setSlipKey(crypto.randomUUID())
            setCart({})
            setQuote(null)
            setPendingCreation(null)
            navigate('order', data.order.uuid)
        } catch (err) {
            setError(err.message || 'ไม่สามารถสร้างคำสั่งซื้อได้')
        } finally {
            setSubmittingOrder(false)
            orderRunning.current = false
        }
    }

    async function refreshOrderStatus() {
        if (!activeOrder?.uuid || !orderToken || (routeOrderUuid && activeOrder.uuid !== routeOrderUuid)) return
        const generation = accessGeneration.current
        const sequence = ++orderReadSequence.current
        const current = () => generation === accessGeneration.current && sequence === orderReadSequence.current
        setError('')
        try {
            const response = await fetch(`${apiOrigin}/api/lab-widgets/${encodeURIComponent(moduleUuid)}/orders/${encodeURIComponent(activeOrder.uuid)}`, {
                method: 'GET', credentials: 'omit',
                headers: { Accept: 'application/json', 'X-Krikkit-Order-Token': orderToken },
            })
            if (!current()) return
            if (deniedAccess(response)) { forgetOrderAccess(ORDER_ACCESS_DENIED); return }
            const data = await response.json()
            if (!current()) return
            if (!response.ok) throw new Error(data.message || 'ไม่สามารถดึงสถานะคำสั่งซื้อได้')
            setActiveOrder(data.order)
            if (data.order.status !== 'pending_review') setSlipFeedback('')
        } catch (err) {
            if (current()) setError(err.message || 'ตรวจสอบสถานะไม่ได้')
        }
    }

    function handleSlipFile(event) {
        const file = event.target.files?.[0]
        if (!file) return
        const reader = new FileReader()
        const generation = accessGeneration.current
        reader.onload = () => {
            if (generation === accessGeneration.current && typeof reader.result === 'string') {
                setSlipForm(prev => ({ ...prev, slip_data_url: reader.result }))
                setSlipKey(crypto.randomUUID())
            }
        }
        reader.readAsDataURL(file)
    }

    async function submitSlip(event) {
        event.preventDefault()
        if (!activeOrder?.uuid || !orderToken) return
        const generation = accessGeneration.current
        if (!slipForm.slip_data_url) {
            setError('กรุณาเลือกรูปสลิปการโอนเงิน (PNG, JPEG หรือ WebP)')
            return
        }
        setSubmittingSlip(true); setError(''); setSlipFeedback('')
        try {
            const response = await fetch(`${apiOrigin}/api/lab-widgets/${encodeURIComponent(moduleUuid)}/orders/${encodeURIComponent(activeOrder.uuid)}/slips`, {
                method: 'POST', credentials: 'omit',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-Krikkit-Order-Token': orderToken,
                },
                body: JSON.stringify({
                    idempotency_key: slipKey,
                    payer_name: slipForm.payer_name,
                    payer_phone: slipForm.payer_phone,
                    amount_satang: activeOrder.total_satang,
                    transfer_ref: slipForm.transfer_ref,
                    claimed_transferred_at: slipForm.claimed_transferred_at,
                    slip_data_url: slipForm.slip_data_url,
                }),
            })
            if (generation !== accessGeneration.current) return
            if (deniedAccess(response)) { forgetOrderAccess(ORDER_ACCESS_DENIED); return }
            const data = await response.json()
            if (generation !== accessGeneration.current) return
            if (!response.ok) throw new Error(data.message || 'ส่งสลิปไม่สำเร็จ')
            setActiveOrder(previous => ({ ...previous, status: data.slip.order_status }))
            setSlipKey(crypto.randomUUID())
            setSlipForm(previous => ({ ...previous, transfer_ref: '', claimed_transferred_at: '', slip_data_url: '' }))
            setSlipFeedback('ส่งหลักฐานการชำระเงินเรียบร้อยแล้ว ร้านค้ากำลังตรวจสอบสลิปของคุณ')
            await refreshOrderStatus()
        } catch (err) {
            if (generation === accessGeneration.current) setError(err.message || 'ส่งสลิปไม่สำเร็จ กรุณาลองอีกครั้ง')
        } finally {
            if (generation === accessGeneration.current) setSubmittingSlip(false)
        }
    }

    return <section className="kw-contact kw-storefront" aria-label="ร้านค้าและตะกร้าสินค้า">
        {baseRoute !== null ? <nav aria-label="เมนูร้านค้า">
            <button type="button" aria-current={routeView === 'catalog' ? 'page' : undefined} disabled={!!pendingCreation} onClick={() => navigate('catalog')}>เลือกสินค้า</button>
            <button type="button" aria-current={['cart', 'checkout'].includes(routeView) ? 'page' : undefined} disabled={!!pendingCreation} onClick={() => navigate('cart')}>ตะกร้า ({cartLines.reduce((sum,line) => sum + line.quantity, 0)})</button>
            {activeOrder?.reference ? <button type="button" aria-current={routeView === 'order' ? 'page' : undefined} disabled={!!pendingCreation} onClick={() => navigate('order', activeOrder.uuid)}>คำสั่งซื้อล่าสุด</button> : null}
        </nav> : null}
        <div className="kw-heading-row">
            <h2>สินค้าและบริการ</h2>
            {activeOrder && (baseRoute === null || routeView === 'order') ? (
                <button type="button" onClick={() => { forgetOrderAccess(); navigate('catalog') }}>
                    เลือกซื้อสินค้าต่อ
                </button>
            ) : null}
        </div>
        {error ? <p role="alert">{error}</p> : null}

        {loading ? <p role="status">กำลังโหลดรายการสินค้า…</p> : activeOrder && (baseRoute === null || (routeView === 'order' && activeOrder.uuid === routeOrderUuid)) ? (
            <div className="kw-order-view">
                <div className="kw-order-summary">
                    <div className="kw-heading-row">
                        <strong>คำสั่งซื้อ #{activeOrder.reference}</strong>
                        <button type="button" onClick={refreshOrderStatus}>เช็คสถานะล่าสุด</button>
                    </div>
                    <p>สถานะ: <strong>{ORDER_STATUS_LABELS[activeOrder.status] || activeOrder.status}</strong></p>
                    <p>ยอดชำระสุทธิ: <strong>{formatBaht(activeOrder.total_satang)}</strong></p>
                    {activeOrder.latest_slip?.review_reason ? (
                        <p role={activeOrder.status === 'rejected' ? 'alert' : 'status'}>หมายเหตุจากร้านค้า: {activeOrder.latest_slip.review_reason}</p>
                    ) : null}
                </div>

                {activeOrder.promptpay && !['paid', 'cancelled'].includes(activeOrder.status) ? (
                    <div className="kw-promptpay">
                        <h3>สแกนชำระเงินด้วย PromptPay</h3>
                        <p>ชื่อบัญชี: <strong>{activeOrder.promptpay.payee_label}</strong> ({activeOrder.promptpay.masked_recipient})</p>
                        {activeOrder.promptpay.instructions ? <p>{activeOrder.promptpay.instructions}</p> : null}
                        {activeOrder.promptpay.qr_svg ? (
                            <div
                                aria-label="PromptPay QR Code" className="kw-promptpay-code"

                                dangerouslySetInnerHTML={{ __html: activeOrder.promptpay.qr_svg }}
                            />
                        ) : null}
                    </div>
                ) : null}

                {slipFeedback ? <p role="status">{slipFeedback}</p> : null}

                {['awaiting_payment', 'rejected'].includes(activeOrder.status) ? (
                    <form onSubmit={submitSlip} className="kw-slip-form">
                        <h3>แจ้งชำระเงิน / แนบสลิปโอนเงิน</h3>
                        <label>ชื่อผู้โอน<input required maxLength={120} value={slipForm.payer_name} onChange={e => setSlipForm({ ...slipForm, payer_name: e.target.value })} /></label>
                        <label>เบอร์โทรติดต่อ<input maxLength={40} value={slipForm.payer_phone} onChange={e => setSlipForm({ ...slipForm, payer_phone: e.target.value })} /></label>
                        <label>เลขอ้างอิงสลิป / เวลาที่โอน (ถ้ามี)<input maxLength={120} value={slipForm.transfer_ref} onChange={e => setSlipForm({ ...slipForm, transfer_ref: e.target.value })} placeholder="เช่น 202610171230 / โอนเวลา 12:30 น." /></label>
                        <label>แนบรูปภาพสลิป (PNG / JPG / WebP)
                            <input type="file" accept="image/png,image/jpeg,image/webp" onChange={handleSlipFile} />
                        </label>
                        <button type="submit" disabled={submittingSlip}>{submittingSlip ? 'กำลังส่งสลิป…' : 'ยืนยันการแจ้งชำระเงิน'}</button>
                    </form>
                ) : null}
            </div>
        ) : baseRoute !== null && routeView === 'order' ? <p role="status">ไม่พบสิทธิ์ดูคำสั่งซื้อนี้ในเบราว์เซอร์ กรุณาเปิดจากแท็บที่ใช้สั่งซื้อ สิทธิ์อาจหมดอายุหรือถูกเพิกถอนแล้ว</p> : (
            <div>
                {baseRoute !== null && routeView !== 'catalog' ? null : products.length === 0 ? (
                    <p>ยังไม่มีสินค้าเปิดจำหน่ายในขณะนี้</p>
                ) : (
                    <div className="kw-product-grid">
                        {products.map(product => {
                            const qty = cart[product.uuid] || 0
                            return (
                                <article key={product.uuid} className="kw-product-card">
                                    <div>
                                        <h3>{product.title}</h3>
                                        <p className="kw-sku">SKU: {product.sku}</p>
                                        {product.description ? <p>{product.description}</p> : null}
                                        <strong>{formatBaht(product.price_satang)}</strong>
                                    </div>
                                    <div className="kw-quantity">
                                        {qty > 0 ? (
                                            <>
                                                <button type="button" disabled={submittingOrder || !!pendingCreation} onClick={() => adjustCart(product.uuid, -1)} aria-label={`ลดจำนวน ${product.title}`}>-</button>
                                                <span aria-live="polite">{qty}</span>
                                                <button type="button" disabled={submittingOrder || !!pendingCreation} onClick={() => adjustCart(product.uuid, 1)} aria-label={`เพิ่มจำนวน ${product.title}`}>+</button>
                                            </>
                                        ) : (
                                            <button type="button" disabled={submittingOrder || !!pendingCreation} onClick={() => adjustCart(product.uuid, 1)}>หยิบใส่ตะกร้า</button>
                                        )}
                                    </div>
                                </article>
                            )
                        })}
                    </div>
                )}

                {baseRoute !== null && routeView !== 'catalog' && cartLines.length === 0 ? <p role="status">ตะกร้าว่าง เลือกสินค้าก่อนชำระเงิน</p> : null}
                {cartLines.length > 0 && (baseRoute === null || routeView !== 'catalog') ? (
                    <div className="kw-cart-box">
                        <h3>ตะกร้าสินค้าของคุณ ({cartLines.reduce((sum, l) => sum + l.quantity, 0)} ชิ้น)</h3>
                        <ul className="kw-cart-lines">
                            {displayedCartLines.map(line => (
                                <li key={line.uuid}>
                                    <span>{line.title} × {line.quantity}</span>
                                    <strong>{formatBaht(line.line_satang)}</strong>
                                </li>
                            ))}
                        </ul>
                        <p className="kw-cart-total">ยอดรวมทั้งหมด: <strong>{formatBaht(totalSatang)}</strong></p>

                        {baseRoute !== null && routeView === 'cart' ? <button type="button" onClick={() => navigate('checkout')}>ไปชำระเงิน</button> : storefront?.checkout_ready ? (
                            <form onSubmit={placeOrder}>
                                <h4>ข้อมูลผู้สั่งซื้อและชำระเงิน</h4>
                                <label>ชื่อ-นามสกุลผู้รับ<input disabled={!!pendingCreation} required maxLength={120} value={checkoutForm.customer_name} onChange={e => setCheckoutForm({ ...checkoutForm, customer_name: e.target.value })} /></label>
                                <label>อีเมล<input disabled={!!pendingCreation} required type="email" maxLength={160} value={checkoutForm.customer_email} onChange={e => setCheckoutForm({ ...checkoutForm, customer_email: e.target.value })} /></label>
                                <label>เบอร์โทรศัพท์<input disabled={!!pendingCreation} maxLength={40} value={checkoutForm.customer_phone} onChange={e => setCheckoutForm({ ...checkoutForm, customer_phone: e.target.value })} /></label>
                                <label>ที่อยู่จัดส่ง / หมายเหตุเพิ่มเติม<textarea disabled={!!pendingCreation} rows={3} maxLength={1000} value={checkoutForm.note} onChange={e => setCheckoutForm({ ...checkoutForm, note: e.target.value })} /></label>
                                {pendingCreation && !submittingOrder ? <p role="status">ยังยืนยันผลการสร้างออร์เดอร์ไม่ได้ กดตรวจผลอีกครั้งโดยใช้รายการเดิม</p> : null}
                                {quote ? <div role="status" className="kw-quote-review">
                                    <h4>ตรวจรายการและราคาก่อนยืนยัน</h4>
                                    <ul>{quote.items.map(line => <li key={line.product_uuid}>{line.title} × {line.quantity}: {formatBaht(line.line_total_satang)}</li>)}</ul>
                                    <p>ยอดที่ยืนยัน: <strong>{formatBaht(quote.total_satang)}</strong> · ใช้ได้ถึง {new Date(quote.expires_at).toLocaleTimeString('th-TH')}</p>
                                    <p>หากราคาหรือบัญชีรับเงินเปลี่ยน ระบบจะให้ตรวจราคาใหม่ก่อนสร้างคำสั่งซื้อ</p>
                                </div> : null}
                                <button type="submit" disabled={submittingOrder}>
                                    {submittingOrder ? 'กำลังตรวจสอบ…' : pendingCreation ? 'ตรวจผลคำสั่งซื้อเดิมอีกครั้ง' : quote ? `ยืนยันสั่งซื้อและรับ QR (${formatBaht(quote.total_satang)})` : 'ตรวจราคาและรายการก่อนสั่งซื้อ'}
                                </button>
                            </form>
                        ) : (
                            <p role="status">ร้านค้ากำลังตั้งค่าบัญชีรับชำระเงิน PromptPay กรุณาติดต่อร้านค้าโดยตรง</p>
                        )}
                    </div>
                ) : null}
            </div>
        )}
    </section>
}
