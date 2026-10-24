BEGIN;
SELECT pg_advisory_xact_lock(hashtextextended('krikkit:managed:contact:v1', 0));
DO $guard$
DECLARE object_name text; object_id oid;
BEGIN
  FOREACH object_name IN ARRAY ARRAY['krikkit_contact_installations','krikkit_contact_messages','krikkit_contact_events'] LOOP
    object_id := to_regclass('public.' || object_name);
    IF object_id IS NOT NULL AND (coalesce(obj_description(object_id, 'pg_class'), '') <> 'krikkit-managed-contact-v1'
      OR NOT EXISTS (SELECT 1 FROM pg_class WHERE oid = object_id AND relkind = 'r')) THEN
      RAISE EXCEPTION 'managed_schema_collision';
    END IF;
  END LOOP;
  FOREACH object_name IN ARRAY ARRAY[
    'public.krikkit_register_contact(uuid,uuid)',
    'public.krikkit_submit_contact(uuid,uuid,uuid,text,jsonb)',
    'public.krikkit_transition_contact(uuid,uuid,uuid,integer,text,bigint)'
  ] LOOP
    object_id := to_regprocedure(object_name);
    IF object_id IS NOT NULL AND coalesce(obj_description(object_id, 'pg_proc'), '') <> 'krikkit-managed-contact-v1' THEN
      RAISE EXCEPTION 'managed_schema_collision';
    END IF;
  END LOOP;
END $guard$;

CREATE TABLE IF NOT EXISTS public.krikkit_contact_installations (
  module_uuid uuid PRIMARY KEY,
  project_uuid uuid NOT NULL,
  schema_version integer NOT NULL DEFAULT 1 CHECK (schema_version = 1),
  created_at timestamptz NOT NULL DEFAULT now(),
  UNIQUE (project_uuid,module_uuid)
);
COMMENT ON TABLE public.krikkit_contact_installations IS 'krikkit-managed-contact-v1';
CREATE TABLE IF NOT EXISTS public.krikkit_contact_messages (
  uuid uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  project_uuid uuid NOT NULL,
  module_uuid uuid NOT NULL,
  request_key uuid NOT NULL,
  request_hash text NOT NULL CHECK (request_hash ~ '^[a-f0-9]{64}$'),
  customer_name text NOT NULL CHECK (length(customer_name) BETWEEN 1 AND 120),
  email text NOT NULL CHECK (length(email) BETWEEN 3 AND 254),
  phone text NOT NULL DEFAULT '' CHECK (length(phone) <= 40),
  subject text NOT NULL DEFAULT '' CHECK (length(subject) <= 180),
  message text NOT NULL CHECK (length(message) BETWEEN 1 AND 5000),
  consent boolean NOT NULL CHECK (consent = true),
  status text NOT NULL DEFAULT 'new' CHECK (status IN ('new','read','archived')),
  version integer NOT NULL DEFAULT 1 CHECK (version >= 1),
  created_at timestamptz NOT NULL DEFAULT now(),
  updated_at timestamptz NOT NULL DEFAULT now(),
  FOREIGN KEY (project_uuid,module_uuid) REFERENCES public.krikkit_contact_installations(project_uuid,module_uuid),
  UNIQUE (project_uuid,module_uuid,request_key)
);
COMMENT ON TABLE public.krikkit_contact_messages IS 'krikkit-managed-contact-v1';
CREATE INDEX IF NOT EXISTS krikkit_contact_messages_page ON public.krikkit_contact_messages(project_uuid,module_uuid,created_at DESC,uuid DESC);
CREATE TABLE IF NOT EXISTS public.krikkit_contact_events (
  uuid uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  project_uuid uuid NOT NULL,
  module_uuid uuid NOT NULL,
  message_uuid uuid NOT NULL REFERENCES public.krikkit_contact_messages(uuid),
  actor_id bigint NOT NULL,
  previous_status text NOT NULL,
  next_status text NOT NULL,
  version integer NOT NULL,
  created_at timestamptz NOT NULL DEFAULT now()
);
COMMENT ON TABLE public.krikkit_contact_events IS 'krikkit-managed-contact-v1';

CREATE OR REPLACE FUNCTION public.krikkit_register_contact(p_project_uuid uuid,p_module_uuid uuid)
RETURNS TABLE(module_uuid uuid,project_uuid uuid,schema_version integer)
LANGUAGE plpgsql SECURITY DEFINER SET search_path = pg_catalog,public AS $fn$
BEGIN
  IF p_project_uuid IS NULL OR p_module_uuid IS NULL THEN RAISE EXCEPTION 'invalid_binding'; END IF;
  INSERT INTO public.krikkit_contact_installations(module_uuid,project_uuid) VALUES(p_module_uuid,p_project_uuid) ON CONFLICT DO NOTHING;
  IF NOT EXISTS (SELECT 1 FROM public.krikkit_contact_installations i WHERE i.module_uuid=p_module_uuid AND i.project_uuid=p_project_uuid) THEN RAISE EXCEPTION 'binding_conflict'; END IF;
  RETURN QUERY SELECT i.module_uuid,i.project_uuid,i.schema_version FROM public.krikkit_contact_installations i WHERE i.module_uuid=p_module_uuid AND i.project_uuid=p_project_uuid;
