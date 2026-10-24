BEGIN;
SELECT pg_advisory_xact_lock(hashtextextended('krikkit:managed:news:v1',0));
DO $guard$
DECLARE n text; object_id oid;
BEGIN
  FOREACH n IN ARRAY ARRAY['krikkit_news_installations','krikkit_news_articles','krikkit_news_events'] LOOP
    object_id:=to_regclass('public.'||n);
    IF object_id IS NOT NULL AND (coalesce(obj_description(object_id,'pg_class'),'')<>'krikkit-managed-news-v1' OR NOT EXISTS(SELECT 1 FROM pg_class WHERE oid=object_id AND relkind='r')) THEN RAISE EXCEPTION 'managed_schema_collision'; END IF;
  END LOOP;
  FOREACH n IN ARRAY ARRAY['public.krikkit_register_news(uuid,uuid)','public.krikkit_create_news(uuid,uuid,uuid,text,jsonb,bigint)','public.krikkit_update_news(uuid,uuid,uuid,integer,jsonb,bigint)'] LOOP
    object_id:=to_regprocedure(n);
    IF object_id IS NOT NULL AND coalesce(obj_description(object_id,'pg_proc'),'')<>'krikkit-managed-news-v1' THEN RAISE EXCEPTION 'managed_schema_collision'; END IF;
  END LOOP;
END $guard$;
CREATE TABLE IF NOT EXISTS public.krikkit_news_installations(
  module_uuid uuid PRIMARY KEY,project_uuid uuid NOT NULL,schema_version integer NOT NULL DEFAULT 1 CHECK(schema_version=1),created_at timestamptz NOT NULL DEFAULT now(),UNIQUE(project_uuid,module_uuid)
);
COMMENT ON TABLE public.krikkit_news_installations IS 'krikkit-managed-news-v1';
CREATE TABLE IF NOT EXISTS public.krikkit_news_articles(
  uuid uuid PRIMARY KEY DEFAULT gen_random_uuid(),project_uuid uuid NOT NULL,module_uuid uuid NOT NULL,
  request_key uuid NOT NULL,request_hash text NOT NULL CHECK(request_hash ~ '^[a-f0-9]{64}$'),
  slug text NOT NULL CHECK(slug ~ '^[a-z0-9]+(-[a-z0-9]+)*$' AND length(slug) BETWEEN 1 AND 120),
  title text NOT NULL CHECK(length(title) BETWEEN 1 AND 200),excerpt text NOT NULL DEFAULT '' CHECK(length(excerpt)<=500),
  body text NOT NULL CHECK(length(body) BETWEEN 1 AND 20000),status text NOT NULL DEFAULT 'draft' CHECK(status IN('draft','published','archived')),
  version integer NOT NULL DEFAULT 1 CHECK(version>=1),published_at timestamptz,created_at timestamptz NOT NULL DEFAULT now(),updated_at timestamptz NOT NULL DEFAULT now(),
  FOREIGN KEY(project_uuid,module_uuid) REFERENCES public.krikkit_news_installations(project_uuid,module_uuid),
  UNIQUE(project_uuid,module_uuid,request_key),UNIQUE(project_uuid,module_uuid,slug)
);
COMMENT ON TABLE public.krikkit_news_articles IS 'krikkit-managed-news-v1';
CREATE INDEX IF NOT EXISTS krikkit_news_public_page ON public.krikkit_news_articles(project_uuid,module_uuid,status,published_at DESC,uuid DESC);
CREATE TABLE IF NOT EXISTS public.krikkit_news_events(
  uuid uuid PRIMARY KEY DEFAULT gen_random_uuid(),project_uuid uuid NOT NULL,module_uuid uuid NOT NULL,
  article_uuid uuid NOT NULL REFERENCES public.krikkit_news_articles(uuid),actor_id bigint NOT NULL,
  previous_status text,next_status text NOT NULL,version integer NOT NULL,created_at timestamptz NOT NULL DEFAULT now()
);
COMMENT ON TABLE public.krikkit_news_events IS 'krikkit-managed-news-v1';
CREATE OR REPLACE FUNCTION public.krikkit_register_news(p_project_uuid uuid,p_module_uuid uuid)
RETURNS TABLE(module_uuid uuid,project_uuid uuid,schema_version integer)
LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
BEGIN
  IF p_project_uuid IS NULL OR p_module_uuid IS NULL THEN RAISE EXCEPTION 'invalid_binding'; END IF;
  INSERT INTO public.krikkit_news_installations(module_uuid,project_uuid) VALUES(p_module_uuid,p_project_uuid) ON CONFLICT DO NOTHING;
  IF NOT EXISTS(SELECT 1 FROM public.krikkit_news_installations i WHERE i.module_uuid=p_module_uuid AND i.project_uuid=p_project_uuid) THEN RAISE EXCEPTION 'binding_conflict'; END IF;
  RETURN QUERY SELECT i.module_uuid,i.project_uuid,i.schema_version FROM public.krikkit_news_installations i WHERE i.module_uuid=p_module_uuid AND i.project_uuid=p_project_uuid;
