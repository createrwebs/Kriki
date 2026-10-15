export const WIZARD_STORAGE_KEY = 'krikkit.lab.project-wizard'
export const PROJECT_BRIEF_START = '--- Krikkit project brief ---'
export const PROJECT_BRIEF_END = '--- End project brief ---'
export const MULTI_PAGE_GUARD_START = '--- Krikkit multi-page generation guard ---'
export const MULTI_PAGE_GUARD_END = '--- End multi-page generation guard ---'
export const DATA_MODEL_START = '--- Krikkit data model contract ---'
export const DATA_MODEL_END = '--- End data model contract ---'
const VIEW_PAGE_IDS = new Set(['overview', 'reports', 'settings', 'home'])

export const FIELD_TYPES = [
    { value: 'text', label: 'Text' },
    { value: 'number', label: 'Number' },
    { value: 'date', label: 'Date' },
    { value: 'select', label: 'Status / select' },
    { value: 'long-text', label: 'Long text' },
]

export const PAGE_LIBRARY = [
    {
        id: 'overview',
        title: 'Overview',
        description: 'A summary dashboard with the most important activity and actions.',
        fields: [
            { name: 'status', type: 'select' },
            { name: 'owner', type: 'text' },
            { name: 'last updated', type: 'date' },
        ],
    },
    {
        id: 'customers',
        title: 'Customers',
        description: 'Searchable customer list with a detail view and contact actions.',
        fields: [
            { name: 'full name', type: 'text' },
            { name: 'phone', type: 'text' },
            { name: 'email', type: 'text' },
            { name: 'notes', type: 'long-text' },
        ],
    },
    {
        id: 'vehicles',
        title: 'Vehicles',
        description: 'Vehicle records connected to customers and service history.',
        fields: [
            { name: 'license plate', type: 'text' },
            { name: 'make and model', type: 'text' },
            { name: 'year', type: 'number' },
            { name: 'mileage', type: 'number' },
        ],
    },
    {
        id: 'work-orders',
        title: 'Work Orders',
        description: 'A work queue with status, assignment, estimate, and detail view.',
        fields: [
            { name: 'customer', type: 'text' },
            { name: 'vehicle', type: 'text' },
            { name: 'repair status', type: 'select' },
            { name: 'assigned technician', type: 'text' },
            { name: 'estimated total', type: 'number' },
            { name: 'due date', type: 'date' },
        ],
    },
    {
        id: 'inventory',
        title: 'Parts Inventory',
        description: 'Parts list with stock levels, supplier details, and reorder cues.',
        fields: [
            { name: 'part name', type: 'text' },
            { name: 'SKU', type: 'text' },
            { name: 'quantity on hand', type: 'number' },
            { name: 'reorder level', type: 'number' },
        ],
    },
    {
        id: 'appointments',
        title: 'Appointments',
        description: 'Calendar and intake flow for upcoming service appointments.',
        fields: [
            { name: 'customer', type: 'text' },
            { name: 'vehicle', type: 'text' },
            { name: 'appointment date', type: 'date' },
            { name: 'service requested', type: 'long-text' },
        ],
    },
    {
        id: 'reports',
        title: 'Reports',
        description: 'A reporting view for trends, totals, and operational performance.',
        fields: [
            { name: 'date range', type: 'date' },
            { name: 'metric', type: 'select' },
        ],
    },
    {
        id: 'settings',
        title: 'Settings',
        description: 'Workspace preferences, team settings, and configuration.',
        fields: [
            { name: 'workspace name', type: 'text' },
            { name: 'default status', type: 'select' },
        ],
    },
    {
        id: 'home',
        title: 'Home / Landing',
        description: 'A public first page explaining the product or service and its CTA.',
        fields: [
            { name: 'headline', type: 'text' },
            { name: 'primary CTA', type: 'text' },
            { name: 'supporting copy', type: 'long-text' },
        ],
    },
]

