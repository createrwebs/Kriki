# Guided Site Builder: Signup → Structure → Multi-page Design

วันที่: 2026-10-16 · สถานะ: Approved design; Part 1 signup implemented, Parts 2/3 pending

บันทึกความคืบหน้าหลังอนุมัติ: ส่วนสมัคร/โปรไฟล์บริษัทตาม§3 ส่งมอบและตรวจแยกแล้วบน local; หลักฐานอยู่ใน `Support/GUIDED-BUILDER-SIGNUP.md` และ delivery index. ข้อความเกี่ยวกับ “รอบออกแบบ” ด้านล่างเป็นบริบทเดิม ไม่ใช่ข้ออ้างว่าทั้ง builder/AI/canvas ใช้งานได้แล้ว.

## 1. เป้าหมายและการตัดสินใจที่ยืนยันแล้ว

ให้ผู้ใช้ใหม่เริ่มจากข้อมูลธุรกิจ ไปถึงเว็บไซต์หลายหน้าที่แก้โครงสร้างและธีมได้ โดยไม่ต้องเขียน prompt ยาวจากหน้าว่าง สมาชิกเดิมเปิด flow เดียวกันจาก “สร้างโปรเจกต์ใหม่” ได้

เจ้าของยืนยันในแชตแล้วว่า:

- รวมข้อมูลบริษัท/งบในฟอร์มสมัคร
- ใช้ประเภท Business site, Ecommerce store, Portfolio, Blog, Other
- แยกทาง AI site builder และ Template
- ตรวจ pages/sections ก่อนสร้าง จากนั้นดูทุกหน้าและแก้ธีม
- เริ่มจากเว็บใหม่ ไม่แปลงเว็บเดิมอัตโนมัติ

เจ้าของยืนยันแบบนี้ด้วยข้อความ “โอเค ทำไปตามแผนได้เลย” เมื่อ2026-10-16 ขั้นถัดไปคือแผนลงมือและเลือกวิธีดำเนินงาน ยังไม่ได้แก้ product code ของ flow ใหม่

## 2. Flow ที่ผู้ใช้เห็น

```text
สมัครพร้อมข้อมูลบริษัท/งบ → ยืนยันอีเมลตามระบบเดิม
                                  ↓
                   สร้างโปรเจกต์ใหม่ / เลือกประเภทเว็บ
                                  ↓
                  เลือกวิธีเริ่ม: AI หรือ Template
                       ↙                       ↘
           อธิบายเว็บ → AI เสนอแผน        เลือก template ที่แก้ได้จริง
                       ↘                       ↙
                   จัดโครงสร้าง pages และ sections
                                  ↓
                      ตรวจสรุป → กดยืนยันสร้าง
                                  ↓
                   ดูทุกหน้าเคียงกัน / เปิดทีละหน้า
                                  ↓
             ปรับธีม/สี/section → ตรวจ build และเส้นทาง
                                  ↓
                   เข้า workspace / เผยแพร่ด้วยตัวเอง
```

ไม่มี auto-publish และไม่มีการสั่งซื้อแพ็กเกจ/หักเงินจากการกรอกงบหรือเลือกประเภทเว็บ

## 3. สมัครสมาชิกและข้อมูลบริษัท

คง name/email/password/password confirmation และพฤติกรรม verification เดิม เพิ่ม Company details ในหน้าเดียวกัน ไม่สร้างบัญชีหลายครั้งระหว่าง steps

ข้อมูลใหม่ที่เสนอ:

- company name: optional, max255; บุคคล/portfolio ไม่ต้องแต่งชื่อบริษัท
- business description: optional, max2000; ระบุทำธุรกิจอะไรและต้องการเว็บเพื่ออะไร
- website budget amount: optional, non-negative decimal; เว้นว่างได้หากยังไม่กำหนด
- currency: THB เป็นค่าเริ่มต้น; USD เป็นตัวเลือก ไม่แปลงค่าเงินอัตโนมัติ

ข้อมูลบริษัทเก็บใน profile ของบัญชี ส่วนรายละเอียดเว็บจริงเป็นของ project และแก้ทับค่าเริ่มต้นได้ ไม่มี migration บังคับกรอกย้อนหลังสำหรับสมาชิกเดิม

