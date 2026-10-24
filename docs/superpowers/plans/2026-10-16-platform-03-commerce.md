# Cart, Orders and Manual PromptPay Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** ลูกค้าซื้อสินค้าจากเว็บที่สร้างได้จริง สแกน PromptPay แนบสลิป และเจ้าของร้านตรวจยืนยันจากหลังบ้านโดยแยกจากการจอง

**Architecture:** Standard React commerce renderer + Laravel scoped API, server-owned catalog/amounts, pinned Supabase orders and private slip storage. Existing platform billing untouched.

**Tech Stack:** Laravel13/PHP8.3+, React19, Supabase Postgres/Storage, reviewed PromptPay library plus existing QR renderer if compatible.

**Spec:** ../specs/2026-10-16-platform-evolution-design.md §8; Support/PROMPTPAY-MANUAL-PAYMENT-RESEARCH.md

**Status:** Draft. Dependencies:S gates. Gate C. Default booking deposit:none; do not charge bookings without separate approved policy.

## Global Constraints

- Platform billing กับ merchant orders/payments เป็นคนละ ledger และ credential boundary
- สลิปและข้อมูลติดต่อเป็น private; ห้าม public bucket, public slip URL หรือส่งเข้า AI โดยปริยาย
- เงินเก็บเป็น integer satang, currency THB; เวลาเก็บ UTC และแสดง Asia/Bangkok
- Upload slip ไม่เท่ากับ paid; ร้านเป็นผู้ตรวจและ audit actor มาจาก session
- Spec§4 applies; slip5MiB/20MP; signedownerimage5minutes; quote10minutes; guestcapability24hours; no real transfers.

## Review Focus

1. Modify price/recipient/status from DevTools — C1/C2 serverauthority tests.
2. Lost response and same order retry — C2 idempotency/concurrentinsert tests.
3. Upload matches extension but is executable/huge decoded image — C3 filecontent tests.
4. Two tabs approve/reject same slip — C4 CAS/audit tests.
5. Cancel booking/order after moneyaccepted — C4 preserve payment/noautomaticrefund tests.

## Task C1: Catalog/payment configuration and additive datastore

**Files:** Create app/Lab/Commerce/CommerceDatastore.php, CommerceInstaller.php, ProductCatalog.php, PaymentSettings.php; database/sql/lab-commerce/0001_initial.sql; app/Http/Controllers/Lab/LabCommerceController.php; routes/lab-commerce.php; tests/regressions/commerce-catalog.php. Modify modulecatalog and routes/web.php.

**Interfaces:** ProductCatalog::save(User,LabProject,array $product,int $expectedVersion):array; PaymentSettings::save(User,LabProject,array $config,int $expectedVersion):array; CommerceInstaller::install(LabModuleInstallation):void. ProductUUID/servername/price_satang positive integer≤100000000/active/version. Settings recipient_type phone10/national_or_tax13/ewallet15, recipient normalizeddigits, payee_label, enabled, instructions plaintext≤2000; reject unknownkeys.

- [ ] Red: twoowners/sameownerprojects cannot read/write eachothercatalog/settings; negatives/floats/unknownrecipients422; missing paymentconfig keepscheckoutdisabled; updateversion409. Existing billingroutes unchanged.
- [ ] Implement namespaced fixedtables/RPCs with module/project keys, RLS/directaccessrevocation; additiveinstaller compatibilitycheck+transaction. privatebucketcreation verifies ownedprivatebucket; never makesexistingpublicbucket availablefor slips. Serverauthoritative products may be seeded only from an owner-reviewed catalog proposal, not scraped from browserprice.
- [ ] Review QR choices against existing composer `bacon/bacon-qr-code` to avoid installing a second imageengine unnecessarily. Pin reviewedpayloadimplementation, retainlicense/sourceattribution if copied. Do not copy README samplecontroller that trusts recipient/amount fromquery.
- [ ] Green: fakeHTTP unit/contracts; live opt-in idempotentinstall on uniquelynamespaced fixture withfailure rollback; noexistingtablechanges. QR vectorsdecodeamount/recipient/CRC; noactualbankpayment.
- [ ] Document endpointcontract in docs/api/commerce.openapi.yaml (authoritative) and localcontractexamples; keep allnewroutes markedplanned until implemented. Parsevalidation required beforeUI consumer.

## Task C2: Quote/order creation and scoped guest access

**Files:** Create app/Lab/Commerce/OrderService.php, GuestOrderAccess.php; app/Http/Controllers/Api/LabCommercePublicController.php; resources/js/lab/commerce-runtime/client.js; tests/regressions/commerce-orders.php, tests/regressions/commerce-client.test.mjs. Modify routes/api.php and module-specific CORS/rate-limit middleware, ServeLabPublication reservedAPI bypass.

