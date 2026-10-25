DO $guard$
DECLARE n text; object_id oid;
BEGIN
  object_id:=to_regclass('public.krikkit_commerce_quotes');
  IF object_id IS NOT NULL AND coalesce(obj_description(object_id,'pg_class'),'')<>'krikkit-commerce-quotes-v2' THEN RAISE EXCEPTION 'managed_schema_collision'; END IF;
  FOREACH n IN ARRAY ARRAY['public.krikkit_quote_order(uuid,uuid,uuid,uuid,jsonb)','public.krikkit_create_quoted_order(uuid,uuid,uuid,uuid,text,jsonb,jsonb)','public.krikkit_revoke_order_access(uuid,uuid,uuid,bigint)'] LOOP
    object_id:=to_regprocedure(n);
    IF object_id IS NOT NULL AND coalesce(obj_description(object_id,'pg_proc'),'')<>'krikkit-commerce-quotes-v2' THEN RAISE EXCEPTION 'managed_schema_collision'; END IF;
  END LOOP;
END $guard$;
ALTER TABLE public.krikkit_orders_installations DROP CONSTRAINT IF EXISTS krikkit_orders_installations_schema_version_check;
ALTER TABLE public.krikkit_orders_installations ADD CONSTRAINT krikkit_orders_installations_schema_version_check CHECK(schema_version IN(1,2));
ALTER TABLE public.krikkit_orders_installations ALTER COLUMN schema_version SET DEFAULT 2;
ALTER TABLE public.krikkit_commerce_orders ADD COLUMN IF NOT EXISTS access_revoked_at timestamptz;
ALTER TABLE public.krikkit_commerce_orders ADD COLUMN IF NOT EXISTS access_revoked_by bigint;
CREATE TABLE IF NOT EXISTS public.krikkit_commerce_quotes(
  uuid uuid PRIMARY KEY DEFAULT gen_random_uuid(),project_uuid uuid NOT NULL,module_uuid uuid NOT NULL,catalog_module_uuid uuid NOT NULL,payment_module_uuid uuid NOT NULL,
  items jsonb NOT NULL CHECK(jsonb_typeof(items)='array' AND jsonb_array_length(items) BETWEEN 1 AND 25),
  total_satang integer NOT NULL CHECK(total_satang BETWEEN 100 AND 100000000),settings jsonb NOT NULL CHECK(jsonb_typeof(settings)='object'),
  catalog_version text NOT NULL,expires_at timestamptz NOT NULL DEFAULT now()+interval '10 minutes',
  consumed_key uuid,created_at timestamptz NOT NULL DEFAULT now(),
  FOREIGN KEY(project_uuid,module_uuid) REFERENCES public.krikkit_orders_installations(project_uuid,module_uuid)
);
COMMENT ON TABLE public.krikkit_commerce_quotes IS 'krikkit-commerce-quotes-v2';
CREATE INDEX IF NOT EXISTS krikkit_quotes_expiry ON public.krikkit_commerce_quotes(project_uuid,module_uuid,expires_at);
CREATE OR REPLACE FUNCTION public.krikkit_register_orders(p_project_uuid uuid,p_module_uuid uuid)
RETURNS TABLE(module_uuid uuid,project_uuid uuid,schema_version integer)
LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
BEGIN
  IF p_project_uuid IS NULL OR p_module_uuid IS NULL THEN RAISE EXCEPTION 'invalid_binding'; END IF;
  INSERT INTO public.krikkit_orders_installations(module_uuid,project_uuid,schema_version) VALUES(p_module_uuid,p_project_uuid,2)
    ON CONFLICT ON CONSTRAINT krikkit_orders_installations_pkey DO UPDATE SET schema_version=CASE WHEN krikkit_orders_installations.project_uuid=EXCLUDED.project_uuid THEN 2 ELSE krikkit_orders_installations.schema_version END;
  IF NOT EXISTS(SELECT 1 FROM public.krikkit_orders_installations i WHERE i.module_uuid=p_module_uuid AND i.project_uuid=p_project_uuid) THEN RAISE EXCEPTION 'binding_conflict'; END IF;
  RETURN QUERY SELECT p_module_uuid,p_project_uuid,2;
