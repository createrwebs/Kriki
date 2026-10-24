BEGIN;
SELECT pg_advisory_xact_lock(hashtextextended('krikkit:managed:manual_payments:v1',0));
DO $guard$
DECLARE n text; object_id oid;
BEGIN
  FOREACH n IN ARRAY ARRAY['krikkit_manual_payments_installations','krikkit_payment_settings','krikkit_payment_attempts','krikkit_payment_events'] LOOP
    object_id:=to_regclass('public.'||n);
    IF object_id IS NOT NULL AND (coalesce(obj_description(object_id,'pg_class'),'')<>'krikkit-managed-manual-payments-v1' OR NOT EXISTS(SELECT 1 FROM pg_class WHERE oid=object_id AND relkind='r')) THEN RAISE EXCEPTION 'managed_schema_collision'; END IF;
  END LOOP;
  FOREACH n IN ARRAY ARRAY['public.krikkit_register_manual_payments(uuid,uuid)','public.krikkit_save_payment_settings(uuid,uuid,integer,jsonb,bigint)','public.krikkit_submit_payment_slip(uuid,uuid,uuid,uuid,text,jsonb)','public.krikkit_review_payment_slip(uuid,uuid,uuid,integer,text,text,bigint)'] LOOP
    object_id:=to_regprocedure(n);
    IF object_id IS NOT NULL AND coalesce(obj_description(object_id,'pg_proc'),'')<>'krikkit-managed-manual-payments-v1' THEN RAISE EXCEPTION 'managed_schema_collision'; END IF;
  END LOOP;
