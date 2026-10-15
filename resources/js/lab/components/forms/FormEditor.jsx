import { useEffect, useState } from 'react'
import { applyFieldOperations, validateFormDefinition } from '../../lib/formDefinition.js'
import { LAB_BTN_OUTLINE, LAB_BTN_PRIMARY } from '../../lib/labConstants'
import BookingForm from '../../form-runtime/BookingForm.jsx'

const inputClass = 'w-full rounded-lg border border-krikkit-line bg-krikkit-surface px-3 py-2 text-sm text-krikkit-fg'
export function FormEditor({ form, busy, onSave, onActivate, onClose, onAskLab }) {
    const [definition, setDefinition] = useState(() => structuredClone(form.definition))
    const [base, setBase] = useState(() => ({ version: form.latest_version, definition: form.definition }))
    const [fieldName, setFieldName] = useState('')
    const [fieldType, setFieldType] = useState('text')
    const [error, setError] = useState('')
    const dirty = JSON.stringify(definition) !== JSON.stringify(base.definition)
    const stale = base.version !== form.latest_version
    useEffect(() => {
        if (! dirty && stale) {
            setDefinition(structuredClone(form.definition))
            setBase({ version: form.latest_version, definition: form.definition })
        }
    }, [dirty, stale, form.definition, form.latest_version])
    const save = async () => {
        try {
            validateFormDefinition(definition, base.definition)
            const saved = await onSave(definition, base.version)
            if (saved) {
                setDefinition(structuredClone(saved.definition))
                setBase({ version: saved.latest_version, definition: saved.definition })
                setError('')
            }
        } catch (err) { setError(err.message) }
    }
    const change = (operations) => { try { setDefinition(applyFieldOperations(definition, operations)); setError('') } catch (err) { setError(err.message) } }
    const add = () => {
        if (! fieldName.trim()) return
        const field = { id: `field_${crypto.randomUUID()}`, label: fieldName.trim(), type: fieldType, required: false, archived: false }
        if (fieldType === 'select') field.options = [{ id: `option_${crypto.randomUUID()}`, label: 'ตัวเลือกแรก' }]
        change([{ op: 'add', field }]); setFieldName('')
    }
    const move = (index, offset) => {
        const ids = definition.fields.map(field => field.id)
        const target = index + offset
        if (target < 0 || target >= ids.length) return
        ;[ids[index], ids[target]] = [ids[target], ids[index]]
        change([{ op: 'reorder', ids }])
    }
    return <div className="space-y-5">
        {stale && dirty ? <p role="alert" className="rounded-lg bg-amber-500/10 p-3 text-sm">มีแบบร่างรุ่นใหม่จากอีกหน้าต่างหรือ AI สิ่งที่กำลังแก้ยังอยู่ การบันทึกจะไม่ทับรุ่นใหม่ <button className="underline" onClick={() => { setDefinition(structuredClone(form.definition)); setBase({ version: form.latest_version, definition: form.definition }) }}>โหลดรุ่นล่าสุดแทนแบบร่างที่กำลังแก้</button></p> : null}
        <div className="flex flex-wrap items-center justify-between gap-3"><div><h2 className="text-xl font-semibold">{form.title}</h2><p className="mt-1 text-xs text-krikkit-muted">แบบร่างรุ่น {form.latest_version} · รุ่นเปิดรับ {form.active_version || 'ยังไม่มี'} · การแก้ช่องยังไม่เปลี่ยนฟอร์มที่เปิดรับจนกดเปิดรับอีกครั้ง</p></div><button className={`rounded-lg px-3 py-2 text-sm ${LAB_BTN_OUTLINE}`} onClick={onClose}>กลับรายการฟอร์ม</button></div>
        <div className="grid gap-6 xl:grid-cols-2"><div>
            <h3 className="mb-3 font-medium">ช่องข้อมูล</h3>
            {definition.fields.map((field, index) => <div key={field.id} className={`mb-3 rounded-xl border border-krikkit-line p-3 ${field.archived ? 'opacity-60' : ''}`}>
                <label className="block text-xs text-krikkit-muted">ชื่อช่อง<input className={`${inputClass} mt-1`} aria-label={`ชื่อช่อง ${index + 1}`} value={field.label} disabled={busy || field.archived} onChange={event => setDefinition(current => ({ ...current, fields: current.fields.map(item => item.id === field.id ? { ...item, label: event.target.value } : item) }))} /></label>
                <div className="mt-2 flex flex-wrap items-center gap-3 text-xs"><span>{field.type}{field.archived ? ' · ซ่อนแล้ว' : ''}</span><label><input type="checkbox" checked={field.required} disabled={busy || ! field.id.startsWith('field_') || field.archived} onChange={event => change([{ op: 'required', id: field.id, required: event.target.checked }])} /> บังคับกรอก</label><button disabled={busy || index === 0} onClick={() => move(index, -1)} aria-label={`เลื่อน ${field.label} ขึ้น`}>ขึ้น</button><button disabled={busy || index === definition.fields.length - 1} onClick={() => move(index, 1)} aria-label={`เลื่อน ${field.label} ลง`}>ลง</button>{field.id.startsWith('field_') && ! field.archived ? <button disabled={busy} onClick={() => change([{ op: 'archive', id: field.id }])}>ซ่อนช่อง</button> : null}</div>
                {field.type === 'select' && ! field.archived ? <div className="mt-2 space-y-2">{field.options.map((option, optionIndex) => <input key={option.id} disabled={busy} className={inputClass} aria-label={`ตัวเลือก ${optionIndex + 1} ของ ${field.label}`} value={option.label} onChange={event => setDefinition(current => ({ ...current, fields: current.fields.map(item => item.id === field.id ? { ...item, options: item.options.map(value => value.id === option.id ? { ...value, label: event.target.value } : value) } : item) }))} />)}<button type="button" className="text-xs" disabled={busy || field.options.length >= 30} onClick={() => setDefinition(current => ({ ...current, fields: current.fields.map(item => item.id === field.id ? { ...item, options: [...item.options, { id: `option_${crypto.randomUUID()}`, label: `ตัวเลือก ${item.options.length + 1}` }] } : item) }))}>เพิ่มตัวเลือก</button></div> : null}
            </div>)}
            <div className="space-y-2 border-t border-krikkit-line pt-4"><label className="block text-sm">เพิ่มช่องใหม่<input className={`${inputClass} mt-1`} value={fieldName} onChange={event => setFieldName(event.target.value)} placeholder="เช่น แพ้อาหาร" /></label><select className={inputClass} aria-label="ชนิดช่องใหม่" value={fieldType} onChange={event => setFieldType(event.target.value)}><option value="text">ข้อความสั้น</option><option value="textarea">ข้อความยาว</option><option value="number">ตัวเลข</option><option value="select">ตัวเลือก</option><option value="checkbox">ช่องทำเครื่องหมาย</option></select><button disabled={busy || ! fieldName.trim()} className={`rounded-lg px-3 py-2 text-sm ${LAB_BTN_OUTLINE}`} onClick={add}>เพิ่มช่อง</button></div>
            {error ? <p role="alert" className="mt-3 text-sm text-red-600">{error}</p> : null}
            <div className="mt-5 flex flex-wrap gap-2"><button disabled={busy} className={`rounded-lg px-3 py-2 text-sm ${LAB_BTN_PRIMARY}`} onClick={save}>บันทึกแบบร่าง</button><button disabled={busy || dirty || stale} className={`rounded-lg px-3 py-2 text-sm ${LAB_BTN_OUTLINE}`} onClick={onActivate}>เปิดรับด้วยรุ่นที่บันทึก</button>{onAskLab ? <button disabled={busy || dirty || stale} className={`rounded-lg px-3 py-2 text-sm ${LAB_BTN_OUTLINE}`} onClick={() => onAskLab(`อ่านฟอร์ม ${form.uuid} แล้วเสนอเพิ่มช่องแพ้อาหารแบบข้อความไม่บังคับด้วย propose_form_change ห้ามบันทึกเอง`)}>ให้ AI เสนอเพิ่มช่อง</button> : null}</div>
        </div><div><h3 className="mb-3 font-medium">ตัวอย่าง — ยังไม่บันทึกคำขอ</h3><BookingForm preview previewDefinition={definition} formUuid={form.uuid} /></div></div>
    </div>
}
