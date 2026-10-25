// CommonJS: Postman local mock handlers run outside the application's ESM graph.
const http = require('node:http');
http.createServer(async (req, res) => {
  const send = (status, body) => { res.writeHead(status, { 'Content-Type': 'application/json' }); res.end(JSON.stringify(body)); };
  try {
  // Parse JSON ourselves so the simulator never decodes PNG bytes as text.
  const chunks=[];for await(const chunk of req)chunks.push(chunk);
  const bytes=Buffer.concat(chunks);
  req.body=req.headers['content-type']?.startsWith('image/')?bytes:(bytes.length?JSON.parse(bytes.toString('utf8')):{});
  const url = new URL(req.url, 'http://localhost');
  // Local-only PostgreSQL-backed fixture. No requests reach Supabase.
  if (url.pathname.startsWith('/pglite/') || url.pathname === '/v1/projects/pglitefixture/database/query') {
    const path = url.pathname.replace(/^\/pglite/, '') + url.search;
    let payload;
    if(req.headers['content-type']?.startsWith('image/')){
      if(Buffer.isBuffer(req.body))payload=req.body;
      else if(typeof req.body==='string')payload=Buffer.from(req.body,'binary');
      else {const chunks=[];for await(const chunk of req)chunks.push(chunk);payload=Buffer.concat(chunks)}
    }else payload=['POST','DELETE'].includes(req.method)?JSON.stringify(req.body):null;
    const upstream = http.request({ hostname: '127.0.0.1', port: 4910, path, method: req.method, headers: { 'Content-Type': req.headers['content-type']||'application/json', ...(payload==null?{}:{'Content-Length':Buffer.byteLength(payload)}), apikey:req.headers.apikey||'',authorization:req.headers.authorization||'','x-upsert':req.headers['x-upsert']||'' } }, response => {
      res.writeHead(response.statusCode, { 'Content-Type': response.headers['content-type']||'application/json' }); response.pipe(res);
    });
    upstream.on('error', () => send(503, { message: 'Local fictional PostgreSQL adapter is unavailable' }));
    upstream.end(payload);
    return;
  }
  const match = url.pathname.match(/^\/v1\/projects\/(fixturemodules|deniedfixture|invalidmarker)\/database\/query$/);
  if (req.method === 'POST' && match) {
    if (req.headers.authorization !== 'Bearer fictional-management') return send(401, { message: 'Unauthorized' });
    if (match[1] === 'deniedfixture') return send(403, { message: 'Fictional upstream body must not escape into owner error' });
    if (typeof req.body?.query !== 'string' || req.body?.read_only !== false) return send(400, { message: 'Invalid query contract' });
    if (match[1] === 'invalidmarker') return send(201, []);
    const schemas = {
      "'krikkit-managed-contact-v1'": 'contact',
      "'krikkit-managed-news-v1'": 'news',
      "'krikkit-managed-catalog-v1'": 'catalog',
      "'krikkit-managed-orders-v1'": 'orders',
      "'krikkit-managed-manual-payments-v1'": 'manual_payments',
    };
    for (const [marker, key] of Object.entries(schemas)) {
      if (req.body.query.includes(marker) && req.body.query.includes('COMMIT;')) {
        return send(201, [{ krikkit_schema_key: key, schema_version: 1, verified: true }]);
      }
    }
    return send(400, { message: 'Unknown fixed schema' });
  }
  const registerMatch = url.pathname.match(/^\/rest\/v1\/rpc\/krikkit_register_(contact|news|catalog|orders|manual_payments)$/);
  if (req.method === 'POST' && registerMatch) {
    if (req.headers.apikey !== 'fictional-private' || req.headers.authorization !== 'Bearer fictional-private') return send(401, { message: 'Unauthorized' });
    if (!/^[a-f0-9-]{36}$/.test(req.body?.p_project_uuid || '') || !/^[a-f0-9-]{36}$/.test(req.body?.p_module_uuid || '')) return send(400, { message: 'Invalid binding' });
    return send(200, [{ project_uuid: req.body.p_project_uuid, module_uuid: req.body.p_module_uuid, schema_version: 1 }]);
  }
  send(501, { message: 'Endpoint not modeled by the managed module contract' });
  } catch { send(500,{message:'Local dependency fixture failed'}); }
}).listen(process.env.PORT);
