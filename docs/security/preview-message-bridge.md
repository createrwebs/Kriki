# Private preview message bridge

Updated: 2026-10-17. Implements the message-binding portion of platform safety task S1; **Gate S remains open**.

Workspace preview, the private preview tab and cover capture accept `MessageEvent` only when its sender is the current iframe window, its origin equals the iframe's exact HTTP(S) origin, and its packet includes the current project UUID and per-load token. A public `source` marker alone has no authority.

On iframe load, the host creates a fresh random token and sends `bridge-connect` to the exact guest origin. The injected guest chrome is generated with the expected platform origin; it accepts the connection and subsequent commands only from its parent at that origin. Both directions include project/token and use an exact target origin. Token fields in caller payloads cannot override the host binding. Reload rotates the token; changing iframe source or detaching the ref invalidates the old binding. Ref callbacks revoke bindings when the project changes or the iframe unmounts.

Connection replays location and painted state. Up to 20 errors produced before connection are retained and sent after the initial paint report, preserving the build-error Fix card when load is delayed. Cover-result replies use the same sender/origin/project/token validation in addition to their capture request ID.

## Verification

```powershell
node --test tests/regressions/preview-message-binding.test.mjs
node --test tests/regressions/*.test.mjs
node tests/regressions/preview-message-browser.mjs
```

The last command starts a manual, loopback-only fixture and prints its host/guest URLs. Open the host URL in a browser; the visible result must show all 15 checks passed. It uses fictional IDs, no Laravel authentication and no customer data. Stop the fixture process after verification. It checks real cross-origin iframe messages, sibling rejection, reload/inspection and stale-token rejection; Node tests additionally execute the generated guest script to check invalid parent/origin/commands and early errors.

## Remaining S1 requirements

This protocol binds messages; it does not isolate a malicious script running inside the authorized guest frame. Current browser runtime/service-worker URLs still share the platform origin and preview iframes still allow scripts plus same-origin. Same-origin code can reach platform APIs/storage regardless of this bridge. Do not enable further tenant capabilities or claim isolation from these tests.

The remaining work must move untrusted document delivery and runtime/service-worker execution to a dedicated cookie-free origin, separate platform session cookies/storage, and restrict any broker to a project/session capability. A configurable iframe URL alone is insufficient: the service worker, runtime asset routing, HMR and private-preview tunnel currently depend on platform origin and require migration together. Do not add permissive CORS or an unsafe host-execution fallback.

Acceptance still requires authenticated browser negative tests against owner APIs and sibling projects, plus actual static React/booking preservation on the new origin. PHP/Python/SSR remain source-only until a separately audited runner exists. No deployment, DNS, database migration or production capability change is delivered by this patch.
