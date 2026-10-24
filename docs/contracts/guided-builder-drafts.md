# Guided builder drafts — local wire contract v1

Source: approved guided-site-builder design and `docs/superpowers/plans/2026-10-16-guided-builder-02a-drafts.md`. This contract governs new draft endpoints only; not LabProject creation/source/build/export, and no public customer API.

All routes use existing web auth + verified + CSRF middleware. JSON requests use `Accept: application/json`, same-origin session cookies, and the page meta CSRF token for POST/PUT. Draft mutations rate-limit30/min; reads120/min with separate read/write counters. Guest and unverified handling remain existing middleware (redirect/403 as appropriate). Validation422 `{message,errors:{path:[messages]}}`, including non-JSON/malformed bodies and wrong object/list wire types; forbidden owner IDs return404 via owner-scopedlookup. ExpiredCSRF419; limit429; missingdrafttable503 `{message}`. No provider, billing, external resource or business-profile operation. Responses including framework errors use `Cache-Control: private, no-store`.

| Method/path | Input | Success |
| --- | --- | --- |
| GET `/lab/builder` | optional `?draft=UUID` for client resume | HTML trusted React entry, no user/profile data |
| GET `/lab/builder/catalog` | none |200 catalog (shape in plan) |
| GET `/lab/builder/drafts` | none |200 `{drafts:[{uuid,title,kind,revision,updated_at}]}` latest20 owner-only |
| POST `/lab/builder/drafts` | exact keys `{request_id:UUID,blueprint:object}`; full edited snapshot validates against v1 schema |201 `{draft}` new,200 identical creationretry;409 `{message}` if samekey with different snapshot |
| GET `/lab/builder/drafts/{uuid}` | none |200 `{draft}` |
| PUT `/lab/builder/drafts/{uuid}` | exact keys `{expected_revision:positive integer,blueprint:object}` |200 `{draft}` revision+1, or409 `{message,current_revision}` stale; no overwrite |

`draft`: `{uuid,revision,blueprint,updated_at}` only. It never includes owner IDs, creation hash or business profile. Titles and summaries follow blueprint limits. Create permits only one compatible catalogtemplate and kind. Unknown request/blueprint keys reject422, including user_id/project_id/owner/status/budget or raw code/style/URL fields.

Exact JSON strings (including allowed empty bodies and content whitespace) are preserved. Only these JSON draft POST/PUT routes are exempt from existing global string-trimming/empty-to-null transformations; all other routes retain existing behavior. Null is not an alternative to a required blueprint string, and padding does not repair an invalid route.

Blueprint, catalog and route invariants are fixed in the linked plan's Shared data/API contract. `BlueprintSchema::validate` preserves the accepted shape and rejects unsupported/extra fields or wrong primitive types. Initial UUIDs server assigned; explicit edits may add UUIDs while keeping unaffected identities stable. No silent route repair.

UUID strings use hexadecimal RFC layout, version nibble1–8 and variant8/9/a/b, case-insensitive: `^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$`. Nil/non-RFC-shaped IDs are invalid. Uniqueness compares IDs case-insensitively.

Creation request UUIDs and draft lookup UUIDs are canonical lowercase at the boundary; case-only spelling changes cannot create duplicate retries or depend on database collation. Blueprint UUID/text strings are not rewritten. Response UUID binding compares identity case-insensitively.

UUIDs are exactly36 characters (no trailing newline). Required text fields cannot consist solely of Unicode White_Space, U+FEFF or U+0000 characters; embedded characters/whitespace are preserved, not trimmed. Optional body/summary/footer strings may contain only whitespace. Server/client use the same blankness policy (including Laravel's existing NUL-only required-field rejection).

Creation idempotency keys are unique per owner; only identical validated snapshots replay (object-key order ignored, list order significant). The full edited snapshot is saved atomically on initial POST; do not create a default template then depend on a second PUT. Update revisions are compare-and-swap; failed writes don't increment. Client does not automatically retry uncertain writes or mark unsent edits saved. An identical POST retry recovers a lost initial response; edits after an uncertain create require a new key and may create a separate draft, never silently replace the unknown draft. PUT after lostresponse can conflict; reload only after user has preserved/discarded local changes explicitly.

2A delivers saved design drafts and trusted previews, not runnable generated artifacts or checkout/CMS. Start mode only template; AI unavailable in this entry. In-session UI undo is not persisted history. Existing project quotas still apply at future materialization; this API neither creates projects nor changes entitlements. No deletion/publication endpoints in2A.
