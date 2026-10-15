# ฟอร์มจองต้นแบบที่แก้ได้ด้วย AI — แบบระบบระยะแรก

วันที่: 15 ตุลาคม 2026

สถานะ: แบบระบบสำหรับผู้ใช้ตรวจ ก่อนจัดทำแผนลงมือและแก้ product code

## 1. เป้าหมายที่ตกลง

ลูกค้า Krikkit เลือกต้นแบบจองโต๊ะ เพิ่ม/ปรับช่องผ่านหน้าตั้งค่าหรือภาษามนุษย์
ดูตัวอย่างก่อนใช้ ส่งคำขอจองจริงลง Supabase และดูรายการในหลังร้านได้
นิยามฟอร์มเป็นแหล่งข้อมูลเดียว ไม่ให้ AI เขียนหน้าเว็บที่แยกขาดจากข้อมูลหลังร้าน

คงการเชื่อม Supabase รายบัญชีตามคำสั่งผู้ใช้ ไม่เปลี่ยนเป็น OAuth รายเว็บ
แต่ละฟอร์มมี UUID แยกและอ้างถึง Lab project เดิมเพื่อไม่ให้รายการต่างฟอร์มปะปนกัน
เจ้าของบัญชีดูแลฟอร์มของตนเองได้ทั้งหมด ยังไม่มีบทบาทพนักงานหรือผู้ดูแลรายเว็บ

ระยะแรกเป็น **คำขอจองที่รอร้านยืนยัน** ไม่ใช่รับประกันโต๊ะว่าง/ยืนยันที่นั่งอัตโนมัติ
ข้อความนี้ต้องเห็นได้ทั้งก่อนส่งและหน้าสำเร็จ

## 2. หลักฐานจากระบบเดิม

- `app/Datastore/SupabaseLinkBroker.php`: OAuth และโปรเจกต์ Supabase ที่เลือกผูกกับบัญชี
- `app/Datastore/DatastoreSurveyor.php`: ตรวจ schema และคืนเฉพาะ client env ที่เผยแพร่ได้
- `app/Datastore/DatastoreRevisor.php`: ใช้ Management API ทำ SQL เป็นราย statement ไม่ใช่ migration runner แบบ transaction ทั้งชุด
- `resources/js/lab/orchestration/toolRuntime.js`: agent เรียก survey/revise และเขียนไฟล์ผ่าน native tools
- `resources/js/lab/lib/labWizard.js`: wizard แปลง fields เป็น prompt/data-model contract ยังไม่ใช่ live form definition
- `app/Lab/LabProjectAccess.php`: ตรวจเจ้าของและ frozen state ของ project
- `app/Models/LabPublication.php`: รู้โดเมนที่เผยแพร่และยืนยันแล้ว ใช้เป็นแหล่งที่มาของ allowed origin ได้
- ระบบ Stripe/PayPal ปัจจุบันใช้ขายแพ็กเกจ Krikkit ห้ามใช้แทนบัญชีรับเงินร้านค้า
- ทดสอบจริง Aiwebsite แล้ว: OAuth, schema survey และ SELECT 1 ผ่าน ยังไม่มี public tables ในครั้งที่ตรวจ
- การเผยแพร่ production artifact ของเว็บที่ AI สร้างยังไม่ผ่าน end-to-end จึงยังไม่เปิดฟอร์มสู่สาธารณะอัตโนมัติ

## 3. ทางเลือกและข้อสรุป

1. **ต้นแบบ + นิยามฟอร์ม + ตัวแสดงผลมาตรฐาน (เลือก):** ปรับช่องผ่าน UI/AI ได้ แต่ validation และการเก็บข้อมูลใช้กติกาเดียวกัน
2. ให้ AI เขียนทั้ง UI และ SQL ใหม่ทุกครั้ง: ใช้ของเดิมได้เร็ว แต่ตรวจความเข้ากันและความปลอดภัยซ้ำทุกครั้ง
3. ฟอร์มตายตัว: ทำง่ายกว่า แต่เพิ่มช่องและใช้กับธุรกิจอื่นยาก

ใช้แบบที่ 1 โดยเก็บโครงสร้างและคำตอบเพิ่มเติมใน JSON/JSONB ไม่เพิ่มคอลัมน์ทุกครั้งที่ลูกค้าเพิ่มคำถาม

