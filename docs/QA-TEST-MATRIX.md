# QA test matrix — Krikkit AI Website Builder

เอกสารนี้เป็นคู่มือทดสอบแบบ end-to-end สำหรับ local release และใช้เป็น checklist ก่อนเปิดให้ลูกค้าใช้งานจริง ใช้ข้อมูลสมมติเท่านั้น ห้ามใช้รหัสผ่าน, API key, Supabase service key, สลิปเงินจริง หรือข้อมูลลูกค้าจริงใน fixture

ผลการรันล่าสุดอยู่ที่ [QA report 2026-11-08](QA-REPORT-2026-11-08.md)

## 1. เป้าหมายและกติกาการตัดสินผล

การทดสอบหนึ่งกรณีต้องบันทึก `case id`, วันที่/เวลา, commit หรือ working tree ที่ใช้, URL/fixture, ผู้ใช้จำลอง, ขั้นตอนที่ทำ, expected result, actual result และหลักฐาน เช่น log, response หรือ screenshot

แบ่งผลเป็น 4 แบบ:

- **PASS** — ผลจริงตรงกับ expected result และมีหลักฐานตรวจซ้ำได้
- **FAIL** — โค้ดทำงานผิด, ข้อมูลรั่ว, state ผิด หรือเกิด side effect ที่ไม่ควรเกิด
- **BLOCKED** — ทดสอบไม่ได้เพราะ service/credential/fixture ไม่พร้อม ต้องไม่สรุปเป็น PASS
- **NOT RUN** — ยังไม่ได้เริ่มทดสอบ

ห้ามใช้ HTTP 200, หน้าไม่ error หรือ screenshot เพียงอย่างเดียวเป็นหลักฐานว่าฟังก์ชันสำเร็จ ต้องตรวจ response body, database/fixture state, ownership และการ reload ด้วย

## 2. สภาพแวดล้อมที่ใช้ทดสอบ

### Instance ปกติ

- แอป local: `http://localhost:8892` หรือ instance ที่ทีมประกาศในรอบนั้น
- หน้า landing: `/features`
- Builder: `/lab/builder`
- Workspace: `/lab/{project}/workspace`
- ใช้บัญชีสมมติที่ยืนยันอีเมลแล้วสำหรับ owner และบัญชีที่สองสำหรับ isolation
- ไม่ reset, drop หรือ seed ฐานข้อมูลของผู้ใช้จริง

### Preview ที่ปลอดภัย

ใช้ `scripts/preview-landing.ps1` หรือ `tests/regressions/local-preview.ps1` เมื่อต้องตรวจเฉพาะ presentation แบบ read-only เท่านั้น Preview นี้ไม่ใช้แทนการทดสอบ login, AI, database, payment หรือ published customer site

### PHP/Node checks

```powershell
$env:DB_CONNECTION='sqlite'
$env:DB_DATABASE=':memory:'
$env:CACHE_STORE='array'
$env:SESSION_DRIVER='array'
$env:LOG_CHANNEL='null'

$php='C:/MAMP/bin/php/php8.5.10/php.exe'
& $php tests/regressions/signup-builder-continuation.php
& $php tests/regressions/builder-blueprint.php
& $php tests/regressions/builder-drafts.php
& $php tests/regressions/builder-source.php
& $php tests/regressions/builder-materialization.php
& $php tests/regressions/builder-ai-planner.php
& $php tests/regressions/entity-definition.php
& $php tests/regressions/account-entitlement-isolation.php
node --test tests/regressions/*.test.mjs
npm.cmd run build
```

หากทดสอบ suite ที่ต้องการ HTTP fixture ให้เปิด fixture ที่ระบุในหัวข้อ 8 ก่อน แล้วปิดเฉพาะ process ที่ตัวเองเปิดเมื่อจบ

## 3. Test personas และข้อมูลสมมติ

| Persona | สิทธิ์ | ใช้ตรวจ |
|---|---|---|
| Visitor | ยังไม่ login | landing, template preview, public customer site |
| New owner | สมัครใหม่และยังไม่ verify | validation, email verification, redirect |
| Verified owner | เจ้าของ project | draft, AI proposal, modules, forms, publish |
| Second owner | เจ้าของอีกบัญชี | project/data/form/submission isolation |
| Customer | ผู้เข้าชม customer site | form submit, cart, quote, slip upload |
| Malicious client | ส่ง HTTP เอง/เปลี่ยน UUID | authorization, CSRF, CORS, ownership, rate limit |

