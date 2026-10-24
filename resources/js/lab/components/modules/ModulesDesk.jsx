import { useEffect, useRef, useState } from 'react'
import {
    applyModuleProposal,
    cancelModuleProposal,
    embedModule,
    listModules,
    prepareModule,
    proposeModuleSchema,
    selectModule,
    setModuleState,
} from '../../lib/modules.js'
import { readWorkspaceFile } from '../../lib/chatTools.js'
import ContactInbox from './ContactInbox.jsx'
import NewsDesk from './NewsDesk.jsx'
import { CatalogDesk, OrdersDesk, PromptPaySettingsDesk } from './CommerceDesk.jsx'
import { LAB_BTN_OUTLINE, LAB_BTN_PRIMARY } from '../../lib/labConstants'

const titles = {
    contact: 'ฟอร์มติดต่อ (Contact Form)',
    news: 'ข่าวสาร & ประชาสัมพันธ์ (News Feed)',
    catalog: 'จัดการสินค้า (Catalog)',
    orders: 'คำสั่งซื้อ & ตรวจสลิป (Orders & Slips)',
    manual_payments: 'ชำระผ่าน PromptPay (PromptPay QR)',
    custom_data: 'ข้อมูลเพิ่มเติม',
    booking: 'การจองเดิม',
}

const statusText = {
    prepared: 'เตรียมแล้ว · รออนุมัติ schema',
    ready: 'พร้อมใช้งาน (Ready)',
    failed: 'ติดตั้งไม่สำเร็จ',
}

const TAB_OPTIONS = [
    { id: 'catalog', label: '📦 จัดการสินค้า', moduleKey: 'catalog' },
    { id: 'orders', label: '🛒 คำสั่งซื้อ & ตรวจสลิป', moduleKey: 'orders' },
    { id: 'promptpay', label: '💳 บัญชี PromptPay', moduleKey: 'manual_payments' },
    { id: 'news', label: '📰 ข่าวสาร & บทความ', moduleKey: 'news' },
    { id: 'contact', label: '✉️ ข้อความติดต่อ', moduleKey: 'contact' },
    { id: 'overview', label: '⚙️ ระบบ & Schema', moduleKey: null },
]

