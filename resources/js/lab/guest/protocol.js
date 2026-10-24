export const PROTOCOL = 'krikkit-runtime-v1'
export const UUID = /^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i
export const SESSION = /^[a-f0-9]{32}$/
export const FS_METHODS = new Set(['readFile', 'writeFile', 'mkdir', 'readdir', 'exists', 'stat', 'rm', 'unlink', 'rmdir', 'rename'])
export const TERMINAL_METHODS = new Set(['input', 'write', 'writeln', 'showPrompt', 'clear', 'setCwd', 'dispose', 'resize'])

export function isHandshake(event, { source, origin, project, session, type }) {
    const data = event?.data
    return event?.source === source && event?.origin === origin && data?.protocol === PROTOCOL
        && data?.type === type && data?.project === project && data?.session === session
        && UUID.test(project) && SESSION.test(session)
}

export function previewUrl(origin, instance, port) {
    if (!/^lab[a-zA-Z0-9_-]+$/.test(instance) || !Number.isInteger(port) || port < 1 || port > 65535) return null
    return `${origin}/__virtual__/${instance}/${port}`
}

export function isPreviewUrl(value, origin, instance, port) {
    try { return new URL(value).href.replace(/\/$/, '') === previewUrl(origin, instance, port) }
    catch { return false }
}
