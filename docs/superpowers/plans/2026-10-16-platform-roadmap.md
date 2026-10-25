# Krikkit Platform Evolution Roadmap

วันที่อ้างอิง:2026-10-16 Asia/Bangkok
สถานะ: In progress — เจ้าของสั่งให้อ่านโปรเจกต์และทำงานต่อ เริ่ม S1
Current2026-10-25: C1–C5/local source/build/entry มี implementation และชุดทดสอบจริงแล้ว; browser/live/independent gate ยังไม่ปิด ดู [สถานะปัจจุบัน](../../PROJECT-STATUS.md) และ [backlog ครบทุกส่วน](../../BACKLOG.md) ก่อนใช้ checkpoint เก่าด้านล่าง

## อ่านเอกสารตามลำดับ

1. [Architecture / Product Design](../specs/2026-10-16-platform-evolution-design.md) — ของเดิม/เป้าหมาย/dataflow/data model/สิทธิ์/ข้อจำกัด
2. [Agent Handoff](../../platform/2026-10-16/AGENT-HANDOFF.md) — วิธีเริ่มงานและกฎส่งมอบ
3. [Safety + Module Foundation](2026-10-16-platform-01-safety-modules.md) — S1–S4
4. [หน้าแรกและการขาย](2026-10-16-platform-02-landing.md) — L1–L3
5. [Cart / Orders / PromptPay / สลิป](2026-10-16-platform-03-commerce.md) — C1–C5
6. [Interactive Data Applications](2026-10-16-platform-04-interactive-data.md) — D1–D4
7. [GitHub Import + Reflex](2026-10-16-platform-05-github-reflex.md) — G1–G3/R1–R2
8. [Reflex source analysis](../../platform/2026-10-16/reflex-analysis.md) — clone/commit/license/function mapping

## Coverage ตามที่ผู้ใช้ขอ

| คำขอ | เอกสาร/งาน | ผลที่ต้องเห็นจริง |
| --- | --- | --- |
| 1. หน้าแรกสวย อธิบายโปรเจกต์และเหตุผลซื้อ | L1–L3 | guest landing + productdemo + copy/ราคาจริง ไม่ทำลายStudio/CMS |
| 2. โครงสร้างละเอียดเป็นMarkdownสำหรับagent | Architecture + ทุกsubplan + Handoff | sourcefacts/targetแยก, signatures/files/tests/gatesครบ |
| 3. สร้างเว็บ/ระบบinteractiveบันทึกDB | S3 + D1–D4 | typedforms/list/detail/CRUDที่มีสิทธิ์จริง, versionedfields |
| 4. โมดูลกำหนดขอบเขตลูกค้า | S2/S4 + manifestในArchitecture | servercapabilities∩plan∩project, quotas+audit |
| 4.1 นำเข้าGitHubเข้าเว็บตนเอง | G1–G3 | inspectSHA/license/runtime→Applydiff→build→publishของตนเอง |
| 4.2 ปรับโค้ด/เพิ่มDB/ฟอร์ม/สั่งซื้อ/จอง/ตะกร้าไม่กระทบคนอื่น | S1–S4/C/D/G3 | cross-account+same-accountcross-projecttests, protectedbusinessmodules |
| ศึกษาReflexและนำบางฟังก์ชันมาใช้ | R1 + pinnedanalysis; R2optional | state/events/formerror/lockingpatterns; ไม่อ้างรันPythonในReactแล้ว |

## ลำดับงานและ dependency

```text
S1 ปิดช่องรันโค้ดบนโฮสต์ ─ S2 registry/policy ─ S3 approved changes ─ S4 isolation gate
                               │                                  │
                               └── L1/L2/L3 หน้าแรก ──────────────┤
                                                                  ├─ C1..C5 commerce
                                                                  ├─ D1..D4 interactive data
                                                                  └─ G1/G2 safe import ─ G3 adaptation (uses C/D)
R1 Reflex patterns → tests in C/D; R2 Python runtime is a separate gated probe, not a blocker
```

