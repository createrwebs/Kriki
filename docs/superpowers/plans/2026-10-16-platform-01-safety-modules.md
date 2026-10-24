# Safety and Managed Modules Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Make tenant boundaries and module capabilities enforceable before new customer data/commerce/import functionality is enabled.

**Architecture:** Extend LabProjectAccess and existing entitlement checks with one server CapabilityPolicy and a versioned module installation registry. Disable untrusted in-process execution; preserve current forms and account-linked Supabase.

**Tech Stack:** Laravel13/PHP8.3+, MySQL, React19, existing Supabase broker.

**Spec:** ../specs/2026-10-16-platform-evolution-design.md

**Status:** In progress — S1 PHP execution stopgap implemented; full preview-origin/browser acceptance and S2–S4 remain open. Dependency:none. Deliverable gate S.

## Global Constraints

- Owner/project scope มาจาก authenticated server context เท่านั้น; UUID/Origin/prompt ไม่ใช่หลักฐานสิทธิ์
- ไม่มี untrusted PHP/Python/Node execution ใน Laravel process หรือ host shell; source import ไม่ได้ให้สิทธิ์ execute
- Preview และ published customer origins ไม่รับ platform session cookie; ทดสอบ cross-origin/CSRF จริงก่อน claim isolation
- รักษางานผู้ใช้ใน checkout/index; ห้าม bulk stage, reset, initial commit ปนงานเดิม หรือประกาศ deploy production จาก local pass
- Remaining constraints in Spec§4 apply; capability defaults deny, build/import concurrency2/account, schema apply1/project.

## Review Focus

1. Owner/path check does not isolate PHP include/process — S1 benign sentinel test.
2. Client changes project/owner UUID while logged in — S2/S4 cross-account tests.
3. Two projects share one Supabase ref — S3 namespace and binding tests.
4. AI sets destructive-ack=true or imports instructions — S3 approval binding tests.
5. Preview script fetches owner API with cookies — S1 origin/browser test.

## Task S1: Stop unsafe execution and establish origin boundaries

**Files:** Modify app/Http/Controllers/Lab/LabPreviewController.php, routes/web.php, config/lab.php, resources/views/lab/preview/preview.blade.php (confirm actual iframe child includes before editing); Create app/Lab/Runtime/RuntimePolicy.php, tests/regressions/runtime-isolation.php.

**Interfaces:** RuntimePolicy::verdict(LabProject $project): array `{mode:'browser_static'|'source_only',can_execute:bool,reason:?string}`. PHP/Python/SSR source is source_only until a separately audited runner is enabled. Do not change existing project_type metadata or erase its files.

- [x] Red: test `php_preview_does_not_execute_untrusted_file` writes only a fixture sentinel inside an isolated temporary workspace; request must return423 `runtime_unavailable` and sentinel must remain absent. No credential-reading exploit tests on real host.
- [x] Run PHP regression; establish it fails because current path executes included fixture. Preserve React preview shell/path/headers and owner denial tests.
- [x] Remove in-process user PHP include path; return actionable source-only notice through RuntimePolicy and documented operator boundary, with no configuration toggle that can re-enable include. Controller/publication source inventory is scoped; other Hosting runners not comprehensively audited, so do not claim platform-wide execution isolation.
- [ ] Separate untrusted iframe/preview origin from authenticated platform origin and enforce validated postMessage origin/project/token. Reject platform cookie sharing and cross-project message payloads. If infrastructure cannot supply a cookie-free origin, keep untrusted execution disabled and record prerequisite instead of adding permissive CORS/sandbox flags.
  - [x] 2026-10-17 message-binding sub-slice: current iframe window/exact origin/project/per-load token in both directions, token rotation, cover validation and pre-connect error preservation. Independent review accepted; Node 35/35 and PHP runtime 24 checks pass. Contract and remaining runtime migration: `docs/security/preview-message-bridge.md`. Parent checkbox stays open: same-origin SW execution and authenticated browser isolation are not solved.
- [ ] Green: runtime-isolation.php; real browser fixture cannot read owner API or sibling project; React static page/booking still work. Attach screenshots and headers, not just source matching.
- [ ] Record changed paths/Red→Green in Debug Note; independent security review before gate. Do not commit user index; make path-only commit only when a clean authorized branch exists.

## Task S2: Effective capabilities and module registry

**Files:** Create app/Lab/Modules/ModuleCatalog.php, CapabilityPolicy.php, ModuleInstallations.php; app/Models/LabModuleInstallation.php, LabProjectCapability.php; database/migrations/2026_10_16_020000_create_lab_module_control_tables.php; config/lab-modules.php; app/Http/Controllers/Lab/LabModuleController.php; routes/lab-modules.php; tests/regressions/module-capabilities.php. Modify routes/web.php, app/Entitlement/EntitlementCatalog.php/Gate.php only where needed, WorkspaceTabs.jsx/WorkspaceStage.jsx; Create components/modules/ModulesDesk.jsx, lib/modules.js.

