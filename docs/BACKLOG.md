# งานค้างและเงื่อนไขปิดงาน

ตรวจจากโค้ดและแผน ณ 25 ตุลาคม 2026 ไม่ใช้ checkbox เก่าเป็นหลักฐานว่า implementation ยังไม่มีหรือเสร็จแล้ว

| งาน | ทำแล้วใน local | สิ่งที่ยังต้องเก็บ |
|---|---|---|
| Guided entry / Builder | สมัคร/login/verify กลับ Builder, draft/revision/source/materialization, ลิงก์ตั้งค่า widget; แก้ AI edit provider/เครดิต/ขอบเขต54ข้อ actual HTTPจำลองผ่าน | Browser AI edit รุ่นล่าสุดและ live vendor; initial AI planner จาก brief ยังเป็นอีกส่วน ไม่เท่ากับ AI edit หลังเลือก template |
| S1 runtime isolation | ปิด tenant PHP บนโฮสต์, dedicated guest origin/bridge และ regression; มี browser proof เดิม | ตรวจ deployment config จริงและ browser หลังเปลี่ยนล่าสุด; ห้ามใช้ host build แทน guest isolation proof |
| S2–S4 module foundation | Registry/policy/quotas/proposals/leases และ owner APIs ทดสอบแล้ว; S3 Data desk/AI survey ผูกโปรเจกต์และปิดทาง generic account-wide rows/catalog/Auth/users/restart แล้ว PHP44/React-tool14 ผ่าน; legacy booking ใช้ desk เดิม | Capability-aware tool dispatch ครบทุกเครื่องมือ, real resource/network limits และ final isolation acceptance; external schema/customer identity รอ reviewed mapping ใน D1–D4; admission lease ไม่ใช่ OS sandbox |
| Landing L1–L3 | หน้าแรก/CMS/CTA/readiness copy; HTTP53; browserรุ่นปัจจุบัน desktop1440/mobile390 ตรวจ toggle/เมนู/Escape/FAQ/Tab และไม่มี document overflow | accessibility ครบทุกสถานะ/หน้า, performance และ final independent acceptance |
| Commerce C1–C5 | มีโค้ดและ local tests; browser รุ่น quote/private ผ่านสร้างออร์เดอร์, private viewer, reject/resubmit/accept/reload paid; owner Contact desktop/mobileและข่าว lifecycleผ่าน; actual cart/order reload, Back→checkoutว่าง/Forward→order และ revoke→ไม่มีQR/upload→reloadผ่าน หลังแก้ stale snapshot มีReact48/ชุดเดิมและbuild/artifactผ่าน | มือถือ/keyboard/failure matrix เพิ่มเติม, independent review, Supabase Storage/RLS จริงหลัง owner-reviewed Apply, recipient/retention/recovery acceptance |
| Historical slips | Owner-only normalized PNG viewer, ไม่เปลี่ยน hash/review/paid state | นโยบาย retention/recovery สำหรับ production; ไม่มีการย้ายหรือลบข้อมูลอัตโนมัติ งานย้าย Storage ไม่เป็นเงื่อนไขเปิดดูหลักฐานเก่าอีกแล้ว |
| D1–D4 interactive data | ยังไม่มีระบบ custom entity ตามแผน; booking/contact/news/commerce เป็น managed domains แยกกัน | Stable entity/field schemas และ approved additive planner, scoped record CRUD, customer JWT identity เมื่อต้องอ่าน/แก้ข้อมูลตนเอง, DataDesk/SDK/typed AI tools และ local/live/browser tests |
| G1–G3 GitHub | stage/backup/content-CAS/rollback25, ZIP24; เพิ่ม pin commit/tree/blob/hash/lock/binary completeness78, credential boundary62 และ controller promotion fence26 ผ่านบนข้อมูลสมมติ | ยังเหลือ persistent inspection/job/provenance, license/rights/runtime report, comprehensive reviewed secret inspection, human Apply diff/client dirty buffers, pull/remote-action approval/fencing, production resource bounds และ source/build/unsupported states |
| R1 Reflex patterns | ยืนยัน pinned source และทำ [mapping](decisions/2026-10-16-reflex-patterns.md) กับ typed actions/CAS/project state/compiler/private upload แล้ว; PostgreSQL และ React conflict/remount ทดสอบ local ผ่าน ไม่ได้คัดลอก framework codeหรือเปิด Python | D1–D4 ยังต้องพิสูจน์เมื่อ implement; production concurrency/live Storage และ final review แยกจาก conceptual mapping |
| R2 Python probe | ปิดอยู่ เป็น optional scope ตามแผน | ต้องแยก spec/approved isolated runner และอนุมัติโครงสร้าง/ค่าใช้จ่ายก่อน; ไม่เปิด arbitrary host execution เพื่อปิด checklist |
| Optional hosting backend | Hosting safety regression37; core Lab publication ใช้ตารางเดิม | Apply named local migrations000001/000002 หลังตรวจว่าไม่มีตารางและตรวจ CREATE-only SQL แล้ว; ตารางครบและ migration pending0 ข้อ การตรวจ runtime/provider/deployment จริงยังเป็น acceptance แยก |
| Xerg | เคยดาวน์โหลด CLI0.35.2 ตามสิทธิ์ download/connect เท่านั้น | Pair ใหม่ให้ credential บันทึกสำเร็จ; persistent skill รอ reviewed40-character commit SHA ตามคำตอบเดิมของเจ้าของ ไม่มีสิทธิ์ upload runtime logs/audits |

