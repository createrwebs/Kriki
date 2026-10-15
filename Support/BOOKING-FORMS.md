# Managed booking forms

## Owner workflow

1. Connect the account's Supabase project, then open the Lab workspace → plus menu → Forms.
2. Create the table-booking template. Rename/reorder labels, add text, number, select or checkbox fields, and save a draft.
3. Open bookings with the saved version. Draft edits do not replace the active version until explicitly activated.
4. Install the form files in the site. Ask AI to import the standard component and config in `/booking`, preserving existing pages/navigation.
5. Review submissions in Forms. Confirm or cancel each request manually. Dates display in Bangkok time.

The default booking fields are name, phone, requested time, party size and notes. Core IDs/types are protected. Custom fields keep UUID IDs; archive rather than delete historical fields. At most 20 active custom fields and 100 retained fields are supported.

For conversational edits, read the managed form then call `propose_form_change`. The owner must Apply the proposal as a draft. The tools never save, activate, modify payments or send customer submissions to the model. Unsaved manual edits retain their original base version on external updates; resolve conflicts explicitly.

## Storage and security

MySQL holds the form registry and immutable definition versions. Each form pins its owner's selected Supabase reference. Submissions, historical definition snapshots and status events live in namespaced Supabase tables. Reconnecting to a different project fails closed instead of falling back to a platform database.

Opening a form performs an additive transactional installation of `database/sql/lab-forms/0001_initial.sql`. An incompatible table shape stops installation. Anonymous/authenticated direct table access is revoked; only the server credential calls the fixed RPCs. Never put private, service-role, OAuth or database credentials in generated files.

Public GET `/api/lab-forms/{uuid}` exposes only an active definition. Public POST `.../{uuid}/submissions` validates fields, version, explicit-timezone future date, honeypot, body size and rate limits. It cannot choose owner, table or initial status. There is no public submission listing/detail endpoint. Public clients omit credentials; CORS permits only the verified live site, with exact local origins for local testing.

Each submission has a stable random retry key. Concurrent identical requests return one reference; changing the payload with that key conflicts. A previously stored identical receipt remains replayable after closure, a version change, or elapsed booking time. New requests still require the current open version and a future time. No public receipt lookup endpoint is provided.

Owner routes under `/lab/{project}/forms` require authentication, verification, project ownership and CSRF for writes. Saving/proposing uses `expected_version`; changing a booking status uses `expected_status`. Old snapshots remain readable after field renames.

## Build and verification

After runtime-source changes, run `npm run build:lab-runtime`, then `npm run build`. Runtime build dependencies are pinned in the root manifest. The runtime script also updates the public process worker. Re-publish customer sites after a runtime/compiler correction: rebuilding the host does not repair already-published bundles.

The runtime must preserve nested awaits in browser JSX. Do not regex-strip await from source the parser cannot understand. Regression: `tests/regressions/runtime-booking-await.test.mjs`.

Runtime compression libraries use the compatible `brotli@1.3.3` / `pako@2.1.0` imports. The top-level-await plugin's SWC dependencies are pinned below its confirmed breaking 1.16 boundary; see the [upstream issue](https://github.com/Menci/vite-plugin-top-level-await/issues/79). Do not remove these constraints without rebuilding both runtime and customer artifacts.

Run `node --test tests/regressions/*.test.mjs`. Standalone PHP regressions are under `tests/regressions/form-*.php`; they use isolated SQLite memory and fake HTTP. `form-publication-routing.php` exercises the real HTTP middleware pipeline. Live Supabase tests require the ignored local opt-in harness and must use fictional data; retained test records are not automatically deleted.

## Deliberate limits

Local acceptance on 2026-10-16 used a real Chrome-published customer artifact and the account-linked Aiwebsite Supabase project. Invalid/valid submission, owner confirm/cancel, Thai AI proposal → Apply draft → explicit activation, stale-version input preservation and historical snapshot checks passed with fictional records. This is local application verification against live storage, not an internet production rollout. The successful AI round took approximately three minutes; provider latency/availability remains external.

- A booking is a request awaiting the restaurant, not a guarantee of availability.
- No payment collection, payment reconciliation, receipt issuance, notifications, staff roles or automatic table allocation in this release.
- The embed shortcut currently installs one managed form in a project's `src/krikkit-forms/` folder and refuses to silently replace another form there.
- Production activation requires a live publication. Local tests do not constitute internet deployment or production readiness for payments.
- The existing café demo contact/cart interactions remain front-end demonstrations, separate from the managed booking form.
