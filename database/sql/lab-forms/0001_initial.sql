BEGIN;
SELECT pg_advisory_xact_lock(hashtextextended('krikkit_form_schema_v1', 0));

CREATE TABLE IF NOT EXISTS public.krikkit_form_schema_versions (
    version integer PRIMARY KEY,
    installed_at timestamptz NOT NULL DEFAULT now()
);
DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM public.krikkit_form_schema_versions WHERE version = 1)
       AND (to_regclass('public.krikkit_form_submissions') IS NOT NULL
         OR to_regclass('public.krikkit_form_submission_events') IS NOT NULL) THEN
        RAISE EXCEPTION 'Krikkit form table collision; refusing to modify existing tables';
    END IF;
END $$;

CREATE TABLE IF NOT EXISTS public.krikkit_form_submissions (
    uuid uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    form_uuid uuid NOT NULL,
    definition_version integer NOT NULL CHECK (definition_version > 0),
    definition_snapshot jsonb NOT NULL,
    customer_name text NOT NULL,
    phone text NOT NULL,
    requested_at timestamptz NOT NULL,
    party_size integer NOT NULL CHECK (party_size BETWEEN 1 AND 20),
    notes text NOT NULL DEFAULT '',
    answers jsonb NOT NULL DEFAULT '{}'::jsonb,
    status text NOT NULL DEFAULT 'pending' CHECK (status IN ('pending','confirmed','cancelled')),
    idempotency_key text NOT NULL CHECK (length(idempotency_key) BETWEEN 1 AND 100),
    payload_hash text NOT NULL CHECK (payload_hash ~ '^[a-f0-9]{64}$'),
    created_at timestamptz NOT NULL DEFAULT now(),
    updated_at timestamptz NOT NULL DEFAULT now(),
    UNIQUE (form_uuid, idempotency_key),
    UNIQUE (form_uuid, uuid)
);
CREATE INDEX IF NOT EXISTS krikkit_form_submissions_list
ON public.krikkit_form_submissions (form_uuid, created_at DESC, uuid DESC);

CREATE TABLE IF NOT EXISTS public.krikkit_form_submission_events (
    id bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    submission_uuid uuid NOT NULL,
    form_uuid uuid NOT NULL,
    actor_id bigint,
    from_status text,
    to_status text NOT NULL,
    created_at timestamptz NOT NULL DEFAULT now(),
    FOREIGN KEY (form_uuid, submission_uuid)
        REFERENCES public.krikkit_form_submissions(form_uuid, uuid) ON DELETE RESTRICT
);

DO $$
BEGIN
    IF EXISTS (
        SELECT 1 FROM (VALUES
            ('krikkit_form_submissions','uuid','uuid'), ('krikkit_form_submissions','form_uuid','uuid'),
            ('krikkit_form_submissions','definition_version','integer'), ('krikkit_form_submissions','definition_snapshot','jsonb'),
            ('krikkit_form_submissions','customer_name','text'), ('krikkit_form_submissions','phone','text'),
            ('krikkit_form_submissions','requested_at','timestamp with time zone'), ('krikkit_form_submissions','party_size','integer'),
            ('krikkit_form_submissions','notes','text'), ('krikkit_form_submissions','answers','jsonb'),
            ('krikkit_form_submissions','status','text'), ('krikkit_form_submissions','idempotency_key','text'),
            ('krikkit_form_submissions','payload_hash','text'), ('krikkit_form_submissions','created_at','timestamp with time zone'),
            ('krikkit_form_submissions','updated_at','timestamp with time zone'),
            ('krikkit_form_submission_events','id','bigint'), ('krikkit_form_submission_events','submission_uuid','uuid'),
            ('krikkit_form_submission_events','form_uuid','uuid'), ('krikkit_form_submission_events','actor_id','bigint'),
            ('krikkit_form_submission_events','from_status','text'), ('krikkit_form_submission_events','to_status','text'),
            ('krikkit_form_submission_events','created_at','timestamp with time zone')
        ) AS expected(table_name,column_name,column_type)
        LEFT JOIN pg_catalog.pg_attribute a
          ON a.attrelid = to_regclass('public.' || expected.table_name)
         AND a.attname = expected.column_name AND NOT a.attisdropped
        WHERE a.attname IS NULL OR pg_catalog.format_type(a.atttypid, a.atttypmod) <> expected.column_type
    ) THEN
        RAISE EXCEPTION 'Incompatible Krikkit form schema; refusing installation';
    END IF;
END $$;

ALTER TABLE public.krikkit_form_schema_versions ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.krikkit_form_submissions ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.krikkit_form_submission_events ENABLE ROW LEVEL SECURITY;
REVOKE ALL ON public.krikkit_form_schema_versions, public.krikkit_form_submissions,
    public.krikkit_form_submission_events FROM PUBLIC, anon, authenticated;
