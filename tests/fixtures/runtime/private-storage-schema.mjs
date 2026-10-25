import { readFile } from 'node:fs/promises'
// Supabase's Storage catalog contract; object bytes are modeled by the HTTP fixture.
export const storageCatalog = `CREATE SCHEMA storage;
CREATE TABLE storage.buckets(id text PRIMARY KEY,name text,public boolean DEFAULT false,file_size_limit bigint,allowed_mime_types text[]);
CREATE TABLE storage.objects(id uuid PRIMARY KEY DEFAULT gen_random_uuid(),bucket_id text REFERENCES storage.buckets(id),name text);
ALTER TABLE storage.objects ENABLE ROW LEVEL SECURITY;
GRANT USAGE ON SCHEMA storage TO anon,authenticated,service_role;
GRANT SELECT,INSERT,UPDATE,DELETE ON storage.objects TO anon,authenticated,service_role;
CREATE POLICY fixture_existing_broad_policy ON storage.objects FOR ALL TO anon,authenticated USING(true) WITH CHECK(true);`
export async function privatePaymentsSql() {
    const legacy = await readFile('database/sql/lab-managed/0005_manual_payments.sql','utf8')
    const addition = await readFile('database/sql/lab-managed/0006_private_slips.fragment.sql','utf8')
    return legacy.slice(0,legacy.lastIndexOf('\nCOMMIT;'))+'\n'+addition+"\nCOMMIT;\nSELECT 'manual_payments'::text AS krikkit_schema_key,2::integer AS schema_version,true AS verified;"
}
export async function quotedOrdersSql() {
    const legacy=await readFile('database/sql/lab-managed/0004_orders.sql','utf8')
    return legacy.slice(0,legacy.lastIndexOf('\nCOMMIT;'))+'\n'+await readFile('database/sql/lab-managed/0007_order_quotes.fragment.sql','utf8')+"\nCOMMIT;\nSELECT 'orders'::text AS krikkit_schema_key,2::integer AS schema_version,true AS verified;"
}