export default function ModulesDesk({ projectUuid, vfsContents = {} }) {
    const [data, setData] = useState(null)
    const [activeTab, setActiveTab] = useState('catalog')
    const [busy, setBusy] = useState(false)
    const [error, setError] = useState('')
    const [notice, setNotice] = useState('')
    const [proposal, setProposal] = useState(null)
    const running = useRef(false)
    const preparations = useRef(new Map())

    const refresh = async () => {
        const next = await listModules(projectUuid)
        setData(next)
        return next
    }

    useEffect(() => {
        setData(null)
        setProposal(null)
        setError('')
        setNotice('')
        preparations.current.clear()
        if (! projectUuid) return undefined
        let cancelled = false
        listModules(projectUuid)
            .then((next) => {
                if (! cancelled) setData(next)
            })
            .catch((err) => {
                if (! cancelled) setError(err.message)
            })
        return () => {
            cancelled = true
        }
    }, [projectUuid])

    const act = async (operation) => {
        if (running.current) return
        running.current = true
        setBusy(true)
        setError('')
        setNotice('')
        try {
            await operation?.()
            await refresh()
        } catch (err) {
            setError(err.status === 409 ? `${err.message} กรุณาโหลดข้อมูลล่าสุดก่อนลองอีกครั้ง` : err.message)
            if (err.status === 409) {
                try {
                    await refresh()
                } catch {
                    // Preserve conflict
                }
            }
        } finally {
            running.current = false
            setBusy(false)
        }
    }

    const prepare = (module) =>
        act(async () => {
            if (! preparations.current.has(module.key)) {
                preparations.current.set(module.key, {
                    key: module.key,
                    expected_revision: data.revision,
                    config: {},
                    idempotency_key: crypto.randomUUID(),
                })
            }
            try {
                await prepareModule(projectUuid, preparations.current.get(module.key))
            } catch (err) {
                if (err.status === 409) preparations.current.delete(module.key)
                throw err
            }
            preparations.current.delete(module.key)
            setNotice('ผูกโมดูลกับฐานข้อมูลแล้ว ยังไม่มีการเปลี่ยน schema หรือเปิดรับข้อมูลลูกค้า')
        })

    const embed = (module, route = '/') =>
        act(async () => {
            const { full } = await readWorkspaceFile({
                projectUuid,
                contents: vfsContents,
                path: 'src/App.jsx',
            })
            const hash = [
                ...new Uint8Array(
                    await crypto.subtle.digest('SHA-256', new TextEncoder().encode(full))
                ),
            ]
                .map((byte) => byte.toString(16).padStart(2, '0'))
                .join('')
            const result = await embedModule(projectUuid, module.uuid, route, hash)
            window.dispatchEvent(
                new CustomEvent('krikkit-module-files-installed', {
                    detail: { projectUuid, files: result.files },
                })
            )
            setNotice(`เพิ่ม ${titles[module.key] || module.key} ในหน้า ${route} แล้ว`)
        })

    const autoSetupAll = async () => {
        if (running.current) return
        running.current = true
        setBusy(true)
        setError('')
        setNotice('กำลังติดตั้งและเปิดใช้งานระบบหลังบ้านทั้งหมด (สินค้า, ตรวจสลิป, PromptPay, ข่าวสาร, ฟอร์มติดต่อ)...')
        try {
            let current = data || (await listModules(projectUuid))
            const keys = ['contact', 'news', 'catalog', 'orders', 'manual_payments']
            for (const key of keys) {
                let mod = current.modules.find((m) => m.key === key)
                if (! mod) continue
                if (! mod.selected) {
                    await selectModule(projectUuid, key, true, current.revision)
                    current = await listModules(projectUuid)
                    mod = current.modules.find((m) => m.key === key)
                }
                if (! mod.installation) {
                    await prepareModule(projectUuid, {
                        key,
                        expected_revision: current.revision,
                        config: {},
                        idempotency_key: crypto.randomUUID(),
                    })
                    current = await listModules(projectUuid)
                    mod = current.modules.find((m) => m.key === key)
                }
                if (mod.installation && mod.installation.status !== 'ready') {
                    const { proposal: p } = await proposeModuleSchema(
                        projectUuid,
                        mod.installation.uuid,
                        mod.installation.revision
                    )
                    await applyModuleProposal(projectUuid, p)
                    current = await listModules(projectUuid)
                    mod = current.modules.find((m) => m.key === key)
                }
                if (mod.installation && ! mod.installation.enabled) {
                    await setModuleState(
                        projectUuid,
                        mod.installation.uuid,
                        'enabled',
                        mod.installation.revision
                    )
                    current = await listModules(projectUuid)
                }
            }
            setData(current)
            setNotice('✅ ติดตั้งและเปิดใช้งานระบบหลังบ้าน (สินค้า, ข่าวสาร, PromptPay, ตรวจสลิป, ฟอร์มติดต่อ) สำเร็จเรียบร้อยแล้ว!')
        } catch (err) {
            setError(err?.message || 'ติดตั้งอัตโนมัติไม่สำเร็จ กรุณาลองใหม่อีกครั้ง')
        } finally {
            running.current = false
            setBusy(false)
        }
    }

    if (! projectUuid) return <p className="p-6">เปิดหรือสร้างโปรเจกต์ก่อนจัดการหลังบ้าน</p>

    const catalogMod = data?.modules?.find((m) => m.key === 'catalog')
    const ordersMod = data?.modules?.find((m) => m.key === 'orders')
    const manualPaymentsMod = data?.modules?.find((m) => m.key === 'manual_payments')
    const newsMod = data?.modules?.find((m) => m.key === 'news')
    const contactMod = data?.modules?.find((m) => m.key === 'contact')

    const catalogReady = catalogMod?.installation?.status === 'ready'
    const ordersReady = ordersMod?.installation?.status === 'ready'
    const manualPaymentsReady = manualPaymentsMod?.installation?.status === 'ready'
    const newsReady = newsMod?.installation?.status === 'ready'
    const contactReady = contactMod?.installation?.status === 'ready'

    const allReady = catalogReady && ordersReady && manualPaymentsReady && newsReady && contactReady
    const writable = Boolean(data?.business_writes_ready)

    return (
        <section className="flex h-full flex-col overflow-hidden bg-krikkit-canvas" aria-label="Owner Back-office">
            {/* Header & Sub-Navigation */}
            <div className="shrink-0 border-b border-krikkit-line bg-krikkit-surface px-6 pt-4">
                <div className="mb-3 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h1 className="text-lg font-bold text-krikkit-fg">
                            🏢 ระบบหลังบ้าน (Owner Back-Office)
                        </h1>
                        <p className="text-xs text-krikkit-muted">
                            จัดการสินค้า คำสั่งซื้อ ตรวจสอบสลิปเงิน ตั้งค่า PromptPay ข่าวสาร และฟอร์มติดต่อ
                        </p>
                    </div>
                    <div className="flex flex-wrap items-center gap-2">
                        <a
                            href={`/lab/builder?project=${encodeURIComponent(projectUuid)}`}
                            className="inline-flex items-center gap-1 rounded-lg border border-krikkit-accent bg-krikkit-soft px-3 py-1.5 text-xs font-semibold text-krikkit-accent transition hover:bg-krikkit-accent hover:text-white"
                        >
                            ✏️ แก้ไขหน้าเว็บ (Studio Builder)
                        </a>
                        {! allReady && (
                            <button
                                type="button"
                                disabled={busy}
                                onClick={autoSetupAll}
                                className={`inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-semibold ${LAB_BTN_PRIMARY}`}
                            >
                                ⚡ ติดตั้งระบบหลังบ้านทั้งหมด
                            </button>
                        )}
                        <button
                            type="button"
                            disabled={busy}
                            className={`rounded-lg px-3 py-1.5 text-xs ${LAB_BTN_OUTLINE}`}
                            onClick={() => act()}
                        >
                            ↻ โหลดล่าสุด
                        </button>
                    </div>
                </div>

                {/* Tabs */}
                <div className="flex flex-wrap gap-1">
                    {TAB_OPTIONS.map((t) => {
                        const active = activeTab === t.id
                        return (
                            <button
                                key={t.id}
                                type="button"
                                onClick={() => setActiveTab(t.id)}
                                className={[
                                    'border-b-2 px-3 py-2 text-xs font-semibold transition',
                                    active
                                        ? 'border-krikkit-accent text-krikkit-accent'
                                        : 'border-transparent text-krikkit-muted hover:text-krikkit-fg',
                                ].join(' ')}
                            >
                                {t.label}
                            </button>
                        )
                    })}
                </div>
            </div>

            {/* Content Body */}
            <div className="flex-1 overflow-y-auto p-6">
                {! data && ! error ? <p role="status">กำลังโหลดข้อมูลระบบหลังบ้าน…</p> : null}
                {error ? <p role="alert" className="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-700">{error}</p> : null}
                {notice ? <p role="status" className="mb-4 rounded-lg bg-emerald-50 p-3 text-sm text-emerald-800">{notice}</p> : null}

                {/* Active Proposal View */}
                {proposal ? (
                    <section className="mb-5 rounded-xl border border-krikkit-line bg-krikkit-surface p-4" aria-label="ตรวจ schema ก่อน Apply">
                        <h2 className="font-semibold text-krikkit-fg">ตรวจการติดตั้ง {titles[proposal.impact.key]}</h2>
                        <p className="mt-1 text-xs text-krikkit-muted">Supabase: {proposal.impact.project_ref} · schema รุ่น {proposal.impact.schema_version}</p>
                        <p className="mt-2 text-xs">คำขอนี้เพิ่มตารางและ RPC สำหรับโมดูล รวมถึงปิดสิทธิ์อ่านข้อมูลโดยตรงจาก browser การ Apply จะเปลี่ยนฐานข้อมูลที่ระบุด้านบน และยังไม่เปิดรับข้อมูลลูกค้า</p>
                        <details className="mt-3">
                            <summary className="cursor-pointer text-xs font-medium text-krikkit-accent">ดู SQL ที่จะใช้</summary>
                            <pre className="mt-2 max-h-60 overflow-auto rounded bg-krikkit-canvas p-2 text-[11px]">{proposal.sql}</pre>
                        </details>
                        <div className="mt-3 flex gap-2">
                            <button disabled={busy} className={`rounded-lg px-3 py-1.5 text-xs ${LAB_BTN_PRIMARY}`} onClick={() => act(async () => { await applyModuleProposal(projectUuid, proposal); setProposal(null); setNotice('ติดตั้ง schema เรียบร้อยแล้ว') })}>Apply schema นี้</button>
                            <button disabled={busy} className={`rounded-lg px-3 py-1.5 text-xs ${LAB_BTN_OUTLINE}`} onClick={() => act(async () => { await cancelModuleProposal(projectUuid, proposal); setProposal(null); setNotice('ยกเลิกคำขอแล้ว') })}>ยกเลิก</button>
                        </div>
                    </section>
                ) : null}

                {/* Tab: Catalog (สินค้า) */}
                {activeTab === 'catalog' && (
                    <div>
                        <div className="mb-4 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h2 className="text-base font-semibold text-krikkit-fg">📦 จัดการสินค้าและราคา</h2>
                                <p className="text-xs text-krikkit-muted">เพิ่มสินค้า แก้ไขชื่อ รายละเอียด ราคาบาท และเปิด/ปิดการจำหน่าย</p>
                            </div>
                            {catalogReady && (
                                <button
                                    type="button"
                                    disabled={busy}
                                    className={`rounded-lg px-3 py-1.5 text-xs ${LAB_BTN_OUTLINE}`}
                                    onClick={() => embed(catalogMod.installation, '/shop')}
                                >
                                    + ติดตั้งหน้าร้านค้าใน /shop
                                </button>
                            )}
                        </div>
                        {catalogReady ? (
                            <CatalogDesk
                                key={catalogMod.installation.uuid}
                                projectUuid={projectUuid}
                                module={catalogMod.installation}
                                writable={writable && catalogMod.installation.enabled}
                            />
                        ) : (
                            <div className="rounded-xl border border-dashed border-krikkit-line p-6 text-center">
                                <p className="mb-2 text-sm text-krikkit-muted">โมดูลร้านค้ายังไม่ได้ติดตั้งในฐานข้อมูล</p>
                                <button
                                    type="button"
                                    disabled={busy}
                                    onClick={autoSetupAll}
                                    className={`rounded-lg px-4 py-2 text-xs font-semibold ${LAB_BTN_PRIMARY}`}
                                >
                                    ⚡ ติดตั้งและเปิดใช้งานโมดูลสินค้าเดี๋ยวนี้
                                </button>
                            </div>
                        )}
                    </div>
                )}

                {/* Tab: Orders (คำสั่งซื้อ & ตรวจสลิป) */}
                {activeTab === 'orders' && (
                    <div>
                        <div className="mb-4 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h2 className="text-base font-semibold text-krikkit-fg">🛒 รายการคำสั่งซื้อและตรวจสลิป</h2>
                                <p className="text-xs text-krikkit-muted">ตรวจดูคำสั่งซื้อของลูกค้า ยอดเงิน สินค้าที่สั่ง และตรวจสลิปโอนเงิน PromptPay</p>
                            </div>
                            {ordersReady && (
                                <button
                                    type="button"
                                    disabled={busy}
                                    className={`rounded-lg px-3 py-1.5 text-xs ${LAB_BTN_OUTLINE}`}
                                    onClick={() => embed(ordersMod.installation, '/')}
                                >
                                    + ฝังวิดเจ็ตตะกร้าสินค้าในหน้าหลัก
                                </button>
                            )}
                        </div>
                        {ordersReady ? (
                            <OrdersDesk
                                key={ordersMod.installation.uuid}
                                projectUuid={projectUuid}
                                module={ordersMod.installation}
                                paymentModule={manualPaymentsMod?.installation || null}
                                writable={writable && ordersMod.installation.enabled}
                            />
                        ) : (
                            <div className="rounded-xl border border-dashed border-krikkit-line p-6 text-center">
                                <p className="mb-2 text-sm text-krikkit-muted">โมดูลคำสั่งซื้อยังไม่ได้ติดตั้งในฐานข้อมูล</p>
                                <button
                                    type="button"
                                    disabled={busy}
                                    onClick={autoSetupAll}
                                    className={`rounded-lg px-4 py-2 text-xs font-semibold ${LAB_BTN_PRIMARY}`}
                                >
                                    ⚡ ติดตั้งและเปิดใช้งานระบบคำสั่งซื้อ
                                </button>
                            </div>
                        )}
                    </div>
                )}

                {/* Tab: PromptPay Settings */}
                {activeTab === 'promptpay' && (
                    <div>
                        <div className="mb-4 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h2 className="text-base font-semibold text-krikkit-fg">💳 ตั้งค่าการรับเงิน PromptPay</h2>
                                <p className="text-xs text-krikkit-muted">กำหนดเบอร์โทรศัพท์/เลขประจำตัวผู้เสียภาษี และชื่อบัญชีสำหรับสร้าง QR ให้ลูกค้าสแกนจ่าย</p>
                            </div>
                            {manualPaymentsReady && (
                                <button
                                    type="button"
                                    disabled={busy}
                                    className={`rounded-lg px-3 py-1.5 text-xs ${LAB_BTN_OUTLINE}`}
                                    onClick={() => embed(manualPaymentsMod.installation, '/')}
                                >
                                    + อัปเดตวิดเจ็ตชำระเงินในหน้าหลัก
                                </button>
                            )}
                        </div>
                        {manualPaymentsReady ? (
                            <PromptPaySettingsDesk
                                key={manualPaymentsMod.installation.uuid}
                                projectUuid={projectUuid}
                                module={manualPaymentsMod.installation}
                                writable={writable && manualPaymentsMod.installation.enabled}
                            />
                        ) : (
                            <div className="rounded-xl border border-dashed border-krikkit-line p-6 text-center">
                                <p className="mb-2 text-sm text-krikkit-muted">โมดูลชำระเงิน PromptPay ยังไม่ได้ติดตั้ง</p>
                                <button
                                    type="button"
                                    disabled={busy}
                                    onClick={autoSetupAll}
                                    className={`rounded-lg px-4 py-2 text-xs font-semibold ${LAB_BTN_PRIMARY}`}
                                >
                                    ⚡ ติดตั้งและเปิดใช้งานระบบชำระเงิน PromptPay
                                </button>
                            </div>
                        )}
                    </div>
                )}

                {/* Tab: News (ข่าวสาร) */}
                {activeTab === 'news' && (
                    <div>
                        <div className="mb-4 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h2 className="text-base font-semibold text-krikkit-fg">📰 จัดการข่าวสารและบทความ</h2>
                                <p className="text-xs text-krikkit-muted">เพิ่มข่าวใหม่ แก้ไขบทความ และปรับสถานะเผยแพร่ (Draft / Published)</p>
                            </div>
                            {newsReady && (
                                <button
                                    type="button"
                                    disabled={busy}
                                    className={`rounded-lg px-3 py-1.5 text-xs ${LAB_BTN_OUTLINE}`}
                                    onClick={() => embed(newsMod.installation, '/')}
                                >
                                    + ฝังฟีดข่าวสารในหน้าหลัก
                                </button>
                            )}
                        </div>
                        {newsReady ? (
                            <NewsDesk
                                key={newsMod.installation.uuid}
                                projectUuid={projectUuid}
                                module={newsMod.installation}
                                writable={writable && newsMod.installation.enabled}
                            />
                        ) : (
                            <div className="rounded-xl border border-dashed border-krikkit-line p-6 text-center">
                                <p className="mb-2 text-sm text-krikkit-muted">โมดูลข่าวสารยังไม่ได้ติดตั้งในฐานข้อมูล</p>
                                <button
                                    type="button"
                                    disabled={busy}
                                    onClick={autoSetupAll}
                                    className={`rounded-lg px-4 py-2 text-xs font-semibold ${LAB_BTN_PRIMARY}`}
                                >
                                    ⚡ ติดตั้งและเปิดใช้งานโมดูลข่าวสาร
                                </button>
                            </div>
                        )}
                    </div>
                )}

                {/* Tab: Contact (ข้อความติดต่อ) */}
                {activeTab === 'contact' && (
                    <div>
                        <div className="mb-4 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h2 className="text-base font-semibold text-krikkit-fg">✉️ กล่องข้อความจากผู้ติดต่อ</h2>
                                <p className="text-xs text-krikkit-muted">ตรวจสอบข้อความที่ลูกค้าส่งผ่านฟอร์มหน้าเว็บ</p>
                            </div>
                            {contactReady && (
                                <button
                                    type="button"
                                    disabled={busy}
                                    className={`rounded-lg px-3 py-1.5 text-xs ${LAB_BTN_OUTLINE}`}
                                    onClick={() => embed(contactMod.installation, '/')}
                                >
                                    + ฝังฟอร์มติดต่อในหน้าหลัก
                                </button>
                            )}
                        </div>
                        {contactReady ? (
                            <ContactInbox
                                key={contactMod.installation.uuid}
                                projectUuid={projectUuid}
                                module={contactMod.installation}
                                writable={writable && contactMod.installation.enabled}
                            />
                        ) : (
                            <div className="rounded-xl border border-dashed border-krikkit-line p-6 text-center">
                                <p className="mb-2 text-sm text-krikkit-muted">โมดูลฟอร์มติดต่อยังไม่ได้ติดตั้งในฐานข้อมูล</p>
                                <button
                                    type="button"
                                    disabled={busy}
                                    onClick={autoSetupAll}
                                    className={`rounded-lg px-4 py-2 text-xs font-semibold ${LAB_BTN_PRIMARY}`}
                                >
                                    ⚡ ติดตั้งและเปิดใช้งานฟอร์มติดต่อ
                                </button>
                            </div>
                        )}
                    </div>
                )}

                {/* Tab: Overview & Database Configuration */}
                {activeTab === 'overview' && (
                    <div className="space-y-4">
                        <div className="mb-2">
                            <h2 className="text-base font-semibold text-krikkit-fg">⚙️ ภาพรวมโมดูลและการเชื่อมต่อฐานข้อมูล</h2>
                            <p className="text-xs text-krikkit-muted">เปิด/ปิดสิทธิ์โมดูล ตรวจสอบความปลอดภัยของ Schema และสิทธิ์ RPC ฝั่งฐานข้อมูล</p>
                        </div>
                        {data?.modules.map((module) => (
                            <article key={module.key} className="rounded-xl border border-krikkit-line bg-krikkit-surface p-4">
                                <div className="flex flex-wrap items-center justify-between gap-2">
                                    <h3 className="font-semibold text-krikkit-fg">{titles[module.key] || module.title}</h3>
                                    <span className="rounded-full border border-krikkit-line px-2 py-0.5 text-xs">
                                        {module.installation ? statusText[module.installation.status] || module.installation.status : 'ยังไม่ได้เตรียม'}
                                    </span>
                                </div>
                                {! module.entitled ? <p className="mt-2 text-xs text-krikkit-muted">แพ็กเกจยังไม่ได้เปิดสิทธิ์โมดูลนี้</p> : null}
                                <label className="mt-3 flex items-center gap-2 text-xs">
                                    <input
                                        type="checkbox"
                                        checked={module.selected}
                                        disabled={busy || (! module.entitled && ! module.selected)}
                                        onChange={(event) => act(() => selectModule(projectUuid, module.key, event.target.checked, data.revision))}
                                    />
                                    เลือกใช้ในโปรเจกต์นี้
                                </label>
                                {module.installation ? (
                                    <div className="mt-3 text-xs">
                                        <p className="text-krikkit-muted">Supabase: {module.installation.project_ref} · รุ่น {module.installation.revision}</p>
                                        <div className="mt-2 flex flex-wrap gap-2">
                                            {['contact', 'news', 'catalog', 'orders', 'manual_payments'].includes(module.key) &&
                                                module.installation.status !== 'ready' && (
                                                    <button
                                                        disabled={busy || ! module.selected || ! module.entitled}
                                                        className={`rounded-lg px-2.5 py-1 text-xs ${LAB_BTN_OUTLINE}`}
                                                        onClick={() =>
                                                            act(async () => {
                                                                const result = await proposeModuleSchema(
                                                                    projectUuid,
                                                                    module.installation.uuid,
                                                                    module.installation.revision
                                                                )
                                                                setProposal(result.proposal)
                                                            })
                                                        }
                                                    >
                                                        ตรวจ schema ก่อนติดตั้ง
                                                    </button>
                                                )}
                                            {module.installation.status === 'ready' && (
                                                <button
                                                    disabled={busy || (! module.installation.enabled && (! data.business_writes_ready || ! module.selected || ! module.entitled))}
                                                    className={`rounded-lg px-2.5 py-1 text-xs ${LAB_BTN_OUTLINE}`}
                                                    onClick={() =>
                                                        act(() =>
                                                            setModuleState(
                                                                projectUuid,
                                                                module.installation.uuid,
                                                                module.installation.enabled ? 'disabled' : 'enabled',
                                                                module.installation.revision
                                                            )
                                                        )
                                                    }
                                                >
                                                    {module.installation.enabled ? 'ปิดรับข้อมูลใหม่' : 'เปิดใช้งาน'}
                                                </button>
                                            )}
                                        </div>
                                    </div>
                                ) : (
                                    <button
                                        disabled={busy || ! module.selected || ! module.entitled}
                                        className={`mt-3 rounded-lg px-3 py-1 text-xs ${LAB_BTN_PRIMARY}`}
                                        onClick={() => prepare(module)}
                                    >
                                        เตรียมฐานข้อมูล
                                    </button>
                                )}
                            </article>
                        ))}
                    </div>
                )}
            </div>
        </section>
    )
}