END $guard$;
CREATE TABLE IF NOT EXISTS public.krikkit_manual_payments_installations(
  module_uuid uuid PRIMARY KEY,project_uuid uuid NOT NULL,schema_version integer NOT NULL DEFAULT 1 CHECK(schema_version=1),created_at timestamptz NOT NULL DEFAULT now(),UNIQUE(project_uuid,module_uuid)
);
COMMENT ON TABLE public.krikkit_manual_payments_installations IS 'krikkit-managed-manual-payments-v1';
CREATE TABLE IF NOT EXISTS public.krikkit_payment_settings(
  module_uuid uuid PRIMARY KEY,project_uuid uuid NOT NULL,
  recipient_type text NOT NULL DEFAULT 'phone' CHECK(recipient_type IN('phone','national_id','ewallet')),
  recipient text NOT NULL DEFAULT '' CHECK(length(recipient)<=20),
  payee_label text NOT NULL DEFAULT '' CHECK(length(payee_label)<=160),
  instructions text NOT NULL DEFAULT '' CHECK(length(instructions)<=1000),
  enabled boolean NOT NULL DEFAULT false,
  version integer NOT NULL DEFAULT 1 CHECK(version>=1),
  updated_at timestamptz NOT NULL DEFAULT now(),
  FOREIGN KEY(project_uuid,module_uuid) REFERENCES public.krikkit_manual_payments_installations(project_uuid,module_uuid),
  UNIQUE(project_uuid,module_uuid)
);
COMMENT ON TABLE public.krikkit_payment_settings IS 'krikkit-managed-manual-payments-v1';
CREATE TABLE IF NOT EXISTS public.krikkit_payment_attempts(
  uuid uuid PRIMARY KEY DEFAULT gen_random_uuid(),project_uuid uuid NOT NULL,module_uuid uuid NOT NULL,
  order_uuid uuid NOT NULL REFERENCES public.krikkit_commerce_orders(uuid),
  request_key uuid NOT NULL,request_hash text NOT NULL CHECK(request_hash ~ '^[a-f0-9]{64}$'),
  payer_name text NOT NULL CHECK(length(payer_name) BETWEEN 1 AND 120),
  payer_phone text NOT NULL DEFAULT '' CHECK(length(payer_phone)<=40),
  amount_satang integer NOT NULL CHECK(amount_satang BETWEEN 100 AND 100000000),
  transfer_ref text NOT NULL DEFAULT '' CHECK(length(transfer_ref)<=120),
  claimed_transferred_at text NOT NULL DEFAULT '' CHECK(length(claimed_transferred_at)<=64),
  slip_mime text NOT NULL CHECK(slip_mime IN('image/png','image/jpeg','image/webp')),
  slip_sha256 text NOT NULL CHECK(slip_sha256 ~ '^[a-f0-9]{64}$'),
  slip_data_url text NOT NULL CHECK(length(slip_data_url) BETWEEN 32 AND 3000000),
  status text NOT NULL DEFAULT 'submitted' CHECK(status IN('submitted','accepted','rejected')),
  reviewer_id bigint,
  review_reason text NOT NULL DEFAULT '' CHECK(length(review_reason)<=500),
  verified_at timestamptz,
  version integer NOT NULL DEFAULT 1 CHECK(version>=1),
  uploaded_at timestamptz NOT NULL DEFAULT now(),updated_at timestamptz NOT NULL DEFAULT now(),
  FOREIGN KEY(project_uuid,module_uuid) REFERENCES public.krikkit_manual_payments_installations(project_uuid,module_uuid),
  UNIQUE(project_uuid,module_uuid,request_key)
);
COMMENT ON TABLE public.krikkit_payment_attempts IS 'krikkit-managed-manual-payments-v1';
CREATE INDEX IF NOT EXISTS krikkit_payment_attempts_order_idx ON public.krikkit_payment_attempts(project_uuid,module_uuid,order_uuid,uploaded_at DESC,uuid DESC);
CREATE TABLE IF NOT EXISTS public.krikkit_payment_events(
  uuid uuid PRIMARY KEY DEFAULT gen_random_uuid(),project_uuid uuid NOT NULL,module_uuid uuid NOT NULL,
  attempt_uuid uuid REFERENCES public.krikkit_payment_attempts(uuid),actor_id bigint,
  action text NOT NULL,status text NOT NULL,version integer NOT NULL,created_at timestamptz NOT NULL DEFAULT now()
);
COMMENT ON TABLE public.krikkit_payment_events IS 'krikkit-managed-manual-payments-v1';
CREATE OR REPLACE FUNCTION public.krikkit_register_manual_payments(p_project_uuid uuid,p_module_uuid uuid)
RETURNS TABLE(module_uuid uuid,project_uuid uuid,schema_version integer)
LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
BEGIN
  IF p_project_uuid IS NULL OR p_module_uuid IS NULL THEN RAISE EXCEPTION 'invalid_binding'; END IF;
  INSERT INTO public.krikkit_manual_payments_installations(module_uuid,project_uuid) VALUES(p_module_uuid,p_project_uuid) ON CONFLICT DO NOTHING;
  IF NOT EXISTS(SELECT 1 FROM public.krikkit_manual_payments_installations i WHERE i.module_uuid=p_module_uuid AND i.project_uuid=p_project_uuid) THEN RAISE EXCEPTION 'binding_conflict'; END IF;
  INSERT INTO public.krikkit_payment_settings(module_uuid,project_uuid) VALUES(p_module_uuid,p_project_uuid) ON CONFLICT DO NOTHING;
  RETURN QUERY SELECT i.module_uuid,i.project_uuid,i.schema_version FROM public.krikkit_manual_payments_installations i WHERE i.module_uuid=p_module_uuid AND i.project_uuid=p_project_uuid;
