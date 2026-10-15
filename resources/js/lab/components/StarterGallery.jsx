import { useCallback, useEffect, useMemo, useRef, useState } from 'react'
import { LAB_BTN_OUTLINE, LAB_BTN_PRIMARY } from '../lib/labConstants'
import { applyStarterToDraft, LAB_STARTERS } from '../lib/labStarters'
import { GuidedProjectWizard } from './GuidedProjectWizard'

const TONE_OPTIONS = [
    { value: 'balanced', label: 'Balanced' },
    { value: 'minimal', label: 'Minimal' },
    { value: 'expressive', label: 'Expressive' },
    { value: 'playful', label: 'Playful' },
]
const ACCENT_OPTIONS = ['teal', 'blue', 'violet', 'rose', 'amber']
const DENSITY_OPTIONS = [
    { value: 'airy', label: 'Airy' },
    { value: 'comfortable', label: 'Comfortable' },
    { value: 'dense', label: 'Dense' },
]

function readPrefs() {
    try {
        const value = JSON.parse(window.localStorage.getItem('krikkit.lab.starter-prefs') || '{}')
        return {
            tone: TONE_OPTIONS.some((item) => item.value === value.tone) ? value.tone : 'balanced',
            accent: ACCENT_OPTIONS.includes(value.accent) ? value.accent : 'teal',
            density: DENSITY_OPTIONS.some((item) => item.value === value.density) ? value.density : 'comfortable',
        }
    } catch {
        return { tone: 'balanced', accent: 'teal', density: 'comfortable' }
    }
}

function savePrefs(prefs) {
    try {
        window.localStorage.setItem('krikkit.lab.starter-prefs', JSON.stringify(prefs))
    } catch {
        // Preferences are an enhancement; private browsing should not block selection.
    }
}

function StarterCard({ starter, onPreview }) {
    return (
        <article className="group min-w-[220px] overflow-hidden rounded-2xl border border-krikkit-line/80 bg-krikkit-canvas text-left transition hover:-translate-y-0.5 hover:border-krikkit-muted/50 sm:min-w-0">
            <button type="button" onClick={() => onPreview(starter)} className="block w-full text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-krikkit-accent/60" aria-label={`Preview ${starter.title}`}>
                <div className="aspect-[16/10] overflow-hidden bg-krikkit-soft">
                    <img src={starter.image} alt={starter.imageAlt} className="h-full w-full object-cover transition duration-300 group-hover:scale-[1.02]" />
                </div>
                <div className="p-3.5">
                    <div className="flex items-start justify-between gap-2">
                        <h3 className="text-sm font-semibold text-krikkit-fg">{starter.title}</h3>
                        <span className="text-[10px] font-medium text-krikkit-subtle">Preview</span>
                    </div>
                    <p className="mt-1.5 line-clamp-2 text-xs leading-relaxed text-krikkit-muted">{starter.description}</p>
                    <div className="mt-3 flex flex-wrap gap-1.5">
                        {starter.tags.slice(0, 2).map((tag) => <span key={tag} className="rounded-full bg-krikkit-soft px-2 py-1 text-[10px] text-krikkit-muted">{tag}</span>)}
                    </div>
                </div>
            </button>
        </article>
    )
}

