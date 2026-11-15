# Template and system completeness audit

ตรวจวันที่ 7 พฤศจิกายน 2026 จาก source จริง, catalog, generated source และ regression tests

## ผลตรวจ

| พื้นที่ | สถานะ | หลักฐาน | ความหมาย |
|---|---|---|---|
| Template catalog | มี 4 แบบ / 5 หน้า each | `resources/builder/catalog.json` | โครงหน้าและ copy ตัวอย่างมีครบสำหรับ business, portfolio, blog และ ecommerce |
| Template theme | ยังไม่ครบ | `app/Lab/Builder/TemplateCatalog.php` | ทุก template ถูกสร้างด้วย `forest/sans/soft` แล้วค่อยให้เจ้าของเปลี่ยนทีหลัง ไม่มี theme identity ต่อ template |
| Template widgets | มี preview slots ครบ | `resources/builder/catalog.json` มี `[widget:form]`, `[widget:news]`, `[widget:cart]` รวม 4 จุด | Builder แสดงฟอร์ม/ข่าว/ตะกร้าได้ตั้งแต่เลือก template; การส่งข้อมูลจริงยังต้องติดตั้ง managed modules และผ่าน schema review |
| Builder preview | ทำงาน | `resources/js/builder/SitePage.jsx` | ฟอร์ม/ข่าว/สินค้าแสดงเป็น preview; input เป็น read-only และปุ่มไม่ส่งข้อมูล |
| Generated website | ทำงานเป็น static source + integration plan | `resources/builder/generated-app.jsx`, `app/Lab/Builder/BlueprintSource.php`, `src/krikkit-site/integration-plan.json` | source เริ่มจาก preview ที่ปลอดภัย และพกแผนโมดูล/route ที่ต้องติดตั้งต่ออย่างชัดเจน; runtime จะถูกเพิ่มผ่าน reviewed Modules desk ไม่ฝัง credential หรือ module UUID ลง template |
| Managed widgets | มีระบบแยกหลังสร้างโปรเจกต์ พร้อม route จาก template | `resources/js/lab/components/modules/ModulesDesk.jsx`, `routes/lab-modules.php` | หน้า Modules อ่าน integration plan และเลือก route ให้ปุ่ม embed อัตโนมัติ แต่ยังต้องเตรียม module → ตรวจ schema → Apply → embed widget ใน workspace |
| Commerce | backend/widget จริงมี local evidence | `StorefrontWidget.jsx`, quote/order/slip suites | ต้องใช้ catalog + orders + manual_payments ที่พร้อม schema; storefront template เองยังประกาศว่าไม่มี cart/checkout/payment |
| Custom data | ยังไม่ครบ | `docs/BACKLOG.md` D1–D4, `ModuleCatalog.php` มีเพียง key | ยังไม่มี stable entity schema, scoped CRUD, customer JWT identity หรือ typed SDK/AI tools |
| Production readiness | ปิดอยู่ | `config/lab-modules.php` | `business_writes_enabled=false`; live Supabase, RLS, Storage, origin, deployment และ independent review ยังไม่ผ่านครบ |

## Regression evidence

- `builder-blueprint.php`: 708 checks ผ่าน — schema/catalog/identity/route invariants และ template integration plans
- `builder-source.php`: 21 checks ผ่าน — generated source, escaping และ integration-plan artifact
- `builder-materialization.php`: 45 checks ผ่าน — owner-scoped project materialization และ idempotent source binding
- `builder-ai-planner.php`: 10 checks ผ่าน — offline proposal, schema validation และ no-side-effect boundary
- `builder-ai-edit.php`: 54 checks ผ่าน — page/section scope, quota, provider-compatible loopback และ failure paths
- `builder-source.test.mjs`: 2 tests ผ่าน — deep routes และ generated source preview
- `builder-client.test.mjs`: 15 tests ผ่าน — builder client state and proposal/editor behavior
- `node --test tests/regressions/*.test.mjs`: 44/44 ผ่าน
- `module-entry.mjs`: 4 checks ผ่าน — workspace module entry/panel boundary
- `storefront-quotes.mjs`: 9 checks ผ่าน — local quote/reprice/order recovery
- `storefront-revocation.mjs`: 48 checks ผ่าน — terminal denial/retry/stale response cleanup

## ข้อสรุป

ระบบมีโครง template, preview slots และระบบ managed modules ที่เชื่อมกันด้วย integration plan แล้ว แต่ยังไม่ใช่ “template พร้อมระบบครบในตัว” การเลือก template วันนี้สร้าง source และจุดเชื่อมต่อให้พร้อม จากนั้นเจ้าของต้องตั้งค่า module และ schema อย่าง explicit ใน workspace ระบบที่ผูกฐานข้อมูลและรับข้อมูลจริงยังไม่ควรประกาศว่าพร้อมใช้งาน production จนกว่า D1–D4 และ external gates ใน `docs/BACKLOG.md` จะผ่าน