END $fn$;
COMMENT ON FUNCTION public.krikkit_register_manual_payments(uuid,uuid) IS 'krikkit-managed-manual-payments-v1';
CREATE OR REPLACE FUNCTION public.krikkit_save_payment_settings(p_project_uuid uuid,p_module_uuid uuid,p_expected_version integer,p_values jsonb,p_actor_id bigint)
RETURNS TABLE(module_uuid uuid,recipient_type text,recipient text,payee_label text,instructions text,enabled boolean,version integer)
LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
DECLARE saved public.krikkit_payment_settings%ROWTYPE;
BEGIN
  IF p_actor_id IS NULL OR p_actor_id<1 OR jsonb_typeof(p_values) IS DISTINCT FROM 'object'
    OR coalesce(p_values->>'recipient_type','') NOT IN('phone','national_id','ewallet')
    OR jsonb_typeof(p_values->'recipient') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'payee_label') IS DISTINCT FROM 'string'
    OR jsonb_typeof(p_values->'instructions') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'enabled') IS DISTINCT FROM 'boolean' THEN RAISE EXCEPTION 'invalid_payment_settings'; END IF;
  SELECT s.* INTO saved FROM public.krikkit_payment_settings s WHERE s.project_uuid=p_project_uuid AND s.module_uuid=p_module_uuid FOR UPDATE;
  IF NOT FOUND THEN RAISE EXCEPTION 'module_not_installed'; END IF;
  IF saved.version IS DISTINCT FROM p_expected_version THEN RAISE EXCEPTION 'version_conflict'; END IF;
  UPDATE public.krikkit_payment_settings s SET recipient_type=p_values->>'recipient_type',recipient=trim(p_values->>'recipient'),
    payee_label=trim(p_values->>'payee_label'),instructions=p_values->>'instructions',enabled=(p_values->>'enabled')::boolean,
    version=s.version+1,updated_at=now() WHERE s.module_uuid=saved.module_uuid RETURNING s.* INTO saved;
  INSERT INTO public.krikkit_payment_events(project_uuid,module_uuid,actor_id,action,status,version)
  VALUES(p_project_uuid,p_module_uuid,p_actor_id,'settings_updated',CASE WHEN saved.enabled THEN 'enabled' ELSE 'disabled' END,saved.version);
  RETURN QUERY SELECT saved.module_uuid,saved.recipient_type,saved.recipient,saved.payee_label,saved.instructions,saved.enabled,saved.version;
END $fn$;
COMMENT ON FUNCTION public.krikkit_save_payment_settings(uuid,uuid,integer,jsonb,bigint) IS 'krikkit-managed-manual-payments-v1';
CREATE OR REPLACE FUNCTION public.krikkit_submit_payment_slip(p_project_uuid uuid,p_module_uuid uuid,p_order_uuid uuid,p_key uuid,p_hash text,p_values jsonb)
RETURNS TABLE(attempt_uuid uuid,order_uuid uuid,status text,order_status text,version integer,replayed boolean)
LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
DECLARE saved public.krikkit_payment_attempts%ROWTYPE; ord public.krikkit_commerce_orders%ROWTYPE;
BEGIN
  IF NOT EXISTS(SELECT 1 FROM public.krikkit_manual_payments_installations i WHERE i.project_uuid=p_project_uuid AND i.module_uuid=p_module_uuid AND i.schema_version=1) THEN RAISE EXCEPTION 'module_not_installed'; END IF;
  IF p_key IS NULL OR p_hash IS NULL OR p_hash !~ '^[a-f0-9]{64}$' OR jsonb_typeof(p_values) IS DISTINCT FROM 'object'
    OR jsonb_typeof(p_values->'payer_name') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'payer_phone') IS DISTINCT FROM 'string'
    OR jsonb_typeof(p_values->'amount_satang') IS DISTINCT FROM 'number' OR jsonb_typeof(p_values->'transfer_ref') IS DISTINCT FROM 'string'
    OR jsonb_typeof(p_values->'claimed_transferred_at') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'slip_mime') IS DISTINCT FROM 'string'
    OR jsonb_typeof(p_values->'slip_sha256') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'slip_data_url') IS DISTINCT FROM 'string' THEN RAISE EXCEPTION 'invalid_payment_slip'; END IF;
  PERFORM pg_advisory_xact_lock(hashtextextended(p_module_uuid::text||p_key::text,0));
  SELECT a.* INTO saved FROM public.krikkit_payment_attempts a WHERE a.project_uuid=p_project_uuid AND a.module_uuid=p_module_uuid AND a.request_key=p_key;
  IF FOUND THEN
    IF saved.request_hash<>p_hash THEN RAISE EXCEPTION 'idempotency_conflict'; END IF;
    SELECT o.* INTO ord FROM public.krikkit_commerce_orders o WHERE o.uuid=saved.order_uuid;
    RETURN QUERY SELECT saved.uuid,saved.order_uuid,saved.status,ord.status,saved.version,true; RETURN;
  END IF;
  SELECT o.* INTO ord FROM public.krikkit_commerce_orders o WHERE o.uuid=p_order_uuid AND o.project_uuid=p_project_uuid FOR UPDATE;
  IF NOT FOUND THEN RAISE EXCEPTION 'order_not_found'; END IF;
  IF ord.status IN('paid','cancelled') THEN RAISE EXCEPTION 'invalid_transition'; END IF;
  INSERT INTO public.krikkit_payment_attempts(project_uuid,module_uuid,order_uuid,request_key,request_hash,payer_name,payer_phone,amount_satang,transfer_ref,claimed_transferred_at,slip_mime,slip_sha256,slip_data_url,status)
  VALUES(p_project_uuid,p_module_uuid,p_order_uuid,p_key,p_hash,trim(p_values->>'payer_name'),trim(p_values->>'payer_phone'),(p_values->>'amount_satang')::integer,trim(p_values->>'transfer_ref'),trim(p_values->>'claimed_transferred_at'),p_values->>'slip_mime',p_values->>'slip_sha256',p_values->>'slip_data_url','submitted') RETURNING * INTO saved;
  UPDATE public.krikkit_commerce_orders o SET status='pending_review',version=o.version+1,updated_at=now() WHERE o.uuid=ord.uuid RETURNING * INTO ord;
  INSERT INTO public.krikkit_commerce_order_events(project_uuid,module_uuid,order_uuid,previous_status,next_status,version) VALUES(p_project_uuid,ord.module_uuid,ord.uuid,ord.status,'pending_review',ord.version);
  INSERT INTO public.krikkit_payment_events(project_uuid,module_uuid,attempt_uuid,action,status,version) VALUES(p_project_uuid,p_module_uuid,saved.uuid,'slip_submitted','submitted',1);
  RETURN QUERY SELECT saved.uuid,saved.order_uuid,saved.status,ord.status,saved.version,false;
