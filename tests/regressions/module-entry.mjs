import assert from 'node:assert/strict'
import { build } from 'esbuild'
import fs from 'node:fs/promises'
import path from 'node:path'
import { pathToFileURL } from 'node:url'
import { createElement } from 'react'
import { renderToStaticMarkup } from 'react-dom/server'

const file=path.resolve('node_modules/.cache/krikkit-module-entry.mjs')
await fs.mkdir(path.dirname(file),{recursive:true})
await build({entryPoints:['resources/js/lab/hooks/useVfsWorkspace.js'],outfile:file,bundle:true,format:'esm',jsx:'automatic',external:['react','react/jsx-runtime'],plugins:[{name:'raw-source',setup(builder){
    builder.onResolve({filter:/\?raw$/},args=>({path:path.resolve(path.dirname(args.importer),args.path.slice(0,-4)),namespace:'raw-source'}))
    builder.onLoad({filter:/.*/,namespace:'raw-source'},async args=>({contents:'export default '+JSON.stringify(await fs.readFile(args.path,'utf8')),loader:'js'}))
}}]})
const {useVfsWorkspace}=await import(pathToFileURL(file))
let panel
function Probe(){panel=useVfsWorkspace({projectUuid:null,projectTitle:'Fictional',building:false}).workspaceView.panel;return null}
try{
    for(const [query,expected] of [['','preview'],['?panel=modules','modules'],['?panel=admin&apply=true','preview'],['?panel=modules-extra','preview']]){
        globalThis.window={location:{search:query}}
        renderToStaticMarkup(createElement(Probe))
        assert.equal(panel,expected,`Workspace entry ${query}`)
    }
    console.log('PASS: actual workspace hook default/module entry and unknown panel rejection (4 checks).')
}finally{delete globalThis.window;await fs.rm(file,{force:true})}