export function StarterGallery({ live = false, draft = '', onDraftChange }) {
    const [selected, setSelected] = useState(null)
    const [prefs, setPrefs] = useState(readPrefs)
    const [category, setCategory] = useState('All')
    const [wizardOpen, setWizardOpen] = useState(false)
    const closeButtonRef = useRef(null)
    const dialogRef = useRef(null)
    const previousFocusRef = useRef(null)
    const categories = useMemo(() => ['All', ...new Set(LAB_STARTERS.map((starter) => starter.category))], [])
    const visible = category === 'All' ? LAB_STARTERS : LAB_STARTERS.filter((starter) => starter.category === category)

    useEffect(() => {
        if (! selected || live) return undefined
        previousFocusRef.current = document.activeElement
        const focusFrame = window.requestAnimationFrame(() => closeButtonRef.current?.focus())
        const onKey = (event) => {
            if (event.key === 'Escape') {
                setSelected(null)
                return
            }
            if (event.key !== 'Tab') return
            const focusable = Array.from(dialogRef.current?.querySelectorAll('button, select, [href], input, textarea, [tabindex]:not([tabindex="-1"])') || [])
                .filter((element) => ! element.hasAttribute('disabled') && element.offsetParent !== null)
            if (! focusable.length) return
            const first = focusable[0]
            const last = focusable[focusable.length - 1]
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault()
                last.focus()
            } else if (! event.shiftKey && document.activeElement === last) {
                event.preventDefault()
                first.focus()
            }
        }
        window.addEventListener('keydown', onKey)
        return () => {
            window.cancelAnimationFrame(focusFrame)
            window.removeEventListener('keydown', onKey)
            previousFocusRef.current?.focus?.()
            previousFocusRef.current = null
        }
    }, [live, selected])

    useEffect(() => {
        savePrefs(prefs)
    }, [prefs])

    if (live) return null

    const updatePref = (key, value) => setPrefs((current) => ({ ...current, [key]: value }))
    const apply = () => {
        if (! selected) return
        onDraftChange?.(applyStarterToDraft(draft, selected, prefs))
        setSelected(null)
    }
    const closeWizard = useCallback(() => setWizardOpen(false), [])

    return (
        <section className="mx-auto mt-6 w-full max-w-4xl px-4 pb-5 sm:px-6" aria-labelledby="starter-gallery-title">
            <div className="flex flex-wrap items-end justify-between gap-3">
                <div className="min-w-0">
                    <p className="text-sm font-medium text-krikkit-fg" id="starter-gallery-title">Start with a visual direction</p>
                    <p className="mt-1 text-xs text-krikkit-muted">Choose a reference. You can edit the prompt before generating.</p>
                </div>
                <div className="flex max-w-full flex-wrap justify-end gap-1.5 sm:flex-nowrap">
                    <button type="button" onClick={() => setWizardOpen(true)} className={`shrink-0 rounded-full px-2.5 py-1 text-[11px] ${LAB_BTN_OUTLINE}`}>Customize project</button>
                    <div className="flex max-w-full gap-1.5 overflow-x-auto pb-0.5" role="group" aria-label="Starter categories">
                        {categories.map((item) => (
                            <button key={item} type="button" aria-pressed={category === item} onClick={() => setCategory(item)} className={`shrink-0 rounded-full px-2.5 py-1 text-[11px] ${category === item ? 'bg-krikkit-fg text-krikkit-canvas' : LAB_BTN_OUTLINE}`}>
                                {item}
                            </button>
                        ))}
                    </div>
                </div>
            </div>

            <div className="mt-3 flex gap-3 overflow-x-auto pb-2 sm:grid sm:grid-cols-3 sm:overflow-visible">
                {visible.map((starter) => <StarterCard key={starter.id} starter={starter} onPreview={setSelected} />)}
            </div>

            {selected ? (
                <div className="fixed inset-0 z-[90] flex items-end justify-center p-0 sm:items-center sm:p-6" role="presentation">
                    <button type="button" className="absolute inset-0 bg-black/45" aria-label="Close preview" onClick={() => setSelected(null)} />
                    <div ref={dialogRef} role="dialog" aria-modal="true" aria-labelledby="starter-preview-title" aria-describedby="starter-preview-description" className="relative max-h-[92dvh] w-full overflow-y-auto rounded-t-3xl border border-krikkit-line bg-krikkit-canvas p-4 sm:max-w-3xl sm:rounded-3xl sm:p-5">
                        <div className="flex items-start justify-between gap-4">
                            <div>
                                <p className="text-[11px] font-medium uppercase tracking-[0.15em] text-krikkit-accent">Visual starter</p>
                                <h2 id="starter-preview-title" className="mt-1 text-lg font-semibold text-krikkit-fg">{selected.title}</h2>
                                <p className="mt-1 text-sm text-krikkit-muted">{selected.description}</p>
                            </div>
                            <button ref={closeButtonRef} type="button" className={`rounded-full px-3 py-1.5 text-xs ${LAB_BTN_OUTLINE}`} onClick={() => setSelected(null)}>Close</button>
                        </div>

                        <div className="mt-4 overflow-hidden rounded-2xl bg-krikkit-soft p-2 sm:p-3">
                            <img src={selected.image} alt={selected.imageAlt} className="aspect-[16/10] w-full rounded-xl object-cover" />
                        </div>

                        <div className="mt-4 grid gap-3 sm:grid-cols-3">
                            <TuneSelect label="Tone" value={prefs.tone} options={TONE_OPTIONS} onChange={(value) => updatePref('tone', value)} />
                            <TuneSelect label="Accent" value={prefs.accent} options={ACCENT_OPTIONS.map((value) => ({ value, label: value[0].toUpperCase() + value.slice(1) }))} onChange={(value) => updatePref('accent', value)} />
                            <TuneSelect label="Density" value={prefs.density} options={DENSITY_OPTIONS} onChange={(value) => updatePref('density', value)} />
                        </div>

                        <div className="mt-5 flex flex-wrap items-center justify-between gap-3">
                            <p id="starter-preview-description" className="max-w-md text-xs leading-relaxed text-krikkit-muted">This adds an editable product brief and visual direction to your prompt. It does not start generation yet.</p>
                            <button type="button" onClick={apply} className={`rounded-full px-4 py-2.5 text-sm font-medium ${LAB_BTN_PRIMARY}`}>Use this direction</button>
                        </div>
                    </div>
                </div>
            ) : null}

            <GuidedProjectWizard open={wizardOpen} draft={draft} onDraftChange={onDraftChange} onClose={closeWizard} />
        </section>
    )
}

function TuneSelect({ label, value, options, onChange }) {
    return (
        <label className="block">
            <span className="mb-1.5 block text-[11px] font-medium text-krikkit-muted">{label}</span>
            <select value={value} onChange={(event) => onChange(event.target.value)} className="h-10 w-full rounded-full border border-transparent bg-krikkit-surface px-3.5 text-sm text-krikkit-fg outline-none focus:border-krikkit-muted/40">
                {options.map((option) => <option key={option.value} value={option.value}>{option.label}</option>)}
            </select>
        </label>
    )
}
