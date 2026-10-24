# Guided Builder Signup Profile Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** เพิ่มข้อมูลบริษัทและงบทำเว็บไซต์ในฟอร์มสมัคร และบันทึก user/profile อย่างปลอดภัยโดยไม่ทำให้สมาชิกเดิมหรือ verification เดิมเสีย

**Architecture:** เพิ่มตารางbusiness profileแบบ1:1กับusers ไม่เพิ่มmass-assignablebusinessfieldsลงUser สมัครผ่าน controllerเดิม แต่แยกpayloadและtransactionก่อนRegistered event/login ไม่มีAIหรือbillingจากsignup ไม่เชื่อมredirectใหม่จนbuilderพร้อม

**Tech Stack:** Laravel13/PHP8.4.1+ตามvendorที่ติดตั้ง, Livewire/Blade, existing auth/verification, MySQL runtime, SQLite-memory regressions, PowerShell local server

**Spec:** `../specs/2026-10-16-guided-site-builder-design.md` §3; shared constraints§9/11/12. Index: `2026-10-16-guided-site-builder.md`

## Global Constraints

- “รวมข้อมูลบริษัท/งบในฟอร์มสมัคร”; company optional max255, business description optional max2000, budget optional non-negative decimal, currencyTHB default/USD supported
- งบนี้เป็นงบทำเว็บไซต์ ไม่ใช่AIcredit/subscriptionprice/paymentauthorization ไม่ส่งงบเข้าAI/log/exportอัตโนมัติ
- คงname/email/password/confirmation, uniqueemail, throttle, CSRF, invited/member role, verified middlewareและredirectเดิม;ไม่มีbackfillบังคับสมาชิกเก่า
- ไม่มีprovidercall,realemailส่ง,planpricechange,productiondeploy,copyVoxelSite หรือmigration/reset/seedฐานข้อมูลจริงจากtest
- Dirty checkout: preserve unrelated changes, no bulk stage/reset/commit. Per-task commit only explicit changed paths when authorized and branch/index safe; otherwise ledger records no commit
- Existing browser profile/sessionห้ามรีเซ็ตรหัสผ่าน;UI signupใช้fixtureหรือบัญชีสมมติในisolateddatabase ไม่สร้างบัญชีจริงเงียบๆ

## Review Focus

1. Optional company/budget missing from legacy signup still registers; no blank or misassigned model fields. Task1/2 tests
2. Decimal edge cases/scientific notation/negative/over-capacity values fail validation, not DB truncation orfloat rounding. Task1 tests
3. Profile insert failure must roll backuser and default entitlement and emit no Registered event/login. Task2 tests
4. Budget cannot appear through default eager serialization/public bootstrap; future AI context must opt in explicitly, not serializeprofile wholesale. Task1/2 tests
5. Old input containingHTML/script is escaped on validation redisplay;passwordneverflash;Thai longlabels remainusable375px. Task3 tests/browser

## File Structure / Existing Evidence

- Existing: `RegisterRequest` validatesname/email/passwordonly;`RegisteredUserController::store()` directlycreatesUser thenRegistered/login
- `User` autoassigns default entitlement oncreated when tableexists;transaction must include this side effect
- `User::sendEmailVerificationNotification()` usesConfirmEmailAddress;existingOpaqueEmailToken verificationcontroller remainsunchanged
- `resources/views/components/form/input.blade.php` hasrequired=true default;optionalfields must explicitlypass`:required="false"`
- New schema/model belongtoaccountprofile;validation remainsinsideRegisterRequest;controllerkeepsregistrationorchestration. Nonewservice/helper unless actualduplicatebehaviorneedsit
- Existing `tests/regressions/forms-bootstrap.php` providesisolateddatabase/checkhelpers;forceDBsqlitebeforebootstrapandfakeHTTP/notifications,notreadactualcustomerdata
- NoexistingPostmanfolder/TestCasefound. MaintainlocalregistrationcontractMarkdownandPHPHTTPtests;nonewremotePostmanworkspace/upload