จัดส่งเป็นsliceเล็กที่ใช้ได้จริง ไม่ทำทุกเฟสพร้อมกัน. หน้าแรกออกแบบคู่ขนานได้แต่ป้ายfeatureต้องรอreadiness. การเพิ่มmodule/สิทธิ์/hostingจริงเริ่มหลังGateSผ่านเท่านั้น

## Milestones และตรวจรับ

| Gate | งาน | Acceptance / สิ่งที่ห้ามอ้างแทน |
| --- | --- | --- |
| S | S1–S4 | ไม่มี untrusted servercodeในLaravel, auth/project/schema/cache/job isolationผ่าน; directoryUUIDอย่างเดียวไม่พอ |
| L | L1–L3 | desktop/mobileCTA/CMS/route/SEO/readinessจริง; ภาพmockไม่แทนlivefeature |
| C | C1–C5 | สินค้า→ตะกร้า→checkout→QR→syntheticslip→ownerreview→refreshจริง; hostbuildอย่างเดียวไม่พอ |
| D | D1–D4 | AIproposal→ownerApply→schema→form→recordCRUDpersist; publiclistไม่เปิดโดยไม่มีidentity |
| G | G1–G3 | pinnedimport/provenance/diff/build/unsupportednotice/otheruserunchanged; importedไม่เท่ากับrunnable |
| R | R1 / optionalR2 | patternsนำมาทดสอบ; Pythonhostingต้องsandbox/costspecแยก |

## Progress ledger — update after real evidence only

- [x] สำรวจlanding/GitHub/runtime/datastore/forms/billingเดิมแบบread-only
- [x] CloneReflexแยกและบันทึกcommit/license/source mapping
- [x] เขียนarchitecture+subplans+agenthandoffและself-reviewเอกสาร
- [x] เจ้าของสั่งให้เริ่มทำงานต่อจากแผน (เริ่มเฟส S1)
- [ ] S1 safety stopgap + actualoriginverification
- [ ] S2–S4 modulepolicy/scopedchanges/isolationgate
- [ ] L1–L3 landingready
- [ ] C1–C5 commerce/promptpay/slipreviewready
- [ ] D1–D4 interactivedataready
- [ ] G1–G3 safeGithubadaptationready
- [ ] R1 behaviorsverified; R2notapproved/disabled

## Decisions / unresolved gates

1. แนะนำเริ่มS1ก่อน แล้วS2/Landing: ลดความเสี่ยงก่อนเพิ่มขอบเขตลูกค้าและได้หน้าขายที่ตรวจรับง่าย
2. React+Laravelเป็นแกนต่อ; ไม่rewriteเป็นReflex Pythonทั้งก้อนหรือเอาAIBuilderSaaSมาอ้างว่าcloneได้
3. PromptPayแบบร้านตรวจเองตามคำตอบผู้ใช้; มัดจำbookingยังไม่อนุมัติ เลยไม่เก็บในreleaseแรก
4. ใช้Supabaseรายบัญชีเดิม, namespaceต่อproject; ใครนำremoteprojectร่วมกันต้องมีsharingcontractก่อน ไม่ใช้servicekeyเป็นหลักฐานสิทธิ์
5. Tenantserverexecution/sharedhostไม่พร้อม → source-onlyชัดเจน ไม่ยอมลดsecurityเพื่อให้demoผ่าน
6. สลิปretention180วันเป็นข้อเสนอ ต้องprivacy/retentionapprovalก่อนproductionและไม่มีpurgeอัตโนมัติในงานนี้
7. Quotasafetyเป็นtechnicalceilings ไม่ใช่แพ็กเกจขายจริง; prices/tiersดึงของเดิมจนownerอนุมัติการเปลี่ยน
8. ไม่กำหนดวันเสร็จจากจำนวนtoken; ประเมินeffortหลังS1baselineและactualhostingconstraints. Gateต้องผ่านตามหลักฐาน ไม่ใช่กำหนดการสมมติ

