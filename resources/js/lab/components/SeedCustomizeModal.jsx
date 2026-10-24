import { useEffect, useId, useMemo, useRef, useState } from 'react'
import {
    buildCustomSeedPrompt,
    defaultSeedValues,
    LAB_BTN_OUTLINE,
    LAB_BTN_PRIMARY,
    seedIsCustomizable,
} from '../lib/labConstants'
import { LabSelect } from './LabSelect'

const fieldClass = [
    'h-10 w-full rounded-xl border border-krikkit-line bg-krikkit-surface',
    'px-3.5 text-sm font-normal text-krikkit-fg placeholder:text-krikkit-subtle',
    'outline-none transition focus:border-krikkit-accent',
].join(' ')

const areaClass = [
    'min-h-[4.75rem] w-full resize-y rounded-xl border border-krikkit-line bg-krikkit-surface',
    'px-3.5 py-2.5 text-sm font-normal text-krikkit-fg placeholder:text-krikkit-subtle',
    'outline-none transition focus:border-krikkit-accent',
].join(' ')

export const STUDIO_HANDOFF_STORAGE_KEY = 'krikkit.builder.studio-handoff'

export const STUDIO_THEME_PALETTES = [
    {
        id: 'forest',
        blueprintPalette: 'forest',
        label: 'Forest Emerald (เขียวธรรมชาติ)',
        background: '#f5f7f4',
        surface: '#ffffff',
        text: '#17251e',
        accent: '#1f6f54',
    },
    {
        id: 'indigo',
        blueprintPalette: 'indigo',
        label: 'Deep Indigo (น้ำเงินโมเดิร์น)',
        background: '#f6f7fb',
        surface: '#ffffff',
        text: '#181c2e',
        accent: '#3b4cca',
    },
    {
        id: 'sand',
        blueprintPalette: 'sand',
        label: 'Warm Terracotta (เอิร์ธโทนอบอุ่น)',
        background: '#faf6f0',
        surface: '#fffdf9',
        text: '#2b2118',
        accent: '#9a4d2e',
    },
    {
        id: 'teal',
        blueprintPalette: 'forest',
        label: 'Ocean Teal (เขียวน้ำทะเล)',
        background: '#f2f9f9',
        surface: '#ffffff',
        text: '#112a2e',
        accent: '#0d9488',
    },
    {
        id: 'rose',
        blueprintPalette: 'sand',
        label: 'Rose Studio (ชมพูโรสโกลด์)',
        background: '#fdf5f7',
        surface: '#ffffff',
        text: '#2d161d',
        accent: '#e11d48',
    },
    {
        id: 'slate',
        blueprintPalette: 'indigo',
        label: 'Minimal Slate (ขาวดำมินิมอล)',
        background: '#f8fafc',
        surface: '#ffffff',
        text: '#0f172a',
        accent: '#334155',
    },
]

export const STUDIO_IMAGE_PACKS = [
    {
        id: 'garden-craft',
        label: 'ธรรมชาติ / สวน / งานคราฟต์',
        heroUrl: 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?auto=format&fit=crop&w=1200&q=80',
        galleryUrl: 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?auto=format&fit=crop&w=900&q=80',
    },
    {
        id: 'cafe-dining',
        label: 'คาเฟ่ / ร้านอาหาร / เบเกอรี่',
        heroUrl: 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=80',
        galleryUrl: 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=900&q=80',
    },
    {
        id: 'modern-tech',
        label: 'ธุรกิจ / สตูดิโอ / เทคโนโลยี',
        heroUrl: 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
        galleryUrl: 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=900&q=80',
    },
    {
        id: 'retail-store',
        label: 'ร้านค้าออนไลน์ / แฟชั่น / สินค้า',
        heroUrl: 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1200&q=80',
        galleryUrl: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=900&q=80',
    },
    {
        id: 'wellness-clinic',
        label: 'คลินิก / สุขภาพ / สปา',
        heroUrl: 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1200&q=80',
        galleryUrl: 'https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&w=900&q=80',
    },
]

export const DEFAULT_MULTI_PAGES = [
    { id: 'home', title: 'หน้าแรก (Home)', route: '/', desc: 'แบนเนอร์หลัก จุดเด่นธุรกิจ และปุ่มติดต่อ/สั่งซื้อ' },
    { id: 'about', title: 'เกี่ยวกับเรา', route: '/about', desc: 'เรื่องราวของแบรนด์ ทีมงาน และความน่าเชื่อถือ' },
    { id: 'services', title: 'สินค้าและบริการ', route: '/services', desc: 'รายการสินค้า/บริการ ราคา และตะกร้าสั่งซื้อ' },
    { id: 'news', title: 'ข่าวสารและบทความ', route: '/news', desc: 'อัปเดตข่าวสาร โปรโมชั่น และบทความ' },
    { id: 'contact', title: 'ติดต่อเรา', route: '/contact', desc: 'แบบฟอร์มติดต่อ แผนที่ และช่องทางชำระเงิน' },
]

export function readUserBusinessProfile() {
    if (typeof document === 'undefined') {
        return { company_name: '', business_description: '' }
    }
    try {
        const el = document.getElementById('lab-config')
        if (! el) return { company_name: '', business_description: '' }
        const parsed = JSON.parse(el.textContent || '{}')
        const bp = parsed?.business_profile || {}
        return {
            company_name: String(bp.company_name || '').trim(),
            business_description: String(bp.business_description || '').trim(),
        }
    } catch {
        return { company_name: '', business_description: '' }
    }
}

const RESERVED_ROUTE_SEGMENTS = new Set([
    'lab',
    'api',
    'login',
    'logout',
    'register',
    'dashboard',
    'settings',
    'projects',
    'assets',
    'build',
    'storage',
    'oauth',
])

const VALID_ROUTE_SEGMENT = /^[\p{L}\p{N}][\p{L}\p{N}\p{M}]*(?:-[\p{L}\p{N}][\p{L}\p{N}\p{M}]*)*$/u

/**
 * Guaranteed to produce a valid BlueprintSchema / blueprint.js route segment:
 * - NFC normalized
 * - lowercase
 * - starts with a letter or digit (never a combining mark)
 * - not in RESERVED_ROUTE_SEGMENTS
 */
export function canonicalPageRoute(rawTitleOrRoute, idx) {
    if (idx === 0) return '/'
    const cleaned = String(rawTitleOrRoute || '')
        .normalize('NFC')
        .toLowerCase()
        .replace(/^\/+/, '')
        .replace(/\([^)]*\)/g, '')
        .trim()
        .replace(/[^\p{L}\p{N}\p{M}]+/gu, '-')
        .replace(/^[^\p{L}\p{N}]+/gu, '')
        .replace(/^-+|-+$/g, '')
        .replace(/-+/g, '-')
        .slice(0, 60)
        .replace(/-+$/g, '')

    const segment = cleaned && VALID_ROUTE_SEGMENT.test(cleaned) && ! RESERVED_ROUTE_SEGMENTS.has(cleaned)
        ? cleaned
        : `page-${idx + 1}`

    return `/${segment}`
}

function makeUuid() {
    if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
        return crypto.randomUUID()
    }
    return 'xxxxxxxx-xxxx-4xxx-8xxx-xxxxxxxxxxxx'.replace(/[xy]/g, (c) => {
        const r = Math.floor(Math.random() * 16)
        const v = c === 'x' ? r : ((r & 0x3) | 0x8)
        return v.toString(16)
    })
}

/**
 * Build a strictly valid BlueprintSchema v1 object so the user can open `/lab/builder?draft={uuid}`
 * with their exact business details, pages (1..5), theme palette, and custom fields!
 */