## Task 1: Optional business profile storage and validation

**Files:**
- Create `database/migrations/2026_10_16_100000_create_user_business_profiles_table.php`
- Create `app/Models/UserBusinessProfile.php`
- Modify `app/Models/User.php`, `app/Http/Requests/Auth/RegisterRequest.php`
- Create `tests/regressions/signup-business-profile.php`
- Create `docs/contracts/signup-business-profile.md`

**Interfaces:**
- Consumes `User` existing identity; `RegisterRequest::rules():array`
- Produces `User::businessProfile():HasOne`, `UserBusinessProfile::user():BelongsTo`, `RegisterRequest::authData():array` (name/email/password only), `RegisterRequest::businessProfileData():array` (four profile keys, nullablevalues,THBdefault);profilefields `company_name`, `business_description`, `website_budget_amount`, `website_budget_currency`
- Table `user_business_profiles`:id, uniqueFKuser_id→users/cascade, nullablecompany_name255, nullabletextbusiness_description, nullabledecimal(14,2)website_budget_amount, currencychar3defaultTHB,timestamps
- Model allowlist excludesuser_id foruntrustedmassassignment;relationshipcreate setsowner. Castamountdecimal:2 (string,notfloat);hideamount/currencyfromdefaultserialization. Company/descriptionnotappendedtoUserpublicbootstrap
- ValidationflatPOSTkeys above,nullablecompany/string/max255, description/string/max2000, amountnullabledecimalformat `^\d{1,12}(\.\d{1,2})?$`,currencyoptionalTHB/USD;normalizeblankcurrencytoTHBonlywhenabsent/empty. No currencyconversion

- [x] **Step 1: Write failing fixture tests**

Use actualmigration againstmemoryusersfixture. Assertuniqueuser_id andFKownership,legacyUserno-profileworks,decimal'1234.50'roundtripsasstring,defaulttoArraycontainsnobudget;requestvalidationcases:

```php
// Fixture checks; never actual email/account.
formCheck($validatorFor(['company_name'=>str_repeat('ก',255)])->passes(), 'Thai company255 accepted');
formCheck($validatorFor(['company_name'=>str_repeat('ก',256)])->fails(), 'Company256 rejected');
foreach (['-1','NaN','INF','1e3','12.345','1000000000000'] as $amount) {
    formCheck($validatorFor(['website_budget_amount'=>$amount])->fails(), 'Invalid budget rejected');
}
formCheck($validatorFor([])->passes(), 'Old signup payload remains valid');
formCheck($validatorFor(['website_budget_amount'=>'0','website_budget_currency'=>'USD'])->passes(), 'Zero/USD accepted');
formCheck($validatorFor(['website_budget_currency'=>'EUR'])->fails(), 'Unsupported currency rejected');
```

- [x] **Step 2: Run intended Red**

Run PHP regression usingdocumentedMAMPbinary/extensions and `DB_CONNECTION=sqlite;DB_DATABASE=:memory:` beforeproviderbootstrap. Expectednonzero becauseprofilemigration/model/validationnotimplemented;labelcorrectfailure,notmissingextensionfailure

- [x] **Step 3: Implement exact schema/relations/validation**

Migrationadditiveonly;modelhiddenbudget/casts/relations;requestwhitelistsprofilefieldseparatelyfromauthfields. Do not expandUserfillable. Contract document lists flat fields/optional/defaults, success 302 and validation 302/session errors (existing bootstrap exception policy excludes /register from JSON errors), rate/CSRF/noAI/nocharge; actual service is a web form, not a new public API.

- [x] **Step 4: Run Green and lint**

Expectedtestexit0 coveringallnewcases;PHP-lintmigration/model/request/User;noactualDBmigrationcalled

- [x] **Step 5: Record scoped changes/verification, simplify and review gate**

Commitonlyifauthorized/safe;otherwiserecordno-commitinimplementationnote. Do not stageuserchanges

## Task 2: Atomic registration with profile and unchanged verification

