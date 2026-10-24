import { useEffect, useRef, useState } from 'react'
import {
    createProduct,
    getPaymentSettings,
    listOrders,
    listProducts,
    reviewPaymentAttempt,
    savePaymentSettings,
    updateProduct,
} from '../../lib/modules.js'
import { LAB_BTN_OUTLINE, LAB_BTN_PRIMARY } from '../../lib/labConstants'

const inputClass = 'mt-1 w-full rounded-lg border border-krikkit-line bg-krikkit-surface px-3 py-2 text-sm text-krikkit-fg'

function formatBaht(satang) {
    const num = Number(satang || 0) / 100
    return num.toLocaleString('th-TH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ฿'
}

export function CatalogDesk({ projectUuid, module, writable }) {
    const [items, setItems] = useState(null)
    const [editing, setEditing] = useState(null)
    const [sku, setSku] = useState('')
    const [title, setTitle] = useState('')
    const [description, setDescription] = useState('')
    const [priceBaht, setPriceBaht] = useState('199.00')
    const [active, setActive] = useState(true)
    const [busy, setBusy] = useState(false)
    const [error, setError] = useState('')
    const [notice, setNotice] = useState('')
    const pendingKey = useRef(null)

    const load = async () => {
        const data = await listProducts(projectUuid, module.uuid)
        setItems(data.items)
        return data.items
    }

    useEffect(() => {
        let cancelled = false
        listProducts(projectUuid, module.uuid)
            .then(data => { if (!cancelled) setItems(data.items) })
            .catch(err => { if (!cancelled) setError(err.message) })
        return () => { cancelled = true }
    }, [projectUuid, module.uuid])

    const startCreate = () => {
        setEditing(null)
        setSku('')
        setTitle('')
        setDescription('')
        setPriceBaht('199.00')
        setActive(true)
        setError('')
        setNotice('')
        pendingKey.current = null
    }

    const startEdit = product => {
        setEditing(product)
        setSku(product.sku)
        setTitle(product.title)
        setDescription(product.description || '')
        setPriceBaht((Number(product.price_satang || 0) / 100).toFixed(2))
        setActive(Boolean(product.active))
        setError('')
        setNotice('')
        pendingKey.current = null
    }

    const save = async event => {
        event.preventDefault()
        if (busy || !writable) return
        const priceSatang = Math.round(Number(priceBaht) * 100)
        if (!Number.isFinite(priceSatang) || priceSatang < 100) {
            setError('กรุณาระบุราคาสินค้าอย่างน้อย 1.00 บาท')
            return
        }
        setBusy(true); setError(''); setNotice('')
        try {
            if (editing) {
                await updateProduct(projectUuid, module.uuid, editing.uuid, {
                    expected_version: editing.version,
                    sku: editing.sku,
                    title: title.trim(),
                    description: description.trim(),
                    price_satang: priceSatang,
                    active,
                })
                setNotice('บันทึกการแก้ไขสินค้าเรียบร้อยแล้ว')
            } else {
                const payload = {
                    sku: sku.trim(),
                    title: title.trim(),
                    description: description.trim(),
                    price_satang: priceSatang,
                    active,
                }
                const sig = JSON.stringify(payload)
                if (pendingKey.current?.sig !== sig) pendingKey.current = { sig, key: crypto.randomUUID() }
                await createProduct(projectUuid, module.uuid, {
                    ...payload,
                    idempotency_key: pendingKey.current.key,
                })
                setNotice('เพิ่มสินค้าใหม่เรียบร้อยแล้ว')
            }
            const next = await load()
            if (editing) {
                const refreshed = next.find(item => item.uuid === editing.uuid)
                if (refreshed) startEdit(refreshed)
            } else {
                startCreate()
                setNotice('เพิ่มสินค้าใหม่เรียบร้อยแล้ว')
            }
        } catch (err) {
            setError(err.message)
            if (err.status === 409) {
                try { await load() } catch { /* Keep conflict visible */ }
            }
        } finally {
            setBusy(false)
        }
    }

    return <section className="mt-5 border-t border-krikkit-line pt-4" aria-label="จัดการสินค้า">
        <div className="flex flex-wrap items-center justify-between gap-2">
            <h3 className="font-semibold">จัดการคลังสินค้า (Catalog)</h3>
            <div className="flex gap-2">
                {editing ? <button type="button" disabled={busy} className={`rounded-lg px-2.5 py-1 text-xs ${LAB_BTN_OUTLINE}`} onClick={startCreate}>+ เพิ่มสินค้าใหม่</button> : null}
                <button type="button" disabled={busy} className={`rounded-lg px-2.5 py-1 text-xs ${LAB_BTN_OUTLINE}`} onClick={() => { setError(''); load().catch(err => setError(err.message)) }}>โหลดรายการสินค้า</button>
            </div>
        </div>
        {error ? <p role="alert" className="mt-3 text-sm text-red-600">{error}</p> : null}
        {notice ? <p role="status" className="mt-3 text-sm">{notice}</p> : null}

        <form onSubmit={save} className="mt-4 space-y-3 rounded-xl border border-krikkit-line p-3">
            <h4 className="font-medium">{editing ? `แก้ไขสินค้า: ${editing.title} (รุ่น ${editing.version})` : 'เพิ่มสินค้าใหม่'}</h4>
            <div className="grid gap-3 sm:grid-cols-3">
                <label className="block text-xs">รหัสสินค้า (SKU)
                    <input className={inputClass} value={sku} onChange={e => setSku(e.target.value)} disabled={busy || !writable || Boolean(editing)} placeholder="เช่น COFFEE-01" pattern="^[A-Za-z0-9][A-Za-z0-9_-]{0,63}$" maxLength={64} required />
                </label>
                <label className="block text-xs">ราคา (บาท)
                    <input type="number" step="0.01" min="1" className={inputClass} value={priceBaht} onChange={e => setPriceBaht(e.target.value)} disabled={busy || !writable} required />
                </label>
                <label className="flex items-center gap-2 pt-5 text-xs">
                    <input type="checkbox" checked={active} onChange={e => setActive(e.target.checked)} disabled={busy || !writable} />
                    เปิดจำหน่ายหน้าร้าน
                </label>
            </div>
            <label className="block text-xs">ชื่อสินค้า
                <input className={inputClass} value={title} onChange={e => setTitle(e.target.value)} disabled={busy || !writable} maxLength={160} required />
            </label>
            <label className="block text-xs">รายละเอียดสินค้า
                <textarea className={inputClass} rows={2} value={description} onChange={e => setDescription(e.target.value)} disabled={busy || !writable} maxLength={2000} />
            </label>
            <button type="submit" disabled={busy || !writable} className={`rounded-lg px-3 py-1.5 text-sm ${LAB_BTN_PRIMARY}`}>
                {busy ? 'กำลังบันทึก…' : editing ? 'บันทึกการแก้ไขสินค้า' : 'เพิ่มสินค้า'}
            </button>
        </form>

        {items === null ? <p className="mt-3">กำลังโหลดสินค้า…</p> : items.length === 0 ? <p className="mt-3 text-krikkit-muted">ยังไม่มีสินค้าในระบบ</p> : (
            <div className="mt-4 space-y-2">
                {items.map(item => (
                    <article key={item.uuid} className="rounded-lg border border-krikkit-line p-3">
                        <div className="flex flex-wrap items-center justify-between gap-2">
                            <p className="font-medium">
                                {item.title} <span className="text-xs text-krikkit-muted">({item.sku}) · {formatBaht(item.price_satang)} · {item.active ? 'เปิดขาย' : 'ซ่อนอยู่'} · รุ่น {item.version}</span>
                            </p>
                            <button type="button" disabled={busy} className={`rounded-lg px-2.5 py-1 text-xs ${LAB_BTN_OUTLINE}`} onClick={() => startEdit(item)}>แก้ไขสินค้านี้</button>
                        </div>
                        {item.description ? <p className="mt-1 text-xs text-krikkit-muted">{item.description}</p> : null}
                    </article>
                ))}
            </div>
        )}
    </section>
}

export function PromptPaySettingsDesk({ projectUuid, module, writable }) {
    const [settings, setSettings] = useState(null)
    const [recipientType, setRecipientType] = useState('phone')
    const [recipient, setRecipient] = useState('')
    const [payeeLabel, setPayeeLabel] = useState('')
    const [instructions, setInstructions] = useState('')
    const [enabled, setEnabled] = useState(true)
    const [busy, setBusy] = useState(false)
    const [error, setError] = useState('')
    const [notice, setNotice] = useState('')

    const load = async () => {
        const data = await getPaymentSettings(projectUuid, module.uuid)
        const s = data.settings
        setSettings(s)
        setRecipientType(s.recipient_type || 'phone')
        setRecipient(s.recipient || '')
        setPayeeLabel(s.payee_label || '')
        setInstructions(s.instructions || '')
        setEnabled(Boolean(s.enabled))
        return s
    }

    useEffect(() => {
        let cancelled = false
        getPaymentSettings(projectUuid, module.uuid)
            .then(data => {
                if (cancelled) return
                const s = data.settings
                setSettings(s)
                setRecipientType(s.recipient_type || 'phone')
                setRecipient(s.recipient || '')
                setPayeeLabel(s.payee_label || '')
                setInstructions(s.instructions || '')
                setEnabled(Boolean(s.enabled))
            })
            .catch(err => { if (!cancelled) setError(err.message) })
        return () => { cancelled = true }
    }, [projectUuid, module.uuid])

    const save = async event => {
        event.preventDefault()
        if (busy || !writable || !settings) return
        setBusy(true); setError(''); setNotice('')
        try {
            const data = await savePaymentSettings(projectUuid, module.uuid, {
                expected_version: settings.version,
                recipient_type: recipientType,
                recipient: recipient.trim(),
                payee_label: payeeLabel.trim(),
                instructions: instructions.trim(),
                enabled,
            })
            setSettings(data.settings)
            setRecipientType(data.settings.recipient_type)
            setRecipient(data.settings.recipient)
            setPayeeLabel(data.settings.payee_label)
            setInstructions(data.settings.instructions)
            setEnabled(Boolean(data.settings.enabled))
            setNotice('บันทึกการตั้งค่าบัญชีรับเงิน PromptPay เรียบร้อยแล้ว')
        } catch (err) {
            setError(err.message)
            if (err.status === 409) {
                try { await load() } catch { /* Keep conflict visible */ }
            }
        } finally {
            setBusy(false)
        }
    }

    return <section className="mt-5 border-t border-krikkit-line pt-4" aria-label="ตั้งค่าบัญชี PromptPay">
        <div className="flex flex-wrap items-center justify-between gap-2">
            <h3 className="font-semibold">ตั้งค่าบัญชีรับชำระเงิน PromptPay</h3>
            <button type="button" disabled={busy} className={`rounded-lg px-2.5 py-1 text-xs ${LAB_BTN_OUTLINE}`} onClick={() => { setError(''); load().catch(err => setError(err.message)) }}>โหลดค่าล่าสุด</button>
        </div>
        {error ? <p role="alert" className="mt-3 text-sm text-red-600">{error}</p> : null}
        {notice ? <p role="status" className="mt-3 text-sm">{notice}</p> : null}

        {!settings ? <p className="mt-3">กำลังโหลดการตั้งค่า PromptPay…</p> : (
            <form onSubmit={save} className="mt-4 space-y-3 rounded-xl border border-krikkit-line p-3">
                <div className="grid gap-3 sm:grid-cols-2">
                    <label className="block text-xs">ประเภท PromptPay
                        <select className={inputClass} value={recipientType} onChange={e => setRecipientType(e.target.value)} disabled={busy || !writable}>
                            <option value="phone">เบอร์โทรศัพท์มือถือ (10 หลัก)</option>
                            <option value="national_id">เลขประจำตัวผู้เสียภาษี / บัตรประชาชน (13 หลัก)</option>
                            <option value="ewallet">E-Wallet ID (15 หลัก)</option>
                        </select>
                    </label>
                    <label className="block text-xs">หมายเลข PromptPay
                        <input className={inputClass} value={recipient} onChange={e => setRecipient(e.target.value)} disabled={busy || !writable} placeholder="เช่น 0812345678" maxLength={20} required />
                    </label>
                </div>
                <div className="grid gap-3 sm:grid-cols-2">
                    <label className="block text-xs">ชื่อบัญชีที่แสดงให้ลูกค้าเห็น
                        <input className={inputClass} value={payeeLabel} onChange={e => setPayeeLabel(e.target.value)} disabled={busy || !writable} placeholder="เช่น บจก. คริกคิต สตูดิโอ" maxLength={160} required />
                    </label>
                    <label className="flex items-center gap-2 pt-5 text-xs">
                        <input type="checkbox" checked={enabled} onChange={e => setEnabled(e.target.checked)} disabled={busy || !writable} />
                        เปิดรับชำระด้วย QR PromptPay บนหน้าเว็บ (รุ่น {settings.version})
                    </label>
                </div>
                <label className="block text-xs">คำแนะนำเพิ่มเติมสำหรับลูกค้า
                    <input className={inputClass} value={instructions} onChange={e => setInstructions(e.target.value)} disabled={busy || !writable} placeholder="เช่น โอนแล้วแนบสลิปเพื่อให้เจ้าหน้าที่ตรวจสอบภายใน 15 นาที" maxLength={1000} />
                </label>
                <button type="submit" disabled={busy || !writable} className={`rounded-lg px-3 py-1.5 text-sm ${LAB_BTN_PRIMARY}`}>
                    {busy ? 'กำลังบันทึก…' : 'บันทึกการตั้งค่า PromptPay'}
                </button>
            </form>
        )}
    </section>
}

const ORDER_STATUS_TH = {
    awaiting_payment: 'รอชำระเงิน',
    pending_review: 'รอตรวจสลิป',
    paid: 'ชำระเงินแล้ว (อนุมัติ)',
    rejected: 'ปฏิเสธสลิป',
    cancelled: 'ยกเลิก',
}

export function OrdersDesk({ projectUuid, module, paymentModule, writable }) {
    const [orders, setOrders] = useState(null)
    const [busy, setBusy] = useState(false)
    const [error, setError] = useState('')
    const [notice, setNotice] = useState('')
    const [rejectReasonByAttempt, setRejectReasonByAttempt] = useState({})

    const load = async () => {
        const data = await listOrders(projectUuid, module.uuid)
        setOrders(data.items)
        return data.items
    }

    useEffect(() => {
        let cancelled = false
        listOrders(projectUuid, module.uuid)
            .then(data => { if (!cancelled) setOrders(data.items) })
            .catch(err => { if (!cancelled) setError(err.message) })
        return () => { cancelled = true }
    }, [projectUuid, module.uuid])

    const handleReview = async (attempt, decision) => {
        if (busy || !writable || !paymentModule) return
        const reason = (rejectReasonByAttempt[attempt.uuid] || '').trim()
        if (decision === 'rejected' && !reason) {
            setError('กรุณาระบุเหตุผลที่ปฏิเสธสลิป เพื่อให้ลูกค้าทราบและแนบสลิปใหม่ได้')
            return
        }
        setBusy(true); setError(''); setNotice('')
        try {
            await reviewPaymentAttempt(projectUuid, paymentModule.uuid, attempt.uuid, {
                expected_version: attempt.version,
                decision,
                reason: decision === 'accepted' ? (reason || 'ตรวจสอบยอดเงินถูกต้อง') : reason,
            })
            setNotice(decision === 'accepted' ? 'อนุมัติสลิปและเปลี่ยนสถานะคำสั่งซื้อเป็นชำระเงินสำเร็จแล้ว' : 'ปฏิเสธสลิปเรียบร้อยแล้ว')
            await load()
        } catch (err) {
            setError(err.message)
            if (err.status === 409) {
                try { await load() } catch { /* Keep conflict visible */ }
            }
        } finally {
            setBusy(false)
        }
    }

    return <section className="mt-5 border-t border-krikkit-line pt-4" aria-label="ตรวจคำสั่งซื้อและสลิป">
        <div className="flex flex-wrap items-center justify-between gap-2">
            <h3 className="font-semibold">รายการคำสั่งซื้อและตรวจสอบสลิปโอนเงิน</h3>
            <button type="button" disabled={busy} className={`rounded-lg px-2.5 py-1 text-xs ${LAB_BTN_OUTLINE}`} onClick={() => { setError(''); load().catch(err => setError(err.message)) }}>โหลดคำสั่งซื้อล่าสุด</button>
        </div>
        {error ? <p role="alert" className="mt-3 text-sm text-red-600">{error}</p> : null}
        {notice ? <p role="status" className="mt-3 text-sm">{notice}</p> : null}

        {orders === null ? <p className="mt-3">กำลังโหลดรายการคำสั่งซื้อ…</p> : orders.length === 0 ? <p className="mt-3 text-krikkit-muted">ยังไม่มีคำสั่งซื้อเข้ามา</p> : (
            <div className="mt-4 space-y-3">
                {orders.map(order => {
                    const itemsList = Array.isArray(order.items) ? order.items : []
                    const attempts = Array.isArray(order.payment_attempts) ? order.payment_attempts : []
                    return (
                        <article key={order.uuid} className="rounded-xl border border-krikkit-line p-3">
                            <div className="flex flex-wrap items-center justify-between gap-2">
                                <p className="font-semibold">
                                    #{order.reference} · {order.customer_name} ({order.customer_email})
                                </p>
                                <span className="rounded-full border border-krikkit-line px-2.5 py-0.5 text-xs font-medium">
                                    {ORDER_STATUS_TH[order.status] || order.status} · {formatBaht(order.total_satang)}
                                </span>
                            </div>
                            {order.customer_phone ? <p className="mt-1 text-xs text-krikkit-muted">โทร: {order.customer_phone}</p> : null}
                            {order.note ? <p className="mt-1 text-xs">หมายเหตุ/จัดส่ง: {order.note}</p> : null}
                            <ul className="mt-2 list-inside list-disc text-xs text-krikkit-muted">
                                {itemsList.map((it, idx) => (
                                    <li key={idx}>{it.title} ({it.sku}) × {it.quantity} = {formatBaht(it.line_total_satang)}</li>
                                ))}
                            </ul>

                            {attempts.length > 0 ? (
                                <div className="mt-3 space-y-2 border-t border-krikkit-line pt-2">
                                    <p className="text-xs font-semibold">หลักฐานการโอนเงิน ({attempts.length} รายการ)</p>
                                    {attempts.map(att => (
                                        <div key={att.uuid} className="rounded-lg border border-krikkit-line p-2.5 text-xs">
                                            <div className="flex flex-wrap items-center justify-between gap-2">
                                                <span>ผู้โอน: <strong>{att.payer_name}</strong> · ยอดแจ้งโอน: <strong>{formatBaht(att.amount_satang)}</strong> · สถานะสลิป: <strong>{att.status}</strong></span>
                                                {att.transfer_ref ? <span>อ้างอิง: {att.transfer_ref}</span> : null}
                                            </div>
                                            {att.slip_data_url ? (
                                                <div className="mt-2">
                                                    <img src={att.slip_data_url} alt={`สลิปโอนเงินของ ${att.payer_name}`} className="max-h-40 rounded border border-krikkit-line object-contain" />
                                                </div>
                                            ) : null}
                                            {att.review_reason ? <p className="mt-1 text-krikkit-muted">ผลการตรวจ: {att.review_reason}</p> : null}
                                            {att.status === 'submitted' && paymentModule ? (
                                                <div className="mt-2 flex flex-wrap items-center gap-2">
                                                    <input
                                                        className="rounded border border-krikkit-line bg-krikkit-surface px-2 py-1 text-xs"
                                                        placeholder="หมายเหตุ / เหตุผลกรณีปฏิเสธสลิป"
                                                        value={rejectReasonByAttempt[att.uuid] || ''}
                                                        onChange={e => setRejectReasonByAttempt(prev => ({ ...prev, [att.uuid]: e.target.value }))}
                                                        disabled={busy || !writable}
                                                    />
                                                    <button type="button" disabled={busy || !writable} className={`rounded-lg px-2.5 py-1 text-xs ${LAB_BTN_PRIMARY}`} onClick={() => handleReview(att, 'accepted')}>อนุมัติการชำระเงิน</button>
                                                    <button type="button" disabled={busy || !writable} className={`rounded-lg px-2.5 py-1 text-xs ${LAB_BTN_OUTLINE}`} onClick={() => handleReview(att, 'rejected')}>ปฏิเสธสลิป</button>
                                                </div>
                                            ) : null}
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <p className="mt-2 text-xs text-krikkit-muted">ลูกค้ายังไม่ได้แนบสลิปการโอนเงิน</p>
                            )}
                        </article>
                    )
                })}
            </div>
        )}
    </section>
}