END $fn$;
COMMENT ON FUNCTION public.krikkit_submit_payment_slip(uuid,uuid,uuid,uuid,text,jsonb) IS 'krikkit-managed-manual-payments-v1';
CREATE OR REPLACE FUNCTION public.krikkit_review_payment_slip(p_project_uuid uuid,p_module_uuid uuid,p_attempt_uuid uuid,p_expected_version integer,p_decision text,p_reason text,p_actor_id bigint)
RETURNS TABLE(attempt_uuid uuid,order_uuid uuid,attempt_status text,order_status text,version integer)
LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
DECLARE saved public.krikkit_payment_attempts%ROWTYPE; ord public.krikkit_commerce_orders%ROWTYPE; next_ord_status text;
BEGIN
  IF p_actor_id IS NULL OR p_actor_id<1 OR p_decision NOT IN('accepted','rejected') OR p_reason IS NULL OR length(p_reason)>500 THEN RAISE EXCEPTION 'invalid_review'; END IF;
  SELECT a.* INTO saved FROM public.krikkit_payment_attempts a WHERE a.uuid=p_attempt_uuid AND a.project_uuid=p_project_uuid AND a.module_uuid=p_module_uuid FOR UPDATE;
  IF NOT FOUND THEN RAISE EXCEPTION 'attempt_not_found'; END IF;
  IF saved.version IS DISTINCT FROM p_expected_version THEN RAISE EXCEPTION 'version_conflict'; END IF;
  IF saved.status<>'submitted' THEN RAISE EXCEPTION 'invalid_transition'; END IF;
  SELECT o.* INTO ord FROM public.krikkit_commerce_orders o WHERE o.uuid=saved.order_uuid AND o.project_uuid=p_project_uuid FOR UPDATE;
  IF NOT FOUND THEN RAISE EXCEPTION 'order_not_found'; END IF;
  next_ord_status := CASE WHEN p_decision='accepted' THEN 'paid' ELSE 'rejected' END;
  UPDATE public.krikkit_payment_attempts a SET status=p_decision,reviewer_id=p_actor_id,review_reason=trim(p_reason),verified_at=now(),version=a.version+1,updated_at=now() WHERE a.uuid=saved.uuid RETURNING * INTO saved;
  UPDATE public.krikkit_commerce_orders o SET status=next_ord_status,version=o.version+1,updated_at=now() WHERE o.uuid=ord.uuid RETURNING * INTO ord;
  INSERT INTO public.krikkit_commerce_order_events(project_uuid,module_uuid,order_uuid,actor_id,previous_status,next_status,version) VALUES(p_project_uuid,ord.module_uuid,ord.uuid,p_actor_id,'pending_review',ord.status,ord.version);
  INSERT INTO public.krikkit_payment_events(project_uuid,module_uuid,attempt_uuid,actor_id,action,status,version) VALUES(p_project_uuid,p_module_uuid,saved.uuid,p_actor_id,'slip_reviewed',saved.status,saved.version);
  RETURN QUERY SELECT saved.uuid,saved.order_uuid,saved.status,ord.status,saved.version;
