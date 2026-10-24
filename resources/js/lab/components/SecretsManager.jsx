import { useEffect, useState, useCallback } from 'react'
import { LAB_BTN_OUTLINE, LAB_BTN_PRIMARY } from '../lib/labConstants'

export default function SecretsManager({ projectUuid }) {
    const [keys, setKeys] = useState([])
    const [loading, setLoading] = useState(true)
    const [error, setError] = useState(null)
    const [saving, setSaving] = useState(false)
    const [newVars, setNewVars] = useState([{ key: '', value: '' }])
    const [message, setMessage] = useState(null)

    const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content || ''

    const fetchKeys = useCallback(async () => {
        setLoading(true)
        try {
            const res = await fetch(`/api/hosting/${projectUuid}/secrets`, {
                headers: { Accept: 'application/json' },
            })
            if (!res.ok) throw new Error(`HTTP ${res.status}`)
            const json = await res.json()
            setKeys(json.keys || [])
        } catch (e) {
            setError(e.message)
        } finally {
            setLoading(false)
        }
    }, [projectUuid])

    useEffect(() => { fetchKeys() }, [fetchKeys])

    const handleSave = async () => {
        const env = {}
        for (const row of newVars) {
            const k = row.key.trim().toUpperCase().replace(/[^A-Z0-9_]/g, '_')
            if (k && row.value) env[k] = row.value
        }
        if (Object.keys(env).length === 0) {
            setError('Add at least one KEY=VALUE pair.')
            return
        }
        setSaving(true)
        setError(null)
        setMessage(null)
        try {
            const res = await fetch(`/api/hosting/${projectUuid}/secrets`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrf() },
                body: JSON.stringify({ env }),
            })
            const json = await res.json()
            if (!res.ok) throw new Error(json.message || 'Save failed')
            setMessage(json.message || 'Secrets updated.')
            setNewVars([{ key: '', value: '' }])
            await fetchKeys()
        } catch (e) {
            setError(e.message)
        } finally {
            setSaving(false)
        }
    }

    const handleDelete = async (key) => {
        if (!confirm(`Delete secret "${key}"?`)) return
        try {
            const res = await fetch(`/api/hosting/${projectUuid}/secrets/${encodeURIComponent(key)}`, {
                method: 'DELETE',
                headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf() },
            })
            if (!res.ok) { const j = await res.json(); throw new Error(j.message || 'Delete failed') }
            await fetchKeys()
        } catch (e) {
            setError(e.message)
        }
    }

    const addRow = () => setNewVars([...newVars, { key: '', value: '' }])
    const updateRow = (i, field, val) => {
        const next = [...newVars]
        next[i] = { ...next[i], [field]: val }
        setNewVars(next)
    }
    const removeRow = (i) => setNewVars(newVars.filter((_, idx) => idx !== i))

    return (
        <div className="space-y-4">
            <p className="text-xs text-krikkit-muted">Encrypted storage only. Static React sites cannot use server secrets; redeploying does not inject these values into your site.</p>
            {error && <p className="text-sm text-red-500">{error}</p>}
            {message && <p className="text-sm text-emerald-600 dark:text-emerald-400">{message}</p>}

            <div>
                <p className="text-[11px] font-medium uppercase tracking-wide text-krikkit-subtle mb-2">
                    Stored keys ({keys.length})
                </p>
                {loading ? (
                    <p className="text-sm text-krikkit-muted">Loading…</p>
                ) : keys.length === 0 ? (
                    <p className="text-sm text-krikkit-muted">No secrets set.</p>
                ) : (
                    <ul className="space-y-1">
                        {keys.map((k) => (
                            <li key={k} className="flex items-center justify-between rounded-lg bg-krikkit-surface px-3 py-2">
                                <span className="font-mono text-xs text-krikkit-fg">{k}</span>
                                <button type="button" disabled={saving} onClick={() => handleDelete(k)}
                                    className="text-[11px] text-red-500 hover:text-red-400 font-medium disabled:opacity-50">
                                    Delete
                                </button>
                            </li>
                        ))}
                    </ul>
                )}
            </div>

            <div>
                <p className="text-[11px] font-medium uppercase tracking-wide text-krikkit-subtle mb-2">Add / update</p>
                <div className="space-y-2">
                    {newVars.map((row, i) => (
                        <div key={i} className="flex items-center gap-2">
                            <input type="text" placeholder="KEY" value={row.key} disabled={saving}
                                onChange={(e) => updateRow(i, 'key', e.target.value)}
                                className="h-9 w-32 rounded-lg border border-krikkit-line bg-krikkit-surface px-3 font-mono text-xs text-krikkit-fg outline-none focus:border-accent" />
                            <input type="password" placeholder="value" value={row.value} disabled={saving}
                                onChange={(e) => updateRow(i, 'value', e.target.value)}
                                className="h-9 flex-1 rounded-lg border border-krikkit-line bg-krikkit-surface px-3 text-xs text-krikkit-fg outline-none focus:border-accent" />
                            {newVars.length > 1 && (
                                <button type="button" disabled={saving} onClick={() => removeRow(i)} className="text-krikkit-subtle hover:text-red-500 text-sm disabled:opacity-50">✕</button>
                            )}
                        </div>
                    ))}
                </div>
                <button type="button" disabled={saving} onClick={addRow} className={`mt-2 rounded-md px-2 py-1 text-[11px] ${LAB_BTN_OUTLINE} disabled:opacity-50`}>
                    + Add row
                </button>
            </div>

            <div className="flex justify-end pt-2">
                <button type="button" disabled={saving} onClick={handleSave}
                    className={`rounded-lg px-4 py-2 text-sm font-medium ${LAB_BTN_PRIMARY} disabled:opacity-50`}>
                    {saving ? 'Saving…' : 'Save secrets'}
                </button>
            </div>
        </div>
    )
}

