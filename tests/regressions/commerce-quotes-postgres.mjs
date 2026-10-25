import assert from 'node:assert/strict'
import { readFile } from 'node:fs/promises'
import { randomUUID } from 'node:crypto'
import { storageCatalog, privatePaymentsSql, quotedOrdersSql } from '../fixtures/runtime/private-storage-schema.mjs'
const { PGlite } = await import(new URL('../../.agent-work/test-deps/node_modules/@electric-sql/pglite/dist/index.js', import.meta.url))
const db=await PGlite.create()
let checks=0
const check=(value,label)=>{assert.ok(value,label);checks++}
const denied=async(fn,pattern,label)=>{await assert.rejects(fn,pattern,label);checks++}
const project=randomUUID(),catalog=randomUUID(),orders=randomUUID(),payments=randomUUID()
try {
    await db.exec('CREATE ROLE anon;CREATE ROLE authenticated;CREATE ROLE service_role BYPASSRLS;')
    await db.exec(storageCatalog)
    await db.exec(await readFile('database/sql/lab-managed/0003_catalog.sql','utf8'))
    await db.exec(await quotedOrdersSql()) // Orders can be reviewed/applied before payments exists.
    await db.exec(await privatePaymentsSql())
    await db.exec(await quotedOrdersSql())
    await db.query('SELECT * FROM krikkit_register_catalog($1,$2)',[project,catalog])
    check((await db.query('SELECT * FROM krikkit_register_orders($1,$2)',[project,orders])).rows[0].schema_version===2,'Orders registration binds schema v2')
    await db.query('SELECT * FROM krikkit_register_manual_payments($1,$2)',[project,payments])
    const product=(await db.query('SELECT * FROM krikkit_create_product($1,$2,$3,$4,$5,$6)',[project,catalog,randomUUID(),'a'.repeat(64),{sku:'FICTIONAL',title:'Fictional coffee',description:'',price_satang:25000,active:true},701])).rows[0].product_uuid
    await db.query("UPDATE krikkit_payment_settings SET enabled=true,recipient='0891234567',payee_label='Fictional' WHERE module_uuid=$1",[payments])
    const cart=[{product_uuid:product,quantity:2}]
    const quote=async(items=cart,p=project,pay=payments)=>(await db.query('SELECT * FROM krikkit_quote_order($1,$2,$3,$4,$5)',[p,orders,catalog,pay,items])).rows[0]
    const values=()=>({reference:'KRK-20261025-'+randomUUID().replaceAll('-','').slice(0,6).toUpperCase(),customer_name:'Fictional',customer_email:'quote@example.invalid',customer_phone:'',note:'',items:[],total_satang:100,promptpay_snapshot:{},access_token_hash:'b'.repeat(64)})
    const create=async(q,key=randomUUID(),items=cart,hash='c'.repeat(64))=>(await db.query('SELECT * FROM krikkit_create_quoted_order($1,$2,$3,$4,$5,$6,$7)',[project,orders,q.uuid,key,hash,items,values()])).rows[0]
    let q=await quote()
    check(q.total_satang===50000&&q.items[0].product_version===1,'Quote calculates integer amount and immutable product version')
    check(Date.parse(q.expires_at)-Date.parse(q.created_at)===600000,'Server quote expires in ten minutes')
    for(const items of [[],[cart[0],cart[0]],[{...cart[0],quantity:0}],[{...cart[0],quantity:'2'}],[{...cart[0],price_satang:1}]]) await denied(()=>quote(items),/invalid_order/,'Malformed or duplicate cart cannot become quote')
    await denied(()=>quote(cart,randomUUID()),/module_not_installed/,'Quote cannot cross project')
    await denied(()=>quote(cart,project,randomUUID()),/module_not_installed/,'Quote requires scoped payment installation')
    await denied(()=>create(q,randomUUID(),[{...cart[0],quantity:1}]),/quote_changed/,'Confirmed cart must match quote exactly')
    await db.query('UPDATE krikkit_catalog_products SET price_satang=28000,version=version+1 WHERE uuid=$1',[product])
    await denied(()=>create(q),/quote_changed/,'Changed catalog invalidates prior quote without order')
    check((await db.query('SELECT count(*)::integer AS n FROM krikkit_commerce_orders')).rows[0].n===0,'Reprice never silently creates an order')
    q=await quote()
    await db.query('UPDATE krikkit_payment_settings SET recipient=$2,version=version+1 WHERE module_uuid=$1',[payments,'0899999999'])
    await denied(()=>create(q),/quote_changed/,'Changed recipient invalidates prior quote')
    q=await quote()
    await db.query("UPDATE krikkit_commerce_quotes SET expires_at=now()-interval '1 second' WHERE uuid=$1",[q.uuid])
    await denied(()=>create(q),/quote_changed/,'Expired quote cannot create new order')
    q=await quote(); const key=randomUUID(), saved=await create(q,key)
    const stored=(await db.query('SELECT * FROM krikkit_commerce_orders WHERE uuid=$1',[saved.order_uuid])).rows[0]
    check(stored.total_satang===56000&&stored.items.length===1,'RPC ignores caller amount/items and persists quoted authority')
    await denied(()=>create(q),/quote_changed/,'A quote cannot be consumed by another creation key')
    await db.query("UPDATE krikkit_commerce_quotes SET expires_at=now()-interval '1 day' WHERE uuid=$1",[q.uuid])
    await db.query('UPDATE krikkit_payment_settings SET enabled=false WHERE module_uuid=$1',[payments])
    check((await create(q,key)).replayed,'Ambiguous accepted creation recovers after quote expiry and disable')
    await denied(()=>create(q,key,cart,'d'.repeat(64)),/idempotency_conflict/,'Retry cannot change accepted creation payload')
    await denied(()=>db.query('SELECT * FROM krikkit_revoke_order_access($1,$2,$3,$4)',[randomUUID(),orders,saved.order_uuid,701]),/order_not_found/,'Guest revoke cannot cross project')
    await db.query('SELECT * FROM krikkit_revoke_order_access($1,$2,$3,$4)',[project,orders,saved.order_uuid,701])
    await db.query('SELECT * FROM krikkit_revoke_order_access($1,$2,$3,$4)',[project,orders,saved.order_uuid,701])
    const revoked=(await db.query('SELECT access_revoked_at,access_revoked_by,status FROM krikkit_commerce_orders WHERE uuid=$1',[saved.order_uuid])).rows[0]
    check(revoked.access_revoked_at&&revoked.access_revoked_by===701&&revoked.status==='awaiting_payment','Owner revoke records actor and preserves payment state')
    check((await db.query('SELECT count(*)::integer AS n FROM krikkit_commerce_order_events WHERE order_uuid=$1',[saved.order_uuid])).rows[0].n===2,'Revocation event is idempotent')
    await denied(()=>db.query('SELECT * FROM krikkit_begin_slip_upload($1,$2,$3,$4,$5,$6,$7)',[project,payments,saved.order_uuid,randomUUID(),'a'.repeat(64),randomUUID(),{sha256:'b'.repeat(64),bytes:100,width:5,height:5}]),/order_not_found/,'SQL also fences uploads when capability was revoked after HTTP authorization')
    await db.query('UPDATE krikkit_payment_settings SET enabled=true WHERE module_uuid=$1',[payments])
    const raced=await create(await quote())
    const staged=(await db.query('SELECT * FROM krikkit_begin_slip_upload($1,$2,$3,$4,$5,$6,$7)',[project,payments,raced.order_uuid,randomUUID(),'a'.repeat(64),randomUUID(),{sha256:'b'.repeat(64),bytes:100,width:5,height:5}])).rows[0]
    await db.query('SELECT * FROM krikkit_revoke_order_access($1,$2,$3,$4)',[project,orders,raced.order_uuid,701])
    await denied(()=>db.query('SELECT * FROM krikkit_commit_slip_upload($1,$2,$3,$4,$5)',[project,payments,staged.uuid,staged.lease_uuid,{payer_name:'Fictional',payer_phone:'',amount_satang:56000,transfer_ref:'',claimed_transferred_at:''}]),/order_not_found/,'In-flight upload cannot commit after owner revokes capability')
    await db.exec('SET ROLE service_role')
    await denied(()=>db.query('SELECT * FROM krikkit_create_order($1,$2,$3,$4,$5)',[project,orders,randomUUID(),'a'.repeat(64),values()]),/permission denied/,'Legacy non-quote create RPC cannot bypass confirmation')
    await denied(()=>db.exec('DELETE FROM krikkit_commerce_quotes WHERE false'),/permission denied/,'Quotes cannot be edited through service REST')
    await db.exec('RESET ROLE')
    for(const role of ['anon','authenticated']){
        await db.exec(`SET ROLE ${role}`)
        await denied(()=>db.query('SELECT * FROM krikkit_commerce_quotes'),/permission denied/,'Private recipient snapshots are not public '+role)
        await denied(()=>quote(),/permission denied/,'Untrusted role cannot issue quote '+role)
        await db.exec('RESET ROLE')
    }
    await db.exec('ALTER TABLE krikkit_commerce_quotes DROP CONSTRAINT krikkit_commerce_quotes_total_satang_check; ALTER TABLE krikkit_commerce_quotes ADD CONSTRAINT krikkit_commerce_quotes_total_satang_check CHECK(true)')
    await denied(async()=>db.exec(await quotedOrdersSql()),/managed_schema_incompatible/,'Weakened schema cannot be marked ready')
    await db.exec('ROLLBACK')
    console.log(`PASS: authoritative quotes, explicit reprice, replay, revoke and private schema (${checks} checks).`)
}finally{await db.close()}
