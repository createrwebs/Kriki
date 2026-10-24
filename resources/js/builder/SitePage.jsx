import React from 'react'

function extractTrustedImageUrl(text) {
    if (typeof text !== 'string' || ! text) return { cleanText: '', imageUrl: null, rawTag: '' }
    const tagMatch = text.match(/\[(?:Hero|Section)\s+Image:\s*((?:https?:\/\/images\.unsplash\.com\/|\/build\/assets\/|\/assets\/|\/storage\/)[^\s\]]+)\]/i)
    if (tagMatch) {
        return {
            cleanText: text.replace(tagMatch[0], '').trim(),
            imageUrl: tagMatch[1],
            rawTag: tagMatch[0],
        }
    }
    const inlineMatch = text.match(/(?:ภาพหลัก|ภาพประกอบ)\s*(?:\([^)]*\))?:\s*((?:https?:\/\/images\.unsplash\.com\/|\/build\/assets\/|\/assets\/|\/storage\/)\S+)/i)
    if (inlineMatch) {
        return {
            cleanText: text.replace(inlineMatch[0], '').replace(/\s*·\s*$/, '').trim(),
            imageUrl: inlineMatch[1],
            rawTag: `[Section Image: ${inlineMatch[1]}]`,
        }
    }
    return { cleanText: text, imageUrl: null, rawTag: '' }
}

function stripWidgetTag(heading) {
    if (typeof heading !== 'string') return { cleanHeading: '', widgetTag: '' }
    const match = heading.match(/\s*(\[widget:(?:form|news|cart)\])\s*/i)
    if (match) {
        return {
            cleanHeading: heading.replace(match[0], ' ').trim() || heading,
            widgetTag: match[1],
        }
    }
    return { cleanHeading: heading, widgetTag: '' }
}

function parseFieldSpec(item) {
    const rawBody = String(item?.body || '').trim()
    let fieldType = 'text'
    let placeholder = rawBody || `กรอก${item?.title || 'ข้อมูล'}`
    let options = []
    const typeMatch = rawBody.match(/\[type:([a-z]+)\]/i)
    if (typeMatch) {
        fieldType = typeMatch[1].toLowerCase()
        if (fieldType === 'select') {
            const optMatch = rawBody.split(/ตัวเลือก:|options:/i)[1]
            options = optMatch ? optMatch.split(',').map((s) => s.trim()).filter(Boolean) : ['ตัวเลือกที่ 1', 'ตัวเลือกที่ 2']
        }
    } else if (/textarea|ข้อความหลายบรรทัด|รายละเอียด/i.test(rawBody)) {
        fieldType = 'textarea'
    } else if (/select|ตัวเลือก:/i.test(rawBody)) {
        fieldType = 'select'
        const optMatch = rawBody.split(/ตัวเลือก:|options:/i)[1]
        options = optMatch ? optMatch.split(',').map((s) => s.trim()).filter(Boolean) : ['ตัวเลือกที่ 1', 'ตัวเลือกที่ 2']
    } else if (/email|อีเมล/i.test(item?.title || rawBody)) {
        fieldType = 'email'
    } else if (/tel|phone|เบอร์โทร/i.test(item?.title || rawBody)) {
        fieldType = 'tel'
    } else if (/date|วันที่/i.test(item?.title || rawBody)) {
        fieldType = 'date'
    } else if (/file|แนบสลิป|อัปโหลด/i.test(item?.title || rawBody)) {
        fieldType = 'file'
    }
    const cleanPlaceholder = placeholder.replace(/\[type:[^\]]+\]/gi, '').trim()
    return { fieldType, placeholder: cleanPlaceholder, options, typeTag: typeMatch ? typeMatch[0] : '' }
}

