# ส่งต่องานหน้าขายและตรวจ local ก่อนนำขึ้นเอง

อัปเดต 2026-10-16 — งานนี้ยังไม่ deploy ไม่ migrate และไม่เปลี่ยนข้อมูลธุรกิจ/CMS ที่ใช้อยู่

## เปิดแอป local จริง

```powershell
./scripts/serve-local.ps1
```

เปิด `http://localhost:8892/features` หรือ `http://localhost:8892/lab` ใช้การตั้งค่าและบัญชีเดิม ไม่ใช่ fixture สามารถเปลี่ยนพอร์ตด้วย `-Port` และเลือก PHP ด้วย `-Php` ตัวรัน bind เฉพาะ loopback และไม่รัน migration/AI generation ให้อัตโนมัติ

การตรวจรอบนี้พบ web bootstrap ของ PHP ที่เปิด OPcache ล้มเหลวใน dotenv/mbstring แต่ปิด OPcache เฉพาะ process แล้วผ่าน จึงใส่ development-only flags ในตัวรัน ไม่แก้ php.ini/vendor/.env และไม่ยกค่านี้ไปเป็น production recommendation ตัว server เดิม8889 ไม่ถูกปิดหรือเปลี่ยนค่า

ตรวจซ้ำเมื่อเปิดทั้งแอปจริงและ preview: `./tests/regressions/local-preview.ps1 -AppUrl http://localhost:8892` ตรวจเนื้อหา HTML ไม่อ้างเพียง HTTP200 เพราะหน้า PHP error อาจตอบ200ได้

## เปิดตรวจหน้าตาแบบไม่ใช้ข้อมูลลูกค้า

รันจาก project ใน PowerShell:

```powershell
./scripts/preview-landing.ps1
```

เปิด `http://127.0.0.1:8891/features` ดูหน้าขายโฉมใหม่ ภาษาไทย ตัวอย่างพร้อม prompt, ข้อดี ขั้นตอน ขอบเขตความสามารถ และลิงก์แพ็กเกจ หาก PHP อยู่ตำแหน่งอื่น ใช้ `-Php 'absolute/path/php.exe'`; เปลี่ยนพอร์ตด้วย `-Port 8893`

ตัวนี้เป็น **read-only presentation preview** ใช้ SQLite ในหน่วยความจำ ไม่อ่าน `.env` ไม่ล็อกอินบัญชีเดิม ไม่ใช่การทดสอบ AI/Supabase/รับเงินจริง ไม่ใช่เว็บสำหรับ deploy และ POST จะตอบ405 ปิดด้วย Ctrl+C มีป้ายบอกโหมด preview บนหน้า จงใช้ instance ปกติสำหรับทดสอบระบบจริง

## ใช้กับ instance ปกติ

- Guest `/` เห็นหน้าขาย; ผู้ที่ล็อกอินยังเข้า Studio เดิม
- `/features` ดูหน้าผลิตภัณฑ์ได้ทั้ง guest และผู้ที่ล็อกอิน โดยยังเคารพ maintenance
- ข้อความ CMS เดิมไม่ถูกเขียนทับจากการอัปเดตโค้ด
- Dashboard → Landing page → ตรวจชุดข้อความใหม่ → ยืนยันใช้ข้อความชุดใหม่ หากต้องการชุดภาษาไทย
- ยืนยันเฉพาะเมื่อเห็นด้วยว่าจะเปลี่ยนข้อความและลิงก์ทุกส่วน ภาพอัปโหลดเดิมยังอยู่ สำเนาข้อความก่อนหน้าเก็บใน `site_settings.payload.landing_previous` หนึ่งชุด ไม่มีการลบไฟล์ภาพ
- หากมีคนแก้ข้อความระหว่างเปิด proposal ให้เปิด proposal ใหม่ ไม่ฝืนเขียนทับ conflict
- Prompt ของตัวอย่างปรับได้ที่แท็บ Agents ช่อง Prompt ตัวอย่าง การกดตัวอย่างบนหน้าขายไม่ส่งคำสั่งไป AI และไม่ตัดเครดิต

## สิ่งที่เปลี่ยน