ใช้ข้อมูลตัวอย่าง เช่น `QA Studio`, บริษัท `Fictional Repair Co.`, งบประมาณ `50000`, prompt ภาษาไทย, project UUID ใหม่ และอีเมลโดเมน `.invalid` เท่านั้น

## 4. P0 smoke และความปลอดภัยของสภาพแวดล้อม

| ID | เงื่อนไข | Expected result |
|---|---|---|
| ENV-001 | เปิด landing, login, register, pricing, contact | ทุกหน้าตอบและ HTML เป็นหน้าที่ถูกต้อง ไม่มี PHP error ถูกซ่อนด้วย 200 |
| ENV-002 | ตรวจ response ของ asset และ deep route | asset ที่มีอยู่ตอบถูกชนิด; route ที่ไม่มีตอบ 404/redirect ตาม contract |
| ENV-003 | ส่ง POST/PUT ไปยัง preview read-only | ถูกปฏิเสธ 405 และไม่มีข้อมูลเปลี่ยน |
| ENV-004 | ตรวจ `.env`, private key, service key, `node_modules`, `.agent-work` ใน generated artifact | ไม่ถูกส่งออกหรือฝังใน source/customer bundle |
| ENV-005 | เรียก customer origin พร้อม platform session cookie | customer site ไม่เห็น session ภายในของ platform |
| ENV-006 | เปิด project UUID ของบัญชีอื่นด้วย direct HTTP | ได้ 401/403/404 ตาม contract และไม่มี metadata หลุด |
| ENV-007 | reload หลัง error และเปิดหลายแท็บ | error เก่าไม่ถูกนำไปใช้กับ project/tab ใหม่ |
| ENV-008 | ตรวจ log หลัง test | ไม่มี token, password, service key, slip bytes หรือข้อมูลลูกค้าจริง |

## 5. สมัครสมาชิกและต่อเข้า Builder

| ID | เงื่อนไข | Expected result |
|---|---|---|
| AUTH-001 | สมัครด้วยข้อมูลครบ: ชื่อ, email, password, company, business type, budget | สร้าง account และ business profile แบบ atomic |
| AUTH-002 | เว้น field required ทีละช่อง | แสดง validation ที่ field นั้น; ไม่สร้าง user/profile ครึ่งรายการ |
| AUTH-003 | email ซ้ำกับสมาชิกเดิม | ปฏิเสธโดยไม่เปลี่ยน profile เดิม |
| AUTH-004 | password ไม่ผ่าน policy/confirmation ไม่ตรง | ปฏิเสธและไม่ส่งต่อเข้า Builder |
| AUTH-005 | งบประมาณเป็นข้อความ, ติดลบ, เกินขอบเขต, ทศนิยมผิด | ปฏิเสธด้วยข้อความที่อ่านได้ |
| AUTH-006 | email ยังไม่ verify แล้วเข้า `/lab/builder` | ถูกส่งไป verify และกลับมายัง intended URL ได้ |
| AUTH-007 | verify สำเร็จแล้ว refresh | profile/company data ยังคงอยู่และไม่สร้างซ้ำ |
| AUTH-008 | login บัญชีเดิม | กลับเข้า project/Builder ที่ถูกต้องของบัญชีเดียวกัน |
| AUTH-009 | logout แล้วใช้ back/old tab | private page ไม่แสดงข้อมูลเก่าและ API ปฏิเสธ |
| AUTH-010 | เปิด signup สองแท็บ submit ซ้ำ | ได้ผลลัพธ์เดียวหรือ error ที่ชัดเจน ไม่สร้าง account ซ้ำ |

## 6. Builder, AI proposal และ draft

