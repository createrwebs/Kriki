# Booking Form Template Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** ให้เจ้าของบัญชีสร้างฟอร์มขอจองโต๊ะ เพิ่มช่องผ่าน UI/AI รับข้อมูลจริงใน Supabase และจัดการรายการหลังร้านได้ โดยไม่ทำข้อมูลรุ่นเก่าสูญหาย

**Architecture:** Laravel เป็น control plane และตรวจสิทธิ์/validation ให้ฟอร์มมาตรฐาน; Supabase ที่บัญชีเลือกเป็น data plane สำหรับคำขอและเหตุการณ์สถานะ. React editor และ AI proposal ใช้ definition contract เดียวกัน. ทำเป็น vertical slices ตามลำดับด้านล่าง ไม่ปล่อย public form ก่อนผ่าน production artifact gate.

**Tech Stack:** PHP 8.3+, Laravel 13, React 19, Tailwind v4, MySQL, Supabase Postgres/JSONB/RLS; ใช้ dependency เดิมก่อน

**Spec:** `docs/superpowers/specs/2026-10-15-booking-form-template-design.md` — ผู้ใช้อนุมัติแล้ว

## Global Constraints

- คงการเชื่อม Supabase รายบัญชี ไม่เพิ่ม OAuth หรือพนักงานรายเว็บ
- UUID แยกรายฟอร์ม; account owner เท่านั้นที่ดูรายการและจัดการหลังร้าน
- v1 คือคำขอจอง pending รอร้านยืนยัน ไม่รับประกันโต๊ะว่างอัตโนมัติ
- จำนวนคนเป็นจำนวนเต็ม 1–20, เวลาแสดง Asia/Bangkok เก็บ UTC, ห้ามเวลาย้อนหลัง
- ช่องเพิ่มสูงสุด 20 ช่อง: text/textarea/number/select/checkbox; select สูงสุด 30 options
- คำตอบสูงสุด 16 KiB, ปฏิเสธ unknown fields, ข้อความไม่รับ HTML/JS
- field ID คงเดิมเมื่อเปลี่ยน label; archive แทนลบ; เปลี่ยนชนิดหลังใช้งานต้องสร้าง ID ใหม่
- ทุก definition version immutable; submission เก็บ snapshot รุ่นที่ส่ง
- key ลับอยู่ server เท่านั้น; เปิด RLS และไม่ให้ anon/authenticated อ่าน/เขียนตารางโดยตรง
- ภาษามนุษย์เสนอการเปลี่ยนแปลง; เฉพาะปุ่ม Apply ที่เจ้าของกดจึงบันทึก definition
- ไม่เพิ่ม payments, QR, สลิป, ใบเสร็จ, table allocation, notifications หรือ staff roles รอบนี้
- ไม่มี reset/drop ตารางเดิม; remote migration ทดสอบเฉพาะ Aiwebsite ที่ผู้ใช้เลือก
- Preserve staged/unstaged user work. Repository ปัจจุบันยังไม่มี HEAD และมีไฟล์ผู้ใช้ staged จำนวนมาก: ห้าม bulk stage/commit; checkpoint ตามรายชื่อไฟล์ของงานเมื่อผู้ใช้ต้องการ version control

## Review Focus

1. ช่องภาษาไทย/Unicode หรือ label ซ้ำต้องไม่ทำให้ field ID และคำตอบสลับ — Task 1/6
2. กดซ้ำหลัง network timeout ต้องได้รายการเดิม ไม่สร้างซ้ำหรือผูก payload ใหม่ — Task 3/5
3. เปลี่ยนฟอร์มระหว่างที่ลูกค้ากรอกต้องเก็บ draft ไว้เมื่อพบ version conflict — Task 5/7
4. สองแท็บแก้ definition หรือสถานะพร้อมกันต้องไม่ overwrite เงียบ — Task 2/3/6
5. เว็บที่ publish ต้องเรียก API จริง ไม่โดน service worker/static fallback หรือ CORS ผิด origin — Task 5/7/9

