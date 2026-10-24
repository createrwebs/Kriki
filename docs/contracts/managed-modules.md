# Managed module control contract

Status: implementation in progress; business data/commerce readiness is not yet enabled.

Authenticated verified owner endpoints under `/lab/{project}/modules` use session+CSRF, strict JSON and private/no-store responses. Project context comes from route binding and ownership, never body owner/project fields.

- `GET /`: catalog, effective capability selection, project revision and installation metadata; readable when frozen or downgraded. No Supabase tokens or customer records.
- `PUT /capabilities/{key}`: `{enabled:boolean,expected_revision:integer>=0}`; corresponding account plan feature required, owner project writable. Unknown keys422, denied403/402, stale409. Every state change increments project module revision. No automatic plan grants.
- `POST /`: `{key,expected_revision,config:{},idempotency_key:UUID}`; prepares one installation per project/key with selected Supabase ref. Dependencies must already be ready/enabled. Exact key/hash retry returns200 existing installation; new201. Different request with same idempotency or existing key409. No schema write or activation.
- `PATCH /{installation}/state`: `{state:enabled|disabled,expected_revision:integer>=1}`; exact project installation binding. Enable requires verified schema ready and dependencies. Disable retains all records/history.

Installation fields: uuid,key,revision,schema_version,status(prepared|ready|failed),enabled. Business data remains in account-selected pinned Supabase, with module+project predicates. Ref changes require reconnecting the original project; no automatic rebind.

New account feature codes `lab_module_{key}` are default-denied and appear in the existing entitlement catalog for explicit operator plan configuration. Existing booking authorization is retained through its original API; enabling commerce is never a side effect of booking adoption.

New business writes additionally require server readiness gate after S1–S4 evidence. Hiding a button is not authorization. External schema Apply will be a separate concrete server-issued proposal; client booleans cannot approve SQL.

Schema approval endpoints under the same owner prefix:

- `POST /{installation}/proposals` accepts only `{expected_revision:integer>=1}`. The server issues a fixed `install_module` proposal, valid for 15 minutes, bound to actor/project/installation/pinned ref/module revision/SQL hash. Contact v1 is the first supported schema. No caller SQL or acknowledgement field is accepted.
- `GET /proposals/{proposal}` returns the exact code-owned DDL, impact hash, expiry and receipt to its owner. It makes no remote change.
- `POST /proposals/{proposal}/apply` accepts only `{impact_hash:64 lowercase hex,expected_revision:integer>=1}`. Authenticated owner CSRF confirmation is required; rechecks capability, dependency, pinned ref, immutable payload, expiry and revision under locks. One schema apply lease per project. An applied proposal returns its receipt without re-execution. Storage failures redact upstream bodies and never mark the installation ready. Remote DDL is transactional and idempotent for safe retry after a lost response.
- `POST /proposals/{proposal}/cancel` accepts `{}`; preserves a cancelled receipt and never calls Supabase.

Installation uses Supabase Management `POST /v1/projects/{ref}/database/query` with the server OAuth token and `read_only:false`, then verifies/registers the exact project/module through a service-role-only RPC. The query endpoint is experimental in the [Supabase contract](https://supabase.com/docs/reference/api/v1-run-a-query); only a verified schema marker plus the exact registration response permits ready state. No credentials are returned to the editor or generated artifact.

Contact v1:

- Public `POST /api/lab-widgets/{installation}/contact`: strict20KiB JSON object `{idempotency_key:UUID,name,email,phone?,subject?,message,consent:true,website?:''}`. Only exact live publication origin (or local app origin in local development), credential-free CORS,10/minute/IP/module and100/minute/module. Missing/foreign origin403, invalid fields422, changed retry409. Returns only `{reference,status}`;201 new,200 exact replay. No anonymous list/read endpoint. Exact accepted retries remain readable after disable; new writes intersect account feature/project capability/ready/enabled/dependencies/readiness gate/frozen status and original pinned plane.
- Owner `GET /{installation}/contacts`: most recent100 scoped messages, optional status filter. Authenticated history remains available after downgrade/disable/freeze. `PATCH /{installation}/contacts/{message}` accepts only integer `expected_version` and `status:new|read|archived`, checks effective mutation capability and uses PostgreSQL row CAS plus append-only actor audit. No hard-delete.
- Owner `POST /{installation}/embed`: `{route,expected_app_hash}`. Existing trusted generated source only, exact App SHA-256, existing page route; preserves original App as SiteContent and adds actual ContactForm component and credential-free config. Custom source/components conflict rather than overwrite. Installed files synchronize into editor VFS.
- Account-level generic database schema and row browsing excludes all `krikkit_` managed objects; private messages require the scoped API even when projects share one Supabase ref.

Local evidence distinguishes the Postman contract stand-in for Management/PostgREST from PostgreSQL itself. `tests/fixtures/runtime/supabase-postgres-server.mjs` runs real PGlite PostgreSQL memory tables/RPCs behind the local simulation; it is not a live Supabase deployment. Start adapter4910, then `postman simulation run postman/simulations/managed-modules.sim.yaml --port 4900 --no-history`. No owner mock was pulled because Postman CLI was signed out. No mock was published or deployed.

News v1 (next slice): owner `GET /{installation}/news` lists private draft/published/archived history; `POST` accepts only `{idempotency_key,slug,title,excerpt,body,status:draft|published}`; `PATCH /{installation}/news/{article}` accepts only `{expected_version,slug,title,excerpt,body,status:draft|published|archived}`. Plain text fields, stable scoped slug, UUID idempotency and CAS audit. Public `GET /api/lab-widgets/{installation}/news` lists latest25 published articles; optional exact `slug` returns a published detail or404. No public draft/list-all or mutation endpoint. Effective capability/origin/pinning rules apply.
