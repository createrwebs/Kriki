import { useCallback, useEffect, useRef, useState } from 'react'
import { LAB_BTN_OUTLINE, LAB_BTN_PRIMARY } from '../lib/labConstants'
import { surveyLabDatastore } from '../lib/datastore'

const STATUS_LABELS = {
    prepared: 'เตรียมโมดูลแล้ว',
    ready: 'เคยตรวจ schema แล้ว',
    failed: 'ต้องตรวจสอบการติดตั้ง',
}

/** Project registration metadata; account-wide database administration stays outside Lab. */
export function DatastoreDesk({ projectUuid = null, onConnect = null, onChangeProject = null }) {
    const [survey, setSurvey] = useState(null)
    const [loading, setLoading] = useState(Boolean(projectUuid))
    const [error, setError] = useState('')
    const requestId = useRef(0)

    const refresh = useCallback(async (fresh = false) => {
        const current = ++requestId.current
        setSurvey(null)
        setError('')
        if (!projectUuid) {
            setLoading(false)
            return
        }
        setLoading(true)
        try {
            const payload = await surveyLabDatastore({ projectUuid, fresh })
            if (current !== requestId.current) return
            if (payload.scope !== 'project' || payload.lab_project_uuid !== projectUuid || !Array.isArray(payload.modules)) {
                throw new Error('ข้อมูลตอบกลับไม่ตรงกับโปรเจกต์ที่เปิดอยู่ กรุณาโหลดใหม่')
            }
            setSurvey(payload)
        } catch (err) {
            if (current === requestId.current) setError(err?.payload?.message || err?.message || 'โหลดข้อมูลฐานข้อมูลไม่สำเร็จ')
        } finally {
            if (current === requestId.current) setLoading(false)
        }
    }, [projectUuid])

    useEffect(() => {
        void refresh()
        return () => { ++requestId.current }
    }, [refresh])

    if (!projectUuid) {
        return <section className="p-6 text-sm text-krikkit-muted"><h2 className="font-semibold text-krikkit-fg">บันทึกโปรเจกต์ก่อนดูฐานข้อมูล</h2><p className="mt-2">เปิดหรือสร้างโปรเจกต์ เพื่อดูโมดูลและการเชื่อมต่อที่ผูกกับงานนี้</p></section>
    }

    return (
        <section className="h-full overflow-y-auto p-6 text-sm text-krikkit-fg" aria-label="ฐานข้อมูลของโปรเจกต์">
            <header className="flex flex-wrap items-start justify-between gap-4 border-b border-krikkit-line pb-5">
                <div>
                    <h2 className="text-lg font-semibold">ฐานข้อมูลของโปรเจกต์</h2>
                    <p className="mt-2 max-w-2xl leading-relaxed text-krikkit-muted">ดูการเชื่อมต่อและทะเบียนโมดูลของงานนี้ ข้อมูลหน้านี้ไม่ใช่ผลตรวจ schema สดจาก Supabase</p>
                </div>
                <button type="button" disabled={loading} onClick={() => void refresh(true)} className={`rounded-lg px-4 py-2 ${LAB_BTN_OUTLINE}`}>รีเฟรช</button>
            </header>
            {loading ? <p className="py-6 text-krikkit-muted" role="status">กำลังโหลดข้อมูลโปรเจกต์…</p> : null}
            {error ? <p className="mt-5 rounded-lg border border-red-200 bg-red-50 p-4 text-red-800" role="alert">{error}</p> : null}
            {survey ? (
                <div className="space-y-6 pt-6">
                    <section className="rounded-xl border border-krikkit-line bg-krikkit-surface p-5" aria-label="การเชื่อมต่อ Supabase">
                        <h3 className="font-semibold">{survey.ready ? 'เชื่อมต่อ Supabase แล้ว' : 'ยังไม่ได้เลือกฐานข้อมูลสำหรับบัญชี'}</h3>
                        <p className="mt-2 break-all text-krikkit-muted">{survey.ready ? survey.project_ref : 'เชื่อมต่อบัญชีและเลือก Supabase ก่อนเตรียมโมดูลใหม่'}</p>
                        <div className="mt-4 flex flex-wrap gap-3">
                            {!survey.ready && onConnect ? <button type="button" onClick={onConnect} className={`rounded-lg px-4 py-2 ${LAB_BTN_PRIMARY}`}>เชื่อมต่อ Supabase</button> : null}
                            {survey.ready && onChangeProject ? <button type="button" onClick={onChangeProject} className={`rounded-lg px-4 py-2 ${LAB_BTN_OUTLINE}`}>เปลี่ยนการเชื่อมต่อของบัญชี</button> : null}
                            <a href={`/lab/${encodeURIComponent(projectUuid)}/workspace?panel=modules`} className={`inline-flex items-center rounded-lg px-4 py-2 ${LAB_BTN_PRIMARY}`}>จัดการโมดูลและข้อมูล</a>
                        </div>
                        <p className="mt-4 text-xs leading-relaxed text-krikkit-muted">การเปลี่ยนฐานข้อมูลมีผลต่อการเชื่อมต่อของบัญชี โมดูลเดิมยังผูกกับฐานข้อมูลที่เลือกตอนติดตั้งและจะไม่ย้ายข้อมูลอัตโนมัติ</p>
                    </section>
                    <section aria-label="โมดูลของโปรเจกต์">
                        <h3 className="font-semibold">โมดูลที่ผูกกับงานนี้</h3>
                        {survey.modules.length === 0 ? (
                            <p className="mt-3 rounded-xl border border-dashed border-krikkit-line p-5 text-krikkit-muted">ยังไม่มีโมดูลที่เตรียมไว้ เริ่มจาก “จัดการโมดูลและข้อมูล” แล้วตรวจข้อเสนอ schema ก่อนกด Apply</p>
                        ) : (
                            <ul className="mt-3 space-y-3">
                                {survey.modules.map((module) => (
                                    <li key={module.uuid} className="rounded-xl border border-krikkit-line p-5">
                                        <div className="flex flex-wrap items-center justify-between gap-3">
                                            <h4 className="font-medium">{module.title}</h4>
                                            <span className="text-xs text-krikkit-muted">{STATUS_LABELS[module.status] || module.status}</span>
                                        </div>
                                        <p className="mt-2 text-xs leading-relaxed text-krikkit-muted">Schema รุ่น {module.schema_version} / ที่ต้องใช้ {module.required_schema_version} · {module.selected ? 'เลือกใช้ในโปรเจกต์' : 'ยังไม่ได้เลือกใช้'} · {module.enabled ? 'ตั้งค่าเปิดโมดูลไว้' : 'ยังไม่เปิดโมดูล'}</p>
                                        {!module.connection_matches ? <p className="mt-3 text-sm text-amber-800">เชื่อมต่อฐานข้อมูลเดิมของโมดูลก่อนใช้งาน</p> : null}
                                    </li>
                                ))}
                            </ul>
                        )}
                    </section>
                    <aside className="border-t border-krikkit-line pt-5 text-sm leading-relaxed text-krikkit-muted">
                        ตารางภายนอกและบัญชีผู้ใช้ Supabase ยังไม่มี mapping แยกตามโปรเจกต์ จึงไม่แสดงผ่านหน้านี้ ใช้ฟอร์ม ข่าว สินค้า และคำสั่งซื้อผ่านโมดูลที่ตรวจสิทธิ์แล้ว การแก้ schema ต้องมีข้อเสนอและเจ้าของกด Apply
                    </aside>
                </div>
            ) : null}
        </section>
    )
}