## Verification runtime

ใช้ standalone regression ตามรูปแบบ tests/regressions เดิม เพราะ PHPUnit ใน vendor ปัจจุบันยังไม่ติดตั้ง
เพิ่ม `tests/regressions/forms-bootstrap.php` ที่เปิด app และใช้ SQLite memory/fake HTTP เท่านั้นสำหรับ local tests
แยก live tests เป็น opt-in และยืนยัน project_ref ก่อนทุกครั้ง ห้ามใช้ live test ตรวจ authorization ด้วยการอ่านข้อมูลลูกค้าจริง

คำสั่ง PHP ใน Windows นี้:

```powershell
& 'C:/MAMP/bin/php/php8.5.10/php.exe' -d extension_dir=C:/MAMP/bin/php/php8.5.10/ext -d extension=openssl -d extension=pdo_sqlite -d extension=mbstring -d extension=curl tests/regressions/NAME.php
```

live harness ใช้ pdo_mysql แทน pdo_sqlite และ CA bundle ของ MAMP โดยไม่ปิด TLS verification
JS pure tests ใช้ `node --test tests/regressions/NAME.test.mjs`; browser QA ใช้ browser tool ที่เชื่อมอยู่

## File boundaries

- `app/Lab/Forms/`: definition rules, versions, datastore access, submission handling, AI proposals; ไม่ยัดทุกอย่างใน controller
- `app/Models/LabForm.php`, `LabFormVersion.php`: control-plane records เท่านั้น ไม่ถือ customer answers หรือ credentials
- `app/Http/Controllers/Lab/LabFormController.php`, `LabFormSubmissionController.php`: owner API
- `app/Http/Controllers/Api/LabPublicFormController.php`: public definition/submit เท่านั้น
- `routes/lab-forms.php`, `routes/api.php`: owner/stateless public routes แยกกัน
- `resources/js/lab/components/forms/`: editor, preview, proposal และ owner list
- `resources/js/lab/lib/forms.js`, `formDefinition.js`: API transport กับ field operations แยกกัน
- `resources/js/lab/form-runtime/`: renderer/client ที่ bundle เป็น source เข้า generated project ได้
- `database/sql/lab-forms/0001_initial.sql`: managed Supabase schema/RPC ที่มี migration version
- `tests/regressions/`: local/fake assertions; live harness แยกและปิดโดย default

## Task 1: Definition contract และต้นแบบที่ตรวจสอบได้

**Files:** Create `app/Lab/Forms/FormDefinition.php`, `BookingTemplate.php`, `resources/js/lab/lib/formDefinition.js`, `tests/regressions/forms-bootstrap.php`, `form-definition.php`, `form-definition.test.mjs`, `tests/fixtures/forms/booking-v1.json`.

**Interfaces:** `BookingTemplate::definition(): array`; `FormDefinition::validate(array $definition, ?array $previous = null): array` คืน canonical definition หรือ Laravel ValidationException. JSON shape: `{template:'table_booking', fields:[{id,label,type,required,archived,options?}]}`. Core IDs: `customer_name`, `phone`, `requested_at`, `party_size`, `notes`; custom IDs เป็น UUID prefix `field_`, ไม่ derive จาก label. JS `applyFieldOperations(definition, operations)` รองรับ add/label/required/reorder/archive; ไม่รับ SQL/script.