function Section({
    section,
    pageId,
    sectionIndex,
    totalSections,
    onEdit,
    onUploadImage,
    onAiSection,
}) {
    const { type, variant, heading, body, items } = section
    const { cleanText, imageUrl, rawTag } = extractTrustedImageUrl(body)
    const { cleanHeading, widgetTag } = stripWidgetTag(heading)
    const isFormWidget = /\[widget:form\]|แบบฟอร์ม|ติดต่อเรา|ลงทะเบียน|จองคิว|แจ้งชำระเงิน/i.test(`${heading} ${body}`) && items.length > 0
    const isNewsWidget = /\[widget:news\]|ข่าวสาร|บทความ|อัปเดต|โปรโมชั่น/i.test(`${heading} ${body}`) && items.length > 0
    const isCartWidget = /\[widget:cart\]|ตะกร้าสินค้า|รายการสินค้า|ชำระเงิน|promptpay/i.test(`${heading} ${body}`) && items.length > 0
    const editable = typeof onEdit === 'function'

    const commitHeading = (nextText) => {
        if (! editable) return
        const trimmed = String(nextText || '').replace(/\s+/g, ' ').trim()
        if (! trimmed) return
        const finalHeading = widgetTag ? `${trimmed} ${widgetTag}`.slice(0, 120) : trimmed.slice(0, 120)
        if (finalHeading !== heading) {
            onEdit({
                type: 'update-section',
                pageId,
                sectionId: section.id,
                patch: { heading: finalHeading },
            })
        }
    }

    const commitBody = (nextText) => {
        if (! editable) return
        const trimmed = String(nextText || '').trim()
        const finalBody = rawTag ? (trimmed ? `${trimmed}\n${rawTag}` : rawTag) : trimmed
        if (finalBody.slice(0, 2000) !== body) {
            onEdit({
                type: 'update-section',
                pageId,
                sectionId: section.id,
                patch: { body: finalBody.slice(0, 2000) },
            })
        }
    }

    const commitItemTitle = (itemId, nextTitle) => {
        if (! editable) return
        const trimmed = String(nextTitle || '').replace(/\s+/g, ' ').trim()
        if (! trimmed) return
        onEdit({
            type: 'update-section',
            pageId,
            sectionId: section.id,
            patch: {
                items: items.map((it) => (it.id === itemId ? { ...it, title: trimmed.slice(0, 120) } : it)),
            },
        })
    }

    const commitItemBody = (item, nextBody) => {
        if (! editable) return
        const spec = parseFieldSpec(item)
        const trimmed = String(nextBody || '').trim()
        const finalItemBody = spec.typeTag ? `${spec.typeTag} ${trimmed}`.trim() : trimmed
        onEdit({
            type: 'update-section',
            pageId,
            sectionId: section.id,
            patch: {
                items: items.map((it) => (it.id === item.id ? { ...it, body: finalItemBody.slice(0, 500) } : it)),
            },
        })
    }

    return (
        <section className={`bp-section bp-${type} bp-${variant}${editable ? ' bp-wysiwyg-section' : ''}`}>
            {editable ? (
                <div className="bp-wysiwyg-toolbar" contentEditable={false}>
                    <span className="bp-wysiwyg-badge" title="คลิกที่ข้อความใดก็ได้ในส่วนนี้เพื่อแก้ไขได้ทันที">
                        ✏️ แก้ไขข้อความบนหน้าเว็บได้ทันที (WYSIWYG)
                    </span>
                    <div className="bp-wysiwyg-actions">
                        {onUploadImage ? (
                            <button
                                type="button"
                                className="bp-wysiwyg-btn"
                                onClick={() => onUploadImage(pageId, section.id, body, type === 'hero')}
                            >
                                📷 {imageUrl ? 'เปลี่ยนรูป / อัปโหลดรูป' : 'อัปโหลดรูปภาพ'}
                            </button>
                        ) : null}
                        {imageUrl ? (
                            <button
                                type="button"
                                className="bp-wysiwyg-btn"
                                onClick={() =>
                                    onEdit({
                                        type: 'update-section',
                                        pageId,
                                        sectionId: section.id,
                                        patch: { body: cleanText },
                                    })
                                }
                            >
                                ลบรูป
                            </button>
                        ) : null}
                        {onAiSection ? (
                            <button
                                type="button"
                                className="bp-wysiwyg-btn bp-wysiwyg-ai"
                                onClick={() => onAiSection(pageId, section.id, cleanHeading)}
                            >
                                ✨ สั่ง AI แก้ส่วนนี้
                            </button>
                        ) : null}
                        {items.length < 6 ? (
                            <button
                                type="button"
                                className="bp-wysiwyg-btn"
                                onClick={() =>
                                    onEdit({
                                        type: 'update-section',
                                        pageId,
                                        sectionId: section.id,
                                        patch: {
                                            items: [
                                                ...items,
                                                {
                                                    id: crypto.randomUUID(),
                                                    title: isFormWidget ? `ช่องข้อมูล ${items.length + 1}` : `หัวข้อใหม่ ${items.length + 1}`,
                                                    body: isFormWidget ? '[type:text] กรอกข้อมูล...' : 'คลิกเพื่อแก้ไขรายละเอียด...',
                                                },
                                            ],
                                        },
                                    })
                                }
                            >
                                + เพิ่มรายการ
                            </button>
                        ) : null}
                    </div>
                </div>
            ) : null}

            <div className={imageUrl ? 'bp-section-grid' : undefined}>
                <div className="bp-copy">
                    <h2
                        className={editable ? 'bp-editable' : undefined}
                        contentEditable={editable || undefined}
                        suppressContentEditableWarning={editable || undefined}
                        onBlur={editable ? (e) => commitHeading(e.currentTarget.textContent) : undefined}
                    >
                        {editable ? cleanHeading : heading}
                    </h2>
                    {cleanText || editable ? (
                        <p
                            className={editable ? 'bp-editable' : undefined}
                            contentEditable={editable || undefined}
                            suppressContentEditableWarning={editable || undefined}
                            onBlur={editable ? (e) => commitBody(e.currentTarget.textContent) : undefined}
                        >
                            {cleanText || (editable ? 'คลิกเพื่อเพิ่มคำอธิบายส่วนนี้...' : '')}
                        </p>
                    ) : null}
                </div>
                {imageUrl ? (
                    <div
                        className={`bp-visual-box${editable ? ' bp-visual-editable' : ''}`}
                        role="img"
                        aria-label={cleanHeading || heading}
                        style={{
                            backgroundImage: `url("${imageUrl.replace(/["\\]/g, '')}")`,
                        }}
                        onClick={editable && onUploadImage ? () => onUploadImage(pageId, section.id, body, type === 'hero') : undefined}
                        title={editable ? 'คลิกเพื่ออัปโหลดรูปภาพใหม่แทนที่รูปนี้' : undefined}
                    >
                        {editable && onUploadImage ? (
                            <span className="bp-visual-overlay">📷 คลิกเพื่อเปลี่ยนรูปภาพ</span>
                        ) : null}
                    </div>
                ) : null}
            </div>

            {isFormWidget ? (
                <form className="bp-widget-form" onSubmit={(e) => e.preventDefault()}>
                    <div className="bp-form-grid">
                        {items.map((item) => {
                            const spec = parseFieldSpec(item)
                            return (
                                <label key={item.id} className={spec.fieldType === 'textarea' ? 'bp-form-field bp-form-full' : 'bp-form-field'}>
                                    <span
                                        className={`bp-form-label${editable ? ' bp-editable' : ''}`}
                                        contentEditable={editable || undefined}
                                        suppressContentEditableWarning={editable || undefined}
                                        onBlur={editable ? (e) => commitItemTitle(item.id, e.currentTarget.textContent) : undefined}
                                    >
                                        {item.title}
                                    </span>
                                    {spec.fieldType === 'textarea' ? (
                                        <textarea rows={3} placeholder={spec.placeholder} readOnly />
                                    ) : spec.fieldType === 'select' ? (
                                        <select defaultValue="">
                                            <option value="" disabled>{spec.placeholder || 'กรุณาเลือก...'}</option>
                                            {spec.options.map((opt) => <option key={opt} value={opt}>{opt}</option>)}
                                        </select>
                                    ) : (
                                        <input type={spec.fieldType} placeholder={spec.placeholder} readOnly />
                                    )}
                                </label>
                            )
                        })}
                    </div>
                    <button type="button" className="bp-demo-action">ส่งข้อมูล / ยืนยันแบบฟอร์ม</button>
                </form>
            ) : isNewsWidget ? (
                <div className="bp-items bp-news-grid">
                    {items.map((item, index) => (
                        <article key={item.id} className="bp-item bp-news-card">
                            <span className="bp-news-badge">ข่าวประชาสัมพันธ์ #{index + 1}</span>
                            <h3
                                className={editable ? 'bp-editable' : undefined}
                                contentEditable={editable || undefined}
                                suppressContentEditableWarning={editable || undefined}
                                onBlur={editable ? (e) => commitItemTitle(item.id, e.currentTarget.textContent) : undefined}
                            >
                                {item.title}
                            </h3>
                            {item.body || editable ? (
                                <p
                                    className={editable ? 'bp-editable' : undefined}
                                    contentEditable={editable || undefined}
                                    suppressContentEditableWarning={editable || undefined}
                                    onBlur={editable ? (e) => commitItemBody(item, e.currentTarget.textContent) : undefined}
                                >
                                    {item.body}
                                </p>
                            ) : null}
                            <span className="bp-news-more">อ่านรายละเอียดเพิ่มเติม →</span>
                        </article>
                    ))}
                </div>
            ) : isCartWidget ? (
                <div className="bp-items bp-cart-grid">
                    {items.map((item, index) => (
                        <article key={item.id} className="bp-item bp-cart-card">
                            <span className="bp-item-mark" aria-hidden="true">สินค้า #{index + 1}</span>
                            <h3
                                className={editable ? 'bp-editable' : undefined}
                                contentEditable={editable || undefined}
                                suppressContentEditableWarning={editable || undefined}
                                onBlur={editable ? (e) => commitItemTitle(item.id, e.currentTarget.textContent) : undefined}
                            >
                                {item.title}
                            </h3>
                            {item.body || editable ? (
                                <p
                                    className={editable ? 'bp-editable' : undefined}
                                    contentEditable={editable || undefined}
                                    suppressContentEditableWarning={editable || undefined}
                                    onBlur={editable ? (e) => commitItemBody(item, e.currentTarget.textContent) : undefined}
                                >
                                    {item.body}
                                </p>
                            ) : null}
                            <span className="bp-cart-btn">+ หยิบใส่ตะกร้า / ชำระเงิน</span>
                        </article>
                    ))}
                </div>
            ) : items.length ? (
                <div className="bp-items">
                    {items.map((item, index) => (
                        <article key={item.id} className="bp-item">
                            <span className="bp-item-mark" aria-hidden="true">{String(index + 1).padStart(2, '0')}</span>
                            <h3
                                className={editable ? 'bp-editable' : undefined}
                                contentEditable={editable || undefined}
                                suppressContentEditableWarning={editable || undefined}
                                onBlur={editable ? (e) => commitItemTitle(item.id, e.currentTarget.textContent) : undefined}
                            >
                                {item.title}
                            </h3>
                            {item.body || editable ? (
                                <p
                                    className={editable ? 'bp-editable' : undefined}
                                    contentEditable={editable || undefined}
                                    suppressContentEditableWarning={editable || undefined}
                                    onBlur={editable ? (e) => commitItemBody(item, e.currentTarget.textContent) : undefined}
                                >
                                    {item.body}
                                </p>
                            ) : null}
                        </article>
                    ))}
                </div>
            ) : null}

            {type === 'cta' && ! isFormWidget ? <span className="bp-demo-action">{cleanHeading || heading}</span> : null}
        </section>
    )
}

