// Local test database adapter only. PGlite runs real PostgreSQL in memory;
// the PostgREST-shaped HTTP subset is a contract stand-in, never production.
import http from 'node:http'
import { PGlite } from '../../../.agent-work/test-deps/node_modules/@electric-sql/pglite/dist/index.js'
const db = await PGlite.create()
await db.exec('CREATE ROLE anon;CREATE ROLE authenticated;CREATE ROLE service_role BYPASSRLS;')
const columns = new Set(['uuid','customer_name','email','phone','subject','message','status','version','created_at','request_hash'])
const newsColumns = new Set(['uuid','slug','title','excerpt','body','status','version','published_at','created_at','updated_at','request_hash'])
const catalogColumns = new Set(['uuid','sku','title','description','price_satang','active','version','created_at','updated_at','request_hash'])
const orderColumns = new Set(['uuid','reference','customer_name','customer_email','customer_phone','note','items','total_satang','currency','promptpay_snapshot','access_token_hash','status','version','created_at','updated_at','request_hash'])
const paymentSettingsColumns = new Set(['module_uuid','project_uuid','recipient_type','recipient','payee_label','instructions','enabled','version','updated_at'])
const paymentAttemptColumns = new Set(['uuid','order_uuid','payer_name','payer_phone','amount_satang','transfer_ref','claimed_transferred_at','slip_mime','slip_sha256','slip_data_url','status','reviewer_id','review_reason','verified_at','version','uploaded_at','updated_at','request_hash'])

const server = http.createServer(async (req, res) => {
    const send = (status, value) => { res.writeHead(status, { 'Content-Type': 'application/json' }); res.end(JSON.stringify(value)) }
    try {
        const url = new URL(req.url, 'http://localhost')
        let text = ''; for await (const chunk of req) text += chunk
        const body = text ? JSON.parse(text) : {}
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
            '/rest/v1/rpc/krikkit_register_manual_payments': ['SELECT * FROM public.krikkit_register_manual_payments($1,$2)', ['p_project_uuid','p_module_uuid']],
            '/rest/v1/rpc/krikkit_save_payment_settings': ['SELECT * FROM public.krikkit_save_payment_settings($1,$2,$3,$4,$5)', ['p_project_uuid','p_module_uuid','p_expected_version','p_values','p_actor_id']],
            '/rest/v1/rpc/krikkit_submit_payment_slip': ['SELECT * FROM public.krikkit_submit_payment_slip($1,$2,$3,$4,$5,$6)', ['p_project_uuid','p_module_uuid','p_order_uuid','p_key','p_hash','p_values']],
            '/rest/v1/rpc/krikkit_review_payment_slip': ['SELECT * FROM public.krikkit_review_payment_slip($1,$2,$3,$4,$5,$6,$7)', ['p_project_uuid','p_module_uuid','p_attempt_uuid','p_expected_version','p_decision','p_reason','p_actor_id']],
        }[url.pathname]
        if (req.method === 'POST' && rpc) return send(200, (await db.query(rpc[0], rpc[1].map(key => body[key]))).rows)

        const queryTable = async (table, allowedColumns, filterNames, defaultOrder) => {
            const select = (url.searchParams.get('select') || '').split(',')
            if (select.some(column => !allowedColumns.has(column))) return send(400, { message: 'Unsupported select' })
            const values = []; const predicates = []
            for (const name of filterNames) {
                const filter = url.searchParams.get(name)
                if (!filter && ['project_uuid','module_uuid'].includes(name)) return send(400, { message: 'Scope required' })
                if (filter) { if (!filter.startsWith('eq.')) return send(400, { message: 'Unsupported filter' }); values.push(filter.slice(3)); predicates.push(`${name}=$${values.length}`) }
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
        if (req.method === 'GET' && url.pathname === '/rest/v1/krikkit_payment_settings') {
            return await queryTable('krikkit_payment_settings', paymentSettingsColumns, ['project_uuid','module_uuid'], 'updated_at DESC,module_uuid DESC')
        }
        if (req.method === 'GET' && url.pathname === '/rest/v1/krikkit_payment_attempts') {
            return await queryTable('krikkit_payment_attempts', paymentAttemptColumns, ['project_uuid','module_uuid','order_uuid','request_key','status','uuid'], 'uploaded_at DESC,uuid DESC')
        }
        send(501, { message: 'Endpoint not modeled by local PostgreSQL adapter' })
    } catch (error) {
        // PostgreSQL transaction errors must release the connection for the next test.
        try { await db.exec('ROLLBACK;') } catch { /* No active transaction. */ }
        send(400, { message: error.message })
    }
})
server.listen(4910, '127.0.0.1', () => console.log('Local fictional PostgreSQL adapter listening on 127.0.0.1:4910'))
