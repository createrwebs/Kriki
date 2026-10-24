import { SitePage } from './SitePage.jsx'

export function BlueprintPreview({
    blueprint,
    catalog,
    pageId,
    onSelectPage,
    overview = false,
    onEdit = null,
    onUploadImage = null,
    onAiSection = null,
}) {
    const pages = overview ? blueprint.pages : blueprint.pages.filter(p => p.id === pageId)
    return (
        <div className={overview ? 'bp-overview' : 'bp-single'}>
            {pages.map(page => (
                <article key={page.id} className="bp-frame-wrap">
                    {overview ? (
                        <button type="button" className="bp-page-label" onClick={() => onSelectPage(page.id)}>
                            <strong>{page.title}</strong><span>{page.route}</span><span>Open page</span>
                        </button>
                    ) : null}
                    <SitePage
                        blueprint={blueprint}
                        catalog={catalog}
                        page={page}
                        onSelectPage={onSelectPage}
                        onEdit={onEdit}
                        onUploadImage={onUploadImage}
                        onAiSection={onAiSection}
                    />
                </article>
            ))}
        </div>
    )
}