/** Code-owned renderer shared by editor preview and exported customer source. */
export function SitePage({
    blueprint,
    catalog,
    page,
    onSelectPage = null,
    onEdit = null,
    onUploadImage = null,
    onAiSection = null,
}) {
    const palette = catalog.palettes[blueprint.theme.palette]
    const editable = typeof onEdit === 'function'
    const style = {
        '--bp-bg': palette.background, '--bp-surface': palette.surface,
        '--bp-text': palette.text, '--bp-accent': palette.accent,
        '--bp-radius': catalog.radii[blueprint.theme.radius], fontFamily: catalog.fonts[blueprint.theme.font],
    }

    const commitBrand = (nextBrand) => {
        if (! editable) return
        const trimmed = String(nextBrand || '').replace(/\s+/g, ' ').trim()
        if (trimmed && trimmed !== blueprint.shared.brand) {
            onEdit({ type: 'update-shared', patch: { brand: trimmed.slice(0, 80) } })
        }
    }

    const commitFooter = (nextFooter) => {
        if (! editable) return
        const trimmed = String(nextFooter || '').replace(/\s+/g, ' ').trim()
        if (trimmed !== blueprint.shared.footer) {
            onEdit({ type: 'update-shared', patch: { footer: trimmed.slice(0, 240) } })
        }
    }

    return (
        <div className="bp-site" style={style}>
            <header className="bp-header">
                <strong
                    className={editable ? 'bp-editable' : undefined}
                    contentEditable={editable || undefined}
                    suppressContentEditableWarning={editable || undefined}
                    onBlur={editable ? (e) => commitBrand(e.currentTarget.textContent) : undefined}
                >
                    {blueprint.shared.brand}
                </strong>
                <nav aria-label={`${page.title} ${onSelectPage ? 'preview navigation' : 'navigation'}`}>
                    {blueprint.pages.filter(p => p.navigation).map(p => onSelectPage ? (
                        <button type="button" key={p.id} aria-current={p.id === page.id ? 'page' : undefined} onClick={() => onSelectPage(p.id)}>{p.title}</button>
                    ) : (
                        <a key={p.id} href={p.route} aria-current={p.id === page.id ? 'page' : undefined}>{p.title}</a>
                    ))}
                </nav>
            </header>
            {blueprint.kind === 'ecommerce' ? <p className="bp-notice">Storefront design demo · No cart, checkout or payments connected</p> : null}
            {blueprint.kind === 'blog' ? <p className="bp-notice">Journal design demo · No publishing system connected</p> : null}
            <main className="bp-content">
                {page.sections.map((section, idx) => (
                    <Section
                        key={section.id}
                        section={section}
                        pageId={page.id}
                        sectionIndex={idx}
                        totalSections={page.sections.length}
                        onEdit={onEdit}
                        onUploadImage={onUploadImage}
                        onAiSection={onAiSection}
                    />
                ))}
            </main>
            <footer className="bp-footer">
                <strong
                    className={editable ? 'bp-editable' : undefined}
                    contentEditable={editable || undefined}
                    suppressContentEditableWarning={editable || undefined}
                    onBlur={editable ? (e) => commitBrand(e.currentTarget.textContent) : undefined}
                >
                    {blueprint.shared.brand}
                </strong>
                <span
                    className={editable ? 'bp-editable' : undefined}
                    contentEditable={editable || undefined}
                    suppressContentEditableWarning={editable || undefined}
                    onBlur={editable ? (e) => commitFooter(e.currentTarget.textContent) : undefined}
                >
                    {blueprint.shared.footer}
                </span>
            </footer>
        </div>
    )
}