งบนี้เป็น “งบทำเว็บไซต์” ไม่ใช่ AI credit limit, subscription price หรือ payment authorization ไม่ส่งงบเข้า AI/log/export โดยอัตโนมัติ การวางแผน AI ไม่ต้องใช้จำนวนเงินเพื่อสร้าง layout

รักษาข้อจำกัด signup/email uniqueness/CSRF/rate limit/access role/status เดิม บันทึก user+profile ให้สำเร็จร่วมกันหรือไม่สร้างค้าง ไม่เรียก provider ระหว่างสมัคร หาก validation ไม่ผ่าน ให้คงข้อความที่กรอกยกเว้น password

หลังยืนยันอีเมล บัญชีใหม่ไปต่อ flow สร้างเว็บจากสถานะ onboarding ที่ server บันทึกไว้ กลับมาเปิดลิงก์ verification/ล็อกอินใหม่ไม่ทำให้สมัครซ้ำหรือสร้าง project ซ้ำ ไม่ข้าม verified middleware และไม่ใช้ redirect URL ภายนอกจากฟอร์ม ผู้ใช้เดิมยังเข้า home/project เดิมได้ตามปกติ

## 4. ประเภทเว็บและวิธีเริ่ม

ประเภทเว็บเป็นภาษาของลูกค้า ไม่ใช่ runtime selector ผู้ใช้ไม่ต้องเลือก React/PHP เพื่อเริ่ม flow นี้ React/static เป็น runtime ที่รองรับในรุ่นแรก

| ประเภท | ชุดหน้าเริ่มต้นที่เสนอ ไม่ใช่ข้อบังคับ |
| --- | --- |
| Business site | Home, About, Services, Case studies, Contact |
| Ecommerce store | Home, Products, Product detail, Cart, Checkout |
| Portfolio | Home, Work, Project detail, About, Contact |
| Blog | Home, Articles, Article detail, About, Contact |
| Other | Home และหน้าที่อธิบายเพิ่ม |

เริ่มต้นเสนอได้ไม่เกิน5หน้าใน flow รุ่นแรก จำกัดเพิ่มเติมตาม entitlement จริง และเพิ่มต่อใน workspace ได้ตามขอบเขตที่รองรับ ห้ามนำ limit ของ Webflow มาแทนสิทธิ์ Krikkit

Ecommerce เป็นตัวเลือกโครงเว็บ ไม่ใช่การเปิด module รับเงินจริง ถ้า orders/payment ยังไม่พร้อม ต้องแสดงว่าเป็น storefront/demo และไม่สร้าง success receipt ปลอม Blog list/article layout ไม่เท่ากับมี CMS publishing backend

### AI site builder

รับ prompt ธุรกิจ/กลุ่มเป้าหมาย/เป้าหมายเว็บ/สไตล์/ภาษา แล้วขอ AI เสนอ summary และโครงสร้างที่ตรวจ schema ได้ ยังไม่เขียนไฟล์หรือแก้ฐานข้อมูลธุรกิจในขั้นนี้ แสดง “AI summary based on your prompt” พร้อมให้แก้และชื่อปุ่มที่บอกว่าขอแผน ไม่ใช่สร้างเว็บเสร็จแล้ว

### Template

แสดง template package ที่มี sections/routes/theme และ source ที่สร้างได้จริง ไม่ใช้ภาพ SVG เดิมเป็นหลักฐานว่ามี template พร้อมรัน เลือกแล้วเข้าสู่โครงสร้าง/ธีมเดียวกับทาง AI ไม่บังคับเรียก AI หรือใช้เครดิตเพื่อเปลี่ยนสี

เริ่มด้วย templates ที่เขียนเอง: business, portfolio, blog และ storefront แบบไม่ต่อ payment แต่ละรายการบอกสิ่งที่รวม/ไม่รวมและสถานะ build ที่พิสูจน์ได้

## 5. Organize the structure

