import { useState } from 'react'
import { LAB_BTN_PRIMARY } from '../lib/labConstants'

const SEV_STYLE = {
    critical: 'border-red-500/30 bg-red-500/5',
    high: 'border-orange-500/30 bg-orange-500/5',
    medium: 'border-amber-500/30 bg-amber-500/5',
    low: 'border-blue-500/30 bg-blue-500/5',
}
const SEV_BADGE = {
    critical: 'bg-red-500/15 text-red-600 dark:text-red-400',
    high: 'bg-orange-500/15 text-orange-600 dark:text-orange-400',
    medium: 'bg-amber-500/15 text-amber-600 dark:text-amber-400',
    low: 'bg-blue-500/15 text-blue-600 dark:text-blue-400',
}

export default function SecurityScanPanel({ projectUuid }) {
    const [scanning, setScanning] = useState(false)
    const [result, setResult] = useState(null)
    const [error, setError] = useState(null)
    const [failOn, setFailOn] = useState('low')

    const handleScan = async () => {
        setScanning(true); setError(null); setResult(null)
        try {
            const res = await fetch(`/api/hosting/${projectUuid}/scan`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
                body: JSON.stringify({ fail_on: failOn }),
            })
            const json = await res.json()
            if ((!res.ok && res.status !== 422) || !Array.isArray(json.violations) || typeof json.failed !== 'boolean') {
                throw new Error(json.message || 'Scan failed')
            }
            setResult(json)
        } catch (e) { setError(e.message) } finally { setScanning(false) }
    }

    return (
        <div className="space-y-4">
            <p className="text-xs text-krikkit-muted">Pattern checks only, not a security certification. This scan does not block deployment or verify dependencies and runtime isolation.</p>
            {error && <p className="text-sm text-red-500">{error}</p>}
            <div>
                <label className="text-[11px] font-medium uppercase tracking-wide text-krikkit-subtle">Fail threshold</label>
                <select value={failOn} onChange={(e) => setFailOn(e.target.value)}
                    className="mt-1 h-9 w-full rounded-lg border border-krikkit-line bg-krikkit-surface px-3 text-xs text-krikkit-fg outline-none focus:border-accent">
                    <option value="low">Low (block on any finding)</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="critical">Critical only</option>
                </select>
            </div>
            <div className="flex justify-end">
                <button type="button" disabled={scanning} onClick={handleScan}
                    className={`rounded-lg px-4 py-2 text-sm font-medium ${LAB_BTN_PRIMARY} disabled:opacity-50`}>
                    {scanning ? 'Scanning…' : 'Run security scan'}
                </button>
            </div>
            {result && (
                <div className="space-y-3">
                    <div className="flex items-center gap-3">
                        <span className={`rounded-full px-3 py-1 text-xs font-semibold ${!result.failed ? 'bg-emerald-500/15 text-emerald-600' : 'bg-red-500/15 text-red-600'}`}>
                            {result.failed ? 'Threshold exceeded' : 'Within selected threshold'}
                        </span>
                        <span className="text-xs text-krikkit-muted">
                            {result.violations.length} findings · {result.violations.filter((finding) => finding.severity === 'critical').length} critical · {result.violations.filter((finding) => finding.severity === 'high').length} high
                        </span>
                    </div>
                    {result.violations.length > 0 && (
                        <ul className="space-y-2">
                            {result.violations.map((f, i) => (
                                <li key={i} className={`rounded-lg border px-3 py-2 ${SEV_STYLE[f.severity] || SEV_STYLE.low}`}>
                                    <div className="flex items-center gap-2">
                                        <span className={`rounded-full px-2 py-0.5 text-[10px] font-bold uppercase ${SEV_BADGE[f.severity] || SEV_BADGE.low}`}>
                                            {f.severity}
                                        </span>
                                        <span className="text-xs font-medium text-krikkit-fg">{f.title || f.rule || 'Finding'}</span>
                                    </div>
                                    {f.recommendation && <p className="mt-1 text-[11px] text-krikkit-muted">{f.recommendation}</p>}
                                    {f.file && <p className="mt-0.5 font-mono text-[10px] text-krikkit-subtle">{f.file}{f.line ? `:${f.line}` : ''}</p>}
                                </li>
                            ))}
                        </ul>
                    )}
                    {result.violations.length === 0 && (
                        <p className="text-sm text-krikkit-muted">No matching patterns in the scanned files.</p>
                    )}
                </div>
            )}
        </div>
    )
}

