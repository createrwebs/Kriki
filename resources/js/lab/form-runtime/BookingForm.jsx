import { useEffect, useRef, useState } from 'react'
import { createBookingSender, loadDefinition, prepareBookingPayload } from './client.js'

const inputStyle = { width: '100%', boxSizing: 'border-box', padding: '10px 12px', border: '1px solid #b8c1b8', borderRadius: 8, background: '#fff', color: '#18251b', font: 'inherit' }
const initialCore = { customer_name: '', phone: '', requested_at: '', party_size: '2', notes: '' }

export function BookingForm({ apiOrigin, formUuid, preview = false, previewDefinition = null }) {
    const [bundle, setBundle] = useState(null)
    const [values, setValues] = useState(initialCore)
    const [answers, setAnswers] = useState({})
    const [website, setWebsite] = useState('')
    const [busy, setBusy] = useState(false)
    const [error, setError] = useState('')
    const [errors, setErrors] = useState({})
    const [saved, setSaved] = useState(null)
    const sender = useRef(null)
    useEffect(() => {
        if (preview) return undefined
        let cancelled = false
        sender.current = createBookingSender(apiOrigin, formUuid)
        setBundle(null)
        setSaved(null)
        setError('')
        loadDefinition(apiOrigin, formUuid).then(data => { if (! cancelled) setBundle(data) }).catch(err => { if (! cancelled) setError(err.message) })
        return () => { cancelled = true }
    }, [apiOrigin, formUuid, preview])
    const definition = preview ? previewDefinition : bundle?.definition
    const update = (field, value) => field.id.startsWith('field_')
        ? setAnswers(current => ({ ...current, [field.id]: value }))
        : setValues(current => ({ ...current, [field.id]: value }))
    const submit = async (event) => {
        event.preventDefault()
        if (preview) { setError('นี่คือตัวอย่าง ยังไม่มีการบันทึกคำขอ'); return }
        if (busy) return
        setBusy(true); setError(''); setErrors({})
        try {
            const payload = prepareBookingPayload(values, definition.fields, answers)
            setSaved(await sender.current({ version: bundle.version, ...payload, website }))
        } catch (err) {
            setError(err.status === 409 ? 'แบบฟอร์มเปลี่ยนแล้ว ข้อมูลที่กรอกยังอยู่ กรุณาโหลดช่องล่าสุดและตรวจคำตอบอีกครั้ง' : err.message)
            setErrors(err.errors || {})
        } finally { setBusy(false) }
    }
    if (saved) return <section role="status" style={{ padding: 24 }}><h2>{saved.status === 'cancelled' ? 'คำขอนี้ถูกยกเลิกแล้ว' : saved.status === 'confirmed' ? 'ร้านยืนยันคำขอนี้แล้ว' : 'ส่งคำขอจองแล้ว'}</h2><p>{saved.status === 'pending' ? 'รอร้านยืนยัน ยังไม่ใช่การยืนยันโต๊ะว่าง' : 'นี่คือสถานะล่าสุดของรายการเดิม ไม่มีการสร้างคำขอซ้ำ'}</p><p>เลขอ้างอิง: {saved.reference}</p></section>
    if (! definition) return <p role={error ? 'alert' : 'status'}>{error || 'กำลังโหลดฟอร์มจอง…'}</p>
    return (
        <section style={{ maxWidth: 640, margin: '0 auto', padding: 24, color: '#18251b', background: '#fff', borderRadius: 12, fontFamily: 'inherit' }}>
            <h2 style={{ marginTop: 0 }}>{preview ? 'ตัวอย่างฟอร์มจองโต๊ะ' : bundle.title}</h2>
            <p>ส่งคำขอจองเพื่อให้ร้านตรวจสอบ ยังไม่ใช่การยืนยันโต๊ะว่าง</p>
            <p style={{ fontSize: 13 }}>วันเวลาใช้เวลาไทย (กรุงเทพฯ)</p>
            <form onSubmit={submit} noValidate>
                {definition.fields.filter(field => ! field.archived).map(field => {
                    const custom = field.id.startsWith('field_')
                    const value = custom ? answers[field.id] : values[field.id]
                    const fieldError = errors[`${custom ? 'answers' : 'core'}.${field.id}`]?.[0]
                    const id = `booking-${formUuid || 'preview'}-${field.id}`
                    const props = { id, name: field.id, required: field.required, disabled: busy, 'aria-invalid': Boolean(fieldError), 'aria-describedby': fieldError ? `${id}-error` : undefined, style: inputStyle }
                    let control
                    if (field.type === 'textarea') control = <textarea {...props} rows={3} value={value || ''} onChange={event => update(field, event.target.value)} />
                    else if (field.type === 'select') control = <select {...props} value={value || ''} onChange={event => update(field, event.target.value)}><option value="">เลือก…</option>{field.options.map(option => <option key={option.id} value={option.id}>{option.label}</option>)}</select>
                    else if (field.type === 'checkbox') control = <input {...props} style={{ width: 20, height: 20 }} type="checkbox" checked={Boolean(value)} onChange={event => update(field, event.target.checked)} />
                    else control = <input {...props} type={field.type === 'datetime' ? 'datetime-local' : ['number', 'integer'].includes(field.type) ? 'number' : field.id === 'phone' ? 'tel' : 'text'} min={field.id === 'party_size' ? 1 : undefined} max={field.id === 'party_size' ? 20 : undefined} value={value ?? ''} onChange={event => update(field, event.target.value)} />
                    return <div key={field.id} style={{ marginBottom: 16 }}><label htmlFor={id} style={{ display: 'block', marginBottom: 6 }}>{field.label}{field.required ? ' *' : ''}</label>{control}{fieldError ? <p id={`${id}-error`} style={{ color: '#a12323' }}>{fieldError}</p> : null}</div>
                })}
                <div aria-hidden="true" style={{ position: 'absolute', left: '-10000px', width: 1, height: 1, overflow: 'hidden' }}><label>Website<input tabIndex={-1} autoComplete="off" value={website} onChange={event => setWebsite(event.target.value)} /></label></div>
                {error ? <p role="alert" style={{ color: '#a12323' }}>{error}</p> : null}
                {! preview && error && error.includes('แบบฟอร์มเปลี่ยน') ? <button type="button" onClick={async () => { try { setBundle(await loadDefinition(apiOrigin, formUuid)); setError('ตรวจคำตอบกับช่องล่าสุดก่อนส่งอีกครั้ง'); setErrors({}) } catch (err) { setError(err.message) } }}>โหลดช่องล่าสุดโดยเก็บคำตอบไว้</button> : null}
                <button type="submit" disabled={busy} style={{ border: 0, borderRadius: 8, background: '#315d3d', color: '#fff', padding: '12px 20px', font: 'inherit', cursor: busy ? 'wait' : 'pointer' }}>{busy ? 'กำลังส่งคำขอ…' : preview ? 'ลองกรอกตัวอย่าง' : 'ส่งคำขอจอง'}</button>
            </form>
        </section>
    )
}
export default BookingForm
