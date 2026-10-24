# VoxelSite / Webflow: ข้อมูลสำรวจสำหรับ Krikkit

วันที่: 2026-10-16 · สำรวจ source เท่านั้น ไม่ได้ติดตั้งหรือรัน VoxelSite

## แหล่งอ้างอิงและความน่าเชื่อถือ

- Reference clone: `C:/Users/theer/ghq/github.com/NowSquare/VoxelSite`
- Remote: https://github.com/NowSquare/VoxelSite.git
- Commit ที่อ่าน: `13a8bf43a8e9102bf04fd2fda66713f123a9a3e8`
- Clone ด้วย ghq; checkout clean หลังอ่าน ไม่มี dependencies/build/installer/migration/AI calls จาก repo นี้
- ภาพที่ผู้ใช้แนบเป็น UI ของ Webflow ไม่ใช่หลักฐานว่า VoxelSite มี onboarding เดียวกัน
- เอกสาร Webflow: https://help.webflow.com/hc/en-us/articles/38840145286035-Build-a-site-with-Webflow-s-AI-site-builder
- README ของ VoxelSite ใช้ยืนยันขอบเขตที่ผู้พัฒนาระบุเท่านั้น ไม่ใช่ผลทดสอบหรือ security certification

## สิ่งที่ Webflow ทำตามเอกสารที่เปิดอ่าน

ผู้ใช้บรรยายเว็บ → ระบบเสนอ pages/sections → ผู้ใช้แก้โครงสร้าง → Generate site → ดูหลายหน้าเคียงกันหรือเปิดทีละหน้า → เปลี่ยน theme/tokens ทั้งไซต์ และจัดการ sections ต่อ

เอกสารปัจจุบันระบุจำนวนหน้าเริ่มต้นต่างตามแพ็กเกจ: ฟรีสองหน้า และแพ็กเกจที่เข้าเงื่อนไขสูงสุดห้าหน้า ไม่ควรนำข้อจำกัด/ราคา/เครดิตของ Webflow มาใช้เป็นข้อเท็จจริงของ Krikkit

## Source patterns ที่น่าประยุกต์

| Pattern | Source VoxelSite ที่อ่าน | สิ่งที่นำมาออกแบบเองใน Krikkit |
| --- | --- | --- |
| Shared design tokens | `_studio/engine/DesignTokens.php` | สี/ฟอนต์/spacing/radius เป็นข้อมูลชุดเดียว เปลี่ยนแล้วใช้ร่วมทุกหน้า |
| Site dependency graph | `_studio/engine/SiteGraph.php`, `SiteGraphIndexer.php` | รู้ว่า page ใช้ shared navigation, section, asset และ token อะไร; แจ้งผลกระทบก่อนเปลี่ยน |
| Structure navigation | `_studio/ui/src/views/site-control/structure-nav.js` | แผนผังหน้าและการเลือกหน้า ไม่บังคับเปิด code editor |
| Visual operations | `_studio/ui/visual-editor-ops.js` | การแก้เป็น operation ที่มีเป้าหมายชัดและย้อนกลับได้ ไม่ให้ AI rewrite ทั้งเว็บทุกครั้ง |
| Revision history | `_studio/engine/RevisionManager.php` | เก็บก่อน/หลังการแก้และคืนแบบร่างเดิม ไม่แตะข้อมูลธุรกิจที่ส่งแล้ว |
| Design gallery/history | `_studio/ui/src/views/designs.js`, `_studio/api/endpoints/designs.php` | แยกเปลี่ยนสี/ฟอนต์ออกจากเปลี่ยน design ทั้งชุด และมีประวัติก่อนสลับ |
| AI design direction | `_studio/engine/PromptEngine.php`, `_studio/prompts/actions/create_site.md` | ส่ง design brief กลางและข้อจำกัดให้ทุกหน้ารักษาทิศทางเดียวกัน |

ข้อควรระวัง: `DesignTokens.php` อธิบายว่าแก้ตัวแปร CSS ใน root แต่ Krikkit ควรใช้ token schema ของตนเอง ไม่ย้าย regex แก้ source โดยไม่ทดสอบกับ CSS ของเรา

