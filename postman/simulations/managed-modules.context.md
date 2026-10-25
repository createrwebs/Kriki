# Managed modules local dependency simulation

## Application and scope

Laravel's real managed-module installers/services and HTTP controllers call a local Supabase Management/PostgREST contract stand-in at `http://127.0.0.1:4900/supabase`. The mock forwards database operations to an actual PGlite PostgreSQL process on4910. It exercises fixed DDL, service-role RPCs, row CAS, RLS/role restrictions and account/project predicates with fictional data.

## Reproduce

1. `node tests/fixtures/runtime/supabase-postgres-server.mjs`
2. `postman simulation run postman/simulations/managed-modules.sim.yaml --port 4900 --no-history`
3. Run PHP regressions `contact-flow.php`, `news-flow.php`, `owner-commerce-pages.php`, and `scoped-schema-proposals.php` using MAMP PHP with openssl/pdo_sqlite/mbstring/curl/intl/gd enabled. Owner paging suite includes quote/commerce/private-slip suites.
4. Standalone PostgreSQL regressions: `node tests/regressions/contact-postgres.mjs`, `news-postgres.mjs`, `commerce-postgres.mjs` (PGlite dependency in `.agent-work/test-deps`).

Optional browser acceptance is an isolated opt-in fixture: run `tests/fixtures/runtime/seed-module-browser.php`, then `node tests/fixtures/runtime/build-module-browser.mjs`, and serve `tests/fixtures/runtime/module-browser-router.php` at localhost8896. This fixture uses its own SQLite control database, session cookie and in-memory business database. The generated fictional site is served at fictional.localhost8896. Source builds are limited to the trusted code-owned fixture; never run imported/customer scripts on the host.

For the MAMP PHP development server pass both `-d opcache.enable=0 -d opcache.enable_cli=0`, `-d variables_order=EGPCS`, its explicit extension directory and openssl/pdo_sqlite/mbstring/curl/intl/gd/fileinfo/zip. CLI-only OPcache disable left this PHP build timing out during bootstrap. These are per-process development flags; do not edit production php.ini. Reseeding or restarting the PostgreSQL process discards the fictional business fixture, so preserve acceptance evidence before doing so.

## Boundaries and evidence

- No live Supabase Management, PostgREST, Storage or bank service is contacted; there are no real transfers or customer records.
- `.agent-work/qa/2026-10-25-simulation.ndjson` is local ephemeral traffic evidence. Do not publish traffic bodies, tokens, customer data or slip images.
- No remote Postman workspace resources were pushed, mocked or deployed. Existing binding was preserved; CLI was not signed in to an owning Supabase mock workspace.
- Storage bytes/signing are local contract stand-ins; PostgreSQL memory evidence cannot establish live Supabase roles, storage, durability or network behavior. Fictional service keys/data only; do not direct fixture fault controls at an external service.
- Results and remaining readiness gates: `.agent-work/implementation/2026-10-25-full-flow-verification.md` and `Support/COMMERCE-MODULE.md`.

## Observations

<!-- begin:observations -->
- 2026-10-25 local-only continuation: new fictional fixture preserved old config/source/proof, generated/server artifact verified; actual contact submit/read/reload and news draft/publish/detail/archive UI passed. Actual cart/order reload, Back→empty checkout/Forward→exact order and owner revoke exposed stale order/QR/upload frontend state. Fixed terminal401/403/404 cleanup and response fencing, React48 plus quote9/routes15/retry5/storage9 pass; fresh platform/generated builds and actual repeated HTTP-backed browser revoke pass. QR/upload absent, owner history intact; no transfers/slips on these new199THB orders. Supabase member served actual requests through local PGlite only. See current UI/storefront-revoke notes; no live Supabase proof or workspace push.
- 2026-10-25 reconnected browser: actual quote/reprice409/order/private upload/viewer/reject/resubmit/accept/reload paid passed through owner/customer UI against this simulation and PostgreSQL. The `supabase` member handled Management/PostgREST/Storage HTTP, no other members exist. Changed initial catalog280 to owner310 then requested a quote: fixed generated checkout displays310 in every monetary field. This is local browser evidence, not live Supabase/bank acceptance. Screenshots and fixed build logs are indexed in docs/PROJECT-STATUS.md. Browser later stopped before remaining contact/news/mobile/back-forward acceptance; do not erase completed proof or claim those later cases passed.
- 2026-10-25 closeout: final PHP30 suites pass, including owner pagination87, historical proof70 and artifact promotion87 cumulative checks (overlapping setup is not additive). The single supabase member served Management/PostgREST/Storage over actual HTTP; no other members exist or stayed silent. Node25 suites pass separately; trusted generated build/manifest passes server verification and four direct HTTP SPA routes. No current browser/live Supabase acceptance. See closeout logs and C5 implementation note.
- 2026-10-25: `owner-commerce-pages.php` passed87 cumulative checks; `supabase` served DDL/PostgREST/Storage plus timestamp/UUID keyset filters. More than25 orders/attempts are retrievable without overlap, new arrivals do not shift older pages, invalid/foreign-scope cursors are denied. No silent members.
- 2026-10-25: `commerce-quotes-flow.php` passed73 cumulative HTTP checks; `supabase` member served Management DDL, PostgREST quotes/orders/revocation and actual binary Storage upload/sign/delete/download traffic. No other simulation members exist or stayed silent. Private Storage loss/failure cases preserve committed evidence; quote conflicts require fresh confirmation.
<!-- end:observations -->

## What didn't work

- 2026-10-25: Postman request interception decoded binary PNG into text. Keep `interceptRequests:false`; the handler collects raw bytes and catches failures explicitly. JSON DELETE forwarding needs Content-Length or the body can disappear. These fixes were verified by signed PNG download and orphan cleanup HTTP checks.