**Files:** Modify `app/Http/Controllers/Auth/RegisteredUserController.php`;extend`tests/regressions/signup-business-profile.php`

**Interfaces:**
- ConsumesTask1flatvalidation/profilemodel/relations and `RegisterRequest::authData():array`, `RegisterRequest::businessProfileData():array`; use these explicit allowlists instead of passing allvalidatedfields toUser
- Producesexisting`RegisteredUserController::store(RegisterRequest,SiteSettings):RedirectResponse` withatomicuser/profilecreateandunchangedhome redirect
- Authpayloadexplicitkeysname/email/password only;serverassignsmemberrole/statusInvited asbefore. Businesspayloadexplicit4profilekeys;profileuser_idfromrelationship,notrequest. MissingprofilefieldsproduceemptyoptionalprofilewithcurrencyTHB

- [x] **Step 1: Add failing tests through store and HTTP kernel**

Createunique in-memoryfixtureemail;fake notifications/events/HTTP. Assertuser+profilecreatedwithcorrectowner,Hash::checktrue,userstatusInvited/email_verified_atnull,Registeredemittedonceaftercommit,loggedinonlyaftercommit,302home. Oldpayloadcreatesuserwithoutrequiredcompany/budget. Forgeduser_id/access_role_id/status/preferencesdo notoverrideownership/role. Duplicateemaildoesnotcreateanotherprofile. Modelserializeincludesnobudget;publicLabbootstrapunchanged

Injectprofilecreatingexceptioninfixtureonlyandassertnocreateduser/profile/entitlement,Registerednotemitted,noauthenticateduser. Fixturelistenerremovedaftertest. Testregdisabled404andthrottle/auth/CSRFthroughactualwebmiddleware;noverificationnotificationtransport

- [x] **Step 2: Run Red**

Expectednonzero becauseexistingcontrollerdoesnotpersistprofile/transaction;restoretestfakeauthbetweenrequests

- [x] **Step 3: Implement direct transaction**

Use `DB::transaction()` aroundUsercreate+relationshipprofilecreate;eventandAuth::login/sessionregenerateaftertransactionreturns. Do notcatchandpretendsuccesswhenprofilesavefails. Noexternalprovider/billingcalls;donotchangeVerifyEmailController/LoginRedirect/roles/notificationcontents. Ifpostcommitemailfails,do notdeletecommittedaccount ordiscloseformpayloadinerrorhandling

- [x] **Step 4: Run Green + preservation**

Expectedsignupfixtureexit0withtransactionfailure/redaction/verificationchecks;landing53,hosting37,existingNode15remainpassing. Countsarecurrentbaseline,notguaranteedfutureexactcounts

- [x] **Step 5: Record/review scoped changes**

Independentreviewbeforeclaimingcomplete;recordlimitationifreviewunavailable,no“passedreview”fromauthoralone

## Task 3: Modern signup Company details and local acceptance

**Files:** Modify `resources/views/auth/register/register.blade.php`;extendsignupfixture;create`Support/GUIDED-BUILDER-SIGNUP.md`

**Interfaces:** ConsumesTask1flatfieldnamesandTask2POSTcontract;producesaccessibleoptionalcompany/budgetfieldsinexistingform,notanewsignupwizard

- [x] **Step 1: Add failing view/HTTP assertions**

GETregistercontainscompany_name/business_description/website_budget_amount/website_budget_currencywithlabels,optionalmarker,andTHB/USDoptions;helptext“งบทำเว็บไซต์ ไม่ใช่ค่าแพ็กเกจหรือเครดิต AI”. Noaction/routechange. Validationredisplayretainsescapedcompany/description/budget/currency;no `<script>fixture</script>`execution;password/confirmationnotflashed. Oldinput/selectcurrencyworkswithemptyamount

- [x] **Step 2: Run Red**

Expectednewfieldassertionfailsagainstcurrentregisterview;existingauthfieldsstillpresent

- [x] **Step 3: Implement optional section**