- หน้าขาย Modern SaaS พื้นขาวอมเทา/เขียวสด หัวเรื่องกลาง ภาพโต๊ะโปรเจกต์ขนาดใหญ่ แกลเลอรีเลือก prompt และ benefit grid โครง responsive และข้อความภาษาไทยเริ่มต้น
- ใช้ภาพ SVG จาก starter ในโปรเจกต์ ติดป้ายภาพแนวทาง ไม่ใช่ screenshot ของระบบลูกค้าที่รันจริง
- ไม่มี testimonial/statistic/ราคาแต่งขึ้น ฟีเจอร์ออเดอร์/PromptPay/slip แสดงว่าอยู่ในแผน
- ปรับเมนูมือถือ Escape/focus, FAQ aria และตัวเลือก preview; ลิงก์ใน CMS ไม่ render javascript/data/protocol-relative URL
- หลังบ้านมีการตรวจ/ยืนยันเปลี่ยนข้อความแบบตรวจ revision และเก็บสำเนาเดิม

## หลักฐานและข้อจำกัด

- Landing copy/HTTP/CMS suite: `tests/regressions/landing-copy.php` ผ่าน53 checks ใช้ SQLite memory เท่านั้น ต้องมีส่วนขยาย fileinfo สำหรับเส้นทางภาพอัปโหลด
- Browser ตรวจ 375/768/1440px: ภาพโหลด ลิงก์ anchor มีเป้าหมาย ไม่มี horizontal overflow; เมนู Escape, เลือก prompt และ FAQ ทำงานบน375px
- GET pricing/contact/login/register ใน preview ตอบ200; ไม่ได้ส่ง newsletter/register/contact หรือทดสอบการชำระเงินจริง
- ไม่ได้วัด Lighthouse จึงไม่อ้างคะแนน accessibility/performance
- เก็บการแสดงคำอ้างอิงและข้อมูลสรุปที่ผู้ดูแลกรอกไว้ (escaped) โดยไม่แสดงเฉพาะ testimonial ตัวอย่างเดิมที่แถมมาในชุดเริ่มต้น
- เปิดแอปจริง localhost:8892/features ผ่านและ browser ใช้บัญชีที่ล็อกอินอยู่เข้าหน้า Lab ได้ ตรวจ wizard ธุรกิจ→หน้า→ฟิลด์ เห็น5 entities/5 relationships ของ auto-repair ไม่ส่ง AI และไม่ Apply brief/เปลี่ยน draft ข้อมูลนี้ยังเป็น prompt/schema preview ไม่ใช่ฐานข้อมูลที่สร้างแล้ว
- ชุดทดสอบ local presentation/startup ผ่าน8 checks (รวม POST/PUT asset ถูกปฏิเสธ405); JavaScript15/15, landing53, runtime24, Hosting37 และ regression ฟอร์ม/บัญชีผ่านใน fixture ไม่ใช่การพิสูจน์ checkout หรือการรับเงินจริง
- ยังไม่ทดสอบ AI generation→build→publish แบบครบขั้นตอนจากบัญชีจริงในรอบนี้ และ final independent recheck ติดข้อจำกัดการใช้งานของ reviewer จึงยังไม่ปิด Gate L/งานทั้งหมด

## ก่อนนำขึ้นจริง

- [ ] ตรวจ CMS/โลโก้ ชื่อแบรนด์ ช่องทางติดต่อ ราคาและเงื่อนไขจริง
- [ ] Backup ฐานข้อมูลและไฟล์ก่อนเปลี่ยนระบบ
- [ ] รัน `npm run build` และ regression suites ด้วย PHP ที่โปรเจกต์รองรับ
- [ ] ทดสอบ instance ปกติ: login → เลือกตัวอย่าง → AI generation → build → preview → ฟอร์มฐานข้อมูล
- [ ] ปิด Hosting ใหม่ไว้จน Gate S/ZIP/origin isolation ผ่าน; backend PHP/Python/Node ไม่เปิดให้ลูกค้ารัน
- [ ] ไม่อัป `.env`, private key, `ref/`, `node_modules/`, `.agent-work/` หรือ local-preview scripts เป็นเว็บ public
- [ ] สคริปต์ preview และ fixture key ใช้เฉพาะอ่านหน้าตาในเครื่อง ห้ามใช้เป็น production router หรือ production APP_KEY
- [ ] ให้เจ้าของตรวจหน้า desktop/mobile จริงก่อนทำเครื่องหมาย Gate L ว่าเสร็จ
