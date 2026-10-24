import { bootGuestRuntime } from '../../../resources/js/lab/lib/guestRuntime.js'
import { buildLabPublishArtifact } from '../../../resources/js/lab/lib/publishBuild.js'
import { destroyLabSession } from '../../../resources/js/lab/lib/labRuntime.js'

const report = document.getElementById('report')
const lines = []
const pods = []
const checks = []
const note = (value) => { lines.push(value); report.textContent = lines.join('\n') }
const check = (condition, label) => { checks.push({ label, pass: Boolean(condition) }); note(`${condition ? 'PASS' : 'FAIL'} ${label}`) }
document.cookie = 'krikkit_boundary_sentinel=fictional-test; Path=/; SameSite=Lax'

async function run() {
    note('Starting actual isolated browser runtime…')
    const serverSignals = []
    for (let index = 0; index < 2; index++) {
        let ready
        const listening = new Promise((resolve) => { ready = resolve })
        serverSignals.push(listening)
        const pod = await bootGuestRuntime({ files: {}, workdir: '/', env: { HOME: '/', PWD: '/' }, onServerReady: (port, url) => ready({ port, url }) })
        pods.push(pod)
        check(pod.origin !== location.origin, `runtime ${index + 1} uses distinct origin`)
        await pod.fs.writeFile('/proof.txt', `fictional-project-${index}`)
        check(await pod.fs.readFile('/proof.txt', 'utf8') === `fictional-project-${index}`, `runtime ${index + 1} filesystem roundtrip`)
        const finite = await pod.spawn('node', ['-e', 'console.log("actual-guest-process")'])
        const result = await finite.completion
        check(result.exitCode === 0 && result.stdout.includes('actual-guest-process'), `runtime ${index + 1} actual process completion`)
    }
    for (let index = 0; index < pods.length; index++) {
        const pod = pods[index]
        const html = `<!doctype html><title>Guest boundary proof ${index + 1}</title><h1>Guest ${index + 1}</h1><pre id="boundary">Checking…</pre><script>
            (async()=>{
              const output={};
              try { void parent.document.body; output.parentBlocked=false; } catch { output.parentBlocked=true; }
              output.platformCookieAbsent=!document.cookie.includes('krikkit_boundary_sentinel');
              try { const response=await fetch(${JSON.stringify(`${location.origin}/lab/builder/drafts`)},{credentials:'include'}); output.ownerApiBlocked=response.status===401||response.status===403||response.status===419; } catch { output.ownerApiBlocked=true; }
              try { void parent.frames[${index ? 0 : 1}].document.body; output.siblingBlocked=false; } catch { output.siblingBlocked=true; }
              document.getElementById('boundary').textContent=JSON.stringify(output);
              parent.postMessage({type:'boundary-proof',index:${index},output},${JSON.stringify(location.origin)});
            })();
          </script>`
        await pod.fs.writeFile('/server.mjs', `import http from 'node:http'; http.createServer((req,res)=>{res.writeHead(200,{'Content-Type':'text/html'});res.end(${JSON.stringify(html)})}).listen(5173,'0.0.0.0');`)
        await pod.spawn('node', ['/server.mjs'])
        const server = await Promise.race([serverSignals[index], new Promise((_, reject) => setTimeout(() => reject(new Error('Guest server did not listen.')), 30_000))])
        check(server.port === 5173 && server.url.startsWith(pod.origin + '/'), `runtime ${index + 1} preview origin`)
        const frame = document.createElement('iframe')
        frame.title = `Guest boundary proof ${index + 1}`
        frame.sandbox = 'allow-scripts allow-same-origin'
        frame.allow = 'cross-origin-isolated'
        frame.src = server.url + '/'
        document.getElementById('previews').appendChild(frame)
        const proof = await new Promise((resolve, reject) => {
            const timer = setTimeout(() => { window.removeEventListener('message', receive); reject(new Error('Guest proof timed out.')) }, 30_000)
            function receive(event) {
                if (event.source !== frame.contentWindow || event.origin !== pod.origin || event.data?.type !== 'boundary-proof' || event.data.index !== index) return
                clearTimeout(timer)
                window.removeEventListener('message', receive)
                resolve(event.data.output)
            }
            window.addEventListener('message', receive)
        })
        for (const [name, value] of Object.entries(proof)) check(value === true, `guest ${index + 1} ${name}`)
    }
    check(pods[0].origin !== pods[1].origin, 'sibling runtime origins differ')
    check(await pods[0].fs.readFile('/proof.txt', 'utf8') === 'fictional-project-0', 'sibling filesystem remains independent')
    note(`RESULT ${checks.filter(item => item.pass).length}/${checks.length}`)
    document.body.dataset.finished = 'true'
    const button = document.createElement('button')
    button.textContent = 'Run actual production build'
    button.addEventListener('click', async () => {
        button.disabled = true
        try {
            for (const pod of pods) await pod.teardown()
            const files = {
                'package.json': JSON.stringify({ name: 'fictional-runtime-build', private: true, type: 'module', scripts: { build: 'vite build' }, dependencies: { react: '19.1.1', 'react-dom': '19.1.1' }, devDependencies: { vite: '^7.0.0' } }),
                'index.html': '<!doctype html><html><head><title>Fictional runtime build</title></head><body><div id="root"></div><script type="module" src="/src/main.jsx"></script></body></html>',
                'src/main.jsx': 'import React from "react";import{createRoot}from"react-dom/client";createRoot(document.getElementById("root")).render(<main><h1>Built in isolated guest runtime</h1></main>);',
                'vite.config.js': 'export default {build:{target:"es2022"}};',
            }
            const artifact = await buildLabPublishArtifact({ getFiles: () => files, onLog: value => note(`BUILD ${value}`) })
            check(artifact instanceof Blob && artifact.size > 100, 'actual isolated production build awaited and packaged')
            const link = document.createElement('a')
            link.href = URL.createObjectURL(artifact)
            link.download = 'fictional-isolated-site.zip'
            link.textContent = 'Download actual built artifact'
            document.body.appendChild(link)
            note(`BUILD RESULT ${artifact.size} bytes`)
        } catch (error) { note(`BUILD ERROR ${error.message}`) }
        finally { await destroyLabSession() }
    })
    document.body.appendChild(button)
}
run().catch((error) => { note(`ERROR ${error.message}`); document.body.dataset.finished = 'error' })
window.addEventListener('pagehide', () => { for (const pod of pods) void pod.teardown() }, { once: true })