**Interfaces:** OrderService::quote(installation,items):`{quote_uuid,catalog_version,items,total_satang,currency,expires_at}`; createOrder(installation,quoteUuid,customer,key):`{order_uuid,reference,status,access_token,expires_at}`. GuestOrderAccess issues random256-bit token, stores hash/order/project/expiry/revocation and compares safely. Visitor endpoints `/api/lab-commerce/{module_uuid}/catalog`, `/quotes`, `/orders`, `/orders/{order_uuid}`; order operations require `X-Krikkit-Order-Token`, publiccatalog excludescontacts/privatefields.

- [ ] Red: alteredtotal/recipient ignored or rejected; forgedproduct/pastquote409; inactiveproducts409; validqty integer1–99 and max50lines; customer name1–120/emailvalid≤254/phonevalid; replaysamekey yieldssameorder, differingpayload409. UUID withouttoken cannot read/upload; tokenA cannotaccessorderB; expires/revoked denied.
- [ ] Implement one atomiccreate RPC, snapshotproduct/name/price/recipient, payloadhash unique(module,key). Samekey replay must safely re-issue access without storingplaintext token: issue a fresh scopedtoken only after matching submitted creation key+hash; do not disclose existingorder to arbitraryguessers. Scope retries and redactkey/token/customerbody fromlogs.
- [ ] Runtimeclient keeps cartIDs/qty in localStorage under project/module key, never customer/slip there. Guesttoken in sessionStorage with24hex/UUID context checking; never URL/referrer. Requests omitplatformcredentials. Exact verifiedpublicationorigins CORS; absenceofOrigin doesn't grantprivilege. Rate limits create10/min/IP/module and100/min/module, uploadlimits C3.
- [ ] Green: order/concurrency/accessfixtures, realHTTP contenttype/OPTIONS/deeproute/CORS tests, secret-free artifacts. Closed/disabledmodule blocksneworders but authorizedguest/ownerhistory remains readable underretentionpolicy.
- [ ] Update OpenAPI/error matrix:422validation,409version/idempotency,413size,429rate,503upstream,401/404privateaccess withoutoracle. Generate Postmancollection fromspec only if CLIavailable; never overwriteexistingworkspacebinding orpushremotely.

## Task C3: Private slip submissions and upload metadata

**Files:** Create app/Lab/Commerce/SlipValidator.php, PaymentAttempts.php, PrivateSlipStorage.php; app/Http/Controllers/Api/LabPaymentSlipController.php; tests/regressions/payment-slip-upload.php; database/sql/lab-commerce/0002_payment_attempts.sql.

**Interfaces:** PaymentAttempts::submit(installation,order,guestToken,UploadedFile,array $declaredPayer,string $key):array `{attempt_uuid,status:'submitted',uploaded_at}`. POST `/api/lab-commerce/{module}/orders/{order}/slips` multipart file/payer_name/email/phone/claimed_transferred_at?/idempotency_key; valuesnapshots explicitlyservervalidated. GETowner signedsliponly afterownerpermission, no publicURLs.

- [ ] Red: otherproject/expiredtoken/oversizedfile/disguisedMIME/SVG/HTML/20MP+ rejected; uploadserverclock independentfromclaimedtime; samekeyretry oneattempt; uploadfailure nofalsepending; acceptedorder rejects newattempt. Missingrequiredcontacts422.
- [ ] Implement fileinspection+safeimage decoder/re-encoder where supported; UUID/randomobjectkey, privatebucket, hash, MIME/size/dimensions. Validation does not use uploaderfilename aspath. No OCR/AIprocessing. Stripmetadata. Verify decoderavailability beforeaccepting aformat; do not disablevalidation on localenvironment.
- [ ] Separate stagedfile upload from transactionalattemptmetadata with exact compensatingcleanup/reconciliationqueue fororphans. Neverdeletepreviousattemptonreject. Usependingoperationreceipt for uploadretry; no claim atomictransactionacrossStorage+Postgres. Rate cap5uploads/min/order,20/hour/order and100/hour/module initial configurableceilings.
- [ ] Green: localfakefiles withfictionaldata, faultinjection betweenobjectupload/databasecommit; anonymous/privateaccessdenied; samefilehash flagsduplicate onlywithinsamemerchant. LiveSupabase smokeuses synthetic image only; no userbankstatement uploadedwithout specificapproval.
- [ ] Record upload_time, claimedtransfer_time, verified_time distinction andretentionapproval prerequisite. No automatic deletion180days untilownerprivacy/retentiondecision and recoveryprocess exist.

## Task C4: Owner Orders/Payments desk with immutable review