- [ ] เขียน failing PHP/JS assertions: template core IDs ครบ, 21 custom fields ไม่ผ่าน, select 31 options ไม่ผ่าน, label ไทยซ้ำไม่ทำ ID ซ้ำ, archive ยังเก็บ field เดิม, type change ของ ID เดิมถูกปฏิเสธ
- [ ] รันทั้งสอง suite ยืนยัน Red จาก implementation ที่ยังไม่มี
- [ ] Implement contract เดียวตาม fixture; text label 1–120 ตัวอักษร, options เป็น `{id,label}` ที่คง ID, ไม่มี HTML/JS. Core types ล็อก: text/text/datetime/integer/textarea; optional phone syntax ตรวจตอน submit
- [ ] รัน Green ทั้งสอง suite; ใช้ fixture เดียวกันป้องกัน PHP/JS shape drift
- [ ] ตรวจ diff เฉพาะงานและ checkpoint ผลทดสอบใน Implementation Note ก่อน Task 2

## Task 2: Owner API และประวัติแบบฟอร์มใน MySQL

**Files:** Create `database/migrations/2026_10_15_010000_create_lab_forms_tables.php`, models สองตัวข้างต้น, `app/Lab/Forms/FormDefinitions.php`, `LabFormController.php`, `routes/lab-forms.php`, `tests/regressions/form-owner-api.php`. Modify `routes/web.php` ให้ include owner routes ใน auth+verified group.

**Interfaces:** `FormDefinitions::create(User $owner, LabProject $project, string $title): LabForm`; `saveVersion(User $owner, LabProject $project, LabForm $form, int $expectedVersion, array $definition): LabFormVersion`; `setState(..., string $state, int $expectedVersion): LabForm`. รับ state draft/active/closed; activation ต้องผ่าน datastore installation check ใน Task 4.

- [ ] เขียน failing tests: owner create/save/read, guest 401, อีกบัญชีและ project/form mismatch 403/404, frozen ปฏิเสธแก้ definition, สองแท็บ expected_version เดิมอันที่สองได้ 409
- [ ] รัน Red; ใช้ SQLite migrations เฉพาะชุด fixture ไม่แตะฐาน local จริง
- [ ] สร้าง `lab_forms` ตาม spec, UUID unique, ref ที่ bind, active_version nullable; `lab_form_versions` unique(form_id,version). latest version คำนวณจาก versions; บันทึก draft ไม่เปลี่ยน active_version จนกด activate
- [ ] Owner routes: GET/POST `/lab/{project}/forms`; GET `/lab/{project}/forms/{form}`; POST `.../versions` body `{expected_version,definition}`; PATCH `.../state` body `{expected_version,state}`. ใช้ transaction + lock สำหรับ save/activate
- [ ] รัน Green; ตรวจว่า snapshot รุ่นเก่าไม่ถูก update และไม่มี endpoint รับ owner_id จาก body

## Task 3: Supabase storage/RPC ที่ scoped และ atomic

**Files:** Create `database/sql/lab-forms/0001_initial.sql`, `app/Lab/Forms/FormDatastore.php`, `SupabaseFormInstaller.php`, `tests/regressions/form-datastore.php`, `.agent-work/debug/live-form-storage.php` (opt-in); consume Broker เดิม ไม่เปลี่ยน default fallback.

**Interfaces:** `FormDatastore::forForm(LabForm $form): array` resolve owner → broker → ตรวจ exact ref/ready/server credential; `install(LabForm $form): void`; `submit(LabForm $form, array $validated, array $snapshot, string $key, string $hash): array`; `list(LabForm $form, array $filters, ?string $cursor): array`; `transition(LabForm $form, string $submissionUuid, string $expectedStatus, string $nextStatus, int $actorId): array`.

