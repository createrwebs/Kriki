export async function moduleBufferConflicts(contents, expected) {
    const conflicts = []
    for (const [path, hash] of Object.entries(expected)) {
        if (!Object.hasOwn(contents, path)) continue
        const digest = [...new Uint8Array(await crypto.subtle.digest('SHA-256', new TextEncoder().encode(String(contents[path]))))].map(byte => byte.toString(16).padStart(2, '0')).join('')
        if (digest !== hash) conflicts.push(path)
    }
    return conflicts
}
