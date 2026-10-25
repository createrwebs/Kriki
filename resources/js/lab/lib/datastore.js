export const DATASTORE_AWAIT = 'lab-datastore-await'
export const DATASTORE_READY = 'lab-datastore-ready'
export const DATASTORE_CANCELLED = 'lab-datastore-cancelled'
export const DATASTORE_OAUTH_MESSAGE = 'datastore-oauth'
export const DATASTORE_OAUTH_CHANNEL = 'krikkit-lab-datastore-oauth'
export const DATASTORE_OAUTH_STORAGE = 'krikkit-lab-datastore-oauth'

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
}

async function requestDatastore(path, { method = 'GET', body = null } = {}) {
    const headers = {
        Accept: 'application/json',
        'X-CSRF-TOKEN': csrfToken(),
        'X-Requested-With': 'XMLHttpRequest',
    }
    const init = { method, headers, credentials: 'same-origin' }
    if (body !== null) {
        headers['Content-Type'] = 'application/json'
        init.body = JSON.stringify(body)
    }

    const response = await fetch(path, init)
    const data = await response.json().catch(() => ({}))
    if (! response.ok) {
        const error = new Error(data.message || `Datastore request failed (${response.status})`)
        error.status = response.status
        error.payload = data
        throw error
    }

    return data
}

async function postDatastore(path, body) {
    return requestDatastore(path, { method: 'POST', body })
}

export function supabaseConnectUrl(returnPath = '/lab', { popup = false } = {}) {
    const params = new URLSearchParams({ return: returnPath })
    if (popup) params.set('popup', '1')
    return `/lab/oauth/supabase/start?${params}`
}

export function redirectToSupabaseOauth(returnPath = '/lab') {
    window.location.assign(supabaseConnectUrl(returnPath, { popup: false }))
}

export function openSupabaseOauthPopup(returnPath = '/lab') {
    const url = supabaseConnectUrl(returnPath, { popup: true })
    return window.open(url, 'krikkit-supabase-oauth', 'popup=yes,width=520,height=720')
}

export function isDatastoreOauthPayload(data) {
    return data?.source === 'krikkit-lab' && data?.kind === DATASTORE_OAUTH_MESSAGE
}

export function isDatastoreOauthMessage(event) {
    return isDatastoreOauthPayload(event?.data)
}

export function readDatastoreOauthStorage() {
    try {
        const raw = window.localStorage.getItem(DATASTORE_OAUTH_STORAGE)
        if (! raw) return null
        const data = JSON.parse(raw)
        return isDatastoreOauthPayload(data) ? data : null
    } catch {
        return null
    }
}

export async function readLabDatastoreStatus() {
    return requestDatastore('/lab/datastore')
}

export async function listLabDatastoreProjects() {
    return requestDatastore('/lab/datastore/projects')
}

export async function attachLabDatastoreProject(projectRef) {
    return requestDatastore('/lab/datastore/attach', {
        method: 'POST',
        body: { project_ref: String(projectRef || '').trim() },
    })
}

export async function unlinkLabDatastore() {
    return requestDatastore('/lab/datastore', { method: 'DELETE' })
}

/**
 * Pause until a project is attached. Opens the connect UI (popup OAuth).
 * Resolves with status when ready. Rejects on cancel / abort / missing OAuth app.
 *
 * @param {{ signal?: AbortSignal }} [input]
 */
export async function awaitLabDatastoreReady({ signal } = {}) {
    const current = await readLabDatastoreStatus()
    if (current.ready) return current
    if (! current.oauth_ready) {
        const error = new Error('Supabase OAuth is not configured.')
        error.code = 'oauth_unconfigured'
        throw error
    }

    return new Promise((resolve, reject) => {
        let settled = false
        let timer = 0

        const finish = (fn, value) => {
            if (settled) return
            settled = true
            window.removeEventListener(DATASTORE_READY, onReady)
            window.removeEventListener(DATASTORE_CANCELLED, onCancel)
            signal?.removeEventListener?.('abort', onAbort)
            if (timer) window.clearInterval(timer)
            fn(value)
        }

        const onReady = (event) => {
            const detail = event?.detail && typeof event.detail === 'object' ? event.detail : { ready: true }
            finish(resolve, detail)
        }
        const onCancel = () => {
            const error = new Error('Supabase connection was cancelled.')
            error.code = 'cancelled'
            finish(reject, error)
        }
        const onAbort = () => {
            const error = new Error('Stopped.')
            error.code = 'aborted'
            finish(reject, error)
        }

        window.addEventListener(DATASTORE_READY, onReady)
        window.addEventListener(DATASTORE_CANCELLED, onCancel)
        if (signal) {
            if (signal.aborted) {
                onAbort()
                return
            }
            signal.addEventListener('abort', onAbort, { once: true })
        }

        window.dispatchEvent(new CustomEvent(DATASTORE_AWAIT, { detail: current }))

        timer = window.setInterval(async () => {
            if (settled) return
            try {
                const next = await readLabDatastoreStatus()
                if (next.ready) finish(resolve, next)
            } catch {
                // keep waiting
            }
        }, 1600)
    })
}

/**
 * Server-scoped module registration metadata. No remote-wide schema or credentials.
 *
 * @param {{ projectUuid: string, fresh?: boolean }} input
 */
export async function surveyLabDatastore(input = {}) {
    if (!input.projectUuid) throw new Error('Open a saved Lab project before inspecting its database.')
    return postDatastore('/lab/datastore/survey', {
        project_uuid: input.projectUuid,
        fresh: Boolean(input.fresh),
    })
}