- [ ] เขียน failing fake-HTTP tests: เปลี่ยน ref, disconnected, ไม่มี steward key ต้องหยุดก่อน upstream; payload ทุกคำสั่ง scope form_uuid, fixed RPC/table allowlist, ไม่รับชื่อ table จาก caller
- [ ] รัน Red แล้ว implement installer ที่ส่ง **หนึ่ง transaction SQL block** ผ่าน Management API โดยไม่ split ด้วย SqlFence. ใช้ managed version table `krikkit_form_schema_versions`, transaction advisory lock และ schema compatibility check; ตารางชื่อชนกับของเดิมแต่ shape ผิดต้องหยุด ไม่แก้ทับ
- [ ] SQL สร้าง submissions/events ตาม spec; RPC `krikkit_submit_form` กับ `krikkit_transition_form` บันทึกใน transaction. unique(form_uuid,idempotency_key); hash เดิม replay คืน UUID เดิม; hash ต่างคืน conflict. Transition pending→confirmed/cancelled, confirmed→cancelled; cancelled terminal. Status mismatch คืน conflict; event กับ status commit พร้อมกัน
- [ ] เปิด RLS, revoke direct access ของ anon/authenticated, grant fixed RPC ให้ service_role เท่านั้น, security definer ตั้ง empty search_path + fully qualified names. Server มีหน้าที่ตรวจ owner; ห้ามมองว่ามี service key แล้ว client ระบุ form ไหนก็ได้
- [ ] รัน fake tests Green; live opt-in บน Aiwebsite: install ซ้ำไม่เปลี่ยนข้อมูล, ทดสอบ RPC ด้วย UUID ฟอร์ม fixture, retry/concurrent transition, anonymous direct access ถูกปฏิเสธ. ไม่ลบข้อมูล fixture อัตโนมัติ เก็บเป็น test-only records
- [ ] ทดสอบ rollback ของ migration บน temporary test transaction/schema ที่มีชื่อเฉพาะ ไม่ drop/reset public tables

## Task 4: Activate และ owner submission API

**Files:** Create `app/Http/Controllers/Lab/LabFormSubmissionController.php`, `tests/regressions/form-submissions-owner.php`; modify `FormDefinitions.php`, `routes/lab-forms.php`.

**Interfaces:** GET `.../forms/{form}/submissions` query `{status?,date_from?,date_to?,q?,cursor?}` คืน `{items,next_cursor}` page size 25 สูงสุด 100. GET `.../submissions/{submission}` คืน snapshot/answers. PATCH `.../submissions/{submission}/status` body `{expected_status,status}`.

- [ ] Red tests: another owner/another form/forged UUID อ่านหรือเปลี่ยนสถานะไม่ได้; date filter แปลงขอบวัน Bangkok เป็น UTC; cursor เสียได้ 422; ค้นหา Unicode ไม่ประกอบ SQL string
- [ ] Implement activation: ตรวจ current ref, install/verify schema ก่อนเปลี่ยน state+active_version; ถ้าฐานล้มเหลวไม่แสดง active. Owner status operations ตรวจ owner แต่ไม่ใช้ frozen editor เป็นเหตุซ่อนคำขอที่เผยแพร่อยู่
- [ ] จำกัด filter allowlist ใช้ fixed RPC หรือ encoded PostgREST filters ผ่าน transport, ไม่มี raw SQL จาก search. masked/sanitized errors ไม่คืน upstream keys/query
- [ ] Green tests รวม status transition สองแท็บและ audit actor เป็น owner จาก session ไม่ใช่ body

## Task 5: Public API validation, retries และ origin policy

**Files:** Create `routes/api.php`, `app/Http/Controllers/Api/LabPublicFormController.php`, `app/Lab/Forms/FormSubmissionValidator.php`, `app/Http/Middleware/LabFormCors.php`, `tests/regressions/form-public-api.php`; modify `bootstrap/app.php`, `app/Http/Middleware/ServeLabPublication.php` และ rate-limiter registration ใน `app/Providers/AppServiceProvider.php`.

**Interfaces:** GET `/api/lab-forms/{uuid}` คืน `{form_uuid,title,version,definition,notice}` สำหรับ active เท่านั้น. POST `/api/lab-forms/{uuid}/submissions` body `{version,core,answers,idempotency_key,website}`; success 201 `{reference,status:'pending'}`, replay 200. ไม่มี public read/list submission. `FormSubmissionValidator::validate(array $body, array $definition): array` คืน normalized UTC payload; hash SHA256 ของ canonical version+core+answers ไม่รวม honeypot.

