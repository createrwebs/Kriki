# Runtime safety: PHP preview stopgap

Updated:2026-10-17

## Implemented

Customer PHP is no longer included/executed in the Laravel process. The legacy `/lab/{project}/php-preview` endpoint checks ownership and returns423 `runtime_unavailable` for PHP projects, including requests for HTML/SVG subresources. It does not create/read a workspace. PHP source remains available through the existing owner-authorized file editor.

Full-page `/lab/{project}/preview` also rejects unavailable project types instead of opening a misleading React shell. The browser gets a trusted escaped source-only notice; JSON clients get `{code,message,runtime:{mode,can_execute,reason}}`. Responses are private/no-store and disallow scripts. No setting can enable in-process PHP execution.

`App\Lab\Runtime\RuntimePolicy` reports availability only, not an isolation security certificate. React browser preview retains its existing shell, guest-path sanitization and COOP/COEP headers. No source/database/account credentials or unrelated Hosting code were changed by this stopgap.

## Still unfinished — do not mark Gate S complete

The S1 message-binding sub-slice is implemented: workspace/private preview and cover replies now require the current iframe window, exact origin, project UUID and fresh per-load token; guest commands require the expected parent/origin and binding. Reload revokes old packets, and early build errors are retained until connection. See [bridge contract and remaining origin prerequisites](../docs/security/preview-message-bridge.md). This does not resolve same-origin execution.

- Existing React preview/service-worker URLs share the platform origin, and iframes allow scripts plus same-origin. COOP/COEP and UUID paths do not prove separation from platform cookies or owner APIs.
- A dedicated cookie-free preview origin plus a validated project/session message bridge and browser negative tests are still required. Do not remove safeguards or rely on an iframe sandbox attribute alone to claim this is solved.
- Other Hosting/external runners and deployment permissions are not certified by this limited patch. Public artifact serving was inspected as static delivery; it was not comprehensively audited for every upload/archive/header case.
- Python/Reflex/SSR execution remains a separately gated isolated-runner project. Do not use PHP include or host shell as a fallback.

## Verification

`tests/regressions/runtime-isolation.php` forces SQLite memory before provider bootstrap and copies a benign sentinel into unique temporary storage. Before the patch the customer PHP sentinel executed; after it the test proves no execution,423 JSON/HTML, escaping/security headers, ownership denial, source preservation, no workspace creation, unsupported-type denial and React shell/path/header preservation.

The suite is local controller/render integration, not proof of authenticated deployed HTTP middleware or browser isolation. Existing booking HTTP/CORS/account regression suites are separate preservation checks. Browser visual confirmation must be reported separately if the connected browser is unavailable.

2026-10-17 message-binding verification: Node 35/35, runtime-isolation.php 24 checks, final frontend build exit 0, independent review clean. Actual Chrome fixture passed 15/15 using two loopback origins and fictional projects; screenshot: [browser evidence](../.agent-work/qa/2026-10-17-preview-bridge-browser.png). This checks the message protocol, not authenticated platform cookie isolation. Reusable fixture command is documented in the bridge contract.

Next task: finish S1 origin isolation before claiming multi-tenant runtime safety; see docs/superpowers/plans/2026-10-16-platform-01-safety-modules.md.