| ID | เงื่อนไข | Expected result |
|---|---|---|
| BLD-001 | เลือก Business site, Ecommerce, Portfolio, Blog, Other | catalog ที่ถูกต้องแสดงและเลือกต่อได้ |
| BLD-002 | เลือก AI site builder | เปิด prompt flow และไม่เรียก AI จากการกด preview ตัวอย่าง |
| BLD-003 | ส่ง brief ภาษาไทย/อังกฤษ/emoji | proposal อ่านได้, schema ผ่าน, ไม่มี HTML/script execution |
| BLD-004 | brief ว่าง/ยาวเกิน/มี control characters | ปฏิเสธหรือแสดง validation โดยไม่สร้าง draft |
| BLD-005 | AI provider unavailable/timeout/invalid JSON/503 | fallback หรือ error ตาม contract; quota และ draft ไม่เสียหาย |
| BLD-006 | proposal มี page/section/widget ที่ catalog ไม่รองรับ | server validate และตัดสินใจ fail closed; ห้ามรับ arbitrary component |
| BLD-007 | เปลี่ยนชื่อเว็บ, theme, สี, typography | preview เปลี่ยนเฉพาะ draft ใน client จนกด Save |
| BLD-008 | เพิ่ม/ลบ/เรียงหน้าและแก้ route | IDs เดิมของส่วนที่ไม่แก้ยังคงเดิม; route ซ้ำถูกปฏิเสธหรือ normalize ตาม contract |
| BLD-009 | fields ภาษาไทย เช่น โทรศัพท์, เวลา, ทะเบียนรถ | data-model preview แสดง type/relationship ถูกต้อง |
| BLD-010 | Save draft ครั้งแรก | revision เพิ่ม, owner scope ถูกต้อง, response มี canonical snapshot |
| BLD-011 | refresh/reopen draft | content, theme, pages, field IDs และ revision กลับมาครบ |
| BLD-012 | Save สองแท็บจาก revision เดียวกัน | แท็บที่ stale ได้ conflict; local edits ของแท็บนั้นไม่หาย |
| BLD-013 | กด Save ซ้ำ/timeout แล้ว retry | ไม่สร้าง duplicate revision/project และผลลัพธ์ idempotent |
| BLD-014 | บัญชีสองเปิด UUID draft ของบัญชีแรก | ปฏิเสธทุก endpoint รวม direct API ไม่ใช่แค่ซ่อนปุ่ม |
| BLD-015 | AI edit ระบุ page/section หนึ่งจุด | ส่วนอื่นต้อง byte/semantic เท่าเดิม; ไม่มี module/schema/business write อัตโนมัติ |
| BLD-016 | AI edit ระบุ target ที่ลบหรือไม่ตรง project | ปฏิเสธก่อน outbound provider call |

## 7. Source, build, preview และ publish

| ID | เงื่อนไข | Expected result |
|---|---|---|
| PUB-001 | สร้าง project จาก saved draft | สร้าง source ด้วย compiler เดียวกับ preview และผูก owner/draft/revision ถูกต้อง |
| PUB-002 | retry materialization เดิม | คืน project เดิมหรือผล idempotent; ไม่ทับ manual edits |
| PUB-003 | generated source มีหน้า `/`, deep route และ route ภาษาไทย | build ผ่านและ reload deep route ตอบ 200 |
| PUB-004 | inspect generated source | ไม่มี platform credential, server secret, SQL หรือ arbitrary backend code |
| PUB-005 | build ให้ล้ม/asset หาย | project ไม่ถูก mark live; last-good artifact ยังใช้งานได้ |
| PUB-006 | preview customer site | navigation, refresh, anchor, back/forward และ mobile layout ทำงาน |
| PUB-007 | publish ครั้งแรก | ต้องผ่าน owner authorization, artifact verification และ assigned publication slug |
| PUB-008 | publish ของบัญชีสองโดยรู้ project UUID | ถูกปฏิเสธและไม่มี artifact เปลี่ยน |
| PUB-009 | unpublish/revoke | customer เห็น denial/404 ตาม policy; owner history ยังคงอยู่ |
| PUB-010 | เปิด published origin พร้อม platform route | cookie/origin isolation ไม่ให้ guest bridge รับ command จาก origin อื่น |

## 8. Managed forms, booking และ submissions

