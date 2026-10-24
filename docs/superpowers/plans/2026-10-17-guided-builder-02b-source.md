# Guided builder Part 2B: real source project

Resumes the approved guided site builder design under the owner's 2026-10-17 request to complete real websites/builds and database widgets. Execute directly in the existing checkout; independent review required. Preserve unrelated dirty files and do not commit automatically.

1. Extract the trusted page DOM/theme renderer into `resources/js/builder/SitePage.jsx`, keeping draft preview navigation behavior and markup. Move only site CSS to reusable `site.css`.
2. Add `BlueprintSource` compiler to validate frozen draft schema and export the same renderer/CSS plus public page JSON, route-aware React app, pinned React pair, existing managed Vite 7 package range and content/hash manifest. Record actual compiler version during build verification; no cross-version binary reproducibility claim. No arbitrary scripts/hooks/server execution, no profile/brief secrets, and no fake widget readiness.
3. Add owner/revision-scoped materialization binding/migration and service. Lock owner/draft, check existing binding before quota, create only a fresh project, write source atomically, bind snapshot, preserve existing edits on retry, compensate only newly allocated workspace on failure.
4. Add strict auth/verified/CSRF endpoint and UI action enabled only for saved clean drafts; handle failure/stale/loading/limit; offer explicit workspace/source export handoff. No auto-publish.
5. Verify isolated PHP HTTP/storage fixtures, source/preview Node tests, actual generated project production build and browser entry. Review and update evidence. Keep the overall full-flow ExecPlan active for isolation/widgets/backend phases.

Contract: `docs/contracts/guided-builder-materialization.md`. Source hashes bind exact accepted saved snapshot; no existing draft API response shape change. Materialization creates one project per draft revision, not persisted undo/history. Isolated DB tests do not authorize production migration. Existing project quotas and source-only restrictions remain.