END $fn$;
COMMENT ON FUNCTION public.krikkit_register_orders(uuid,uuid) IS 'krikkit-managed-orders-v1';
CREATE OR REPLACE FUNCTION public.krikkit_quote_order(p_project_uuid uuid,p_module_uuid uuid,p_catalog uuid,p_payments uuid,p_items jsonb)
RETURNS SETOF public.krikkit_commerce_quotes LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
DECLARE line jsonb; product record; config record; snapshot jsonb:='[]'; total bigint:=0; saved public.krikkit_commerce_quotes%ROWTYPE;
BEGIN
  IF NOT EXISTS(SELECT 1 FROM public.krikkit_orders_installations i WHERE i.project_uuid=p_project_uuid AND i.module_uuid=p_module_uuid AND i.schema_version=2)
    OR NOT EXISTS(SELECT 1 FROM public.krikkit_catalog_installations i WHERE i.project_uuid=p_project_uuid AND i.module_uuid=p_catalog)
    OR NOT EXISTS(SELECT 1 FROM public.krikkit_manual_payments_installations i WHERE i.project_uuid=p_project_uuid AND i.module_uuid=p_payments AND i.schema_version=2) THEN RAISE EXCEPTION 'module_not_installed'; END IF;
  IF jsonb_typeof(p_items) IS DISTINCT FROM 'array' OR jsonb_array_length(p_items) NOT BETWEEN 1 AND 25
    OR (SELECT count(DISTINCT item->>'product_uuid') FROM jsonb_array_elements(p_items) item)<>jsonb_array_length(p_items) THEN RAISE EXCEPTION 'invalid_order'; END IF;
  SELECT s.* INTO config FROM public.krikkit_payment_settings s WHERE s.project_uuid=p_project_uuid AND s.module_uuid=p_payments FOR SHARE;
  IF NOT FOUND OR NOT config.enabled OR config.recipient='' THEN RAISE EXCEPTION 'quote_changed'; END IF;
  FOR line IN SELECT value FROM jsonb_array_elements(p_items) ORDER BY value->>'product_uuid' LOOP
    IF jsonb_typeof(line) IS DISTINCT FROM 'object' OR EXISTS(SELECT 1 FROM jsonb_object_keys(line) k WHERE k NOT IN('product_uuid','quantity'))
      OR jsonb_typeof(line->'quantity') IS DISTINCT FROM 'number' OR coalesce(line->>'quantity','') !~ '^[0-9]{1,2}$' THEN RAISE EXCEPTION 'invalid_order'; END IF;
    IF (line->>'quantity')::integer NOT BETWEEN 1 AND 99 THEN RAISE EXCEPTION 'invalid_order'; END IF;
    SELECT p.* INTO product FROM public.krikkit_catalog_products p WHERE p.project_uuid=p_project_uuid AND p.module_uuid=p_catalog AND p.uuid=(line->>'product_uuid')::uuid AND p.active FOR SHARE;
    IF NOT FOUND THEN RAISE EXCEPTION 'quote_changed'; END IF;
    total:=total+product.price_satang*(line->>'quantity')::bigint;
    snapshot:=snapshot||jsonb_build_array(jsonb_build_object('product_uuid',product.uuid,'sku',product.sku,'title',product.title,'unit_price_satang',product.price_satang,'quantity',(line->>'quantity')::integer,'line_total_satang',product.price_satang*(line->>'quantity')::bigint,'product_version',product.version));
  END LOOP;
  IF total NOT BETWEEN 100 AND 100000000 THEN RAISE EXCEPTION 'invalid_order'; END IF;
  INSERT INTO public.krikkit_commerce_quotes(project_uuid,module_uuid,catalog_module_uuid,payment_module_uuid,items,total_satang,settings,catalog_version)
    VALUES(p_project_uuid,p_module_uuid,p_catalog,p_payments,snapshot,total,jsonb_build_object('recipient_type',config.recipient_type,'recipient',config.recipient,'payee_label',config.payee_label,'instructions',config.instructions,'version',config.version),md5(snapshot::text||config.version::text)) RETURNING * INTO saved;
  RETURN NEXT saved;