GRANT SELECT ON public.krikkit_form_schema_versions, public.krikkit_form_submissions,
    public.krikkit_form_submission_events TO service_role;

CREATE OR REPLACE FUNCTION public.krikkit_submit_form(
    p_form_uuid uuid, p_definition_version integer, p_definition_snapshot jsonb,
    p_core jsonb, p_answers jsonb, p_key text, p_hash text
) RETURNS TABLE(submission_uuid uuid, status text, replayed boolean)
LANGUAGE plpgsql SECURITY DEFINER SET search_path = '' AS $$
DECLARE
    saved public.krikkit_form_submissions%ROWTYPE;
BEGIN
    PERFORM pg_advisory_xact_lock(hashtextextended(p_form_uuid::text || ':' || p_key, 0));
    SELECT * INTO saved FROM public.krikkit_form_submissions s
        WHERE s.form_uuid = p_form_uuid AND s.idempotency_key = p_key;
    IF FOUND THEN
        IF saved.payload_hash <> p_hash THEN
            RAISE EXCEPTION USING ERRCODE = 'P0001', MESSAGE = 'idempotency_conflict';
        END IF;
        RETURN QUERY SELECT saved.uuid, saved.status, true;
        RETURN;
    END IF;
    INSERT INTO public.krikkit_form_submissions
        (form_uuid, definition_version, definition_snapshot, customer_name, phone,
         requested_at, party_size, notes, answers, idempotency_key, payload_hash)
    VALUES (p_form_uuid, p_definition_version, p_definition_snapshot, p_core->>'customer_name',
        p_core->>'phone', (p_core->>'requested_at')::timestamptz, (p_core->>'party_size')::integer,
        coalesce(p_core->>'notes',''), p_answers, p_key, p_hash)
    RETURNING * INTO saved;
    INSERT INTO public.krikkit_form_submission_events(submission_uuid,form_uuid,to_status)
        VALUES (saved.uuid, saved.form_uuid, 'pending');
    RETURN QUERY SELECT saved.uuid, saved.status, false;
END $$;

CREATE OR REPLACE FUNCTION public.krikkit_transition_form(
    p_form_uuid uuid, p_submission_uuid uuid, p_expected_status text,
    p_next_status text, p_actor_id bigint
) RETURNS TABLE(submission_uuid uuid, status text)
LANGUAGE plpgsql SECURITY DEFINER SET search_path = '' AS $$
DECLARE
    saved public.krikkit_form_submissions%ROWTYPE;
BEGIN
    SELECT * INTO saved FROM public.krikkit_form_submissions s
        WHERE s.form_uuid = p_form_uuid AND s.uuid = p_submission_uuid FOR UPDATE;
    IF NOT FOUND THEN
        RAISE EXCEPTION USING ERRCODE = 'P0002', MESSAGE = 'submission_not_found';
    END IF;
    IF saved.status <> p_expected_status THEN
        RAISE EXCEPTION USING ERRCODE = 'P0001', MESSAGE = 'status_conflict';
    END IF;
    IF p_actor_id IS NULL OR p_actor_id < 1 OR NOT (
        (saved.status = 'pending' AND p_next_status IN ('confirmed','cancelled'))
        OR (saved.status = 'confirmed' AND p_next_status = 'cancelled')
    ) THEN
        RAISE EXCEPTION USING ERRCODE = 'P0001', MESSAGE = 'invalid_transition';
    END IF;
    UPDATE public.krikkit_form_submissions s SET status = p_next_status, updated_at = now()
        WHERE s.uuid = saved.uuid;
    INSERT INTO public.krikkit_form_submission_events
        (submission_uuid, form_uuid, actor_id, from_status, to_status)
        VALUES (saved.uuid, saved.form_uuid, p_actor_id, saved.status, p_next_status);
    RETURN QUERY SELECT saved.uuid, p_next_status;
END $$;

REVOKE ALL ON FUNCTION public.krikkit_submit_form(uuid,integer,jsonb,jsonb,jsonb,text,text) FROM PUBLIC, anon, authenticated;
REVOKE ALL ON FUNCTION public.krikkit_transition_form(uuid,uuid,text,text,bigint) FROM PUBLIC, anon, authenticated;
GRANT EXECUTE ON FUNCTION public.krikkit_submit_form(uuid,integer,jsonb,jsonb,jsonb,text,text) TO service_role;
GRANT EXECUTE ON FUNCTION public.krikkit_transition_form(uuid,uuid,text,text,bigint) TO service_role;
INSERT INTO public.krikkit_form_schema_versions(version) VALUES(1) ON CONFLICT DO NOTHING;
COMMIT;