export const BUSINESS_PRESETS = [
    {
        id: 'auto-repair',
        title: 'Auto repair shop',
        description: 'Customers, vehicles, work orders, parts, and appointments.',
        defaultPages: ['overview', 'customers', 'vehicles', 'work-orders', 'inventory', 'appointments', 'reports', 'settings'],
        relationshipHint: 'Customers own vehicles; vehicles have work orders; work orders can use parts and link to appointments.',
        relationships: [
            { from: 'customers', to: 'vehicles', label: 'one customer can own many vehicles' },
            { from: 'vehicles', to: 'work-orders', label: 'each work order belongs to a vehicle' },
            { from: 'work-orders', to: 'inventory', label: 'work orders can consume parts from inventory' },
            { from: 'customers', to: 'appointments', label: 'customers can book many appointments' },
            { from: 'vehicles', to: 'appointments', label: 'appointments can reference a vehicle' },
        ],
    },
    {
        id: 'saas',
        title: 'SaaS product',
        description: 'A product workspace with accounts, usage, and team operations.',
        defaultPages: ['overview', 'customers', 'reports', 'settings'],
        relationshipHint: 'Accounts contain users and activity; reports aggregate product usage and business metrics.',
        relationships: [],
    },
    {
        id: 'commerce',
        title: 'Online store',
        description: 'Products, customers, orders, inventory, and fulfillment.',
        defaultPages: ['overview', 'customers', 'inventory', 'reports', 'settings'],
        relationshipHint: 'Customers create orders; orders contain products; inventory tracks stock and fulfillment state.',
        relationships: [],
    },
    {
        id: 'portfolio',
        title: 'Portfolio / studio',
        description: 'Projects, services, about content, and contact leads.',
        defaultPages: ['home', 'reports', 'settings'],
        relationshipHint: 'Projects belong to the portfolio and can link to services, case studies, and contact leads.',
        relationships: [],
    },
    {
        id: 'custom',
        title: 'Something else',
        description: 'Start with a blank business brief and define your own pages.',
        defaultPages: ['overview', 'settings'],
        relationshipHint: 'Keep relationships between records consistent and make the main workflow clear.',
        relationships: [],
    },
]

function pageTemplate(pageId) {
    const page = PAGE_LIBRARY.find((item) => item.id === pageId) || PAGE_LIBRARY[0]
    return {
        id: page.id,
        title: page.title,
        fields: page.fields.map((field, index) => ({ id: `${page.id}-${index}`, name: field.name, type: field.type })),
    }
}

export function wizardStateForBusiness(businessId = 'auto-repair') {
    const business = BUSINESS_PRESETS.find((item) => item.id === businessId) || BUSINESS_PRESETS[0]
    return {
        businessId: business.id,
        businessName: business.id === 'custom' ? '' : business.title,
        pages: business.defaultPages.map(pageTemplate),
    }
}

export function readWizardState() {
    try {
        const value = JSON.parse(window.localStorage.getItem(WIZARD_STORAGE_KEY) || 'null')
        return normalizeWizardState(value)
    } catch {
        return wizardStateForBusiness()
    }
}

export function saveWizardState(state) {
    try {
        window.localStorage.setItem(WIZARD_STORAGE_KEY, JSON.stringify(normalizeWizardState(state)))
    } catch {
        // Persistence is optional; the wizard remains usable if storage is unavailable.
    }
}

export function normalizeWizardState(value) {
    const base = value && typeof value === 'object' ? value : wizardStateForBusiness()
    const business = BUSINESS_PRESETS.find((item) => item.id === base.businessId) || BUSINESS_PRESETS[0]
    const pages = Array.isArray(base.pages) ? base.pages : []
    const usedPageIds = new Set()
    return {
        businessId: business.id,
        businessName: String(base.businessName || (business.id === 'custom' ? '' : business.title)).trim(),
        pages: pages
            .map((page, pageIndex) => {
                if (! page || typeof page !== 'object') return null
                const libraryPage = PAGE_LIBRARY.find((item) => item.id === page?.id)
                const title = String(page?.title !== undefined ? page.title : (libraryPage?.title || '')).trim()
                let id = String(page?.id || `custom-page-${pageIndex}`).trim() || `custom-page-${pageIndex}`
                if (usedPageIds.has(id)) {
                    const baseId = id
                    let suffix = 2
                    while (usedPageIds.has(`${baseId}-${suffix}`)) suffix += 1
                    id = `${baseId}-${suffix}`
                }
                usedPageIds.add(id)
                const fields = Array.isArray(page?.fields) ? page.fields : []
                const usedFieldIds = new Set()
                return {
                    id,
                    title,
                    fields: fields
                        .map((field, fieldIndex) => ({
                            id: uniqueFieldId(field?.id || `${pageIndex}-${fieldIndex}`, usedFieldIds),
                            name: String(field?.name || '').trim(),
                            type: FIELD_TYPES.some((item) => item.value === field?.type) ? field.type : 'text',
                        })),
                }
            })
            .filter(Boolean),
    }
}

