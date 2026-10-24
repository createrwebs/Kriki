import React, { useEffect, useState } from 'react'
import { SitePage } from './krikkit-site/SitePage.jsx'
import blueprint from './krikkit-site/site.json'
import catalog from './krikkit-site/catalog.json'

function currentPath() {
    try { return decodeURIComponent(window.location.pathname).replace(/\/$/, '') || '/' }
    catch { return null }
}

export default function App() {
    const [path, setPath] = useState(currentPath)
    useEffect(() => {
        const sync = () => setPath(currentPath())
        window.addEventListener('popstate', sync)
        return () => window.removeEventListener('popstate', sync)
    }, [])
    const page = blueprint.pages.find(p => p.route === path)
    if (!page) return <main style={{ padding: 32, fontFamily: 'sans-serif' }}><h1>Page not found</h1><a href="/">Return home</a></main>
    return <SitePage blueprint={blueprint} catalog={catalog} page={page} />
}