END $fn$;
COMMENT ON FUNCTION public.krikkit_review_payment_slip(uuid,uuid,uuid,integer,text,text,bigint) IS 'krikkit-managed-manual-payments-v1';
DO $compatibility$
BEGIN
  IF EXISTS(SELECT 1 FROM (VALUES
    ('krikkit_manual_payments_installations','module_uuid','uuid'),('krikkit_manual_payments_installations','project_uuid','uuid'),('krikkit_manual_payments_installations','schema_version','integer'),
    ('krikkit_payment_settings','module_uuid','uuid'),('krikkit_payment_settings','recipient_type','text'),('krikkit_payment_settings','recipient','text'),('krikkit_payment_settings','enabled','boolean'),('krikkit_payment_settings','version','integer'),
    ('krikkit_payment_attempts','uuid','uuid'),('krikkit_payment_attempts','order_uuid','uuid'),('krikkit_payment_attempts','amount_satang','integer'),('krikkit_payment_attempts','status','text'),('krikkit_payment_attempts','version','integer')
  ) e(t,c,type_name) LEFT JOIN pg_attribute a ON a.attrelid=to_regclass('public.'||e.t) AND a.attname=e.c AND NOT a.attisdropped WHERE a.attnum IS NULL OR NOT a.attnotnull OR format_type(a.atttypid,a.atttypmod)<>e.type_name)
  THEN RAISE EXCEPTION 'managed_schema_incompatible'; END IF;
END $compatibility$;
ALTER TABLE public.krikkit_manual_payments_installations ENABLE ROW LEVEL SECURITY;ALTER TABLE public.krikkit_manual_payments_installations FORCE ROW LEVEL SECURITY;
ALTER TABLE public.krikkit_payment_settings ENABLE ROW LEVEL SECURITY;ALTER TABLE public.krikkit_payment_settings FORCE ROW LEVEL SECURITY;
ALTER TABLE public.krikkit_payment_attempts ENABLE ROW LEVEL SECURITY;ALTER TABLE public.krikkit_payment_attempts FORCE ROW LEVEL SECURITY;
ALTER TABLE public.krikkit_payment_events ENABLE ROW LEVEL SECURITY;ALTER TABLE public.krikkit_payment_events FORCE ROW LEVEL SECURITY;
REVOKE ALL ON public.krikkit_manual_payments_installations,public.krikkit_payment_settings,public.krikkit_payment_attempts,public.krikkit_payment_events FROM PUBLIC,anon,authenticated,service_role;
REVOKE ALL ON FUNCTION public.krikkit_register_manual_payments(uuid,uuid),public.krikkit_save_payment_settings(uuid,uuid,integer,jsonb,bigint),public.krikkit_submit_payment_slip(uuid,uuid,uuid,uuid,text,jsonb),public.krikkit_review_payment_slip(uuid,uuid,uuid,integer,text,text,bigint) FROM PUBLIC,anon,authenticated;
GRANT SELECT ON public.krikkit_manual_payments_installations,public.krikkit_payment_settings,public.krikkit_payment_attempts,public.krikkit_payment_events TO service_role;
GRANT EXECUTE ON FUNCTION public.krikkit_register_manual_payments(uuid,uuid),public.krikkit_save_payment_settings(uuid,uuid,integer,jsonb,bigint),public.krikkit_submit_payment_slip(uuid,uuid,uuid,uuid,text,jsonb),public.krikkit_review_payment_slip(uuid,uuid,uuid,integer,text,text,bigint) TO service_role;
NOTIFY pgrst,'reload schema';
COMMIT;
SELECT 'manual_payments'::text AS krikkit_schema_key,1::integer AS schema_version,true AS verified;
