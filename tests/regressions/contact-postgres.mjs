// npm install --prefix .agent-work/test-deps --ignore-scripts --no-audit --no-fund @electric-sql/pglite@0.5.10
// Real PostgreSQL WASM semantics in memory; not a live Supabase/RLS deployment test.
import assert from 'node:assert/strict'
import { readFile } from 'node:fs/promises'
const { PGlite } = await import(new URL('../../.agent-work/test-deps/node_modules/@electric-sql/pglite/dist/index.js', import.meta.url))
const sql = await readFile('database/sql/lab-managed/0001_contact.sql', 'utf8')
let checks = 0
const check = (value, label) => { assert.ok(value, label); checks++ }
const db = await PGlite.create()
const project = '10000000-0000-4000-8000-000000000001'
const sibling = '10000000-0000-4000-8000-000000000002'
const module = '20000000-0000-4000-8000-000000000001'
const otherModule = '20000000-0000-4000-8000-000000000002'
const key = '30000000-0000-4000-8000-000000000001'
const values = { name: 'ข้อมูลสมมติ', email: 'fictional@example.invalid', phone: '', subject: 'ทดลองติดต่อ', message: 'ข้อความสำหรับทดสอบฐานข้อมูล', consent: true }
const submit = (p = project, m = module, k = key, hash = 'a'.repeat(64), data = values) => db.query('SELECT * FROM public.krikkit_submit_contact($1,$2,$3,$4,$5)', [p, m, k, hash, data])
const denied = async (action, pattern, label) => { await assert.rejects(action, pattern, label); checks++ }
try {
    await db.exec('CREATE ROLE anon; CREATE ROLE authenticated; CREATE ROLE service_role BYPASSRLS; ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO service_role;')
    await db.exec(sql)
    await db.exec(sql)
    check(true, 'Additive install is idempotent on owned schema')
    await db.query('SELECT * FROM public.krikkit_register_contact($1,$2)', [project, module])
    await db.query('SELECT * FROM public.krikkit_register_contact($1,$2)', [sibling, otherModule])
    await denied(() => db.query('SELECT * FROM public.krikkit_register_contact($1,$2)', [sibling, module]), /binding_conflict/, 'Module UUID cannot move to another project')
    await db.exec('SET ROLE service_role;')
    for (const statement of [
        "UPDATE public.krikkit_contact_messages SET message='unreviewed' WHERE false",
        'DELETE FROM public.krikkit_contact_messages WHERE false',
        'INSERT INTO public.krikkit_contact_messages SELECT * FROM public.krikkit_contact_messages WHERE false',
    ]) await denied(() => db.exec(statement), /permission denied/, 'Preexisting service-role grants cannot bypass audited CAS RPC writes')
    const first = (await submit()).rows[0]
    check(first.status === 'new' && first.version === 1 && !first.replayed, 'Real insert starts new with server status/version')
    const replay = (await submit()).rows[0]
    check(replay.submission_uuid === first.submission_uuid && replay.replayed, 'Exact idempotency retry does not duplicate')
    await denied(() => submit(project, module, key, 'b'.repeat(64)), /idempotency_conflict/, 'Changed payload hash conflicts')
    await denied(() => submit(sibling, module), /module_not_installed/, 'Same database project does not bypass module/project binding')
    await denied(() => submit(project, module, '30000000-0000-4000-8000-000000000002', 'b'.repeat(64), { ...values, status: 'paid' }), /invalid_contact/, 'Unknown controlled field is rejected')
    await denied(() => submit(project, module, '30000000-0000-4000-8000-000000000002', 'b'.repeat(64), { ...values, consent: 'true' }), /invalid_contact/, 'String consent cannot impersonate boolean consent')
    const own = await db.query('SELECT uuid FROM public.krikkit_contact_messages WHERE project_uuid=$1 AND module_uuid=$2', [project, module])
    const foreign = await db.query('SELECT uuid FROM public.krikkit_contact_messages WHERE project_uuid=$1 AND module_uuid=$2', [sibling, otherModule])
    check(own.rows.length === 1 && foreign.rows.length === 0, 'Scoped listing isolates sibling records')
    const updated = (await db.query('SELECT * FROM public.krikkit_transition_contact($1,$2,$3,$4,$5,$6)', [project, module, first.submission_uuid, 1, 'read', 701])).rows[0]
    check(updated.status === 'read' && updated.version === 2, 'CAS transition increments record version')
    await denied(() => db.query('SELECT * FROM public.krikkit_transition_contact($1,$2,$3,$4,$5,$6)', [project, module, first.submission_uuid, 1, 'archived', 701]), /version_conflict/, 'Competing stale transition loses')
    await denied(() => db.query('SELECT * FROM public.krikkit_transition_contact($1,$2,$3,$4,$5,$6)', [sibling, otherModule, first.submission_uuid, 2, 'archived', 701]), /contact_not_found/, 'Cross-project record reference does not authorize transition')
    const events = (await db.query('SELECT actor_id,previous_status,next_status,version FROM public.krikkit_contact_events WHERE project_uuid=$1 AND module_uuid=$2', [project, module])).rows
    check(events.length === 1 && events[0].next_status === 'read' && Number(events[0].actor_id) === 701, 'Accepted transition appends one owner audit event')
    for (const role of ['anon', 'authenticated']) {
        await db.exec(`RESET ROLE; SET ROLE ${role};`)
        await denied(() => db.query('SELECT * FROM public.krikkit_contact_messages'), /permission denied/, `${role} cannot directly read private messages`)
        await denied(() => submit(), /permission denied/, `${role} cannot invoke server contact RPC`)
    }
    await db.exec('RESET ROLE;')
    const preserved = (await db.query('SELECT count(*)::integer AS count FROM public.krikkit_contact_messages')).rows[0]
    check(preserved.count === 1, 'Rejected operations preserve the accepted message')
    await db.exec('ALTER TABLE public.krikkit_contact_messages ALTER COLUMN module_uuid DROP NOT NULL;')
    await denied(() => db.exec(sql), /managed_schema_incompatible/, 'Owned but incompatible table cannot be marked verified')
    await db.exec('ROLLBACK;')
    check((await db.query('SELECT count(*)::integer AS count FROM public.krikkit_contact_messages')).rows[0].count === 1, 'Incompatible retry rolls back without removing messages')
    const collision = await PGlite.create()
    try {
        await collision.exec('CREATE ROLE anon;CREATE ROLE authenticated;CREATE ROLE service_role BYPASSRLS;CREATE TABLE public.krikkit_contact_messages(marker text);INSERT INTO public.krikkit_contact_messages VALUES(\'preserve\');')
        await denied(() => collision.exec(sql), /managed_schema_collision/, 'Unowned namespace collision aborts installation')
        await collision.exec('ROLLBACK;')
        check((await collision.query('SELECT marker FROM public.krikkit_contact_messages')).rows[0].marker === 'preserve', 'Collision preserves existing table and rows')
    } finally { await collision.close() }
    console.log(`PASS: contact PostgreSQL schema/RPC/private access (${checks} checks, PGlite memory).`)
} finally { await db.close() }