- [ ] Red endpoint tests: body>16KiB→413, core/answers invalid→422 keyed errors, unknown fields/forged owner/status/table→422, old version→409, inactive→404, DB mismatch/down→503, duplicate retry ไม่เพิ่มรายการ
- [ ] Implement strict name 1–120, phone pattern `^\+?[0-9][0-9 ()-]{7,19}$` หลัง trim, future ISO datetime with explicit offset; เทียบ server clock และ normalize UTC; party_size integer1–20. Notes max2000, text max500, textarea max2000, numeric finite only, checkbox boolean, select ใช้ option ID
- [ ] Register public routesแบบ stateless ไม่มี CSRF exception แบบกว้าง. Owner routesยังใช้ web CSRF. จำกัด 10 submissions/minute/IP/form และ 100/minute/form; honeypot ไม่ว่างไม่บันทึกและคืน generic rejection; ไม่ log body/PII
- [ ] CORS เช็ค form project live verified origin หรือ explicit local origin เฉพาะ APP_ENV=local; Origin null/opaque ไม่อนุญาต; ไม่มี credentialed wildcard. OPTIONS และ actual response policy ตรงกัน. แยก CORS path จาก global HandleCors ไม่ให้ preflight ถูกตอบด้วย permissive default
- [ ] Exempt reserved API path จาก publication static GET fallback โดยไม่ยกเว้น protected owner routes; public client ใช้ credentials:'omit'. Existing unpublished/site routes preservation tests ต้องผ่าน
- [ ] Green รวมกรณี absent/untrusted Origin ไม่ได้สิทธิ์ admin, timeout แล้ว retry, รหัส error ไม่มี secret และ GET API ไม่กลายเป็น index.html

## Task 6: Forms editor และหลังร้านใน workspace

**Files:** Create `resources/js/lab/components/forms/FormsDesk.jsx`, `FormEditor.jsx`, `FormSubmissions.jsx`, `resources/js/lab/lib/forms.js`, `tests/regressions/forms-client.test.mjs`; modify `WorkspaceTabs.jsx`, `WorkspaceStage.jsx` และ prop pass-through ใน `LabApp.jsx`/workspace parent ที่จำเป็นเท่านั้น.

**Interfaces:** `FormsDesk({projectUuid,onEmbed,onAskLab})`; `FormEditor({form,onSave,onActivate,onClose})`; `FormSubmissions({projectUuid,formUuid})`; transport `listForms/createForm/saveFormVersion/setFormState/listFormSubmissions/getFormSubmission/setSubmissionStatus` ใช้ route/body ของ Task 2/4 พร้อม same-origin CSRF.

- [ ] Red pure client testsสำหรับ serialization/field errors/version conflict; browser QA checklistเริ่มก่อน implementation
- [ ] เพิ่มเมนู Forms และ panel โดยไม่ลบ Supabase/Files/Console. ต้นแบบหนึ่งตัว; editorใช้ field operations ของ Task1; preview ไม่ส่งข้อมูลจริง; ปุ่ม save draft/เปิดรับ/ปิดรับแยกชัด
- [ ] UI มี label, keyboard focus, add/archive/reorder/required/options, Unicode labels; save conflict เก็บ unsaved draft ไม่ overwrite server; closed form ยังดูประวัติได้
- [ ] Owner list โหลดจริง รองรับ loading/empty/error/detail/status; แสดง snapshot ของแต่ละ submission ไม่ใช้ label ปัจจุบันแทน; ไม่มีปุ่ม payment/receipt หลอก
- [ ] Green JS + browser: create/save/reload, addช่องไทย, invalid/empty state, เปลี่ยนสถานะ, อีกบัญชีต้องถูกปฏิเสธจริงใน endpoint tests

