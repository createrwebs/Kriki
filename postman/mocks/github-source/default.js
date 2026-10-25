const http = require('node:http');
const crypto = require('node:crypto');
const commit = '1'.repeat(40);
const nextCommit = '2'.repeat(40);
const source = {
  'app/package.json': Buffer.from('{"name":"fictional-import","dependencies":{"react":"19.1.1","vite":"8.2.0"}}'),
  'app/src/App.jsx': Buffer.from('export default function App(){return <main>Fictional source only</main>}'),
  'app/package-lock.json': Buffer.from('{"name":"fictional-import","lockfileVersion":3,"packages":{}}'),
  'app/LICENSE': Buffer.from('FICTIONAL TEST LICENSE - not a real redistribution grant'),
  'app/public/icon.bin': Buffer.from([137,80,78,71,0,255,13,10]),
};
const sha = bytes => crypto.createHash('sha1').update(Buffer.from('blob '+bytes.length+'\0')).update(bytes).digest('hex');
const blobs = Object.fromEntries(Object.values(source).map(bytes => [sha(bytes), bytes]));
let journal = [];
http.createServer((req, res) => {
  const url = new URL(req.url, 'http://fixture.invalid');
  const send = (status, data) => { res.writeHead(status, {'Content-Type':'application/json'}); res.end(JSON.stringify(data)); };
  if (url.pathname === '/__qa/reset' && req.method === 'POST') { journal = []; return send(200, {ok:true}); }
  if (url.pathname === '/__qa/journal' && req.method === 'GET') return send(200, journal);
  journal.push({method:req.method,path:url.pathname,ref:url.searchParams.get('ref')});
  const match = url.pathname.match(/^\/repos\/fictional\/([a-z-]+)(?:\/(.*))?$/);
  if (!match || req.method !== 'GET') return send(501, {message:'Unmodeled fictional GitHub request'});
  const scenario = match[1], path = match[2] || '';
  if (path === '') return send(200, {default_branch:'main'});
  if (path.startsWith('commits/')) {
    const ref = decodeURIComponent(path.slice(8));
    const previous = journal.filter(row => row.path === url.pathname).length;
    return send(200, {sha: ref === 'main' && previous > 1 ? nextCommit : ref === 'main' ? commit : ref});
  }
  if (path.startsWith('contents/')) {
    const file = path.slice(9);
    if (url.searchParams.get('ref') !== commit) return send(404, {message:'Branch advanced; only pinned source is modeled'});
    const bytes = source[file];
    return bytes ? send(200, {type:'file',encoding:'base64',content:bytes.toString('base64'),sha:sha(bytes),size:bytes.length}) : send(404, {message:'Not Found'});
  }
  if (path.startsWith('git/trees/')) {
    let tree = Object.entries(source).map(([path,bytes]) => ({path,type:'blob',mode:'100644',sha:sha(bytes),size:bytes.length}));
    const app = tree.find(row => row.path === 'app/src/App.jsx');
    if (scenario === 'oversized') app.size = 2097153;
    if (scenario === 'invalid-size') app.size = '10';
    if (scenario === 'symlink') app.mode = '120000';
    if (scenario === 'submodule') tree.push({path:'app/dependency',type:'commit',mode:'160000',sha:commit});
    if (scenario === 'collision') tree.push({...app,path:'app/src/app.jsx'});
    if (scenario === 'bad-path') tree.push({...app,path:'app/../escape.txt'});
    if (scenario === 'over-count') tree = Array.from({length:2501},(_,i)=>({...app,path:'app/file-'+i+'.txt'}));
    if (scenario === 'over-total') tree = Array.from({length:51},(_,i)=>({...app,path:'app/file-'+i+'.txt',size:2097152}));
    if (scenario === 'malformed') return send(200, {tree:'invalid',truncated:false});
    return send(200, {sha:commit,tree,truncated:scenario === 'truncated'});
  }
  if (path.startsWith('git/blobs/')) {
    const requested = path.slice(10), original = blobs[requested];
    if (!original) return send(404, {message:'Not Found'});
    const isApp = requested === sha(source['app/src/App.jsx']);
    if (isApp && scenario === 'failed-blob') return send(503, {message:'FICTIONAL UPSTREAM DETAILS MUST NOT LEAK'});
    const bytes = isApp && scenario === 'actual-oversized' ? Buffer.alloc(2097153,65) : isApp && scenario === 'wrong-hash' ? Buffer.from('corrupted source') : original;
    return send(200, {sha:requested,size:bytes.length,encoding:'base64',content:isApp && scenario === 'bad-base64' ? '%%%' : bytes.toString('base64')});
  }
  return send(501, {message:'Unmodeled fictional GitHub request'});
}).listen(process.env.PORT || 4905, '127.0.0.1');