## 4. ขอบเขตการส่งมอบแรก

- ต้นแบบเดียว: ขอจองโต๊ะ
- หน้าตั้งค่า: ชื่อฟอร์ม ช่องมาตรฐาน ช่องเพิ่มเติม เรียงลำดับ บังคับกรอก ตัวเลือก และ preview
- ภาษามนุษย์: AI เสนอการเปลี่ยนแปลงในรูปแบบที่ตรวจสอบได้ ไม่ apply เงียบ ๆ
- ตัวแสดงฟอร์มและตัวบันทึกมาตรฐานที่นำไปฝังในหน้า React เดิมได้
- รายการหลังร้านของเจ้าของบัญชี: ค้นหา กรองตามฟอร์ม/วันที่/สถานะ เปิดรายละเอียด และเปลี่ยนสถานะ
- บันทึกและโหลดรายการจริงได้ หลัง refresh ยังอยู่และคำตอบรุ่นเก่ายังอ่านได้
- ทดสอบกับ Aiwebsite ด้วยข้อมูลสมมติเท่านั้น ไม่แก้หรือลบตารางอื่น

นอกขอบเขตแรก: รับเงินจริง, QR/สลิป, ใบเสร็จ, จัดผังโต๊ะ/ล็อกช่วงเวลา,
แจ้งเตือนอีเมล/SMS, บทบาทพนักงาน, เงื่อนไขแสดงช่องซ้อนกัน, และ drag-and-drop editor
ส่วนเหล่านี้เป็นรอบออกแบบ/พัฒนาต่อ ไม่แสดงปุ่มที่ทำเหมือนใช้งานได้แล้ว

## 5. นิยามฟอร์มและช่องข้อมูล

ช่องหลักคงรหัสและชนิดข้อมูล: ชื่อผู้จอง เบอร์โทร วันเวลาที่ต้องการ จำนวนคน และหมายเหตุ
เวลาแสดง Asia/Bangkok และเก็บเป็น UTC; จำนวนคนเป็นจำนวนเต็ม 1–20
คำขอวันเวลาที่ผ่านมาไม่ผ่าน validation ฝั่ง server

ช่องเพิ่มเติมรองรับ text, textarea, number, select และ checkbox สูงสุด 20 ช่อง
แต่ละช่องมี `id` คงที่, label, type, required, options และข้อจำกัดความยาว/ค่าตามชนิด
label เป็นข้อความล้วน ไม่รับ HTML หรือ JavaScript; select สูงสุด 30 ตัวเลือก
คำตอบทั้งหมดจำกัด 16 KiB และปฏิเสธรหัสช่องที่ไม่อยู่ใน definition รุ่นนั้น

การเปลี่ยน label/ลำดับ/required สร้างรุ่นใหม่แต่เก็บ field id เดิม
การเอาช่องออกจากฟอร์มคือ archive ไม่ลบคำตอบเก่า
การเปลี่ยนชนิดช่องที่เคยมีคำตอบให้สร้าง field id ใหม่พร้อม archive ตัวเดิม ไม่แปลงข้อมูลเงียบ ๆ
ช่องหลักห้ามลบหรือเปลี่ยนชนิดผ่าน AI

## 6. ที่เก็บและความรับผิดชอบ

**Krikkit MySQL (control plane):**

- `lab_forms`: UUID, owner user_id, lab_project_id, template, title, status draft/active/closed,
  active_version, และ Supabase project_ref ที่ใช้ตอนติดตั้ง
- `lab_form_versions`: form_id + version แบบ unique และ definition JSON แบบ immutable
- สร้าง/แก้ definition ภายใน transaction และตรวจ expected_version กันการแก้ทับ
- ฟอร์มไม่ถือ credential เอง; ใช้ resolver รายบัญชีเดิม
- ถ้าเจ้าของเปลี่ยน Supabase selection ให้หยุดการอ่าน/เขียนฟอร์มที่ผูก ref เก่าและแจ้ง reconnect
  ห้ามย้ายหรือเขียนลงฐานใหม่อัตโนมัติ การ migrate ข้อมูลอยู่นอกรอบนี้

**Supabase ของบัญชี (data plane):**

- `krikkit_form_submissions`: UUID, form_uuid, definition_version, definition_snapshot JSONB,
  ชื่อ/เบอร์/เวลาที่ขอ/จำนวนคนเป็นคอลัมน์หลัก, answers JSONB, status, idempotency key, created_at/updated_at
