-- ModuleSchema includes this in the reviewed v1 transaction. Objects use Storage REST only.
DO $guard$
DECLARE n text; object_id oid;
BEGIN
  IF to_regclass('storage.buckets') IS NULL OR to_regclass('storage.objects') IS NULL OR NOT EXISTS(SELECT 1 FROM pg_class WHERE oid='storage.objects'::regclass AND relrowsecurity) THEN RAISE EXCEPTION 'private_storage_unavailable'; END IF;
  object_id:=to_regclass('public.krikkit_slip_uploads');
  IF object_id IS NOT NULL AND coalesce(obj_description(object_id,'pg_class'),'')<>'krikkit-private-slips-v2' THEN RAISE EXCEPTION 'managed_schema_collision'; END IF;
  FOREACH n IN ARRAY ARRAY['public.krikkit_is_private_slip_bucket(text)','public.krikkit_begin_slip_upload(uuid,uuid,uuid,uuid,text,uuid,jsonb)','public.krikkit_commit_slip_upload(uuid,uuid,uuid,uuid,jsonb)','public.krikkit_claim_slip_cleanup(uuid,uuid,uuid,uuid,uuid)','public.krikkit_finish_slip_cleanup(uuid,uuid,uuid,uuid)'] LOOP
    object_id:=to_regprocedure(n);
    IF object_id IS NOT NULL AND coalesce(obj_description(object_id,'pg_proc'),'')<>'krikkit-private-slips-v2' THEN RAISE EXCEPTION 'managed_schema_collision'; END IF;
  END LOOP;
  SELECT oid INTO object_id FROM pg_policy WHERE polrelid='storage.objects'::regclass AND polname='krikkit_private_slips_guard';
  IF object_id IS NOT NULL AND coalesce(obj_description(object_id,'pg_policy'),'')<>'krikkit-private-slips-v2' THEN RAISE EXCEPTION 'managed_schema_collision'; END IF;
