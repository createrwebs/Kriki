// Builds only the code-owned, fictional fixture created by seed-module-browser.php.
import fs from 'node:fs/promises'
import path from 'node:path'
import { build as bundle } from 'esbuild'
import { build } from 'vite'
import { pathToFileURL } from 'node:url'

const repo = process.cwd()
const fixture = JSON.parse(await fs.readFile('.agent-work/qa/module-browser-config.json', 'utf8'))
const source = path.resolve(fixture.source)
const allowed = path.join(repo, '.agent-work', 'qa', 'module-browser-')
if (!source.startsWith(allowed) || !source.includes(`${path.sep}app${path.sep}lab${path.sep}projects${path.sep}`)) throw new Error('Not a fictional fixture source directory')
await bundle({ entryPoints: ['resources/js/lab/lib/moduleArtifact.js'], outfile: '.agent-work/qa/module-artifact-helper.mjs', bundle: true, format: 'esm', platform: 'node' })
const { verifyModuleBuildSources, moduleArtifactManifest } = await import(pathToFileURL(path.resolve('.agent-work/qa/module-artifact-helper.mjs')))
if (!fixture.build_context) throw new Error('Reseed the trusted fixture to capture its source and schema build context')
const pod = { fs: { readFile: file => fs.readFile(path.join(source,file.replace(/^\//,''))) } }
await verifyModuleBuildSources(pod,fixture.build_context)
await bundle({ entryPoints: ['tests/fixtures/runtime/module-owner-browser.jsx'], bundle: true, outfile: '.agent-work/qa/module-owner.js', format: 'esm', jsx: 'automatic' })
await build({ root: source, configFile: false, build: { outDir: 'dist' } })
await verifyModuleBuildSources(pod,fixture.build_context)
const files = {}
async function collect(directory, prefix = '') {
    for (const entry of await fs.readdir(directory,{withFileTypes:true})) {
        const name = prefix+entry.name
        if (entry.isDirectory()) await collect(path.join(directory,entry.name),name+'/')
        else if (name !== '.krikkit-artifact.json') files[name] = await fs.readFile(path.join(directory,entry.name))
    }
}
await collect(path.join(source,'dist'))
await fs.writeFile(path.join(source,'dist/.krikkit-artifact.json'),await moduleArtifactManifest(files,fixture.build_context))
console.log('Trusted fixture build includes verified source/schema/SDK and actual artifact byte hashes.')