**Files:** Create resources/js/lab/components/commerce/CommerceDesk.jsx, ProductEditor.jsx, OrdersList.jsx, OrderDetail.jsx, PaymentsReview.jsx, PaymentSettingsEditor.jsx; app/Http/Controllers/Lab/LabPaymentReviewController.php; app/Lab/Commerce/PaymentReview.php; tests/regressions/payment-review.php. Modify WorkspaceTabs/Stage and module API client.

**Interfaces:** PaymentReview::review(User,LabProject,string $attemptUuid,string $expectedStatus,int $expectedVersion,string $decision,string $reason):array. Owner GET `/lab/{project}/commerce/{module}/orders|payments`, GET `/orders/{order}`, POST `/payments/{attempt}/review` `{decision,expected_status,expected_version,reason}`; currentowner fromsession, noactorfields accepted.

- [ ] Red: guest/A↔Breviewdenied, sameownerwrongprojectdenied, duplicateandconcurrentapprove/reject onlyonewinner/event, rejectedattempt immutable, acceptedpaymentcannot be reset byordercancel, noautomaticrefund route.
- [ ] Implement lockedreview RPC/eventappend; approverequiresownerbankcheck acknowledgement inactualUI, rejectionreasonrequired1–1000 chars. Decisionupdates paymentstatus only; orderderivedstate documented, bookingunchanged. Do not autogenerate legalreceipt/taxinvoice.
- [ ] UIshows contact/payee/amount/orderitems/slip, Bangkokserveruploadtime vsclaimedtransfer, reviewer/verifiedtime; paginated25 max100. PrivateviewerURL5minutes; revokedpermission blocksfreshview. Downloads explicit andprivacyaware.
- [ ] Green: regression+keyboard/loading/empty/error/conflict browserstates, refreshpersistence, bookingdeskuntouched; unsignedslipurlfails. Auditstores actoridentityfromsession evenifclientpostsforgedname.
- [ ] Independentreviewsecurity/datahistory before Cgate; no paymentmarkingtestonrealcustomerrows.

## Task C5: Standard generated storefront integration and real build gate

**Files:** Create resources/js/lab/commerce-runtime/Cart.jsx, Checkout.jsx, OrderStatus.jsx, index.js; app/Lab/Commerce/CommerceEmbed.php; components/modules/ModuleEmbedPanel.jsx; tests/regressions/commerce-embed.php, cart-runtime.test.mjs. Modify agenttoolcatalog/orchestration for approvedmoduleembedding, not freeformpaymentimplementation.

**Interfaces:** CommerceEmbed::files(installation):array sourcefilemap + nonsecretapiOrigin/moduleUUID; Cart/Checkout props `{apiOrigin,moduleUuid}`. Install under src/krikkit-commerce/, never autooverwriteApp.jsx; owner/AI-reviewedroutepatch mounts `/cart`, `/checkout`, `/order` withnoaccesssecretsinpath. Protectedruntimefiles cannot be replacedby ordinary AIwrite_file.

- [ ] Red: Addproduct createsline notcounteronly, qty/remove/cartpersistence, serverquote/reprice409, checkout→pendingreview notpaid; awaitretainedactualJSX in productionbundle. Embedpreserves bookingroutes/unsavedVFS; nosecretinmanifest.
- [ ] Integratecafé asfirstfictionalacceptancecase; managedmodule install/import event mergesonlyinstalledfiles. AI canpatchvisualwrapper/routes but backendstate remainsmanaged. Existing contactdemo muststay clearlydemo, not newfalsepersistentclaim.
- [ ] Green: frontend/unit/PHPcontracts, host/runtimebuild, actualbrowsercustomerpublication anddeepcart/checkout/orderpaths. Testinvalidcheckout→validorder→downloadQR→syntheticslip→ownerreject→resubmit→approve→refresh. Usermustverifyrealrecipientbeforeanyliveuse; agentdoesnottransfermoney.
- [ ] Crossaccountandsharedrefisolation/secret/CORS tests; restorelastgoodsourceifartifactfails, do notrollbackacceptedpayments withsource. Artifact manifest includes source/artifact hash, runtime/module/required-schema/SDK versions; block promotion or code rollback when schema compatibility fails. Screenshotcustomer+ownerresult.
- [ ] Update Support/COMMERCE-MODULE.md, readinessfeatureflag, masterledger andreviewevidence. GateC onlyafteractualflowpassed; pricing/landing maythen showavailable.

## Execution Handoff

Native implementation proposed afterplanreview; C1→C2→C3→C4→C5 sequential interfaces. Userbookingdepositquestion remains a documented non-blocker forproductcheckout but blocks bookingcharges. Do notrequirea realPromptPayID to runpuretests; donotpublishfictionalrecipientasworkingmerchant.
