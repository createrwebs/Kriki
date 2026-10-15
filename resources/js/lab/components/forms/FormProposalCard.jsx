import { useEffect, useRef, useState } from 'react'
import { saveFormVersion } from '../../lib/forms.js'
import { LAB_BTN_OUTLINE, LAB_BTN_PRIMARY } from '../../lib/labConstants'

export default function FormProposalCard({ proposal, onClose }) {
    const [busy, setBusy] = useState(false)
    const [error, setError] = useState('')
    const dialog = useRef(null)
    useEffect(() => { dialog.current.showModal() }, [])
    const apply = async () => {
        setBusy(true); setError('')
        try {
            await saveFormVersion(proposal.projectUuid, proposal.form_uuid, proposal.base_version, proposal.definition)
            window.dispatchEvent(new CustomEvent('krikkit-form-saved', { detail: { projectUuid: proposal.projectUuid } }))
            onClose()
        } catch (err) { setError(err.status === 409 ? 'ฟอร์มมีการเปลี่ยนแปลงแล้ว กรุณาขอข้อเสนอใหม่ ข้อเสนอนี้ยังไม่ได้บันทึก' : err.message) }
        finally { setBusy(false) }
    }
    return <dialog ref={dialog} onCancel={event => { if (busy) event.preventDefault(); else onClose() }} aria-labelledby="form-proposal-title" className="max-h-[85vh] w-[min(40rem,90vw)] overflow-y-auto rounded-2xl border border-krikkit-line bg-krikkit-canvas p-6 text-krikkit-fg backdrop:bg-black/60">
        <h2 id="form-proposal-title" className="text-xl font-semibold">ข้อเสนอแก้ฟอร์มจาก AI</h2>
        <p className="mt-2 text-sm text-krikkit-muted">ตรวจทานก่อนนำไปใช้ · บันทึกเป็นแบบร่างเท่านั้น ยังไม่เปลี่ยนรุ่นที่เปิดรับ</p>
        <ul className="my-4 list-inside list-disc text-sm">{proposal.summary.map((line, index) => <li key={index}>{line}</li>)}</ul>
        <div className="space-y-2">{proposal.definition.fields.filter(field => ! field.archived).map(field => <p key={field.id} className="rounded-lg bg-krikkit-soft p-2 text-sm">{field.label} · {field.type}{field.required ? ' · บังคับกรอก' : ' · ไม่บังคับ'}</p>)}</div>
        {error ? <p role="alert" className="mt-3 text-sm text-red-600">{error}</p> : null}
        <div className="mt-5 flex gap-3"><button disabled={busy} onClick={apply} className={`rounded-lg px-4 py-2 ${LAB_BTN_PRIMARY}`}>{busy ? 'กำลังบันทึก…' : 'นำไปใช้เป็นแบบร่าง'}</button><button disabled={busy} onClick={onClose} className={`rounded-lg px-4 py-2 ${LAB_BTN_OUTLINE}`}>ยกเลิก</button></div>
    </dialog>
}