END $guard$;
ALTER TABLE public.krikkit_manual_payments_installations DROP CONSTRAINT IF EXISTS krikkit_manual_payments_installations_schema_version_check;
ALTER TABLE public.krikkit_manual_payments_installations ADD CONSTRAINT krikkit_manual_payments_installations_schema_version_check CHECK(schema_version IN(1,2));
ALTER TABLE public.krikkit_manual_payments_installations ALTER COLUMN schema_version SET DEFAULT 2;
ALTER TABLE public.krikkit_manual_payments_installations ADD COLUMN IF NOT EXISTS slip_bucket_id text;
CREATE UNIQUE INDEX IF NOT EXISTS krikkit_slip_bucket_binding ON public.krikkit_manual_payments_installations(slip_bucket_id) WHERE slip_bucket_id IS NOT NULL;
ALTER TABLE public.krikkit_payment_attempts ALTER COLUMN slip_data_url DROP NOT NULL;
ALTER TABLE public.krikkit_payment_attempts ADD COLUMN IF NOT EXISTS slip_bucket text;
ALTER TABLE public.krikkit_payment_attempts ADD COLUMN IF NOT EXISTS slip_object text;
ALTER TABLE public.krikkit_payment_attempts ADD COLUMN IF NOT EXISTS slip_bytes integer;
ALTER TABLE public.krikkit_payment_attempts ADD COLUMN IF NOT EXISTS slip_width integer;
ALTER TABLE public.krikkit_payment_attempts ADD COLUMN IF NOT EXISTS slip_height integer;
CREATE TABLE IF NOT EXISTS public.krikkit_slip_uploads(
  uuid uuid PRIMARY KEY DEFAULT gen_random_uuid(),project_uuid uuid NOT NULL,module_uuid uuid NOT NULL,order_uuid uuid NOT NULL REFERENCES public.krikkit_commerce_orders(uuid),request_key uuid NOT NULL,
  request_hash text NOT NULL CHECK(request_hash ~ '^[a-f0-9]{64}$'),bucket_id text NOT NULL,object_path text NOT NULL UNIQUE,
  image_sha256 text NOT NULL CHECK(image_sha256 ~ '^[a-f0-9]{64}$'),image_bytes integer NOT NULL CHECK(image_bytes BETWEEN 16 AND 5242880),
  image_width integer NOT NULL CHECK(image_width BETWEEN 1 AND 20000),image_height integer NOT NULL CHECK(image_height BETWEEN 1 AND 20000),
  state text NOT NULL DEFAULT 'staged' CHECK(state IN('staged','committed','cleanup_pending','abandoned')),lease_uuid uuid NOT NULL,lease_expires_at timestamptz NOT NULL,
  attempt_uuid uuid REFERENCES public.krikkit_payment_attempts(uuid),created_at timestamptz NOT NULL DEFAULT now(),updated_at timestamptz NOT NULL DEFAULT now(),
  CHECK(image_width::bigint*image_height<=20000000),CHECK((state='committed')=(attempt_uuid IS NOT NULL)),
  FOREIGN KEY(project_uuid,module_uuid) REFERENCES public.krikkit_manual_payments_installations(project_uuid,module_uuid)
);
COMMENT ON TABLE public.krikkit_slip_uploads IS 'krikkit-private-slips-v2';
DO $compatibility$
BEGIN
  IF EXISTS(SELECT 1 FROM (VALUES
    ('krikkit_manual_payments_installations','slip_bucket_id','text',false),
    ('krikkit_payment_attempts','slip_bucket','text',false),('krikkit_payment_attempts','slip_object','text',false),
    ('krikkit_payment_attempts','slip_bytes','integer',false),('krikkit_payment_attempts','slip_width','integer',false),('krikkit_payment_attempts','slip_height','integer',false),
    ('krikkit_slip_uploads','uuid','uuid',true),('krikkit_slip_uploads','project_uuid','uuid',true),('krikkit_slip_uploads','module_uuid','uuid',true),
    ('krikkit_slip_uploads','order_uuid','uuid',true),('krikkit_slip_uploads','request_key','uuid',true),('krikkit_slip_uploads','request_hash','text',true),
    ('krikkit_slip_uploads','bucket_id','text',true),('krikkit_slip_uploads','object_path','text',true),('krikkit_slip_uploads','image_sha256','text',true),
    ('krikkit_slip_uploads','image_bytes','integer',true),('krikkit_slip_uploads','image_width','integer',true),('krikkit_slip_uploads','image_height','integer',true),
    ('krikkit_slip_uploads','state','text',true),('krikkit_slip_uploads','lease_uuid','uuid',true),('krikkit_slip_uploads','lease_expires_at','timestamp with time zone',true),
    ('krikkit_slip_uploads','attempt_uuid','uuid',false),('krikkit_slip_uploads','created_at','timestamp with time zone',true),('krikkit_slip_uploads','updated_at','timestamp with time zone',true)
  ) e(t,c,type_name,required) LEFT JOIN pg_attribute a ON a.attrelid=to_regclass('public.'||e.t) AND a.attname=e.c AND NOT a.attisdropped
    WHERE a.attnum IS NULL OR a.attnotnull<>e.required OR format_type(a.atttypid,a.atttypmod)<>e.type_name)
  THEN RAISE EXCEPTION 'managed_schema_incompatible'; END IF;
  IF (SELECT count(*) FROM pg_constraint WHERE conrelid='public.krikkit_slip_uploads'::regclass AND contype='c' AND convalidated)<>8
    OR NOT EXISTS(SELECT 1 FROM pg_constraint WHERE conrelid='public.krikkit_slip_uploads'::regclass AND contype='p' AND pg_get_constraintdef(oid)='PRIMARY KEY (uuid)') THEN RAISE EXCEPTION 'managed_schema_incompatible'; END IF;