export function buildValidBlueprintFromStudio({
    kind = 'business',
    brandName = 'เว็บไซต์ธุรกิจของเรา',
    businessDesc = '',
    paletteId = 'forest',
    font = 'sans',
    radius = 'soft',
    pages = DEFAULT_MULTI_PAGES,
    heroImageUrl = '',
    galleryImageUrl = '',
    seedTitle = '',
    customFieldsSummary = '',
}) {
    const templateMap = {
        business: 'business-studio',
        ecommerce: 'storefront-demo',
        portfolio: 'portfolio-editorial',
        blog: 'blog-journal',
        other: 'business-studio',
    }
    const safeKind = Object.hasOwn(templateMap, kind) ? kind : 'business'
    const templateId = templateMap[safeKind]
    const paletteObj = STUDIO_THEME_PALETTES.find((p) => p.id === paletteId) || STUDIO_THEME_PALETTES[0]
    const safePalette = ['forest', 'indigo', 'sand'].includes(paletteObj.blueprintPalette)
        ? paletteObj.blueprintPalette
        : 'forest'
    const safeFont = ['sans', 'serif'].includes(font) ? font : 'sans'
    const safeRadius = ['soft', 'square'].includes(radius) ? radius : 'soft'

    const cleanTitle = Array.from(String(brandName || 'เว็บไซต์ธุรกิจของเรา').normalize('NFC').trim()).slice(0, 120).join('') || 'เว็บไซต์ธุรกิจของเรา'
    const cleanBrand = Array.from(cleanTitle).slice(0, 80).join('')
    const fullPromptText = buildStudioMultiPagePrompt({
        seed: { title: seedTitle || cleanTitle },
        customFieldsPrompt: customFieldsSummary,
        brandName: cleanTitle,
        businessDesc,
        kind: safeKind,
        paletteId,
        font: safeFont,
        radius: safeRadius,
        pages,
        heroImageUrl,
        galleryImageUrl,
    })
    const summaryText = Array.from(String(fullPromptText).normalize('NFC')).slice(0, 2000).join('')

    const usedRoutes = new Set(['/'])
    const boundedPages = (Array.isArray(pages) && pages.length ? pages : DEFAULT_MULTI_PAGES).slice(0, 5)

    const blueprintPages = boundedPages.map((p, index) => {
        const rawPageTitle = String(p.title || (index === 0 ? 'Home' : `หน้า ${index + 1}`)).normalize('NFC').trim()
        const pageTitle = Array.from(rawPageTitle || (index === 0 ? 'Home' : `หน้า ${index + 1}`)).slice(0, 80).join('')

        let route = '/'
        if (index > 0) {
            let candidate = canonicalPageRoute(p.route || p.title, index)
            let suffix = 2
            const base = candidate
            while (usedRoutes.has(candidate)) {
                candidate = `${base}-${suffix++}`
            }
            usedRoutes.add(candidate)
            route = candidate
        }

        const desc = Array.from(String(p.desc || businessDesc || 'รายละเอียดเนื้อหาและบริการของหน้านี้').normalize('NFC').trim()).slice(0, 1500).join('')
        const heroBodyBase = index === 0
            ? String(businessDesc || desc || `ยินดีต้อนรับสู่ ${cleanTitle}`).normalize('NFC').trim()
            : desc
        const heroBodyWithImg = index === 0 && heroImageUrl
            ? `${heroBodyBase}\n[Hero Image: ${heroImageUrl}]`
            : heroBodyBase
        const sectionBodyWithImg = galleryImageUrl
            ? `ข้อมูลสำคัญสำหรับหน้า ${pageTitle}\n[Section Image: ${galleryImageUrl}]`
            : `ข้อมูลสำคัญสำหรับหน้า ${pageTitle}`

        return {
            id: makeUuid(),
            title: pageTitle,
            route,
            navigation: true,
            sections: [
                {
                    id: makeUuid(),
                    type: index === 0 ? 'hero' : 'text',
                    variant: index === 0 ? 'split' : 'default',
                    heading: index === 0 ? cleanTitle : pageTitle,
                    body: Array.from(heroBodyWithImg).slice(0, 1800).join(''),
                    items: [],
                },
                {
                    id: makeUuid(),
                    type: index === 0 ? 'feature-grid' : 'cards',
                    variant: 'default',
                    heading: Array.from(index === 0 ? 'บริการและจุดเด่นของเรา' : `รายละเอียด ${pageTitle}`).slice(0, 120).join(''),
                    body: Array.from(sectionBodyWithImg).slice(0, 1800).join(''),
                    items: [
                        {
                            id: makeUuid(),
                            title: 'คุณภาพและบริการมาตรฐาน',
                            body: Array.from(desc).slice(0, 400).join('') || 'พร้อมดูแลและให้บริการลูกค้าทุกท่านอย่างครบวงจร',
                        },
                        {
                            id: makeUuid(),
                            title: 'ติดต่อและสั่งซื้อสะดวก',
                            body: 'รองรับการสอบถามข้อมูล ตะกร้าสินค้า และแจ้งชำระเงินออนไลน์',
                        },
                    ],
                },
            ],
        }
    })

    return {
        schema_version: 1,
        kind: safeKind,
        start_mode: 'template',
        template_id: templateId,
        title: cleanTitle,
        summary: summaryText,
        shared: {
            brand: cleanBrand,
            footer: Array.from(`© ${cleanBrand} · สร้างและปรับแต่งผ่าน Krikkit Studio`).slice(0, 240).join(''),
        },
        theme: {
            palette: safePalette,
            font: safeFont,
            radius: safeRadius,
        },
        pages: blueprintPages,
    }
}

/**
 * Compose a rich multi-page website prompt from the Studio configurator.
 */
export function buildStudioMultiPagePrompt({
    seed,
    customFieldsPrompt = '',
    brandName = '',
    businessDesc = '',
    kind = 'business',
    paletteId = 'forest',
    font = 'sans',
    radius = 'soft',
    pages = DEFAULT_MULTI_PAGES,
    heroImageUrl = '',
    galleryImageUrl = '',
}) {
    const palette = STUDIO_THEME_PALETTES.find((p) => p.id === paletteId) || STUDIO_THEME_PALETTES[0]
    const activePages = Array.isArray(pages) && pages.length ? pages : DEFAULT_MULTI_PAGES
    const usedRoutes = new Set(['/'])
    const pageLines = activePages.map((p, idx) => {
        let route = idx === 0 ? '/' : canonicalPageRoute(p.route || p.title, idx)
        if (idx > 0) {
            const base = route
            let n = 2
            while (usedRoutes.has(route)) route = `${base}-${n++}`
            usedRoutes.add(route)
        }
        return `${idx + 1}. หน้า "${p.title}" (route: ${route}) — ${p.desc || 'แสดงเนื้อหาสำคัญของหน้านี้ครบถ้วน'}`
    })

    return [
        `สร้างเว็บไซต์หลายหน้า (Multi-page Website) สำหรับแบรนด์/ธุรกิจ: "${brandName || seed?.title || 'เว็บไซต์ธุรกิจ'}"`,
        businessDesc ? `รายละเอียดธุรกิจ (จากข้อมูลที่ลงทะเบียน/แก้ไข): ${businessDesc}` : '',
        customFieldsPrompt ? `แนวทางต้นแบบที่เลือก (${seed?.title || 'Template'}):\n${customFieldsPrompt}` : '',
        '',
        '--- โครงสร้างหน้าเว็บหลายหน้า (Multi-Page Navigation & Routes) ---',
        `จำนวนหน้าทั้งหมด ${activePages.length} หน้า (ต้องมีแถบเมนู Header Navigation คลิกสลับหน้าได้จริงทุกหน้าโดยไม่พัง):`,
        ...pageLines,
        '',
        '--- ธีมสีและดีไซน์ (Theme & Visual Styling) ---',
        `- โทนสีหลัก (Palette): ${palette.label}`,
        `- สีพื้นหลัง (Background): ${palette.background}, สีพื้นผิวการ์ด (Surface): ${palette.surface}, สีตัวอักษร (Text): ${palette.text}, สีเน้น/ปุ่ม (Accent): ${palette.accent}`,
        `- รูปแบบฟอนต์: ${font === 'serif' ? 'Serif เรียบหรูมีเอกลักษณ์' : 'Sans-serif ทันสมัยอ่านง่าย'} · มุมการ์ด/ปุ่ม: ${radius === 'square' ? 'เหลี่ยมคมชัด' : 'โค้งมนสวยงาม'}`,
        '',
        '--- รูปภาพประกอบเว็บไซต์ (Visual Assets & Hero Images) ---',
        heroImageUrl ? `- รูปภาพแบนเนอร์หลัก (Hero Image URL): ${heroImageUrl}` : '',
        galleryImageUrl ? `- รูปภาพประกอบเนื้อหา/การ์ด (Section/Gallery Image URL): ${galleryImageUrl}` : '',
        '- แสดงรูปภาพประกอบด้วยขนาดและสัดส่วนที่สวยงาม (object-cover, rounded corners) พร้อมข้อมูลตัวอย่างภาษาไทยที่สมจริงตรงกับธุรกิจ',
    ].filter(Boolean).join('\n')
}

