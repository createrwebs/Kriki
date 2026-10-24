# Guided Builder Drafts (Part 2A)

Status: Done / Ready for review  
Date: 2026-10-17
Plan: `docs/superpowers/plans/2026-10-16-guided-builder-02a-drafts.md`  
Contract: `docs/contracts/guided-builder-drafts.md`

## Delivered scope

- Owner-scoped saved template design drafts at `/lab/builder`.
- Strict version-1 blueprint schema with shared PHP/JS validation.
- Trusted real preview using existing components (no generated scripts).
- Editor/theme/resume flow for saved drafts.
- Separate `website_drafts` storage with owner FK, idempotent create, and CAS revision.
- Empty Lab Hero link to builder (no LabApp rewrite).

## Endpoints

- `GET /lab/builder` — chooser/authenticated page.
- `POST /lab/builder/drafts` — atomic first save `{request_id, blueprint}`.
- `GET /lab/builder/drafts` — list owner drafts.
- `GET /lab/builder/drafts/{uuid}` — load single draft.
- `PUT /lab/builder/drafts/{uuid}` — update with CAS revision.
- Part 2A has no delete/archive endpoint; routes above match the current local contract.

## Validation and contract

- Strict schema rejects unknown fields, wrong primitives, invalid routes.
- RFC v1–8 UUID strict 36-char, uppercase canonicalization.
- Unicode whitespace + FEFF + NUL required blank parity.
- Route grammar includes combining marks, NFC/lowercase rejection for Thai.
- Field limits and HTML agree with PHP contract.
- No raw HTML/JS/styles/URLs/module bindings/profile budget.

## Test results

- `node --test tests/regressions/*.test.mjs`: 30/30 pass.
- `tests/regressions/signup-business-profile.php`: PASS 149 checks.
- `tests/regressions/landing-copy.php`: PASS 53 checks.
- `tests/regressions/hosting-safety.php`: PASS 37 checks.
- `tests/regressions/builder-blueprint.php`: PASS 689 checks.
- `tests/regressions/builder-drafts.php`: PASS 462 checks.
- Production build: exit 0, `public/build/manifest.json` present (162306 bytes, 2026-10-16 20:51:14).
- Blade view cache: exit 0.

## Limitations

- Authenticated local Chrome save/resume/conflict QA passed on 2026-10-17: fictional draft, edited brand/home/theme, save revision 1, reload preserved content, edited About title/path, save revision 2, stale-tab save rejected without losing local edits. Evidence: `.agent-work/qa/2026-10-17-builder-flow.md`.
- Part 2B materialization/source export/persisted undo and AI flow remain out of scope.
- Local-only; no staging/commits/production changes.

## Out of scope

- AI generation (Part 3).
- Source export/publish/real commerce (Part 2B).
- Existing project changes/entitlements.
- General middleware bypass or auth/CSRF reduction.