export function buildProjectBrief(state) {
    const normalized = normalizeWizardState(state)
    const business = BUSINESS_PRESETS.find((item) => item.id === normalized.businessId) || BUSINESS_PRESETS[0]
    const businessName = normalized.businessName || business.title
    const productDirection = business.id === 'custom'
        ? `Build a ${businessName} system around the workflow and pages below.`
        : business.description
    const pageLines = normalized.pages.length > 0
        ? normalized.pages.map((page, index) => {
            const namedFields = page.fields.filter((field) => field.name)
            const fields = namedFields.length > 0
                ? namedFields.map((field) => `${field.name} (${fieldTypeLabel(field.type)})`).join(', ')
                : 'Add the fields needed for this workflow.'
            return `${index + 1}. ${page.title || 'Untitled page'}: ${fields}`
        })
        : ['1. Start with a clear overview and the primary workflow.']

    return [
        PROJECT_BRIEF_START,
        `Business domain: ${businessName}.`,
        `Product direction: ${productDirection}`,
        `Pages and fields:\n${pageLines.join('\n')}`,
        `Data relationships: ${business.relationshipHint}`,
        'Use realistic fictional sample data, clear empty/loading/error states, responsive layouts, and consistent navigation between pages.',
        buildDataModel(normalized).prompt,
        buildMultiPageGenerationGuard(normalized.pages),
        PROJECT_BRIEF_END,
    ].join('\n')
}

export function buildDataModel(state) {
    const normalized = normalizeWizardState(state)
    const business = BUSINESS_PRESETS.find((item) => item.id === normalized.businessId) || BUSINESS_PRESETS[0]
    const usedRoutes = new Set()
    const pages = normalized.pages.map((page, index) => {
        const fields = page.fields
            .filter((field) => field.name)
            .map((field) => ({ name: field.name, type: field.type }))
        return {
            id: page.id,
            title: page.title || 'Untitled page',
            route: uniquePageRoute(page, index, usedRoutes),
            kind: isDataPage(page) ? 'entity' : 'view',
            fields,
        }
    })
    const pageIds = new Set(pages.map((page) => page.id))
    const pageById = new Map(pages.map((page) => [page.id, page]))
    const relationships = (business.relationships || [])
        .filter((relationship) => pageIds.has(relationship.from) && pageIds.has(relationship.to))
        .map((relationship) => ({
            ...relationship,
            fromTitle: pageById.get(relationship.from).title,
            fromRoute: pageById.get(relationship.from).route,
            toTitle: pageById.get(relationship.to).title,
            toRoute: pageById.get(relationship.to).route,
        }))
    const lines = pages.length
        ? pages.map((page, index) => {
            const fields = page.fields.length
                ? page.fields.map((field) => `${field.name} (${fieldTypeLabel(field.type)})`).join(', ')
                : 'no named fields yet'
            return `${index + 1}. ${page.title} [${page.kind}] at ${page.route}: ${fields}.`
        })
        : ['1. No pages selected; create a primary record/view only after the user adds a page.']
    const relationshipLines = relationships.length
        ? relationships.map((relationship) => `${relationship.fromTitle} (${relationship.from}) [${relationship.fromRoute}] → ${relationship.toTitle} (${relationship.to}) [${relationship.toRoute}]: ${relationship.label}.`)
        : ['No explicit relationship was selected; infer only relationships supported by the listed fields.']

    return {
        pages,
        relationships,
        prompt: [
            DATA_MODEL_START,
            'Treat this as the initial data model contract for the generated application.',
            '- Pages marked entity represent records/collections; pages marked view are read/summary/configuration screens and should not become duplicate tables without a clear reason.',
            '- Keep field names and types consistent across forms, tables, detail views, filters, and sample data. Do not invent conflicting aliases for the same field.',
            '- Use the relationships below for references and navigation. Keep foreign-key-like values stable in sample data and show useful empty/loading/error states.',
            `Pages, entities, and columns:\n${lines.join('\n')}`,
            `Relationships:\n${relationshipLines.join('\n')}`,
            '- Keep these route assignments aligned with the multi-page generation guard so later CRUD/database wiring can use the same names.',
            DATA_MODEL_END,
        ].join('\n'),
    }
}

