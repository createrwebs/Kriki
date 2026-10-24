# Guided Builder 2A: Saved template design workspace Implementation Plan

> **For agentic workers:** Execute bounded tasks with subagent implementers and independent reviewers. The named superpowers:subagent-driven-development skill is unavailable; use subagent-supervision. Steps use checkbox syntax.

**Goal:** Members can choose a website kind and an original template, edit its pages/sections/theme, preview real trusted components, save and resume an account-owned design draft without calling AI or modifying an existing project.

**Architecture:** A separate `/lab/builder` React entry consumes a shared JSON catalog and an authenticated draft API. Versioned, strictly validated blueprint JSON is saved in a new table; optimistic revision checks prevent silent overwrites. Drafts are not Lab projects or runnable generated artifacts; source/export integration remains 2B and AI remains Part3.

**Tech Stack:** Existing Laravel/PHP, Blade, React19, Vite/Tailwind; no new dependencies.

**Spec:** `../specs/2026-10-16-guided-site-builder-design.md`, sections4–9; this implements the first cohesive slice of Part2. Delivery index: `2026-10-16-guided-site-builder.md`.

## Authority and scope

Owner explicitly approved the full written design and selected subagent implementation/review. After Part1, owner said to decide as appropriate and proceed. Coordinator selects the details and sequencing below without repeating preference/plan approval requests; this explicit direction overrides workflow defaults to pause for another plan approval. It does not authorize production, paid AI calls, real payments, access expansion or destructive data changes. Local-only additive schema rollout must verify the local loopback target and name only this migration; no all-pending migration/reset/seed. Preserve dirty unrelated edits; no staging/commits.

## Global constraints

- New drafts only; no existing LabProject/source/runtime changes, no signup redirect change.
- Five website kinds: `business`, `ecommerce`, `portfolio`, `blog`, `other`.
- Only `start_mode=template` is enabled. The UI shows AI as not yet connected, never claims an AI summary or calls a provider.
- Four original template packages: `business-studio`, `portfolio-editorial`, `blog-journal`, `storefront-demo`; Other may use business-studio. Storefront/blog are design demos, not checkout/CMS. No third-party assets/code copied.
- At most5 pages,1–12 sections/page,0–6 items/section; the initial page is Home at `/`. Pages and sections use stable UUIDs, no automatic route suffixing/renaming.
- No raw HTML/CSS/JS, URLs, customer records, account profile/budget or module bindings in this schema. Render escaped text only; theme choices from catalog. No iframe, eval, generated script or service worker registration.
- Drafts consume no project entitlement or AI credits; they do not grant any project/page/payment entitlement. Actual project materialization must enforce existing entitlement/frozen/ownership gates in 2B.
- Revisions persist on server; Undo is in-session only in2A. Persistent revision history/template-family swaps/compiled source/export are2B; don't advertise them as delivered.

## Review focus

1. A second account's UUID cannot read/update a draft; owner/request/project IDs are never accepted from payload. Task2 HTTP isolation.
2. Stale or repeated saves and creation retries cannot silently overwrite or duplicate drafts. Task2 CAS/idempotency tests; Task3 conflict UI must preserve edits.
3. Duplicate/reserved/traversal/script/encoded routes and duplicate IDs fail without silent normalization. Task1 schema + Task3 client checks.
4. Model-looking HTML remains escaped; unsupported sections/tokens/unknown keys fail and cannot load arbitrary code/styles/URLs. Tasks1/3 tests.
5. Network failures, validation errors, rapid local edits and account changes cannot masquerade as saved state or discard unsent input. Task3 transport/state tests and browser QA.

## Shared data/API contract

Authoritative local contract: `docs/contracts/guided-builder-drafts.md` (coordinator writes before implementation). Shared catalog: `resources/builder/catalog.json`.