function getSeedStorageKey(seed) {
    const id = String(seed?.id || seed?.title || 'default')
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '')
    return `krikkit.studio.seed.${id || 'default'}`
}

/**
 * Multi-page Pre-Build Studio Modal for `/lab/`:
 * Allows configuring Business Info (pre-filled from registration), Pages (add/remove/reorder/rename),
 * Theme Colors, and Hero/Section Images (including live Unsplash keyword search) before either building or opening `/lab/builder`.
 */
export function SeedCustomizeModal({
    seed = null,
    open = false,
    onClose,
    onApply,
    onBuildNow = null,
}) {
    const titleId = useId()
    const firstRef = useRef(null)
    const userProfile = useMemo(() => readUserBusinessProfile(), [open])

    const fields = seed?.customize?.fields ?? []
    const defaults = useMemo(() => defaultSeedValues(seed), [seed])
    const [values, setValues] = useState(defaults)

    const [activeTab, setActiveTab] = useState('overview')
    const [brandName, setBrandName] = useState('')
    const [businessDesc, setBusinessDesc] = useState('')
    const [kind, setKind] = useState('business')
    const [paletteId, setPaletteId] = useState('forest')
    const [font, setFont] = useState('sans')
    const [radius, setRadius] = useState('soft')
    const [pages, setPages] = useState(DEFAULT_MULTI_PAGES)
    const [newPageTitle, setNewPageTitle] = useState('')
    const [newPageDesc, setNewPageDesc] = useState('')
    const [imagePackId, setImagePackId] = useState(STUDIO_IMAGE_PACKS[0].id)
    const [heroImageUrl, setHeroImageUrl] = useState(STUDIO_IMAGE_PACKS[0].heroUrl)
    const [galleryImageUrl, setGalleryImageUrl] = useState(STUDIO_IMAGE_PACKS[0].galleryUrl)
    const [savedDraftUuid, setSavedDraftUuid] = useState(null)

    // Unsplash search state
    const [unsplashQuery, setUnsplashQuery] = useState('')
    const [unsplashTarget, setUnsplashTarget] = useState('hero') // 'hero' | 'gallery'
    const [unsplashResults, setUnsplashResults] = useState([])
    const [unsplashLoading, setUnsplashLoading] = useState(false)
    const [unsplashError, setUnsplashError] = useState('')

    const [savingDraft, setSavingDraft] = useState(false)
    const [draftError, setDraftError] = useState('')
    const [hydrated, setHydrated] = useState(false)

    useEffect(() => {
        if (! open || ! seed) {
            setHydrated(false)
            return undefined
        }
        setActiveTab('overview')
        setDraftError('')
        setUnsplashError('')

        const defaultBrand = userProfile.company_name
            || seed?.customize?.fields?.find((f) => ['name', 'company', 'org'].includes(f.key))?.default
            || seed?.title
            || 'เว็บไซต์ธุรกิจของเรา'
        const defaultDesc = userProfile.business_description
            || seed?.description
            || seed?.prompt
            || 'บริการและสินค้าคุณภาพ พร้อมระบบติดต่อและสั่งซื้อครบวงจร'

        let defaultKind = 'business'
        let defaultPackId = STUDIO_IMAGE_PACKS[0].id
        let defaultHero = STUDIO_IMAGE_PACKS[0].heroUrl
        let defaultGallery = STUDIO_IMAGE_PACKS[0].galleryUrl
        let defaultPalette = 'forest'
        let defaultFont = 'sans'
        let defaultRadius = 'soft'

        const titleAndMeta = [
            seed?.id,
            seed?.title,
            seed?.category,
            seed?.visualPrompt,
            ...(Array.isArray(seed?.tags) ? seed.tags : []),
        ].filter(Boolean).join(' ')

        // Match kind and preset image pack from seed/starter
        if (/store|commerce|shop|checkout|market|ร้านค้า|ขายของ|แฟชั่น|จิวเวลรี่/i.test(titleAndMeta)) {
            defaultKind = 'ecommerce'
            defaultPackId = 'retail-store'
            defaultHero = STUDIO_IMAGE_PACKS[3].heroUrl
            defaultGallery = STUDIO_IMAGE_PACKS[3].galleryUrl
            defaultPalette = 'sand'
        } else if (/portfolio|editorial|agency|photo|gallery|creator|สตูดิโอ|พอร์ตโฟลิโอ|ช่างภาพ/i.test(titleAndMeta)) {
            defaultKind = 'portfolio'
            defaultPackId = 'modern-tech'
            defaultHero = STUDIO_IMAGE_PACKS[2].heroUrl
            defaultGallery = STUDIO_IMAGE_PACKS[2].galleryUrl
            defaultPalette = 'sand'
            defaultFont = 'serif'
        } else if (/blog|news|docs|course|บทความ|ข่าว|คอร์ส|เรียน/i.test(titleAndMeta)) {
            defaultKind = 'blog'
            defaultPackId = 'modern-tech'
            defaultHero = STUDIO_IMAGE_PACKS[2].heroUrl
            defaultGallery = STUDIO_IMAGE_PACKS[2].galleryUrl
            defaultPalette = 'indigo'
        } else if (/cafe|restaurant|food|recipe|คาเฟ่|ร้านอาหาร|เบเกอรี่|กาแฟ/i.test(titleAndMeta)) {
            defaultKind = 'business'
            defaultPackId = 'cafe-dining'
            defaultHero = STUDIO_IMAGE_PACKS[1].heroUrl
            defaultGallery = STUDIO_IMAGE_PACKS[1].galleryUrl
            defaultPalette = 'sand'
        } else if (/clinic|spa|wellness|fitness|health|คลินิก|สปา|สุขภาพ|ความงาม|ฟิตเนส/i.test(titleAndMeta)) {
            defaultKind = 'business'
            defaultPackId = 'wellness-clinic'
            defaultHero = STUDIO_IMAGE_PACKS[4].heroUrl
            defaultGallery = STUDIO_IMAGE_PACKS[4].galleryUrl
            defaultPalette = 'teal'
        } else if (/saas|dashboard|event|crm|repair|tech|ai|code|ระบบ|แดชบอร์ด|อู่ซ่อมรถ|คลังสินค้า/i.test(titleAndMeta)) {
            defaultKind = 'business'
            defaultPackId = 'modern-tech'
            defaultHero = STUDIO_IMAGE_PACKS[2].heroUrl
            defaultGallery = STUDIO_IMAGE_PACKS[2].galleryUrl
            defaultPalette = 'indigo'
        }

        // Exact Starter ID / Starter Accent override so the popup matches the clicked Starter card 100%
        const accentToPalette = {
            teal: 'teal',
            blue: 'indigo',
            violet: 'indigo',
            rose: 'rose',
            amber: 'sand',
        }
        const seedDefaultAccent = seed?.starterAccent
            || seed?.customize?.fields?.find((f) => f.key === 'accent')?.default
        if (seedDefaultAccent && accentToPalette[seedDefaultAccent]) {
            defaultPalette = accentToPalette[seedDefaultAccent]
        }
        if (seed?.id === 'editorial-portfolio') {
            defaultPalette = 'sand'
            defaultFont = 'serif'
            defaultRadius = 'square'
        } else if (seed?.id === 'premium-store') {
            defaultPalette = 'sand'
            defaultFont = 'serif'
        } else if (seed?.id === 'creator-links') {
            defaultPalette = 'rose'
        } else if (seed?.id === 'event-landing') {
            defaultPalette = 'indigo'
        } else if (seed?.id === 'auto-repair') {
            defaultPalette = 'slate'
        } else if (seed?.id === 'saas-dashboard') {
            defaultPalette = 'teal'
        }

        // If the clicked card has its own preview image (e.g. StarterGallery card), use that exact image!
        if (seed?.image) {
            defaultPackId = 'starter-card-image'
            defaultHero = seed.image
        }

        // Load previously edited values for this seed from localStorage so user edits are never lost
        let restored = null
        try {
            const raw = window.localStorage?.getItem(getSeedStorageKey(seed))
            if (raw) restored = JSON.parse(raw)
        } catch {
            restored = null
        }

        setValues(restored?.values && typeof restored.values === 'object' ? { ...defaultSeedValues(seed), ...restored.values } : defaultSeedValues(seed))
        setBrandName(restored?.brandName || defaultBrand)
        setBusinessDesc(restored?.businessDesc || defaultDesc)
        setKind(restored?.kind || defaultKind)
        setPaletteId(restored?.paletteId || defaultPalette)
        setFont(restored?.font || defaultFont)
        setRadius(restored?.radius || defaultRadius)
        setPages(Array.isArray(restored?.pages) && restored.pages.length >= 1 ? restored.pages.slice(0, 5) : DEFAULT_MULTI_PAGES)
        setImagePackId(restored?.imagePackId || defaultPackId)
        setHeroImageUrl(restored?.heroImageUrl || defaultHero)
        setGalleryImageUrl(restored?.galleryImageUrl || defaultGallery)
        setSavedDraftUuid(restored?.savedDraftUuid || null)
        setHydrated(true)

        const id = window.requestAnimationFrame(() => firstRef.current?.focus())
        return () => window.cancelAnimationFrame(id)
    }, [open, seed, userProfile.company_name, userProfile.business_description])

    // Auto-save every edit to localStorage immediately so nothing is ever lost
    useEffect(() => {
        if (! open || ! seed || ! hydrated) return
        const customFieldsSummary = seedIsCustomizable(seed)
            ? buildCustomSeedPrompt(seed, values)
            : ''
        const blueprint = buildValidBlueprintFromStudio({
            kind,
            brandName,
            businessDesc,
            paletteId,
            font,
            radius,
            pages,
            heroImageUrl,
            galleryImageUrl,
            seedTitle: seed?.title || '',
            customFieldsSummary,
        })
        const payload = {
            seedId: seed?.id || seed?.title || 'custom',
            seedTitle: seed?.title || '',
            templateId: blueprint.template_id,
            kind,
            brandName,
            businessDesc,
            paletteId,
            font,
            radius,
            pages,
            imagePackId,
            heroImageUrl,
            galleryImageUrl,
            values,
            savedDraftUuid,
            blueprint,
            at: Date.now(),
        }
        try {
            window.localStorage?.setItem(getSeedStorageKey(seed), JSON.stringify(payload))
            window.localStorage?.setItem(STUDIO_HANDOFF_STORAGE_KEY, JSON.stringify(payload))
            window.sessionStorage?.setItem(STUDIO_HANDOFF_STORAGE_KEY, JSON.stringify(payload))
        } catch {
            // Ignore storage quota errors
        }
    }, [
        open,
        seed,
        hydrated,
        kind,
        brandName,
        businessDesc,
        paletteId,
        font,
        radius,
        pages,
        imagePackId,
        heroImageUrl,
        galleryImageUrl,
        values,
        savedDraftUuid,
    ])

    useEffect(() => {
        if (! open) return undefined
        const onKey = (event) => {
            if (event.key === 'Escape') {
                event.preventDefault()
                onClose?.()
            }
        }
        window.addEventListener('keydown', onKey, true)
        return () => window.removeEventListener('keydown', onKey, true)
    }, [open, onClose])

    if (! open || ! seed) return null

    const currentPalette = STUDIO_THEME_PALETTES.find((p) => p.id === paletteId) || STUDIO_THEME_PALETTES[0]

    const setField = (key, value) => {
        setValues((prev) => ({ ...prev, [key]: value }))
    }

    const selectImagePack = (pack) => {
        setImagePackId(pack.id)
        setHeroImageUrl(pack.heroUrl)
        setGalleryImageUrl(pack.galleryUrl)
    }

    const searchUnsplash = async (customQuery) => {
        const q = String(customQuery ?? unsplashQuery).trim()
        if (! q) return
        setUnsplashLoading(true)
        setUnsplashError('')
        try {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            const response = await fetch('/lab/visuals', {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({
                    query: q.slice(0, 80),
                    count: 8,
                    orientation: 'landscape',
                }),
            })
            const data = await response.json().catch(() => ({}))
            if (! response.ok) {
                throw new Error(data?.message || 'ค้นหารูปภาพจาก Unsplash ไม่สำเร็จ')
            }
            const visuals = Array.isArray(data?.visuals) ? data.visuals : []
            setUnsplashResults(visuals)
            if (visuals.length === 0) {
                setUnsplashError('ไม่พบรูปภาพสำหรับคำค้นหานี้ ลองใช้คำค้นอื่น เช่น coffee, garden, office, clinic')
            }
        } catch (err) {
            setUnsplashError(err?.message || 'เกิดข้อผิดพลาดในการค้นหารูปภาพ')
        } finally {
            setUnsplashLoading(false)
        }
    }

    const applyUnsplashHit = (hit, targetRole = unsplashTarget) => {
        if (! hit?.src) return
        if (targetRole === 'gallery') {
            setGalleryImageUrl(hit.src)
        } else {
            setHeroImageUrl(hit.src)
        }
        setImagePackId('custom-unsplash')
    }

    const togglePresetPage = (preset) => {
        const exists = pages.some((p) => p.id === preset.id)
        if (exists) {
            if (pages.length <= 1) return
            setPages(pages.filter((p) => p.id !== preset.id))
        } else {
            if (pages.length >= 5) return
            setPages([...pages, { ...preset }])
        }
    }

    const addCustomPage = () => {
        const title = newPageTitle.trim()
        if (! title || pages.length >= 5) return
        const id = `custom-${Date.now()}`
        setPages([
            ...pages,
            {
                id,
                title,
                route: canonicalPageRoute(title, pages.length),
                desc: newPageDesc.trim() || `รายละเอียดและเนื้อหาของหน้า ${title}`,
            },
        ])
        setNewPageTitle('')
        setNewPageDesc('')
    }

    const removePageAt = (index) => {
        if (pages.length <= 1) return
        setPages(pages.filter((_, i) => i !== index))
    }

    const movePage = (index, dir) => {
        const target = index + dir
        if (target < 0 || target >= pages.length) return
        const next = [...pages]
        ;[next[index], next[target]] = [next[target], next[index]]
        next[0] = { ...next[0], route: '/' }
        setPages(next)
    }

    const updatePageItem = (index, patch) => {
        setPages(pages.map((item, i) => (i === index ? { ...item, ...patch } : item)))
    }

    const composePrompt = () => {
        const customFieldsPrompt = seedIsCustomizable(seed)
            ? buildCustomSeedPrompt(seed, values)
            : String(seed?.prompt || '')
        return buildStudioMultiPagePrompt({
            seed,
            customFieldsPrompt,
            brandName,
            businessDesc,
            kind,
            paletteId,
            font,
            radius,
            pages,
            heroImageUrl,
            galleryImageUrl,
        })
    }

    const handleApplyToComposer = () => {
        onApply?.(composePrompt())
    }

    const handleBuildImmediately = () => {
        const prompt = composePrompt()
        if (typeof onBuildNow === 'function') {
            onBuildNow(prompt)
        } else {
            onApply?.(prompt)
        }
    }

    const handleOpenFullBuilder = async () => {
        setSavingDraft(true)
        setDraftError('')
        const customFieldsSummary = seedIsCustomizable(seed)
            ? buildCustomSeedPrompt(seed, values)
            : ''
        const blueprint = buildValidBlueprintFromStudio({
            kind,
            brandName,
            businessDesc,
            paletteId,
            font,
            radius,
            pages,
            heroImageUrl,
            galleryImageUrl,
            seedTitle: seed?.title || '',
            customFieldsSummary,
        })
        const seedKeyId = String(seed?.id || seed?.title || 'custom')
        const handoffPayload = {
            seedId: seedKeyId,
            seedTitle: seed?.title || '',
            templateId: blueprint.template_id,
            kind,
            brandName,
            businessDesc,
            paletteId,
            font,
            radius,
            pages,
            imagePackId,
            heroImageUrl,
            galleryImageUrl,
            values,
            savedDraftUuid,
            blueprint,
            at: Date.now(),
        }
        try {
            window.localStorage?.setItem(getSeedStorageKey(seed), JSON.stringify(handoffPayload))
            window.localStorage?.setItem(STUDIO_HANDOFF_STORAGE_KEY, JSON.stringify(handoffPayload))
            window.sessionStorage?.setItem(STUDIO_HANDOFF_STORAGE_KEY, JSON.stringify(handoffPayload))
        } catch {
            // Storage fallback
        }
        const queryTail = `template=${encodeURIComponent(blueprint.template_id)}&seed=${encodeURIComponent(seedKeyId)}&from=studio`
        try {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            const response = await fetch('/lab/builder/drafts', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({
                    request_id: makeUuid(),
                    blueprint,
                }),
            })
            const data = await response.json().catch(() => ({}))
            if (response.ok && data?.draft?.uuid) {
                const draftUuid = data.draft.uuid
                setSavedDraftUuid(draftUuid)
                try {
                    const updatedPayload = { ...handoffPayload, savedDraftUuid: draftUuid }
                    window.localStorage?.setItem(getSeedStorageKey(seed), JSON.stringify(updatedPayload))
                    window.localStorage?.setItem(STUDIO_HANDOFF_STORAGE_KEY, JSON.stringify(updatedPayload))
                    window.sessionStorage?.setItem(STUDIO_HANDOFF_STORAGE_KEY, JSON.stringify(updatedPayload))
                } catch {
                    // Ignore storage error
                }
                window.location.assign(`/lab/builder?draft=${encodeURIComponent(draftUuid)}&${queryTail}`)
                return
            }
            window.location.assign(`/lab/builder?${queryTail}`)
        } catch {
            window.location.assign(`/lab/builder?${queryTail}`)
        } finally {
            setSavingDraft(false)
        }
    }

    return (
        <div className="krikkit-scroll-hover fixed inset-0 z-[90] flex items-start justify-center overflow-y-auto px-3 py-6 sm:px-6 sm:py-10">
            <button
                type="button"
                className="fixed inset-0 bg-black/50 backdrop-blur-[2px]"
                aria-label="Dismiss"
                onClick={onClose}
            />
            <form
                role="dialog"
                aria-modal="true"
                aria-labelledby={titleId}
                className="relative z-10 w-full max-w-4xl overflow-hidden rounded-3xl border border-krikkit-line bg-krikkit-canvas shadow-2xl"
                onSubmit={(event) => {
                    event.preventDefault()
                    handleApplyToComposer()
                }}
            >
                {/* Header */}
                <div className="flex flex-wrap items-start justify-between gap-3 border-b border-krikkit-line bg-krikkit-surface/60 px-5 py-4 sm:px-6">
                    <div>
                        <div className="flex items-center gap-2">
                            <span className="rounded-full bg-krikkit-accent/15 px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wider text-krikkit-accent">
                                Pre-Build Studio
                            </span>
                            <span className="text-xs text-krikkit-muted">
                                ตั้งค่าเว็บไซต์หลายหน้า · แก้ไขจำนวนหน้า · ธีมสี · ค้นหารูป Unsplash ก่อนเริ่ม Build
                            </span>
                        </div>
                        <h2 id={titleId} className="mt-1 text-lg font-semibold text-krikkit-fg">
                            ปรับแต่งเว็บไซต์: {seed.title}
                        </h2>
                    </div>
                    <button
                        type="button"
                        onClick={onClose}
                        className={`rounded-full px-3 py-1.5 text-xs ${LAB_BTN_OUTLINE}`}
                    >
                        ปิดหน้าต่าง
                    </button>
                </div>

                {/* Step Tabs */}
                <div className="flex flex-wrap gap-1.5 border-b border-krikkit-line bg-krikkit-canvas px-5 py-2.5 sm:px-6" role="tablist">
                    {[
                        { id: 'overview', label: '1. ข้อมูลธุรกิจ & รายละเอียด' },
                        { id: 'pages', label: `2. แก้ไขจำนวนหน้า (${pages.length} หน้า)` },
                        { id: 'theme', label: '3. แก้ไขธีมสี & ค้นหารูป Unsplash' },
                    ].map((tab) => (
                        <button
                            key={tab.id}
                            type="button"
                            role="tab"
                            aria-selected={activeTab === tab.id}
                            onClick={() => setActiveTab(tab.id)}
                            className={`rounded-full px-3.5 py-1.5 text-xs font-medium transition ${
                                activeTab === tab.id
                                    ? 'bg-krikkit-fg text-krikkit-canvas'
                                    : 'bg-krikkit-surface text-krikkit-muted hover:text-krikkit-fg'
                            }`}
                        >
                            {tab.label}
                        </button>
                    ))}
                </div>

                {/* Main Two-Column Body */}
                <div className="grid max-h-[68dvh] gap-6 overflow-y-auto p-5 sm:px-6 lg:grid-cols-[1.2fr_0.8fr]">
                    {/* Left Column: Active Tab Controls */}
                    <div className="space-y-4">
                        {activeTab === 'overview' && (
                            <div className="space-y-3.5">
                                {userProfile.company_name || userProfile.business_description ? (
                                    <div className="rounded-2xl border border-krikkit-accent/30 bg-krikkit-soft/60 px-3.5 py-2.5 text-xs text-krikkit-fg">
                                        ดึงข้อมูลธุรกิจจากตอนสมัครสมาชิกให้แล้ว คุณสามารถแก้ไขเพิ่มเติมก่อนสร้างเว็บไซต์ได้ทันที
                                    </div>
                                ) : null}

                                <div className="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label htmlFor="studio-brand-name" className="mb-1.5 block text-xs font-medium text-krikkit-muted">
                                            ชื่อเว็บไซต์ / ชื่อแบรนด์ธุรกิจ
                                        </label>
                                        <input
                                            ref={firstRef}
                                            id="studio-brand-name"
                                            type="text"
                                            value={brandName}
                                            onChange={(e) => setBrandName(e.target.value)}
                                            placeholder="เช่น ร้านสวนสวย / คลินิกทันตกรรม"
                                            className={fieldClass}
                                        />
                                    </div>
                                    <div>
                                        <label htmlFor="studio-site-kind" className="mb-1.5 block text-xs font-medium text-krikkit-muted">
                                            ประเภทเว็บไซต์
                                        </label>
                                        <select
                                            id="studio-site-kind"
                                            value={kind}
                                            onChange={(e) => setKind(e.target.value)}
                                            className={fieldClass}
                                        >
                                            <option value="business">เว็บไซต์บริษัท / บริการ (Business)</option>
                                            <option value="ecommerce">ร้านค้าออนไลน์ / สินค้า (E-commerce)</option>
                                            <option value="portfolio">พอร์ตโฟลิโอ / ผลงาน (Portfolio)</option>
                                            <option value="blog">บล็อก / ข่าวสาร / บทความ (Blog & News)</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label htmlFor="studio-business-desc" className="mb-1.5 block text-xs font-medium text-krikkit-muted">
                                        รายละเอียดธุรกิจ สินค้า หรือบริการ (แก้ไขก่อน Build ได้)
                                    </label>
                                    <textarea
                                        id="studio-business-desc"
                                        rows={3}
                                        value={businessDesc}
                                        onChange={(e) => setBusinessDesc(e.target.value)}
                                        placeholder="อธิบายจุดเด่น สินค้า บริการ หรือกลุ่มลูกค้าของคุณ..."
                                        className={areaClass}
                                    />
                                </div>

                                {fields.length > 0 ? (
                                    <div className="rounded-2xl border border-krikkit-line bg-krikkit-surface/50 p-3.5 space-y-3">
                                        <p className="text-xs font-semibold text-krikkit-fg">
                                            ข้อมูลเฉพาะสำหรับ {seed.title}
                                        </p>
                                        {fields.map((field) => (
                                            <div key={field.key}>
                                                <label
                                                    htmlFor={`seed-field-${field.key}`}
                                                    className="mb-1 block text-[11px] font-medium text-krikkit-muted"
                                                >
                                                    {field.label}
                                                </label>
                                                {field.type === 'select' ? (
                                                    <LabSelect
                                                        id={`seed-field-${field.key}`}
                                                        value={values[field.key] ?? field.default ?? ''}
                                                        onChange={(value) => setField(field.key, value)}
                                                        options={field.options ?? []}
                                                    />
                                                ) : field.type === 'textarea' ? (
                                                    <textarea
                                                        id={`seed-field-${field.key}`}
                                                        value={values[field.key] ?? ''}
                                                        onChange={(event) => setField(field.key, event.target.value)}
                                                        placeholder={field.placeholder}
                                                        className={areaClass}
                                                        rows={3}
                                                    />
                                                ) : (
                                                    <input
                                                        id={`seed-field-${field.key}`}
                                                        type="text"
                                                        value={values[field.key] ?? ''}
                                                        onChange={(event) => setField(field.key, event.target.value)}
                                                        placeholder={field.placeholder}
                                                        className={fieldClass}
                                                    />
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                ) : null}
                            </div>
                        )}

                        {activeTab === 'pages' && (
                            <div className="space-y-4">
                                <div>
                                    <div className="flex items-center justify-between">
                                        <h3 className="text-sm font-semibold text-krikkit-fg">
                                            เลือกและจัดลำดับหน้าเว็บไซต์ ({pages.length}/5 หน้า)
                                        </h3>
                                        <span className="text-[11px] text-krikkit-muted">
                                            คลิกเพื่อเปิด/ปิดหน้ามาตรฐาน
                                        </span>
                                    </div>
                                    <div className="mt-2 flex flex-wrap gap-1.5">
                                        {DEFAULT_MULTI_PAGES.map((preset) => {
                                            const selected = pages.some((p) => p.id === preset.id)
                                            return (
                                                <button
                                                    key={preset.id}
                                                    type="button"
                                                    onClick={() => togglePresetPage(preset)}
                                                    className={`rounded-full border px-3 py-1 text-xs font-medium transition ${
                                                        selected
                                                            ? 'border-krikkit-accent bg-krikkit-accent text-white'
                                                            : 'border-krikkit-line bg-krikkit-surface text-krikkit-muted hover:text-krikkit-fg'
                                                    }`}
                                                >
                                                    {selected ? '✓ ' : '+ '}
                                                    {preset.title}
                                                </button>
                                            )
                                        })}
                                    </div>
                                </div>

                                <div className="space-y-2">
                                    {pages.map((page, index) => (
                                        <div
                                            key={page.id}
                                            className="rounded-2xl border border-krikkit-line bg-krikkit-surface p-3"
                                        >
                                            <div className="flex items-center gap-2">
                                                <span className="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-krikkit-soft text-xs font-semibold text-krikkit-fg">
                                                    {index + 1}
                                                </span>
                                                <input
                                                    type="text"
                                                    value={page.title}
                                                    onChange={(e) => updatePageItem(index, { title: e.target.value })}
                                                    className="h-8 min-w-0 flex-1 rounded-lg border border-krikkit-line bg-krikkit-canvas px-2.5 text-xs font-medium text-krikkit-fg outline-none focus:border-krikkit-accent"
                                                    aria-label={`ชื่อหน้าที่ ${index + 1}`}
                                                />
                                                <span className="rounded bg-krikkit-canvas px-2 py-1 font-mono text-[10px] text-krikkit-muted">
                                                    {index === 0 ? '/' : canonicalPageRoute(page.route || page.title, index)}
                                                </span>
                                                <button
                                                    type="button"
                                                    disabled={index === 0}
                                                    onClick={() => movePage(index, -1)}
                                                    className={`rounded-lg px-2 py-1 text-xs ${LAB_BTN_OUTLINE}`}
                                                    title="เลื่อนขึ้น"
                                                >
                                                    ↑
                                                </button>
                                                <button
                                                    type="button"
                                                    disabled={index === pages.length - 1}
                                                    onClick={() => movePage(index, 1)}
                                                    className={`rounded-lg px-2 py-1 text-xs ${LAB_BTN_OUTLINE}`}
                                                    title="เลื่อนลง"
                                                >
                                                    ↓
                                                </button>
                                                <button
                                                    type="button"
                                                    disabled={pages.length <= 1}
                                                    onClick={() => removePageAt(index)}
                                                    className={`rounded-lg px-2 py-1 text-xs text-rose-600 ${LAB_BTN_OUTLINE}`}
                                                    title="ลบหน้านี้"
                                                >
                                                    ลบ
                                                </button>
                                            </div>
                                            <input
                                                type="text"
                                                value={page.desc || ''}
                                                onChange={(e) => updatePageItem(index, { desc: e.target.value })}
                                                placeholder="รายละเอียดเนื้อหาในหน้านี้..."
                                                className="mt-2 h-8 w-full rounded-lg border border-krikkit-line/70 bg-krikkit-canvas px-2.5 text-xs text-krikkit-muted outline-none focus:border-krikkit-accent focus:text-krikkit-fg"
                                            />
                                        </div>
                                    ))}
                                </div>

                                {pages.length < 5 ? (
                                    <div className="rounded-2xl border border-dashed border-krikkit-line p-3">
                                        <p className="text-xs font-medium text-krikkit-fg">เพิ่มหน้าใหม่</p>
                                        <div className="mt-2 flex flex-wrap gap-2">
                                            <input
                                                type="text"
                                                value={newPageTitle}
                                                onChange={(e) => setNewPageTitle(e.target.value)}
                                                placeholder="ชื่อหน้า เช่น รีวิวลูกค้า / คำถามพบบ่อย"
                                                className="h-9 min-w-[160px] flex-1 rounded-xl border border-krikkit-line bg-krikkit-surface px-3 text-xs text-krikkit-fg outline-none focus:border-krikkit-accent"
                                            />
                                            <input
                                                type="text"
                                                value={newPageDesc}
                                                onChange={(e) => setNewPageDesc(e.target.value)}
                                                placeholder="รายละเอียดของหน้า (ไม่บังคับ)"
                                                className="h-9 min-w-[180px] flex-1 rounded-xl border border-krikkit-line bg-krikkit-surface px-3 text-xs text-krikkit-fg outline-none focus:border-krikkit-accent"
                                            />
                                            <button
                                                type="button"
                                                onClick={addCustomPage}
                                                disabled={! newPageTitle.trim()}
                                                className={`rounded-xl px-3.5 py-1.5 text-xs font-medium ${LAB_BTN_PRIMARY}`}
                                            >
                                                + เพิ่มหน้า
                                            </button>
                                        </div>
                                    </div>
                                ) : null}
                            </div>
                        )}

                        {activeTab === 'theme' && (
                            <div className="space-y-4">
                                <div>
                                    <h3 className="text-sm font-semibold text-krikkit-fg">เลือกโทนสีเว็บไซต์ (Theme Palette)</h3>
                                    <div className="mt-2 grid gap-2 sm:grid-cols-2">
                                        {STUDIO_THEME_PALETTES.map((p) => (
                                            <button
                                                key={p.id}
                                                type="button"
                                                onClick={() => setPaletteId(p.id)}
                                                className={`flex items-center gap-3 rounded-2xl border p-2.5 text-left transition ${
                                                    paletteId === p.id
                                                        ? 'border-krikkit-accent bg-krikkit-soft ring-2 ring-krikkit-accent/30'
                                                        : 'border-krikkit-line bg-krikkit-surface hover:border-krikkit-muted'
                                                }`}
                                            >
                                                <span className="flex shrink-0 items-center -space-x-1">
                                                    <span className="h-6 w-6 rounded-full border border-black/10" style={{ backgroundColor: p.background }} />
                                                    <span className="h-6 w-6 rounded-full border border-black/10" style={{ backgroundColor: p.accent }} />
                                                    <span className="h-6 w-6 rounded-full border border-black/10" style={{ backgroundColor: p.text }} />
                                                </span>
                                                <span className="min-w-0 flex-1 truncate text-xs font-medium text-krikkit-fg">
                                                    {p.label}
                                                </span>
                                            </button>
                                        ))}
                                    </div>
                                </div>

                                <div className="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label htmlFor="studio-font-select" className="mb-1 block text-xs font-medium text-krikkit-muted">
                                            สไตล์ตัวอักษร (Typography)
                                        </label>
                                        <select
                                            id="studio-font-select"
                                            value={font}
                                            onChange={(e) => setFont(e.target.value)}
                                            className={fieldClass}
                                        >
                                            <option value="sans">Sans-serif (ทันสมัย อ่านง่าย)</option>
                                            <option value="serif">Serif (เรียบหรู สไตล์นิตยสาร)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label htmlFor="studio-radius-select" className="mb-1 block text-xs font-medium text-krikkit-muted">
                                            ความโค้งมนของการ์ดและปุ่ม
                                        </label>
                                        <select
                                            id="studio-radius-select"
                                            value={radius}
                                            onChange={(e) => setRadius(e.target.value)}
                                            className={fieldClass}
                                        >
                                            <option value="soft">โค้งมน (Soft Rounded)</option>
                                            <option value="square">เหลี่ยมคมชัด (Sharp Editorial)</option>
                                        </select>
                                    </div>
                                </div>

                                {/* Unsplash Keyword Search Box */}
                                <div className="rounded-2xl border border-krikkit-line bg-krikkit-surface/60 p-3.5">
                                    <div className="flex flex-wrap items-center justify-between gap-2">
                                        <div>
                                            <h3 className="text-sm font-semibold text-krikkit-fg">
                                                ค้นหารูปภาพจาก Unsplash ด้วย Keyword
                                            </h3>
                                            <p className="text-[11px] text-krikkit-muted">
                                                พิมพ์คำค้นหา (ไทยหรืออังกฤษ เช่น coffee, garden, office, คลินิก, ร้านอาหาร) แล้วคลิกเลือกรูป
                                            </p>
                                        </div>
                                        <div className="flex rounded-full border border-krikkit-line bg-krikkit-canvas p-0.5 text-[11px]">
                                            <button
                                                type="button"
                                                onClick={() => setUnsplashTarget('hero')}
                                                className={`rounded-full px-2.5 py-1 font-medium transition ${
                                                    unsplashTarget === 'hero'
                                                        ? 'bg-krikkit-accent text-white'
                                                        : 'text-krikkit-muted hover:text-krikkit-fg'
                                                }`}
                                            >
                                                ตั้งเป็นรูป Hero
                                            </button>
                                            <button
                                                type="button"
                                                onClick={() => setUnsplashTarget('gallery')}
                                                className={`rounded-full px-2.5 py-1 font-medium transition ${
                                                    unsplashTarget === 'gallery'
                                                        ? 'bg-krikkit-accent text-white'
                                                        : 'text-krikkit-muted hover:text-krikkit-fg'
                                                }`}
                                            >
                                                ตั้งเป็นรูป Section
                                            </button>
                                        </div>
                                    </div>

                                    <div className="mt-2.5 flex gap-2">
                                        <input
                                            type="search"
                                            value={unsplashQuery}
                                            onChange={(e) => setUnsplashQuery(e.target.value)}
                                            onKeyDown={(e) => {
                                                if (e.key === 'Enter') {
                                                    e.preventDefault()
                                                    searchUnsplash()
                                                }
                                            }}
                                            placeholder="ค้นหารูป Unsplash เช่น coffee shop, garden, modern office, spa..."
                                            className={fieldClass}
                                        />
                                        <button
                                            type="button"
                                            disabled={unsplashLoading || ! unsplashQuery.trim()}
                                            onClick={() => searchUnsplash()}
                                            className={`shrink-0 rounded-xl px-4 text-xs font-semibold ${LAB_BTN_PRIMARY}`}
                                        >
                                            {unsplashLoading ? 'กำลังค้นหา…' : 'ค้นหา Unsplash'}
                                        </button>
                                    </div>

                                    <div className="mt-2 flex flex-wrap gap-1.5">
                                        {['coffee shop', 'garden plant', 'modern office', 'retail store', 'wellness spa', 'restaurant food'].map((kw) => (
                                            <button
                                                key={kw}
                                                type="button"
                                                onClick={() => {
                                                    setUnsplashQuery(kw)
                                                    searchUnsplash(kw)
                                                }}
                                                className="rounded-full border border-krikkit-line bg-krikkit-canvas px-2.5 py-0.5 text-[10px] text-krikkit-muted hover:border-krikkit-accent hover:text-krikkit-fg"
                                            >
                                                #{kw}
                                            </button>
                                        ))}
                                    </div>

                                    {unsplashError ? (
                                        <p className="mt-2 text-xs text-rose-600">{unsplashError}</p>
                                    ) : null}

                                    {unsplashResults.length > 0 ? (
                                        <div className="mt-3 grid grid-cols-2 gap-2 sm:grid-cols-4">
                                            {unsplashResults.map((hit) => {
                                                const isHero = heroImageUrl === hit.src
                                                const isGallery = galleryImageUrl === hit.src
                                                return (
                                                    <div
                                                        key={hit.src}
                                                        className={`group relative overflow-hidden rounded-xl border bg-krikkit-canvas transition ${
                                                            isHero || isGallery
                                                                ? 'border-krikkit-accent ring-2 ring-krikkit-accent/40'
                                                                : 'border-krikkit-line hover:border-krikkit-muted'
                                                        }`}
                                                    >
                                                        <button
                                                            type="button"
                                                            onClick={() => applyUnsplashHit(hit, unsplashTarget)}
                                                            className="block w-full text-left"
                                                        >
                                                            <div className="aspect-[16/10] w-full overflow-hidden bg-krikkit-soft">
                                                                <img
                                                                    src={hit.thumb || hit.src}
                                                                    alt={hit.alt || 'Unsplash photo'}
                                                                    className="h-full w-full object-cover transition group-hover:scale-105"
                                                                />
                                                            </div>
                                                            <div className="p-1.5">
                                                                <p className="truncate text-[10px] font-medium text-krikkit-fg">
                                                                    {hit.alt || 'Unsplash'}
                                                                </p>
                                                                <p className="truncate text-[9px] text-krikkit-muted">
                                                                    by {hit.credit || 'Unsplash'}
                                                                </p>
                                                            </div>
                                                        </button>
                                                        <div className="flex border-t border-krikkit-line/60 bg-krikkit-surface text-[9px]">
                                                            <button
                                                                type="button"
                                                                onClick={() => applyUnsplashHit(hit, 'hero')}
                                                                className={`flex-1 py-1 font-medium ${
                                                                    isHero ? 'bg-krikkit-accent text-white' : 'text-krikkit-muted hover:text-krikkit-fg'
                                                                }`}
                                                            >
                                                                {isHero ? '✓ Hero' : 'ใช้เป็น Hero'}
                                                            </button>
                                                            <button
                                                                type="button"
                                                                onClick={() => applyUnsplashHit(hit, 'gallery')}
                                                                className={`flex-1 border-l border-krikkit-line/60 py-1 font-medium ${
                                                                    isGallery ? 'bg-krikkit-accent text-white' : 'text-krikkit-muted hover:text-krikkit-fg'
                                                                }`}
                                                            >
                                                                {isGallery ? '✓ Section' : 'ใช้เป็น Section'}
                                                            </button>
                                                        </div>
                                                    </div>
                                                )
                                            })}
                                        </div>
                                    ) : null}
                                </div>

                                <div>
                                    <h3 className="text-sm font-semibold text-krikkit-fg">หรือเลือกชุดภาพแนะนำ (Preset Packs)</h3>
                                    <div className="mt-2 grid grid-cols-2 gap-2 sm:grid-cols-3">
                                        {STUDIO_IMAGE_PACKS.map((pack) => (
                                            <button
                                                key={pack.id}
                                                type="button"
                                                onClick={() => selectImagePack(pack)}
                                                className={`group overflow-hidden rounded-xl border text-left transition ${
                                                    imagePackId === pack.id
                                                        ? 'border-krikkit-accent ring-2 ring-krikkit-accent/30'
                                                        : 'border-krikkit-line hover:border-krikkit-muted'
                                                }`}
                                            >
                                                <div className="aspect-[16/9] w-full overflow-hidden bg-krikkit-soft">
                                                    <img
                                                        src={pack.heroUrl}
                                                        alt={pack.label}
                                                        className="h-full w-full object-cover transition group-hover:scale-105"
                                                    />
                                                </div>
                                                <div className="p-2">
                                                    <p className="truncate text-[11px] font-medium text-krikkit-fg">{pack.label}</p>
                                                </div>
                                            </button>
                                        ))}
                                    </div>

                                    <div className="mt-3 grid gap-2 sm:grid-cols-2">
                                        <div>
                                            <label htmlFor="studio-hero-url" className="mb-1 block text-[11px] font-medium text-krikkit-muted">
                                                ลิงก์รูปภาพแบนเนอร์หลัก (Hero Image URL)
                                            </label>
                                            <input
                                                id="studio-hero-url"
                                                type="url"
                                                value={heroImageUrl}
                                                onChange={(e) => setHeroImageUrl(e.target.value)}
                                                placeholder="https://images.unsplash.com/..."
                                                className={fieldClass}
                                            />
                                        </div>
                                        <div>
                                            <label htmlFor="studio-gallery-url" className="mb-1 block text-[11px] font-medium text-krikkit-muted">
                                                ลิงก์รูปภาพประกอบเนื้อหา (Section Image URL)
                                            </label>
                                            <input
                                                id="studio-gallery-url"
                                                type="url"
                                                value={galleryImageUrl}
                                                onChange={(e) => setGalleryImageUrl(e.target.value)}
                                                placeholder="https://images.unsplash.com/..."
                                                className={fieldClass}
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>

                    {/* Right Column: Live Multi-Page + Theme + Image Preview */}
                    <div className="flex flex-col justify-between rounded-2xl border border-krikkit-line bg-krikkit-surface/50 p-3.5">
                        <div>
                            <div className="mb-2 flex items-center justify-between">
                                <span className="text-xs font-semibold text-krikkit-fg">ตัวอย่างการแสดงผล (Live Preview)</span>
                                <span className="rounded-full bg-krikkit-soft px-2 py-0.5 text-[10px] font-medium text-krikkit-muted">
                                    {pages.length} หน้า · {currentPalette.id}
                                </span>
                            </div>

                            {/* Mini Website Preview Card */}
                            <div
                                className="overflow-hidden border border-black/10 shadow-sm transition"
                                style={{
                                    backgroundColor: currentPalette.background,
                                    color: currentPalette.text,
                                    borderRadius: radius === 'square' ? '6px' : '16px',
                                    fontFamily: font === 'serif' ? 'Georgia, serif' : 'inherit',
                                }}
                            >
                                {/* Mini Header + Multi-Page Nav */}
                                <div
                                    className="flex flex-wrap items-center justify-between gap-2 border-b border-black/10 px-3.5 py-2.5"
                                    style={{ backgroundColor: currentPalette.surface }}
                                >
                                    <strong className="truncate text-xs font-bold">
                                        {brandName || seed.title}
                                    </strong>
                                    <div className="flex flex-wrap gap-1.5">
                                        {pages.map((p, idx) => (
                                            <span
                                                key={p.id}
                                                className="rounded px-1.5 py-0.5 text-[10px] font-medium"
                                                style={{
                                                    backgroundColor: idx === 0 ? currentPalette.accent : 'transparent',
                                                    color: idx === 0 ? '#ffffff' : currentPalette.text,
                                                }}
                                            >
                                                {p.title}
                                            </span>
                                        ))}
                                    </div>
                                </div>

                                {/* Mini Hero Banner with Image */}
                                <div className="grid gap-3 p-3.5 sm:grid-cols-[1.1fr_0.9fr] sm:items-center">
                                    <div>
                                        <span
                                            className="inline-block rounded-full px-2 py-0.5 text-[9px] font-semibold uppercase"
                                            style={{ backgroundColor: `${currentPalette.accent}22`, color: currentPalette.accent }}
                                        >
                                            {kind}
                                        </span>
                                        <h4 className="mt-1 text-sm font-bold leading-snug">
                                            {brandName || seed.title}
                                        </h4>
                                        <p className="mt-1 line-clamp-2 text-[11px] opacity-80">
                                            {businessDesc || 'รายละเอียดเว็บไซต์และบริการของคุณ'}
                                        </p>
                                        <span
                                            className="mt-2.5 inline-block px-3 py-1 text-[10px] font-semibold text-white"
                                            style={{
                                                backgroundColor: currentPalette.accent,
                                                borderRadius: radius === 'square' ? '4px' : '999px',
                                            }}
                                        >
                                            ดูบริการของเรา
                                        </span>
                                    </div>
                                    {heroImageUrl ? (
                                        <div
                                            className="aspect-[16/10] overflow-hidden border border-black/10"
                                            style={{ borderRadius: radius === 'square' ? '4px' : '12px' }}
                                        >
                                            <img
                                                src={heroImageUrl}
                                                alt="Hero preview"
                                                className="h-full w-full object-cover"
                                            />
                                        </div>
                                    ) : null}
                                </div>

                                {/* Mini Page Map */}
                                <div className="border-t border-black/10 px-3.5 py-2.5" style={{ backgroundColor: currentPalette.surface }}>
                                    <p className="text-[10px] font-semibold opacity-75">โครงสร้างหน้าเว็บที่จะสร้าง ({pages.length} หน้า):</p>
                                    <div className="mt-1.5 grid grid-cols-2 gap-1.5">
                                        {pages.map((p, idx) => (
                                            <div
                                                key={p.id}
                                                className="truncate rounded border border-black/10 px-2 py-1 text-[10px]"
                                                style={{ backgroundColor: currentPalette.background }}
                                            >
                                                <strong>{idx + 1}. {p.title}</strong>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {draftError ? (
                            <p className="mt-2 text-xs text-rose-600">{draftError}</p>
                        ) : null}

                        <div className="mt-3 rounded-xl bg-krikkit-canvas p-2.5 text-[11px] leading-relaxed text-krikkit-muted">
                            คุณสามารถกด <strong>“เปิดหน้าแก้ไขรายหน้าแบบละเอียด (/lab/builder)”</strong> เพื่อส่งค่า ID และรายละเอียดที่แก้ไว้ทั้งหมดไปยังหน้าแก้ไขราย Section หรือกด <strong>“สร้างเว็บไซต์หลายหน้าทันที”</strong> ได้เลย
                        </div>
                    </div>
                </div>

                {/* Footer Actions */}
                <div className="flex flex-wrap items-center justify-between gap-2.5 border-t border-krikkit-line bg-krikkit-surface/60 px-5 py-3.5 sm:px-6">
                    <button
                        type="button"
                        disabled={savingDraft}
                        onClick={handleOpenFullBuilder}
                        className={`rounded-full px-4 py-2 text-xs font-semibold ${LAB_BTN_OUTLINE}`}
                    >
                        {savingDraft ? 'กำลังส่งข้อมูลไปหน้าแก้ไข…' : 'เปิดหน้าแก้ไขรายหน้าแบบละเอียด (/lab/builder)'}
                    </button>

                    <div className="flex flex-wrap items-center gap-2">
                        <button
                            type="button"
                            onClick={onClose}
                            className={`rounded-full px-3.5 py-2 text-xs ${LAB_BTN_OUTLINE}`}
                        >
                            ยกเลิก
                        </button>
                        <button
                            type="submit"
                            className={`rounded-full px-4 py-2 text-xs font-medium ${LAB_BTN_OUTLINE}`}
                        >
                            นำค่าที่ตั้งใส่ช่อง Prompt ก่อน Build
                        </button>
                        {typeof onBuildNow === 'function' ? (
                            <button
                                type="button"
                                onClick={handleBuildImmediately}
                                className={`rounded-full px-4 py-2 text-xs font-semibold ${LAB_BTN_PRIMARY}`}
                            >
                                สร้างเว็บไซต์หลายหน้าทันที (Build)
                            </button>
                        ) : null}
                    </div>
                </div>
            </form>
        </div>
    )
}
