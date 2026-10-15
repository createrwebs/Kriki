# Tora / Responses API configuration

Run `php artisan migrate` when deploying the connection-settings migration.

An administrator can open Dashboard → API Integration → OpenAI and configure:

- Base URL: `https://www.toraapi.com/v1` (omit `/responses`).
- API format: Responses API.
- API key: stored encrypted with the existing provider credentials.
- Default model: select a model authorized for the provider token. `/v1/models` is the authoritative list for that token.

Environment alternatives are `OPENAI_BASE_URL`, `OPENAI_API_DRIVER=openai_responses`, `OPENAI_API_KEY`, and `AI_DEFAULT_MODEL`. Workspace settings override environment defaults; account model preferences override the workspace default.

For the local Windows/MAMP setup, run `./scripts/serve-local.ps1`. This enables the required PHP extensions and configures the available MAMP CA bundle without disabling HTTPS certificate validation.

Verification should include both a real provider request and the customer workflow: generate files, start the guest runtime, navigate all requested pages, exercise controls, and open the private full-page preview with the workspace running. A successful host `npm run build` alone does not validate generated customer sites.