END $fn$;
COMMENT ON FUNCTION public.krikkit_register_contact(uuid,uuid) IS 'krikkit-managed-contact-v1';

CREATE OR REPLACE FUNCTION public.krikkit_submit_contact(p_project_uuid uuid,p_module_uuid uuid,p_key uuid,p_hash text,p_values jsonb)
RETURNS TABLE(submission_uuid uuid,status text,version integer,replayed boolean)
LANGUAGE plpgsql SECURITY DEFINER SET search_path = pg_catalog,public AS $fn$
DECLARE saved public.krikkit_contact_messages%ROWTYPE;
BEGIN
  IF NOT EXISTS (SELECT 1 FROM public.krikkit_contact_installations i WHERE i.project_uuid=p_project_uuid AND i.module_uuid=p_module_uuid AND i.schema_version=1) THEN RAISE EXCEPTION 'module_not_installed'; END IF;
  IF p_key IS NULL OR p_hash IS NULL OR p_hash !~ '^[a-f0-9]{64}$' OR jsonb_typeof(p_values) IS DISTINCT FROM 'object'
    OR EXISTS (SELECT 1 FROM jsonb_object_keys(p_values) k WHERE k NOT IN ('name','email','phone','subject','message','consent'))
    OR p_values->'consent' IS DISTINCT FROM 'true'::jsonb
    OR jsonb_typeof(p_values->'name') IS DISTINCT FROM 'string'
    OR jsonb_typeof(p_values->'email') IS DISTINCT FROM 'string'
    OR jsonb_typeof(p_values->'message') IS DISTINCT FROM 'string'
    OR length(trim(p_values->>'name')) NOT BETWEEN 1 AND 120
    OR length(p_values->>'email') NOT BETWEEN 3 AND 254
    OR position('@' IN p_values->>'email') < 2
    OR length(trim(p_values->>'message')) NOT BETWEEN 1 AND 5000
    OR (p_values ? 'phone' AND jsonb_typeof(p_values->'phone') <> 'string')
    OR (p_values ? 'subject' AND jsonb_typeof(p_values->'subject') <> 'string') THEN RAISE EXCEPTION 'invalid_contact'; END IF;
  PERFORM pg_advisory_xact_lock(hashtextextended(p_module_uuid::text || p_key::text,0));
  SELECT m.* INTO saved FROM public.krikkit_contact_messages m WHERE m.project_uuid=p_project_uuid AND m.module_uuid=p_module_uuid AND m.request_key=p_key;
  IF FOUND THEN
    IF saved.request_hash <> p_hash THEN RAISE EXCEPTION 'idempotency_conflict'; END IF;
    RETURN QUERY SELECT saved.uuid,saved.status,saved.version,true; RETURN;
  END IF;
  INSERT INTO public.krikkit_contact_messages(project_uuid,module_uuid,request_key,request_hash,customer_name,email,phone,subject,message,consent)
  VALUES(p_project_uuid,p_module_uuid,p_key,p_hash,trim(p_values->>'name'),p_values->>'email',coalesce(p_values->>'phone',''),coalesce(p_values->>'subject',''),trim(p_values->>'message'),true) RETURNING * INTO saved;
  RETURN QUERY SELECT saved.uuid,saved.status,saved.version,false;
END $fn$;
COMMENT ON FUNCTION public.krikkit_submit_contact(uuid,uuid,uuid,text,jsonb) IS 'krikkit-managed-contact-v1';

CREATE OR REPLACE FUNCTION public.krikkit_transition_contact(p_project_uuid uuid,p_module_uuid uuid,p_message_uuid uuid,p_expected_version integer,p_status text,p_actor_id bigint)
RETURNS TABLE(submission_uuid uuid,status text,version integer)
LANGUAGE plpgsql SECURITY DEFINER SET search_path = pg_catalog,public AS $fn$
DECLARE saved public.krikkit_contact_messages%ROWTYPE; old_status text;
BEGIN
  IF p_status IS NULL OR p_status NOT IN ('new','read','archived') OR p_actor_id IS NULL OR p_actor_id < 1 THEN RAISE EXCEPTION 'invalid_transition'; END IF;
  SELECT m.* INTO saved FROM public.krikkit_contact_messages m WHERE m.uuid=p_message_uuid AND m.project_uuid=p_project_uuid AND m.module_uuid=p_module_uuid FOR UPDATE;
  IF NOT FOUND THEN RAISE EXCEPTION 'contact_not_found'; END IF;
  IF saved.version IS DISTINCT FROM p_expected_version THEN RAISE EXCEPTION 'version_conflict'; END IF;
  IF saved.status=p_status THEN RETURN QUERY SELECT saved.uuid,saved.status,saved.version; RETURN; END IF;
  old_status := saved.status;
  UPDATE public.krikkit_contact_messages m SET status=p_status,version=m.version+1,updated_at=now() WHERE m.uuid=saved.uuid RETURNING m.* INTO saved;
  INSERT INTO public.krikkit_contact_events(project_uuid,module_uuid,message_uuid,actor_id,previous_status,next_status,version)
  VALUES(p_project_uuid,p_module_uuid,saved.uuid,p_actor_id,old_status,saved.status,saved.version);
  RETURN QUERY SELECT saved.uuid,saved.status,saved.version;
