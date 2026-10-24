import { useEffect, useRef, useState } from 'react'
import { createNews, listNews, updateNews } from '../../lib/modules.js'
import { LAB_BTN_OUTLINE, LAB_BTN_PRIMARY } from '../../lib/labConstants'

const statusLabels = { draft: 'แบบร่าง', published: 'เผยแพร่แล้ว', archived: 'เก็บเข้าคลัง' }
const inputClass = 'mt-1 w-full rounded-lg border border-krikkit-line bg-krikkit-surface px-3 py-2 text-sm text-krikkit-fg'

export default function NewsDesk({ projectUuid, module, writable }) {
    const [items, setItems] = useState(null)
    const [editing, setEditing] = useState(null)
    const [slug, setSlug] = useState('')
    const [title, setTitle] = useState('')
    const [excerpt, setExcerpt] = useState('')
    const [body, setBody] = useState('')
    const [status, setStatus] = useState('draft')
    const [busy, setBusy] = useState(false)
    const [error, setError] = useState('')
    const [notice, setNotice] = useState('')
    const pendingKey = useRef(null)

    const load = async () => {
        const data = await listNews(projectUuid, module.uuid)
        setItems(data.items)
        return data.items
    }

    useEffect(() => {
        let cancelled = false
        listNews(projectUuid, module.uuid)
            .then(data => { if (!cancelled) setItems(data.items) })
            .catch(err => { if (!cancelled) setError(err.message) })
        return () => { cancelled = true }
    }, [projectUuid, module.uuid])

    const startCreate = () => {
        setEditing(null)
        setSlug('')
        setTitle('')
        setExcerpt('')
        setBody('')
        setStatus('draft')
        setError('')
        setNotice('')
        pendingKey.current = null
    }

    const startEdit = article => {
        setEditing(article)
        setSlug(article.slug)
        setTitle(article.title)
        setExcerpt(article.excerpt || '')
        setBody(article.body)
        setStatus(article.status)
        setError('')
        setNotice('')
        pendingKey.current = null
    }

    const save = async event => {
        event.preventDefault()
        if (busy || !writable) return
        setBusy(true); setError(''); setNotice('')
        try {
            if (editing) {
                await updateNews(projectUuid, module.uuid, editing.uuid, {
                    expected_version: editing.version,
                    slug: editing.slug,
                    title: title.trim(),
                    excerpt: excerpt.trim(),
                    body: body.trim(),
                    status,
                })
                setNotice('บันทึกการแก้ไขข่าวสารเรียบร้อยแล้ว')
            } else {
                const payload = {
                    slug: slug.trim(),
                    title: title.trim(),
                    excerpt: excerpt.trim(),
                    body: body.trim(),
                    status: status === 'archived' ? 'draft' : status,
                }
                const sig = JSON.stringify(payload)
                if (pendingKey.current?.sig !== sig) pendingKey.current = { sig, key: crypto.randomUUID() }
                await createNews(projectUuid, module.uuid, {
                    ...payload,
                    idempotency_key: pendingKey.current.key,
                })
                setNotice('เพิ่มข่าวสารใหม่เรียบร้อยแล้ว')
            }
            const next = await load()
            if (editing) {
                const refreshed = next.find(item => item.uuid === editing.uuid)
                if (refreshed) startEdit(refreshed)
            } else {
                startCreate()
                setNotice('เพิ่มข่าวสารใหม่เรียบร้อยแล้ว')
            }
        } catch (err) {
            setError(err.message)
            if (err.status === 409) {
                try { await load() } catch { /* Keep conflict visible. */ }
            }
        } finally {
            setBusy(false)
        }
    }

    return <section className="mt-5 border-t border-krikkit-line pt-4" aria-label="จัดการข่าวสาร">
        <div className="flex flex-wrap items-center justify-between gap-2">
            <h3 className="font-semibold">จัดการข่าวสารและบทความ</h3>
            <div className="flex gap-2">
                {editing ? <button type="button" disabled={busy} className={`rounded-lg px-2.5 py-1 text-xs ${LAB_BTN_OUTLINE}`} onClick={startCreate}>+ เขียนข่าวใหม่</button> : null}
                <button type="button" disabled={busy} className={`rounded-lg px-2.5 py-1 text-xs ${LAB_BTN_OUTLINE}`} onClick={() => { setError(''); load().catch(err => setError(err.message)) }}>โหลดข่าวล่าสุด</button>
            </div>
        </div>
        {error ? <p role="alert" className="mt-3 text-sm text-red-600">{error}</p> : null}
        {notice ? <p role="status" className="mt-3 text-sm">{notice}</p> : null}

        <form onSubmit={save} className="mt-4 space-y-3 rounded-xl border border-krikkit-line p-3">
            <h4 className="font-medium">{editing ? `แก้ไขข่าว: ${editing.title} (รุ่น ${editing.version})` : 'เพิ่มข่าวสารใหม่'}</h4>
            <div className="grid gap-3 sm:grid-cols-2">
                <label className="block text-xs">Slug (ภาษาอังกฤษตัวพิมพ์เล็ก-ตัวเลข-ขีดกลาง)
                    <input className={inputClass} value={slug} onChange={e => setSlug(e.target.value.toLowerCase())} disabled={busy || !writable || Boolean(editing)} placeholder="เช่น grand-opening-2026" pattern="^[a-z0-9]+(-[a-z0-9]+)*$" maxLength={120} required />
                </label>
                <label className="block text-xs">สถานะ
                    <select className={inputClass} value={status} onChange={e => setStatus(e.target.value)} disabled={busy || !writable}>
                        <option value="draft">แบบร่าง (ไม่แสดงหน้าเว็บ)</option>
                        <option value="published">เผยแพร่แล้ว (แสดงหน้าเว็บ)</option>
                        {editing ? <option value="archived">เก็บเข้าคลัง (ซ่อนจากหน้าเว็บ)</option> : null}
                    </select>
                </label>
            </div>
            <label className="block text-xs">หัวข้อข่าว
                <input className={inputClass} value={title} onChange={e => setTitle(e.target.value)} disabled={busy || !writable} maxLength={200} required />
            </label>
            <label className="block text-xs">คำโปรยย่อ (ไม่บังคับ)
                <input className={inputClass} value={excerpt} onChange={e => setExcerpt(e.target.value)} disabled={busy || !writable} maxLength={500} />
            </label>
            <label className="block text-xs">เนื้อหาข่าว
                <textarea className={inputClass} rows={4} value={body} onChange={e => setBody(e.target.value)} disabled={busy || !writable} maxLength={20000} required />
            </label>
            <button type="submit" disabled={busy || !writable} className={`rounded-lg px-3 py-1.5 text-sm ${LAB_BTN_PRIMARY}`}>
                {busy ? 'กำลังบันทึก…' : editing ? 'บันทึกการแก้ไขข่าว' : 'เพิ่มข่าวสาร'}
            </button>
        </form>

        {items === null ? <p className="mt-3">กำลังโหลดรายการข่าว…</p> : items.length === 0 ? <p className="mt-3 text-krikkit-muted">ยังไม่มีข่าวสาร</p> : (
            <div className="mt-4 space-y-2">
                {items.map(item => (
                    <article key={item.uuid} className="rounded-lg border border-krikkit-line p-3">
                        <div className="flex flex-wrap items-center justify-between gap-2">
                            <p className="font-medium">{item.title} <span className="text-xs text-krikkit-muted">({item.slug}) · {statusLabels[item.status]} · รุ่น {item.version}</span></p>
                            <button type="button" disabled={busy} className={`rounded-lg px-2.5 py-1 text-xs ${LAB_BTN_OUTLINE}`} onClick={() => startEdit(item)}>แก้ไขข่าวนี้</button>
                        </div>
                        {item.excerpt ? <p className="mt-1 text-xs text-krikkit-muted">{item.excerpt}</p> : null}
                        <p className="mt-2 line-clamp-2 whitespace-pre-wrap text-xs">{item.body}</p>
                    </article>
                ))}
            </div>
        )}
    </section>
}