| ID | เงื่อนไข | Expected result |
|---|---|---|
| FORM-001 | สร้าง booking template | ได้ immutable definition version แรกและ stable field IDs |
| FORM-002 | rename/reorder/add field | เป็น draft version ใหม่; active version ยังไม่เปลี่ยนจน Apply/Activate |
| FORM-003 | archive field ที่มี historical answers | ห้ามลบจริง; snapshot เก่ายังอ่านได้ |
| FORM-004 | เปลี่ยน type/core field | ปฏิเสธด้วย conflict/validation |
| FORM-005 | public GET definition | เปิดเฉพาะ active definition และไม่เผย credential/owner data |
| FORM-006 | public POST ครบและเวลา Bangkok อยู่อนาคต | สร้าง submission สถานะรอตรวจและคืน reference ที่สุ่มปลอดภัย |
| FORM-007 | public POST ขาด required/ชนิดผิด/เวลาย้อนหลัง | 422 และไม่สร้าง record |
| FORM-008 | retry key เดิม + payload เดิม | คืน receipt เดิม ไม่สร้างซ้ำ |
| FORM-009 | retry key เดิม + payload ต่าง | conflict และไม่เปลี่ยนรายการเดิม |
| FORM-010 | ปิดรับ/เปลี่ยน active version | request ใหม่ใช้ version เก่าถูกปฏิเสธ; receipt เก่ายัง replay ได้ตาม contract |
| FORM-011 | owner confirm/cancel พร้อม expected status | transition ถูกต้องและกดซ้ำไม่ทำให้ state ย้อน |
| FORM-012 | anonymous เรียก owner listing/detail | ไม่มี public listing/detail และถูกปฏิเสธ |
| FORM-013 | origin/CORS/OPTIONS | exact verified origin ผ่าน; origin อื่นและ wildcard ถูกปฏิเสธ |
| FORM-014 | owner สองบัญชีเปิด form/submission เดียวกัน | ไม่มีข้อมูลข้ามบัญชีแม้รู้ UUID |

## 9. Cart, quote, payment proof และ owner desk

| ID | เงื่อนไข | Expected result |
|---|---|---|
| SHOP-001 | เพิ่มสินค้า, ลด/เพิ่มจำนวน, reload cart | quantity และยอดคำนวณจาก catalog ล่าสุดอย่างถูกต้อง |
| SHOP-002 | ไป checkout จาก cart ว่าง | ไม่สร้าง order และแสดงคำแนะนำที่ชัดเจน |
| SHOP-003 | catalog เปลี่ยนหลัง quote | checkout แสดง conflict; ต้องยืนยันราคาใหม่ก่อนสร้าง order |
| SHOP-004 | สร้าง order สำเร็จ | ได้ order/reference เดียวจาก retry เดิมและสถานะรอชำระ |
| SHOP-005 | แสดง PromptPay QR | QR มาจากยอด/reference ที่ server ยืนยัน ไม่รับยอดจาก client ตรงๆ |
| SHOP-006 | อัปโหลดสลิปถูกต้อง | เก็บ private object, uploader name/email/phone และ uploaded time; ไม่เปิด public URL |
| SHOP-007 | ไฟล์ผิดชนิด/ใหญ่เกิน/ภาพเสีย/metadata แปลก | ปฏิเสธและไม่สร้าง attempt ที่ใช้งานได้ |
| SHOP-008 | ส่งสลิปซ้ำหรือ retry timeout | ไม่สร้าง duplicate attempt; สถานะและประวัติยังตรวจสอบได้ |
| SHOP-009 | owner reject พร้อมเหตุผล | ลูกค้าเห็นสถานะ reject และส่งใหม่ได้ตาม policy; หลักฐานเก่ายังอยู่ |
| SHOP-010 | owner approve พร้อม expected status/version | เปลี่ยนเป็น paid ได้ครั้งเดียว; approve ซ้ำไม่จ่ายซ้ำ |
| SHOP-011 | customer เปิด order UUID โดยไม่มี session/capability | ไม่เห็น QR, slip form หรือข้อมูลรายการ |
| SHOP-012 | owner revoke/cancel หลังลูกค้าเปิดหน้าไว้ | UI ล้างข้อมูล stale ผ่าน 401/403/404; owner history ไม่หาย |
| SHOP-013 | เปิด owner desk ด้วยบัญชีอื่น | ไม่เห็น order/payment/slip ของเจ้าของเดิม |

## 10. GitHub import และ source safety