- คอลัมน์ซ้าย: ชื่อโปรเจกต์ สรุป prompt ประเภทเว็บ จำนวนหน้า และขั้นตอน
- พื้นที่หลัก: page cards แต่ละใบแสดง title/path และ sections เรียงลำดับ
- เพิ่ม/ลบ/เปลี่ยนชื่อหน้า ตั้ง URL และจัด navigation order ได้
- Home ต้องมีหนึ่งหน้าเสมอและ route `/`; ห้ามลบทิ้งจนไม่มี homepage
- Route ซ้ำ/สงวน/ไม่ถูกต้องมี validation ชัดเจน ไม่มี suffix หรือเปลี่ยน route เงียบหลังเจ้าของยืนยัน
- Sections มี stable IDs; เพิ่ม/ลบ/duplicate/reorder/เลือก layout variant ได้โดยไม่ทำ ID ของหน้าอื่นเปลี่ยน
- Header/footer เป็น shared sections อยู่ตำแหน่งที่กำหนด ไม่ลากไปกลางหน้าโดยไม่มีสัญญาใหม่
- เปลี่ยนชื่อ/path ของหน้าแสดงผลกระทบต่อ navigation/links ก่อนยืนยัน ใช้ references กลางแทนการแก้ด้วย regex ทั้ง source
- Edit prompt → regenerate structure ต้องเตือนว่าจะได้ข้อเสนอใหม่ ไม่เขียนทับสิ่งที่แก้เองทันที เก็บ revision เดิมและให้เลือก Apply/Cancel
- งบ/ข้อมูลบริษัทไม่แสดงใน public page หรือแชร์ไปหน้า preview

## 6. แกนข้อมูลและการสร้างหน้า

แยก business profile, website draft, approved blueprint และ generated artifact ออกจากกัน ผล AI ไม่ใช่ source of truth จนเจ้าของยืนยัน

Blueprint ที่เสนอมี schema version, revision, project binding, kind, start mode, prompt/summary, pages, shared layout, theme, assets และ requested modules

Page มี ID/title/route/navigation และรายการ section IDs; Section มี type/variant/content/asset references/optional module binding; Theme มี colors, font roles, spacing scale, button/radius และ motion preference ที่อยู่ใน allowlist

เก็บ draft ตามบัญชีและผูก project เมื่อสร้างจริง ไม่ใช้ localStorage key กลางข้ามบัญชีเป็นแหล่งข้อมูลหลัก กลับมาแก้ต่อได้หลัง reload และไม่ใช้ชื่อ company เป็น tenant identity

เส้นทางแปลง blueprint → preview และ blueprint → source/export ใช้ renderer/component catalog ชุดเดียวกัน เพื่อลดปัญหา preview สวยแต่ source ที่ได้ไม่ตรง คง source manifest และ revision/hash ไว้ตรวจความสอดคล้อง

AI เสนอเฉพาะ JSON ตาม schema และใช้เฉพาะ section types/variants ที่รองรับ เนื้อหา render แบบ escaped ไม่ใช้ arbitrary HTML/CSS/JS หรือ eval จากคำตอบ AI; source mode ขั้นสูงยังอยู่ใน workspace เดิมและไม่อนุญาต PHP execution

ดังนั้น AI ใน flow ใหม่นี้เลือก composition/variant และสร้างเนื้อหา แล้วตัวสร้าง source แปลงเป็น React/static ที่ตรวจได้ ไม่ใช่ปล่อย AI เขียนโค้ดอิสระแล้วรับประกันว่าจะย้อนกลับเป็น section editor ได้ทุกรูปแบบ การขยาย section catalog ทำเป็นรุ่นใหม่โดยรักษาแบบร่างเก่า

เริ่มจาก structured managed pages ไม่พยายาม parse React/HTML ทุกรูปแบบกลับเป็น canvas หากเจ้าของแก้ generated managed source นอกสัญญา ให้แสดงว่าไม่ sync/ต้องตรวจ ไม่เขียนทับ source เพื่อทำให้ canvas ผ่าน

## 7. Generate และ All pages canvas