END $compatibility$;
DO $constraints$
BEGIN
  IF EXISTS(SELECT 1 FROM (VALUES
    ($c$CHECK ((((image_width)::bigint * image_height) <= 20000000))$c$),
    ($c$CHECK (((state = 'committed'::text) = (attempt_uuid IS NOT NULL)))$c$),
    ($c$CHECK (((image_bytes >= 16) AND (image_bytes <= 5242880)))$c$),
    ($c$CHECK (((image_height >= 1) AND (image_height <= 20000)))$c$),
    ($c$CHECK (((image_width >= 1) AND (image_width <= 20000)))$c$),
    ($c$CHECK ((image_sha256 ~ '^[a-f0-9]{64}$'::text))$c$),($c$CHECK ((request_hash ~ '^[a-f0-9]{64}$'::text))$c$),
    ($c$CHECK ((state = ANY (ARRAY['staged'::text, 'committed'::text, 'cleanup_pending'::text, 'abandoned'::text])))$c$),
    ($c$UNIQUE (object_path)$c$),($c$FOREIGN KEY (order_uuid) REFERENCES krikkit_commerce_orders(uuid)$c$),
    ($c$FOREIGN KEY (attempt_uuid) REFERENCES krikkit_payment_attempts(uuid)$c$),
    ($c$FOREIGN KEY (project_uuid, module_uuid) REFERENCES krikkit_manual_payments_installations(project_uuid, module_uuid)$c$)
  ) expected(definition) WHERE NOT EXISTS(SELECT 1 FROM pg_constraint c WHERE c.conrelid='public.krikkit_slip_uploads'::regclass AND c.convalidated AND pg_get_constraintdef(c.oid)=expected.definition)) THEN RAISE EXCEPTION 'managed_schema_incompatible'; END IF;
END $constraints$;
CREATE UNIQUE INDEX IF NOT EXISTS krikkit_slip_live_request ON public.krikkit_slip_uploads(project_uuid,module_uuid,request_key) WHERE state<>'abandoned';
CREATE INDEX IF NOT EXISTS krikkit_slip_cleanup_due ON public.krikkit_slip_uploads(project_uuid,module_uuid,state,lease_expires_at);
CREATE OR REPLACE FUNCTION public.krikkit_is_private_slip_bucket(p_bucket text) RETURNS boolean
LANGUAGE sql STABLE SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
  SELECT EXISTS(SELECT 1 FROM public.krikkit_manual_payments_installations i WHERE i.slip_bucket_id=p_bucket)
$fn$;
COMMENT ON FUNCTION public.krikkit_is_private_slip_bucket(text) IS 'krikkit-private-slips-v2';
DROP POLICY IF EXISTS krikkit_private_slips_guard ON storage.objects;
CREATE POLICY krikkit_private_slips_guard ON storage.objects AS RESTRICTIVE FOR ALL TO anon,authenticated
  USING(NOT public.krikkit_is_private_slip_bucket(bucket_id)) WITH CHECK(NOT public.krikkit_is_private_slip_bucket(bucket_id));
COMMENT ON POLICY krikkit_private_slips_guard ON storage.objects IS 'krikkit-private-slips-v2';
REVOKE ALL ON FUNCTION public.krikkit_is_private_slip_bucket(text) FROM PUBLIC;
GRANT EXECUTE ON FUNCTION public.krikkit_is_private_slip_bucket(text) TO anon,authenticated,service_role;
CREATE OR REPLACE FUNCTION public.krikkit_register_manual_payments(p_project_uuid uuid,p_module_uuid uuid)
RETURNS TABLE(module_uuid uuid,project_uuid uuid,schema_version integer)
LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
DECLARE installation public.krikkit_manual_payments_installations%ROWTYPE; bucket text:='krikkit-slips-'||p_module_uuid::text;
BEGIN
  IF p_project_uuid IS NULL OR p_module_uuid IS NULL THEN RAISE EXCEPTION 'invalid_binding'; END IF;
  PERFORM pg_advisory_xact_lock(hashtextextended('krikkit:bucket:'||p_module_uuid::text,0));
  SELECT i.* INTO installation FROM public.krikkit_manual_payments_installations i WHERE i.module_uuid=p_module_uuid FOR UPDATE;
  IF FOUND AND installation.project_uuid<>p_project_uuid THEN RAISE EXCEPTION 'binding_conflict'; END IF;
  IF installation.slip_bucket_id IS NULL THEN
    IF EXISTS(SELECT 1 FROM storage.buckets b WHERE b.id=bucket) THEN RAISE EXCEPTION 'private_bucket_collision'; END IF;
    INSERT INTO storage.buckets(id,name,public,file_size_limit,allowed_mime_types) VALUES(bucket,bucket,false,5242880,ARRAY['image/png']);
  ELSIF installation.slip_bucket_id<>bucket THEN RAISE EXCEPTION 'binding_conflict'; END IF;
  IF NOT EXISTS(SELECT 1 FROM storage.buckets b WHERE b.id=bucket AND b.name=bucket AND b.public=false AND b.file_size_limit=5242880 AND b.allowed_mime_types=ARRAY['image/png']) THEN RAISE EXCEPTION 'private_bucket_incompatible'; END IF;
  INSERT INTO public.krikkit_manual_payments_installations(module_uuid,project_uuid,schema_version,slip_bucket_id) VALUES(p_module_uuid,p_project_uuid,2,bucket)
    ON CONFLICT ON CONSTRAINT krikkit_manual_payments_installations_pkey DO UPDATE SET schema_version=2,slip_bucket_id=EXCLUDED.slip_bucket_id;
  INSERT INTO public.krikkit_payment_settings(module_uuid,project_uuid) VALUES(p_module_uuid,p_project_uuid) ON CONFLICT DO NOTHING;
  RETURN QUERY SELECT p_module_uuid,p_project_uuid,2;
