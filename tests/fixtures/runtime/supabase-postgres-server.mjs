// Local test database adapter only. PGlite runs real PostgreSQL in memory;
// the PostgREST-shaped HTTP subset is a contract stand-in, never production.
import http from 'node:http'
import { PGlite } from '../../../.agent-work/test-deps/node_modules/@electric-sql/pglite/dist/index.js'
import { storageCatalog } from './private-storage-schema.mjs'
import { randomBytes, createHash } from 'node:crypto'
const db = await PGlite.create()
await db.exec('CREATE ROLE anon;CREATE ROLE authenticated;CREATE ROLE service_role BYPASSRLS;')
await db.exec(storageCatalog)
const objects=new Map(), signedTokens=new Map(), faults=new Map()
const consumeFault=(module,operation)=>{const key=module+':'+operation;if(!faults.has(key))return false;faults.delete(key);return true}
const columns = new Set(['uuid','customer_name','email','phone','subject','message','status','version','created_at','request_hash'])
const newsColumns = new Set(['uuid','slug','title','excerpt','body','status','version','published_at','created_at','updated_at','request_hash'])
const catalogColumns = new Set(['uuid','sku','title','description','price_satang','active','version','created_at','updated_at','request_hash'])
const orderColumns = new Set(['uuid','reference','customer_name','customer_email','customer_phone','note','items','total_satang','currency','promptpay_snapshot','access_token_hash','access_revoked_at','status','version','created_at','updated_at','request_hash'])
const quoteColumns=new Set(['uuid','items','total_satang','settings','expires_at','catalog_version'])
const paymentSettingsColumns = new Set(['module_uuid','project_uuid','recipient_type','recipient','payee_label','instructions','enabled','version','updated_at'])
const paymentAttemptColumns = new Set(['uuid','order_uuid','payer_name','payer_phone','amount_satang','transfer_ref','claimed_transferred_at','slip_mime','slip_sha256','slip_data_url','slip_bucket','slip_object','slip_bytes','slip_width','slip_height','status','reviewer_id','review_reason','verified_at','version','uploaded_at','updated_at','request_hash'])
const uploadColumns=new Set(['uuid','bucket_id','object_path','state','attempt_uuid','lease_expires_at'])

