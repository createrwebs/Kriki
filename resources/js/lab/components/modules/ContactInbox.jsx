import { useEffect, useState } from 'react'
import { listContacts, transitionContact } from '../../lib/modules.js'
import { LAB_BTN_OUTLINE } from '../../lib/labConstants'
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
    return <section className="studio-desk mt-5 border-t border-krikkit-line pt-4" aria-label="กล่องข้อความติดต่อ"><div className="flex flex-wrap items-center justify-between gap-3"><h3 className="font-semibold">กล่องข้อความติดต่อ</h3><button className={`px-3 py-2 ${LAB_BTN_OUTLINE}`} disabled={busy} onClick={() => { setError(''); load().catch(err => setError(err.message)) }}>โหลดข้อความล่าสุด</button></div>
        {error ? <p role="alert" className="mt-3 text-red-600">{error}</p> : null}
        {items === null ? <p className="mt-3" role="status">กำลังโหลดข้อความ…</p> : items.length === 0 ? (
            <div className="studio-empty mt-4"><h4>ยังไม่มีข้อความใหม่</h4><p>เมื่อมีผู้ส่งฟอร์มติดต่อ ข้อความและช่องทางติดต่อกลับจะแสดงที่นี่</p></div>
        ) : <>
            <p className="mt-3 text-xs text-krikkit-muted">แสดงข้อความล่าสุดสูงสุด 100 รายการ</p>
            <div className="studio-inbox-list">
                {items.map(message => <article key={message.uuid} className="mt-3">
                    <div className="studio-inbox-heading"><h4>{message.subject || 'ไม่มีหัวข้อ'}</h4><span className="studio-record-status" data-status={message.status}>{labels[message.status]}</span></div>
                    <p className="studio-inbox-person">{message.customer_name} <span>{message.email}{message.phone ? ` · ${message.phone}` : ''}</span></p>
                    <p className="studio-inbox-message">{message.message}</p>
                    <div className="studio-inbox-footer"><span>{new Date(message.created_at).toLocaleString('th-TH')}</span><code title={message.uuid}>{message.uuid}</code></div>
                    <div className="mt-3 flex flex-wrap gap-2">{Object.entries(labels).filter(([status]) => status !== message.status).map(([status, label]) => <button key={status} className={`px-3 py-2 ${LAB_BTN_OUTLINE}`} disabled={busy || !writable} onClick={() => change(message, status)}>{label}</button>)}</div>
                </article>)}
            </div>
        </>}
    </section>
}
