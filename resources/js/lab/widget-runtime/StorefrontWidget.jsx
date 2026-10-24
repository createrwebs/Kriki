import React, { useEffect, useState } from 'react'

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

export default function StorefrontWidget({ apiOrigin, moduleUuid }) {
    const [storefront, setStorefront] = useState(null)
    const [loading, setLoading] = useState(true)
    const [error, setError] = useState('')
    const [cart, setCart] = useState({})
    const [checkoutForm, setCheckoutForm] = useState({ customer_name: '', customer_email: '', customer_phone: '', note: '' })
    const [orderKey, setOrderKey] = useState(() => crypto.randomUUID())
    const [submittingOrder, setSubmittingOrder] = useState(false)
    const [activeOrder, setActiveOrder] = useState(null)
    const [orderToken, setOrderToken] = useState('')
    const [slipForm, setSlipForm] = useState({ payer_name: '', payer_phone: '', transfer_ref: '', claimed_transferred_at: '', slip_data_url: '' })
    const [slipKey, setSlipKey] = useState(() => crypto.randomUUID())
    const [submittingSlip, setSubmittingSlip] = useState(false)
    const [slipFeedback, setSlipFeedback] = useState('')

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

    function adjustCart(productUuid, delta) {
        setCart(prev => {
            const nextQty = (prev[productUuid] || 0) + delta
            const copy = { ...prev }
            if (nextQty <= 0) delete copy[productUuid]
            else copy[productUuid] = Math.min(99, nextQty)
            return copy
        })
        setOrderKey(crypto.randomUUID())
    }

    const products = storefront?.products || []
    const cartLines = products
        .filter(p => (cart[p.uuid] || 0) > 0)
        .map(p => ({ ...p, quantity: cart[p.uuid], line_satang: p.price_satang * cart[p.uuid] }))
    const totalSatang = cartLines.reduce((sum, item) => sum + item.line_satang, 0)

    async function placeOrder(event) {
        event.preventDefault()
        if (cartLines.length === 0) return
        setSubmittingOrder(true); setError('')
        try {
            const response = await fetch(`${apiOrigin}/api/lab-widgets/${encodeURIComponent(moduleUuid)}/orders`, {
                method: 'POST', credentials: 'omit',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body: JSON.stringify({
                    idempotency_key: orderKey,
                    customer_name: checkoutForm.customer_name,
                    customer_email: checkoutForm.customer_email,
                    customer_phone: checkoutForm.customer_phone,
                    note: checkoutForm.note,
                    items: cartLines.map(item => ({ product_uuid: item.uuid, quantity: item.quantity })),
                }),
            })
            const data = await response.json()
            if (!response.ok) throw new Error(data.message || 'ไม่สามารถสร้างคำสั่งซื้อได้')
            setActiveOrder(data.order)
            setOrderToken(data.access_token || '')
            setSlipForm(prev => ({ ...prev, payer_name: checkoutForm.customer_name, payer_phone: checkoutForm.customer_phone }))
            setSlipKey(crypto.randomUUID())
            setCart({})
        } catch (err) {
            setError(err.message || 'ไม่สามารถสร้างคำสั่งซื้อได้')
        } finally {
            setSubmittingOrder(false)
        }
    }

    async function refreshOrderStatus() {
        if (!activeOrder?.uuid || !orderToken) return
        setError('')
        try {
            const response = await fetch(`${apiOrigin}/api/lab-widgets/${encodeURIComponent(moduleUuid)}/orders/${encodeURIComponent(activeOrder.uuid)}`, {
                method: 'GET', credentials: 'omit',
                headers: { Accept: 'application/json', 'X-Krikkit-Order-Token': orderToken },
            })
            const data = await response.json()
            if (!response.ok) throw new Error(data.message || 'ไม่สามารถดึงสถานะคำสั่งซื้อได้')
            setActiveOrder(data.order)
        } catch (err) {
            setError(err.message || 'ตรวจสอบสถานะไม่ได้')
        }
    }

    function handleSlipFile(event) {
        const file = event.target.files?.[0]
        if (!file) return
        const reader = new FileReader()
        reader.onload = () => {
            if (typeof reader.result === 'string') {
                setSlipForm(prev => ({ ...prev, slip_data_url: reader.result }))
                setSlipKey(crypto.randomUUID())
            }
        }
        reader.readAsDataURL(file)
    }

    async function submitSlip(event) {
        event.preventDefault()
        if (!activeOrder?.uuid || !orderToken) return
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
            const data = await response.json()
            if (!response.ok) throw new Error(data.message || 'ส่งสลิปไม่สำเร็จ')
            setSlipFeedback('ส่งหลักฐานการชำระเงินเรียบร้อยแล้ว ร้านค้ากำลังตรวจสอบสลิปของคุณ')
            await refreshOrderStatus()
        } catch (err) {
            setError(err.message || 'ส่งสลิปไม่สำเร็จ กรุณาลองอีกครั้ง')
        } finally {
            setSubmittingSlip(false)
        }
    }

    return <section className="kw-contact kw-storefront" aria-label="ร้านค้าและตะกร้าสินค้า">
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', gap: 12, flexWrap: 'wrap' }}>
            <h2>สินค้าและบริการ</h2>
            {activeOrder ? (
                <button type="button" onClick={() => { setActiveOrder(null); setOrderToken(''); setSlipFeedback('') }}>
                    เลือกซื้อสินค้าต่อ
                </button>
            ) : null}
        </div>
        {error ? <p role="alert">{error}</p> : null}

        {loading ? <p role="status">กำลังโหลดรายการสินค้า…</p> : activeOrder ? (
            <div className="kw-order-view" style={{ display: 'grid', gap: 16 }}>
                <div style={{ padding: 16, border: '1px solid #cde3da', borderRadius: 8, background: '#f5fbf8' }}>
                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', gap: 8, flexWrap: 'wrap' }}>
                        <strong>คำสั่งซื้อ #{activeOrder.reference}</strong>
                        <button type="button" onClick={refreshOrderStatus}>เช็คสถานะล่าสุด</button>
                    </div>
                    <p style={{ margin: '8px 0 4px' }}>สถานะ: <strong>{ORDER_STATUS_LABELS[activeOrder.status] || activeOrder.status}</strong></p>
                    <p style={{ margin: 0 }}>ยอดชำระสุทธิ: <strong>{formatBaht(activeOrder.total_satang)}</strong></p>
                    {activeOrder.latest_slip?.review_reason ? (
                        <p role="alert" style={{ marginTop: 8 }}>หมายเหตุจากร้านค้า: {activeOrder.latest_slip.review_reason}</p>
                    ) : null}
                </div>

                {activeOrder.promptpay ? (
                    <div style={{ padding: 16, border: '1px solid #ddd', borderRadius: 8, textAlign: 'center' }}>
                        <h3 style={{ margin: '0 0 8px' }}>สแกนชำระเงินด้วย PromptPay</h3>
                        <p style={{ margin: '4px 0' }}>ชื่อบัญชี: <strong>{activeOrder.promptpay.payee_label}</strong> ({activeOrder.promptpay.masked_recipient})</p>
                        {activeOrder.promptpay.instructions ? <p style={{ margin: '4px 0', fontSize: '0.9em' }}>{activeOrder.promptpay.instructions}</p> : null}
                        {activeOrder.promptpay.qr_svg ? (
                            <div
                                aria-label="PromptPay QR Code"
                                style={{ width: 220, height: 220, margin: '12px auto', padding: 8, border: '1px solid #eee', borderRadius: 8, background: '#fff' }}
                                dangerouslySetInnerHTML={{ __html: activeOrder.promptpay.qr_svg }}
                            />
                        ) : null}
                    </div>
                ) : null}

                {slipFeedback ? <p role="status" style={{ color: '#194938', fontWeight: 600 }}>{slipFeedback}</p> : null}

                {activeOrder.status !== 'paid' && activeOrder.status !== 'cancelled' ? (
                    <form onSubmit={submitSlip} style={{ borderTop: '1px solid #eee', paddingTop: 12 }}>
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
        ) : (
            <div style={{ display: 'grid', gap: 24 }}>
                {products.length === 0 ? (
                    <p>ยังไม่มีสินค้าเปิดจำหน่ายในขณะนี้</p>
                ) : (
                    <div className="kw-product-grid" style={{ display: 'grid', gap: 12 }}>
                        {products.map(product => {
                            const qty = cart[product.uuid] || 0
                            return (
                                <article key={product.uuid} style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', gap: 12, padding: 14, border: '1px solid #e5e7eb', borderRadius: 8 }}>
                                    <div>
                                        <h3 style={{ margin: '0 0 4px' }}>{product.title}</h3>
                                        <p style={{ margin: '0 0 6px', fontSize: '0.85em', opacity: 0.75 }}>SKU: {product.sku}</p>
                                        {product.description ? <p style={{ margin: '0 0 8px' }}>{product.description}</p> : null}
                                        <strong>{formatBaht(product.price_satang)}</strong>
                                    </div>
                                    <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                                        {qty > 0 ? (
                                            <>
                                                <button type="button" onClick={() => adjustCart(product.uuid, -1)} aria-label={`ลดจำนวน ${product.title}`}>-</button>
                                                <span aria-live="polite" style={{ minWidth: 24, textAlign: 'center', fontWeight: 600 }}>{qty}</span>
                                                <button type="button" onClick={() => adjustCart(product.uuid, 1)} aria-label={`เพิ่มจำนวน ${product.title}`}>+</button>
                                            </>
                                        ) : (
                                            <button type="button" onClick={() => adjustCart(product.uuid, 1)}>หยิบใส่ตะกร้า</button>
                                        )}
                                    </div>
                                </article>
                            )
                        })}
                    </div>
                )}

                {cartLines.length > 0 ? (
                    <div className="kw-cart-box" style={{ borderTop: '2px solid #194938', paddingTop: 16 }}>
                        <h3>ตะกร้าสินค้าของคุณ ({cartLines.reduce((sum, l) => sum + l.quantity, 0)} ชิ้น)</h3>
                        <ul style={{ listStyle: 'none', padding: 0, margin: '0 0 12px', display: 'grid', gap: 6 }}>
                            {cartLines.map(line => (
                                <li key={line.uuid} style={{ display: 'flex', justifyContent: 'space-between' }}>
                                    <span>{line.title} × {line.quantity}</span>
                                    <strong>{formatBaht(line.line_satang)}</strong>
                                </li>
                            ))}
                        </ul>
                        <p style={{ fontSize: '1.1em' }}>ยอดรวมทั้งหมด: <strong>{formatBaht(totalSatang)}</strong></p>

                        {storefront?.checkout_ready ? (
                            <form onSubmit={placeOrder}>
                                <h4>ข้อมูลผู้สั่งซื้อและชำระเงิน</h4>
                                <label>ชื่อ-นามสกุลผู้รับ<input required maxLength={120} value={checkoutForm.customer_name} onChange={e => setCheckoutForm({ ...checkoutForm, customer_name: e.target.value })} /></label>
                                <label>อีเมล<input required type="email" maxLength={160} value={checkoutForm.customer_email} onChange={e => setCheckoutForm({ ...checkoutForm, customer_email: e.target.value })} /></label>
                                <label>เบอร์โทรศัพท์<input maxLength={40} value={checkoutForm.customer_phone} onChange={e => setCheckoutForm({ ...checkoutForm, customer_phone: e.target.value })} /></label>
                                <label>ที่อยู่จัดส่ง / หมายเหตุเพิ่มเติม<textarea rows={3} maxLength={1000} value={checkoutForm.note} onChange={e => setCheckoutForm({ ...checkoutForm, note: e.target.value })} /></label>
                                <button type="submit" disabled={submittingOrder}>
                                    {submittingOrder ? 'กำลังสร้างรายการชำระเงิน…' : `สั่งซื้อและรับ QR PromptPay (${formatBaht(totalSatang)})`}
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