END $fn$;
COMMENT ON FUNCTION public.krikkit_quote_order(uuid,uuid,uuid,uuid,jsonb) IS 'krikkit-commerce-quotes-v2';
CREATE OR REPLACE FUNCTION public.krikkit_create_quoted_order(p_project_uuid uuid,p_module_uuid uuid,p_quote uuid,p_key uuid,p_hash text,p_cart jsonb,p_values jsonb)
RETURNS TABLE(order_uuid uuid,reference text,status text,version integer,replayed boolean)
LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
DECLARE quote public.krikkit_commerce_quotes%ROWTYPE; saved public.krikkit_commerce_orders%ROWTYPE; line jsonb; product record; config record; cart jsonb;
BEGIN
  PERFORM pg_advisory_xact_lock(hashtextextended(p_module_uuid::text||p_key::text,0));
  SELECT o.* INTO saved FROM public.krikkit_commerce_orders o WHERE o.project_uuid=p_project_uuid AND o.module_uuid=p_module_uuid AND o.request_key=p_key;
  IF FOUND THEN
    IF saved.request_hash<>p_hash THEN RAISE EXCEPTION 'idempotency_conflict'; END IF;
    RETURN QUERY SELECT saved.uuid,saved.reference,saved.status,saved.version,true; RETURN;
  END IF;
  SELECT q.* INTO quote FROM public.krikkit_commerce_quotes q WHERE q.uuid=p_quote AND q.project_uuid=p_project_uuid AND q.module_uuid=p_module_uuid FOR UPDATE;
  IF NOT FOUND OR quote.expires_at<=now() OR quote.consumed_key IS NOT NULL THEN RAISE EXCEPTION 'quote_changed'; END IF;
  SELECT jsonb_agg(jsonb_build_object('product_uuid',item->'product_uuid','quantity',item->'quantity') ORDER BY item->>'product_uuid') INTO cart FROM jsonb_array_elements(quote.items) item;
  IF cart IS DISTINCT FROM (SELECT jsonb_agg(item ORDER BY item->>'product_uuid') FROM jsonb_array_elements(p_cart) item) THEN RAISE EXCEPTION 'quote_changed'; END IF;
  SELECT s.* INTO config FROM public.krikkit_payment_settings s WHERE s.project_uuid=p_project_uuid AND s.module_uuid=quote.payment_module_uuid FOR SHARE;
  IF NOT FOUND OR NOT config.enabled OR config.version<>(quote.settings->>'version')::integer THEN RAISE EXCEPTION 'quote_changed'; END IF;
  FOR line IN SELECT value FROM jsonb_array_elements(quote.items) ORDER BY value->>'product_uuid' LOOP
    SELECT p.* INTO product FROM public.krikkit_catalog_products p WHERE p.project_uuid=p_project_uuid AND p.module_uuid=quote.catalog_module_uuid AND p.uuid=(line->>'product_uuid')::uuid FOR SHARE;
    IF NOT FOUND OR NOT product.active OR product.version<>(line->>'product_version')::integer OR product.price_satang<>(line->>'unit_price_satang')::integer THEN RAISE EXCEPTION 'quote_changed'; END IF;
  END LOOP;
  RETURN QUERY SELECT result.order_uuid,result.reference,result.status,result.version,result.replayed FROM public.krikkit_create_order(p_project_uuid,p_module_uuid,p_key,p_hash,p_values||jsonb_build_object('items',quote.items,'total_satang',quote.total_satang)) result;
  UPDATE public.krikkit_commerce_quotes q SET consumed_key=p_key WHERE q.uuid=quote.uuid;
END $fn$;
COMMENT ON FUNCTION public.krikkit_create_quoted_order(uuid,uuid,uuid,uuid,text,jsonb,jsonb) IS 'krikkit-commerce-quotes-v2';
CREATE OR REPLACE FUNCTION public.krikkit_revoke_order_access(p_project_uuid uuid,p_module_uuid uuid,p_order uuid,p_actor bigint)
RETURNS TABLE(revoked boolean) LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
DECLARE saved public.krikkit_commerce_orders%ROWTYPE;
BEGIN
  IF p_actor IS NULL OR p_actor<1 THEN RAISE EXCEPTION 'invalid_order'; END IF;
  SELECT o.* INTO saved FROM public.krikkit_commerce_orders o WHERE o.project_uuid=p_project_uuid AND o.module_uuid=p_module_uuid AND o.uuid=p_order FOR UPDATE;
  IF NOT FOUND THEN RAISE EXCEPTION 'order_not_found'; END IF;
  IF saved.access_revoked_at IS NULL THEN
    UPDATE public.krikkit_commerce_orders o SET access_revoked_at=now(),access_revoked_by=p_actor WHERE o.uuid=saved.uuid;
    INSERT INTO public.krikkit_commerce_order_events(project_uuid,module_uuid,order_uuid,actor_id,previous_status,next_status,version) VALUES(p_project_uuid,p_module_uuid,p_order,p_actor,saved.status,saved.status,saved.version);
  END IF;
  RETURN QUERY SELECT true;