Blueprint exact keys: `schema_version`(integer1), `kind`, `start_mode`(template), `template_id`, `title`(1–120), `summary`(0–2000), `shared`{`brand`1–80,`footer`0–240}, `theme`{`palette`:forest/indigo/sand,`font`:sans/serif,`radius`:soft/square}, `pages`. Required text must contain a character outside Unicode White_Space/U+FEFF/U+0000 (same server/client policy); preserve embedded/leading/trailing content whitespace, no trimming. UUID strings exactly36 characters, no trailing newline.

Each page exact keys: `id`UUID,`title`1–80,`route`1–160,`navigation`boolean,`sections`. Each section: `id`UUID,`type`hero/feature-grid/text/cta/cards,`variant`split/centered (hero only), otherwise default,`heading`1–120,`body`0–2000,`items`. Each item: `id`UUID,`title`1–120,`body`0–500. UUID shape is RFC hex version1–8, variant8/9/a/b, case-insensitive (exact regex in contract); nil/non-RFC IDs invalid. IDs globally unique across blueprint case-insensitively. No other keys allowed at any level. Empty strings allowed only for summary/footer/body; at least one visible navigation page.

Route `/` exactly first/once. Other routes are NFC-canonical lowercase Unicode letters/numbers/combining marks with single hyphens in nonempty segments (Thai combining marks required). Each segment/hyphen-part starts letter/number; marks may follow. No consecutive/leading/trailing hyphens per segment, query/hash/percent/backslash/dot/duplicate slash or trailing slash. Non-NFC/uppercase invalid paths are rejected, not silently normalized. Reserved first segments: lab,api,login,logout,register,dashboard,settings,projects,assets,build,storage,oauth. Maximum total encoded blueprint JSON128KiB.

Catalog shape: `schema_version:1`, `kinds:[{id,label,description,template_ids}]`, `palettes:{id:{label,background,surface,text,accent}}` (fixed hex), `fonts:{sans,serif}`(fixed CSSfamily), `radii:{soft,square}`(fixed px), `section_types:[{id,label,variants}]`, `templates:[{id,label,description,kinds,pages:[{title,route,sections:[{type,variant,heading,body,items:[{title,body}]}]}]}]`. No UUIDs in catalog; factory assigns all IDs. Defaults: theme forest/sans/soft, brand first80Unicode codepoints of title, footer `ข้อมูลและเนื้อหาตัวอย่างสมมติ`. Catalog contains original Thai sample copy, explicitly fictional/demo, not fabricated customer evidence.

## Task1: Shared catalog and strict blueprint domain

**Files:** create catalog above; `app/Lab/Builder/BlueprintSchema.php`, `app/Lab/Builder/TemplateCatalog.php`, `tests/regressions/builder-blueprint.php`.

**Interfaces:** `TemplateCatalog::all():array`; `TemplateCatalog::blueprint(string $kind,string $templateId,string $title,string $summary=''):array`; `BlueprintSchema::validate(array $blueprint):array` returns validated unchanged shape, throws ValidationException with field paths. Factory validates its result. Catalog loaded from fixed resource path, never client filename.

- [ ] Write failing tests for all4templates/kinds, counts/defaults/stable IDs, valid Thai routes/content, all5 review-focus malformed schema/route cases, excessive sizes/arrays/unknownkeys, exact primitive types and UUID uniqueness.
- [ ] Run intended Red before implementing domain; not a missing PHP extension failure.
- [ ] Implement catalog/factory/schema directly using existing Laravel validation plus explicit invariant checks; no generic schema framework.
- [ ] Run Green and PHP syntax; simplify only introduced abstractions; own note `.agent-work/implementation/2026-10-16-builder-domain.md`.
- [ ] Independent review and coordinator acceptance before Task2 depends on PHP interfaces. Task3 may work independently from the frozen JSON/API contract, but final integration waits for acceptance.

## Task2: Account-owned draft API and storage