END $fn$;
COMMENT ON FUNCTION public.krikkit_transition_contact(uuid,uuid,uuid,integer,text,bigint) IS 'krikkit-managed-contact-v1';

DO $compatibility$
BEGIN
  IF EXISTS (
    SELECT 1 FROM (VALUES
      ('krikkit_contact_installations','module_uuid','uuid'),('krikkit_contact_installations','project_uuid','uuid'),('krikkit_contact_installations','schema_version','integer'),
      ('krikkit_contact_messages','uuid','uuid'),('krikkit_contact_messages','project_uuid','uuid'),('krikkit_contact_messages','module_uuid','uuid'),
      ('krikkit_contact_messages','request_key','uuid'),('krikkit_contact_messages','request_hash','text'),('krikkit_contact_messages','customer_name','text'),
      ('krikkit_contact_messages','email','text'),('krikkit_contact_messages','phone','text'),('krikkit_contact_messages','subject','text'),
      ('krikkit_contact_messages','message','text'),('krikkit_contact_messages','consent','boolean'),('krikkit_contact_messages','status','text'),
      ('krikkit_contact_messages','version','integer'),('krikkit_contact_messages','created_at','timestamp with time zone'),('krikkit_contact_messages','updated_at','timestamp with time zone'),
      ('krikkit_contact_events','uuid','uuid'),('krikkit_contact_events','project_uuid','uuid'),('krikkit_contact_events','module_uuid','uuid'),
      ('krikkit_contact_events','message_uuid','uuid'),('krikkit_contact_events','actor_id','bigint'),('krikkit_contact_events','previous_status','text'),
      ('krikkit_contact_events','next_status','text'),('krikkit_contact_events','version','integer'),('krikkit_contact_events','created_at','timestamp with time zone')
    ) expected(table_name,column_name,type_name)
    LEFT JOIN pg_attribute a ON a.attrelid=to_regclass('public.'||expected.table_name) AND a.attname=expected.column_name AND NOT a.attisdropped
    WHERE a.attnum IS NULL OR NOT a.attnotnull OR format_type(a.atttypid,a.atttypmod)<>expected.type_name
  ) OR NOT EXISTS (
    SELECT 1 FROM pg_constraint WHERE conrelid='public.krikkit_contact_messages'::regclass AND contype='u' AND pg_get_constraintdef(oid)='UNIQUE (project_uuid, module_uuid, request_key)'
  ) OR NOT EXISTS (
    SELECT 1 FROM pg_constraint WHERE conrelid='public.krikkit_contact_installations'::regclass AND contype='u' AND pg_get_constraintdef(oid)='UNIQUE (project_uuid, module_uuid)'
  ) THEN RAISE EXCEPTION 'managed_schema_incompatible'; END IF;
END $compatibility$;

ALTER TABLE public.krikkit_contact_installations ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.krikkit_contact_installations FORCE ROW LEVEL SECURITY;
ALTER TABLE public.krikkit_contact_messages ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.krikkit_contact_messages FORCE ROW LEVEL SECURITY;
ALTER TABLE public.krikkit_contact_events ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.krikkit_contact_events FORCE ROW LEVEL SECURITY;
REVOKE ALL ON public.krikkit_contact_installations,public.krikkit_contact_messages,public.krikkit_contact_events FROM PUBLIC,anon,authenticated,service_role;
REVOKE ALL ON FUNCTION public.krikkit_register_contact(uuid,uuid),public.krikkit_submit_contact(uuid,uuid,uuid,text,jsonb),public.krikkit_transition_contact(uuid,uuid,uuid,integer,text,bigint) FROM PUBLIC,anon,authenticated;
GRANT SELECT ON public.krikkit_contact_installations,public.krikkit_contact_messages,public.krikkit_contact_events TO service_role;
GRANT EXECUTE ON FUNCTION public.krikkit_register_contact(uuid,uuid),public.krikkit_submit_contact(uuid,uuid,uuid,text,jsonb),public.krikkit_transition_contact(uuid,uuid,uuid,integer,text,bigint) TO service_role;
NOTIFY pgrst,'reload schema';
COMMIT;
SELECT 'contact'::text AS krikkit_schema_key,1::integer AS schema_version,true AS verified;
