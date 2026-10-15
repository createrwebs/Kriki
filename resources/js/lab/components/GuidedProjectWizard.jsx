import { useEffect, useRef, useState } from 'react'
import { LAB_BTN_OUTLINE, LAB_BTN_PRIMARY } from '../lib/labConstants'
import {
    BUSINESS_PRESETS,
    FIELD_TYPES,
    PAGE_LIBRARY,
    applyWizardToDraft,
    buildDataModel,
    normalizeWizardState,
    readWizardState,
    saveWizardState,
    wizardStateForBusiness,
} from '../lib/labWizard'

const STEPS = ['Business', 'Pages', 'Fields & review']

export function GuidedProjectWizard({ open, draft, onDraftChange, onClose }) {
    const [step, setStep] = useState(0)
    const [state, setState] = useState(readWizardState)
    const [pageIndex, setPageIndex] = useState(0)
    const [customPageName, setCustomPageName] = useState('')
    const dialogRef = useRef(null)
    const closeRef = useRef(null)
    const previousFocusRef = useRef(null)
    const onCloseRef = useRef(onClose)

    useEffect(() => {
        onCloseRef.current = onClose
    }, [onClose])

    useEffect(() => {
        if (! open) return undefined
        setState(readWizardState())
        setStep(0)
        setPageIndex(0)
        setCustomPageName('')
        previousFocusRef.current = document.activeElement
        const focusFrame = window.requestAnimationFrame(() => closeRef.current?.focus())
        const onKey = (event) => {
            if (event.key === 'Escape') {
                onCloseRef.current?.()
                return
            }
            if (event.key !== 'Tab') return
            const focusable = Array.from(dialogRef.current?.querySelectorAll('button, input, select, textarea, [href], [tabindex]:not([tabindex="-1"])') || [])
                .filter((element) => ! element.disabled && element.offsetParent !== null)
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
    }, [open])

    useEffect(() => {
        if (open) saveWizardState(state)
    }, [open, state])

    if (! open) return null

    const selectedPage = state.pages[pageIndex] || state.pages[0] || null
    const updateState = (next) => setState(normalizeWizardState(next))

    const chooseBusiness = (businessId) => {
        updateState(wizardStateForBusiness(businessId))
        setPageIndex(0)
    }

    const togglePage = (pageId) => {
        const exists = state.pages.some((page) => page.id === pageId)
        const nextPages = exists
            ? state.pages.filter((page) => page.id !== pageId)
            : [...state.pages, pageTemplate(pageId)]
        updateState({ ...state, pages: nextPages })
        setPageIndex(Math.max(0, Math.min(pageIndex, nextPages.length - 1)))
    }

    const addCustomPage = () => {
        const title = customPageName.trim()
        if (! title) return
        const page = { id: `custom-page-${Date.now()}`, title, fields: [] }
        updateState({ ...state, pages: [...state.pages, page] })
        setPageIndex(state.pages.length)
        setCustomPageName('')
    }

    const movePage = (index, direction) => {
        const target = index + direction
        if (target < 0 || target >= state.pages.length) return
        const pages = [...state.pages]
        ;[pages[index], pages[target]] = [pages[target], pages[index]]
        updateState({ ...state, pages })
        setPageIndex(target)
    }

    const updatePage = (index, patch) => {
        const pages = state.pages.map((page, pageIndexToUpdate) => pageIndexToUpdate === index ? { ...page, ...patch } : page)
        updateState({ ...state, pages })
    }

    const updateField = (fieldIndex, patch) => {
        if (! selectedPage) return
        const fields = selectedPage.fields.map((field, index) => index === fieldIndex ? { ...field, ...patch } : field)
        updatePage(pageIndex, { fields })
    }

    const removeField = (fieldIndex) => {
        if (! selectedPage) return
        updatePage(pageIndex, { fields: selectedPage.fields.filter((_, index) => index !== fieldIndex) })
    }

    const addField = () => {
        if (! selectedPage) return
        updatePage(pageIndex, { fields: [...selectedPage.fields, { id: `${selectedPage.id}-field-${Date.now()}`, name: 'New field', type: 'text' }] })
    }

    const apply = () => {
        onDraftChange?.(applyWizardToDraft(draft, state))
        onClose?.()
    }
    const canContinue = step === 0
        ? state.businessId !== 'custom' || Boolean(state.businessName.trim())
        : step === 1
            ? state.pages.length > 0
            : true

    return (
        <div className="fixed inset-0 z-[95] flex items-end justify-center p-0 sm:items-center sm:p-6" role="presentation">
            <button type="button" className="absolute inset-0 bg-black/45" aria-label="Close project wizard" onClick={onClose} />
            <div ref={dialogRef} role="dialog" aria-modal="true" aria-labelledby="project-wizard-title" aria-describedby="project-wizard-description" className="relative flex max-h-[94dvh] w-full flex-col overflow-y-auto rounded-t-3xl border border-krikkit-line bg-krikkit-canvas p-4 sm:max-w-3xl sm:rounded-3xl sm:p-6">
                <div className="flex items-start justify-between gap-4">
                    <div>
                        <p className="text-[11px] font-medium uppercase tracking-[0.15em] text-krikkit-accent">Project setup</p>
                        <h2 id="project-wizard-title" className="mt-1 text-xl font-semibold text-krikkit-fg">Shape the system before generating</h2>
                        <p id="project-wizard-description" className="mt-1 max-w-xl text-sm leading-relaxed text-krikkit-muted">Choose the domain, pages, and fields. You can still edit the generated brief in the composer.</p>
                    </div>
                    <button ref={closeRef} type="button" className={`shrink-0 rounded-full px-3 py-1.5 text-xs ${LAB_BTN_OUTLINE}`} onClick={onClose}>Close</button>
                </div>

                <div className="mt-5 grid grid-cols-3 gap-1.5" aria-label="Wizard steps">
                    {STEPS.map((label, index) => (
                        <button key={label} type="button" onClick={() => index <= step && setStep(index)} aria-current={step === index ? 'step' : undefined} className={`rounded-full px-2 py-2 text-[11px] ${step === index ? 'bg-krikkit-fg text-krikkit-canvas' : index < step ? 'bg-krikkit-soft text-krikkit-fg' : 'bg-krikkit-surface text-krikkit-subtle'}`}>
                            {index + 1}. {label}
                        </button>
                    ))}
                </div>

                <div className="mt-5 min-h-[20rem]">
                    {step === 0 ? <BusinessStep state={state} onChoose={chooseBusiness} onNameChange={(businessName) => updateState({ ...state, businessName })} /> : null}
                    {step === 1 ? <PagesStep state={state} onToggle={togglePage} customPageName={customPageName} onCustomPageNameChange={setCustomPageName} onAddCustom={addCustomPage} onMove={movePage} /> : null}
                    {step === 2 ? <FieldsStep state={state} pageIndex={pageIndex} selectedPage={selectedPage} onSelectPage={setPageIndex} onUpdatePage={updatePage} onUpdateField={updateField} onRemoveField={removeField} onAddField={addField} /> : null}
                </div>

                <div className="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-krikkit-line pt-4">
                    <button type="button" className={`rounded-full px-3.5 py-2 text-sm ${LAB_BTN_OUTLINE}`} onClick={() => step === 0 ? onClose?.() : setStep(step - 1)}>{step === 0 ? 'Cancel' : 'Back'}</button>
                    {step < 2 ? (
                        <button type="button" disabled={! canContinue} className={`rounded-full px-4 py-2.5 text-sm font-medium ${LAB_BTN_PRIMARY}`} onClick={() => setStep(step + 1)}>{step === 1 && state.pages.length === 0 ? 'Choose pages first' : step === 0 && state.businessId === 'custom' && ! state.businessName.trim() ? 'Describe your business first' : 'Continue'}</button>
                    ) : (
                        <button type="button" className={`rounded-full px-4 py-2.5 text-sm font-medium ${LAB_BTN_PRIMARY}`} onClick={apply}>Add project brief to prompt</button>
                    )}
                </div>
            </div>
        </div>
    )
}

function BusinessStep({ state, onChoose, onNameChange }) {
    return (
        <div>
            <h3 className="text-sm font-semibold text-krikkit-fg">What are you building?</h3>
            <div className="mt-3 grid gap-2 sm:grid-cols-2">
                {BUSINESS_PRESETS.map((business) => (
                    <button key={business.id} type="button" onClick={() => onChoose(business.id)} aria-pressed={state.businessId === business.id} className={`rounded-2xl border p-3 text-left transition ${state.businessId === business.id ? 'border-krikkit-accent bg-krikkit-soft' : 'border-krikkit-line hover:bg-krikkit-surface'}`}>
                        <span className="text-sm font-medium text-krikkit-fg">{business.title}</span>
                        <span className="mt-1 block text-xs leading-relaxed text-krikkit-muted">{business.description}</span>
                    </button>
                ))}
            </div>
            {state.businessId === 'custom' ? (
                <label className="mt-4 block">
                    <span className="mb-1.5 block text-xs font-medium text-krikkit-muted">Describe your business or system</span>
                    <input value={state.businessName} onChange={(event) => onNameChange(event.target.value)} placeholder="e.g. dental clinic management" className="h-11 w-full rounded-xl border border-krikkit-line bg-krikkit-surface px-3.5 text-sm text-krikkit-fg outline-none focus:border-krikkit-accent" autoFocus />
                </label>
            ) : null}
        </div>
    )
}

function PagesStep({ state, onToggle, customPageName, onCustomPageNameChange, onAddCustom, onMove }) {
    return (
        <div className="grid gap-5 sm:grid-cols-[1.1fr_.9fr]">
            <div>
                <h3 className="text-sm font-semibold text-krikkit-fg">Which pages should the first version include?</h3>
                <p className="mt-1 text-xs text-krikkit-muted">Select the screens that make the main workflow usable.</p>
                <div className="mt-3 grid gap-2 sm:grid-cols-2">
                    {PAGE_LIBRARY.map((page) => {
                        const selected = state.pages.some((item) => item.id === page.id)
                        return <button key={page.id} type="button" onClick={() => onToggle(page.id)} aria-pressed={selected} className={`rounded-xl border p-3 text-left ${selected ? 'border-krikkit-accent bg-krikkit-soft' : 'border-krikkit-line hover:bg-krikkit-surface'}`}><span className="text-xs font-medium text-krikkit-fg">{page.title}</span><span className="mt-1 block text-[11px] leading-relaxed text-krikkit-muted">{page.description}</span></button>
                    })}
                </div>
                <div className="mt-3 flex gap-2">
                    <input value={customPageName} onChange={(event) => onCustomPageNameChange(event.target.value)} onKeyDown={(event) => { if (event.key === 'Enter') onAddCustom() }} placeholder="Custom page name" aria-label="Custom page name" className="h-9 min-w-0 flex-1 rounded-full border border-krikkit-line bg-krikkit-surface px-3 text-xs text-krikkit-fg outline-none focus:border-krikkit-accent" />
                    <button type="button" onClick={onAddCustom} disabled={! customPageName.trim()} className={`shrink-0 rounded-full px-3 py-1.5 text-xs ${LAB_BTN_OUTLINE}`}>+ Add page</button>
                </div>
            </div>
            <div>
                <h3 className="text-sm font-semibold text-krikkit-fg">Page order</h3>
                <p className="mt-1 text-xs text-krikkit-muted">Use the arrows to set the navigation order.</p>
                <div className="mt-3 space-y-2">
                    {state.pages.length ? state.pages.map((page, index) => (
                        <div key={`${page.id}-${index}`} className="flex items-center gap-2 rounded-xl border border-krikkit-line bg-krikkit-surface px-3 py-2">
                            <span className="min-w-0 flex-1 truncate text-xs font-medium text-krikkit-fg">{index + 1}. {page.title}</span>
                            <button type="button" onClick={() => onMove(index, -1)} disabled={index === 0} className={`rounded-full px-2 py-1 text-xs ${LAB_BTN_OUTLINE}`} aria-label={`Move ${page.title} up`}>↑</button>
                            <button type="button" onClick={() => onMove(index, 1)} disabled={index === state.pages.length - 1} className={`rounded-full px-2 py-1 text-xs ${LAB_BTN_OUTLINE}`} aria-label={`Move ${page.title} down`}>↓</button>
                        </div>
                    )) : <p className="rounded-xl border border-dashed border-krikkit-line p-4 text-xs text-krikkit-muted">Select at least one page to continue.</p>}
                </div>
            </div>
        </div>
    )
}

function FieldsStep({ state, pageIndex, selectedPage, onSelectPage, onUpdatePage, onUpdateField, onRemoveField, onAddField }) {
    return (
        <div>
            <h3 className="text-sm font-semibold text-krikkit-fg">What information does each page need?</h3>
            <p className="mt-1 text-xs text-krikkit-muted">These become the first data fields and can be refined in the prompt.</p>
            {state.pages.length ? (
                <>
                    <div className="mt-4 flex gap-1.5 overflow-x-auto pb-1" role="group" aria-label="Wizard pages">
                        {state.pages.map((page, index) => <button key={`${page.id}-tab`} type="button" aria-pressed={pageIndex === index} onClick={() => onSelectPage(index)} className={`shrink-0 rounded-full px-3 py-1.5 text-xs ${pageIndex === index ? 'bg-krikkit-fg text-krikkit-canvas' : LAB_BTN_OUTLINE}`}>{page.title}</button>)}
                    </div>
                    {selectedPage ? (
                        <div className="mt-4 rounded-2xl border border-krikkit-line bg-krikkit-surface p-3.5">
                            <label className="block"><span className="mb-1.5 block text-[11px] font-medium text-krikkit-muted">Page name</span><input value={selectedPage.title} onChange={(event) => onUpdatePage(pageIndex, { title: event.target.value })} className="h-10 w-full rounded-xl border border-krikkit-line bg-krikkit-canvas px-3 text-sm text-krikkit-fg outline-none focus:border-krikkit-accent" /></label>
                            <div className="mt-4 space-y-2">
                                {selectedPage.fields.map((field, index) => <div key={field.id} className="flex flex-col gap-2 sm:flex-row"><input value={field.name} onChange={(event) => onUpdateField(index, { name: event.target.value })} aria-label={`Field ${index + 1} name`} className="h-10 min-w-0 flex-1 rounded-xl border border-krikkit-line bg-krikkit-canvas px-3 text-sm text-krikkit-fg outline-none focus:border-krikkit-accent" /><select value={field.type} onChange={(event) => onUpdateField(index, { type: event.target.value })} aria-label={`Field ${index + 1} type`} className="h-10 rounded-xl border border-krikkit-line bg-krikkit-canvas px-3 text-sm text-krikkit-fg outline-none focus:border-krikkit-accent">{FIELD_TYPES.map((type) => <option key={type.value} value={type.value}>{type.label}</option>)}</select><button type="button" onClick={() => onRemoveField(index)} className={`h-10 rounded-xl px-3 text-xs ${LAB_BTN_OUTLINE}`}>Remove</button></div>)}
                            </div>
                            <button type="button" onClick={onAddField} className={`mt-3 rounded-full px-3 py-1.5 text-xs ${LAB_BTN_OUTLINE}`}>+ Add field</button>
                        </div>
                    ) : null}
                    <div className="mt-4 rounded-2xl bg-krikkit-soft p-3.5"><p className="text-[11px] font-medium uppercase tracking-[0.14em] text-krikkit-accent">Review</p><p className="mt-1 text-xs leading-relaxed text-krikkit-muted">{state.businessName || 'Your system'} will start with {state.pages.length} page{state.pages.length === 1 ? '' : 's'} and {state.pages.reduce((total, page) => total + page.fields.filter((field) => field.name.trim()).length, 0)} defined field{state.pages.reduce((total, page) => total + page.fields.filter((field) => field.name.trim()).length, 0) === 1 ? '' : 's'}.</p></div>
                    <DataModelPreview state={state} />
                </>
            ) : <p className="mt-4 rounded-xl border border-dashed border-krikkit-line p-4 text-xs text-krikkit-muted">Go back and choose at least one page.</p>}
        </div>
    )
}

function DataModelPreview({ state }) {
    const model = buildDataModel(state)
    const entityCount = model.pages.filter((page) => page.kind === 'entity').length

    return (
        <div className="mt-4 rounded-2xl border border-krikkit-line bg-krikkit-surface p-3.5">
            <div className="flex flex-wrap items-baseline justify-between gap-2">
                <div>
                    <p className="text-[11px] font-medium uppercase tracking-[0.14em] text-krikkit-accent">Data model preview</p>
                    <p className="mt-1 text-xs text-krikkit-muted">Fields become one shared contract for tables, forms, and detail views.</p>
                </div>
                <span className="text-[11px] text-krikkit-subtle">{entityCount} entities · {model.relationships.length} relationships</span>
            </div>
            <div className="mt-3 space-y-2">
                {model.pages.map((page) => (
                    <div key={page.id} className="rounded-xl border border-krikkit-line bg-krikkit-canvas px-3 py-2">
                        <div className="flex flex-wrap items-baseline justify-between gap-2">
                            <span className="text-xs font-medium text-krikkit-fg">{page.title}</span>
                            <span className="text-[10px] uppercase tracking-[0.12em] text-krikkit-subtle">{page.kind} · {page.route}</span>
                        </div>
                        <p className="mt-1 text-[11px] leading-relaxed text-krikkit-muted">
                            {page.fields.length
                                ? page.fields.map((field) => `${field.name} (${FIELD_TYPES.find((type) => type.value === field.type)?.label || 'Text'})`).join(' · ')
                                : 'No named fields yet'}
                        </p>
                    </div>
                ))}
            </div>
            {model.relationships.length ? (
                <div className="mt-3 border-t border-krikkit-line pt-3">
                    <p className="text-[11px] font-medium text-krikkit-muted">Relationships</p>
                    <ul className="mt-1 space-y-1">
                        {model.relationships.map((relationship) => (
                            <li key={`${relationship.from}-${relationship.to}`} className="text-[11px] text-krikkit-muted">
                                {relationship.fromTitle} → {relationship.toTitle}: {relationship.label}
                            </li>
                        ))}
                    </ul>
                </div>
            ) : null}
        </div>
    )
}

function pageTemplate(pageId) {
    const page = PAGE_LIBRARY.find((item) => item.id === pageId)
    return {
        id: page?.id || pageId,
        title: page?.title || 'New page',
        fields: (page?.fields || []).map((field, index) => ({ id: `${pageId}-${index}`, name: field.name, type: field.type })),
    }
}
