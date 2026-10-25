import { formsRequest } from './forms.js'
const digest = async bytes => [...new Uint8Array(await crypto.subtle.digest('SHA-256', bytes))].map(byte => byte.toString(16).padStart(2,'0')).join('')
export async function loadModuleBuildContext(projectUuid) {
    return (await formsRequest(`/lab/${encodeURIComponent(projectUuid)}/modules/build-context`)).context
}
export async function verifyModuleBuildSources(pod, context) {
    if (!context) return
    for (const [path, expected] of Object.entries(context.source_files)) {
        let body
        try { body = await pod.fs.readFile('/'+path) } catch { throw new Error(`Missing managed build source: ${path}. Save and reload before building.`) }
        const bytes = body instanceof Uint8Array ? body : new TextEncoder().encode(String(body))
        if (await digest(bytes) !== expected) throw new Error(`Source changed or is not saved: ${path}. Save and review the widget before building.`)
    }
}
export async function moduleArtifactManifest(files, context) {
    if (!context) return null
    const { source_files, ...manifest } = context
    const artifact_files = {}
    for (const path of Object.keys(files).sort()) {
        if (path === '.krikkit-artifact.json') continue
        artifact_files[path] = await digest(files[path])
    }
    return new TextEncoder().encode(JSON.stringify({ ...manifest, artifact_files }))
}
