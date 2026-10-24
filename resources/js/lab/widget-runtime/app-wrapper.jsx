import React, { useEffect, useState } from 'react'
import SiteContent from './SiteContent.jsx'
import ContactForm from './krikkit-widgets/ContactForm.jsx'
import NewsFeed from './krikkit-widgets/NewsFeed.jsx'
import StorefrontWidget from './krikkit-widgets/StorefrontWidget.jsx'
import config from './krikkit-widgets/config.json'
import './krikkit-widgets/widgets.css'

function currentRoute() {
    try { return decodeURIComponent(window.location.pathname).replace(/\/$/, '') || '/' } catch { return null }
}

export default function App() {
    const [route, setRoute] = useState(currentRoute)
    useEffect(() => {
        const sync = () => setRoute(currentRoute())
        window.addEventListener('popstate', sync)
        return () => window.removeEventListener('popstate', sync)
    }, [])
    return <><SiteContent />{config.widgets.filter(widget => widget.route === route).map(widget => {
        if (widget.key === 'contact') return <ContactForm key={widget.moduleUuid} apiOrigin={config.apiOrigin} moduleUuid={widget.moduleUuid} />
        if (widget.key === 'news') return <NewsFeed key={widget.moduleUuid} apiOrigin={config.apiOrigin} moduleUuid={widget.moduleUuid} />
        if (['catalog', 'orders', 'manual_payments'].includes(widget.key)) return <StorefrontWidget key={widget.moduleUuid} apiOrigin={config.apiOrigin} moduleUuid={widget.moduleUuid} />
        return null
    })}</>
}