## เงื่อนไขก่อนเปิดข้อมูลธุรกิจจริง

1. ตรวจ browser ครบทั้งลูกค้าและ owner รวมมือถือ, keyboard, ข้อผิดพลาด/ลองใหม่/สองแท็บ/refresh และ direct routes โดยไม่ฉีด state หรือใช้ cookie จากแพลตฟอร์มกับเว็บลูกค้า
2. ปิด independent review หลังแก้ findings และตรวจรุ่นสุดท้าย; reviewer เดิมติด usage limit จึงยังไม่มี final acceptance และไม่พยายามข้ามข้อจำกัด
3. Supabase target ยืนยันเป็น `bugiisgheikkduzlibvv`; เตรียม SQL5โมดูล/hash แล้ว แต่ local credentialยัง401และ refreshไม่สำเร็จ มี UI reconnect/retry/same-ref refresh ที่ทดสอบแล้ว ต้องจบ callbackและตรวจ schema metadata ก่อนสร้าง concrete proposal ให้เจ้าของตรวจ/กด Apply แล้วตรวจ RLS/Storage/private URL/roles/origin/network จริง ห้ามอ้าง Postman/PGlite เป็นหลักฐาน live
4. ยืนยันผู้รับเงิน, ขั้นตรวจยอดเข้าบัญชี, retention/recovery และ deployment isolation ก่อน deliberate activation

## สิ่งที่เก็บแล้วในรอบนี้

- แก้ registration continuation; กัน budget ไม่ให้เข้า Lab bootstrap และตรวจ profile ข้ามบัญชี
- เชื่อม Builder ไป Modules desk โดยไม่ติดตั้งหรือเปิดรับข้อมูลอัตโนมัติ
- ทดสอบ historical proof viewer, all-file source/VFS CAS, SDK ownership, deep commerce routes, artifact validation/last-good/unpublish และ ZIP/path aliases บน Windows
- ตรวจ MAMP MySQL loopback ไม่มี config cache; inspect CREATE-only SQL และ apply named core120000 สำหรับ leases/proposals จากนั้นตรวจและ apply named hosting000001/000002 สำเร็จ ตารางเป้าหมายครบ6ตารางและ migration pending0 ไม่มีการสร้าง deployment หรือ provision infra
- Build แพลตฟอร์มและ trusted generated site จริง; generated artifact ผ่านตัวตรวจ source/schema/SDK/byte hashes ของเซิร์ฟเวอร์ และ service HTTP เปิด4เส้นทาง ไม่ใช่ browser interaction proof
- ปรับ OpenAPI/collection เป็น v0.4 พร้อม source/embed/build-context/historical image และ lint ใน local ไม่ push workspace ภายนอก
- Browser checkout รุ่น quote/private สำเร็จใน fixture แยก; แก้ display mismatch แบบ Red→Green และ build ใหม่แล้ว ข่าวแบบร่างไม่แสดง public ในรอบปัจจุบัน ส่วน contact/publication/keyboard/back-forward ถูกหยุดก่อนตรวจจบจึงไม่อ้างว่าผ่าน
- Import failure-preservation ผ่าน filesystem/controller25ข้อ; ZIP adversarial/ordinary source24ข้อ รวมตรวจ raw header เพราะ libzip ตัดชื่อ NUL ไม่ได้เพิ่มสิทธิ์ GitHub, execute source หรือเปิด Gate G

รายการ D/G และ AI planner ยังไม่ได้ปิดเพียงเพราะ commerce ผ่าน local tests งานเหล่านี้มี dependency/gate ตาม [roadmap](superpowers/plans/2026-10-16-platform-roadmap.md) และต้องทำเป็น slice ต่อจากหลักฐานที่ผ่านจริง
