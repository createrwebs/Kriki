// Real PostgreSQL WASM semantics in memory; not a live Supabase/RLS deployment test.
import assert from 'node:assert/strict'
import { readFile } from 'node:fs/promises'
const { PGlite } = await import(new URL('../../.agent-work/test-deps/node_modules/@electric-sql/pglite/dist/index.js', import.meta.url))
const sql = await readFile('database/sql/lab-managed/0002_news.sql', 'utf8')
let checks = 0
const check = (value, label) => { assert.ok(value, label); checks++ }
const db = await PGlite.create()
const project = '10000000-0000-4000-8000-000000000001'
const sibling = '10000000-0000-4000-8000-000000000002'
const module = '20000000-0000-4000-8000-000000000001'
const otherModule = '20000000-0000-4000-8000-000000000002'
const key = '30000000-0000-4000-8000-000000000001'
const values = { slug: 'grand-opening', title: 'เปิดสาขาใหม่', excerpt: 'ข่าวประชาสัมพันธ์', body: 'รายละเอียดการเปิดสาขาใหม่สำหรับทดสอบระบบข่าวสาร', status: 'draft' }
const create = (p = project, m = module, k = key, hash = 'a'.repeat(64), data = values, actor = 701) =>
    db.query('SELECT * FROM public.krikkit_create_news($1,$2,$3,$4,$5,$6)', [p, m, k, hash, data, actor])
const update = (p, m, id, ver, data, actor = 701) =>
    db.query('SELECT * FROM public.krikkit_update_news($1,$2,$3,$4,$5,$6)', [p, m, id, ver, data, actor])
const denied = async (action, pattern, label) => { await assert.rejects(action, pattern, label); checks++ }
try {
    await db.exec('CREATE ROLE anon; CREATE ROLE authenticated; CREATE ROLE service_role BYPASSRLS; ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO service_role;')
    await db.exec(sql)
    await db.exec(sql)
    check(true, 'Additive news install is idempotent on owned schema')
    await db.query('SELECT * FROM public.krikkit_register_news($1,$2)', [project, module])
    await db.query('SELECT * FROM public.krikkit_register_news($1,$2)', [sibling, otherModule])
    await denied(() => db.query('SELECT * FROM public.krikkit_register_news($1,$2)', [sibling, module]), /binding_conflict/, 'News module UUID cannot move to another project')
    await db.exec('SET ROLE service_role;')
    for (const statement of [
        "UPDATE public.krikkit_news_articles SET title='unreviewed' WHERE false",
        'DELETE FROM public.krikkit_news_articles WHERE false',
        'INSERT INTO public.krikkit_news_articles SELECT * FROM public.krikkit_news_articles WHERE false',
    ]) await denied(() => db.exec(statement), /permission denied/, 'Service role cannot bypass audited news RPC writes')
    const first = (await create()).rows[0]
    check(first.status === 'draft' && first.version === 1 && !first.replayed, 'Real news insert starts draft with server version 1')
    const replay = (await create()).rows[0]
    check(replay.article_uuid === first.article_uuid && replay.replayed, 'Exact idempotency retry does not duplicate article')
    await denied(() => create(project, module, key, 'b'.repeat(64)), /idempotency_conflict/, 'Changed payload hash conflicts')
    await denied(() => create(project, module, '30000000-0000-4000-8000-000000000002', 'b'.repeat(64), values), /slug_conflict/, 'Duplicate slug in same module rejected')
    const siblingArticle = (await create(sibling, otherModule, '30000000-0000-4000-8000-000000000003', 'c'.repeat(64), values)).rows[0]
    check(Boolean(siblingArticle.article_uuid), 'Sibling project can use the same slug independently')
    await denied(() => create(project, module, '30000000-0000-4000-8000-000000000004', 'd'.repeat(64), { ...values, slug: 'bad_slug' }), /check|invalid_article/, 'Invalid slug format rejected')
    await denied(() => create(project, module, '30000000-0000-4000-8000-000000000005', 'd'.repeat(64), { ...values, status: 'archived' }), /invalid_article/, 'Create cannot start archived')
    const published = (await update(project, module, first.article_uuid, 1, { ...values, title: 'เปิดสาขาใหม่ (อัปเดต)', status: 'published' })).rows[0]
    check(published.status === 'published' && published.version === 2, 'CAS update publishes article and increments version')
    await denied(() => update(project, module, first.article_uuid, 1, { ...values, status: 'archived' }), /version_conflict/, 'Stale version update rejected')
    await denied(() => update(project, module, first.article_uuid, 2, { ...values, slug: 'renamed-slug' }), /slug_immutable/, 'Published/saved slug is immutable')
    await denied(() => update(sibling, otherModule, first.article_uuid, 2, { ...values, status: 'archived' }), /article_not_found/, 'Cross-project update denied')
    const archived = (await update(project, module, first.article_uuid, 2, { ...values, status: 'archived' })).rows[0]
    check(archived.status === 'archived' && archived.version === 3, 'Archive preserves article and advances version')
    const events = (await db.query('SELECT actor_id,previous_status,next_status,version FROM public.krikkit_news_events WHERE project_uuid=$1 AND module_uuid=$2 ORDER BY version ASC', [project, module])).rows
    check(events.length === 3 && events[0].next_status === 'draft' && events[1].next_status === 'published' && events[2].next_status === 'archived', 'Audit events record full lifecycle')
    for (const role of ['anon', 'authenticated']) {
        await db.exec(`RESET ROLE; SET ROLE ${role};`)
        await denied(() => db.query('SELECT * FROM public.krikkit_news_articles'), /permission denied/, `${role} cannot directly read news table`)
        await denied(() => create(), /permission denied/, `${role} cannot invoke news create RPC`)
    }
    await db.exec('RESET ROLE;')
    console.log(`PASS: news PostgreSQL schema/RPC/private access (${checks} checks, PGlite memory).`)
} finally { await db.close() }