END $fn$;
COMMENT ON FUNCTION public.krikkit_register_manual_payments(uuid,uuid) IS 'krikkit-managed-manual-payments-v1';
CREATE OR REPLACE FUNCTION public.krikkit_begin_slip_upload(p_project_uuid uuid,p_module_uuid uuid,p_order_uuid uuid,p_key uuid,p_hash text,p_lease uuid,p_image jsonb)
RETURNS SETOF public.krikkit_slip_uploads LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
DECLARE saved public.krikkit_slip_uploads%ROWTYPE; bucket text; ord public.krikkit_commerce_orders%ROWTYPE; object_id uuid;
BEGIN
  IF p_key IS NULL OR p_lease IS NULL OR p_hash IS NULL OR p_hash !~ '^[a-f0-9]{64}$' OR coalesce(p_image->>'sha256','') !~ '^[a-f0-9]{64}$' THEN RAISE EXCEPTION 'invalid_payment_slip'; END IF;
  SELECT i.slip_bucket_id INTO bucket FROM public.krikkit_manual_payments_installations i WHERE i.project_uuid=p_project_uuid AND i.module_uuid=p_module_uuid AND i.schema_version=2;
  IF bucket IS NULL THEN RAISE EXCEPTION 'module_not_installed'; END IF;
  PERFORM pg_advisory_xact_lock(hashtextextended(p_module_uuid::text||p_key::text,0));
  IF EXISTS(SELECT 1 FROM public.krikkit_slip_uploads u WHERE u.project_uuid=p_project_uuid AND u.module_uuid=p_module_uuid AND u.request_key=p_key AND (u.request_hash<>p_hash OR u.order_uuid<>p_order_uuid)) THEN RAISE EXCEPTION 'idempotency_conflict'; END IF;
  SELECT u.* INTO saved FROM public.krikkit_slip_uploads u WHERE u.project_uuid=p_project_uuid AND u.module_uuid=p_module_uuid AND u.request_key=p_key AND u.state<>'abandoned' FOR UPDATE;
  IF FOUND THEN
    IF saved.request_hash<>p_hash OR saved.order_uuid<>p_order_uuid THEN RAISE EXCEPTION 'idempotency_conflict'; END IF;
    IF saved.state='committed' THEN RETURN NEXT saved; RETURN; END IF;
    RAISE EXCEPTION 'upload_in_progress';
  END IF;
  SELECT o.* INTO ord FROM public.krikkit_commerce_orders o WHERE o.uuid=p_order_uuid AND o.project_uuid=p_project_uuid FOR UPDATE;
  IF NOT FOUND THEN RAISE EXCEPTION 'order_not_found'; END IF;
  IF to_jsonb(ord)->>'access_revoked_at' IS NOT NULL OR ord.created_at+interval '24 hours'<=now() THEN RAISE EXCEPTION 'order_not_found'; END IF;
  IF ord.status IN('paid','cancelled') THEN RAISE EXCEPTION 'invalid_transition'; END IF;
  object_id:=gen_random_uuid();
    INSERT INTO public.krikkit_slip_uploads(project_uuid,module_uuid,order_uuid,request_key,request_hash,bucket_id,object_path,image_sha256,image_bytes,image_width,image_height,lease_uuid,lease_expires_at)
    VALUES(p_project_uuid,p_module_uuid,p_order_uuid,p_key,p_hash,bucket,p_project_uuid::text||'/'||p_module_uuid::text||'/'||p_order_uuid::text||'/'||object_id::text||'.png',p_image->>'sha256',(p_image->>'bytes')::integer,(p_image->>'width')::integer,(p_image->>'height')::integer,p_lease,now()+interval '5 minutes') RETURNING * INTO saved;
  RETURN NEXT saved;
