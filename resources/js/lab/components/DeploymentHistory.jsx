import { useEffect, useState, useCallback } from 'react'
import { LAB_BTN_OUTLINE } from '../lib/labConstants'

const STATUS_COLORS = {
    live: 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400',
    pending: 'bg-amber-500/15 text-amber-600 dark:text-amber-400',
    building: 'bg-blue-500/15 text-blue-600 dark:text-blue-400',
    failed: 'bg-red-500/15 text-red-600 dark:text-red-400',
    stopped: 'bg-zinc-500/15 text-zinc-500',
    superseded: 'bg-zinc-400/10 text-zinc-400',
}

function StatusBadge({ status }) {
    const color = STATUS_COLORS[status] || STATUS_COLORS.pending
    return (
        <span className={`inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-medium ${color}`}>
            {status}
        </span>
    )
}

function timeAgo(iso) {
    if (!iso) return '—'
    const diff = Date.now() - new Date(iso).getTime()
    const mins = Math.floor(diff / 60000)
    if (mins < 1) return 'just now'
    if (mins < 60) return `${mins}m ago`
    const hours = Math.floor(mins / 60)
    if (hours < 24) return `${hours}h ago`
    return `${Math.floor(hours / 24)}d ago`
}

export default function DeploymentHistory({ projectUuid }) {
    const [deployments, setDeployments] = useState([])
    const [loading, setLoading] = useState(true)
    const [error, setError] = useState(null)
    const [rolling, setRolling] = useState(null)
    const [page, setPage] = useState(1)
    const [meta, setMeta] = useState(null)

    const fetchHistory = useCallback(async (p = 1) => {
        setLoading(true)
        setError(null)
        try {
            const res = await fetch(`/api/hosting/${projectUuid}/deployments?per_page=10&page=${p}`, {
                headers: { Accept: 'application/json' },
            })
            if (!res.ok) throw new Error(`HTTP ${res.status}`)
            const json = await res.json()
            setDeployments(json.data || [])
            setMeta(json.meta || null)
        } catch (e) {
            setError(e.message)
        } finally {
            setLoading(false)
        }
    }, [projectUuid])

    useEffect(() => { fetchHistory(page) }, [fetchHistory, page])

    const handleRollback = async (deploymentId) => {
        if (!confirm('Rollback to the previous version?')) return
        setRolling(deploymentId)
        try {
            const res = await fetch(`/api/hosting/${projectUuid}/deployments/${deploymentId}/rollback`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
            })
            const json = await res.json()
            if (!res.ok) throw new Error(json.message || 'Rollback failed')
            await fetchHistory(page)
        } catch (e) {
            setError(e.message)
        } finally {
            setRolling(null)
        }
    }


    return (
        <div className="space-y-3">
            {loading && <p className="py-8 text-center text-sm text-krikkit-muted">Loading…</p>}
            {error && <p className="py-4 text-center text-sm text-red-500">{error}</p>}
            {!loading && !error && deployments.length === 0 && (
                <p className="py-8 text-center text-sm text-krikkit-muted">No deployments yet.</p>
            )}
            <ul className="space-y-2">
                {deployments.map((d) => (
                    <li key={d.id} className="flex items-center gap-3 rounded-xl border border-krikkit-line bg-krikkit-surface px-4 py-3">
                        <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-krikkit-soft text-xs font-bold text-krikkit-muted">v{d.version}</div>
                        <div className="min-w-0 flex-1">
                            <div className="flex items-center gap-2">
                                <StatusBadge status={d.status} />
                                <span className="text-[11px] text-krikkit-subtle">{d.provider}</span>
                                {d.strategy && d.strategy !== 'immediate' && (
                                    <span className="text-[11px] text-krikkit-subtle">· {d.strategy}</span>
                                )}
                            </div>
                            <p className="mt-0.5 truncate text-xs text-krikkit-muted">{d.description || d.url || '—'}</p>
                        </div>
                        <div className="shrink-0 text-right">
                            <p className="text-[11px] text-krikkit-subtle">{timeAgo(d.deployed_at || d.created_at)}</p>
                            {d.can_rollback && d.status !== 'superseded' && (
                                <button type="button" disabled={rolling === d.id} onClick={() => handleRollback(d.id)}
                                    className={`mt-1 rounded-md px-2 py-0.5 text-[11px] font-medium ${LAB_BTN_OUTLINE} disabled:opacity-50`}>
                                    {rolling === d.id ? 'Rolling…' : 'Rollback'}
                                </button>
                            )}
                        </div>
                    </li>
                ))}
            </ul>
            {meta && meta.last_page > 1 && (
                <div className="flex items-center justify-center gap-2 pt-2">
                    <button type="button" disabled={page <= 1} onClick={() => setPage(page - 1)}
                        className={`rounded-md px-2 py-1 text-xs ${LAB_BTN_OUTLINE} disabled:opacity-40`}>← Prev</button>
                    <span className="text-xs text-krikkit-muted">{page} / {meta.last_page}</span>
                    <button type="button" disabled={page >= meta.last_page} onClick={() => setPage(page + 1)}
                        className={`rounded-md px-2 py-1 text-xs ${LAB_BTN_OUTLINE} disabled:opacity-40`}>Next →</button>
                </div>
            )}
        </div>
    )
}
