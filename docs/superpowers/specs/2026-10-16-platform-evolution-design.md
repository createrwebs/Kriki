# Krikkit Platform Evolution — Architecture and Product Design

วันที่: 2026-10-16 · สถานะ: Draft for owner review · Planning only

## 1. เป้าหมายและขอบเขต

ให้ลูกค้าสร้างเว็บไซต์หลายหน้าและระบบ interactive ที่บันทึกข้อมูลจริงได้จากภาษามนุษย์ เปิด/ปิดโมดูลตามสิทธิ์ นำเข้าโค้ด GitHub ในขอบเขตที่รองรับ และแก้โปรเจกต์ตนเองโดยไม่อ่าน/เปลี่ยนข้อมูลหรือโค้ดของบัญชีอื่น หน้าแรกต้องอธิบายคุณค่าและข้อจำกัดอย่างซื่อสัตย์ พร้อมตัวอย่างที่ตรวจสอบได้

แผนนี้เป็นชุดโครงการย่อย ไม่ใช่การอนุมัติ deploy หรือเพิ่มสิทธิ์รันโค้ดให้ลูกค้าทันที ไม่มี product code/migration/payment settings เปลี่ยนในรอบวางแผน การใช้ agent หลักทำทีละส่วนและผู้ตรวจอิสระก่อนส่งมอบเป็นวิธีที่เสนอ; รอเจ้าของตรวจแผนก่อนเริ่ม

ผู้ใช้กำหนดแล้ว: PromptPay scan → อัปโหลดสลิปพร้อมชื่อ อีเมล เบอร์โทร และเวลาอัปโหลด → ร้านตรวจเอง; หลังบ้านต้องเห็นการจองและการชำระเงิน การเก็บมัดจำการจองยังไม่ยืนยัน จึงกำหนด release แรกว่าจองโต๊ะฟรีและคิดเงินเฉพาะสินค้า จนกว่าจะมีการอนุมัติกฎมัดจำแยก

## 2. สิ่งที่พบในระบบจริง