## Source-control / verification policy

Checkoutมีงานผู้ใช้จำนวนมากและยังไม่มีusableHEADในรอบก่อน ต้องตรวจสถานะปัจจุบันก่อนทุกslice ไม่มีbulkcommit/reset. Nativeagentหลักทำงานตามsubplan และ independentreviewก่อนgateเป็นวิธีเสนอ; ไม่สร้างchatหรือส่งงานให้chatอื่นโดยพลการ

ผลเก่าbookingv1/liveSupabaseอยู่ใน .agent-work/implementation/2026-10-15-booking-form-template.md และSupport/BOOKING-FORMS.md. ให้ใช้เป็นbaseline ไม่อ้างว่าครอบคลุมcommerceหรือfuturemultitenantisolation. ทุกreleaseต้องfreshregression+actualcustomerartifact+browserproof+review

## Result of this planning turn

ได้แผนและreferencecloneจริงเท่านั้น ไม่มีproductsource/migration/remotepermissions/paymentrecipientเปลี่ยน ไม่มีrealtransferหรือproductionrollout. Readyforownerreview, notreadyforimplementationclaim.

## Execution checkpoint: S1 PHP stopgap

2026-10-16: RuntimePolicy + LabPreviewController ปฏิเสธ PHP/server runtime ที่ยังไม่รองรับด้วย423 พร้อม source-only notice โดยไม่อ่าน/execute workspace และไม่ลบไฟล์เดิม. Benign sentinel Red→Green;24 local controller/render checks, Node11/11, existing PHP preservation suites และ hostbuildผ่าน. Scoped independent reviewerไม่พบข้อค้าง. ไม่มี stage/commit/migration/Hosting edits.

S1 checkboxยังไม่ปิด: same-origin React service-worker preview และ cookie-free origin/message bridge ยังต้องทำและทดสอบผ่านเบราว์เซอร์จริง. Browser attachmentและ auxiliary visual-fixture serverไม่สำเร็จในรอบนี้; global Blade viewcache ผ่านแล้วหลังใช้เวลานาน. GateS/โมดูลใหม่ยังไม่เปิด. รายละเอียด Support/RUNTIME-SAFETY.md และ .agent-work/implementation/2026-10-16-runtime-safety-s1.md.

## Local presentation / startup checkpoint

2026-10-16: เจ้าของขอพัฒนาเฉพาะlocalและเลือกแนวModern SaaS. เพิ่มหน้าขายภาษาไทย/CMS opt-in, `/features`, starter gallery และข้อจำกัดฟีเจอร์ที่ตรงสถานะจริง; buildผ่าน4814modules, landing53/Node15/15 และ responsive375/768/1440ผ่านตามการตรวจในรอบนี้. `scripts/serve-local.ps1` เปิดแอปจริงบนlocalhost8892ได้ โดยแก้เงื่อนไขตัวรันPHP(public cwd/process-only OPcache disable) ไม่แก้.env/vendor/CMS และไม่migrate. Browserบัญชีเดิมเข้าหน้าขาย→Lab→wizardได้; smoke8checksผ่าน. ไม่มีAIgenerationหรือทดสอบรับเงินจริง.

ยังไม่ปิดGateL/ทั้งroadmap: ownerต้องตรวจหน้าจริง, ยังไม่วัดperformance และfinalindependentrecheckติดusage limit. GateS origin+modules, commerce, genericdata และsafeGitHubadaptationยังเหลือ. ห้ามเปิดHosting/commerceหรืออ้างพร้อมขายครบจากการผ่านbuild/หน้าขายเพียงอย่างเดียว. วิธีเปิดและตรวจต่ออยู่ในSupport/LOCAL-RELEASE-CHECKLIST.md.
