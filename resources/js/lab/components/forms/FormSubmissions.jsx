import { useEffect, useState } from 'react'
import { getFormSubmission, listFormSubmissions, setSubmissionStatus } from '../../lib/forms.js'
import { LAB_BTN_OUTLINE } from '../../lib/labConstants'

const labels = { pending: 'รอยืนยัน', confirmed: 'ยืนยันแล้ว', cancelled: 'ยกเลิก' }
const bangkokTime = value => new Date(value).toLocaleString('th-TH', { timeZone: 'Asia/Bangkok' })
export function FormSubmissions({ projectUuid, formUuid }) {
    const [filters, setFilters] = useState({ status: '', q: '', date_from: '', date_to: '' })
    const [result, setResult] = useState({ items: [], next_cursor: null })
    const [detail, setDetail] = useState(null)
    const [error, setError] = useState('')
    const [busy, setBusy] = useState(false)
    const load = async (cursor = '') => {
        setBusy(true); setError('')
        try { setResult(await listFormSubmissions(projectUuid, formUuid, { ...filters, cursor })) }
        catch (err) { setError(err.message) }
        finally { setBusy(false) }
    }
    useEffect(() => { let cancelled = false; setBusy(true); listFormSubmissions(projectUuid, formUuid).then(data => { if (! cancelled) setResult(data) }).catch(err => { if (! cancelled) setError(err.message) }).finally(() => { if (! cancelled) setBusy(false) }); return () => { cancelled = true } }, [projectUuid, formUuid])
    const inspect = async (uuid) => { try { setDetail(await getFormSubmission(projectUuid, formUuid, uuid)); setError('') } catch (err) { setError(err.message) } }
    const transition = async (status) => { setBusy(true); try { await setSubmissionStatus(projectUuid, formUuid, detail.uuid, detail.status, status); await inspect(detail.uuid); await load() } catch (err) { setError(err.message) } finally { setBusy(false) } }
    return <div className="mt-6 space-y-4"><h3 className="text-lg font-medium">รายการขอจอง</h3>
        <form onSubmit={event => { event.preventDefault(); load() }} className="flex flex-wrap gap-2"><input aria-label="ค้นหาชื่อผู้จอง" className="rounded-lg border border-krikkit-line bg-krikkit-surface px-3 py-2 text-sm" placeholder="ค้นหาชื่อผู้จอง" value={filters.q} onChange={event => setFilters(current => ({ ...current, q: event.target.value }))} /><select aria-label="สถานะรายการจอง" value={filters.status} className="rounded-lg bg-krikkit-surface px-3 text-sm" onChange={event => setFilters(current => ({ ...current, status: event.target.value }))}><option value="">ทุกสถานะ</option>{Object.entries(labels).map(([value, label]) => <option key={value} value={value}>{label}</option>)}</select>{['date_from', 'date_to'].map((key, index) => <label key={key} className="text-xs">{index ? 'ถึงวันที่' : 'ตั้งแต่วันที่'}<input className="ml-2 rounded-lg border border-krikkit-line bg-krikkit-surface p-2" type="date" value={filters[key]} onChange={event => setFilters(current => ({ ...current, [key]: event.target.value }))} /></label>)}<button disabled={busy} className={`rounded-lg px-3 py-2 text-sm ${LAB_BTN_OUTLINE}`}>ค้นหา / รีเฟรช</button></form>
        {error ? <p role="alert" className="text-sm text-red-600">{error}</p> : null}
        {busy ? <p role="status">กำลังโหลด…</p> : null}
        {! busy && ! result.items.length ? <p className="text-sm text-krikkit-muted">ยังไม่มีรายการในเงื่อนไขนี้</p> : null}
        <div className="overflow-x-auto"><table className="w-full text-left text-sm"><thead><tr>{['ชื่อผู้จอง', 'วันเวลา (ไทย)', 'จำนวนคน', 'สถานะ', 'รายละเอียด'].map(label => <th key={label} className="p-2 font-medium">{label}</th>)}</tr></thead><tbody>{result.items.map(row => <tr key={row.uuid} className="border-t border-krikkit-line"><td className="p-2">{row.customer_name}</td><td className="p-2">{bangkokTime(row.requested_at)}</td><td className="p-2">{row.party_size}</td><td className="p-2">{labels[row.status]}</td><td className="p-2"><button onClick={() => inspect(row.uuid)}>เปิดรายการ</button></td></tr>)}</tbody></table></div>
        {result.next_cursor ? <button disabled={busy} onClick={() => load(result.next_cursor)}>หน้าถัดไป</button> : null}
        {detail ? <section className="rounded-xl border border-krikkit-line p-4"><div className="flex justify-between gap-3"><h4 className="font-medium">{detail.customer_name} · {detail.phone}</h4><button onClick={() => setDetail(null)}>ปิดรายละเอียด</button></div><p className="my-2 text-xs text-krikkit-muted">แบบฟอร์มรุ่น {detail.definition_version} · {labels[detail.status]}</p><dl className="space-y-2">{detail.definition_snapshot.fields.map(field => {
            const value = field.id.startsWith('field_') ? detail.answers?.[field.id] : detail[field.id]
            let display = value
            if (field.type === 'datetime' && value) display = bangkokTime(value)
            else if (field.type === 'select') display = field.options.find(option => option.id === value)?.label || value
            else if (typeof value === 'boolean') display = value ? 'ใช่' : 'ไม่ใช่'
            return <div key={field.id}><dt className="text-xs text-krikkit-muted">{field.label}</dt><dd className="whitespace-pre-wrap text-sm">{display ?? '—'}</dd></div>
        })}</dl><div className="mt-4 flex gap-3">{detail.status === 'pending' ? <button disabled={busy} className={`rounded-lg px-3 py-2 ${LAB_BTN_OUTLINE}`} onClick={() => transition('confirmed')}>ยืนยันการจอง</button> : null}{detail.status !== 'cancelled' ? <button disabled={busy} className={`rounded-lg px-3 py-2 ${LAB_BTN_OUTLINE}`} onClick={() => transition('cancelled')}>ยกเลิกคำขอ</button> : null}</div></section> : null}
    </div>
}
