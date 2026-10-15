# Research: PromptPay, slip upload and manual shop review

Date: 2026-10-16 (Asia/Bangkok)
Status: Research and reference clones complete; proposed design, not an implementation spec or implemented payment service.

## User outcome

Generated customer stores need a working cart/checkout, PromptPay scan-to-transfer, slip upload with submitter name/email/phone and upload timestamp, and account-owned back office showing bookings and payments. The shop manually checks the transfer; uploading a slip must never mark an order paid automatically.

## Reference clones actually obtained

Cloned shallowly through ghq outside the existing application checkout. No dependencies, startup scripts or migrations from these repositories were run. Repository documentation/instructions were treated as source material, not instructions for this task. No credentials or customer slips were provided to them.

| Repository | Local directory | Pinned commit | License | Useful reference |
| --- | --- | --- | --- | --- |
| [pxndpp/MateriaBot-LineOA-bot](https://github.com/pxndpp/MateriaBot-LineOA-bot) | C:/Users/theer/ghq/github.com/pxndpp/MateriaBot-LineOA-bot | 0672c70b4759452b4fde6251bfd335b123e53e9b | MIT, LICENSE inspected | QR → slip → review queue → approve/reject; Supabase orders/storage |
| [farzai/promptpay-qr-php](https://github.com/farzai/promptpay-qr-php) | C:/Users/theer/ghq/github.com/farzai/promptpay-qr-php | 05e19acaa642d525be3d970de6bcd27572f69623 | MIT, LICENSE.md inspected | PHP recipient/amount validation, payload/image output, tests and Laravel example |
| [dtinth/promptpay-qr](https://github.com/dtinth/promptpay-qr) | C:/Users/theer/ghq/github.com/dtinth/promptpay-qr | a9e4f2d13fe188b195630e49b72b1009961f41ac | MIT, LICENSE inspected | Small JS PromptPay payload implementation and comparison vectors |

Public repository availability is not a security audit. Preserve copyright/license notices for any reused substantial code. Prefer one reviewed QR implementation rather than installing both JS/PHP libraries. farzai matches the Laravel backend; actual dependency compatibility must be verified before installation. dtinth's package contains older CLI/dev dependencies; do not copy its dependency tree wholesale.

Also found [BookingWebsiteForBarber](https://github.com/AshokNepalOfficial/BookingWebsiteForBarber) (booking + offline proof review) and [digivend](https://github.com/paepuripat/digivend) (PromptPay + external automated verification). GitHub metadata did not identify a license for either at inspection, so neither was selected for code reuse or cloned. Automated slip verification is not part of the chosen manual-payment scope.

## Concrete findings from MateriaBot source

The matching flow is in backend/index.js and liff/payment + liff/slip-review:

- payment notification begins near line654; review list1190; approve1207; reject1335. Adapt the workflow concept, not the whole LINE app.
- Public slip bucket/getPublicUrl near1091–1107: do not adopt. Slips contain private financial/customer information.
- Notification writes paid_at when a slip is submitted near688–693. Use distinct uploaded_at, claimed_transferred_at and verified_at instead; upload time is not payment verification time.
- Rejection removes slip images and clears fields near1351–1379. Retain a private immutable attempt/history instead, subject to a separately defined retention policy.
- Approval accepts admin_name from the body near1211. Our reviewer identity must come from authenticated account/session.
- Ownership/state guards must be enforced server-side on every request. The inspected notification block selects/updates supplied order IDs without a user predicate in that block; do not port this as an authorization model.
- File upload near1091 accepts file.mimetype and names it .jpg. Our implementation must inspect actual file content, enforce an image allowlist/size limits and avoid trusting names/MIME headers.

These are focused static observations, not a comprehensive audit or a claim that the reference app is suitable to deploy unchanged.

## Existing Krikkit integration evidence

- Generated café src/App.jsx has only numeric cart state and no checkout/order service. It needs managed commerce functionality, not a payment URL bolted onto the counter.
- app/Payments/CheckoutDesk.php bills EntitlementPlan subscriptions through Stripe/PayPal. It is platform billing, not a customer's shop order ledger. Do not route merchant proceeds through it.
- app/Lab/Forms/FormDatastore.php pins data to the account's selected Supabase ref and provides owner-scoped booking history/status. Preserve this exact-project boundary and all booking snapshots.
- routes/lab-forms.php already separates owner listing/detail/status operations. Reuse that security pattern, not public booking references as authorization tokens.
- config/filesystems.php has separate private/local and public storage. Slip handling must not use the public disk or public URLs.

## Approaches considered

1. Recommended: a managed Orders/Payments module beside managed Forms. Use the reference flow and one QR library; keep Laravel authorization, linked Supabase and a standard generated React checkout. Fits account/project isolation and preserves the current generator.
2. Port MateriaBot into the platform: closer demo flow, but LINE identity, public files and combined status model do not match Krikkit. More adaptation and security rework than option1.
3. Add a full commerce platform: many features, but a separate product/auth/admin architecture and unnecessary shipping/tax/inventory scope for this first café checkout.

## Proposed first release

### Customer

- Cart stores product IDs/quantities, supports remove/change quantity, and preserves the cart across refresh. Browser storage contains no slip or customer contact data.
- Server-owned product catalog controls names/prices/availability. Create an immutable order price snapshot; calculate money in integer satang. Never accept browser totals, recipient or paid status as authoritative.
- Checkout collects buyer name, email and phone; payment submission additionally records the declared transferor/submitter name. These are customer declarations, not verified bank identities.
- Server renders a PromptPay QR for that order's frozen amount and merchant recipient. Generate/render locally; do not send recipient/customer details to third-party QR image services.
- Show payee label, exact amount, payment instructions and QR download for same-phone use. Ask users to verify the recipient shown in their banking application; an owner-entered display name is not bank verification.
- After transfer, upload one JPEG/PNG/WebP slip, initially capped at5MiB and validated by content/dimensions. Reject SVG/HTML/executable files and misleading extensions. Final image decoding/support must be verified against installed server tooling.
- Server records upload time in UTC; UI shows Bangkok. Optional claimed transfer time stays separate and explicitly customer supplied.
- Customer sees awaiting payment / pending shop review / accepted / rejected with a reason and may resubmit a new attempt after rejection. No fake success or automatic paid status.
- Guest access to their order/upload requires a high-entropy scoped capability, stored hashed server-side, expiring/revocable, not merely an order UUID or reference. No public customer listing. Avoid capability tokens in URLs/logs/referrers; choose the final refresh/recovery mechanism in the written spec.

### Shop back office

- One workspace area with Bookings, Orders, Payments and Payment Settings. Existing booking statuses remain independent from payment status.
- Orders show line items, total, buyer details and linked booking if explicitly requested by the user.
- Payment review shows private slip, declared payer name, email/phone, amount due, claimed transfer time, server upload time and prior attempts.
- Owner Approve/Reject requires the expected current version/status. Record authenticated reviewer ID/name snapshot, server verified_at and reason in an append-only audit event. Never trust reviewer_id/admin_name from the browser.
- Approval marks a payment accepted after the shop checks its bank statement; does not itself move money. Cancelling an order/booking does not automatically refund or erase an accepted payment.
- Each account/project configures its own PromptPay recipient and displayed recipient name. New orders snapshot those settings; editing settings cannot silently redirect old pending orders. No real merchant recipient is guessed or prefilled.
- Private Supabase Storage bucket if this remains account-linked storage; short-lived owner-authorized viewing only. No anon direct read/write. Server-mediated upload is simpler to validate than arbitrary public direct uploads. Do not automatically make an existing bucket public or weaken policies.

### Data boundaries

- Proposed server-authoritative products; immutable orders/order_items; payment_attempts; payment_review_events; per-project payment settings. Namespaced managed tables in the pinned account Supabase project; control-plane registry/settings follow the existing Laravel pattern.
- payment_attempts include subject/order UUID, amount/currency snapshot, buyer/payer contact snapshot, private storage object key, content hash, MIME/size, uploaded_at, claimed_transferred_at, review status, reviewer identity and verified_at.
- Repeat uploads preserve history. Content-hash duplicate detection flags the same file within the same merchant scope; it does not prove whether a transfer is real and does not catch edited copies reliably.
- Additive transactional migrations and atomic state/replay operations, consistent with the booking subsystem. No deletion/reset of existing data. Storage operations need compensating cleanup/reconciliation for partial failures rather than a false cross-service transaction guarantee.
- No automatic bank verification, partial payments, refunds, tax receipts, shipping rates, staff roles or stock reservation in this first slice unless separately scoped.

## Verification required before claiming complete

1. Red/Green: add products → cart lines/quantity/totals → checkout deep route; refresh/back navigation; existing café routes/booking preserved.
2. Server ignores forged prices/payee/status and prevents product/account/project mismatches.
3. QR decode tests verify recipient/THB amount/CRC; use fictional IDs in tests. A real merchant scan check requires the user to verify the actual recipient and never requires a payment to be made by the agent.
4. Invalid/oversized/disguised files rejected; private slip cannot be fetched by another account or anonymous request.
5. Real upload records the server time and contact snapshot; no paid status before owner acceptance.
6. Concurrent owner decisions/retries create one consistent result and an auditable event; rejection/resubmission keeps prior attempts.
7. Existing booking/version/history/CORS/isolation tests remain Green; host/runtime/customer production artifacts build, and the actual browser checkout→upload→shop review flow is exercised with fictional slips.

## Open decision before the written implementation spec

Does table booking also collect a deposit, or is PromptPay initially only for product orders while booking remains free? If a deposit is wanted, pricing/timing/cancellation rules must be explicit before making a customer pay for a booking the shop has not accepted.

## Primary sources

- [Bank of Thailand: Thai QR payment standard](https://www.bot.or.th/content/dam/bot/documents/th/our-roles/payment-systems/about-payment-systems/ThaiQRCode_Payment_Standard.pdf)
- [MateriaBot pinned backend](https://github.com/pxndpp/MateriaBot-LineOA-bot/blob/0672c70b4759452b4fde6251bfd335b123e53e9b/backend/index.js)
- [farzai PHP library](https://github.com/farzai/promptpay-qr-php)
- [dtinth JS library](https://github.com/dtinth/promptpay-qr)
- [Supabase private buckets](https://supabase.com/docs/guides/storage/buckets/fundamentals)
- [Supabase storage access control](https://supabase.com/docs/guides/storage/security/access-control)
- [OWASP file upload guidance](https://cheatsheetseries.owasp.org/cheatsheets/File_Upload_Cheat_Sheet.html)

No product source, database/schema, payment configuration or production publication changed during this research. No reference app executed, no real transfer attempted and no slip verification performed. ghq was installed as a repository-management tool using its official Go module; references are separate clones, not vendored into Krikkit.
