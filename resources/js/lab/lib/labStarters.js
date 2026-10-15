import dashboardPreview from '../assets/starters/saas-dashboard.svg'
import portfolioPreview from '../assets/starters/editorial-portfolio.svg'
import storePreview from '../assets/starters/premium-store.svg'
import creatorPreview from '../assets/starters/creator-links.svg'
import eventPreview from '../assets/starters/event-landing.svg'
import autoRepairPreview from '../assets/starters/auto-repair.svg'

export const STARTER_DIRECTION_START = '--- Krikkit visual direction:'
export const STARTER_DIRECTION_END = '--- End visual direction ---'

export const LAB_STARTERS = [
    {
        id: 'saas-dashboard',
        title: 'SaaS dashboard',
        category: 'Product',
        description: 'A focused workspace for metrics, activity, and operations.',
        tags: ['Minimal', 'Product', 'Light'],
        image: dashboardPreview,
        imageAlt: 'SaaS dashboard with sidebar, KPI cards, and analytics chart',
        imagePrompt: 'High-fidelity web UI screenshot of a modern SaaS analytics dashboard, white canvas, compact left navigation, KPI cards, teal chart, recent activity table, soft gray surfaces, clean sans-serif typography, no logo or readable brand name, no browser frame, 16:10.',
        basePrompt: 'Build a SaaS analytics dashboard for a developer tools product. Include a compact sidebar, overview with KPI cards, revenue and usage charts, a recent activity table, date-range filtering, and responsive behavior for tablet and mobile.',
        visualPrompt: 'Use a bright white SaaS product interface with compact sidebar navigation, teal accent, soft gray surfaces, dense but readable dashboard content, restrained borders, and responsive behavior.',
    },
    {
        id: 'editorial-portfolio',
        title: 'Editorial portfolio',
        category: 'Creative',
        description: 'Typography-led storytelling for a studio, designer, or maker.',
        tags: ['Editorial', 'Expressive', 'Light'],
        image: portfolioPreview,
        imageAlt: 'Editorial portfolio with oversized type and project tiles',
        imagePrompt: 'High-fidelity editorial portfolio website screenshot, warm white background, oversized serif headline, minimal navigation, asymmetric project index, art direction image blocks, black and muted orange typography, no logo or readable brand name, no browser frame, 16:10.',
        basePrompt: 'Create a portfolio site for a product designer or creative studio. Include a strong personal introduction, selected case studies with cover imagery, project detail links, a short about section, and a clear contact CTA with an editorial reading flow.',
        visualPrompt: 'Use a warm editorial portfolio direction with oversized display typography, generous whitespace, asymmetric project index, quiet navigation, and one restrained terracotta accent.',
    },
    {
        id: 'premium-store',
        title: 'Premium store',
        category: 'Commerce',
        description: 'A calm product grid with a considered, tactile shopping mood.',
        tags: ['Commerce', 'Warm', 'Minimal'],
        image: storePreview,
        imageAlt: 'Premium store with product grid and warm neutral palette',
        imagePrompt: 'High-fidelity premium ecommerce website screenshot, warm off-white canvas, quiet serif display type, product grid, simple filters, tactile neutral product blocks, restrained orange accent, no logo or readable brand name, no browser frame, 16:10.',
        basePrompt: 'Build a premium ecommerce storefront for a considered lifestyle brand. Include a hero collection, category navigation, product grid with filters and sort, product cards with price and availability, a quick-view interaction, and a persistent cart summary.',
        visualPrompt: 'Use a premium commerce direction with warm off-white surfaces, quiet serif display type, calm product cards, tactile neutral imagery, simple filters, and one warm orange accent.',
    },
    {
        id: 'creator-links',
        title: 'Creator links',
        category: 'Creator',
        description: 'A mobile-first profile with clear links and a distinct personal voice.',
        tags: ['Mobile-first', 'Personal', 'Warm'],
        image: creatorPreview,
        imageAlt: 'Mobile-first creator links page with profile and stacked buttons',
        imagePrompt: 'High-fidelity mobile-first creator link page screenshot, warm blush background, centered avatar, short bio, stacked rounded links, small social icons, editorial but friendly typography, no logo or readable brand name, no browser frame, 16:10.',
        basePrompt: 'Design a mobile-first creator links page. Include an avatar or initials fallback, name and short bio, social icons, a reorderable-looking stack of link buttons, link hover and pressed states, and a small newsletter or contact CTA.',
        visualPrompt: 'Use a mobile-first creator profile direction with centered identity, rounded stacked links, warm blush surfaces, friendly typography, and a personal-brand accent.',
    },
    {
        id: 'event-landing',
        title: 'Event landing',
        category: 'Marketing',
        description: 'A bold, high-contrast event page with an immediate registration path.',
        tags: ['Bold', 'Event', 'Dark'],
        image: eventPreview,
        imageAlt: 'Bold event landing page with date and registration call to action',
        imagePrompt: 'High-fidelity event landing page screenshot, deep navy background, oversized sans-serif headline, bright coral CTA, event date and location, abstract geometric hero object, no logo or readable brand name, no browser frame, 16:10.',
        basePrompt: 'Create a conversion-focused event landing page. Include a bold hero with event name, date, location, and registration CTA, speaker cards, an agenda, ticket or registration options, FAQ, and a final CTA that works well on mobile.',
        visualPrompt: 'Use a bold event direction with deep navy canvas, oversized sans-serif headline, bright coral CTA, clear date and location proof, and a single abstract hero object.',
    },
    {
        id: 'auto-repair',
        title: 'Auto repair workspace',
        category: 'Operations',
        description: 'A practical workshop command center for vehicles, work orders, and parts.',
        tags: ['Workspace', 'Professional', 'Amber'],
        image: autoRepairPreview,
        imageAlt: 'Auto repair management workspace with work order table and status cards',
        imagePrompt: 'High-fidelity auto repair management dashboard screenshot, clean white workspace, dark charcoal sidebar, amber accent, work order table, vehicle statuses, parts summary, realistic but fictional data, no logo or readable brand name, no browser frame, 16:10.',
        basePrompt: 'Build an auto repair shop management workspace. Include dashboard status cards, customer and vehicle records, work orders with status and due date, technician assignment, parts and inventory summary, appointment intake, search and filters, and a work-order detail view with editable fields.',
        visualPrompt: 'Use a professional auto repair workspace with dark charcoal navigation, amber accent, compact status cards, readable work-order tables, vehicle states, parts inventory cues, and responsive layouts.',
    },
]

