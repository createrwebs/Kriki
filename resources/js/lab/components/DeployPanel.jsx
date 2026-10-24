import { useEffect, useState } from 'react'
import { LAB_BTN_PRIMARY } from '../lib/labConstants'

export default function DeployPanel({ projectUuid, onDeployed }) {
    const [providers, setProviders] = useState([])
    const [description, setDescription] = useState('')
    const [deploying, setDeploying] = useState(false)
    const [status, setStatus] = useState(null)
    const [error, setError] = useState(null)
    const [loading, setLoading] = useState(true)

    useEffect(() => {
        const controller = new AbortController()
        async function loadProviders() {
            try {
                const res = await fetch(`/api/hosting/${projectUuid}/providers`, {
                    headers: { Accept: 'application/json' }, signal: controller.signal,
                })
                if (!res.ok) throw new Error(`Unable to load hosting (HTTP ${res.status}).`)
                const json = await res.json()
                setProviders(json.providers || [])
            } catch (error) {
                if (!controller.signal.aborted) setError(error.message)
            } finally {
                if (!controller.signal.aborted) setLoading(false)
            }
        }
        loadProviders()
        return () => controller.abort()
    }, [projectUuid])

    const available = providers.some((provider) => provider.key === 'local')
    async function handleDeploy(artifactFile) {
        if (!available || deploying) return
        setDeploying(true)
        setError(null)
        setStatus('uploading')
        const form = new FormData()
        form.append('artifact', artifactFile)
        form.append('provider', 'local')
        form.append('strategy', 'immediate')
        if (description) form.append('description', description)
        try {
            const res = await fetch(`/api/hosting/${projectUuid}/deploy`, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: form,
            })
            const json = await res.json()
            if (!res.ok) throw new Error(json.message || 'Deploy failed')
            setStatus('live')
            onDeployed?.(json.deployment)
        } catch (error) {
            setStatus('failed')
            setError(error.message)
        } finally {
            setDeploying(false)
        }
    }

    if (loading) return <p className="text-sm text-krikkit-muted">Loading hosting…</p>
    return (
        <div className="space-y-4">
            <p className="text-sm font-medium text-krikkit-fg">Static hosting · immediate replacement</p>
            <p className="text-xs leading-relaxed text-krikkit-muted">
                Configure and start a build in Publish first, then upload a production ZIP containing index.html.
                Custom domains must be verified in Publish. This does not build source code or run PHP, Python, Node servers, or runtime secrets.
                Rolling, canary, server sizing and rollback are not available.
            </p>
            {error && <p role="alert" className="text-sm text-red-500">{error}</p>}
            {!available && <p className="text-sm text-krikkit-muted">Static publishing is not available for this project.</p>}
            <div>
                <label htmlFor="hosting-description" className="text-[11px] font-medium uppercase tracking-wide text-krikkit-subtle">Description (optional)</label>
                <input id="hosting-description" type="text" maxLength={500} value={description} onChange={(event) => setDescription(event.target.value)}
                    placeholder="e.g. Fix header layout"
                    className="mt-1 h-9 w-full rounded-lg border border-krikkit-line bg-krikkit-surface px-3 text-xs text-krikkit-fg outline-none focus:border-accent" />
            </div>
            {status && <p role="status" className="text-xs text-krikkit-muted">
                {status === 'uploading' ? 'Deploying…' : status === 'live' ? 'Deployment live.' : 'Deployment failed.'}
            </p>}
            <div className="flex justify-end pt-2">
                <label className={`inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium ${LAB_BTN_PRIMARY} ${deploying || !available ? 'opacity-50' : 'cursor-pointer'}`}>
                    {deploying ? 'Deploying…' : 'Choose build & deploy'}
                    <input type="file" accept=".zip" className="sr-only" disabled={deploying || !available}
                        onChange={(event) => { const file = event.target.files?.[0]; if (file) handleDeploy(file); event.target.value = '' }} />
                </label>
            </div>
        </div>
    )
}