| พื้นที่ | หลักฐานใน checkout ปัจจุบัน | ความหมายต่อแผน |
| --- | --- | --- |
| หน้าแรก | routes/web.php, resources/views/home/home.blade.php, home/partials/*, LandingCopy.php | Guest เห็น landing; login แล้ว `/` เป็น Studio อย่าเปลี่ยนทางเข้าเดิมโดยไม่ตั้งใจ |
| แก้ landing | app/Livewire/Dashboard/Landing/LandingComponent.php | มี CMS copy อยู่แล้ว ต้องรักษาค่าที่ผู้ดูแลบันทึก |
| Workspace | app/Lab/SiteWorkspace.php, LabProjectAccess.php | มี owner check, atomic file edits, UUID directory; directory แยกไม่เท่ากับ process sandbox |
| GitHub | app/Lab/Github/*, LabGithubController/RemoteController/VcsController, ImportFromGithubModal.jsx | มี import/link/pull/push/fork แล้ว ไม่สร้างระบบ OAuth หรือ import ซ้ำ |
| Runtime | resources/js/lab/runtime, labRuntime.js, publishEsbuild.js, publishBuild.js | React browser runtime มีอยู่; ตรวจ framework label ไม่ได้พิสูจน์ว่า build ได้ทุก Next/Nuxt repo |
| PHP preview | LabPreviewController::php เรียก include ไฟล์ workspace ใน Laravel process | Static finding: ขอบเขต owner/path ไม่แยกการ execute ต้องปิด untrusted execution หรือย้าย sandbox ก่อนเปิดหลายลูกค้า |
| Supabase | SupabaseLinkBroker, DatastoreRevisor, SqlFence | ผูกตามบัญชี; generic SQL revise ยังไม่เป็น project/object isolation; regex SQL fence ไม่ใช่ authorization boundary |
| Forms | app/Lab/Forms/*, components/forms/*, form-runtime/* | Booking v1 ผ่าน local browser+live storage; preserve version/snapshot/retry/history |
| Billing | app/Payments/CheckoutDesk.php | เก็บเงินแพ็กเกจของแพลตฟอร์ม ไม่ใช่ออเดอร์ร้าน ห้ามผสมบัญชี/สถานะเงิน |
| Commerce | App.jsx ใน café QA เก็บ cart เป็นจำนวนอย่างเดียว | ต้องสร้าง product/order/checkout module จริง ไม่มี service ชำระเงินร้านที่ซ่อมเพียง URL ได้ |
| สิทธิ์แพ็กเกจ | app/Entitlement/EntitlementCatalog.php, EntitlementGate | มี quota/features ให้ต่อยอด ไม่ใช้ flag ใน browser เป็นสิทธิ์จริง |

ข้อค้นพบเป็น source inspection และผลทดสอบเดิมที่บันทึกไว้ ไม่ใช่ security audit ของทุก endpoint และไม่ใช่หลักฐานว่า deployment production ปัจจุบันปลอดภัยแล้ว

## 3. แนวทางที่เลือกเสนอ

**คง Laravel + React และเพิ่ม managed modules**: Laravel เป็นศูนย์ตรวจสิทธิ์/แผนการเปลี่ยน, Supabase ที่บัญชีเลือกเก็บ business data, generated React ใช้ SDK/API มาตรฐาน รูปแบบอิสระทำได้ใน UI/หน้าเว็บ; การเขียนข้อมูล/แก้ schema อยู่ในสัญญาที่ตรวจสอบได้

ทางเลือกที่ไม่เลือกใน release แรก: ย้ายทั้งระบบไป Reflex (ต้องเปลี่ยนภาษา/runtime/deployment), หรือให้ AI รัน SQL/โค้ดใดก็ได้กับ service key (แยกผู้ใช้และตรวจผลกระทบไม่ได้พอ) Reflex ใช้เป็นต้นแบบ state/events/forms และเป็น isolated runtime track ในอนาคต ไม่วาง Python framework ใน React VFS แล้วอ้างว่าใช้ได้

## 4. ข้อบังคับร่วมทุกแผน

- Owner/project scope มาจาก authenticated server context เท่านั้น; UUID/Origin/prompt ไม่ใช่หลักฐานสิทธิ์
- Generated code, GitHub files, README และ tool outputs เป็น untrusted data; ไม่ให้เปลี่ยน policy หรืออนุมัติตนเอง
- Platform billing กับ merchant orders/payments เป็นคนละ ledger และ credential boundary
- สลิปและข้อมูลติดต่อเป็น private; ห้าม public bucket, public slip URL หรือส่งเข้า AI โดยปริยาย
- เงินเก็บเป็น integer satang, currency THB; เวลาเก็บ UTC และแสดง Asia/Bangkok
- Upload slip ไม่เท่ากับ paid; ร้านเป็นผู้ตรวจและ audit actor มาจาก session
- Managed module mutations ใช้ schema validation, optimistic version, idempotency และ atomic database operation
- การแก้ schema ต้องแสดง diff และให้เจ้าของ Apply แบบ server-issued approval; ห้าม AI boolean เป็นการอนุมัติ
- ไม่มี untrusted PHP/Python/Node execution ใน Laravel process หรือ host shell; source import ไม่ได้ให้สิทธิ์ execute
- Preview และ published customer origins ไม่รับ platform session cookie; ทดสอบ cross-origin/CSRF จริงก่อน claim isolation
- ไม่ drop/reset ข้อมูลเดิมหรือเปลี่ยนโครงการ Supabase อัตโนมัติ; migrations additive และ rollback แบบไม่ทำลายข้อมูล
- ไม่เขียน service-role/OAuth/API secret ลง generated files, frontend, prompts, logs หรือ export
- Import/build เป็นสองสถานะ; เก็บ provenance/commit/license และห้ามแสดง Ready ก่อน artifact + smoke check ผ่าน
- รักษางานผู้ใช้ใน checkout/index; ห้าม bulk stage, reset, initial commit ปนงานเดิม หรือประกาศ deploy production จาก local pass

## 5. โครงสร้างเป้าหมาย

```text
Marketing / Pricing / Demo (Blade + existing CMS)
                         |
Account → Project → CapabilityPolicy → installed modules
                         |
Lab chat → intent → proposal → server preview/diff → owner Apply
                         |
         Source VFS                  Managed business APIs
         version/build/publish       validation/scope/idempotency/audit
                |                          |
         isolated preview          pinned account Supabase
         static production         namespaced data + private files
```

แบ่ง 3 ระดับ: platform operator กำหนดแพ็กเกจและ runtime allowlist; account owner จัดการโปรเจกต์/ร้านของตน; visitor/customer ใช้เฉพาะ public operation ที่โมดูลเปิด เว็บร้านไม่ใช่ back office ของ platform และไม่มีสิทธิ์เข้าตารางควบคุมของ Laravel

### Module manifest ที่ agent และ UI อ่านร่วมกัน

ตัวอย่างสัญญาเป้าหมาย ไม่ใช่ไฟล์ที่ลูกค้าอัปโหลดมาเพื่อให้สิทธิ์ตนเอง:

```json
{
  "key": "manual_payments",
  "version": 1,
  "requires": ["catalog", "orders"],
  "runtime": ["react-vite-static"],
  "operations": ["settings.read", "settings.propose", "settings.apply", "slip.submit", "payment.review"],
  "human_only": ["settings.apply", "payment.review"],
  "data_scope": "project_module",
  "readiness": "planned"
}
```

`operations` เป็นรายชื่อที่ policy ต้องพิจารณาต่อ ไม่ได้เปิดให้ทุก actor ใช้ได้; `slip.submit` ต้อง scoped customer capability, `payment.review` ต้อง owner session. Module dependency/version/definition ของ installation ต้องตรวจฝั่ง server ก่อนสร้าง SDK config. SDK config ส่งเฉพาะ public identifiers/API origin ไม่ส่ง policy override หรือ secret

การขอระบบอื่น เช่น ระบบซ่อมรถ: classify ก่อนว่าใช้ custom entities/forms ได้หรือไม่ ถ้าได้จึงเสนอ schema/pages/roles ตาม D1–D4; ถ้าต้องการ financial calculation, external integration หรือ privileged action ใหม่ ให้เสนอ module spec ใหม่ ห้ามให้ AI สร้าง unrestricted server plugin เพื่อข้ามความสามารถที่มี

### Control plane (MySQL ของ Krikkit)

ตารางเสนอเพิ่มผ่าน migrations แยกย่อย:

| ตาราง | หน้าที่/คีย์สำคัญ |
| --- | --- |
| lab_project_capabilities | project_id unique FK, revision, overrides JSON; เก็บ deny/limits ไม่เก็บ service keys |
| lab_module_installations | UUID, project_id, module_key, module_version, config_version, state, pinned_supabase_ref, schema_namespace; unique(project,module_key) |
| lab_change_proposals | UUID, project_id, actor_id, module_uuid, base_version, operations, impact_hash, expires_at, state; immutable content |
| lab_operation_events | project_id/module_uuid/actor/action/correlation/time, redacted metadata; append-only |
| lab_import_inspections | UUID, project_id, source identity, commit SHA, root, file/size report, license, runtime verdict, expiry |
| lab_import_jobs | inspection UUID, idempotency key, expected source revision, state, manifest/artifact references |

Reuse LabProject, LabForm/versions, LabPublication, entitlement models และ encrypted integrations เดิม ไม่เพิ่ม owner_id ที่ caller กำหนดเอง โมดูลเดิม bookings import เป็น installation metadata ได้โดยไม่ย้าย/เขียนทับ submission snapshots

### Business data (Supabase ที่ผูกไว้)

- Booking เดิมยังใช้ตาราง/RPC krikkit_form_*; installation ต้องอ้าง form UUID เดิม ไม่ทำสำเนาคำตอบ
- Commerce: krikkit_products, krikkit_orders, krikkit_order_items, krikkit_payment_attempts, krikkit_payment_events พร้อม project_uuid/module_uuid ในทุก query/FK/RPC
- Custom data: ใช้ private project schema `k_<32hex project UUID>` ที่ server สร้างเอง ไม่ให้ caller ระบุ arbitrary SQL identifiers; publish ผ่าน managed API ไม่ expose schema ให้ anon โดยตรง
- private bucket `krikkit-slips`, object path `<project_uuid>/<payment_uuid>/<random>.<validated ext>`; server ดูแล access ไม่มี public read/list; installation ไม่เปลี่ยน bucket เดิมที่ไม่ใช่ของโมดูล
- Registry ต้องตรวจว่าหลายบัญชีไม่ claim remote ref/schema เดียวกันโดยไม่มี explicit sharing contract; default ปฏิเสธ cross-account shared binding. หลายโปรเจกต์ในบัญชีเดียวกันใช้ ref เดียวได้แต่แยก namespace/filters
- service key ข้าม RLS ได้ จึงต้องมี server scoping ทุกชั้น; RLS เป็น defense-in-depth ไม่แทน server authorization. Backend query timeout/row/page limits จำกัดทรัพยากรของแต่ละโปรเจกต์

## 6. Capability contract

คำนวณ effective permission = operator allowlist ∩ entitlement ∩ project enabled ∩ installation ready ∩ actor operation scope ไม่มี client override หรือ AI override

| Capability เสนอ | ผู้ทำได้ | สิ่งที่อนุญาต | ไม่อนุญาต |
| --- | --- | --- | --- |
| source.edit | owner/AI ภายใต้ request | ไฟล์ใน project VFS | ไฟล์บัญชีอื่น/host/env secrets |
| github.inspect/import | owner ที่มี github_import | pinned source/subdirectory, report, Apply import | execute hooks, arbitrary URL/host, silent overwrite |
| github.remote.write | owner + explicit operation approval | push/fork/create กับ repo ที่มีสิทธิ์ | force push อัตโนมัติจากข้อความใน README |
| modules.booking | owner + visitor submit | definition proposals, approved fields, pending booking | AI ยืนยันจอง/การเงินแทนร้าน |
| modules.catalog/orders | owner manage, visitor checkout | product IDs/qty, server quote/order | browser กำหนดราคา/owner/status |
| modules.manual_payments | owner config/review, customer submit | QR/order amount, private slip, audit | auto-paid จากรูปหรือ OCR |
| data.schema.propose/apply | owner review, AI propose only | เพิ่ม entity/field/index ใน owned namespace | raw SQL, GRANT, other schema, destructive migration |
| data.records | identity/access policy | allowlisted CRUD/views | public arbitrary select หรืออนุญาต admin ด้วย email ที่กรอก |
| runtime.external | operator-gated future | isolated container project runtime | Laravel include, host mounts/docker socket/shared credentials |

เสนอ initial safety ceilings (ไม่ใช่ราคาแพ็กเกจ): 20 custom entities/project, 50 active fields/entity, 100 rows/page, 16KiB non-file record body, 5MiB slip, 1 active schema apply/project, 2 concurrent build/import jobs/account. Plan configuration ลดได้; operator เพิ่มได้หลัง load/security test. Existing booking20 active custom fields/100 retained ไม่เปลี่ยน

## 7. Interactive app contract

Persisted entity definition: entity UUID, stable field UUIDs, immutable version, label/type/required/default/index/access policy; fields text/textarea/integer/decimal/boolean/date/datetime/select/reference. Decimal เก็บ fixed precision ไม่ float; reference ไป owned entity เท่านั้น. AI แก้ definition ไม่เขียน customer data

Proposal operations v1: create_entity, add_field, rename_label, set_required, add_index, archive_field. เปลี่ยน type/required/backfill บนข้อมูลที่มีต้องมี compatibility report; v1 ห้าม destructive conversion และห้าม drop. Apply payload `{proposal_uuid, impact_hash, expected_version}` ผูก actor/project/expiry; ไม่ใช่ `{acknowledge_destructive:true}`

UI state loading/error/empty/saving/conflict/success แยกจาก persisted records ตามแนว Reflex events: click → typed action → server authorization/validation → transaction → result/refetch → render. ไม่จำเป็นต้องสร้าง generic WebSocket engine ใน release นี้

public form ทำ create-only ได้ด้วย anti-abuse validation; staff-style CRUD ไม่เปิด public. หากต้องมี end-customer login ให้ใช้ Supabase Auth ของโปรเจกต์นั้น + JWT verification/audience/subject mapping ในโมดูล identity เฉพาะก่อนเปิด self-service read/update. ห้ามนำ platform owner session ไปใช้ใน generated app

## 8. Orders / PromptPay / slip review

Product catalog เป็น source of truth ฝั่ง server. Cart browser เก็บเฉพาะ product IDs/qty; checkout เก็บชื่อผู้ซื้อ email phone; payer_name แยกชื่อผู้ซื้อ. Quote อายุ10นาที, expected catalog version; create order ทำ snapshot ราคา/ชื่อ/recipient และ amount_satang/currency, idempotency key+payload hash

Order states: awaiting_payment → payment_review → accepted/processing → completed; cancelled แยกจาก payment. Payment attempt: submitted → approved/rejected; ส่งใหม่สร้าง attempt ใหม่ ไม่ทับ/ลบประวัติ. `uploaded_at` จาก server, `claimed_transferred_at` จากลูกค้า, `verified_at/reviewer_id` จาก owner review; ไม่ใช้ชื่อ paid_at แทนเวลาอัปโหลด

Customer order access release แรก: opaque capability256-bit อายุ24ชม, serverเก็บ hashและorder/project scope, runtimeส่งผ่าน `X-Krikkit-Order-Token` header และเก็บใน sessionStorage ของร้านนั้นเพื่อ refresh tab ได้. ไม่ใช้ platform session/third-party cookie, ไม่ใส่ token ใน URL/log/referrer และไม่มี public recovery ด้วย email+order ID. Token เป็นสิทธิ์เฉพาะออเดอร์ ไม่ใช่การพิสูจน์ชื่อหรือบัญชีธนาคาร; หน้า checkout ต้องลด XSS และไม่มี third-party script ที่ไม่จำเป็น

สลิป JPEG/PNG/WebP≤5MiB, inspect MIME/signature/decoded dimensions≤20MP, strip metadata/re-encode ถ้า decoderรองรับ; unsupported decoder failชัดเจน ไม่ยอมรับไฟล์เพราะนามสกุล. Object private, signed view5นาทีหลัง owner auth. Hash duplicate flag ภายในmerchant เดียว; ไม่อ้างตรวจสลิปปลอมได้. เก็บ attempts180วันเป็นค่าเสนอ ต้องให้เจ้าของยืนยัน retention/privacy policy ก่อน production; ไม่ลบ material data ใน local testอัตโนมัติ

Approve/Reject ต้อง expected_status/attempt_version และ transaction+audit. Approveไม่โอนเงินจริง; cancelไม่ทำrefundอัตโนมัติ. ไม่เพิ่มบัญชี PromptPay จริงโดยเดา และไม่ทดสอบโอนเงินจริงแทนผู้ใช้

## 9. GitHub import และขอบเขตรัน

UI ต้องแยก Import source / Link remote / Fork / Publish. Server inspect repo→resolve immutable commit→detect root/runtime/license/scripts/secrets→รายงาน→owner Apply→stage new snapshot→buildในขอบเขตที่รองรับ→smoke test→promote. Existing project importต้องแสดง file diff/expected workspace revision; no force overlay. ข้อผิดพลาดคืนsnapshotเดิมได้โดยไม่ลบ DBข้อมูลใหม่

React Vite static เป็น supported matrix เริ่มต้น. Static export framework อื่นเป็น conditional หลังfixture buildผ่าน; Next SSR/API route, Python Reflex, Laravel/PHP backend, Docker-compose เป็น source-only/unsupported-runtime พร้อมคำอธิบาย ไม่แปลงชื่อเป็น Reactแล้วอ้างรันได้

Limitsคงค่าเดิมเริ่มต้น25MiB archive/2500files/2MiBต่อfile; เพิ่ม expanded total100MiB/depth32/compression ratio100:1 และปฏิเสธ absolute/traversal/backslash/drive/UNC/symlink/submodule/git hooks/credential-bearing files. ตรวจ tree truncated/rate-limit และ never silent drop required source; preserve relevant license/NOTICE/lockfile (not execute) เพื่อreproducible build. LockfilesเดิมถูกskipในSiteWorkspace ต้องปรับด้วยfixture ไม่แก้กระจายโดยไม่ทดสอบ

Imported codeไม่เข้าcontrol-plane source. Npm lifecycle scriptsปิดเริ่มต้น; dependency resolve/buildมี timeout/memory/network allowlistและแยกcontext; browser workerไม่ใช่sandboxสำหรับservercodeหรือสิทธิ์admin. Repo instructions/tool outputไม่ให้สิทธิ์ส่งข้อมูลออก, push, activate, SQL หรือเปิดnetwork

### Publication compatibility และ rollback

Artifact manifest เป้าหมายเก็บ source_revision, artifact_hash, runtime_kind, module UUID/version, required_schema_version และ SDK version. Publish ตรวจ installation ready และ required schema ก่อน promote; ไม่ส่ง runtime secrets ใน manifest. Buildสำเร็จแต่ moduleไม่พร้อมต้องแสดง blocked/setup-required ไม่เปลี่ยน active publication เงียบ ๆ. Undo code/เลือกartifactเก่าไม่rollback records/payment/schema; ต้องใช้ compatibility report ว่าartifactเก่าทำงานกับschemaปัจจุบันได้ มิฉะนั้นไม่อนุญาต rollback ไปหน้าที่เขียนข้อมูลผิดรุ่น

## 10. หน้าแรก / ความซื่อสัตย์ในการขาย

เห็นใน10วินาทีว่า สร้างเว็บจากภาษาไทย → แก้หน้า/ข้อมูล → ทดสอบ → เปิดใช้บนโดเมนของคุณ; เหตุผลซื้อคือเริ่มเร็ว, มีโค้ดแก้ต่อ, โครงข้อมูล/หลังบ้านมาตรฐาน, ขอบเขตสิทธิ์ชัด ไม่ใช้คำว่าได้ทุกภาษา/ทุกrepo/จ่ายเงินอัตโนมัติ

หน้าlandingคงBlade/CMSเดิม ไม่เปลี่ยนเป็นReactทั้งหน้า. Theme white/warm neutral + existing accent, Thai readable font, real product screenshot, sparse motion. Pricingอ้างplanจริง ไม่ hardcodeตัวเลข, ไม่มีโลโก้ลูกค้า/สถิติ/คำรับรองปลอม

Feature readinessใช้ enum `available`, `limited`, `planned`; mapผ่านconfig serverเดียวกับmodule readiness. ไม่มี commerce/demo live claim จนbrowser acceptanceผ่าน. Guest `/` landingเหมือนเดิม; logged-in `/` Studioเหมือนเดิม; เพิ่ม public `/features` สำหรับคนloginที่อยากดูผลิตภัณฑ์โดยไม่logout

## 11. Delivery gates / non-goals

ลำดับ P0 safety → landing + module foundation → real commerce → interactive entities → hardened GitHub → optional Reflex runtime probe. แต่ละsliceต้องมีRed/Green, preservationtests, actualcustomerartifact, reviewerและhandoffnote ไม่อนุญาตเปิดphaseถัดไปเพียงเพราะhost npm buildผ่าน

ไม่รวม release แรก: automatic bank verification/OCR decision, refunds, tax invoice, staffteams, shipping/tax engines, full ecommerce inventory, arbitrary raw SQL/hostshell, Pythonmultiuserhosting. งานเหล่านี้ต้องsub-spec/permission/infraของตนเอง

เกณฑ์รวม: สองบัญชีและสองโปรเจกต์ในบัญชีเดียวกันไม่ข้ามไฟล์/records/slips/jobs/cache; unsupportedrepoอธิบายตรง; click/cart/submit/review persistจริง; publishversionสัมพันธ์กับmodule/schema; หน้าแรกขายเฉพาะความสามารถที่เปิดใช้และตรวจแล้ว

## 12. แหล่งหลักและข้อจำกัด

- [Reflex source](https://github.com/reflex-dev/reflex), [database docs](https://reflex.dev/docs/database/overview/), pinned analysisใน docs/platform/2026-10-16/reflex-analysis.md
- [GitHub contents API](https://docs.github.com/en/rest/repos/contents?apiVersion=2022-11-28)
- [OWASP multi-tenant security](https://cheatsheetseries.owasp.org/cheatsheets/Multi_Tenant_Security_Cheat_Sheet.html)
- [Supabase private storage](https://supabase.com/docs/guides/storage/buckets/fundamentals)
- PromptPay/manual reference findings: Support/PROMPTPAY-MANUAL-PAYMENT-RESEARCH.md

Self-review: source factsแยกจากtargetdesign; ไม่มีคำกล่าวว่าการเพิ่มPythonruntimeเสร็จ; payment/bookingstatesแยก; ไม่มีวันเวลาส่งมอบหรือquotaแพ็กเกจที่แต่งขึ้น; decisionsมัดจำ/retention/runtimeproductionแสดงเป็นgates. Planขอให้ตรวจเป็นเอกสารก่อนเริ่มimplementation
