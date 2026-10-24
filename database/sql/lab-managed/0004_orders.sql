BEGIN;
SELECT pg_advisory_xact_lock(hashtextextended('krikkit:managed:orders:v1',0));
DO $guard$
DECLARE n text; object_id oid;
BEGIN
  FOREACH n IN ARRAY ARRAY['krikkit_orders_installations','krikkit_commerce_orders','krikkit_commerce_order_events'] LOOP
    object_id:=to_regclass('public.'||n);
    IF object_id IS NOT NULL AND (coalesce(obj_description(object_id,'pg_class'),'')<>'krikkit-managed-orders-v1' OR NOT EXISTS(SELECT 1 FROM pg_class WHERE oid=object_id AND relkind='r')) THEN RAISE EXCEPTION 'managed_schema_collision'; END IF;
  END LOOP;
  FOREACH n IN ARRAY ARRAY['public.krikkit_register_orders(uuid,uuid)','public.krikkit_create_order(uuid,uuid,uuid,text,jsonb)'] LOOP
    object_id:=to_regprocedure(n);
    IF object_id IS NOT NULL AND coalesce(obj_description(object_id,'pg_proc'),'')<>'krikkit-managed-orders-v1' THEN RAISE EXCEPTION 'managed_schema_collision'; END IF;
  END LOOP;
END $guard$;
CREATE TABLE IF NOT EXISTS public.krikkit_orders_installations(
  module_uuid uuid PRIMARY KEY,project_uuid uuid NOT NULL,schema_version integer NOT NULL DEFAULT 1 CHECK(schema_version=1),created_at timestamptz NOT NULL DEFAULT now(),UNIQUE(project_uuid,module_uuid)
);
COMMENT ON TABLE public.krikkit_orders_installations IS 'krikkit-managed-orders-v1';
CREATE TABLE IF NOT EXISTS public.krikkit_commerce_orders(
  uuid uuid PRIMARY KEY DEFAULT gen_random_uuid(),project_uuid uuid NOT NULL,module_uuid uuid NOT NULL,
  request_key uuid NOT NULL,request_hash text NOT NULL CHECK(request_hash ~ '^[a-f0-9]{64}$'),
  reference text NOT NULL CHECK(reference ~ '^KRK-[0-9]{8}-[A-Z0-9]{6}$'),
  customer_name text NOT NULL CHECK(length(customer_name) BETWEEN 1 AND 120),
  customer_email text NOT NULL CHECK(length(customer_email) BETWEEN 3 AND 160),
  customer_phone text NOT NULL DEFAULT '' CHECK(length(customer_phone)<=40),
  note text NOT NULL DEFAULT '' CHECK(length(note)<=1000),
  items jsonb NOT NULL CHECK(jsonb_typeof(items)='array' AND jsonb_array_length(items) BETWEEN 1 AND 50),
  total_satang integer NOT NULL CHECK(total_satang BETWEEN 100 AND 100000000),
  currency text NOT NULL DEFAULT 'THB' CHECK(currency='THB'),
  promptpay_snapshot jsonb NOT NULL CHECK(jsonb_typeof(promptpay_snapshot)='object'),
  access_token_hash text NOT NULL CHECK(access_token_hash ~ '^[a-f0-9]{64}$'),
  status text NOT NULL DEFAULT 'awaiting_payment' CHECK(status IN('awaiting_payment','pending_review','paid','rejected','cancelled')),
  version integer NOT NULL DEFAULT 1 CHECK(version>=1),
  created_at timestamptz NOT NULL DEFAULT now(),updated_at timestamptz NOT NULL DEFAULT now(),
  FOREIGN KEY(project_uuid,module_uuid) REFERENCES public.krikkit_orders_installations(project_uuid,module_uuid),
  UNIQUE(project_uuid,module_uuid,request_key),UNIQUE(project_uuid,module_uuid,reference)
);
COMMENT ON TABLE public.krikkit_commerce_orders IS 'krikkit-managed-orders-v1';
CREATE INDEX IF NOT EXISTS krikkit_orders_owner_idx ON public.krikkit_commerce_orders(project_uuid,module_uuid,created_at DESC,uuid DESC);
CREATE TABLE IF NOT EXISTS public.krikkit_commerce_order_events(
  uuid uuid PRIMARY KEY DEFAULT gen_random_uuid(),project_uuid uuid NOT NULL,module_uuid uuid NOT NULL,
  order_uuid uuid NOT NULL REFERENCES public.krikkit_commerce_orders(uuid),actor_id bigint,
  previous_status text,next_status text NOT NULL,version integer NOT NULL,created_at timestamptz NOT NULL DEFAULT now()
);
COMMENT ON TABLE public.krikkit_commerce_order_events IS 'krikkit-managed-orders-v1';
CREATE OR REPLACE FUNCTION public.krikkit_register_orders(p_project_uuid uuid,p_module_uuid uuid)
RETURNS TABLE(module_uuid uuid,project_uuid uuid,schema_version integer)
LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
BEGIN
  IF p_project_uuid IS NULL OR p_module_uuid IS NULL THEN RAISE EXCEPTION 'invalid_binding'; END IF;
  INSERT INTO public.krikkit_orders_installations(module_uuid,project_uuid) VALUES(p_module_uuid,p_project_uuid) ON CONFLICT DO NOTHING;
  IF NOT EXISTS(SELECT 1 FROM public.krikkit_orders_installations i WHERE i.module_uuid=p_module_uuid AND i.project_uuid=p_project_uuid) THEN RAISE EXCEPTION 'binding_conflict'; END IF;
  RETURN QUERY SELECT i.module_uuid,i.project_uuid,i.schema_version FROM public.krikkit_orders_installations i WHERE i.module_uuid=p_module_uuid AND i.project_uuid=p_project_uuid;