- `krikkit_form_submission_events`: submission_uuid, form_uuid, ผู้ดำเนินการ, สถานะเดิม/ใหม่, เวลา
- status เริ่ม pending เปลี่ยนเป็น confirmed หรือ cancelled โดยเจ้าของบัญชีเท่านั้น
- RLS เปิดและไม่ให้ anon/authenticated อ่านหรือเขียนตารางนี้โดยตรง
- เบราว์เซอร์เรียก Laravel API มาตรฐาน ไม่ได้รับ steward/management key
- Laravel ใช้ server-side credential ของบัญชีหลังตรวจ ownership และ scope form ทุก query
- ติดตั้งด้วย migration แบบมีรุ่นและ transaction ผ่าน Management API; ไม่ใช้ loop ราย statement เดิมแทนความเป็น atomic
- ไม่ reset database ไม่ปิด RLS และไม่สร้างสิทธิ์อนุญาตอ่านข้อมูลทั้งหมดเพื่อให้ทดสอบผ่าน

นิยามฟอร์มอยู่ MySQL เพื่อใช้ ownership เดิมได้; snapshot อยู่กับคำตอบด้วย
เพื่ออ่านรายการเก่าได้แม้ชื่อช่องหรือแบบฟอร์มใน Krikkit เปลี่ยนแล้ว

## 7. API และสิทธิ์

ตั้ง namespace ใหม่ ไม่แก้ความหมายของ endpoint datastore เดิม:

- Owner API ใต้ `/lab/{project}/forms`: list/create/detail/preview/version apply และรายการ/สถานะคำขอ
- ทุก owner endpoint ตรวจ auth, verified, LabProjectAccess และ form.lab_project_id ตรงกับ URL
- การเปลี่ยน definition ตรวจ writable/frozen state; การดูและจัดการคำขอของฟอร์มที่เผยแพร่อยู่เป็นสิทธิ์ owner ไม่ผูกกับการเปิด editor
- Public API ใต้ `/api/lab-forms/{formUuid}`: คืน definition ที่ active และรับ submission เท่านั้น ไม่มี list/search/get รายการจอง
- Public POST รับ version, core answers, additional answers, idempotency key และ honeypot; ไม่รับ user_id, table name, status หรือยอดเงินจาก client
- Server derive owner/ref/form ทุกครั้ง ตรวจฟอร์ม active และนิยามรุ่นที่แสดงยังตรงรุ่นล่าสุด
- version เก่าได้ 409 พร้อมให้โหลดฟอร์มใหม่ โดยไม่ทิ้งข้อความที่ลูกค้ากรอกไป
- invalid answers ได้ 422 แบบ field-level; form ปิด/ไม่มีได้ 404; datastore ไม่พร้อมได้ 503 ไม่ fallback ไปเก็บในเบราว์เซอร์แล้วอ้างว่าส่งแล้ว
- กันกดซ้ำด้วย unique(form_uuid, idempotency_key), replay ต้อง payload เดิม; key เดิมแต่คนละ payload ได้ 409
- rate limit แบบ per-IP และ per-form, จำกัด body, honeypot; ห้ามพึ่ง CORS เป็นการยืนยันตัวตนหรือกัน spam เพียงอย่างเดียว
- ข้อมูลติดต่อไม่ออกใน URL, log, AI prompt หรือ error response

Published frontend เรียก API origin แบบ explicit ไม่สมมติว่า `/api` บนโดเมน guest คือ Laravel
อนุญาต CORS เฉพาะ origin ที่ตรง live publication ที่ verified หรือ local preview origin ที่อนุญาตใน local mode
ไม่ส่ง owner session cookies ใน public requests และไม่ใช้ credentialed CORS แบบ wildcard
API routes ต้องไม่ถูก service worker/SPA static fallback ดักเป็น HTML

## 8. UI และการคุยกับ AI

เพิ่มทางเข้า Forms ใน workspace menu เดิม โดยให้เจ้าของเลือกต้นแบบและเปิด editor/รายการได้
ใช้ภาษาที่แยกชัดระหว่าง 'ตัวอย่าง', 'บันทึกแบบร่าง', 'เปิดรับคำขอ' และ 'ยืนยันการจอง'
นิยามฉบับร่างยังไม่เปิดรับบุคคลทั่วไป