| ID | เงื่อนไข | Expected result |
|---|---|---|
| GH-001 | inspect branch แล้ว commit เปลี่ยนระหว่างทาง | import pin commit เดียวตลอด operation |
| GH-002 | tree/blob หาย, hash ผิด, response truncated | fail closed และ workspace เดิมไม่เปลี่ยน |
| GH-003 | path traversal, absolute path, backslash, drive, UNC, symlink, submodule | ปฏิเสธ |
| GH-004 | archive ใหญ่/ไฟล์มาก/depth/compression ratio เกิน | ปฏิเสธก่อนเขียนหรือขยายเกิน quota |
| GH-005 | `.env`, private key, token, credential-bearing file | scan/approval ปฏิเสธหรือ quarantine; ไม่ส่งเข้า AI/workspace แบบลับ |
| GH-006 | lockfile, binary, LICENSE/NOTICE | เก็บหรือรายงานตาม policy; ห้าม silent drop ไฟล์สำคัญ |
| GH-007 | cancel/expired/replaced/frozen worker | ไม่ promote source และล้าง temporary state |
| GH-008 | imported source มี script/backend | ไม่ execute บน host; build/runtime ต้องผ่าน allowlist และ inspection gate |

## 11. Accessibility, responsive และ reliability

- ตรวจ viewport 375, 768, 1024 และ 1440px: ไม่มี document horizontal overflow, focus ไม่หาย, button มี accessible name
- ใช้ keyboard ล้วน: Tab, Shift+Tab, Enter, Space, Escape และ focus กลับ modal trigger
- ตรวจ reduced motion, loading, empty, 401, 403, 404, 409, 422, 429, 500 และ 503
- refresh ระหว่าง save/upload/AI request แล้วไม่สร้าง duplicate หรือทำ draft/order เสีย
- เปิดสองแท็บและกด back/forward ระหว่าง cart, checkout, preview, workspace และ owner desk
- ทดสอบ network offline/slow/timeout แล้ว retry ได้โดยไม่ส่ง credential ซ้ำเกินจำเป็น
- ตรวจ log ไม่แสดง secret และตรวจ response ไม่สะท้อน raw SQL, file path หรือ internal exception

## 12. Fixture commands และการแยกผล BLOCKED

### AI edit fixture

```powershell
postman simulation run postman/simulations/builder-ai.sim.yaml --port 4905 --no-history --output ndjson
```

รอ event `listening` ก่อนรัน `tests/regressions/builder-ai-edit.php`; ถ้า port 4905 ไม่พร้อมให้บันทึก `BLOCKED-FIXTURE` ไม่ใช่ FAIL ของ AI controller

### GitHub fixture

```powershell
postman simulation run postman/simulations/github-source.sim.yaml --port 4904 --no-history --output ndjson
```

จากนั้นรัน `github-source-completeness.php`, `github-import-fence.php` และ `github-import-publish.php` ด้วยข้อมูล fictional เท่านั้น

### Managed module fixture

```powershell
node tests/fixtures/runtime/supabase-postgres-server.mjs
postman simulation run postman/simulations/managed-modules.sim.yaml --port 4900 --no-history
```

จากนั้นรัน `contact-flow.php`, `news-flow.php`, `commerce-flow.php`, `commerce-quotes-flow.php`, `owner-commerce-pages.php`, `private-slip-flow.php` และ `scoped-schema-proposals.php` แบบ serial

หากพบ `Module schema storage is temporarily unavailable` ขณะ port 4900/PGlite ยังไม่พร้อม ให้จัดเป็น `BLOCKED` และเก็บ log startup; อย่าแก้ production config เพื่อทำให้ test ผ่าน

## 13. เกณฑ์ release

ห้ามประกาศว่า production-ready จนกว่า P0/P1 ผ่านทั้งหมด, ไม่มี P0/P1 FAIL, ทุก BLOCKED ที่เป็น dependency ถูกเปิด fixture และรันซ้ำ, generated customer build ผ่าน deep-route smoke, owner/customer isolation ผ่าน และมีหลักฐาน browser สำหรับ desktop/mobile/keyboard

ผลตรวจ local ล่าสุดที่ยืนยันแล้ว: standalone PHP suites เดิมผ่าน **30/30 suites** ใน `.agent-work/qa/2026-11-08-standalone-qa.log`; เพิ่ม account entitlement/credit isolation อีก **8 checks ผ่าน**; Node `44/44`, blueprint `708`, drafts `462`, source `21`, materialization `45`, AI planner `10`, AI edit `54` ผ่านตาม fixture ของแต่ละชุด; `npm.cmd run build` ผ่าน แต่ live Supabase, production payment, customer CRUD, browser matrix ครบทุกกรณี และ independent final review ยังเป็น gate แยก
