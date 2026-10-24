import { useEffect, useState } from 'react'
import { listContacts, transitionContact } from '../../lib/modules.js'
const labels = { new: 'ใหม่', read: 'อ่านแล้ว', archived: 'เก็บแล้ว' }
export default function ContactInbox({ projectUuid, module, writable }) {
    const [items, setItems] = useState(null)
    const [error, setError] = useState('')
    const [busy, setBusy] = useState(false)
    const load = async () => { const data = await listContacts(projectUuid, module.uuid); setItems(data.items) }
    useEffect(() => { let cancelled = false; listContacts(projectUuid, module.uuid).then(data => { if (!cancelled) setItems(data.items) }).catch(err => { if (!cancelled) setError(err.message) }); return () => { cancelled = true } }, [projectUuid, module.uuid])
    const change = async (message, status) => {
        setBusy(true); setError('')
        try { await transitionContact(projectUuid, module.uuid, message.uuid, message.version, status); await load() }
        catch (err) { setError(err.message); if (err.status === 409) { try { await load() } catch { /* Keep conflict visible. */ } } }
        finally { setBusy(false) }
    }
    return <section className="mt-5 border-t border-krikkit-line pt-4" aria-label="กล่องข้อความติดต่อ"><div className="flex justify-between"><h3 className="font-semibold">กล่องข้อความติดต่อ</h3><button disabled={busy} onClick={() => { setError(''); load().catch(err => setError(err.message)) }}>โหลดข้อความล่าสุด</button></div>
        {error ? <p role="alert" className="mt-3 text-red-600">{error}</p> : null}
        {items === null ? <p className="mt-3">กำลังโหลดข้อความ…</p> : items.length === 0 ? <p className="mt-3 text-krikkit-muted">ยังไม่มีข้อความ เมื่อมีผู้ส่งฟอร์มจะแสดงที่นี่</p> : <><p className="mt-2 text-xs text-krikkit-muted">แสดงข้อความล่าสุดสูงสุด 100 รายการ</p>{items.map(message => <article key={message.uuid} className="mt-3 rounded-lg border border-krikkit-line p-3"><p className="font-medium">{message.subject || 'ไม่มีหัวข้อ'} · {labels[message.status]}</p><p className="mt-2">{message.customer_name} · {message.email}{message.phone ? ` · ${message.phone}` : ''}</p><p className="mt-2 whitespace-pre-wrap">{message.message}</p><p className="mt-2 text-xs text-krikkit-muted">{new Date(message.created_at).toLocaleString('th-TH')} · {message.uuid}</p><div className="mt-3 flex gap-3">{Object.entries(labels).filter(([status]) => status !== message.status).map(([status, label]) => <button key={status} disabled={busy || !writable} onClick={() => change(message, status)}>{label}</button>)}</div></article>)}</>}
    </section>
}
