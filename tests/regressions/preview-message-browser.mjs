// Manual browser integration fixture. Loopback only; no application session or tenant data.
import http from 'node:http'
import { readFile } from 'node:fs/promises'
import { createPreviewChromeScript } from '../../resources/js/lab/lib/previewChromeBridge.js'

const bridgeSource = await readFile(new URL('../../resources/js/lab/lib/previewChromeBridge.js', import.meta.url), 'utf8')
const serve = (handler) => new Promise((resolve) => {
    const server = http.createServer(handler)
    server.listen(0, '127.0.0.1', () => resolve(server))
})
let hostOrigin
const guest = await serve((req, res) => {
    res.setHeader('Content-Type', 'text/html; charset=utf-8')
    res.end(`<!doctype html><html><body><main><h1>Fictional preview fixture</h1><p>This page contains no account data.</p></main><script>${createPreviewChromeScript(hostOrigin)}</script></body></html>`)
})
const guestOrigin = `http://127.0.0.1:${guest.address().port}`
const host = await serve((req, res) => {
    res.setHeader('Cache-Control', 'no-store')
    if (req.url === '/bridge.js') {
        res.setHeader('Content-Type', 'text/javascript')
        res.end(bridgeSource)
        return
    }
    res.setHeader('Content-Type', 'text/html; charset=utf-8')
    res.end(`<!doctype html><html lang="en"><meta charset="utf-8"><title>Preview bridge browser verification</title>
<style>body{font:16px system-ui;margin:32px;background:#f7f9fb;color:#142030}iframe{width:42%;height:180px;margin:8px}li{margin:8px}#status{font-weight:700}</style>
<h1>Preview bridge browser verification</h1><p>Local fictional fixture. This verifies messages across two browser origins; it does not certify platform cookie or runtime isolation.</p><p id="status">Running…</p><ul id="results"></ul>
<iframe id="a" title="Project A"></iframe><iframe id="b" title="Project B"></iframe>
<script type="module">
import {connectPreviewChrome, disconnectPreviewChrome, isPreviewChromeMessage, postToPreview} from '/bridge.js'
const a=document.querySelector('#a'), b=document.querySelector('#b'), results=document.querySelector('#results')
const received=[]
let failures=0
function check(ok,label){const li=document.createElement('li');li.textContent=(ok?'PASS: ':'FAIL: ')+label;results.append(li);if(!ok) failures++}
window.addEventListener('message',event=>received.push({event,accepted:isPreviewChromeMessage(event,a)}))
const loaded=frame=>new Promise(resolve=>{frame.onload=resolve;frame.src=${JSON.stringify(`${guestOrigin}/guest`)}})
const delay=ms=>new Promise(resolve=>setTimeout(resolve,ms))
async function until(predicate){for(let i=0;i<60;i++){if(predicate()) return true;await delay(50)}return false}
await Promise.all([loaded(a),loaded(b)])
check(!postToPreview(a,{type:'reload'}),'commands are rejected before connection')
check(connectPreviewChrome(a,'10000000-0000-4000-8000-000000000001'),'connect project A at exact guest origin')
check(connectPreviewChrome(b,'10000000-0000-4000-8000-000000000002'),'connect project B independently')
check(await until(()=>received.some(x=>x.accepted&&x.event.data.type==='location')),'current frame location accepted')
check(await until(()=>received.some(x=>x.accepted&&x.event.data.type==='cover-painted')),'initial painted event preserved after handshake')
check(await until(()=>received.some(x=>x.event.source===b.contentWindow)),'sibling frame replies arrived')
check(received.filter(x=>x.event.source===b.contentWindow).every(x=>!x.accepted),'sibling frame cannot control project A')
const valid=received.find(x=>x.accepted).event
window.postMessage(valid.data,location.origin)
await delay(80)
check(received.filter(x=>x.event.source===window).every(x=>!x.accepted),'replayed packet from wrong window rejected')
check(!isPreviewChromeMessage({source:a.contentWindow,origin:'https://wrong.example',data:valid.data},a),'wrong origin rejected')
check(!isPreviewChromeMessage({source:a.contentWindow,origin:valid.origin,data:{...valid.data,projectUuid:'other'}},a),'wrong project rejected')
check(!isPreviewChromeMessage({source:a.contentWindow,origin:valid.origin,data:{...valid.data,token:'stale'}},a),'wrong token rejected')
postToPreview(a,{type:'reload'})
check(await until(()=>received.some(x=>x.accepted&&x.event.data.type==='need-reload')),'reload command and reply preserved')
postToPreview(a,{type:'inspect-start'})
check(await until(()=>received.some(x=>x.accepted&&x.event.data.type==='inspect-state'&&x.event.data.active)),'DOM inspection command and acknowledgement preserved')
const reload=new Promise(resolve=>{a.onload=()=>{connectPreviewChrome(a,'10000000-0000-4000-8000-000000000001');resolve()}})
a.src=a.src+'?reload=1'
await reload
check(!isPreviewChromeMessage(valid,a),'old document token rejected after real iframe reload')
disconnectPreviewChrome(a)
check(!postToPreview(a,{type:'reload'}),'disconnect revokes commands')
document.querySelector('#status').textContent=failures?failures+' FAILED':'All '+results.children.length+' checks passed'
</script></html>`)
})
hostOrigin = `http://127.0.0.1:${host.address().port}`
console.log(`Preview bridge fixture: ${hostOrigin}`)
console.log(`Guest origin: ${guestOrigin}`)
for (const signal of ['SIGINT', 'SIGTERM']) process.on(signal, () => { host.close(); guest.close() })
