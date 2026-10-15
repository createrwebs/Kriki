import { useEffect, useState } from 'react'
import { createForm, embedForm, listForms, saveFormVersion, setFormState } from '../../lib/forms.js'
import { LAB_BTN_OUTLINE, LAB_BTN_PRIMARY } from '../../lib/labConstants'
import { FormEditor } from './FormEditor.jsx'
import { FormSubmissions } from './FormSubmissions.jsx'

export function FormsDesk({ projectUuid, onAskLab }) {
    const [forms, setForms] = useState([])
    const [selected, setSelected] = useState(null)
    const [editing, setEditing] = useState(false)
    const [title, setTitle] = useState('จองโต๊ะ')
    const [busy, setBusy] = useState(false)
    const [error, setError] = useState('')
    const [notice, setNotice] = useState('')
    useEffect(() => {
        const saved = async event => {
            if (event.detail?.projectUuid !== projectUuid) return
            try {
                const data = await listForms(projectUuid)
                setForms(data.forms)
                setSelected(current => data.forms.find(form => form.uuid === current?.uuid) || current)
                setNotice('บันทึกข้อเสนอเป็นแบบร่างแล้ว เปิดฟอร์มตรวจและกดเปิดรับเมื่อต้องการใช้รุ่นใหม่')
            } catch (err) { setError(err.message) }
        }
        window.addEventListener('krikkit-form-saved', saved)
        return () => window.removeEventListener('krikkit-form-saved', saved)
    }, [projectUuid])
    useEffect(() => { if (! projectUuid) return undefined; let cancelled = false; setBusy(true); listForms(projectUuid).then(data => { if (! cancelled) setForms(data.forms) }).catch(err => { if (! cancelled) setError(err.message) }).finally(() => { if (! cancelled) setBusy(false) }); return () => { cancelled = true } }, [projectUuid])
    const act = async (operation) => {
        setBusy(true); setError(''); setNotice('')
        try {
            const result = await operation()
            if (result?.files) window.dispatchEvent(new CustomEvent('krikkit-form-files-installed', { detail: { projectUuid, files: result.files } }))
            if (result?.uuid) { setSelected(result); setForms(current => [result, ...current.filter(item => item.uuid !== result.uuid)]) }
            return result
        } catch (err) { setError(err.status === 409 ? `${err.message} ข้อมูลที่กำลังแก้ยังอยู่` : err.message) }
        finally { setBusy(false) }
    }
    if (! projectUuid) return <p className="p-6">เปิดโปรเจกต์ก่อนสร้างฟอร์ม</p>
    return <div className="h-full overflow-y-auto p-5 text-krikkit-fg sm:p-7">
        <h1 className="mb-2 text-2xl font-semibold">ฟอร์มและรายการจอง</h1><p className="mb-6 max-w-2xl text-sm text-krikkit-muted">ฐานข้อมูลใช้ Supabase ของบัญชีคุณ รายการแยกตามฟอร์ม · รุ่นแรกส่งคำขอรอร้านยืนยัน ไม่มีการรับเงินอัตโนมัติ</p>
        {error ? <p role="alert" className="mb-4 rounded-lg bg-red-500/10 p-3 text-sm text-red-600">{error}</p> : null}{notice ? <p role="status" className="mb-4 text-sm">{notice}</p> : null}
        {editing && selected ? <FormEditor key={selected.uuid} form={selected} busy={busy} onClose={() => setEditing(false)} onSave={(definition, version) => act(() => saveFormVersion(projectUuid, selected.uuid, version, definition))} onActivate={() => act(() => setFormState(projectUuid, selected.uuid, selected.latest_version, 'active'))} onAskLab={onAskLab} /> : <>
            <form onSubmit={event => { event.preventDefault(); act(() => createForm(projectUuid, title)).then(result => { if (result) setEditing(true) }) }} className="mb-6 flex flex-wrap items-end gap-3"><label className="text-sm">ชื่อฟอร์มขอจองโต๊ะ<input className="mt-1 block rounded-lg border border-krikkit-line bg-krikkit-surface px-3 py-2" value={title} onChange={event => setTitle(event.target.value)} maxLength={120} required /></label><button disabled={busy} className={`rounded-lg px-4 py-2 text-sm ${LAB_BTN_PRIMARY}`}>สร้างฟอร์มต้นแบบ</button></form>
            {busy ? <p role="status">กำลังดำเนินการ…</p> : null}
            {! busy && ! forms.length ? <p className="text-sm text-krikkit-muted">ยังไม่มีฟอร์ม เริ่มจากต้นแบบด้านบน แล้วเพิ่มช่องตามธุรกิจได้</p> : null}
            <div className="space-y-2">{forms.map(form => <div key={form.uuid} className={`flex flex-wrap items-center justify-between gap-3 rounded-xl p-3 ${selected?.uuid === form.uuid ? 'bg-krikkit-soft' : 'bg-krikkit-surface'}`}><button className="text-left" onClick={() => setSelected(form)}><span className="font-medium">{form.title}</span><span className="mt-1 block text-xs text-krikkit-muted">{({ draft: 'แบบร่าง', active: 'เปิดรับคำขอ', closed: 'ปิดรับ' })[form.status]} · รุ่น {form.latest_version}</span></button><div className="flex gap-3 text-sm"><button disabled={busy} onClick={() => { setSelected(form); setEditing(true) }}>แก้แบบฟอร์ม</button>{form.status === 'active' ? <button disabled={busy} onClick={() => act(() => setFormState(projectUuid, form.uuid, form.latest_version, 'closed'))}>ปิดรับ</button> : null}</div></div>)}</div>
            {selected ? <><div className="mt-5 flex flex-wrap gap-3"><button disabled={busy} className={`rounded-lg px-3 py-2 text-sm ${LAB_BTN_OUTLINE}`} onClick={() => act(() => embedForm(projectUuid, selected.uuid)).then(result => { if (result) setNotice('ติดตั้งไฟล์ฟอร์มแล้ว กดคำสั่งถัดไปเพื่อให้ AI นำไปแสดงในหน้าเว็บ') })}>ติดตั้งฟอร์มในเว็บ</button>{onAskLab ? <button className={`rounded-lg px-3 py-2 text-sm ${LAB_BTN_OUTLINE}`} onClick={() => onAskLab(`เพิ่มหน้า /booking ในเว็บเดิม โดย import BookingForm จาก ./krikkit-forms/BookingForm.jsx และ config จาก ./krikkit-forms/config.json แล้วใช้ <BookingForm {...config} /> ห้ามเขียน mock submit หรือแก้ client มาตรฐานของฟอร์ม คง navigation หน้าเดิมทั้งหมดไว้`)}>ให้ AI เพิ่มหน้าจอง</button> : null}</div><FormSubmissions key={selected.uuid} projectUuid={projectUuid} formUuid={selected.uuid} /></> : null}
        </>}
    </div>
}
export default FormsDesk