**Interfaces:** ModuleCatalog::get(string $key): array; CapabilityPolicy::assertAllowed(User $actor,LabProject $project,string $operation,?LabModuleInstallation $module=null):void; ModuleInstallations::install(User,LabProject,string $key,int $expectedRevision,array $config):LabModuleInstallation. Keys booking/catalog/orders/manual_payments/custom_data; dependencies and schema version declared server-side. Catalog config is code-owned, not uploaded package code.

- [ ] Red: module-capabilities.php asserts entitlement∩project∩ready state; forged owner/feature denied; missing dependency409; duplicate install returns same installation; stale revision409; frozen edits denied but history readable.
- [ ] Implement additive registry/migration and existing booking metadata adoption, without migrating old records. Existing authorized booking owners retain their previously enabled booking access during adoption; do not grant new commerce/GitHub/runtime capabilities as a side effect. Add a legacy-booking-access preservation test. Install is prepare→schema verify→ready; failed installation not ready; enabling/disabling preserves history. Effective server policy also gates AI tool dispatch, not just UI visibility.
- [ ] Owner GET `/lab/{project}/modules`, POST installs `{key,expected_revision,config,idempotency_key}`, PATCH `/{module}/state` `{state,expected_revision}`; CSRF/auth+verified and project binding. Unknown config keys422. No install code execution.
- [ ] Green: PHP regression, JS serialization/conflict tests, browser denied/empty/install-failed states; existing booking regression suite passes. Review quota/account downgrade behavior: block new mutations, preserve owner history and accepted-payment evidence.
- [ ] Record gate evidence and review. No hidden entitlement expansion or plan-price change.

## Task S3: Scoped schema proposals instead of agent raw SQL

**Files:** Create app/Lab/Changes/ChangeProposal.php, ProposalStore.php, ProposalApproval.php, SchemaPlanner.php; app/Models/LabChangeProposal.php; database/migrations/2026_10_16_030000_create_lab_change_proposals.php; routes/lab-changes.php; app/Http/Controllers/Lab/LabChangeController.php; tests/regressions/scoped-schema-proposals.php. Modify DatastoreRevisor.php, LabDatastoreController.php, app/Ai/Lab/LabToolCatalog.php/StageContextContract.php, orchestration/toolRuntime.js/runTurn.js, hooks/useLabChat.js.

**Interfaces:** SchemaPlanner::propose(User,LabProject,array $operations,int $baseVersion):ChangeProposal; ProposalApproval::apply(User,LabProject,string $proposalUuid,string $impactHash,int $expectedVersion):array. UUID/project/actor/version/expiry/hash checked against immutable server proposal; one transaction/lock, expiry15minutes, replay receipt. No arbitrary SQL input in customer AI path.

- [ ] Red: forged ack boolean cannot authorize; proposal for projectA fails onB; cross-account binding to same remote ref denied; owned schemas cannot access public/auth/storage/krikkit_form or commerce tables; expired/stale/changed-hash409; duplicate Apply one operation; cancel no schema change.
- [ ] Implement server allowlisted operations and server-generated identifiers, object ownership registry and additive DDL transaction. Default-deny raw revise_datastore for customer contexts including direct endpoints, pseudo-tool parser and native tools. Retain operator migration path separately, not AI-accessible bypass.
- [ ] Restrict survey/catalog outputs by project namespace so schema information does not leak across projects. Keep external existing schemas read-only until explicit reviewed mapping exists. Explain disabled legacy raw-SQL capability in UI; do not silently alter existing external tables.
- [ ] Green against SQLite/fake transport, then opt-in unique test schema in selected Supabase. Verify transaction rollback without dropping real tables; source/body redaction. Existing account-isolation tests pass.
- [ ] Record migration compatibility/feature-flag rollout and independent review before enabling module mutations.

## Task S4: End-to-end isolation and quotas

**Files:** Create app/Lab/Modules/OperationLimiter.php, tests/regressions/module-isolation.php, docs/security/module-isolation-matrix.md; update cache/job keys in exact handlers identified by S1–S3, not blanket global cache rewrite.

**Interfaces:** OperationLimiter::acquire(User,LabProject,string $operation,string $operationKey):lease; release/finalize idempotent. Authoritative database leases enforce build/import2/account, apply1/project; process crash expires lease rather than consuming quota forever.

- [ ] Red: A↔B owner file/definition/record/slip/proposal/job access denied; same-owner projectA↔B denied where not explicitly shared; missing context fails closed; warmed cache does not return other project state; queued job rechecks actor/project permissions at execution.
- [ ] Implement scoped cache/lock/idempotency keys and per-account quotas; cancellation and crash cleanup limited to exact project/job. Never delete shared workspace roots.
- [ ] Green: module-isolation.php + existing29 datastore checks/12OAuth checks/booking regressions. Concurrent lease fixture never exceeds limits and userA's saturation does not stopB except declared infrastructure capacity.
- [ ] Gate S report: remaining threat boundaries, actual browser origins, source-only runtime notice, zero unresolved high-severity findings. Gate S is not satisfied by UUID checks alone.

## Execution Handoff

Read Spec + docs/platform/2026-10-16/AGENT-HANDOFF.md first. Run each suite via the documented PHP binary/extensions, fail on nonzero exit. Native execution proposed; ask owner to review plan before starting. No production permission/config change is pre-authorized by this planning document.
