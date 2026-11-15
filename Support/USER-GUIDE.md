# คู่มือการใช้งาน Krikkit AI Website Builder

คู่มือนี้อธิบายการใช้งาน local version ตั้งแต่สมัครสมาชิกจนถึงสร้างเว็บไซต์และเตรียมระบบฟอร์ม/ร้านค้า ใช้ข้อมูลสมมติระหว่างทดสอบเสมอ การแสดง preview หรือ proposal ไม่ได้หมายความว่าฐานข้อมูลจริง, การชำระเงินจริง หรือระบบ production ถูกเปิดใช้งานแล้ว

## 1. เริ่มต้นใช้งาน

1. เปิด `http://localhost:8892/features` หรือ URL local ที่ทีมประกาศ
2. กด **สร้างเว็บไซต์** หรือ **เริ่มต้นใช้งาน**
3. สมัครด้วยชื่อ, email, password และข้อมูลบริษัท
4. เลือกประเภทธุรกิจ เช่น business site, ecommerce, portfolio, blog หรือ other
5. กรอกงบประมาณและรายละเอียดธุรกิจให้ AI เข้าใจขอบเขตงาน
6. ยืนยันอีเมล แล้วกลับไปยัง Builder ตามลิงก์ที่ระบบพาไป

หากเป็นสมาชิกเดิม ให้กด login แล้วเปิด Builder จาก Dashboard ได้ทันที ข้อมูลบริษัทจะผูกกับบัญชีของตนเองและไม่ถูกใช้ร่วมกับบัญชีอื่น

## 2. เลือกวิธีเริ่มต้น

ใน Builder มีสองทางเลือก:

- **AI site builder** — พิมพ์คำอธิบายภาษามนุษย์ ระบบสร้าง proposal ของโครงหน้า, section, theme และ widgets ที่ catalog รองรับ
- **Template** — เริ่มจากโครงที่เตรียมไว้ แล้วแก้ชื่อ, copy, สี, ฟอนต์, หน้าและ section เอง

การกดดูตัวอย่าง starter เป็นการเปิด preview ในเครื่อง ไม่ส่ง prompt และไม่ตัดเครดิต AI

## 3. เขียน prompt ให้ได้ผลดี

เขียนให้ครบ 5 ส่วน:

1. ธุรกิจทำอะไรและลูกค้าคือใคร
2. เป้าหมายเว็บไซต์ เช่น รับ lead, นัดหมาย, ขายสินค้า หรือเผยแพร่บทความ
3. หน้าที่ต้องการ เช่น หน้าแรก, บริการ, เกี่ยวกับเรา, ราคา, ติดต่อ
4. ข้อมูลที่ต้องเก็บ เช่น ชื่อ, โทรศัพท์, เวลา, จำนวนคน, สถานะการชำระ
5. โทนภาพและสี เช่น modern SaaS, minimal, dark, luxury หรือสีประจำแบรนด์

ตัวอย่าง:

```text
สร้างเว็บไซต์ศูนย์ซ่อมรถสำหรับลูกค้าในกรุงเทพฯ
ต้องการหน้าแรก บริการ นัดหมาย ประวัติการซ่อม และติดต่อเรา
ฟอร์มนัดหมายต้องเก็บชื่อ เบอร์โทร ทะเบียนรถ รุ่นรถ วันที่ต้องการเข้ารับบริการ
และอาการเสียเบื้องต้น ใช้โทน navy/green ดูน่าเชื่อถือและอ่านง่ายบนมือถือ
```

เขียนสิ่งที่ต้องการปรับในรอบต่อไปให้เฉพาะเจาะจง เช่น `เปลี่ยน hero ของหน้าแรก`, `เพิ่ม field ทะเบียนรถในฟอร์มนัดหมาย`, หรือ `เปลี่ยนปุ่ม CTA หน้า contact` แทนการส่งคำสั่งกว้าง ๆ ว่า “ทำให้ดีขึ้น”

## 4. ตรวจโครงสร้างก่อนสร้าง

หลังส่ง prompt ระบบจะแสดง summary และรายการหน้า ให้ตรวจ:

- จำนวนหน้าและ route
- หน้าที่เป็น entity เช่น customers, vehicles, appointments
- หน้าที่เป็น view เช่น overview, reports, settings
- field และชนิดข้อมูลของแต่ละหน้า
- relationship เช่น customer → vehicle หรือ vehicle → work order

หน้า **Data model preview** เป็นสัญญาให้ AI และ generated source ใช้ field เดียวกันระหว่าง form, table, detail, filter และ sample data ยังไม่ใช่ migration หรือฐานข้อมูลจริง

แก้หน้าได้โดยเพิ่ม, ลบ, เรียง, เปลี่ยนชื่อ หรือแก้ route ก่อนกด Save ถ้าไม่ต้องการเก็บข้อมูลจริง ให้ใช้ field ที่เป็น presentation-only และอย่าสั่งให้ระบบเปิด CRUD โดยไม่ผ่าน owner review

## 5. Theme และการแก้ draft

ใน editor สามารถเปลี่ยน:

- สีหลักและสีพื้น
- ฟอนต์และขนาดหัวข้อ
- ชื่อเว็บและข้อความปุ่ม
- section ของแต่ละหน้า
- ลำดับหน้าและ navigation

กด **Save draft** เพื่อสร้าง revision ใหม่ การ refresh หรือกลับมาเปิดภายหลังจะโหลด revision ล่าสุดของบัญชีเดิม

ถ้าเปิดสองแท็บจาก revision เดียวกันแล้วบันทึกพร้อมกัน แท็บที่ stale จะได้รับ conflict เพื่อป้องกันการเขียนทับ แก้โดยโหลด revision ล่าสุดแล้วตรวจ diff ก่อน Save ใหม่

## 6. AI edit

AI edit ใช้แก้ proposal ที่เลือกในขอบเขต page หรือ section:

1. เลือกหน้า/section ที่ต้องการ
2. พิมพ์คำสั่งสั้นและระบุผลลัพธ์ที่ต้องการ
3. ตรวจ proposal ก่อน Apply เข้า draft
4. Save เป็น revision เมื่อพอใจ

AI edit ไม่ควรเขียน module, schema, payment หรือข้อมูลธุรกิจทันที หาก provider ไม่พร้อม ระบบอาจใช้ local fallback หรือแสดง error ตาม contract ห้ามวาง API key ใน prompt, source หรือ generated customer site

## 7. สร้าง source และ preview ลูกค้า

เมื่อ draft ถูกตรวจแล้ว:

1. กดสร้าง project/source
2. รอ materialization เสร็จ
3. ตรวจหน้าแรกและ deep route เช่น `/about`, `/services`, `/contact`
4. เปิด preview บน desktop และ mobile
5. ทดสอบ navigation, refresh, back/forward และ empty/error state
6. จึงค่อยส่งให้ owner review ก่อน publish

การ build ของแพลตฟอร์มสำเร็จไม่ได้แปลว่า generated customer project ทุกตัวพร้อม production ต้อง build artifact ของ project นั้นและตรวจ deep route แยก

## 8. ฟอร์มจองและข้อมูลหลังบ้าน

สำหรับ booking form:

1. เปิด Workspace → Modules/Forms
2. เลือก booking template
3. แก้ label, เพิ่ม field, เรียง field แล้ว Save draft
4. ตรวจ active version และกด Activate เมื่อพร้อม
5. ฝัง form ใน route ที่เลือก
6. ส่งรายการจาก customer site
7. เปิด owner desk เพื่อตรวจ, ยืนยัน หรือยกเลิก

field หลักและ field ที่มี historical answers ห้ามเปลี่ยน type หรือลบทิ้ง ให้ archive แทน การแก้แบบ conversational ต้องได้ proposal ก่อน และ owner ต้อง Apply เอง

เวลานัดหมายให้ระบุ timezone ชัดเจน ระบบแสดง Bangkok time ใน owner desk แต่ควรเก็บค่ามาตรฐานตาม contract

## 9. Cart, PromptPay และสลิป

ใน module ร้านค้า:

1. เพิ่มสินค้าใน cart
2. ตรวจจำนวนและยอดจาก catalog ล่าสุด
3. สร้าง order/quote
4. หากราคาเปลี่ยน ให้ยืนยันราคาใหม่ก่อน checkout
5. แสดง PromptPay QR ที่สร้างจาก reference และยอดที่ server ยืนยัน
6. ลูกค้าอัปโหลดสลิป พร้อมชื่อ, email, เบอร์โทร และเวลาส่ง
7. Owner ตรวจสลิปจากหลังบ้าน
8. กด reject พร้อมเหตุผล หรือ approve ตามสถานะที่คาดไว้

การอัปโหลดสลิปควรเป็น private storage ไม่เปิด public URL และการแนบสลิปไม่ได้แปลว่าได้รับการชำระเงินแล้วจนกว่า owner จะตรวจยืนยัน ระบบนี้ยังไม่เชื่อมธนาคารหรือโอนเงินจริงโดยอัตโนมัติ

## 10. GitHub import

GitHub import ใช้สำหรับนำ source ที่ตรวจสอบแล้วเข้ามาเป็น project:

1. ระบุ repository และ commit/ref
2. ตรวจ inspection, license, lockfile และไฟล์ต้องสงสัย
3. ดู diff/impact ก่อน Apply
4. ยืนยันการ import ด้วย owner action
5. รอ source promotion และ build ใน sandbox ที่อนุญาต

ระบบต้อง pin commit เดียว, ปฏิเสธ path traversal/secret/symlink/ไฟล์เกิน quota และไม่ execute source ที่นำเข้าโดยอัตโนมัติ การ import จาก fixture เป็นข้อมูลสมมติ ไม่ใช่การยืนยัน GitHub OAuth หรือ rate limit production

## 11. เมื่อพบปัญหา

บันทึก URL, project UUID, revision, ขั้นตอนล่าสุด, เวลา, response status/body ที่ไม่เป็นความลับ และ screenshot เฉพาะหน้าจอที่ไม่มี token/PII จากนั้นแยกประเภท:

- หน้า/ปุ่มผิดหรือข้อมูลหาย: **FAIL**
- service fixture ไม่เปิด เช่น port 4900/4904/4905: **BLOCKED-FIXTURE**
- provider/Supabase จริงตอบ 401/timeout: **BLOCKED-EXTERNAL** จนกว่าจะยืนยัน credential และ callback
- build ผ่านแต่ generated route เสีย: **FAIL** ของ customer artifact
- build warning ที่ไม่กระทบ output: บันทึกเป็น warning ไม่ปิดบัง error อื่น

ชุดตรวจละเอียดอยู่ที่ [QA-TEST-MATRIX.md](../docs/QA-TEST-MATRIX.md) และขอบเขตฟอร์มจองอยู่ที่ [BOOKING-FORMS.md](BOOKING-FORMS.md)

## 12. ข้อมูลแยกตามบัญชี

แต่ละบัญชีควรเห็นเฉพาะ:

- project, draft, revision และ generated source ของตนเอง
- form, submission, order, payment attempt และ slip ของ project ตนเอง
- plan, credit bonus และ usage ledger ของตนเอง

การทดสอบ direct HTTP จะตรวจซ้ำด้วย UUID ของอีกบัญชี ไม่ใช่แค่ตรวจว่าปุ่มถูกซ่อน หากเป็นบัญชีที่มีสิทธิ์ `finance.browse` บัญชีนั้นถือเป็นผู้ดูแลการเงินของแพลตฟอร์มและจะเห็นภาพรวม invoices/credits ตามสิทธิ์ที่ได้รับ การให้สิทธิ์นี้ไม่ควรเปิดแก่ลูกค้าทั่วไป

## 13. ขอบเขตที่ยังไม่ควรสัญญากับลูกค้า

- custom entity ตอนนี้มี schema/metadata contract แต่ยังไม่ใช่ CRUD production เต็มรูปแบบ
- AI planner/editor เป็น proposal-first และยังไม่ Apply business schema อัตโนมัติ
- live Supabase, live payment, production domain, customer JWT และ final independent review ต้องผ่าน gate แยก
- local fixture, screenshot และ platform build ไม่ใช่หลักฐานว่า production พร้อมใช้งาน