END $fn$;
COMMENT ON FUNCTION public.krikkit_register_orders(uuid,uuid) IS 'krikkit-managed-orders-v1';
CREATE OR REPLACE FUNCTION public.krikkit_create_order(p_project_uuid uuid,p_module_uuid uuid,p_key uuid,p_hash text,p_values jsonb)
RETURNS TABLE(order_uuid uuid,reference text,total_satang integer,status text,version integer,replayed boolean)
LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
DECLARE saved public.krikkit_commerce_orders%ROWTYPE;
BEGIN
  IF NOT EXISTS(SELECT 1 FROM public.krikkit_orders_installations i WHERE i.project_uuid=p_project_uuid AND i.module_uuid=p_module_uuid AND i.schema_version=1) THEN RAISE EXCEPTION 'module_not_installed'; END IF;
  IF p_key IS NULL OR p_hash IS NULL OR p_hash !~ '^[a-f0-9]{64}$' OR jsonb_typeof(p_values) IS DISTINCT FROM 'object'
    OR jsonb_typeof(p_values->'reference') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'customer_name') IS DISTINCT FROM 'string'
    OR jsonb_typeof(p_values->'customer_email') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'customer_phone') IS DISTINCT FROM 'string'
    OR jsonb_typeof(p_values->'note') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'items') IS DISTINCT FROM 'array'
    OR jsonb_typeof(p_values->'total_satang') IS DISTINCT FROM 'number' OR jsonb_typeof(p_values->'promptpay_snapshot') IS DISTINCT FROM 'object'
    OR jsonb_typeof(p_values->'access_token_hash') IS DISTINCT FROM 'string' THEN RAISE EXCEPTION 'invalid_order'; END IF;
  PERFORM pg_advisory_xact_lock(hashtextextended(p_module_uuid::text||p_key::text,0));
  SELECT o.* INTO saved FROM public.krikkit_commerce_orders o WHERE o.project_uuid=p_project_uuid AND o.module_uuid=p_module_uuid AND o.request_key=p_key;
  IF FOUND THEN
    IF saved.request_hash<>p_hash THEN RAISE EXCEPTION 'idempotency_conflict'; END IF;
    RETURN QUERY SELECT saved.uuid,saved.reference,saved.total_satang,saved.status,saved.version,true; RETURN;
  END IF;
  INSERT INTO public.krikkit_commerce_orders(project_uuid,module_uuid,request_key,request_hash,reference,customer_name,customer_email,customer_phone,note,items,total_satang,promptpay_snapshot,access_token_hash)
  VALUES(p_project_uuid,p_module_uuid,p_key,p_hash,p_values->>'reference',trim(p_values->>'customer_name'),lower(trim(p_values->>'customer_email')),trim(p_values->>'customer_phone'),p_values->>'note',p_values->'items',(p_values->>'total_satang')::integer,p_values->'promptpay_snapshot',p_values->>'access_token_hash') RETURNING * INTO saved;
  INSERT INTO public.krikkit_commerce_order_events(project_uuid,module_uuid,order_uuid,next_status,version) VALUES(p_project_uuid,p_module_uuid,saved.uuid,saved.status,1);
  RETURN QUERY SELECT saved.uuid,saved.reference,saved.total_satang,saved.status,saved.version,false;
