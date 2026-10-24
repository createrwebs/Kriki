BEGIN;
SELECT pg_advisory_xact_lock(hashtextextended('krikkit:managed:catalog:v1',0));
DO $guard$
DECLARE n text; object_id oid;
BEGIN
  FOREACH n IN ARRAY ARRAY['krikkit_catalog_installations','krikkit_catalog_products','krikkit_catalog_events'] LOOP
    object_id:=to_regclass('public.'||n);
    IF object_id IS NOT NULL AND (coalesce(obj_description(object_id,'pg_class'),'')<>'krikkit-managed-catalog-v1' OR NOT EXISTS(SELECT 1 FROM pg_class WHERE oid=object_id AND relkind='r')) THEN RAISE EXCEPTION 'managed_schema_collision'; END IF;
  END LOOP;
  FOREACH n IN ARRAY ARRAY['public.krikkit_register_catalog(uuid,uuid)','public.krikkit_create_product(uuid,uuid,uuid,text,jsonb,bigint)','public.krikkit_update_product(uuid,uuid,uuid,integer,jsonb,bigint)'] LOOP
    object_id:=to_regprocedure(n);
    IF object_id IS NOT NULL AND coalesce(obj_description(object_id,'pg_proc'),'')<>'krikkit-managed-catalog-v1' THEN RAISE EXCEPTION 'managed_schema_collision'; END IF;
  END LOOP;
END $guard$;
CREATE TABLE IF NOT EXISTS public.krikkit_catalog_installations(
  module_uuid uuid PRIMARY KEY,project_uuid uuid NOT NULL,schema_version integer NOT NULL DEFAULT 1 CHECK(schema_version=1),created_at timestamptz NOT NULL DEFAULT now(),UNIQUE(project_uuid,module_uuid)
);
COMMENT ON TABLE public.krikkit_catalog_installations IS 'krikkit-managed-catalog-v1';
CREATE TABLE IF NOT EXISTS public.krikkit_catalog_products(
  uuid uuid PRIMARY KEY DEFAULT gen_random_uuid(),project_uuid uuid NOT NULL,module_uuid uuid NOT NULL,
  request_key uuid NOT NULL,request_hash text NOT NULL CHECK(request_hash ~ '^[a-f0-9]{64}$'),
  sku text NOT NULL CHECK(sku ~ '^[A-Za-z0-9][A-Za-z0-9_-]{0,63}$'),
  title text NOT NULL CHECK(length(title) BETWEEN 1 AND 160),
  description text NOT NULL DEFAULT '' CHECK(length(description)<=2000),
  price_satang integer NOT NULL CHECK(price_satang BETWEEN 100 AND 100000000),
  active boolean NOT NULL DEFAULT true,
  version integer NOT NULL DEFAULT 1 CHECK(version>=1),
  created_at timestamptz NOT NULL DEFAULT now(),updated_at timestamptz NOT NULL DEFAULT now(),
  FOREIGN KEY(project_uuid,module_uuid) REFERENCES public.krikkit_catalog_installations(project_uuid,module_uuid),
  UNIQUE(project_uuid,module_uuid,request_key),UNIQUE(project_uuid,module_uuid,sku)
);
COMMENT ON TABLE public.krikkit_catalog_products IS 'krikkit-managed-catalog-v1';
CREATE INDEX IF NOT EXISTS krikkit_catalog_public_idx ON public.krikkit_catalog_products(project_uuid,module_uuid,active,created_at DESC,uuid DESC);
CREATE TABLE IF NOT EXISTS public.krikkit_catalog_events(
  uuid uuid PRIMARY KEY DEFAULT gen_random_uuid(),project_uuid uuid NOT NULL,module_uuid uuid NOT NULL,
  product_uuid uuid NOT NULL REFERENCES public.krikkit_catalog_products(uuid),actor_id bigint NOT NULL,
  action text NOT NULL,price_satang integer NOT NULL,active boolean NOT NULL,version integer NOT NULL,created_at timestamptz NOT NULL DEFAULT now()
);
COMMENT ON TABLE public.krikkit_catalog_events IS 'krikkit-managed-catalog-v1';
CREATE OR REPLACE FUNCTION public.krikkit_register_catalog(p_project_uuid uuid,p_module_uuid uuid)
RETURNS TABLE(module_uuid uuid,project_uuid uuid,schema_version integer)
LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
BEGIN
  IF p_project_uuid IS NULL OR p_module_uuid IS NULL THEN RAISE EXCEPTION 'invalid_binding'; END IF;
  INSERT INTO public.krikkit_catalog_installations(module_uuid,project_uuid) VALUES(p_module_uuid,p_project_uuid) ON CONFLICT DO NOTHING;
  IF NOT EXISTS(SELECT 1 FROM public.krikkit_catalog_installations i WHERE i.module_uuid=p_module_uuid AND i.project_uuid=p_project_uuid) THEN RAISE EXCEPTION 'binding_conflict'; END IF;
  RETURN QUERY SELECT i.module_uuid,i.project_uuid,i.schema_version FROM public.krikkit_catalog_installations i WHERE i.module_uuid=p_module_uuid AND i.project_uuid=p_project_uuid;