END $fn$;
COMMENT ON FUNCTION public.krikkit_revoke_order_access(uuid,uuid,uuid,bigint) IS 'krikkit-commerce-quotes-v2';
DO $compatibility$
BEGIN
  IF EXISTS(SELECT 1 FROM (VALUES
    ('krikkit_commerce_orders','access_revoked_at','timestamp with time zone',false),('krikkit_commerce_orders','access_revoked_by','bigint',false),
    ('krikkit_commerce_quotes','uuid','uuid',true),('krikkit_commerce_quotes','project_uuid','uuid',true),('krikkit_commerce_quotes','module_uuid','uuid',true),
    ('krikkit_commerce_quotes','catalog_module_uuid','uuid',true),('krikkit_commerce_quotes','payment_module_uuid','uuid',true),
    ('krikkit_commerce_quotes','items','jsonb',true),('krikkit_commerce_quotes','total_satang','integer',true),('krikkit_commerce_quotes','settings','jsonb',true),
    ('krikkit_commerce_quotes','catalog_version','text',true),('krikkit_commerce_quotes','expires_at','timestamp with time zone',true),
    ('krikkit_commerce_quotes','consumed_key','uuid',false),('krikkit_commerce_quotes','created_at','timestamp with time zone',true)
  ) e(t,c,type_name,required) LEFT JOIN pg_attribute a ON a.attrelid=to_regclass('public.'||e.t) AND a.attname=e.c AND NOT a.attisdropped
    WHERE a.attnum IS NULL OR a.attnotnull<>e.required OR format_type(a.atttypid,a.atttypmod)<>e.type_name)
    OR EXISTS(SELECT 1 FROM (VALUES
      ('krikkit_commerce_quotes_pkey','PRIMARY KEY (uuid)'),
      ('krikkit_commerce_quotes_items_check',$check$CHECK (((jsonb_typeof(items) = 'array'::text) AND ((jsonb_array_length(items) >= 1) AND (jsonb_array_length(items) <= 25))))$check$),
      ('krikkit_commerce_quotes_total_satang_check','CHECK (((total_satang >= 100) AND (total_satang <= 100000000)))'),
      ('krikkit_commerce_quotes_settings_check',$check$CHECK ((jsonb_typeof(settings) = 'object'::text))$check$),
      ('krikkit_commerce_quotes_project_uuid_module_uuid_fkey','FOREIGN KEY (project_uuid, module_uuid) REFERENCES krikkit_orders_installations(project_uuid, module_uuid)')
    ) e(n,definition) LEFT JOIN pg_constraint c ON c.conrelid='public.krikkit_commerce_quotes'::regclass AND c.conname=e.n
      WHERE c.oid IS NULL OR NOT c.convalidated OR pg_get_constraintdef(c.oid)<>e.definition)
  THEN RAISE EXCEPTION 'managed_schema_incompatible'; END IF;
END $compatibility$;
ALTER TABLE public.krikkit_commerce_quotes ENABLE ROW LEVEL SECURITY;ALTER TABLE public.krikkit_commerce_quotes FORCE ROW LEVEL SECURITY;
REVOKE ALL ON public.krikkit_commerce_quotes FROM PUBLIC,anon,authenticated,service_role;
GRANT SELECT ON public.krikkit_commerce_quotes TO service_role;
REVOKE ALL ON FUNCTION public.krikkit_quote_order(uuid,uuid,uuid,uuid,jsonb),public.krikkit_create_quoted_order(uuid,uuid,uuid,uuid,text,jsonb,jsonb),public.krikkit_revoke_order_access(uuid,uuid,uuid,bigint) FROM PUBLIC,anon,authenticated;
GRANT EXECUTE ON FUNCTION public.krikkit_quote_order(uuid,uuid,uuid,uuid,jsonb),public.krikkit_create_quoted_order(uuid,uuid,uuid,uuid,text,jsonb,jsonb),public.krikkit_revoke_order_access(uuid,uuid,uuid,bigint) TO service_role;
REVOKE EXECUTE ON FUNCTION public.krikkit_create_order(uuid,uuid,uuid,text,jsonb) FROM PUBLIC,anon,authenticated,service_role;
