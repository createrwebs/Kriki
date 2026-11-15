# Guided Builder Part 2B: real source and build

Updated: 2026-11-07. Source-project slice is implemented and reverified locally; the full database/widget flow remains gated separately.

## User flow

Open `/lab/builder`, save a template design, then select **Create website project**. The server creates a real owner-scoped React/Vite project from that saved revision, using the existing project quota. Open its Lab workspace to edit/export the actual source. Publishing remains a separate explicit action. AI and data widgets are not enabled by this source operation.

Identical retries recover the same project and preserve manual source edits. An edited draft must be saved before creating a project; a newer saved revision can create a separate project, subject to quota. Existing projects are never overwritten automatically. The private website brief and account/business-profile data are excluded from export.

## Implementation and schema

- Contract: `docs/contracts/guided-builder-materialization.md`.
- Shared preview/export page renderer: `resources/js/builder/SitePage.jsx` and `site.css`.
- Source compiler and materializer: `app/Lab/Builder/BlueprintSource.php`, `DraftMaterializer.php`.
- Endpoint: POST `/lab/builder/drafts/{uuid}/project`, auth + verified + CSRF; strict `{expected_revision}` JSON body.
- Additive migration: `2026_10_17_100000_create_website_draft_projects_table.php`. Applied only to verified local loopback MySQL; production rollout not performed.

## Verification refreshed 2026-11-07

- `builder-source.php`: 21 checks passed.
- `builder-materialization.php`: 45 checks passed.
- `builder-drafts.php`: 462 checks passed.
- `builder-blueprint.php`: 708 checks passed.
- `builder-source.test.mjs`: 2/2 passed; full Node regression suite: 44/44 passed.
- `builder-ai-planner.php`: 10 checks passed; AI edit through the local Postman HTTP simulation: 54 checks passed.
- The current compiler emitted a fictional source tree and the generated Vite 7.3.7 build passed. Static smoke requests for `/`, `/about`, and `/story-qa` returned 200. This is local fixture evidence only.
- Fresh fixture artifact: `.agent-work/qa/generated-source-current-4231d2f536b84d359c02449f088c86b2`; platform log: `.agent-work/qa/2026-11-07-platform-build.log`.

## Actual local acceptance

Authenticated Chrome created fictional project `9e25fea9-c4bb-476f-9a87-f645aa536600` from draft `d02eba61-c0d3-49f2-9cda-e227bd4bfb4a`, revision 2. The UI opened the existing workspace and a repeated create action returned its same workspace link. All 11 compiler files were byte-checked before host-side build; only this controlled generated source was compiled, no imported/customer scripts or npm hooks were run on the host.

The actual project's production build passed with Vite 7.3.7. Output contains `dist/index.html`, JS and CSS. Local Vite preview at `http://127.0.0.1:8894/` showed the edited Thai content, theme and navigation. Clicking `/story-qa` and reloading that deep URL displayed the correct second page. This is a loopback acceptance preview, not a public publication or proof that the in-browser runtime has been isolated.

Evidence: `.agent-work/qa/2026-10-17-builder-real-project.png`, `2026-10-17-built-website-home.png`, `2026-10-17-built-website-deep-route.png`, `2026-10-17-actual-customer-build.log`. Independent review found and then accepted fixes for popstate navigation, route discovery, Thai URL encoding and font resources; no unresolved findings remain in this slice.

## Remaining full flow

Dedicated runtime origin/Gate S, managed module approvals and capabilities, live vendor/provider acceptance, Supabase/production rollout, and final mobile/keyboard/browser review remain required by `.agent-work/plans/2026-10-17-builder-full-flow.md`. Contact/news/commerce code exists in separate managed slices but does not become enabled merely because a source project builds. Store/blog labels remain truthful design demos until their managed backends are explicitly installed. No production deployment or real payment was performed.
