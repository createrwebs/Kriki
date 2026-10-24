// Test-only tools: npm install --prefix .agent-work/test-deps --ignore-scripts react@19.1.1 react-test-renderer@19.1.1
import assert from 'node:assert/strict'
import { readFile, writeFile, unlink } from 'node:fs/promises'
import { transform } from 'esbuild'
import React from '../../.agent-work/test-deps/node_modules/react/index.js'
import renderer from '../../.agent-work/test-deps/node_modules/react-test-renderer/index.js'
const fixture = new URL('../../.agent-work/test-deps/widget-navigation-fixture.mjs', import.meta.url)
const reactUrl = new URL('../../.agent-work/test-deps/node_modules/react/index.js', import.meta.url).href
let source = await readFile('resources/js/lab/widget-runtime/app-wrapper.jsx','utf8')
source = source.replace("from 'react'", `from ${JSON.stringify(reactUrl)}`)
    .replace("import SiteContent from './SiteContent.jsx'", 'const SiteContent = () => React.createElement("main",null,"Site content")')
    .replace("import ContactForm from './krikkit-widgets/ContactForm.jsx'", 'const ContactForm = props => React.createElement("form", {"data-module":props.moduleUuid})')
    .replace("import NewsFeed from './krikkit-widgets/NewsFeed.jsx'", 'const NewsFeed = () => null')
    .replace("import StorefrontWidget from './krikkit-widgets/StorefrontWidget.jsx'", 'const StorefrontWidget = () => null')
    .replace("import config from './krikkit-widgets/config.json'", 'const config = {apiOrigin:"https://api.example.invalid",widgets:[{key:"contact",moduleUuid:"home",route:"/"},{key:"contact",moduleUuid:"other",route:"/other"}]}')
    .replace("import './krikkit-widgets/widgets.css'", '')
await writeFile(fixture, (await transform(source,{loader:'jsx',format:'esm'})).code)
const previousWindow = globalThis.window
const listeners = new Set()
globalThis.window = { location:{pathname:'/'},addEventListener(type, listener){if(type==='popstate')listeners.add(listener)},removeEventListener(type,listener){if(type==='popstate')listeners.delete(listener)} }
globalThis.IS_REACT_ACT_ENVIRONMENT = true
let tree
try {
    const { default: App } = await import(fixture.href)
    await renderer.act(() => { tree = renderer.create(React.createElement(App)) })
    const visible = () => tree.root.findAllByType('form').map(node=>node.props['data-module'])
    assert.deepEqual(visible(),['home'])
    await renderer.act(() => { window.location.pathname='/other'; for(const listener of listeners)listener() })
    assert.deepEqual(visible(),['other'])
    await renderer.act(() => { window.location.pathname='/missing'; for(const listener of listeners)listener() })
    assert.deepEqual(visible(),[])
    await renderer.act(() => { window.location.pathname='/'; for(const listener of listeners)listener() })
    assert.deepEqual(visible(),['home'])
    await renderer.act(() => tree.unmount())
    assert.equal(listeners.size,0)
    console.log('PASS: actual widget wrapper route state, popstate, return and cleanup (5 checks).')
} finally { globalThis.window=previousWindow; delete globalThis.IS_REACT_ACT_ENVIRONMENT; await unlink(fixture) }