END $fn$;
COMMENT ON FUNCTION public.krikkit_begin_slip_upload(uuid,uuid,uuid,uuid,text,uuid,jsonb) IS 'krikkit-private-slips-v2';
CREATE OR REPLACE FUNCTION public.krikkit_commit_slip_upload(p_project_uuid uuid,p_module_uuid uuid,p_upload uuid,p_lease uuid,p_values jsonb)
RETURNS SETOF public.krikkit_slip_uploads LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
DECLARE saved public.krikkit_slip_uploads%ROWTYPE; attempt public.krikkit_payment_attempts%ROWTYPE; ord public.krikkit_commerce_orders%ROWTYPE; previous text;
BEGIN
  SELECT u.* INTO saved FROM public.krikkit_slip_uploads u WHERE u.project_uuid=p_project_uuid AND u.module_uuid=p_module_uuid AND u.uuid=p_upload FOR UPDATE;
  IF NOT FOUND THEN RAISE EXCEPTION 'attempt_not_found'; END IF;
  IF saved.state='committed' THEN RETURN NEXT saved; RETURN; END IF;
  IF saved.state<>'staged' OR saved.lease_uuid IS DISTINCT FROM p_lease OR saved.lease_expires_at<=now() THEN RAISE EXCEPTION 'upload_in_progress'; END IF;
  IF jsonb_typeof(p_values) IS DISTINCT FROM 'object' OR jsonb_typeof(p_values->'payer_name') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'payer_phone') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'amount_satang') IS DISTINCT FROM 'number' OR jsonb_typeof(p_values->'transfer_ref') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'claimed_transferred_at') IS DISTINCT FROM 'string' THEN RAISE EXCEPTION 'invalid_payment_slip'; END IF;
  SELECT o.* INTO ord FROM public.krikkit_commerce_orders o WHERE o.uuid=saved.order_uuid AND o.project_uuid=p_project_uuid FOR UPDATE;
  IF NOT FOUND THEN RAISE EXCEPTION 'order_not_found'; END IF;
  IF to_jsonb(ord)->>'access_revoked_at' IS NOT NULL OR ord.created_at+interval '24 hours'<=now() THEN RAISE EXCEPTION 'order_not_found'; END IF;
  IF ord.status IN('paid','cancelled') THEN RAISE EXCEPTION 'invalid_transition'; END IF;
  previous:=ord.status;
  INSERT INTO public.krikkit_payment_attempts(project_uuid,module_uuid,order_uuid,request_key,request_hash,payer_name,payer_phone,amount_satang,transfer_ref,claimed_transferred_at,slip_mime,slip_sha256,slip_bucket,slip_object,slip_bytes,slip_width,slip_height,status)
  VALUES(p_project_uuid,p_module_uuid,saved.order_uuid,saved.request_key,saved.request_hash,trim(p_values->>'payer_name'),trim(p_values->>'payer_phone'),(p_values->>'amount_satang')::integer,trim(p_values->>'transfer_ref'),trim(p_values->>'claimed_transferred_at'),'image/png',saved.image_sha256,saved.bucket_id,saved.object_path,saved.image_bytes,saved.image_width,saved.image_height,'submitted') RETURNING * INTO attempt;
  UPDATE public.krikkit_commerce_orders o SET status='pending_review',version=o.version+1,updated_at=now() WHERE o.uuid=ord.uuid RETURNING o.* INTO ord;
  INSERT INTO public.krikkit_commerce_order_events(project_uuid,module_uuid,order_uuid,previous_status,next_status,version) VALUES(p_project_uuid,ord.module_uuid,ord.uuid,previous,'pending_review',ord.version);
  INSERT INTO public.krikkit_payment_events(project_uuid,module_uuid,attempt_uuid,action,status,version) VALUES(p_project_uuid,p_module_uuid,attempt.uuid,'slip_submitted','submitted',1);
  UPDATE public.krikkit_slip_uploads u SET state='committed',attempt_uuid=attempt.uuid,updated_at=now() WHERE u.uuid=saved.uuid RETURNING u.* INTO saved;
  RETURN NEXT saved;
