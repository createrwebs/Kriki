# Builder AI HTTP simulation

Start `postman simulation run postman/simulations/builder-ai.sim.yaml --port 4905 --no-history --output ndjson` in a hidden local background process. Wait for the actual listening event, then run `tests/regressions/builder-ai-edit.php` with MAMP PHP8.5, SQLite, intl, mbstring and curl. Base URL: `http://127.0.0.1:4905/ai/v1`. No vendor endpoint or real key is used. Stop only the exact owned process after verification.

This is a minimal hand-written Chat Completions contract based on the existing `OpenAiCompatibleDriver` request/response shape. Owner-workspace lookup is skipped because no signed-in provider workspace is available. No workspace push. Unknown paths501. QA journal contains only a call count; no auth/header/blueprint persistence in the handler. Postman ndjson can contain response bodies, so use fictional content only and do not upload logs.

Healthy response intentionally modifies all pages/theme to prove server-enforced scope. Prompt tokens `fixture-provider-error`, `fixture-invalid-json`, `fixture-missing-target` exercise upstream failures and missing target. Quota exhaustion is local and must prevent outbound calls. Reset/journal is fixture-only, not an application API. This proves real local HTTP wiring and failure handling, not live vendor availability or semantic content quality.