## Task 7: Standard booking runtime และฝังใน generated site

**Files:** Create `resources/js/lab/form-runtime/BookingForm.jsx`, `client.js`, `app/Lab/Forms/FormEmbed.php`, `tests/regressions/form-embed.php`, `booking-runtime.test.mjs`; modify owner controller/routesเพื่อ POST `.../forms/{form}/embed`.

**Interfaces:** `BookingForm({apiOrigin,formUuid,preview=false})`; client `loadDefinition`, `submitBooking`, เก็บ stable idempotency key ต่อ canonical payload จนผลแน่นอน. `FormEmbed::files(LabForm $form): array<string,string>` คืน source files สำหรับ VFS; embed endpoint ตรวจ writable owner แล้ว commitAtomic ผ่าน SiteWorkspace เดิม.

- [ ] Red tests: sourceไม่มี secret/service key, apiOrigin จาก trusted app config ไม่รับ arbitrary caller URL; relative component importsถูกต้อง; ไม่ overwrite src/App.jsx หรือ stylesheet เดิม
- [ ] Implement wrapperมาตรฐานพร้อม field renderer/validation/errors/success; noticeคงที่ 'ส่งคำขอจองแล้ว รอร้านยืนยัน' ไม่อ้างมีโต๊ะหรือส่งอีเมล. เก็บค่าที่กรอกเมื่อ409และให้ reviewก่อนส่งด้วยversionใหม่; 503ไม่แสดง success
- [ ] Embed เขียน `src/krikkit-forms/BookingForm.jsx`, `client.js` และ `config.json` (URL/form UUIDเท่านั้น); promptให้ AI import component ไปหน้า booking โดยไม่แก้ client มาตรฐานหรือเพิ่ม mock submit
- [ ] Green runtime testsสำหรับ required/new fields/unknown options/same retry key; browserส่งข้อมูลสมมติใน private preview แล้วเห็นในowner listหลังrefresh. ตรวจ service-worker network route ไม่ชน API และไม่มี credentialsใน artifacts

## Task 8: AI proposals ที่ยังต้องกด Apply

**Files:** Create `app/Lab/Forms/FormProposal.php`, `resources/js/lab/components/forms/FormProposalCard.jsx`, `tests/regressions/form-proposal.php`; modify `LabToolCatalog.php`, `StageContextContract.php`, `toolRuntime.js`, `agentLoop.js`, observation/card rendering และ PHP/JS pseudo-tool allowlists เฉพาะชื่อใหม่.

**Interfaces:** tools `read_form({form_uuid})` กับ `propose_form_change({form_uuid,expected_version,operations})`; POST owner `.../forms/{form}/proposals` คืน `{form_uuid,base_version,definition,summary}` จาก server validator. ปุ่ม Apply ส่ง canonical definition ไปsaveVersion Task2; toolไม่มี apply/activate/status mutation.

- [ ] Red tests: 'เพิ่มแพ้อาหารไม่บังคับ' → add text optional; requestลบcore/typeแก้core/SQL/HTML/ownerเปลี่ยน/statusชำระเงินต้องreject; stale expected_version→409
- [ ] Implement tool contractและcardที่แสดง diff/preview/Apply/Cancel; proposalsไม่ส่งข้อมูลลูกค้าหรือsubmissionsให้AI. AIไม่สร้างตารางเองสำหรับmoduleนี้และไม่activateฟอร์มจากtool
- [ ] Green testsยืนยันการเรียกtoolไม่เพิ่มversionหรือเปลี่ยนactive_version; Applyผ่านowner/CSRF/definition validatorเดิม; Cancelไม่เปลี่ยนข้อมูล
- [x] BrowserทดสอบจริงกับToraหนึ่งรอบว่าคำขอไทยได้proposalและเพิ่มช่องหลังเจ้าของกดApplyเท่านั้น

## Task 9: End-to-end, production artifact gate และการส่งมอบ