END $fn$;
COMMENT ON FUNCTION public.krikkit_create_order(uuid,uuid,uuid,text,jsonb) IS 'krikkit-managed-orders-v1';
DO $compatibility$
BEGIN
  IF EXISTS(SELECT 1 FROM (VALUES
    ('krikkit_orders_installations','module_uuid','uuid'),('krikkit_orders_installations','project_uuid','uuid'),('krikkit_orders_installations','schema_version','integer'),
    ('krikkit_commerce_orders','uuid','uuid'),('krikkit_commerce_orders','project_uuid','uuid'),('krikkit_commerce_orders','module_uuid','uuid'),
    ('krikkit_commerce_orders','reference','text'),('krikkit_commerce_orders','total_satang','integer'),('krikkit_commerce_orders','status','text'),('krikkit_commerce_orders','version','integer')
  ) e(t,c,type_name) LEFT JOIN pg_attribute a ON a.attrelid=to_regclass('public.'||e.t) AND a.attname=e.c AND NOT a.attisdropped WHERE a.attnum IS NULL OR NOT a.attnotnull OR format_type(a.atttypid,a.atttypmod)<>e.type_name)
  THEN RAISE EXCEPTION 'managed_schema_incompatible'; END IF;
END $compatibility$;
ALTER TABLE public.krikkit_orders_installations ENABLE ROW LEVEL SECURITY;ALTER TABLE public.krikkit_orders_installations FORCE ROW LEVEL SECURITY;
ALTER TABLE public.krikkit_commerce_orders ENABLE ROW LEVEL SECURITY;ALTER TABLE public.krikkit_commerce_orders FORCE ROW LEVEL SECURITY;
ALTER TABLE public.krikkit_commerce_order_events ENABLE ROW LEVEL SECURITY;ALTER TABLE public.krikkit_commerce_order_events FORCE ROW LEVEL SECURITY;
REVOKE ALL ON public.krikkit_orders_installations,public.krikkit_commerce_orders,public.krikkit_commerce_order_events FROM PUBLIC,anon,authenticated,service_role;
REVOKE ALL ON FUNCTION public.krikkit_register_orders(uuid,uuid),public.krikkit_create_order(uuid,uuid,uuid,text,jsonb) FROM PUBLIC,anon,authenticated;
GRANT SELECT ON public.krikkit_orders_installations,public.krikkit_commerce_orders,public.krikkit_commerce_order_events TO service_role;
GRANT EXECUTE ON FUNCTION public.krikkit_register_orders(uuid,uuid),public.krikkit_create_order(uuid,uuid,uuid,text,jsonb) TO service_role;
NOTIFY pgrst,'reload schema';
COMMIT;
SELECT 'orders'::text AS krikkit_schema_key,1::integer AS schema_version,true AS verified;