- สร้าง shared theme/navigation/components ก่อน แล้วสร้างแต่ละหน้าตาม approved blueprint
- Progress เป็นรายหน้า: waiting/generating/checking/ready/failed ไม่อ้าง5หน้าพร้อมจากหน้าแรกผ่านเพียงหน้าเดียว
- Retry หน้าที่ล้มเหลวได้ ไม่เริ่มต้นใหม่ทุกหน้าหรือทิ้งสิ่งที่แก้แล้ว; ใช้ revision/idempotency ป้องกัน submit/retry ซ้ำ
- ข้อมูลตัวอย่างระบุว่า fictional/demo ไม่แต่งลูกค้าจริง รีวิว ยอดเงิน หรือหลักฐานชำระ
- All pages view เรียงหน้าเคียงกันตามภาพ มีชื่อหน้า zoom/fit และเลือกเปิดทีละหน้า
- มือถือใช้ page selector/การ์ดแนวตั้ง ไม่ย่อ5หน้าให้เล็กจนแตะไม่ได้
- Preview cards ต้องมาจาก blueprint/render ที่บันทึกไว้ ไม่ใช่ stock screenshot หรือภาพ AI แทนหน้าเว็บจริง
- โหมด overview ไม่เปิด interactive frames จำนวนมากพร้อมกัน; render lightweight trusted previews และโหลด assets ใต้จอแบบ lazy
- เลือกหน้าแล้วมี sidebar ธีม และแถบ section actions: edit/add/replace/move/delete

## 8. ธีม สี และการสลับ template

เปลี่ยน palette/typography/spacing/radius/button style ผ่าน typed design tokens กลาง อัปเดตทุกหน้าและ export ด้วย revision เดียวกัน ปรับสีไม่จำเป็นต้องเรียก AI

“เปลี่ยนธีม” ต่างจาก “เปลี่ยน template”: ธีมเปลี่ยนสไตล์แต่รักษาเนื้อหา/routes/section IDs ส่วน template อาจเปลี่ยนโครงสร้าง ต้องแสดง mapping/ส่วนที่ใช้ต่อไม่ได้และ preview diff ก่อน Apply ไม่ลบเนื้อหา custom/form bindings เงียบ ๆ

รุ่นแรกอนุญาต full template choice ก่อนสร้าง ส่วนหลังสร้างให้สลับได้เฉพาะ compatible layout variants; การเปลี่ยน template family ที่ mapping ไม่ครบให้ Save as new draft ไม่เขียนทับงานเดิม

Undo คืนเฉพาะ blueprint/source/design revision ไม่ย้อน submissions/orders/slips หรือค่าบริการ AI ที่ใช้แล้ว ไม่ใช้ snapshot ของ source ไปกู้ทับข้อมูลธุรกิจ

## 9. สิทธิ์และความปลอดภัย

- ทุก draft/blueprint/revision/action ใช้ server owner/project context; user/project UUID ที่ client ส่งไม่ใช่หลักฐานสิทธิ์
- Account A อ่านหรือแก้ของ B ไม่ได้; project A ของ owner เดียวกันไม่เข้าถึง bindings/project B โดยอัตโนมัติ
- คง quota/frozen/verified/entitlement checks; เลือก Ecommerce ไม่ขยายสิทธิ์ subscription หรือ payment module
- คำสั่ง AI เป็น proposal ไม่อนุมัติ apply/migration/publish เอง และไม่อ่าน provider secrets/customer records
- Local draft/canvas ที่ใช้ trusted components ทำได้ก่อน origin gate แต่การรัน generated/imported scripts ต้องผ่าน cookie-free preview origin/message validation ตาม Gate S ก่อน ห้ามถือว่า iframe หรือ cloned builder ให้ isolation มาแล้ว
- ไม่มีการย้าย PHP preview include ของ VoxelSite เข้ามาใน Laravel
- Flow นี้ไม่เพิ่ม runtime backend, checkout, generic CRUD หรือ Supabase permissions โดยอัตโนมัติ; ต่อกับ roadmap C/D/S เมื่อพร้อมจริง

## 10. พื้นที่โค้ดที่เกี่ยวข้อง

ของเดิมที่ต้องรักษา:

- Signup: `RegisterRequest`, `RegisteredUserController`, `resources/views/auth/register/register.blade.php`
- Project ownership/quota: `LabProject`, `LabProjectAccess`, `StudioProjectDesk`, existing entitlement services
- New-project UI: `LabApp`, `EmptyLabHero`, `LabComposerBar`
- Seed/gallery/wizard/library: `StarterGallery`, `GuidedProjectWizard`, `labStarters.js`, `labWizard.js`, `ThaiPromptLibrary`
- Build/orchestration: existing Lab source commit/build/preview flow ไม่สร้างระบบเผยแพร่ซ้ำ

ส่วนใหม่ที่ต้องออกแบบเป็น implementation plan ต่อ: business profile storage, versioned website drafts/blueprints, trusted section/template catalog, renderer/source generation, structure editor, multi-page overview, theme controls และ regression fixtures

ไม่ใส่ชื่อ endpoint/migration ที่ยังไม่ตรวจ dependency ว่าเป็นของที่มีแล้ว Contract/validation/HTTP tests ต้องกำหนดก่อน implementation ตาม Postman API engineering workflow และเก็บ local เท่านั้น ไม่สร้าง remote workspace จากงานนี้

## 11. ลำดับการตรวจรับที่เสนอ

1. Signup profile: สมัคร/validation/verification/ข้อมูลเก่าไม่เสีย; ไม่มี plaintext password/financial data ใน logs
2. New-project draft: ประเภท/AI-Template/back/resume; cross-account และ multi-project isolation
3. Structure editor: หน้า+section CRUD/order/route invariants/expected revision conflicts
4. AI planning: strict schema, malformed result, retry/cancel, proposalไม่เขียน source
5. Deterministic template path: เลือกtemplate→5หน้าจริง→navigation/deep routes→build โดยไม่ใช้ AI
6. AI generation path: approved5pages→sourceจริง→build→ตรวจทุกpage ไม่ใช่ภาพแทน
7. Canvas/theme: overview/one page/mobile, global tokens, per-section operations, undo และ exportตรงpreview

ต้องมี React/build/PHP regression ที่เกี่ยวข้อง และ browser proof375/768/1440 รวม keyboard/reduced motion/error states ไม่ใช้ build pass หรือ screenshot ตัวอย่างแทนการตรวจ customer artifact

## 12. Reference / licensing / ขอบเขตรอบนี้

รายละเอียด clone และ source mapping อยู่ใน `docs/platform/2026-10-16/voxelsite-onboarding-research.md`

ภาพผู้ใช้เป็น reference ของ Webflow; ไม่คัด assets/branding/customer proof ของเขา VoxelSite ใช้ AGPL-3.0 ต้องตรวจเงื่อนไขก่อนนำ source มาใช้ รอบนี้ใช้เฉพาะการอ่านแนวคิด ไม่คัดโค้ดเข้าผลิตภัณฑ์

ไม่มี deploy, migration จริง, installation ของ VoxelSite, provider call หรือแก้ registration/product source ในรอบออกแบบนี้ การยืนยัน flow ในแชตอนุมัติการทำเอกสารนี้; ขั้นถัดไปคือให้เจ้าของตรวจ spec แล้วจึงเขียน implementation plan และเลือกวิธีดำเนินงาน

## Self-review

ตรวจความสอดคล้องแล้ว: signup รวมบริษัทตามคำตอบเจ้าของ; สมาชิกเก่าไม่ถูกบังคับ migrate; AI/Template ใช้ blueprint+renderer เดียว; เปลี่ยน theme ไม่เท่ากับเปลี่ยนโครง template;5หน้าเป็นขอบเขตเสนอไม่ใช่ entitlement ใหม่; งาน commerce/runtime/isolation ไม่ถูกอ้างว่าเสร็จจาก flow นี้; spec ไม่ใช้ screenshot/SVG เป็นหลักฐาน runnable template

ไม่ commit เอกสารในรอบนี้ เพราะ checkout มีงานเดิมหลายส่วนและยังไม่มีคำสั่งให้จัด commit แยก เก็บไฟล์ไว้ให้ตรวจโดยไม่ stage/reset งานผู้ใช้
