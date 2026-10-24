import { useEffect, useRef, useState } from 'react'
import { applyModuleProposal, cancelModuleProposal, embedModule, listModules, prepareModule, proposeModuleSchema, selectModule, setModuleState } from '../../lib/modules.js'
import { readWorkspaceFile } from '../../lib/chatTools.js'
import ContactInbox from './ContactInbox.jsx'
import NewsDesk from './NewsDesk.jsx'
import { CatalogDesk, OrdersDesk, PromptPaySettingsDesk } from './CommerceDesk.jsx'
import { LAB_BTN_OUTLINE, LAB_BTN_PRIMARY } from '../../lib/labConstants'

const titles = { contact: 'ฟอร์มติดต่อ', news: 'ข่าวสาร', catalog: 'สินค้า', orders: 'ตะกร้าและคำสั่งซื้อ', manual_payments: 'ชำระผ่าน PromptPay', custom_data: 'ข้อมูลเพิ่มเติม', booking: 'การจองเดิม' }
const statusText = { prepared: 'เตรียมแล้ว · รออนุมัติ schema', ready: 'schema พร้อม', failed: 'ติดตั้งไม่สำเร็จ' }

export default function ModulesDesk({ projectUuid, vfsContents = {} }) {
    const [data, setData] = useState(null)
    const [busy, setBusy] = useState(false)
    const [error, setError] = useState('')
    const [notice, setNotice] = useState('')
    const [proposal, setProposal] = useState(null)
    const running = useRef(false)
    const preparations = useRef(new Map())
    const refresh = async () => { const next = await listModules(projectUuid); setData(next); return next }
    useEffect(() => {
        setData(null); setProposal(null); setError(''); setNotice(''); preparations.current.clear()
        if (!projectUuid) return undefined
        let cancelled = false
        listModules(projectUuid).then(next => { if (!cancelled) setData(next) }).catch(err => { if (!cancelled) setError(err.message) })
        return () => { cancelled = true }
    }, [projectUuid])
    const act = async operation => {
        if (running.current) return
        running.current = true; setBusy(true); setError(''); setNotice('')
        try { await operation?.(); await refresh() }
        catch (err) {
            setError(err.status === 409 ? `${err.message} กรุณาโหลดข้อมูลล่าสุดก่อนลองอีกครั้ง` : err.message)
            if (err.status === 409) { try { await refresh() } catch { /* Preserve the original conflict. */ } }
        } finally { running.current = false; setBusy(false) }
    }
    const prepare = module => act(async () => {
        if (!preparations.current.has(module.key)) preparations.current.set(module.key, {
            key: module.key, expected_revision: data.revision, config: {}, idempotency_key: crypto.randomUUID(),
        })
        try { await prepareModule(projectUuid, preparations.current.get(module.key)) }
        catch (err) { if (err.status === 409) preparations.current.delete(module.key); throw err }
        preparations.current.delete(module.key)
        setNotice('ผูกโมดูลกับฐานข้อมูลที่เลือกแล้ว ยังไม่มีการเปลี่ยน schema หรือเปิดรับข้อมูลลูกค้า')
    })
    const embed = (module, route = '/') => act(async () => {
        const { full } = await readWorkspaceFile({ projectUuid, contents: vfsContents, path: 'src/App.jsx' })
        const hash = [...new Uint8Array(await crypto.subtle.digest('SHA-256', new TextEncoder().encode(full)))].map(byte => byte.toString(16).padStart(2, '0')).join('')
        const result = await embedModule(projectUuid, module.uuid, route, hash)
        window.dispatchEvent(new CustomEvent('krikkit-module-files-installed', { detail: { projectUuid, files: result.files } }))
        setNotice(`เพิ่ม ${titles[module.key] || module.key} ในหน้า ${route} แล้ว ทดสอบได้เมื่อเปิดโมดูล`)
    })
    if (!projectUuid) return <p className="p-6">เปิดหรือสร้างโปรเจกต์ก่อนเพิ่ม widget</p>
    const manualPaymentsInstall = data?.modules?.find(m => m.key === 'manual_payments')?.installation || null
    return <section className="h-full overflow-y-auto p-6" aria-label="Database widgets">
        <div className="mb-5 flex items-center justify-between gap-4"><h1 className="text-lg font-semibold">ฟอร์ม ข่าวสาร และร้านค้า</h1><button disabled={busy} className={`rounded-lg px-3 py-2 text-sm ${LAB_BTN_OUTLINE}`} onClick={() => act()}>โหลดล่าสุด</button></div>
        <p className="mb-4 text-sm text-krikkit-muted">เลือกโมดูลสำหรับโปรเจกต์นี้ แล้วเตรียมฐานข้อมูล Supabase ของบัญชีคุณ การเปิดใช้งานต้องผ่านการตรวจ schema และสิทธิ์ฝั่ง server</p>
        {!data && !error ? <p role="status">กำลังโหลดโมดูล…</p> : null}
        {error ? <p role="alert" className="mb-4 text-sm text-red-600">{error}</p> : null}
        {notice ? <p role="status" className="mb-4 text-sm">{notice}</p> : null}
        {proposal ? <section className="mb-5 rounded-xl border border-krikkit-line p-4" aria-label="ตรวจ schema ก่อน Apply">
            <h2 className="font-semibold">ตรวจการติดตั้ง {titles[proposal.impact.key]}</h2>
            <p className="mt-2 text-sm">Supabase: {proposal.impact.project_ref} · schema รุ่น {proposal.impact.schema_version}</p>
            <p className="mt-2 text-sm">คำขอนี้เพิ่มตารางและ RPC สำหรับโมดูล รวมถึงปิดสิทธิ์อ่านข้อมูลโดยตรงจาก browser การ Apply จะเปลี่ยนฐานข้อมูลที่ระบุด้านบน และยังไม่เปิดรับข้อมูลลูกค้า</p>
            <p className="mt-2 break-all text-xs text-krikkit-muted">หมดอายุ {new Date(proposal.expires_at).toLocaleString('th-TH')} · SHA-256 {proposal.impact_hash}</p>
            <details className="mt-3"><summary className="cursor-pointer text-sm">ดู SQL ที่จะใช้ทั้งหมด</summary><pre className="mt-2 max-h-80 overflow-auto whitespace-pre text-xs">{proposal.sql}</pre></details>
            <div className="mt-4 flex gap-3"><button disabled={busy} className={`rounded-lg px-3 py-2 text-sm ${LAB_BTN_PRIMARY}`} onClick={() => act(async () => { await applyModuleProposal(projectUuid, proposal); setProposal(null); setNotice('ติดตั้งและตรวจ schema แล้ว โมดูลยังปิดรับข้อมูลจนกว่าคุณจะเปิดใช้งาน') })}>Apply schema นี้</button><button disabled={busy} className={`rounded-lg px-3 py-2 text-sm ${LAB_BTN_OUTLINE}`} onClick={() => act(async () => { await cancelModuleProposal(projectUuid, proposal); setProposal(null); setNotice('ยกเลิกคำขอแล้ว ไม่มีการเปลี่ยน schema') })}>ยกเลิกคำขอ</button></div>
        </section> : null}
        {data && !data.business_writes_ready ? <p className="mb-5 rounded-lg border border-krikkit-line p-3 text-sm">ระบบ widget ฐานข้อมูลยังอยู่ระหว่างตรวจความพร้อม เตรียมการตั้งค่าได้ แต่ยังเปิดรับข้อมูลลูกค้าหรือชำระเงินจริงไม่ได้</p> : null}
        <div className="space-y-4">{data?.modules.map(module => <article key={module.key} className="rounded-xl border border-krikkit-line p-4">
            <h2 className="font-semibold">{titles[module.key] || module.title}</h2>
            {module.legacy ? <p className="mt-2 text-sm text-krikkit-muted">มีฟอร์มจองเดิม {data.legacy_booking_forms} ฟอร์ม จัดการต่อได้ที่เมนู Forms ข้อมูลเดิมไม่ถูกย้าย</p> : <>
                {!module.entitled ? <p className="mt-2 text-sm text-krikkit-muted">แพ็กเกจยังไม่ได้เปิดสิทธิ์โมดูลนี้</p> : null}
                <label className="mt-3 flex items-center gap-2 text-sm"><input type="checkbox" checked={module.selected} disabled={busy || (!module.entitled && !module.selected)} onChange={event => act(() => selectModule(projectUuid, module.key, event.target.checked, data.revision))} />เลือกใช้ในโปรเจกต์นี้</label>
                {module.dependencies.length ? <p className="mt-2 text-sm text-krikkit-muted">ต้องมี: {module.dependencies.map(key => titles[key]).join(', ')}</p> : null}
                {module.installation ? <div className="mt-3 text-sm"><p>{statusText[module.installation.status]} · รุ่น {module.installation.revision}</p><p className="mt-1 text-krikkit-muted">Supabase: {module.installation.project_ref}</p><p className="mt-1">{module.installation.enabled ? 'เปิดใช้งาน' : 'ยังไม่เปิดใช้งาน'}</p>
                    {['contact', 'news', 'catalog', 'orders', 'manual_payments'].includes(module.key) && module.installation.status !== 'ready' ? <button disabled={busy || !module.selected || !module.entitled} className={`mt-3 rounded-lg px-3 py-2 ${LAB_BTN_OUTLINE}`} onClick={() => act(async () => { const result = await proposeModuleSchema(projectUuid, module.installation.uuid, module.installation.revision); setProposal(result.proposal) })}>ตรวจ schema ก่อนติดตั้ง</button> : null}
                    {module.installation.status === 'ready' ? <button disabled={busy || (!module.installation.enabled && (!data.business_writes_ready || !module.selected || !module.entitled))} className={`mt-3 rounded-lg px-3 py-2 ${LAB_BTN_OUTLINE}`} onClick={() => act(() => setModuleState(projectUuid, module.installation.uuid, module.installation.enabled ? 'disabled' : 'enabled', module.installation.revision))}>{module.installation.enabled ? 'ปิดรับข้อมูลใหม่' : 'เปิดใช้งาน'}</button> : null}
                    {module.key === 'contact' && module.installation.status === 'ready' ? <><button disabled={busy || !module.selected || !module.entitled} className={`ml-3 mt-3 rounded-lg px-3 py-2 ${LAB_BTN_OUTLINE}`} onClick={() => embed(module.installation)}>เพิ่มฟอร์มในหน้าหลัก</button><ContactInbox key={module.installation.uuid} projectUuid={projectUuid} module={module.installation} writable={module.installation.enabled && module.entitled && module.selected && data.business_writes_ready} /></> : null}
                    {module.key === 'news' && module.installation.status === 'ready' ? <><button disabled={busy || !module.selected || !module.entitled} className={`ml-3 mt-3 rounded-lg px-3 py-2 ${LAB_BTN_OUTLINE}`} onClick={() => embed(module.installation)}>เพิ่มข่าวสารในหน้าหลัก</button><NewsDesk key={module.installation.uuid} projectUuid={projectUuid} module={module.installation} writable={module.installation.enabled && module.entitled && module.selected && data.business_writes_ready} /></> : null}
                    {module.key === 'catalog' && module.installation.status === 'ready' ? <><button disabled={busy || !module.selected || !module.entitled} className={`ml-3 mt-3 rounded-lg px-3 py-2 ${LAB_BTN_OUTLINE}`} onClick={() => embed(module.installation)}>เพิ่มหน้าร้านค้าและตะกร้าในหน้าหลัก</button><CatalogDesk key={module.installation.uuid} projectUuid={projectUuid} module={module.installation} writable={module.installation.enabled && module.entitled && module.selected && data.business_writes_ready} /></> : null}
                    {module.key === 'orders' && module.installation.status === 'ready' ? <><button disabled={busy || !module.selected || !module.entitled} className={`ml-3 mt-3 rounded-lg px-3 py-2 ${LAB_BTN_OUTLINE}`} onClick={() => embed(module.installation)}>เพิ่มตะกร้าสินค้าในหน้าหลัก</button><OrdersDesk key={module.installation.uuid} projectUuid={projectUuid} module={module.installation} paymentModule={manualPaymentsInstall} writable={module.installation.enabled && module.entitled && module.selected && data.business_writes_ready} /></> : null}
                    {module.key === 'manual_payments' && module.installation.status === 'ready' ? <><button disabled={busy || !module.selected || !module.entitled} className={`ml-3 mt-3 rounded-lg px-3 py-2 ${LAB_BTN_OUTLINE}`} onClick={() => embed(module.installation)}>อัปเดตวิดเจ็ตชำระเงินในหน้าหลัก</button><PromptPaySettingsDesk key={module.installation.uuid} projectUuid={projectUuid} module={module.installation} writable={module.installation.enabled && module.entitled && module.selected && data.business_writes_ready} /></> : null}
                </div> : <button disabled={busy || !module.selected || !module.entitled} className={`mt-3 rounded-lg px-3 py-2 text-sm ${LAB_BTN_PRIMARY}`} onClick={() => prepare(module)}>เตรียมฐานข้อมูล</button>}
            </>}
        </article>)}</div>
    </section>
}
