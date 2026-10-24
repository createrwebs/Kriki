import React, { useRef, useState } from 'react'

export default function ContactForm({ apiOrigin, moduleUuid }) {
    const [busy, setBusy] = useState(false)
    const [error, setError] = useState('')
    const [receipt, setReceipt] = useState(null)
    const pending = useRef(null)
    const submitting = useRef(false)
    async function submit(event) {
        event.preventDefault()
        if (submitting.current) return
        const form = event.currentTarget
        const fields = new FormData(form)
        const values = { name: String(fields.get('name') || '').trim(), email: String(fields.get('email') || '').trim(), phone: String(fields.get('phone') || '').trim(), subject: String(fields.get('subject') || '').trim(), message: String(fields.get('message') || '').trim(), consent: fields.get('consent') === 'on', website: String(fields.get('website') || '') }
        const signature = JSON.stringify(values)
        if (pending.current?.signature !== signature) pending.current = { signature, key: crypto.randomUUID() }
        submitting.current = true; setBusy(true); setError('')
        try {
            const response = await fetch(`${apiOrigin}/api/lab-widgets/${encodeURIComponent(moduleUuid)}/contact`, {
                method: 'POST', credentials: 'omit', headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ ...values, idempotency_key: pending.current.key }),
            })
            const data = await response.json()
            if (!response.ok) throw new Error(data.message || 'ส่งข้อความไม่สำเร็จ กรุณาลองอีกครั้ง')
            setReceipt(data); pending.current = null
        } catch (err) { setError(err.message || 'เชื่อมต่อไม่ได้ กรุณาลองส่งอีกครั้ง') }
        finally { submitting.current = false; setBusy(false) }
    }
    if (receipt) return <section className="kw-contact" aria-label="ส่งข้อความแล้ว"><h2>ได้รับข้อความแล้ว</h2><p>ทีมงานจะตรวจสอบและติดต่อกลับ</p><p>เลขอ้างอิง: {receipt.reference}</p><button type="button" onClick={() => setReceipt(null)}>ส่งข้อความใหม่</button></section>
    return <form className="kw-contact" onSubmit={submit} aria-label="ฟอร์มติดต่อ">
        <h2>ติดต่อเรา</h2><p>ฝากรายละเอียดไว้ แล้วทีมงานจะติดต่อกลับ</p>
        <label>ชื่อ<input name="name" autoComplete="name" required maxLength={120} /></label>
        <label>อีเมล<input name="email" type="email" autoComplete="email" required maxLength={254} /></label>
        <label>โทรศัพท์ (ไม่บังคับ)<input name="phone" type="tel" autoComplete="tel" maxLength={40} /></label>
        <label>หัวข้อ (ไม่บังคับ)<input name="subject" maxLength={180} /></label>
        <label>ข้อความ<textarea name="message" required maxLength={5000} rows={5} /></label>
        <div hidden aria-hidden="true"><label>Website<input name="website" tabIndex={-1} autoComplete="off" /></label></div>
        <label className="kw-consent"><input name="consent" type="checkbox" required />ยินยอมให้ใช้ข้อมูลนี้เพื่อตอบกลับข้อความของฉัน</label>
        {error ? <p role="alert">{error}</p> : null}
        <button type="submit" disabled={busy}>{busy ? 'กำลังส่ง…' : 'ส่งข้อความ'}</button>
    </form>
}