**Files:** create `database/migrations/2026_10_16_110000_create_website_drafts_table.php`, `app/Models/WebsiteDraft.php`, `app/Http/Controllers/Lab/WebsiteDraftController.php`, `routes/lab-builder.php`, `tests/regressions/builder-drafts.php`; add one require before dynamic `/lab/{project}` in existing verified route group. In `bootstrap/app.php`, add narrowly scoped TrimStrings/ConvertEmptyStringsToNull skip callbacks only for JSON POST `/lab/builder/drafts` and JSON PUT `/lab/builder/drafts/{uuid}`. This preserves exact content and intentionally empty body strings; all other auth/hosting/form behavior remains unchanged. No User/LabProject changes.

**Storage:** id;uuid unique;user_id FKusers/cascade;client_request_id UUID;creation_hash char64;blueprint JSON;revision unsignedInteger default1;timestamps;unique(user_id,client_request_id);index(user_id,updated_at). Model no public mass-assignment ownership; explicit server forceFill. CatalogGET independent of schema availability; storage endpoints fail503 with safe message when table not installed, not silent in-memory fallback.

Exception responses on exact `/lab/builder` and `/lab/builder/*` also receive `Cache-Control: private, no-store` via a narrowly scoped exception-response callback in bootstrap/app.php; preserve existing status/auth/JSON policy and do not replace another response callback. Test representative framework errors as well as controller responses. CAS success payload must be captured under its update transaction/write lock so it describes the accepted snapshot, not a later concurrent writer's data.