function isDataPage(page) {
    return ! VIEW_PAGE_IDS.has(page.id)
}

function buildMultiPageGenerationGuard(pages) {
    const usedRoutes = new Set()
    const pageLines = pages.length
        ? pages.map((page, index) => {
            const route = uniquePageRoute(page, index, usedRoutes)
            const fields = page.fields.filter((field) => field.name).map((field) => `${field.name} (${fieldTypeLabel(field.type)})`).join(', ')
            return `${index + 1}. ${page.title || 'Untitled page'} — route: ${route}; required fields: ${fields || 'infer the fields needed for this page workflow'}.`
        })
        : ['1. Create one primary screen at route: / with a clear overview and primary workflow.']

    return [
        MULTI_PAGE_GUARD_START,
        'Build every required page below as a real route or screen in the same application.',
        '- Keep one shared app shell, header/sidebar, and navigation model across all pages.',
        '- Add working links or navigation actions between related pages; the first listed page is the default route.',
        '- Give every page its own purpose, layout, copy, data view, and interaction states. Do not merge, omit, or replace selected pages with placeholder cards or headings.',
        '- Use the listed fields as the page data contract and show realistic create/edit/detail or filter interactions where the page implies them.',
        `Required routes and page contracts:\n${pageLines.join('\n')}`,
        '- Before finishing, verify every listed route renders, every navigation link resolves, and every listed field appears in the relevant page UI.',
        MULTI_PAGE_GUARD_END,
    ].join('\n')
}

export function applyWizardToDraft(draft, state) {
    const base = String(draft || '').trim()
    const brief = buildProjectBrief(state)
    const previous = new RegExp(`\\n*${escapeRegExp(PROJECT_BRIEF_START)}[\\s\\S]*?${escapeRegExp(PROJECT_BRIEF_END)}`, 'g')
    const cleaned = base.replace(previous, '').trim()
    return cleaned ? `${cleaned}\n\n${brief}` : brief
}

function fieldTypeLabel(type) {
    return FIELD_TYPES.find((item) => item.value === type)?.label.toLowerCase() || 'text'
}

function uniquePageRoute(page, index, usedRoutes) {
    if (index === 0) {
        usedRoutes.add('/')
        return '/'
    }

    const segment = slugifyRouteSegment(page.title || page.id || `page-${index + 1}`) || `page-${index + 1}`
    let route = `/${segment}`
    let suffix = 2
    while (usedRoutes.has(route)) {
        route = `/${segment}-${suffix}`
        suffix += 1
    }
    usedRoutes.add(route)
    return route
}

function slugifyRouteSegment(value) {
    return String(value || '')
        .normalize('NFKD')
        .toLowerCase()
        .replace(/[^\p{Letter}\p{Number}\p{Mark}]+/gu, '-')
        .replace(/^-+|-+$/g, '')
}

function escapeRegExp(value) {
    return String(value).replace(/[.*+?^${}()|[\]\\]/g, '\\$&')
}

function uniqueFieldId(value, usedIds) {
    const candidate = String(value || '').trim() || `field-${usedIds.size}`
    if (! usedIds.has(candidate)) {
        usedIds.add(candidate)
        return candidate
    }
    const baseId = candidate
    let suffix = 2
    while (usedIds.has(`${baseId}-${suffix}`)) suffix += 1
    const uniqueId = `${baseId}-${suffix}`
    usedIds.add(uniqueId)
    return uniqueId
}
