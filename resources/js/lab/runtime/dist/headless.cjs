Object.defineProperty(exports,Symbol.toStringTag,{value:"Module"});const j=require("./rolldown-runtime-DGqZhkpL.cjs"),W=require("./pako.esm-BPAXIKFy.cjs"),d=require("./host-SwhBQQUz.cjs"),E=require("./lab-keys-BeJxXanB.cjs"),O=require("./offload-Fc-99Ir2.cjs");let L=require("node:fs"),I=require("node:path"),x=require("node:url"),q=require("node:worker_threads"),A=require("node:http");A=j.__toESM(A,1);let k=require("node:crypto"),g=require("node:fs/promises"),C=require("node:os");function D(){return`"use strict";
const { parentPort, workerData } = require("node:worker_threads");
if (!parentPort) throw new Error("deepthought worker shim: missing parentPort");

const messageListeners = new Set();
const errorListeners = new Set();

function emitMessage(data) {
  const ev = { data };
  for (const fn of messageListeners) {
    try { fn(ev); } catch (err) { console.error(err); }
  }
  if (typeof globalThis.onmessage === "function") {
    try { globalThis.onmessage(ev); } catch (err) { console.error(err); }
  }
}

globalThis.self = globalThis;
globalThis.postMessage = function postMessage(data, transfer) {
  if (Array.isArray(transfer) && transfer.length) {
    parentPort.postMessage(data, transfer);
  } else {
    parentPort.postMessage(data);
  }
};
globalThis.addEventListener = function addEventListener(type, fn) {
  if (type === "message") messageListeners.add(fn);
  else if (type === "error") errorListeners.add(fn);
};
globalThis.removeEventListener = function removeEventListener(type, fn) {
  if (type === "message") messageListeners.delete(fn);
  else if (type === "error") errorListeners.delete(fn);
};
globalThis.onmessage = null;
globalThis.onerror = null;

parentPort.on("message", function (data) { emitMessage(data); });
parentPort.on("error", function (err) {
  const ev = { message: err && err.message ? err.message : String(err), error: err };
  for (const fn of errorListeners) {
    try { fn(ev); } catch (_) { /* ignore */ }
  }
  if (typeof globalThis.onerror === "function") {
    try { globalThis.onerror(ev); } catch (_) { /* ignore */ }
  }
});

const source = workerData && workerData.__deepthoughtSource;
if (typeof source !== "string" || !source) {
  throw new Error("deepthought worker shim: missing __deepthoughtSource");
}
(0, eval)(source);
`}function T(t){const o=new Set,s=new Set;let a=null,n=null;return t.on("message",e=>{const r={data:e};for(const v of o)try{v(r)}catch{}if(a)try{a(r)}catch{}}),t.on("error",e=>{const r={message:e.message,error:e};for(const v of s)try{v(r)}catch{}if(n)try{n(r)}catch{}}),{postMessage(e,r){r&&r.length?t.postMessage(e,r):t.postMessage(e)},terminate(){t.terminate()},addEventListener(e,r){e==="message"?o.add(r):e==="error"&&s.add(r)},removeEventListener(e,r){e==="message"?o.delete(r):e==="error"&&s.delete(r)},get onmessage(){return a},set onmessage(e){a=e},get onerror(){return n},set onerror(e){n=e}}}function P(t,o){return T(new q.Worker(D(),{eval:!0,workerData:{__deepthoughtSource:t},...o?{name:o}:{}}))}function Z(t){const o=t.host??"127.0.0.1",s=t.port??0;let a=null,n=null;async function e(c){const u=[];for await(const b of c)u.push(Buffer.isBuffer(b)?b:Buffer.from(b));return Buffer.concat(u)}function r(c){const u={};for(const[b,i]of Object.entries(c))i!=null&&(u[b]=Array.isArray(i)?i.join(", "):i);return u}async function v(c,u){try{const b=new URL(c.url||"/",`http://${o}`),i=b.pathname.split("/").filter(Boolean);if(i[0]!=="__virtual__"||i.length<3){u.statusCode=404,u.setHeader("Content-Type","text/plain"),u.end("Not Found");return}const F=i[1],m=Number(i[2]);if(!Number.isFinite(m)){u.statusCode=400,u.setHeader("Content-Type","text/plain"),u.end("Invalid port");return}const V=await e(c),N=V.byteLength>0?V.buffer.slice(V.byteOffset,V.byteOffset+V.byteLength):void 0,f=i.slice(3),Y=(f.length?`/${f.join("/")}`:"/")+b.search,w=await t.proxy.handleRequest(F,m,(c.method||"GET").toUpperCase(),Y,r(c.headers),N);u.statusCode=w.statusCode;for(const[R,h]of Object.entries(w.headers))h!=null&&(Array.isArray(h),u.setHeader(R,h));const y=w.body?Buffer.isBuffer(w.body)?w.body:Buffer.from(w.body):Buffer.alloc(0);u.end(y)}catch(b){u.statusCode=500,u.setHeader("Content-Type","text/plain"),u.end(b instanceof Error?b.message:String(b))}}return{kind:"local-http",get baseUrl(){return n},async start(){if(a)return;a=A.default.createServer((u,b)=>{v(u,b)}),await new Promise((u,b)=>{a.once("error",b),a.listen(s,o,()=>{a.off("error",b),u()})});const c=a.address();c&&typeof c=="object"?n=`http://${o}:${c.port}`:n=`http://${o}`,t.proxy.setBaseUrl(n)},async stop(){const c=a;a=null,n=null,c&&await new Promise(u=>{c.close(()=>u())})}}}var X=2,z=6048e5;function G(){return process.env.DEEPTHOUGHT_CACHE||(0,I.join)((0,C.tmpdir)(),"deepthought-snapshots")}function l(t,o){const s=(0,k.createHash)("sha256").update(o).digest("hex");return(0,I.join)(t,`${s}.bin`)}async function S(t=G()){try{await(0,g.mkdir)(t,{recursive:!0})}catch{return null}return(async()=>{try{const o=await(0,g.readdir)(t),s=Date.now();for(const a of o)if(a.endsWith(".meta.json"))try{const n=await(0,g.readFile)((0,I.join)(t,a),"utf8"),e=JSON.parse(n);if(e.schema!==X||e.createdAt!=null&&s-e.createdAt>z){const r=a.slice(0,-10);await(0,g.unlink)((0,I.join)(t,a)).catch(()=>{}),await(0,g.unlink)((0,I.join)(t,`${r}.bin`)).catch(()=>{})}}catch{}}catch{}})(),{async get(o){try{const s=l(t,o),a=s.replace(/\.bin$/,".meta.json"),n=await(0,g.readFile)(a,"utf8"),e=JSON.parse(n);if(e.schema!==X||e.createdAt!=null&&Date.now()-e.createdAt>z)return null;const r=await(0,g.readFile)(s);if(r.byteLength<4)return null;const v=r.readUInt32LE(0),c=r.subarray(4,4+v),u=r.buffer.slice(r.byteOffset+4+v,r.byteOffset+r.byteLength);return{manifest:JSON.parse(c.toString("utf8")),data:u}}catch{return null}},async set(o,s){const a=l(t,o),n=a.replace(/\.bin$/,".meta.json"),e=Buffer.from(JSON.stringify(s.manifest),"utf8"),r=Buffer.from(s.data),v=Buffer.alloc(4);v.writeUInt32LE(e.byteLength,0),await(0,g.writeFile)(a,Buffer.concat([v,e,r])),await(0,g.writeFile)(n,JSON.stringify({schema:X,createdAt:Date.now()}))},close(){}}}function p(){try{const t=(0,I.dirname)((0,x.fileURLToPath)({}.url)),o=[(0,I.join)(t,"..","..","__worker__.js"),(0,I.join)(t,"..","..","..","dist","__worker__.js"),(0,I.join)(process.cwd(),"dist","__worker__.js")];for(const s of o)if((0,L.existsSync)(s))return s}catch{}return null}function H(){const t=Buffer.from(d.PROCESS_WORKER_BUNDLE_GZIP_BASE64,"base64");return W.ungzip_1(t,{to:"string"})}function M(t,o){return!o&&t&&(0,L.existsSync)(t)||t&&(0,L.existsSync)(t)?(0,L.readFileSync)(t,"utf8"):H()}function J(t={}){let o=t.workerPath??p(),s=null;function a(n){if(!n&&s)return s;const e=M(o,!!n);return n||(s=e),e}return{kind:"node",defaultHeadless:!0,canCreateWorkers(){return!0},createWorker(n){if(n.type==="url"){let v=n.url;if(v.startsWith("file:")&&(v=(0,x.fileURLToPath)(v)),!(0,L.existsSync)(v))throw new Error(`[DeepThoughtEngine] Node host cannot load worker URL: ${n.url}`);const c=(0,L.readFileSync)(v,"utf8"),u=P(c,n.name);return E.markHostWorkerDirect(u,!0),u}if(n.type==="source")return P(n.source,n.name);const e=a(n.embedded),r=P(e,n.name);return o&&!n.embedded&&E.markHostWorkerDirect(r,!0),r},async probeProcessWorkerUrl(n){if(n){let r=n;return r.startsWith("file:")&&(r=(0,x.fileURLToPath)(r)),(0,L.existsSync)(r)?(o=r,s=null,r):null}const e=p();return e?(o=e,s=null,e):null},disposeGlobalResources(){s=null},async openSnapshotCache(n){return n.enableSnapshotCache===!1||n.packageStore==="memory"?null:S(t.cacheDir)},createHttpIngress({proxy:n,headless:e}){return e?Z({proxy:n,host:t.httpHost,port:t.httpPort}):{kind:"programmatic",baseUrl:null}}}}O.setRuntimeHost(J());exports.DeepThoughtEngine=d.LabRuntime;exports.DeepThoughtFS=d.LabFS;exports.DeepThoughtFSClient=d.LabFSClient;exports.DeepThoughtFSClientError=d.LabFSClientError;exports.DeepThoughtProcess=d.LabProcess;exports.DeepThoughtSWSetupError=d.DeepThoughtSWSetupError;exports.DependencyInstaller=d.DependencyInstaller;exports.LabFS=d.LabFS;exports.LabFSClient=d.LabFSClient;exports.LabFSClientError=d.LabFSClientError;exports.LabProcess=d.LabProcess;exports.LabRuntime=d.LabRuntime;exports.MemoryVolume=d.MemoryVolume;exports.RequestProxy=d.RequestProxy;exports.createLocalHttpIngress=Z;exports.createNodeHost=J;exports.discoverWorkspaces=d.discoverWorkspaces;exports.getProxyInstance=d.getProxyInstance;exports.getRuntimeHost=O.getRuntimeHost;exports.install=d.install;exports.openFsSnapshotCache=S;exports.readWorkspacePatterns=d.readWorkspacePatterns;exports.resetProxy=d.resetProxy;exports.resetRuntimeHost=O.resetRuntimeHost;exports.setRuntimeHost=O.setRuntimeHost;

//# sourceMappingURL=headless.cjs.map