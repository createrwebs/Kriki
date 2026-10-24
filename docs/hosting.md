# Hosting API — experimental static hosting

This addition is not a cloud runtime or Reflex deployment. It reuses the existing static ZIP publisher. It is **disabled by default** (`HOSTING_ENABLED=false`), separately from existing Publish.

Do not enable it until migrations, artifact safety, concurrency and browser-origin checks in [the review handoff](platform/2026-10-16/HOSTING-AGENT-REVIEW.md) are resolved. No production migrations or external deployment were performed in the review.

## Authentication and readiness

- Prefix: `/api/hosting` (URLs preserved).
- First-party Laravel web session, `auth:web`, verified email, web CSRF protection, 60 requests/minute.
- Send `Accept: application/json`. Mutations require the current session CSRF token in `X-CSRF-TOKEN`.
- This API does **not** accept the new `krik_` tokens. Sanctum is not installed or required.
- Project routes use `{project:uuid}` and server-side ownership. Mutations also reject frozen projects.
- Disabled feature:503 `hosting_unavailable`. Opted in but missing hosting tables:503 `hosting_not_ready`.
- Guest GET:401; unverified/nonowner:403; missing CSRF on mutation:419.

## Provider metadata

| Method | Path | Result |
| --- | --- | --- |
| GET | `/{project}/providers` | `{providers:[{key,name,regions,vm_types}]}` |
| GET | `/providers/regions` | `{regions:[...]}` metadata |
| GET | `/providers/vmtypes` | `{vm_types:[]}` for local static provider; no compute provisioning |

## Deployments

`POST /{project}/deploy` accepts multipart fields:

| Field | Supported value |
| --- | --- |
| artifact | Required production ZIP, at most50MiB, containing index.html (or dist/build/out wrapper) |
| provider | `local` (default; only registered application provider) |
| strategy | `immediate` only for local |
| description | Optional text, max500 characters |
| region / vmtype | Omit; local compatibility values `local` / `shared` only |

`env`, `hostname`, `min_instances`, `max_instances` are prohibited. Local rejects advanced strategies rather than silently pretending to implement them.

Configure/start publishing using existing Publish first. The publication must be `building`; custom domain verification is required. PHP and unknown backend project types are source-only and cannot deploy here. No source compilation or backend process is started.

Success:201 `{deployment:{...},message:...}`. Provider errors:422 with generic public message. Upload resolves through the private local disk and is removed after the synchronous operation. `artifact_path` is not a retained rollback archive.

| Method | Path | Behavior |
| --- | --- | --- |
| GET | `/{project}/deployments` | `{data:[...],meta:{current_page,last_page,per_page,total}}`; per_page clamped1–100 |
| GET | `/{project}/deployments/current` | `{deployment:object|null}` |
| GET | `/{project}/deployments/{deployment}` | Project-owned deployment only |
| GET | `/{project}/deployments/{deployment}/logs` | `{logs:[{timestamp,level,message}]}`; limit1–1000 |
| POST | `/{project}/deployments/{deployment}/rollback` | Local unsupported,422 before new record |
| POST | `/{project}/deployments/{deployment}/stop` | Local unsupported,422 |
| POST | `/{project}/deployments/{deployment}/start` | Local unsupported,422 |

Local status/log fallback describes synchronous completion only, not live health. History/version allocation and filesystem replacement are not yet concurrency-safe or atomic. Existing Publish changes are not synchronized into this new history.

## Stored secrets

- `GET /{project}/secrets` → `{keys:[...],count:N}`, no values, no-store/private.
- `PUT /{project}/secrets` → JSON `{env:{KEY:"value"}}` or an `envfile` upload; returns updated key names.
- `DELETE /{project}/secrets/{key}` removes one stored key.
- `DELETE /{project}/secrets` removes all keys for that project.

Values remain encrypted in the database and hidden from model/project serialization. JSON secret updates preserve significant leading/trailing whitespace and newlines. Editing controls are disabled while a save is pending. **Static hosting does not inject these values**; redeploying does not make server secrets available to React. Never copy private keys into frontend source, build-time VITE variables or AI prompts. Existing encryption encoding is retained; no data migration was performed.

## Pattern scan

`POST /{project}/scan`, optional `{fail_on:"critical|high|medium|low|none"}` (default low).

```json
{
  "violations": [{"file":"src/app.js","line":42,"severity":"high","title":"Pattern title","recommendation":"Suggested action"}],
  "files_scanned": 1,
  "scanned_at": "2026-10-16T00:00:00+00:00",
  "violation_count": 1,
  "failed": true,
  "fail_on": "low"
}
```

Returns422 when threshold exceeded,200 otherwise. Validation/operational errors also use422 but are **not** scan results. No raw source snippets are returned. Symbolic links are skipped. A clean result means no matching patterns in scanned files, not that the app is secure. No dependency audit, resource-isolated execution or deploy gate is implemented.

## Legacy token records

- `GET /tokens`: authenticated user's metadata/fingerprints only.
- `POST /tokens`:501 `hosting_tokens_unavailable`, no token issued.
- `DELETE /tokens/{token}`: only its owner may revoke an existing record.

Before enabling programmatic deployment, design and test scoped bearer authentication, expiry, ability enforcement and project ownership. Do not treat the token model alone as implemented authentication.

## Local verification

`tests/regressions/hosting-safety.php` uses SQLite memory and unique temporary storage, no customer records. HTTP middleware tests inject a web user; they do not replace browser login/cookie/isolation tests.

`node --test tests/regressions/hosting-client.test.mjs` renders the actual scan panel against the API result shape. Run `npm run build` for the full React integration. Syntax-only verification is insufficient.