END $fn$;
COMMENT ON FUNCTION public.krikkit_register_catalog(uuid,uuid) IS 'krikkit-managed-catalog-v1';
CREATE OR REPLACE FUNCTION public.krikkit_create_product(p_project_uuid uuid,p_module_uuid uuid,p_key uuid,p_hash text,p_values jsonb,p_actor_id bigint)
RETURNS TABLE(product_uuid uuid,sku text,price_satang integer,active boolean,version integer,replayed boolean)
LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
DECLARE saved public.krikkit_catalog_products%ROWTYPE;
BEGIN
  IF NOT EXISTS(SELECT 1 FROM public.krikkit_catalog_installations i WHERE i.project_uuid=p_project_uuid AND i.module_uuid=p_module_uuid AND i.schema_version=1) THEN RAISE EXCEPTION 'module_not_installed'; END IF;
  IF p_key IS NULL OR p_hash IS NULL OR p_hash !~ '^[a-f0-9]{64}$' OR p_actor_id IS NULL OR p_actor_id<1 OR jsonb_typeof(p_values) IS DISTINCT FROM 'object'
    OR EXISTS(SELECT 1 FROM jsonb_object_keys(p_values) k WHERE k NOT IN('sku','title','description','price_satang','active'))
    OR jsonb_typeof(p_values->'sku') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'title') IS DISTINCT FROM 'string'
    OR jsonb_typeof(p_values->'description') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'price_satang') IS DISTINCT FROM 'number'
    OR jsonb_typeof(p_values->'active') IS DISTINCT FROM 'boolean' THEN RAISE EXCEPTION 'invalid_product'; END IF;
  PERFORM pg_advisory_xact_lock(hashtextextended(p_module_uuid::text||p_key::text,0));
  SELECT p.* INTO saved FROM public.krikkit_catalog_products p WHERE p.project_uuid=p_project_uuid AND p.module_uuid=p_module_uuid AND p.request_key=p_key;
  IF FOUND THEN
    IF saved.request_hash<>p_hash THEN RAISE EXCEPTION 'idempotency_conflict'; END IF;
    RETURN QUERY SELECT saved.uuid,saved.sku,saved.price_satang,saved.active,saved.version,true; RETURN;
  END IF;
  IF EXISTS(SELECT 1 FROM public.krikkit_catalog_products p WHERE p.project_uuid=p_project_uuid AND p.module_uuid=p_module_uuid AND p.sku=p_values->>'sku') THEN RAISE EXCEPTION 'sku_conflict'; END IF;
  INSERT INTO public.krikkit_catalog_products(project_uuid,module_uuid,request_key,request_hash,sku,title,description,price_satang,active)
  VALUES(p_project_uuid,p_module_uuid,p_key,p_hash,p_values->>'sku',trim(p_values->>'title'),p_values->>'description',(p_values->>'price_satang')::integer,(p_values->>'active')::boolean) RETURNING * INTO saved;
  INSERT INTO public.krikkit_catalog_events(project_uuid,module_uuid,product_uuid,actor_id,action,price_satang,active,version)
  VALUES(p_project_uuid,p_module_uuid,saved.uuid,p_actor_id,'created',saved.price_satang,saved.active,1);
  RETURN QUERY SELECT saved.uuid,saved.sku,saved.price_satang,saved.active,saved.version,false;