END $fn$;
COMMENT ON FUNCTION public.krikkit_register_news(uuid,uuid) IS 'krikkit-managed-news-v1';
CREATE OR REPLACE FUNCTION public.krikkit_create_news(p_project_uuid uuid,p_module_uuid uuid,p_key uuid,p_hash text,p_values jsonb,p_actor_id bigint)
RETURNS TABLE(article_uuid uuid,status text,version integer,replayed boolean)
LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
DECLARE saved public.krikkit_news_articles%ROWTYPE;
BEGIN
  IF NOT EXISTS(SELECT 1 FROM public.krikkit_news_installations i WHERE i.project_uuid=p_project_uuid AND i.module_uuid=p_module_uuid AND i.schema_version=1) THEN RAISE EXCEPTION 'module_not_installed'; END IF;
  IF p_key IS NULL OR p_hash IS NULL OR p_hash !~ '^[a-f0-9]{64}$' OR p_actor_id IS NULL OR p_actor_id<1 OR jsonb_typeof(p_values) IS DISTINCT FROM 'object'
    OR EXISTS(SELECT 1 FROM jsonb_object_keys(p_values) k WHERE k NOT IN('slug','title','excerpt','body','status'))
    OR jsonb_typeof(p_values->'slug') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'title') IS DISTINCT FROM 'string'
    OR jsonb_typeof(p_values->'excerpt') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'body') IS DISTINCT FROM 'string'
    OR coalesce(p_values->>'status','') NOT IN('draft','published') THEN RAISE EXCEPTION 'invalid_article'; END IF;
  PERFORM pg_advisory_xact_lock(hashtextextended(p_module_uuid::text||p_key::text,0));
  SELECT a.* INTO saved FROM public.krikkit_news_articles a WHERE a.project_uuid=p_project_uuid AND a.module_uuid=p_module_uuid AND a.request_key=p_key;
  IF FOUND THEN
    IF saved.request_hash<>p_hash THEN RAISE EXCEPTION 'idempotency_conflict'; END IF;
    RETURN QUERY SELECT saved.uuid,saved.status,saved.version,true; RETURN;
  END IF;
  IF EXISTS(SELECT 1 FROM public.krikkit_news_articles a WHERE a.project_uuid=p_project_uuid AND a.module_uuid=p_module_uuid AND a.slug=p_values->>'slug') THEN RAISE EXCEPTION 'slug_conflict'; END IF;
  INSERT INTO public.krikkit_news_articles(project_uuid,module_uuid,request_key,request_hash,slug,title,excerpt,body,status,published_at)
  VALUES(p_project_uuid,p_module_uuid,p_key,p_hash,p_values->>'slug',trim(p_values->>'title'),p_values->>'excerpt',trim(p_values->>'body'),p_values->>'status',CASE WHEN p_values->>'status'='published' THEN now() ELSE NULL END) RETURNING * INTO saved;
  INSERT INTO public.krikkit_news_events(project_uuid,module_uuid,article_uuid,actor_id,next_status,version) VALUES(p_project_uuid,p_module_uuid,saved.uuid,p_actor_id,saved.status,1);
  RETURN QUERY SELECT saved.uuid,saved.status,saved.version,false;
END $fn$;
COMMENT ON FUNCTION public.krikkit_create_news(uuid,uuid,uuid,text,jsonb,bigint) IS 'krikkit-managed-news-v1';
CREATE OR REPLACE FUNCTION public.krikkit_update_news(p_project_uuid uuid,p_module_uuid uuid,p_article_uuid uuid,p_expected_version integer,p_values jsonb,p_actor_id bigint)
RETURNS TABLE(article_uuid uuid,status text,version integer)
LANGUAGE plpgsql SECURITY DEFINER SET search_path=pg_catalog,public AS $fn$
DECLARE saved public.krikkit_news_articles%ROWTYPE; previous text;
BEGIN
  IF p_actor_id IS NULL OR p_actor_id<1 OR jsonb_typeof(p_values) IS DISTINCT FROM 'object'
    OR EXISTS(SELECT 1 FROM jsonb_object_keys(p_values) k WHERE k NOT IN('slug','title','excerpt','body','status'))
    OR jsonb_typeof(p_values->'slug') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'title') IS DISTINCT FROM 'string'
    OR jsonb_typeof(p_values->'excerpt') IS DISTINCT FROM 'string' OR jsonb_typeof(p_values->'body') IS DISTINCT FROM 'string'
    OR coalesce(p_values->>'status','') NOT IN('draft','published','archived') THEN RAISE EXCEPTION 'invalid_article'; END IF;
  SELECT a.* INTO saved FROM public.krikkit_news_articles a WHERE a.uuid=p_article_uuid AND a.project_uuid=p_project_uuid AND a.module_uuid=p_module_uuid FOR UPDATE;
  IF NOT FOUND THEN RAISE EXCEPTION 'article_not_found'; END IF;
  IF saved.version IS DISTINCT FROM p_expected_version THEN RAISE EXCEPTION 'version_conflict'; END IF;
  IF saved.slug<>p_values->>'slug' THEN RAISE EXCEPTION 'slug_immutable'; END IF;
  previous:=saved.status;
  UPDATE public.krikkit_news_articles a SET title=trim(p_values->>'title'),excerpt=p_values->>'excerpt',body=trim(p_values->>'body'),status=p_values->>'status',version=a.version+1,
    published_at=CASE WHEN p_values->>'status'='published' THEN coalesce(a.published_at,now()) ELSE a.published_at END,updated_at=now() WHERE a.uuid=saved.uuid RETURNING a.* INTO saved;
  INSERT INTO public.krikkit_news_events(project_uuid,module_uuid,article_uuid,actor_id,previous_status,next_status,version) VALUES(p_project_uuid,p_module_uuid,saved.uuid,p_actor_id,previous,saved.status,saved.version);
  RETURN QUERY SELECT saved.uuid,saved.status,saved.version;
