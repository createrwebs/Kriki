# Guided builder — Part 1: Signup business profile

This part adds optional company details to the existing registration form and saves an account-owned business profile. The account fields, registration action, CSRF protection, email verification and existing home redirect are retained. Company name, business description and website budget can be left empty; currency defaults to THB, with USD available. No currency conversion is performed.

The website budget is not a package price, AI credit balance or payment authorization. Registration does not charge the user, call an AI provider or automatically share the budget with AI. Budget fields are hidden from default profile serialization and are not included in the public Lab bootstrap. See the exact field/validation contract in `docs/contracts/signup-business-profile.md`.

Only Part 1 signup/profile is implemented here. Website type selection, the AI/Template path, structure editor, blueprint generation, multi-page canvas, theme controls and continuation to a builder after verification remain later work. This is not completion of the full Guided Builder or Gate S, and no website is generated or published by signup.

## Storage and rollout

The additive migration is `database/migrations/2026_10_16_100000_create_user_business_profiles_table.php`. It creates one optional profile per user, with nullable company/description and `decimal(14,2)` website budget. Existing members do not require a profile or backfill. User, profile and default entitlement are created in one transaction; verification and login happen after the transaction commits.

The coordinator applied only this named migration to the explicitly approved local database and checked the local schema. This implementer did not run a migration or create a real account. No production migration, deployment or automatic rollout has occurred. Other environments require their own normal reviewed migration/release process; do not run unrelated pending migrations as part of this change.

## Verification evidence

The signup regression suite forces SQLite memory before application bootstrap and fakes notifications and HTTP. Its 149 checks pass with MAMP PHP 8.5.10 and the openssl, pdo_sqlite, mbstring, curl, fileinfo, zip and intl extensions. They cover storage/decimal rules, ownership, atomic rollback including entitlement, legacy signup, actual HTTP middleware, verification/redirect preservation, all four new POST fields, optional controls, labels/help/errors, escaped validation redisplay, empty amount with USD, rejected array/boolean redisplay, and passwords never flashed.

The form uses the existing Sora auth layout and theme tokens, one optional fieldset, and a budget/currency row that stacks on mobile. New controls have explicit visible labels and associated help/errors. The browser amount constraints are `min=0`, `step=0.01`, `max=999999999999.99`; server validation is authoritative.

Coordinator acceptance (2026-10-16):

- Signup suite: 149 checks; landing: 53; hosting safety: 37; Node: 15/15. Final runs all exited 0.
- `npm run build` exited 0 (4,814 modules). It still reports font fallback, generated CSS, runtime browser-externalization, dynamic-import and large-chunk warnings; this signup change does not resolve those separate build warnings.
- Isolated `artisan view:cache` exited 0. Signup tests passed again after caching.
- Independent reviewers accepted storage/validation, atomic registration, the form/integration, and the narrow local-runner intl fix with no unresolved actionable findings.
- Actual local GET returned 200 with the new fields and no fatal-error body. The owner restarted the runner; the new listener was verified on 127.0.0.1:8892 with intl enabled. No php.ini change was made.
- Browser checks at 375/768/1440 px found no horizontal overflow. Mobile stacks amount/currency; larger sizes place them side by side. Labels/help are associated with all four optional controls. Keyboard traversal reaches company → description → amount → currency → Register. Thai fixture text, exact `25000.50` and USD selection were checked without submitting, then the form was reset and the temporary viewport override removed.

All successful/invalid registration POSTs and email verification tests used the isolated database and fake transports, not a real local account or delivered email. MySQL verification covers the additive schema, not a real account roundtrip. Screenshots with fictional, unsent form data are saved under `.agent-work/qa/2026-10-16-signup-{mobile,tablet,desktop}.jpg` for local inspection; that directory is ignored.

One implementer run initially failed the post-cache guest GET assertion; subsequent clean serial author, coordinator and independent-reviewer runs passed all 149 checks without a product/cache fix. Its cause was not established. Do not claim it was diagnosed or hidden by a retry workaround.
