# Landing Page and Product Positioning Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** หน้าแรกสวย อ่านรู้เรื่องภายใน10วินาที และอธิบายว่าควรซื้อเพราะอะไรโดยไม่ขายฟีเจอร์ที่ยังทำไม่ได้

**Architecture:** คงBlade/Alpine + LandingCopy/SiteSettings/Livewire CMS; เพิ่มreadiness modelสำหรับcapabilitiesและตัวอย่างจริง ไม่เปลี่ยนroutingของStudio

**Tech Stack:** Laravel13, Blade/Livewire4, Tailwind4, existing design tokens.

**Spec:** ../specs/2026-10-16-platform-evolution-design.md §10

**Status:** Draft. Dependency: readiness contract S2สำหรับlive badges; layout/copyออกแบบได้ก่อน. Gate L.

## Global Constraints

- Import/build เป็นสองสถานะ; เก็บ provenance/commit/license และห้ามแสดง Ready ก่อน artifact + smoke check ผ่าน
- รักษางานผู้ใช้ใน checkout/index; ห้าม bulk stage, reset, initial commit ปนงานเดิม หรือประกาศ deploy production จาก local pass
- Remaining Spec§4 applies. Guest `/` remains landing; authenticated `/` remains Studio; `/features` public product view. No fabricated prices/logos/reviews/statistics.

## Review Focus

1. Saved CMS copy overrides defaults — L1 non-destructive migration preview test.
2. Logged-in visitor loses Studio entry — L1 route regression.
3. Payment module not yet ready but hero promises payment — L2 readiness test.
4. Thai long labels/mobile/reduced motion — L3 visual+keyboard tests.
5. Failed demo appears like real success — L2 static demo label and no real POST test.

## Task L1: Product copy contract and CMS preservation

**Files:** Modify app/Support/Site/LandingCopy.php, app/Livewire/Dashboard/Landing/LandingComponent.php, resources/views/livewire/dashboard/landing/*, routes/web.php. Create app/Support/Site/ProductCapabilities.php, tests/regressions/landing-copy.php, config/product-capabilities.php.

**Interfaces:** ProductCapabilities::publicFeatures():array of `{key,title,status,limitations,demo_url}`; status available/limited/planned. Public mapping never exposes account/module records. LandingCopy extends sections with benefits/usecases/readiness without overwriting site->bag('landing').

- [ ] Red: saved custom hero remains unchanged after defaults upgrade; missing newsection receives fallback; old keys roundtripCMS; activeCMS values escaped; unsafehref rejected. Guest200landing/authStudio/maintenance503 and `/features` tests.
- [ ] Implement new defaults and opt-in CMS comparison/apply action with expected settings revision. Existing operatorcopy must not disappear; do not auto-run overwrite command.
- [ ] Add `/features` reuse landing template with explicit SEO canonical for that route; `/` behavior unchanged. Availability driven by release config updated only after gates, not frontend hardcoded badges.
- [ ] Green: landing-copy.php, Blade viewcache, CMS save/reload regression; inspect actual admin editor reflects newsections. Record any route/SEO change.

## Task L2: Landing sections and honest sales narrative

**Files:** Modify resources/views/home/home.blade.php, partials/header/hero/platform/agents/walkthrough/integrations/faq/cta/footer.blade.php; create partials/benefits.blade.php, examples.blade.php, feature-status.blade.php. Modify resources/css/app.css only scoped landing tokens; add optimized real screenshots to public/images/product/ (fictional account data only).

**Interfaces:** All visible copy comes from LandingCopy sections; ProductCapabilities provides feature state. Demo fixtures have `demo=true`, no credential, no live customer POST. Existingregister/login/pricing route helpers stay intact.

- [ ] Red: landmark/CTA/feature status assertions; planned manual-payment feature cannot produce an unlabeled working-demo link; primaryCTA goesregister/lab asappropriate, not fake checkout.
- [ ] Implement sections in order with responsive design:
  1. Header: logo, ทำอะไรได้, ตัวอย่าง, ราคา, FAQ, เข้าสู่ระบบ/เริ่มสร้าง.
  2. Hero headline proposed `จากไอเดียภาษาไทย สู่เว็บไซต์ที่พร้อมต่อยอดธุรกิจ`; subcopy `สร้างหน้าเว็บหลายหน้า ปรับหน้าตาและฟอร์มในที่เดียว พร้อมเชื่อมข้อมูลและเปิดใช้โมดูลตามความต้องการ` with readiness qualifier where unavailable.
  3. Actual workspace screenshot beside a brief→preview→record flow; no fake customerstats or borrowed brandlogos.
  4. Benefits: เริ่มจากตัวอย่างแทนหน้าว่าง; มีโค้ดแก้ต่อ; ปรับทีละหน้า/ช่องข้อมูล; จัดการในโปรเจกต์เดียว. Avoid guaranteed delivery-time/costsaving claims.
  5. Usecases: businesssite, cafébooking, productcatalog, internaldataform; mark `ใช้ได้แล้ว/มีข้อจำกัด/กำลังพัฒนา` accurately.
  6. Walkthrough4steps: อธิบาย→เลือกหน้า/โมดูล→ตรวจหน้าตาและข้อมูล→ทดสอบแล้วเผยแพร่. Show human approvalbeforechanges.
  7. Data/security: own linkedSupabase, owneradmin, privatefiles whenavailable; don'tclaim auditcertifications or absolute security.
  8. Pricing links existingactivepacks/limits, not new inventedtiers; distinguish platformsubscription from customerstorepayments and external provider charges.
  9. FAQ: GitHub supported stacks, dataownership/export, paymentreview, hostedversusselfhosted, limits; finalCTA.
- [ ] Green: browserclick everyCTA/mobilemenu/FAQ/demo and realroute; verify no live mutationfrom demo. Featurecopy gate test prevents commerceavailable untilCpasses.
- [ ] Capture desktop/mobile screenshots, document copychanges + retainedcustomCMS choices; do not publish productclaims ahead ofbackend.

## Task L3: Visual/accessibility/performance acceptance

**Files:** Tests/regressions/landing-copy.php; add tests/browser/landing.spec.js only ifbrowserrunner isavailable, otherwise executablemanualchecklist in Support/LANDING-QA.md. Do not install duplicate browser stack merely for the plan.

- [ ] Baseline screenshot actualguestlanding beforeedit. Check375/768/1440px, Thai longcopy, keyboardfocus/FAQ/menu/Escape, reducedmotion, readablecontrast and nohorizontaloverflow.
- [ ] Use warmwhite/neutral withcurrentaccent; Thai text body16px+, lineheight1.6+, maxcontentwidth1280, viewport-appropriate hero; avoidautoplay backgroundvideo and generic wallofcards. Screenshotassets WebP/AVIF withwidth/height; lazyload belowfold.
- [ ] Verify actualHTTPpage, headings/metadata/OG, canonical andworkingpricing withoutstaleplanhardcodes. TargetLighthouse mobile accessibility≥95/LCP≤2.5s in recordedrepresentativeenvironment; report actualresults, not assumedgrades.
- [ ] Build host + Bladecache, screenshots and independentUIreview; record measuredperformance/deviations. GateL completeonlyafterownerreviewofrealpage.

## Execution Handoff

Readmasterhandoff; designartifactonly. Do not overwrite CMS, authhomebehavior orclaimfuturecommercefeaturesjusttocomplete landing. Allpost-implementationclaimsrequirefreshcommands/browserproof.
