# Guided builder: saved draft to source project

Status: implementing (2026-10-17). Extends approved guided-builder Part 2 with deterministic source; widget/backend readiness remains separately gated.

`POST /lab/builder/drafts/{uuid}/project` uses existing auth/verified/CSRF and owner lookup. Body is exactly `{expected_revision: positive integer}`. Draft must already be saved; unsaved UI changes must be saved before this action. Stale revision returns 409; unknown/foreign draft returns 404; missing additive materialization table returns 503. Quota uses the existing project entitlement under an owner lock. Read/write responses are private, no-store.

Successful creation returns 201 `{project:{uuid,title,workspace_url},source:{draft_uuid,draft_revision,blueprint_hash,renderer_version}}`. Same owner/draft/revision retries return the same project with 200, including after project source is manually edited; a retry never regenerates or overwrites it. Saving a newer draft revision permits a separate project, quota permitting. No client owner/project/source paths are accepted. No AI credits, publication or module activation occurs.

The source compiler validates the saved blueprint and emits a standard React/Vite package: real page routes/navigation, shared page renderer and trusted theme CSS, non-secret public page content and build manifest. The same renderer powers trusted draft preview and generated source. Brief/summary, account/profile/budget and credentials are not exported. Text stays data in JSON and is escaped by React; it cannot become JavaScript or HTML. Unsupported blog/store widgets remain explicitly unconnected until their managed backend phases pass.

An additive `website_draft_projects` table binds a saved revision to a project. DB writes and source generation are serialized for the owner; a filesystem failure rolls back the new project/binding and cleans only its newly allocated workspace. Existing projects/drafts are never reset. The local named migration requires a verified loopback target; production rollout is separate.

Verification: generated source passes actual production compilation, direct/deep route smoke checks and source/preview parity; HTTP tests cover owner/CSRF/strict JSON/revision/quota/idempotency/failure cleanup and no unrelated writes. Actual browser action creates a fictional project and opens the existing Lab workspace. Build evidence must distinguish the platform build from the generated customer's build.