AI ใช้ tool สำหรับอ่าน definition และเสนอ patch ของช่อง ไม่สั่ง SQL หรือเขียน payment/auth policy สำหรับ feature นี้
คำขอเช่น 'เพิ่มช่องแพ้อาหาร ไม่บังคับ' คืน preview พร้อมสรุปช่องที่เปลี่ยน
ปุ่ม Apply เรียก server validator ชุดเดียวกับ editor และสร้างรุ่นใหม่เมื่อผู้ใช้ยืนยัน
AI ไม่มีสิทธิ์ apply ข้อมูลเองจากข้อความกำกวมหรือเปลี่ยนสถานะคำขอว่า confirmed
request ที่อยู่นอกชนิดช่องที่รองรับแจ้งข้อจำกัด ไม่สร้าง code แอบแฝงใน definition

generated React ใช้ wrapper/template ที่แสดง schema และเรียก API จริง
AI ตกแต่งหน้าโดยรอบได้ แต่ไม่เขียนการบันทึกจำลองแทน handler มาตรฐาน
ฟอร์มตัวอย่างก่อนเปิดรับใช้ preview mode และไม่แสดงข้อความเหมือนบันทึกจริง

## 9. การตรวจรับ

1. สร้างฟอร์มจากต้นแบบในบัญชีทดสอบ เก็บ draft และ refresh กลับมาได้
2. เพิ่มช่องแพ้อาหารผ่าน editor และผ่าน AI proposal ได้ definition/validation แบบเดียวกัน
3. ส่งข้อมูลถูกต้องลง Aiwebsite เห็น pending ในหลังร้าน และเปิดดูหลัง refresh ได้
4. ฟอร์มว่าง เบอร์ผิด จำนวนคนผิด ตัวเลือกปลอม unknown field และ version เก่าไม่ถูกบันทึก
5. กดซ้ำ/timeout แล้ว retry ไม่เพิ่มรายการซ้ำ; payload คนละชุดใช้ key เดิมไม่ได้
6. เปลี่ยน label/ซ่อนช่องแล้วคำตอบรุ่นเก่ายังอ่านตาม snapshot ได้
7. อีกบัญชีอ่าน/แก้ definition หรือ submission ไม่ได้ แม้รู้ UUID; ทดสอบ direct HTTP ไม่ใช่แค่ซ่อนปุ่ม
8. Public client ระบุ owner/status/table เองไม่ได้ และ anon Supabase อ่านตารางรายการไม่ได้
9. ตัดการเชื่อมหรือเปลี่ยน Supabase ref แล้ว fail closed ไม่มีการเขียนไปโปรเจกต์อื่น
10. ข้อมูลสำคัญไม่หลุดในไฟล์ guest หรือผล AI; ทดสอบ migration ซ้ำและ rollback บน test schema
11. Host React build ผ่าน และ generated customer React build ต้องมี artifact จริง ไม่รับ exit 0 ที่ไม่มี dist
12. ทดสอบ route/POST จริงทั้ง private preview และ production artifact ที่เสิร์ฟในสภาพแวดล้อมทดสอบ
    ถ้าขั้นเผยแพร่ยังไม่ผ่าน ห้ามแสดงฟอร์มว่า production-ready หรือเปิดรับจริง

## 10. ลำดับขอบเขตต่อจากระยะแรก

หลังฟอร์มจองและเส้นทางจัดเก็บผ่าน จึงออกแบบสั่งล่วงหน้า/สินค้า แล้ว payment methods + ตรวจสลิป
และใบเสร็จตามลำดับ กฎคำนวณยอด การยืนยันเงิน และเอกสารจะเป็น server-side module แยก
ไม่ขยาย template จองให้กลายเป็นระบบชำระเงินโดยปริยาย

## 11. จุดหยุดก่อน implementation

ผู้ใช้ยืนยันแนวทางสนทนาแล้ว เอกสารนี้เพิ่มรายละเอียดทางเทคนิคและข้อจำกัดคำขอจองรอร้านยืนยัน
จึงต้องตรวจและอนุมัติแบบเอกสารนี้ก่อนจัดทำแผนลงมือ ไม่มีการสร้างตารางจริงหรือแก้ product code ในขั้นออกแบบ