END $fn$;
COMMENT ON FUNCTION public.krikkit_update_news(uuid,uuid,uuid,integer,jsonb,bigint) IS 'krikkit-managed-news-v1';
DO $compatibility$
BEGIN
  IF EXISTS(SELECT 1 FROM (VALUES
    ('krikkit_news_installations','module_uuid','uuid'),('krikkit_news_installations','project_uuid','uuid'),('krikkit_news_installations','schema_version','integer'),
    ('krikkit_news_articles','uuid','uuid'),('krikkit_news_articles','project_uuid','uuid'),('krikkit_news_articles','module_uuid','uuid'),('krikkit_news_articles','request_key','uuid'),
    ('krikkit_news_articles','request_hash','text'),('krikkit_news_articles','slug','text'),('krikkit_news_articles','title','text'),('krikkit_news_articles','excerpt','text'),('krikkit_news_articles','body','text'),('krikkit_news_articles','status','text'),('krikkit_news_articles','version','integer'),
    ('krikkit_news_articles','created_at','timestamp with time zone'),('krikkit_news_articles','updated_at','timestamp with time zone'),
    ('krikkit_news_events','uuid','uuid'),('krikkit_news_events','project_uuid','uuid'),('krikkit_news_events','module_uuid','uuid'),('krikkit_news_events','article_uuid','uuid'),('krikkit_news_events','actor_id','bigint'),('krikkit_news_events','next_status','text'),('krikkit_news_events','version','integer')
  ) e(t,c,type_name) LEFT JOIN pg_attribute a ON a.attrelid=to_regclass('public.'||e.t) AND a.attname=e.c AND NOT a.attisdropped WHERE a.attnum IS NULL OR NOT a.attnotnull OR format_type(a.atttypid,a.atttypmod)<>e.type_name)
  OR NOT EXISTS(SELECT 1 FROM pg_constraint WHERE conrelid='public.krikkit_news_articles'::regclass AND contype='u' AND pg_get_constraintdef(oid)='UNIQUE (project_uuid, module_uuid, request_key)')
  OR NOT EXISTS(SELECT 1 FROM pg_constraint WHERE conrelid='public.krikkit_news_articles'::regclass AND contype='u' AND pg_get_constraintdef(oid)='UNIQUE (project_uuid, module_uuid, slug)')
  THEN RAISE EXCEPTION 'managed_schema_incompatible'; END IF;
END $compatibility$;
ALTER TABLE public.krikkit_news_installations ENABLE ROW LEVEL SECURITY;ALTER TABLE public.krikkit_news_installations FORCE ROW LEVEL SECURITY;
ALTER TABLE public.krikkit_news_articles ENABLE ROW LEVEL SECURITY;ALTER TABLE public.krikkit_news_articles FORCE ROW LEVEL SECURITY;
ALTER TABLE public.krikkit_news_events ENABLE ROW LEVEL SECURITY;ALTER TABLE public.krikkit_news_events FORCE ROW LEVEL SECURITY;
REVOKE ALL ON public.krikkit_news_installations,public.krikkit_news_articles,public.krikkit_news_events FROM PUBLIC,anon,authenticated,service_role;
REVOKE ALL ON FUNCTION public.krikkit_register_news(uuid,uuid),public.krikkit_create_news(uuid,uuid,uuid,text,jsonb,bigint),public.krikkit_update_news(uuid,uuid,uuid,integer,jsonb,bigint) FROM PUBLIC,anon,authenticated;
GRANT SELECT ON public.krikkit_news_installations,public.krikkit_news_articles,public.krikkit_news_events TO service_role;
GRANT EXECUTE ON FUNCTION public.krikkit_register_news(uuid,uuid),public.krikkit_create_news(uuid,uuid,uuid,text,jsonb,bigint),public.krikkit_update_news(uuid,uuid,uuid,integer,jsonb,bigint) TO service_role;
NOTIFY pgrst,'reload schema';
COMMIT;
SELECT 'news'::text AS krikkit_schema_key,1::integer AS schema_version,true AS verified;
