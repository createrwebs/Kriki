# Managed commerce request shapes

Generated locally from authoritative `docs/api/commerce.openapi.yaml` (v0.4.0) on2026-10-25. No remote workspace was pushed. Regenerate with `postman spec generate collection docs/api/commerce.openapi.yaml -n managed-commerce-implemented --force`, then run `node scripts/normalize-commerce-collection.mjs` and `postman collection lint postman/collections/managed-commerce-implemented`. Back up curated scripts/examples first if adding any; generation replaces the folder, including this README.

The normalizer preserves required Laravel cookie authentication instead of the generator's cookie-as-header API key, and uses publication Origin/CSRF/order token variables. Session/CSRF/token values remain blank. Supply credentials privately; never commit real values.

The default target is the isolated fictional loopback fixture (`baseUrl`8896, `publicationOrigin`fictional.localhost8896). Generated UUIDs/payloads are placeholders. Obtain a real fixture quote, inspect its amount and explicitly confirm by using its `quote_uuid` with matching items in createOrder; the example quote UUID cannot create an order. Owner operations must use the appropriate catalog/orders/manual_payments module UUID.

OpenAPI syntax and collection structural lint pass. This collection has not been executed as a complete flow; behavioral evidence comes from real Laravel HTTP/PostgreSQL and React regressions documented in `Support/COMMERCE-MODULE.md`. Live Supabase and updated browser acceptance remain pending.

Owner order/evidence lists now use25-row keyset pages. Pass the exact returned `next_cursor` until null, retaining the status/order filter. Initial order response contains only a bounded attempt summary; use the dedicated per-order payment-attempt history endpoint to review all evidence. Cursors are not authorization; owner/session/project/module/order checks apply to every page.

Source setup uses GET embed preview then POST with the complete expected_files/impact hash. Build context binds exact source hashes and SDK/schema/runtime versions. Historical inline images return a relative owner-session legacy-image URL, not a signed Storage bearer URL; every image download rechecks ownership. New requests were regenerated locally and normalized for session/CSRF. Structural lint scans34 files with zero errors/warnings; no full collection execution or remote push is claimed.
