import React, { useEffect, useState } from 'react'
import SiteContent from './SiteContent.jsx'
import ContactForm from './krikkit-widgets/ContactForm.jsx'
import NewsFeed from './krikkit-widgets/NewsFeed.jsx'
import StorefrontWidget from './krikkit-widgets/StorefrontWidget.jsx'
import { commerceRoute } from './krikkit-widgets/commerceRoutes.js'
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
    const storefront = config.widgets.find(widget => ['catalog', 'orders', 'manual_payments'].includes(widget.key) && commerceRoute(widget.route, route))
    const deepStoreRoute = storefront && commerceRoute(storefront.route, route).view !== 'catalog'
    return <>{!deepStoreRoute ? <SiteContent /> : null}{config.widgets.filter(widget => widget === storefront || widget.route === route).map(widget => {
        if (widget.key === 'contact') return <ContactForm key={widget.moduleUuid} apiOrigin={config.apiOrigin} moduleUuid={widget.moduleUuid} />
        if (widget.key === 'news') return <NewsFeed key={widget.moduleUuid} apiOrigin={config.apiOrigin} moduleUuid={widget.moduleUuid} />
        if (['catalog', 'orders', 'manual_payments'].includes(widget.key)) {
            const current = commerceRoute(widget.route, route)
            return current ? <StorefrontWidget key={widget.moduleUuid} apiOrigin={config.apiOrigin} moduleUuid={widget.moduleUuid} baseRoute={widget.route} routeView={current.view} routeOrderUuid={current.orderUuid} /> : null
        }
        return null
    })}</>
}
