# ตรวจงาน Hosting ที่ agent เพิ่ม และสถานะส่งต่องาน

วันที่ 2026-10-16 — ตรวจจาก source และทดสอบใน fixture ไม่ใช่ production audit

## สิ่งที่เพิ่มเข้ามา

มีหน้า Hosting ใน Lab, provider registry, deployment history, secret storage, regex scan, token model และ migrations ใหม่ ระบบนี้ใช้ตัวเผยแพร่ ZIP เดิม ไม่ได้เพิ่มเครื่อง cloud หรือระบบรัน Reflex/Python จริง

## ข้อบกพร่องที่แก้แล้วในรอบตรวจ

| ปัญหาเดิม | การแก้ |
| --- | --- |
| ใช้ `auth:sanctum` แต่ไม่มี dependency/guard | ใช้ web session เดิม พร้อม verified, CSRF และ throttle |
| อัปโหลดเข้า private disk แต่ deploy อ่านจากอีกตำแหน่ง | Resolve ผ่าน disk จริง และลบไฟล์ชั่วคราวทั้งสำเร็จ/ล้มเหลว |
| แก้ข้อมูลได้เมื่อโปรเจกต์ frozen | ใช้ writable owner check สำหรับ mutations |
| Local deploy ไม่ตรวจ custom domain verification | ตรวจ publication building และ domain verification ก่อนเขียนไฟล์ |
| เลือก canary/rolling/VM/hostname ได้แต่ไม่ได้ทำจริง | UI ไม่เสนอ และ provider ปฏิเสธค่าที่ไม่รองรับ |
| แสดง rollback แต่ provider ทำไม่ได้ | ไม่แสดง capability สำหรับ local และปฏิเสธก่อนสร้าง record |
| API สแกนส่ง `violations/failed` แต่ UI อ่านชื่ออื่น | แสดงรายการ จำนวน และ threshold จริง; error ไม่ใช่ผลสแกนสะอาด |
| ผลสแกนส่งบรรทัด source ที่อาจมี key | ไม่ส่ง snippet และไม่อ่าน symbolic links |
| Secret model serialize ค่าได้ | ซ่อน value รวมถึงตอน load relationship; ไม่ถอดรหัสส่งไป static hosting |
| Provider exception อาจส่งข้อมูลลับเข้าหน้าจอ/log | ใช้ข้อความสาธารณะคงที่สำหรับ failure ใหม่ |
| สร้าง token แต่ไม่มีระบบรับ token นั้น | ปิด issuance ด้วย501; token เดิมยัง list/revoke ตามบัญชีได้ |
| ยังไม่มี migrations แต่ endpoint ถูกเปิด | ค่าเริ่มต้นปิด Hosting; ตรวจตารางก่อนใช้และตอบ503 เมื่อยังไม่พร้อม |
| Middleware ตัดช่องว่าง/บรรทัดหัวท้ายของ secret | ยกเว้น TrimStrings เฉพาะ PUT secrets; ทดสอบผ่าน HTTP ว่า byte ตรงเดิม |
| พิมพ์รายการใหม่ระหว่าง Save แล้วถูกล้างทิ้ง | ปิดช่องแก้ไข/เพิ่ม/ลบชั่วคราวจนบันทึกเสร็จ |

## ขอบเขตที่ทดสอบ

- SQLite ในหน่วยความจำและ storage ชั่วคราวเฉพาะ test; ไม่ใช้ข้อมูลลูกค้า
- HTTP kernel/middleware: guest, verified web user, valid/missing CSRF, other owner, frozen mutation
- Private secret serialization, encrypted storage roundtrip, upload path/cleanup, failure redaction
- Static artifact happy path และ custom-host/runtime/options rejection
- React render จากรูปแบบผล API จริง ทั้งมี findings และไม่มี findings
- หลังแก้ review: Hosting 37 checks, Node ทั้งชุด 15/15 และ build React exit 0; ไม่ใช่ผล browser/production
- ผู้ตรวจอิสระตรวจซ้ำแล้วไม่พบประเด็นค้างในขอบเขตการซ่อมรอบนี้ ไม่ใช่การอนุมัติให้เปิด Hosting จริง
- ผล build และ independent review ดู implementation note; อย่าถือ lint หรือข้อความ agent ว่าเป็นผล production

## ยังห้ามกล่าวว่าเสร็จพร้อมให้ลูกค้าใช้

1. `HOSTING_ENABLED` ปิดไว้เป็นค่าเริ่มต้น ไม่ได้เปิดหรือ migrate ฐานข้อมูลจริงในรอบนี้
2. ต้องทดสอบ cookie-free preview/published origins และ message bridge ตาม Gate S; session/CSRF อย่างเดียวไม่กันโค้ด generated ที่อยู่ same-origin
3. ตัว ZIP deployer เดิมยังต้อง harden: entry allowlist, symlink/drive paths, expanded-byte limits, atomic live replacement และ failure recovery
4. Deployment version allocation ยังไม่มี project-level serialization/unique constraint; ห้ามอ้าง concurrency-safe หรือ zero-downtime
5. ยังไม่ได้เชื่อม browser build artifact เข้า Hosting แบบอัตโนมัติ ให้ใช้ Publish เดิม; การอัปโหลด ZIP ไม่ใช่ server build
6. Local rollback/start/stop ไม่รองรับ; ไม่มี archive retention/cleanup schedule หรือ live health proof; status/log fallback ของ provider ยังเป็น synchronous metadata ไม่ใช่ monitoring
7. Scanner เป็น pattern hints ไม่ตรวจ dependencies/authorization/data-flow และไม่ใช่ deploy gate; ต้องเพิ่ม resource budgets ก่อนเปิดกับ source ใหญ่
8. Secret store ยังใช้ encoding เดิม (manager encryption + model cast); ไม่มีการย้ายข้อมูลหรือส่ง secrets ไป backend runtime; error/log เก่าที่อาจมีข้อมูลลับไม่ได้ถูกลบอัตโนมัติ
9. Token bearer auth/ability enforcement ยังไม่มี ไม่ควรเพิ่ม bypass เพื่อให้ token ใช้ได้
10. งาน cart → order → PromptPay → private slip → ร้านตรวจ และหน้าแรกตาม roadmap ยังไม่ถือว่าทำเสร็จจากการเพิ่ม Hosting

## ลำดับถัดไปสำหรับ agent

1. ทำ Gate S origin isolation และ artifact safety ก่อนเปิด Hosting
2. ออกแบบ history/version locking และ publication lifecycle ให้ตรงกับ Publish เดิม ไม่สร้างสอง source of truth
3. ทดสอบ browser เจ้าของ/บัญชีอื่นจริง แล้วจึงเสนอ migrate/เปิด feature ใน environment ที่กำหนด
4. กลับไปเดิน roadmap: module permissions, commerce, interactive data, GitHub staging ตาม dependency

ห้ามรัน deploy scripts หรือใช้ private key ใน workspace เพียงเพราะพบไฟล์ และห้ามนำ credential ใส่เอกสาร/ZIP/commit