const server = http.createServer(async (req, res) => {
    const send = (status, value) => { res.writeHead(status, { 'Content-Type': 'application/json' }); res.end(JSON.stringify(value)) }
    try {
        const url = new URL(req.url, 'http://localhost')
        const chunks=[]; for await(const chunk of req) chunks.push(chunk)
        const raw=Buffer.concat(chunks), text=raw.toString('utf8')
        const body = raw.length && !req.headers['content-type']?.startsWith('image/') ? JSON.parse(text) : {}
        if(req.method==='POST'&&url.pathname==='/__fixture/fault'){faults.set(body.module_uuid+':'+body.operation,true);return send(200,{ok:true})}
        if(req.method==='POST'&&url.pathname==='/__fixture/expire-uploads'){await db.query("UPDATE krikkit_slip_uploads SET lease_expires_at=now()-interval '1 day' WHERE module_uuid=$1 AND state IN('staged','cleanup_pending')",[body.module_uuid]);return send(200,{ok:true})}
        if(req.method==='POST'&&url.pathname==='/__fixture/bucket-privacy'){await db.query('UPDATE storage.buckets SET public=$1 WHERE id=$2',[body.public,'krikkit-slips-'+body.module_uuid]);return send(200,{ok:true})}
        if(req.method==='POST'&&url.pathname==='/__fixture/legacy-attempt'){
            const attempt=(await db.query('SELECT * FROM krikkit_payment_attempts WHERE module_uuid=$1 AND uuid=$2',[body.module_uuid,body.attempt_uuid])).rows[0]
            const bytes=attempt&&objects.get(attempt.slip_bucket+'/'+attempt.slip_object)
            if(!bytes)return send(404,{message:'Fictional stored proof required'})
            await db.query('UPDATE krikkit_payment_attempts SET slip_data_url=$1,slip_bucket=NULL,slip_object=NULL WHERE module_uuid=$2 AND uuid=$3',['data:image/png;base64,'+bytes.toString('base64'),body.module_uuid,body.attempt_uuid])
            return send(200,{sha256:attempt.slip_sha256,status:attempt.status,version:attempt.version})
        }
        if(req.method==='GET'&&url.pathname==='/__fixture/state'){
            const module=url.searchParams.get('module'), bucket='krikkit-slips-'+module
            return send(200,{objects:[...objects.entries()].filter(([key])=>key.startsWith(bucket+'/')).map(([key,value])=>({key,bytes:value.length,sha256:createHash('sha256').update(value).digest('hex')})),uploads:(await db.query('SELECT state,count(*)::integer AS count FROM krikkit_slip_uploads WHERE module_uuid=$1 GROUP BY state',[module])).rows})
        }
        if(url.pathname.startsWith('/storage/v1/')){
            const route=url.pathname.slice('/storage/v1'.length), token=signedTokens.get(url.searchParams.get('token'))
            if(req.method==='GET'&&route.startsWith('/object/sign/')){
                const key=route.slice('/object/sign/'.length)
                if(!token||token.key!==key||token.expires<Date.now()||!objects.has(key))return send(403,{message:'Private object'})
                res.writeHead(200,{'Content-Type':'image/png','Cache-Control':'private,no-store'});res.end(objects.get(key));return
            }
            if(req.headers.apikey!=='fictional-private'||req.headers.authorization!=='Bearer fictional-private')return send(403,{message:'Private object'})
            if(req.method==='GET'&&route.startsWith('/bucket/')){const rows=(await db.query('SELECT * FROM storage.buckets WHERE id=$1',[route.slice(8)])).rows;return send(rows.length?200:404,rows[0]||{})}
            if(req.method==='POST'&&route.startsWith('/object/sign/')){
                const key=route.slice('/object/sign/'.length),module=key.split('/')[0].slice('krikkit-slips-'.length)
                if(body.expiresIn!==300||!objects.has(key))return send(400,{message:'Invalid sign contract'})
                const token=randomBytes(24).toString('hex');signedTokens.set(token,{key,expires:Date.now()+300_000})
                return send(200,{signedURL:consumeFault(module,'sign_url')?'https://evil.invalid/leak':`/object/sign/${key}?token=${token}`})
            }
            if(req.method==='POST'&&route.startsWith('/object/')){
                const key=route.slice(8),module=key.split('/')[0].slice('krikkit-slips-'.length)
                if(consumeFault(module,'upload'))return send(503,{message:'Fictional storage failure'})
                if(req.headers['content-type']!=='image/png'||req.headers['x-upsert']!=='false'||objects.has(key)||!raw.subarray(0,8).equals(Buffer.from([137,80,78,71,13,10,26,10])))return send(400,{message:'Invalid upload contract'})
                objects.set(key,raw);await db.query('INSERT INTO storage.objects(bucket_id,name) VALUES($1,$2)',[key.split('/')[0],key.split('/').slice(1).join('/')])
                if(consumeFault(module,'upload_after'))return send(503,{message:'Lost upload reply'})
                return send(200,{Key:key})
            }
            if(req.method==='DELETE'&&route.startsWith('/object/')){
                const bucket=route.slice(8),module=bucket.slice('krikkit-slips-'.length)
                if(consumeFault(module,'delete'))return send(503,{message:'Fictional cleanup failure'})
                if(!Array.isArray(body.prefixes)||body.prefixes.length!==1)return send(400,{message:'Exact path required'})
                const key=bucket+'/'+body.prefixes[0];objects.delete(key);await db.query('DELETE FROM storage.objects WHERE bucket_id=$1 AND name=$2',[bucket,body.prefixes[0]])
                return send(200,[])
            }
            return send(501,{message:'Storage endpoint not modeled'})
        }
        if (req.method === 'POST' && url.pathname === '/v1/projects/pglitefixture/database/query') {
            const validMarkers = [
                'krikkit-managed-contact-v1',
                'krikkit-managed-news-v1',
                'krikkit-managed-catalog-v1',
                'krikkit-managed-orders-v1',
                'krikkit-managed-manual-payments-v1',
            ]
            if (body.read_only !== false || typeof body.query !== 'string' || !validMarkers.some(m => body.query.includes(m))) return send(400, { message: 'Unsupported fixture schema' })
            const results = await db.exec(body.query)
            return send(201, results.at(-1).rows)
        }
        const rpc = {
            '/rest/v1/rpc/krikkit_register_contact': ['SELECT * FROM public.krikkit_register_contact($1,$2)', ['p_project_uuid','p_module_uuid']],
            '/rest/v1/rpc/krikkit_submit_contact': ['SELECT * FROM public.krikkit_submit_contact($1,$2,$3,$4,$5)', ['p_project_uuid','p_module_uuid','p_key','p_hash','p_values']],
            '/rest/v1/rpc/krikkit_transition_contact': ['SELECT * FROM public.krikkit_transition_contact($1,$2,$3,$4,$5,$6)', ['p_project_uuid','p_module_uuid','p_message_uuid','p_expected_version','p_status','p_actor_id']],
            '/rest/v1/rpc/krikkit_register_news': ['SELECT * FROM public.krikkit_register_news($1,$2)', ['p_project_uuid','p_module_uuid']],
            '/rest/v1/rpc/krikkit_create_news': ['SELECT * FROM public.krikkit_create_news($1,$2,$3,$4,$5,$6)', ['p_project_uuid','p_module_uuid','p_key','p_hash','p_values','p_actor_id']],
            '/rest/v1/rpc/krikkit_update_news': ['SELECT * FROM public.krikkit_update_news($1,$2,$3,$4,$5,$6)', ['p_project_uuid','p_module_uuid','p_article_uuid','p_expected_version','p_values','p_actor_id']],
            '/rest/v1/rpc/krikkit_register_catalog': ['SELECT * FROM public.krikkit_register_catalog($1,$2)', ['p_project_uuid','p_module_uuid']],
            '/rest/v1/rpc/krikkit_create_product': ['SELECT * FROM public.krikkit_create_product($1,$2,$3,$4,$5,$6)', ['p_project_uuid','p_module_uuid','p_key','p_hash','p_values','p_actor_id']],
            '/rest/v1/rpc/krikkit_update_product': ['SELECT * FROM public.krikkit_update_product($1,$2,$3,$4,$5,$6)', ['p_project_uuid','p_module_uuid','p_product_uuid','p_expected_version','p_values','p_actor_id']],
            '/rest/v1/rpc/krikkit_register_orders': ['SELECT * FROM public.krikkit_register_orders($1,$2)', ['p_project_uuid','p_module_uuid']],
            '/rest/v1/rpc/krikkit_create_order': ['SELECT * FROM public.krikkit_create_order($1,$2,$3,$4,$5)', ['p_project_uuid','p_module_uuid','p_key','p_hash','p_values']],
            '/rest/v1/rpc/krikkit_quote_order':['SELECT * FROM krikkit_quote_order($1,$2,$3,$4,$5)',['p_project_uuid','p_module_uuid','p_catalog','p_payments','p_items']],
            '/rest/v1/rpc/krikkit_create_quoted_order':['SELECT * FROM krikkit_create_quoted_order($1,$2,$3,$4,$5,$6,$7)',['p_project_uuid','p_module_uuid','p_quote','p_key','p_hash','p_cart','p_values']],
            '/rest/v1/rpc/krikkit_revoke_order_access':['SELECT * FROM krikkit_revoke_order_access($1,$2,$3,$4)',['p_project_uuid','p_module_uuid','p_order','p_actor']],
            '/rest/v1/rpc/krikkit_register_manual_payments': ['SELECT * FROM public.krikkit_register_manual_payments($1,$2)', ['p_project_uuid','p_module_uuid']],
            '/rest/v1/rpc/krikkit_save_payment_settings': ['SELECT * FROM public.krikkit_save_payment_settings($1,$2,$3,$4,$5)', ['p_project_uuid','p_module_uuid','p_expected_version','p_values','p_actor_id']],
            '/rest/v1/rpc/krikkit_submit_payment_slip': ['SELECT * FROM public.krikkit_submit_payment_slip($1,$2,$3,$4,$5,$6)', ['p_project_uuid','p_module_uuid','p_order_uuid','p_key','p_hash','p_values']],
            '/rest/v1/rpc/krikkit_review_payment_slip': ['SELECT * FROM public.krikkit_review_payment_slip($1,$2,$3,$4,$5,$6,$7)', ['p_project_uuid','p_module_uuid','p_attempt_uuid','p_expected_version','p_decision','p_reason','p_actor_id']],
            '/rest/v1/rpc/krikkit_begin_slip_upload': ['SELECT * FROM krikkit_begin_slip_upload($1,$2,$3,$4,$5,$6,$7)',['p_project_uuid','p_module_uuid','p_order_uuid','p_key','p_hash','p_lease','p_image']],
            '/rest/v1/rpc/krikkit_commit_slip_upload': ['SELECT * FROM krikkit_commit_slip_upload($1,$2,$3,$4,$5)',['p_project_uuid','p_module_uuid','p_upload','p_lease','p_values']],
            '/rest/v1/rpc/krikkit_claim_slip_cleanup': ['SELECT * FROM krikkit_claim_slip_cleanup($1,$2,$3,$4,$5)',['p_project_uuid','p_module_uuid','p_upload','p_lease','p_cleanup']],
            '/rest/v1/rpc/krikkit_finish_slip_cleanup': ['SELECT * FROM krikkit_finish_slip_cleanup($1,$2,$3,$4)',['p_project_uuid','p_module_uuid','p_upload','p_lease']],
        }[url.pathname]
        if (req.method === 'POST' && rpc) {
            const committing=url.pathname.endsWith('/krikkit_commit_slip_upload')
            if(committing&&consumeFault(body.p_module_uuid,'commit_before'))return send(503,{message:'Fictional commit unavailable'})
            const result=await db.query(rpc[0],rpc[1].map(key=>body[key]))
            if(committing&&consumeFault(body.p_module_uuid,'commit_after'))return send(503,{message:'Fictional commit response lost'})
            return send(200,result.rows)
        }

        const queryTable = async (table, allowedColumns, filterNames, defaultOrder) => {
            const select = (url.searchParams.get('select') || '').split(',')
            if (select.some(column => !allowedColumns.has(column))) return send(400, { message: 'Unsupported select' })
            const values = []; const predicates = []
            for (const name of filterNames) {
                const filter = url.searchParams.get(name)
                if (!filter && ['project_uuid','module_uuid'].includes(name)) return send(400, { message: 'Scope required' })
                if(filter==='in.(staged,cleanup_pending,abandoned)'&&name==='state'){predicates.push("state IN('staged','cleanup_pending','abandoned')");continue}
                if(filter?.startsWith('lt.')&&name==='lease_expires_at'){values.push(filter.slice(3));predicates.push(`${name}<$${values.length}`);continue}
                if (filter) { if (!filter.startsWith('eq.')) return send(400, { message: 'Unsupported filter' }); values.push(filter.slice(3)); predicates.push(`${name}=$${values.length}`) }
            }
            const cursor = url.searchParams.get('or')
            if(cursor){
                const match=cursor.match(/^\((created_at|uploaded_at)\.lt\.([^,()]+),and\(\1\.eq\.\2,uuid\.lt\.([a-f0-9-]{36})\)\)$/)
                if(!match||!allowedColumns.has(match[1]))return send(400,{message:'Unsupported cursor'})
                values.push(match[2],match[3]);predicates.push(`(${match[1]},uuid)<($${values.length-1}::timestamptz,$${values.length}::uuid)`)
            }
            const limit = Math.min(100, Math.max(1, Number(url.searchParams.get('limit') || 100)))
            const orderBy = url.searchParams.get('order') === 'published_at.desc,uuid.desc' ? 'published_at DESC,uuid DESC' : defaultOrder
            const result = await db.query(`SELECT ${select.join(',')} FROM public.${table} WHERE ${predicates.join(' AND ')} ORDER BY ${orderBy} LIMIT ${limit}`, values)
            return send(200, result.rows)
        }

        if (req.method === 'GET' && url.pathname === '/rest/v1/krikkit_contact_messages') {
            return await queryTable('krikkit_contact_messages', columns, ['project_uuid','module_uuid','request_key','status'], 'created_at DESC,uuid DESC')
        }
        if (req.method === 'GET' && url.pathname === '/rest/v1/krikkit_news_articles') {
            return await queryTable('krikkit_news_articles', newsColumns, ['project_uuid','module_uuid','request_key','status','slug'], 'created_at DESC,uuid DESC')
        }
        if (req.method === 'GET' && url.pathname === '/rest/v1/krikkit_catalog_products') {
            return await queryTable('krikkit_catalog_products', catalogColumns, ['project_uuid','module_uuid','request_key','active','sku','uuid'], 'created_at DESC,uuid DESC')
        }
        if (req.method === 'GET' && url.pathname === '/rest/v1/krikkit_commerce_orders') {
            return await queryTable('krikkit_commerce_orders', orderColumns, ['project_uuid','module_uuid','request_key','status','reference','uuid'], 'created_at DESC,uuid DESC')
        }
        if(req.method==='GET'&&url.pathname==='/rest/v1/krikkit_commerce_quotes')return await queryTable('krikkit_commerce_quotes',quoteColumns,['project_uuid','module_uuid','uuid'],'created_at DESC,uuid DESC')
        if (req.method === 'GET' && url.pathname === '/rest/v1/krikkit_payment_settings') {
            return await queryTable('krikkit_payment_settings', paymentSettingsColumns, ['project_uuid','module_uuid'], 'updated_at DESC,module_uuid DESC')
        }
        if (req.method === 'GET' && url.pathname === '/rest/v1/krikkit_payment_attempts') {
            return await queryTable('krikkit_payment_attempts', paymentAttemptColumns, ['project_uuid','module_uuid','order_uuid','request_key','status','uuid'], 'uploaded_at DESC,uuid DESC')
        }
        if(req.method==='GET'&&url.pathname==='/rest/v1/krikkit_slip_uploads')return await queryTable('krikkit_slip_uploads',uploadColumns,['project_uuid','module_uuid','state','lease_expires_at'],'lease_expires_at ASC,uuid ASC')
        send(501, { message: 'Endpoint not modeled by local PostgreSQL adapter' })
    } catch (error) {
        // PostgreSQL transaction errors must release the connection for the next test.
        try { await db.exec('ROLLBACK;') } catch { /* No active transaction. */ }
        send(400, { message: error.message })
    }
})
server.listen(4910, '127.0.0.1', () => console.log('Local fictional PostgreSQL adapter listening on 127.0.0.1:4910'))