**Files:** Create `Support/BOOKING-FORMS.md`, `tests/regressions/form-publication-routing.php`; modify `publishBuild.js`/`publishEsbuild.js`/runtime sourceเฉพาะเมื่อ reproduceพบสาเหตุ; บันทึก debug noteแยกสำหรับbuild failure.

- [x] Reproduce customer buildเดิมที่exit0แต่ไม่มีdistเป็นRedโดยใช้โปรเจกต์ทดสอบเดิม; ห้ามอ้างhost npm buildแทนcustomer build
- [x] ซ่อมเฉพาะcauseที่พิสูจน์ได้ด้วยregression; ต้องมีdist/index.html+JS/CSSจริง, importbundleครบ, ไม่มีunresolved /src imports. ปิดpublic activationเมื่อไม่มีverified working publicationสำหรับproduction; local modeทดสอบได้ชัดว่าlocal
- [x] รัน PHP suites Task1–8 และexisting 29 datastore/12callback checks; node --test JS suites; PHP lint; artisan view:cache; npm run build. ทุกผลบันทึกแยกmock/local/live
- [x] Browserจากgeneratedฟอร์ม: invalid→422, valid→pending, reload→รายการยังอยู่, เพิ่มช่องผ่านAI→versionใหม่, คำตอบเก่ายังตรงsnapshot; retry/concurrencyไม่มีdup, another-account/API key exposure testsผ่าน
- [x] เสิร์ฟ production artifactในlocaltest originที่อนุญาตและทดสอบpublicAPI+deep route+CORS/OPTIONSทั้งสำเร็จและปฏิเสธ; ไม่เผยแพร่public DNSหรือเปลี่ยนโดเมนจริงโดยปริยาย
- [x] ใช้code-simplifyและfresh read-only independent reviewerตามทักษะrequesting-code-review; ไม่ใช้agentรายงานแทนverification. ถ้าreviewerใช้งานไม่ได้รายงานข้อจำกัด ไม่อ้างผ่านreview
- [x] ส่งมอบURL/ภาพจริง/ขอบเขตที่ทำงานและข้อจำกัด manual confirmation; ไม่รายงานว่า payments, table availability หรือproduction internet rolloutเสร็จ

## Plan self-review

- Spec coverage: definition/version/fields→1–2; data+RLS+idempotency/events→3; owner management→4/6; public/auth/origin→5; renderer/embed→7; conversation→8; acceptance/build→9
- Step/Interfaces: ทุก endpoint/tool มีผู้สร้างและผู้ใช้ใน task ระบุไว้; definition draft ไม่เปลี่ยน active_version; publicversionเทียบactiveversionไม่ใช่latestdraft
- Review Focus ทั้ง5รายการมีtestsในtaskเจ้าของ; prod buildและSupabase live แยกจากhost build/fake tests
- Decomposition: ทั้งหมดเป็นระบบฟอร์มจองหนึ่งvertical subsystem; payments/staff/table allocationไม่รวมแผนนี้
- Git safety: เอกสารนี้ยังไม่commitเพราะrepositoryไม่มีHEADและมีstaged user workจำนวนมาก; ไม่สร้างinitial commitปนงานผู้ใช้

## Execution handoff

รอผู้ใช้ตรวจแผนและเลือกวิธีลงมือ. แนะนำ **Native**: ผู้ช่วยหลักทำงานตามtaskในแชตนี้
แล้วให้ผู้ตรวจอิสระตรวจตอนจบ เพราะงานพึ่งcontractเดียวกันและลดต้นทุนcontextต่อtask
อีกทางคือ Subagent-driven: แยกผู้ทำและผู้ตรวจรายtask ใช้ทรัพยากรมากกว่าแต่ตรวจอิสระถี่กว่า
ยังไม่เริ่มproduct codeหรือremote migrationจนกว่าผู้ใช้ตรวจแผนและเลือกวิธี