END $fn$;
COMMENT ON FUNCTION public.krikkit_create_product(uuid,uuid,uuid,text,jsonb,bigint) IS 'krikkit-managed-catalog-v1';
CREATE OR REPLACE FUNCTION public.krikkit_update_product(p_project_uuid uuid,p_module_uuid uuid,p_product_uuid uuid,p_expected_version integer,p_values jsonb,p_actor_id bigint)
RETURNS TABLE(product_uuid uuid,sku text,price_satang integer,active boolean,version integer)
LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
DECLARE saved public.krikkit_catalog_products%ROWTYPE;
BEGIN
  IF p_actor_id IS NULL OR p_actor_id<1 OR jsonb_typeof(p_values) IS DISTINCT FROM 'object'
    OR EXISTS(SELECT 1 FROM jsonb_object_keys(p_values) k WHERE k NOT IN('sku','title','description','price_satang','active'))
    OR jsonb_typeof(p_values->'sku') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'title') IS DISTINCT FROM 'string'
    OR jsonb_typeof(p_values->'description') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'price_satang') IS DISTINCT FROM 'number'
    OR jsonb_typeof(p_values->'active') IS DISTINCT FROM 'boolean' THEN RAISE EXCEPTION 'invalid_product'; END IF;
  SELECT p.* INTO saved FROM public.krikkit_catalog_products p WHERE p.uuid=p_product_uuid AND p.project_uuid=p_project_uuid AND p.module_uuid=p_module_uuid FOR UPDATE;
  IF NOT FOUND THEN RAISE EXCEPTION 'product_not_found'; END IF;
  IF saved.version IS DISTINCT FROM p_expected_version THEN RAISE EXCEPTION 'version_conflict'; END IF;
  IF saved.sku<>p_values->>'sku' THEN RAISE EXCEPTION 'sku_immutable'; END IF;
  UPDATE public.krikkit_catalog_products p SET title=trim(p_values->>'title'),description=p_values->>'description',price_satang=(p_values->>'price_satang')::integer,
    active=(p_values->>'active')::boolean,version=p.version+1,updated_at=now() WHERE p.uuid=saved.uuid RETURNING p.* INTO saved;
  INSERT INTO public.krikkit_catalog_events(project_uuid,module_uuid,product_uuid,actor_id,action,price_satang,active,version)
  VALUES(p_project_uuid,p_module_uuid,saved.uuid,p_actor_id,'updated',saved.price_satang,saved.active,saved.version);
  RETURN QUERY SELECT saved.uuid,saved.sku,saved.price_satang,saved.active,saved.version;
END $fn$;
COMMENT ON FUNCTION public.krikkit_update_product(uuid,uuid,uuid,integer,jsonb,bigint) IS 'krikkit-managed-catalog-v1';
DO $compatibility$
BEGIN
  IF EXISTS(SELECT 1 FROM (VALUES
    ('krikkit_catalog_installations','module_uuid','uuid'),('krikkit_catalog_installations','project_uuid','uuid'),('krikkit_catalog_installations','schema_version','integer'),
    ('krikkit_catalog_products','uuid','uuid'),('krikkit_catalog_products','project_uuid','uuid'),('krikkit_catalog_products','module_uuid','uuid'),
    ('krikkit_catalog_products','sku','text'),('krikkit_catalog_products','title','text'),('krikkit_catalog_products','price_satang','integer'),('krikkit_catalog_products','active','boolean'),('krikkit_catalog_products','version','integer')
  ) e(t,c,type_name) LEFT JOIN pg_attribute a ON a.attrelid=to_regclass('public.'||e.t) AND a.attname=e.c AND NOT a.attisdropped WHERE a.attnum IS NULL OR NOT a.attnotnull OR format_type(a.atttypid,a.atttypmod)<>e.type_name)
  THEN RAISE EXCEPTION 'managed_schema_incompatible'; END IF;
END $compatibility$;
ALTER TABLE public.krikkit_catalog_installations ENABLE ROW LEVEL SECURITY;ALTER TABLE public.krikkit_catalog_installations FORCE ROW LEVEL SECURITY;
ALTER TABLE public.krikkit_catalog_products ENABLE ROW LEVEL SECURITY;ALTER TABLE public.krikkit_catalog_products FORCE ROW LEVEL SECURITY;
ALTER TABLE public.krikkit_catalog_events ENABLE ROW LEVEL SECURITY;ALTER TABLE public.krikkit_catalog_events FORCE ROW LEVEL SECURITY;
REVOKE ALL ON public.krikkit_catalog_installations,public.krikkit_catalog_products,public.krikkit_catalog_events FROM PUBLIC,anon,authenticated,service_role;
REVOKE ALL ON FUNCTION public.krikkit_register_catalog(uuid,uuid),public.krikkit_create_product(uuid,uuid,uuid,text,jsonb,bigint),public.krikkit_update_product(uuid,uuid,uuid,integer,jsonb,bigint) FROM PUBLIC,anon,authenticated;
GRANT SELECT ON public.krikkit_catalog_installations,public.krikkit_catalog_products,public.krikkit_catalog_events TO service_role;
GRANT EXECUTE ON FUNCTION public.krikkit_register_catalog(uuid,uuid),public.krikkit_create_product(uuid,uuid,uuid,text,jsonb,bigint),public.krikkit_update_product(uuid,uuid,uuid,integer,jsonb,bigint) TO service_role;
NOTIFY pgrst,'reload schema';
COMMIT;
SELECT 'catalog'::text AS krikkit_schema_key,1::integer AS schema_version,true AS verified;