export function starterById(id) {
    return LAB_STARTERS.find((starter) => starter.id === id) || null
}

export function buildStarterPrompt(starter, { tone = 'balanced', accent = 'teal', density = 'comfortable' } = {}) {
    if (! starter) return ''

    const toneText = {
        balanced: 'Keep the tone balanced and polished.',
        minimal: 'Keep the tone minimal, quiet, and spacious.',
        expressive: 'Make the tone expressive with confident typography and visual contrast.',
        playful: 'Make the tone friendly and lightly playful without becoming noisy.',
    }[tone] || 'Keep the tone balanced and polished.'
    const densityText = {
        airy: 'Use an airy content density with generous spacing.',
        comfortable: 'Use a comfortable content density with clear scanning rhythm.',
        dense: 'Use a dense but readable content density for information-rich screens.',
    }[density] || 'Use a comfortable content density with clear scanning rhythm.'

    const direction = [
        `${STARTER_DIRECTION_START} ${starter.id} ---`,
        `Reference: ${starter.title}.`,
        starter.basePrompt,
        starter.visualPrompt,
        `Accent preference: ${accent}.`,
        toneText,
        densityText,
        'Keep the result responsive and make the first viewport show the product or page purpose clearly.',
        STARTER_DIRECTION_END,
    ].join('\n')

    return direction
}

export function applyStarterToDraft(draft, starter, options = {}) {
    const base = String(draft || '').trim()
    const block = buildStarterPrompt(starter, options)
    if (! block) return base

    const previous = new RegExp(`\\n*${escapeRegExp(STARTER_DIRECTION_START)}[\\s\\S]*?${escapeRegExp(STARTER_DIRECTION_END)}`, 'g')
    const cleaned = base.replace(previous, '').trim()
    return cleaned ? `${cleaned}\n\n${block}` : block
}

function escapeRegExp(value) {
    return String(value).replace(/[.*+?^${}()|[\\]\\]/g, '\\$&')
}
