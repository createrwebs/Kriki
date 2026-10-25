const http = require('node:http');
let calls = 0;
http.createServer((req, res) => {
  const send = (status, data) => { res.writeHead(status, {'Content-Type':'application/json'}); res.end(JSON.stringify(data)); };
  if (req.url === '/__qa/reset' && req.method === 'POST') { calls = 0; return send(200, {ok:true}); }
  if (req.url === '/__qa/journal' && req.method === 'GET') return send(200, {calls});
  if (req.url !== '/v1/chat/completions' || req.method !== 'POST') return send(501, {error:{message:'Unmodeled fixture request'}});
  let body = '';
  req.on('data', chunk => { body += chunk; });
  req.on('end', () => {
    calls++;
    try {
      const input = JSON.parse(body);
      const user = input.messages.find(message => message.role === 'user')?.content || '';
      if (user.includes('fixture-provider-error')) return send(503, {error:{message:'Fictional unavailable provider'}});
      const blueprint = JSON.parse(user.split('CURRENT BLUEPRINT JSON:\n')[1]);
      blueprint.theme.palette = 'sand'; blueprint.title = 'Fictional provider global change'; blueprint.summary = 'Fictional provider summary';
      blueprint.pages.forEach(page => { page.title = 'Fictional provider page'; page.sections.forEach(section => { section.heading = 'Fictional provider heading'; section.body = 'Fictional provider body'; }); });
      if (user.includes('fixture-missing-target')) blueprint.pages[0].sections = blueprint.pages[0].sections.slice(1);
      const content = user.includes('fixture-invalid-json') ? 'Fictional invalid JSON' : JSON.stringify(blueprint);
      return send(200, {id:'fixture-completion',object:'chat.completion',model:input.model,choices:[{index:0,message:{role:'assistant',content},finish_reason:'stop'}],usage:{prompt_tokens:100,completion_tokens:50,total_tokens:150}});
    } catch { return send(400, {error:{message:'Invalid fictional completion request'}}); }
  });
}).listen(process.env.PORT);
