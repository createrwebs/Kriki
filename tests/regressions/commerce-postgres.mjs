import assert from 'node:assert/strict'
import { readFile } from 'node:fs/promises'
import { randomUUID } from 'node:crypto'
import { storageCatalog, privatePaymentsSql } from '../fixtures/runtime/private-storage-schema.mjs'
const { PGlite } = await import(new URL('../../.agent-work/test-deps/node_modules/@electric-sql/pglite/dist/index.js', import.meta.url))
const db = await PGlite.create()
let checks = 0
const check = (value, label) => { assert.ok(value, label); checks++ }
const denied = async (fn, pattern, label) => { await assert.rejects(fn,pattern,label); checks++ }
const project = randomUUID(), orders = randomUUID(), payments = randomUUID()
try {
    await db.exec('CREATE ROLE anon;CREATE ROLE authenticated;CREATE ROLE service_role BYPASSRLS;ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO service_role;')
    await db.exec(storageCatalog)
    for(const file of ['0003_catalog.sql','0004_orders.sql','0005_manual_payments.sql']) {
        const sql = file==='0005_manual_payments.sql' ? await privatePaymentsSql() : await readFile(`database/sql/lab-managed/${file}`,'utf8'); await db.exec(sql); await db.exec(sql)
    }
    await db.query('SELECT * FROM krikkit_register_orders($1,$2)',[project,orders])
    await db.query('SELECT * FROM krikkit_register_manual_payments($1,$2)',[project,payments])
    const makeOrder = async (total=1000) => (await db.query('SELECT * FROM krikkit_create_order($1,$2,$3,$4,$5)',[project,orders,randomUUID(),'a'.repeat(64),{reference:'KRK-20261025-'+randomUUID().replaceAll('-','').slice(0,6).toUpperCase(),customer_name:'Fictional',customer_email:'test@example.invalid',customer_phone:'',note:'',items:[{title:'fixture'}],total_satang:total,promptpay_snapshot:{},access_token_hash:'b'.repeat(64)}])).rows[0]
    const begin = async(id,key=randomUUID(),lease=randomUUID()) => (await db.query('SELECT * FROM krikkit_begin_slip_upload($1,$2,$3,$4,$5,$6,$7)',[project,payments,id,key,'c'.repeat(64),lease,{sha256:'d'.repeat(64),bytes:100,width:5,height:5}])).rows[0]
    const commit = async(upload,amount=1000) => (await db.query('SELECT * FROM krikkit_commit_slip_upload($1,$2,$3,$4,$5)',[project,payments,upload.uuid,upload.lease_uuid,{payer_name:'Fictional',payer_phone:'',amount_satang:amount,transfer_ref:'',claimed_transferred_at:''}])).rows[0]
    const slip = async (id,amount=1000) => (await commit(await begin(id),amount))
    const review = (id,decision) => db.query('SELECT * FROM krikkit_review_payment_slip($1,$2,$3,$4,$5,$6,$7)',[project,payments,id,1,decision,'Bank verified by fictional owner',701])
    const order = await makeOrder(), first = await slip(order.order_uuid), second = await slip(order.order_uuid)
    check((await review(first.attempt_uuid,'accepted')).rows[0].order_status === 'paid','First accepted payment marks paid')
    await denied(()=>review(second.attempt_uuid,'rejected'),/invalid_transition/,'A later review cannot reset an already paid order')
    const state = (await db.query('SELECT status FROM krikkit_commerce_orders WHERE uuid=$1',[order.order_uuid])).rows[0]
    check(state.status==='paid','Accepted payment terminal state preserved')
    const events = (await db.query('SELECT previous_status,next_status FROM krikkit_commerce_order_events WHERE order_uuid=$1 ORDER BY version',[order.order_uuid])).rows
    check(events[1].previous_status==='awaiting_payment'&&events[1].next_status==='pending_review','Upload audit retains actual pre-update order state')
    const wrongAmountOrder = await makeOrder(), wrongSlip = await slip(wrongAmountOrder.order_uuid,2000)
    await denied(()=>review(wrongSlip.attempt_uuid,'accepted'),/invalid_review/,'Owner cannot accept a slip whose declared amount differs from the order')
    const receipt=await begin((await makeOrder()).order_uuid), cleanupLease=randomUUID()
    await denied(()=>begin(receipt.order_uuid,receipt.request_key),/upload_in_progress/,'Parallel retry cannot share an active upload lease')
    const claim=()=>db.query('SELECT * FROM krikkit_claim_slip_cleanup($1,$2,$3,$4,$5)',[project,payments,receipt.uuid,receipt.lease_uuid,cleanupLease])
    check((await claim()).rows[0].state==='cleanup_pending','Cleanup claim fences uncertain commit before deleting')
    await denied(()=>commit(receipt),/upload_in_progress/,'Commit cannot race past claimed cleanup')
    await db.query('SELECT krikkit_finish_slip_cleanup($1,$2,$3,$4)',[project,payments,receipt.uuid,cleanupLease])
    const retried=await begin(receipt.order_uuid,receipt.request_key)
    check(retried.object_path!==receipt.object_path,'Retry after confirmed cleanup gets a fresh random object path')
    await denied(()=>commit(receipt),/upload_in_progress/,'A stale worker cannot commit the replacement upload')
    const committed=await commit(retried)
    check((await commit(retried)).attempt_uuid===committed.attempt_uuid,'Lost commit response retries exactly one attempt')
    const claimedCommitted=(await db.query('SELECT * FROM krikkit_claim_slip_cleanup($1,$2,$3,$4,$5)',[project,payments,retried.uuid,retried.lease_uuid,randomUUID()])).rows[0]
    check(claimedCommitted.state==='committed','Uncertain response recovery preserves committed evidence')
    const stored=(await db.query('SELECT slip_data_url,slip_bucket,slip_bytes FROM krikkit_payment_attempts WHERE uuid=$1',[committed.attempt_uuid])).rows[0]
    check(stored.slip_data_url===null&&stored.slip_bucket==='krikkit-slips-'+payments&&stored.slip_bytes===100,'New attempts store metadata only, no inline image')
    await denied(()=>db.query('SELECT * FROM krikkit_claim_slip_cleanup($1,$2,$3,$4,$5)',[randomUUID(),payments,retried.uuid,retried.lease_uuid,randomUUID()]),/attempt_not_found/,'Cleanup cannot cross project scope')
    const bucket='krikkit-slips-'+payments
    await db.query("INSERT INTO storage.buckets(id,name) VALUES('unrelated','unrelated')")
    await db.query("INSERT INTO storage.objects(bucket_id,name) VALUES($1,'private.png'),('unrelated','ordinary.png')",[bucket])
    for(const role of ['anon','authenticated']){
        await db.exec(`SET ROLE ${role}`)
        check((await db.query('SELECT name FROM storage.objects')).rows.every(row=>row.name==='ordinary.png'),'Restrictive policy preserves unrelated access and hides private evidence '+role)
        await denied(()=>db.query("INSERT INTO storage.objects(bucket_id,name) VALUES($1,'forged.png')",[bucket]),/row-level security/,'Broad existing policies cannot permit private writes '+role)
        await db.exec('RESET ROLE')
    }
    for(const isPublic of [true,false]){
        const collision=randomUUID(), collisionBucket='krikkit-slips-'+collision
        await db.query('INSERT INTO storage.buckets(id,name,public) VALUES($1,$1,$2)',[collisionBucket,isPublic])
        await denied(()=>db.query('SELECT * FROM krikkit_register_manual_payments($1,$2)',[project,collision]),/private_bucket_collision/,'Existing unowned bucket is never adopted')
    }
    await db.exec('SET ROLE service_role')
    await denied(()=>db.query('SELECT * FROM krikkit_submit_payment_slip($1,$2,$3,$4,$5,$6)',[project,payments,receipt.order_uuid,randomUUID(),'a'.repeat(64),{}]),/permission denied/,'Legacy inline submission RPC is disabled')
    await db.exec('RESET ROLE')
    await db.exec('ALTER TABLE krikkit_payment_attempts ALTER COLUMN slip_bucket TYPE integer USING NULL;')
    await denied(async()=>db.exec(await privatePaymentsSql()),/managed_schema_incompatible/,'Wrong new column type cannot be marked schema ready')
    await db.exec('ROLLBACK; ALTER TABLE krikkit_payment_attempts ALTER COLUMN slip_bucket TYPE text USING NULL;')
    await db.exec('ALTER TABLE krikkit_slip_uploads DROP CONSTRAINT krikkit_slip_uploads_image_bytes_check; ALTER TABLE krikkit_slip_uploads ADD CONSTRAINT krikkit_slip_uploads_image_bytes_check CHECK(true);')
    await denied(async()=>db.exec(await privatePaymentsSql()),/managed_schema_incompatible/,'Weakened receipt constraint cannot be marked ready')
    await db.exec('ROLLBACK;')
    for(const role of ['anon','authenticated','service_role']) {
        await db.exec(`SET ROLE ${role}`)
        await denied(()=>db.exec('DELETE FROM krikkit_payment_attempts WHERE false'),/permission denied/,'Direct table writes denied '+role)
        if(role!=='service_role') await denied(()=>db.query('SELECT * FROM krikkit_payment_attempts'),/permission denied/,'Private slips not readable '+role)
        await db.exec('RESET ROLE')
    }
    console.log(`PASS: commerce PostgreSQL monetary state, audit and private access (${checks} checks).`)
} finally { await db.close() }
