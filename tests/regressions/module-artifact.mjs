import assert from 'node:assert/strict'
import { moduleArtifactManifest, verifyModuleBuildSources } from '../../resources/js/lab/lib/moduleArtifact.js'
const bytes=value=>new TextEncoder().encode(value)
const hash=async value=>[...new Uint8Array(await crypto.subtle.digest('SHA-256',bytes(value)))].map(v=>v.toString(16).padStart(2,'0')).join('')
const source={'src/App.jsx':'<App />','public/logo.svg':'<svg>สมมติ</svg>'}
const context={version:1,project_uuid:crypto.randomUUID(),sdk_version:3,runtime_version:'krikkit-runtime-v1',modules:{orders:{uuid:crypto.randomUUID(),schema_version:2}},source_hash:'fictional-source-hash',source_files:Object.fromEntries(await Promise.all(Object.entries(source).map(async([path,value])=>[path,await hash(value)])))}
const pod={fs:{readFile:async path=>{const value=source[path.slice(1)];if(value===undefined)throw Error('Missing');return bytes(value)}}}
await verifyModuleBuildSources(pod,context)
source['src/App.jsx']='// changed during build'
await assert.rejects(()=>verifyModuleBuildSources(pod,context),/Source changed/)
delete source['src/App.jsx']
await assert.rejects(()=>verifyModuleBuildSources(pod,context),/Missing managed build source/)
const files={'index.html':bytes('<h1>fictional</h1>'),'assets/app.js':bytes('console.log("fixture")'),'.krikkit-artifact.json':bytes('untrusted old manifest')}
const manifest=JSON.parse(new TextDecoder().decode(await moduleArtifactManifest(files,context)))
assert.equal(manifest.artifact_files['index.html'],await hash('<h1>fictional</h1>'))
assert.equal(manifest.sdk_version,3)
assert.equal(manifest.modules.orders.schema_version,2)
assert.ok(!('source_files' in manifest)&&!('.krikkit-artifact.json' in manifest.artifact_files))
assert.equal(await moduleArtifactManifest(files,null),null)
console.log('PASS: build source snapshot before/after, binary hashes, version metadata and plain-site preservation (8 checks).')
