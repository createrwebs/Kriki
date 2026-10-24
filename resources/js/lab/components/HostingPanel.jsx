import { useState } from 'react'
import DeployPanel from './DeployPanel'
import DeploymentHistory from './DeploymentHistory'
import SecretsManager from './SecretsManager'
import SecurityScanPanel from './SecurityScanPanel'

const TABS = [
    { id: 'deploy', label: 'Deploy' },
    { id: 'history', label: 'History' },
    { id: 'secrets', label: 'Secrets' },
    { id: 'scan', label: 'Security' },
]

export function HostingPanel({ open, projectUuid, onClose }) {
    const [tab, setTab] = useState('deploy')

    if (!open || !projectUuid) return null

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm" onClick={onClose}>
            <div
                className="flex h-[80vh] w-full max-w-3xl flex-col overflow-hidden rounded-xl border border-krikkit-line bg-krikkit-canvas shadow-2xl"
                onClick={(e) => e.stopPropagation()}
            >
                {/* Header */}
                <div className="flex h-12 shrink-0 items-center justify-between border-b border-krikkit-line px-4">
                    <div className="flex items-center gap-1">
                        {TABS.map((t) => (
                            <button
                                key={t.id}
                                type="button"
                                onClick={() => setTab(t.id)}
                                className={[
                                    'rounded-md px-3 py-1.5 text-xs font-medium transition',
                                    tab === t.id
                                        ? 'bg-krikkit-fg/10 text-krikkit-fg'
                                        : 'text-krikkit-muted hover:text-krikkit-fg',
                                ].join(' ')}
                            >
                                {t.label}
                            </button>
                        ))}
                    </div>
                    <button
                        type="button"
                        onClick={onClose}
                        className="flex h-7 w-7 items-center justify-center rounded-md text-krikkit-muted transition hover:bg-krikkit-fg/5 hover:text-krikkit-fg"
                        aria-label="Close hosting panel"
                    >
                        <svg className="h-4 w-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" strokeWidth="1.5">
                            <path d="M4 4l8 8M12 4l-8 8" strokeLinecap="round" />
                        </svg>
                    </button>
                </div>

                {/* Body */}
                <div className="min-h-0 flex-1 overflow-y-auto p-4">
                    {tab === 'deploy' && <DeployPanel key={projectUuid} projectUuid={projectUuid} />}
                    {tab === 'history' && <DeploymentHistory key={projectUuid} projectUuid={projectUuid} />}
                    {tab === 'secrets' && <SecretsManager key={projectUuid} projectUuid={projectUuid} />}
                    {tab === 'scan' && <SecurityScanPanel key={projectUuid} projectUuid={projectUuid} />}
                </div>
            </div>
        </div>
    )
}
