# Project datastore inspection

Implemented locally on 2026-10-25 as the S3 read-isolation slice. This does not enable business writes, custom entities, customer identity or live Supabase acceptance.

## Survey

`POST /lab/datastore/survey` requires the existing verified Laravel session and CSRF token. JSON fields are exactly `project_uuid` (required UUID) and `fresh` (optional boolean). Other fields return422. The server resolves the project under the authenticated actor; an unknown or foreign project returns404. Missing authentication401, unverified email403, missing CSRF419 and invalid/missing UUID422.

The200 response is `Cache-Control: no-store`:

```json
{
  "scope": "project",
  "survey_mode": "registration_metadata",
  "lab_project_uuid": "00000000-0000-4000-8000-000000000001",
  "ready": true,
  "kind": "supabase",
  "status": "ready",
  "host": "https://fictional.supabase.co",
  "project_ref": "fictional",
  "can_revise": false,
  "oauth_ready": true,
  "linked": true,
  "client_env": [],
  "suggested_files": [],
  "tables": [],
  "modules": [],
  "message": "Project module registration metadata only; this is not a live schema check. Unmapped external tables and account-wide credentials are hidden. Use managed widgets and their scoped APIs; schema changes require a proposal and owner Apply."
}
```

`ready/status` describe the saved account connection, not current network reachability, schema validity or module write readiness. With no attached connection, `ready=false/status=missing`; owner registration history remains available. `modules` contains only this actor/project's known managed installations: `uuid`, `key`, `title`, `revision`, `schema_version`, `required_schema_version`, `status`, `enabled`, `selected`, `connection_matches`. There are no customer records, installation config, idempotency keys or credentials. `enabled/selected/status` are saved metadata; they do not override effective server capability checks.

`connection_matches` compares the selected account ref with the installation's pinned ref. Changing/disconnecting the account connection never rebinds a module or moves records. Frozen/downgraded owners may still read their registration history. This path reads local control metadata without remote schema calls or a shared schema cache; `fresh` is retained for callers but does not trigger remote inspection.

## Unmapped administration

These generic Lab endpoints return403 with `code=project_scope_required`, a setup explanation and `Cache-Control: no-store` before any remote request:

- `GET /lab/datastore/rows`
- `GET /lab/datastore/catalog`
- `GET/PATCH /lab/datastore/auth`
- `GET/POST /lab/datastore/users`
- `POST /lab/datastore/restart`

They previously operated on the entire account-selected Supabase, including sibling projects. No reviewed external entity or customer identity mapping exists yet, so they cannot be used as a project API. Existing fixed booking/managed contact/news/catalog/order/payment APIs remain authoritative and unchanged. Account OAuth, connection status, attach and unlink remain account controls; their responses no longer advertise raw revision capability. Raw SQL stays denied through its existing schema-approval contract.

## Clients and compatibility

`survey_datastore` uses the runtime's actual project context, never a model-supplied UUID. Missing project fails before OAuth/network. Tool and Data desk reject a response bound to another project. Neither returns a remote-wide table map, browser credentials or suggested direct client files. The Data desk shows scoped registrations and links to `/lab/{project}/workspace?panel=modules`; generic remote administration controls have been removed.

Legacy internally callable `DatastoreSurveyor::survey` remains separate for existing system/account inspections and preservation tests. No customer Lab controller or AI path calls it. This slice adds no migration, external Apply, identity binding, deployment or activation.

Evidence: `tests/regressions/project-datastore-scope.php` (controller and real middleware), `tests/regressions/project-datastore-client.mjs` (real transport, React races and tool dispatch). Browser screenshot acceptance and final independent review remain pending under the broader release gates.
