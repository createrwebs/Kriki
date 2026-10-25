import React, { useEffect, useState } from 'react'

export default function NewsFeed({ apiOrigin, moduleUuid }) {
    const [items, setItems] = useState(null)
    const [article, setArticle] = useState(null)
    const [loading, setLoading] = useState(true)
    const [error, setError] = useState('')

    async function loadList() {
        setLoading(true); setError(''); setArticle(null)
        try {
            const response = await fetch(`${apiOrigin}/api/lab-widgets/${encodeURIComponent(moduleUuid)}/news`, {
                method: 'GET', credentials: 'omit', headers: { Accept: 'application/json' },
            })
            const data = await response.json()
            if (!response.ok) throw new Error(data.message || 'โหลดข่าวสารไม่สำเร็จ')
            setItems(Array.isArray(data.items) ? data.items : [])
        } catch (err) { setError(err.message || 'เชื่อมต่อไม่ได้ กรุณาลองอีกครั้ง') }
        finally { setLoading(false) }
    }

    async function loadArticle(slug) {
        setLoading(true); setError('')
        try {
            const response = await fetch(`${apiOrigin}/api/lab-widgets/${encodeURIComponent(moduleUuid)}/news?slug=${encodeURIComponent(slug)}`, {
                method: 'GET', credentials: 'omit', headers: { Accept: 'application/json' },
            })
            const data = await response.json()
            if (!response.ok) throw new Error(data.message || 'ไม่พบข่าวสารที่เลือก')
            setArticle(data.article || null)
        } catch (err) { setError(err.message || 'เชื่อมต่อไม่ได้ กรุณาลองอีกครั้ง') }
        finally { setLoading(false) }
    }

    useEffect(() => { loadList() }, [apiOrigin, moduleUuid])

    return <section className="kw-contact kw-news" aria-label="ข่าวสารและประกาศ">
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', gap: 12 }}>
            <h2>ข่าวสารและประกาศ</h2>
            {article ? <button type="button" onClick={loadList}>กลับหน้ารวมข่าว</button> : null}
        </div>
        {error ? <p role="alert">{error}</p> : null}
        {loading ? <p role="status">กำลังโหลดข่าวสาร…</p> : article ? (
            <article className="kw-news-detail">
                <h3>{article.title}</h3>
                {article.published_at ? <p><small>{new Date(article.published_at).toLocaleString('th-TH')}</small></p> : null}
                {article.excerpt ? <p><strong>{article.excerpt}</strong></p> : null}
                <p style={{ whiteSpace: 'pre-wrap' }}>{article.body}</p>
            </article>
        ) : !items || items.length === 0 ? (
            <p>ยังไม่มีข่าวประชาสัมพันธ์ในขณะนี้</p>
        ) : (
            <div className="kw-news-list">
                {items.map(item => (
                    <article key={item.uuid}>
                        <h3 style={{ margin: '0 0 6px' }}>{item.title}</h3>
                        {item.published_at ? <p style={{ margin: '0 0 6px', fontSize: '0.85em', opacity: 0.75 }}>{new Date(item.published_at).toLocaleString('th-TH')}</p> : null}
                        {item.excerpt ? <p style={{ margin: '0 0 10px' }}>{item.excerpt}</p> : null}
                        <button type="button" onClick={() => loadArticle(item.slug)}>อ่านรายละเอียด</button>
                    </article>
                ))}
            </div>
        )}
    </section>
}