**Interfaces:** controller `catalog`, `index`, `store`, `show`, `update`; routes and wirefields per contract. Owner-scoped lookup by UUID returns404 to other users. POST exact payload `{request_id,blueprint}` atomically persists the full validated edited snapshot, not a default template followed by a second PUT. User-row lock transaction + requestid lookup: identical retry200 same UUID; new201; mismatched reused key409. Hash canonical JSON snapshot (sort object keys recursively, preserve list order), no budget/profile. PUT CAS update byowner/id/revision; success increments once; stale409 with current_revision (no other owner's data). GET/POST/PUT private no-store responses. No source/project/money/provider side effects.

- [ ] Write failing actual HTTP-kernel fixture tests (SQLite forced beforebootstrap; fakeHTTP/notifications) for create/list/show/update/reload, cross-account read/write/list, forgedfields, exact revision/CAS, idempotent create/mismatchedkey, invalidblueprint/path, noAI/credits/projects changed, auth/verified/CSRF/throttle/status/routeprecedence and503missingtable. Explicitly assert empty string section bodies and leading/trailing content whitespace survive JSON POST/PUT unchanged; null/wrong types and space-padded routes must reject422 rather than middleware-normalize. Preserve signup nullable-field behavior via existing149 suite.
- [ ] Run intended Red, implement direct controller/schema integration + named migration.
- [ ] Green/lint/preservation; own note `.agent-work/implementation/2026-10-16-builder-drafts.md`. No actual database operations from implementer.
- [ ] Fresh independent reviewer; accept/fix/recheck before final integration.

## Task3: Guided design UI, real previews and explicit save/resume

**Files:** create `resources/js/builder/builder.jsx`, `BuilderApp.jsx`, `BlueprintPreview.jsx`, `blueprint.js`, `transport.js`, `builder.css`, `tests/regressions/builder-client.test.mjs`; create `resources/views/lab/builder.blade.php` and `app/Http/Controllers/Lab/WebsiteBuilderController.php`. Styles belong in the dedicated entry CSS, not a large minified inline Blade block; format new sources for maintainability without reformatting existing dirty files. Add Vite entry; add pageGET to routes/lab-builder.php after Task2 routefile is stable (coordinate ownership). Add link in EmptyLabHero only, no LabApp rewrite.

**Interfaces:** contract shape fixed above. `createBlueprint(catalog,{kind,templateId,title,summary},idFactory):blueprint`; `validateBlueprint(blueprint,catalog):Record<path,string>`; `editBlueprint(blueprint,operation):blueprint` immutable known operation union add/remove/move page, updatepage, add/remove/move/duplicate/update section, update theme/shared/title/summary; preserve existing IDs, duplicate assigns new IDs. No eval/HTML/URL input. `builderRequest(url,{method='GET',body,signal}):Promise<JSON>` sends AcceptJSON, sameorigin cookies, CSRF for writes; structured errors retain status/errors/current_revision; never retries writes automatically. Separate entry mounts from trusted escaped `builder-bootstrap` JSON{catalog,endpoints:{catalog,drafts},storage_ready}; no businessprofile or user records in boot.

**UI:** header Krikkit + Lab link + saved/unsaved state; quiet white/gray shell and green accent, existing Sora. Desktop left editor + right preview, mobile stacked. Start:5kindcards→Template or disabledAI explanation→4compatible template choices→editable title/summary→structure. Returning user loads draft list (latest20) and can resume by `?draft=UUID`; validate UUID query and owner API. New draft doesn't overwrite current unsaved edits without explicit local confirmation. Save on explicit click only; save disables edits while request in-flight so submitted snapshot equals saved label. On failed/conflicted save retain draft and offer reload only after confirming discard; no auto-retry or autosave. URL stores only UUID, no private content. No localStorage persistence across accounts.

**Editor:** page selector/title/path/nav, addpage(default generated route user sees), home cannotdelete/move; max5. Section list with type-specific allowedvariant, heading/body edits, add/delete/duplicate/move; max12. Theme palette/font/radius select updates allpreviews. In-sessionUndo for edits, reset history when loading serverdraft; not persisted revision-history claim. Overview renders lightweight real trustedcomponents with page title; individual view shows interactive nav changing selectedpage (no execution/iframe). Every section realescapedtext; header/footer shared. No fake booking/cart/checkout success; storefront demo notice always visible.

- [ ] Add failing Node tests for factory/schema/operationsimmutability/IDstability/routeinvariants, transportvalidation/conflict/network, saving snapshot/failure behavior where testable without newdependencies.
- [ ] Implement pure helpers/transport/render+UI using contract, no heavyLab runtime import; sectionrenderer shared by overview and pageview. No compiler/export yet; banner says design draft, not built/published website.
- [ ] Green Node/syntax/focused bundle; own note `.agent-work/implementation/2026-10-16-builder-ui.md`; simplify. Parent owns fullbuild/cache/browser and schema rollout.
- [ ] Fresh independent review including UI state/accessibility; parent browser375/768/1440/keyboard/save/resume if authenticated, otherwise report exact limitation. No credential/password changes or real signup.

## Final acceptance / next boundary

- [ ] Coordinator source inspection and targeted reruns; existing signup149/landing53/hosting37/Node15 preserved; full npm build exit0, isolated Bladecache then PHPHTTPtests serially.
- [ ] Apply only named draft migration if verifiedlocal, no allpending/reset/seed; inspect schema without real data dump. No production or paidcalls.
- [ ] Browser verifies actual template structure/theme and nooverflow, no fakeAIsummary; save/resume/conflict tested through memoryfixtures and actual authenticated local if session available. Fictional testdraft only, no actual Labproject build/publish.
- [ ] Update Support/GUIDED-BUILDER-DRAFTS.md and index with facts/limits. Whole-slice independent review reports no unresolved actionable findings.
- [ ] Part2B still required: persisted revisions/undo, renderer-based compiledsource/export and entitlement-gated newproject materialization/build/deeproutes. Part3AI+onboarding continuation remain separate. Do not claim fullbuilder/Hostingerparity.

## Self-review

Blueprint/API fields and limits match across tasks; scoped security tests cover all5focuscases. Catalog and renderer staytrusted; no arbitrary assets/contentexecution. Storage not conflated withprojects oractual ecommerce. Existing signupflow remainsintact. Designintentapprovedpreviously; currentownerdelegateddetails; preserved subagentexecutionmethod. Dirtycheckout noautomaticcommit. Tests use MAMPPHP withopenssl,pdo_sqlite,mbstring,curl,fileinfo,zip,intl and forceSQLitebeforeproviders; no secrets/accountrecords/logs read.
