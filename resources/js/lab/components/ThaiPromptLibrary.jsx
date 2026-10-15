import { useMemo, useState } from 'react'
import { LAB_BTN_OUTLINE, LAB_BTN_PRIMARY } from '../lib/labConstants'
import { THAI_PROMPT_CATEGORIES, THAI_PROMPT_LIBRARY } from '../lib/labThaiPrompts'

const INITIAL_VISIBLE = 8

export function filterThaiPrompts(prompts, query = '', category = 'ทั้งหมด') {
    const normalizedQuery = query.trim().toLowerCase()
    return prompts.filter((prompt) => {
        if (category !== 'ทั้งหมด' && prompt.category !== category) return false
        if (! normalizedQuery) return true
        return [prompt.title, prompt.category, prompt.prompt, ...prompt.tags]
            .join(' ')
            .toLowerCase()
            .includes(normalizedQuery)
    })
}

export function ThaiPromptLibrary({ live = false, onPickPrompt }) {
    const [query, setQuery] = useState('')
    const [category, setCategory] = useState('ทั้งหมด')
    const [expanded, setExpanded] = useState(false)

    const filteredPrompts = useMemo(() => filterThaiPrompts(THAI_PROMPT_LIBRARY, query, category), [category, query])
    const visiblePrompts = expanded ? filteredPrompts : filteredPrompts.slice(0, INITIAL_VISIBLE)

    if (live) return null

    const resultCount = filteredPrompts.length
    const hasMore = resultCount > INITIAL_VISIBLE

    return (
        <section className="mx-auto mt-5 w-full max-w-4xl px-4 pb-6 sm:px-6" aria-labelledby="thai-prompt-library-title">
            <div className="rounded-3xl border border-krikkit-line/80 bg-krikkit-surface p-4 sm:p-5">
                <div className="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p className="text-[11px] font-medium uppercase tracking-[0.15em] text-krikkit-accent">Thai prompt library</p>
                        <h2 id="thai-prompt-library-title" className="mt-1 text-base font-semibold text-krikkit-fg">ตัวอย่าง Prompt ภาษาไทย</h2>
                        <p className="mt-1 max-w-2xl text-xs leading-relaxed text-krikkit-muted">เลือก prompt ธุรกิจที่ใกล้เคียง แล้วแก้ชื่อ ฟิลด์ และรายละเอียดต่อใน composer ได้ทันที</p>
                    </div>
                    <span className="rounded-full bg-krikkit-soft px-2.5 py-1 text-[11px] text-krikkit-muted">{THAI_PROMPT_LIBRARY.length} ชุด</span>
                </div>

                <div className="mt-4 grid gap-2 sm:grid-cols-[1fr_auto]">
                    <label className="relative block">
                        <span className="sr-only">ค้นหา prompt ภาษาไทย</span>
                        <input
                            value={query}
                            onChange={(event) => { setQuery(event.target.value); setExpanded(false) }}
                            placeholder="ค้นหาธุรกิจ เช่น อู่ซ่อมรถ ร้านอาหาร หรือ CRM"
                            className="h-10 w-full rounded-xl border border-krikkit-line bg-krikkit-canvas px-3.5 text-sm text-krikkit-fg outline-none placeholder:text-krikkit-subtle focus:border-krikkit-accent"
                        />
                    </label>
                    <button type="button" onClick={() => { setQuery(''); setCategory('ทั้งหมด'); setExpanded(false) }} className={`rounded-xl px-3 text-xs ${LAB_BTN_OUTLINE}`}>ล้างตัวกรอง</button>
                </div>

                <div className="mt-3 flex gap-1.5 overflow-x-auto pb-1" role="group" aria-label="หมวดหมู่ prompt ภาษาไทย">
                    {['ทั้งหมด', ...THAI_PROMPT_CATEGORIES].map((item) => (
                        <button key={item} type="button" aria-pressed={category === item} onClick={() => { setCategory(item); setExpanded(false) }} className={`shrink-0 rounded-full px-2.5 py-1.5 text-[11px] ${category === item ? 'bg-krikkit-fg text-krikkit-canvas' : LAB_BTN_OUTLINE}`}>
                            {item}
                        </button>
                    ))}
                </div>

                <p aria-live="polite" className="mt-3 text-[11px] text-krikkit-subtle">พบ {resultCount} ชุด · กด “ใช้ prompt” เพื่อใส่ลงช่องเขียน</p>
                <div className="mt-2 grid gap-2 sm:grid-cols-2">
                    {visiblePrompts.map((prompt) => (
                        <article key={prompt.id} className="flex min-w-0 flex-col rounded-2xl border border-krikkit-line bg-krikkit-canvas p-3">
                            <div className="flex items-start justify-between gap-2">
                                <div className="min-w-0">
                                    <h3 className="truncate text-sm font-medium text-krikkit-fg">{prompt.title}</h3>
                                    <p className="mt-0.5 text-[10px] text-krikkit-accent">{prompt.category}</p>
                                </div>
                                <span className="shrink-0 text-[10px] text-krikkit-subtle">Prompt</span>
                            </div>
                            <p className="mt-2 line-clamp-3 text-[11px] leading-relaxed text-krikkit-muted">{prompt.prompt}</p>
                            <div className="mt-3 flex items-center justify-between gap-2">
                                <div className="flex min-w-0 flex-wrap gap-1">
                                    {prompt.tags.slice(0, 2).map((tag) => <span key={tag} className="rounded-full bg-krikkit-soft px-2 py-1 text-[10px] text-krikkit-muted">{tag}</span>)}
                                </div>
                                <button type="button" onClick={() => onPickPrompt?.(prompt.prompt)} className={`shrink-0 rounded-full px-3 py-1.5 text-[11px] font-medium ${LAB_BTN_PRIMARY}`}>ใช้ prompt</button>
                            </div>
                        </article>
                    ))}
                </div>

                {! visiblePrompts.length ? <p className="mt-3 rounded-2xl border border-dashed border-krikkit-line p-5 text-center text-xs text-krikkit-muted">ไม่พบ prompt ที่ตรงกับคำค้น ลองค้นหาคำอื่นหรือเลือก “ทั้งหมด”</p> : null}
                {hasMore ? (
                    <button type="button" onClick={() => setExpanded((value) => ! value)} className={`mt-3 w-full rounded-xl px-3 py-2 text-xs ${LAB_BTN_OUTLINE}`}>
                        {expanded ? 'แสดงน้อยลง' : `แสดงทั้งหมด ${resultCount} ชุด`}
                    </button>
                ) : null}
            </div>
        </section>
    )
}