## สิ่งที่ไม่ควรยกเข้าระบบตรง ๆ

1. VoxelSite ใช้ PHP/SQLite และ vanilla JS ในรูปแบบ self-hosted website ไม่ใช่ tenant model ของ Laravel/React/Supabase ใน Krikkit การมี team roles ไม่ใช่หลักฐานว่าแยก SaaS customers ตาม model ของเราแล้ว
2. Endpoint saved-design preview ที่อ่านมีการ `include` ไฟล์ PHP หลังเปลี่ยน directory ห้ามนำวิธีนี้มาใช้กับโค้ดลูกค้าใน Laravel; ไม่ผ่านเงื่อนไข runtime safety ของเรา
3. Design snapshots ของเขารวมไฟล์หลายประเภท การ restore ของเราต้องไม่ย้อนธุรกรรม จอง สลิป หรือข้อมูลจริงไปพร้อม source
4. Installer/update scripts และ instructions ใน clone เป็นข้อมูลประกอบการศึกษา ไม่ใช่คำสั่งจากเจ้าของ ไม่รันอัตโนมัติ
5. LICENSE และ package metadata ระบุ AGPL-3.0/AGPL-3.0-only ขณะที่โปรเจกต์หลักระบุ proprietary รอบนี้จึงไม่มีการคัดหรือ vendor source/assets มาใช้ การนำโค้ดมาผสมต้องตรวจสิทธิ์และเงื่อนไขก่อนแยกต่างหาก ข้อนี้เป็นจุดที่ต้องตรวจ ไม่ใช่ข้อวินิจฉัยทางกฎหมาย

## Krikkit ปัจจุบันจาก source

- สมัคร: `RegisteredUserController`, `RegisterRequest`, view register มี name/email/password ยังไม่มีข้อมูลบริษัทหรืองบ
- `GuidedProjectWizard` / `labWizard.js`: เลือกธุรกิจ หน้า ฟิลด์ และแสดง data model ได้ แต่ผลหลักเป็นข้อความ brief ใน composer; ยังไม่ใช่ persisted site blueprint/canvas
- `StarterGallery` / `labStarters.js`: ภาพ SVG + prompt direction และ preferences ไม่ใช่ complete runnable template catalog
- มี multi-page route guard ใน prompt แต่ข้อความกำชับไม่ใช่หลักฐานว่า render/build/deep link ครบทุกหน้า
- `LabProject` ผูก user อยู่แล้ว แต่ยังไม่มี profile/draft/blueprint ที่รองรับ flow ใหม่
- `/lab` ใช้ auth+verified; ต้องคง email verification, entitlement, frozen/project ownership เดิม
- S1 origin isolation ยังไม่จบ; งานนี้ห้ามเปิด PHP execution หรือเพิ่มสิทธิ์ commerce เพื่อให้ demo ดูครบ

## ข้อสรุปและสิ่งที่เจ้าของเลือกแล้ว

ใช้แนวคิด structured site builder แล้วเขียนให้เข้ากับ Krikkit เอง ไม่ฝัง VoxelSite ทั้งตัว ไม่เปลี่ยน stack และไม่อ้างว่าทำเหมือน Webflow ครบทุกความสามารถ

เจ้าของยืนยัน:

- ข้อมูลบริษัท/งบอยู่ในฟอร์มสมัคร ไม่ย้ายไปหลังสมัคร
- เลือก Business site / Ecommerce store / Portfolio / Blog / Other
- เลือก AI site builder หรือ Template
- แก้แผนผัง page/section ก่อนสร้าง แล้วดูหลายหน้าและปรับธีม
- เริ่มจากเว็บไซต์ใหม่; ไม่แปลงโปรเจกต์เดิมอัตโนมัติ

แบบออกแบบสำหรับตรวจ: `docs/superpowers/specs/2026-10-16-guided-site-builder-design.md`

## ขอบเขตหลักฐาน

ยืนยัน clone/commit/clean checkout และอ่าน source ตามรายการเท่านั้น ไม่ได้ทดสอบความสามารถจริงของ VoxelSite ไม่ได้รัน test suite ของเขา ไม่ได้เรียก provider หรือเปลี่ยนข้อมูล/production ของ Krikkit