Keepcurrentbrand/theme/authlayoutandemail/passwordfieldnames/order. Addcompanydetailsafteraccountfields,clearprivacycopyandoptionalfields;useformcomponentrequired=false,textarea/selectwitherrors/oldinputescaped. Budgetinputtype=number,min=0,step=.01,max=999999999999.99;servervalidationremainsauthoritative. No budgetfrontendcalculation/no“estimatedAIcost”

- [x] **Step 4: Verify browser/build/regressions**

Fixture-backedGET/browser375/768/1440,keyboard/taborder/labels/errorcopy/noscrolloverflow/disabled-submitnotrequired. Donotsubmitrealregistration,emailorchangeexistingpassword. RunBladecachethenPHPsuite sequentially; `npm run build`exit0;Nodepreservationexit0;scopeddiffcheck/lint

Ifactuallocalmigrationdesired,firstverifytargetislocalandshowadditiveimpact;donotrunallpendingmigrationsorresetdata. Applyonlynamedmigrationafterexplicitenvironmentapproval. UIcanbepreviewedagainstisolatedfixturewithoutliveDBwrites

- [x] **Step 5: Simplify, independent review, docs and handoff**

Docsstateonlysignup/profilecomplete;builderredirect/AI/canvasremainnextparts. Includechangedfiles,actualcounts/buildexit,fixturevsrealverification,exactmigrationpathandnoautomaticrollout. DonotmarkfullGuidedBuilderorGateScomplete

## Commands / Evidence

PHP: `C:/MAMP/bin/php/php8.5.10/php.exe` with extension_dir`C:/MAMP/bin/php/php8.5.10/ext`,openssl,pdo_sqlite,mbstring,curl,fileinfo,zip,intl. Tests force SQLite before bootstrap; intl is needed by the real default-entitlement lifecycle.

Runnew`tests/regressions/signup-business-profile.php`;then`tests/regressions/landing-copy.php`,`tests/regressions/hosting-safety.php`;`node --test tests/regressions/*.test.mjs`;`npm run build`;`artisan view:cache`onlywithisolatedDBandsequentiallybeforeHTTPtests. Readexitcodesandactualbody,notstatusalone

## Plan Self-review

- Specscopeexplicit:Part1signup/storageonly;Part2/3deliveryindexcoversrest,noclaimallrequirementsalreadyimplemented
- Interfacesconsistent:4flatprofilekeys;UserrelationbusinessProfile;FKsetbyserver;decimalneverfloat
- All5reviewfocusconditionsmappedtonewtests;notificationsfake;noactualaccount/providercall
- Noverificationredirecttonewmissingroute;buildercontinuationdeferredexplicitly,notforgotten
- Noextraframework/dependency/remotePostmansetup;noVoxelSitecodecopied

## Execution handoff

Owner approved this plan and selected subagent implementation with independent review after each task. The subagent-driven-development skill is unavailable; use bounded implementer/fresh-reviewer dispatches under subagent-supervision, with coordinator acceptance before dependent work. No automatic staging or commits.

## Execution status (2026-10-16)

- Task 1 accepted after independent review: storage/validation/contract, Red → Green 63 checks, syntax/whitespace checks.
- Task 2 accepted after independent review: atomic signup and preserved auth/verification, Red → Green 99 checks; landing53/hosting37/Node15 preserved.
- Task 3 accepted after independent review: Red → Green 149 signup checks, final landing53/hosting37/Node15 pass. Asset build and Blade cache exit0. Browser375/768/1440 and keyboard checks complete; fixture-only signup, no real account/email/provider call.
- Owner explicitly approved and coordinator applied only the named profile migration to verified local loopback MySQL. Expected nullable decimal(14,2) and FK verified from schema; no existing-member changes or real signup performed.
- Local runner now enables intl, required by existing entitlement setup; narrow fix independently reviewed. Tool restart was blocked before execution; owner restarted it. New loopback8892 process with intl and actual registerGET200 verified. See Support/GUIDED-BUILDER-SIGNUP.md for acceptance evidence, warnings and limitations.