END $fn$;
COMMENT ON FUNCTION public.krikkit_commit_slip_upload(uuid,uuid,uuid,uuid,jsonb) IS 'krikkit-private-slips-v2';
CREATE OR REPLACE FUNCTION public.krikkit_claim_slip_cleanup(p_project_uuid uuid,p_module_uuid uuid,p_upload uuid,p_lease uuid,p_cleanup uuid)
RETURNS SETOF public.krikkit_slip_uploads LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
DECLARE saved public.krikkit_slip_uploads%ROWTYPE;
BEGIN
  SELECT u.* INTO saved FROM public.krikkit_slip_uploads u WHERE u.project_uuid=p_project_uuid AND u.module_uuid=p_module_uuid AND u.uuid=p_upload FOR UPDATE;
  IF NOT FOUND THEN RAISE EXCEPTION 'attempt_not_found'; END IF;
  IF saved.state='committed' THEN RETURN NEXT saved; RETURN; END IF;
  IF p_cleanup IS NULL OR (saved.lease_uuid IS DISTINCT FROM p_lease AND saved.lease_expires_at>now()) THEN RAISE EXCEPTION 'upload_in_progress'; END IF;
  IF EXISTS(SELECT 1 FROM public.krikkit_payment_attempts a WHERE a.project_uuid=p_project_uuid AND a.module_uuid=p_module_uuid AND a.slip_bucket=saved.bucket_id AND a.slip_object=saved.object_path) THEN RAISE EXCEPTION 'invalid_transition'; END IF;
  UPDATE public.krikkit_slip_uploads u SET state='cleanup_pending',lease_uuid=p_cleanup,lease_expires_at=now()+interval '2 minutes',updated_at=now() WHERE u.uuid=saved.uuid RETURNING u.* INTO saved;
  RETURN NEXT saved;
END $fn$;
COMMENT ON FUNCTION public.krikkit_claim_slip_cleanup(uuid,uuid,uuid,uuid,uuid) IS 'krikkit-private-slips-v2';
CREATE OR REPLACE FUNCTION public.krikkit_finish_slip_cleanup(p_project_uuid uuid,p_module_uuid uuid,p_upload uuid,p_lease uuid)
RETURNS TABLE(finished boolean) LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
BEGIN
  UPDATE public.krikkit_slip_uploads u SET state='abandoned',lease_expires_at=now()+interval '1 day',updated_at=now() WHERE u.project_uuid=p_project_uuid AND u.module_uuid=p_module_uuid AND u.uuid=p_upload AND u.state='cleanup_pending' AND u.lease_uuid=p_lease;
  IF NOT FOUND THEN RAISE EXCEPTION 'upload_in_progress'; END IF;
  RETURN QUERY SELECT true;
END $fn$;
COMMENT ON FUNCTION public.krikkit_finish_slip_cleanup(uuid,uuid,uuid,uuid) IS 'krikkit-private-slips-v2';
ALTER TABLE public.krikkit_slip_uploads ENABLE ROW LEVEL SECURITY;ALTER TABLE public.krikkit_slip_uploads FORCE ROW LEVEL SECURITY;
REVOKE ALL ON public.krikkit_slip_uploads FROM PUBLIC,anon,authenticated,service_role;
GRANT SELECT ON public.krikkit_slip_uploads TO service_role;
REVOKE ALL ON FUNCTION public.krikkit_begin_slip_upload(uuid,uuid,uuid,uuid,text,uuid,jsonb),public.krikkit_commit_slip_upload(uuid,uuid,uuid,uuid,jsonb),public.krikkit_claim_slip_cleanup(uuid,uuid,uuid,uuid,uuid),public.krikkit_finish_slip_cleanup(uuid,uuid,uuid,uuid) FROM PUBLIC,anon,authenticated;
GRANT EXECUTE ON FUNCTION public.krikkit_begin_slip_upload(uuid,uuid,uuid,uuid,text,uuid,jsonb),public.krikkit_commit_slip_upload(uuid,uuid,uuid,uuid,jsonb),public.krikkit_claim_slip_cleanup(uuid,uuid,uuid,uuid,uuid),public.krikkit_finish_slip_cleanup(uuid,uuid,uuid,uuid) TO service_role;
REVOKE EXECUTE ON FUNCTION public.krikkit_submit_payment_slip(uuid,uuid,uuid,uuid,text,jsonb) FROM PUBLIC,anon,authenticated,service_role;
