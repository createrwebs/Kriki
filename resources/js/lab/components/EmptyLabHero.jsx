import { RefreshCw } from 'lucide-react'
import { useCallback, useEffect, useLayoutEffect, useRef, useState } from 'react'
import { LAB_BTN_OUTLINE, pickRandomSeeds, SEEDS, seedIsCustomizable } from '../lib/labConstants'
import { LabTooltip } from './LabTooltip'
import { SeedCustomizeModal } from './SeedCustomizeModal'
import { SeedIcon } from './SeedIcon'
import { LabGlyph } from './Icons'

const SEED_MAX = 4

function nextSeeds() {
    return pickRandomSeeds(Math.min(SEED_MAX, SEEDS.length))
}

export function EmptyLabHero({ live, projectType = 'react', onProjectTypeChange }) {
    const [showProjectsModal, setShowProjectsModal] = useState(false)
    const [projectsList, setProjectsList] = useState([])
    const [draftsList, setDraftsList] = useState([])
    const [loadingList, setLoadingList] = useState(false)

    const fetchProjectsAndDrafts = useCallback(async () => {
        setLoadingList(true)
        try {
            const [projRes, draftRes] = await Promise.all([
                fetch('/lab/builder/projects', { credentials: 'same-origin', headers: { Accept: 'application/json' } }),
                fetch('/lab/builder/drafts', { credentials: 'same-origin', headers: { Accept: 'application/json' } }),
            ])
            const projJson = await projRes.json().catch(() => ({}))
            const draftJson = await draftRes.json().catch(() => ({}))
            if (Array.isArray(projJson.projects)) setProjectsList(projJson.projects)
            if (Array.isArray(draftJson.drafts)) setDraftsList(draftJson.drafts)
        } catch {
            // ignore
        } finally {
            setLoadingList(false)
        }
    }, [])

    useEffect(() => {
        if (! live) {
            fetchProjectsAndDrafts()
        }
    }, [live, fetchProjectsAndDrafts])

    const handleDeleteProject = async (uuid, title) => {
        if (! window.confirm(`ต้องการลบโปรเจกต์ "${title || uuid}" ออกจากระบบใช่หรือไม่? (คืนโควต้าโปรเจกต์ทันที)`)) return
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        const res = await fetch(`/lab/builder/projects/${encodeURIComponent(uuid)}`, {
            method: 'DELETE',
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...(token ? { 'X-CSRF-TOKEN': token } : {}),
            },
        })
        if (res.ok) {
            setProjectsList((prev) => prev.filter((p) => p.uuid !== uuid))
        }
    }

    const handleDeleteDraft = async (uuid, title) => {
        if (! window.confirm(`ต้องการลบแบบร่าง "${title || uuid}" ออกจากระบบใช่หรือไม่?`)) return
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        const res = await fetch(`/lab/builder/drafts/${encodeURIComponent(uuid)}`, {
            method: 'DELETE',
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...(token ? { 'X-CSRF-TOKEN': token } : {}),
            },
        })
        if (res.ok) {
            setDraftsList((prev) => prev.filter((d) => d.uuid !== uuid))
        }
    }

    return (
        <div
            className={[
                'lab-layout-pane mx-auto w-full max-w-2xl overflow-hidden px-4 sm:px-6',
                live ? 'mb-0 max-h-0 -translate-y-3 opacity-0' : 'mb-8 max-h-[38rem] opacity-100',
            ].join(' ')}
            aria-hidden={live}
        >
            <div className="text-center">
                <p className="mb-3 inline-flex h-5 items-center justify-center gap-1.5 text-xs font-medium uppercase leading-none tracking-[0.18em] text-krikkit-accent">
                    <LabGlyph />
                    Lab
                </p>
                <h1 className="mb-3 text-3xl font-semibold tracking-tight text-krikkit-fg sm:text-4xl">
                    What should we build?
                </h1>
                <p className="mx-auto max-w-md text-sm leading-relaxed text-krikkit-muted">
                    Describe the site or product. We’ll shape the idea here first — the build workspace opens when you’re ready.
                </p>
                {! live && (
                    <div className="mt-3 flex flex-wrap items-center justify-center gap-2">
                        <a
                            href="/lab/builder"
                            className="inline-flex items-center gap-1.5 rounded-full border border-krikkit-accent/40 bg-krikkit-soft px-3.5 py-1.5 text-xs font-semibold text-krikkit-accent transition hover:bg-krikkit-accent hover:text-white"
                        >
                            Start with a guided design template (สร้างเว็บหลายหน้า · แก้ไขก่อน Build)
                        </a>
                        <button
                            type="button"
                            onClick={() => {
                                const next = ! showProjectsModal
                                setShowProjectsModal(next)
                                if (next) fetchProjectsAndDrafts()
                            }}
                            className="inline-flex items-center gap-1.5 rounded-full border border-krikkit-line bg-krikkit-surface px-3.5 py-1.5 text-xs font-medium text-krikkit-fg transition hover:border-krikkit-accent hover:text-krikkit-accent"
                        >
                            🗂 จัดการ/ลบโปรเจกต์ที่ทำแล้ว ({projectsList.length} โปรเจกต์ · {draftsList.length} แบบร่าง)
                        </button>
                    </div>
                )}
                {! live && showProjectsModal && (
                    <div className="mx-auto mt-3 max-w-xl rounded-2xl border border-krikkit-line bg-krikkit-surface p-3.5 text-left shadow-sm">
                        <div className="mb-2 flex items-center justify-between">
                            <span className="text-xs font-semibold text-krikkit-fg">
                                🗂 โปรเจกต์และแบบร่างของคุณ (สามารถเปิดดูหรือลบออกได้)
                            </span>
                            <button
                                type="button"
                                onClick={() => setShowProjectsModal(false)}
                                className="text-xs text-krikkit-muted hover:text-krikkit-fg"
                            >
                                ✕ ปิด
                            </button>
                        </div>
                        {loadingList ? (
                            <p className="py-2 text-xs text-krikkit-muted">กำลังโหลดรายการ...</p>
                        ) : (
                            <div className="grid max-h-48 gap-3 overflow-y-auto sm:grid-cols-2">
                                <div>
                                    <p className="mb-1.5 text-[11px] font-semibold text-krikkit-muted">
                                        โปรเจกต์ที่สร้างแล้ว ({projectsList.length})
                                    </p>
                                    {projectsList.length === 0 ? (
                                        <p className="text-[11px] text-krikkit-muted">ยังไม่มีโปรเจกต์</p>
                                    ) : (
                                        <div className="space-y-1.5">
                                            {projectsList.map((proj) => (
                                                <div key={proj.uuid} className="flex items-center justify-between gap-1.5 rounded-lg border border-krikkit-line bg-krikkit-canvas px-2.5 py-1.5 text-xs">
                                                    <a href={proj.workspace_url} className="truncate font-medium text-krikkit-fg hover:text-krikkit-accent">
                                                        {proj.title || 'Untitled Project'}
                                                    </a>
                                                    <div className="flex shrink-0 items-center gap-1">
                                                        <a href={proj.workspace_url} className="rounded border border-krikkit-line px-1.5 py-0.5 text-[10px] text-krikkit-fg hover:bg-krikkit-surface">
                                                            เปิด
                                                        </a>
                                                        <button
                                                            type="button"
                                                            onClick={() => handleDeleteProject(proj.uuid, proj.title)}
                                                            className="rounded border border-red-200 bg-red-50 px-1.5 py-0.5 text-[10px] font-semibold text-red-700 hover:bg-red-100"
                                                        >
                                                            🗑 ลบ
                                                        </button>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    )}
                                </div>
                                <div>
                                    <p className="mb-1.5 text-[11px] font-semibold text-krikkit-muted">
                                        แบบร่าง Pre-Build ({draftsList.length})
                                    </p>
                                    {draftsList.length === 0 ? (
                                        <p className="text-[11px] text-krikkit-muted">ยังไม่มีแบบร่าง</p>
                                    ) : (
                                        <div className="space-y-1.5">
                                            {draftsList.map((item) => (
                                                <div key={item.uuid} className="flex items-center justify-between gap-1.5 rounded-lg border border-krikkit-line bg-krikkit-canvas px-2.5 py-1.5 text-xs">
                                                    <a href={`/lab/builder?draft=${encodeURIComponent(item.uuid)}`} className="truncate font-medium text-krikkit-fg hover:text-krikkit-accent">
                                                        {item.title || 'Untitled Draft'}
                                                    </a>
                                                    <div className="flex shrink-0 items-center gap-1">
                                                        <a href={`/lab/builder?draft=${encodeURIComponent(item.uuid)}`} className="rounded border border-krikkit-line px-1.5 py-0.5 text-[10px] text-krikkit-fg hover:bg-krikkit-surface">
                                                            แก้ต่อ
                                                        </a>
                                                        <button
                                                            type="button"
                                                            onClick={() => handleDeleteDraft(item.uuid, item.title)}
                                                            className="rounded border border-red-200 bg-red-50 px-1.5 py-0.5 text-[10px] font-semibold text-red-700 hover:bg-red-100"
                                                        >
                                                            🗑 ลบ
                                                        </button>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    )}
                                </div>
                            </div>
                        )}
                    </div>
                )}
                {! live && (
                    <div className="mx-auto mt-4 flex max-w-sm rounded-full border border-krikkit-line bg-krikkit-surface p-1" role="group" aria-label="Project type">
                        {[['react', 'React app'], ['php', 'Plain PHP + AJAX']].map(([value, label]) => (
                            <button key={value} type="button" onClick={() => onProjectTypeChange?.(value)} className={`flex-1 rounded-full px-3 py-2 text-xs font-medium transition ${projectType === value ? 'bg-krikkit-accent text-white' : 'text-krikkit-muted hover:text-krikkit-fg'}`}>
                                {label}
                            </button>
                        ))}
                    </div>
                )}
            </div>
        </div>
    )
}

function SeedChipButton({ seed, onPickSeed, className = '' }) {
    return (
        <button
            type="button"
            onClick={() => onPickSeed(seed)}
            className={[
                'inline-flex h-8 shrink-0 items-center gap-2 rounded-full px-3.5 text-xs font-medium',
                LAB_BTN_OUTLINE,
                className,
            ].join(' ')}
        >
            <SeedIcon name={seed.icon} className="size-3.5 shrink-0 opacity-70" />
            <span className="whitespace-nowrap">{seed.title}</span>
        </button>
    )
}

export function SeedChips({ live, onPickSeed, onSendSeed = null, padded = true }) {
    const [seeds, setSeeds] = useState(nextSeeds)
    const [batch, setBatch] = useState(0)
    const [customSeed, setCustomSeed] = useState(null)
    const hostRef = useRef(null)
    const measureRef = useRef(null)
    const reloadRef = useRef(null)
    const [visibleCount, setVisibleCount] = useState(SEED_MAX)

    const pickSeed = useCallback((seed) => {
        if (! seed) return
        setCustomSeed(seed)
    }, [])

    const reshuffle = useCallback(() => {
        setSeeds(nextSeeds())
        setBatch((n) => n + 1)
    }, [])

    useLayoutEffect(() => {
        if (live) return undefined

        const host = hostRef.current
        const measure = measureRef.current
        if (! host || ! measure) return undefined

        const fit = () => {
            const maxW = host.clientWidth
            const kids = Array.from(measure.children)
            const reloadW = reloadRef.current?.getBoundingClientRect().width || 32
            if (! kids.length || maxW <= 0) {
                setVisibleCount(1)
                return
            }

            const styles = window.getComputedStyle(measure)
            const gap = Number.parseFloat(styles.columnGap || styles.gap || '0') || 0
            // Chips + reload share one centered group — reserve reload + trailing gap.
            const chipBudget = Math.max(0, maxW - reloadW - gap)
            let used = 0
            let count = 0

            for (const child of kids) {
                const width = child.getBoundingClientRect().width
                const next = count === 0 ? width : used + gap + width
                if (next > chipBudget + 0.5) break
                used = next
                count += 1
            }

            setVisibleCount(Math.max(1, Math.min(SEED_MAX, count)))
        }

        fit()
        const observer = new ResizeObserver(fit)
        observer.observe(host)
        return () => observer.disconnect()
    }, [live, seeds])

    const visible = seeds.slice(0, visibleCount)

    return (
        <>
            <div
                className={[
                    'lab-layout-pane mx-auto w-full overflow-hidden',
                    padded ? 'max-w-2xl px-4 sm:px-6' : '',
                    live ? 'mt-0 max-h-0 opacity-0' : 'mt-6 max-h-24 opacity-100',
                ].join(' ')}
                aria-hidden={live}
            >
                <div ref={hostRef} className="relative flex w-full justify-center">
                    <div
                        ref={measureRef}
                        className="pointer-events-none absolute left-0 top-0 flex w-max gap-2 opacity-0"
                        aria-hidden
                    >
                        {seeds.map((seed) => (
                            <SeedChipButton
                                key={`m-${batch}-${seed.title}`}
                                seed={seed}
                                onPickSeed={() => {}}
                            />
                        ))}
                    </div>

                    <div className="flex w-max max-w-full flex-nowrap items-center justify-center gap-2 overflow-hidden">
                        {visible.map((seed) => (
                            <SeedChipButton
                                key={`${batch}-${seed.title}`}
                                seed={seed}
                                onPickSeed={pickSeed}
                            />
                        ))}
                        <LabTooltip content="Refresh">
                            <button
                                ref={reloadRef}
                                type="button"
                                onClick={reshuffle}
                                className={`inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full ${LAB_BTN_OUTLINE}`}
                                aria-label="Refresh suggestions"
                            >
                                <RefreshCw className="size-3.5" strokeWidth={1.75} aria-hidden="true" />
                            </button>
                        </LabTooltip>
                    </div>
                </div>
                <div className="mt-2.5 flex justify-center">
                    <button
                        type="button"
                        onClick={() =>
                            setCustomSeed({
                                title: 'เว็บไซต์ธุรกิจหลายหน้า (Multi-Page Website)',
                                icon: 'sparkles',
                                prompt: 'สร้างเว็บไซต์ธุรกิจหลายหน้า พร้อมหน้าแรก เกี่ยวกับเรา สินค้าและบริการ ข่าวสาร และติดต่อเรา',
                            })
                        }
                        className="inline-flex items-center gap-1.5 rounded-full border border-krikkit-line bg-krikkit-surface px-3.5 py-1 text-xs font-medium text-krikkit-fg transition hover:border-krikkit-accent hover:text-krikkit-accent"
                    >
                        ✨ ตั้งค่าเว็บหลายหน้า · แก้ไขจำนวนหน้า · ธีมสี · เปลี่ยนรูปภาพก่อน Build
                    </button>
                </div>
            </div>

            <SeedCustomizeModal
                seed={customSeed}
                open={Boolean(customSeed) && ! live}
                onClose={() => setCustomSeed(null)}
                onApply={(prompt) => {
                    onPickSeed?.(prompt)
                    setCustomSeed(null)
                }}
                onBuildNow={
                    typeof onSendSeed === 'function'
                        ? (prompt) => {
                            onPickSeed?.(prompt)
                            setCustomSeed(null)
                            window.setTimeout(() => onSendSeed(prompt), 30)
                        }
                        : null
                }
            />
        </>
    )
}
