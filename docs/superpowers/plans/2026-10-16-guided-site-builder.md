# Guided Site Builder Delivery Index

Spec approved:2026-10-16 · Execution plans require review before code changes

Spec: `../specs/2026-10-16-guided-site-builder-design.md`

แบ่งเป็นแผนที่ส่งมอบทดสอบแยกกันได้ ไม่ยกทั้ง registration/canvas/compiler/AI เป็นงานก้อนเดียว:

1. **Signup / company profile**: [แผนลงมือ](2026-10-16-guided-builder-01-signup.md) — เพิ่มข้อมูลบริษัทและงบในสมัคร เก็บแบบ atomic รักษาสมาชิกเดิม ยังไม่ redirect ไป flow ที่ยังไม่มี
2. **Structured builder / Template path**: แผนถัดไปหลังรับช่วงแรก — persisted owner-scoped drafts,5ประเภท, AI/Template chooser, page/section schema+operations, original template catalog, trusted renderer, multi-page overview, theme controls, resume/revision/undo และ source/export ที่ตรงpreview โดยยังไม่เรียก AI
3. **AI planning / generation / integration**: แผนถัดไปหลังแกน renderer ผ่าน — strict proposal schema, owner Apply, malformed/error/retry handling, generationรายหน้า, build/deep-route verification, onboarding continuation และ workspace handoff;ไม่เปิด generated-script execution ก่อนGateS

Part1 ครอบคลุม spec§3 เฉพาะ signup/storage/validation; continuation redirect ไป builder อยู่Part3หลังมีปลายทางจริง Part2ครอบคลุม§4–8ฝั่ง deterministic;Part3ครอบคลุมAIใน§4/6/7 และการต่อflowครบ ส่วน§9/10/11เป็นข้อกำหนดข้ามทุกpart

ไม่เปิด commerce/payment/backend runtime จากการเลือกประเภทเว็บ ไม่แปลงโปรเจกต์เก่าอัตโนมัติ ไม่copyVoxelSite ไม่deploy ไม่run migrationsกับฐานข้อมูลจริงโดยไม่ได้ตรวจenvironmentและขอบเขตอีกครั้ง

สถานะ 2026-10-16: Part1 implemented and independently accepted, including approved local-only schema rollout, build/cache checks and responsive signup QA. Evidence: `Support/GUIDED-BUILDER-SIGNUP.md`. Part2/3 still require detailed implementation plans and review; website-type selection, structure editor, AI/Template generation and canvas are not delivered by Part1.

อัปเดต 2026-10-17: [Part 2A saved template drafts](2026-10-16-guided-builder-02a-drafts.md) implemented; actual authenticated local Chrome verified chooser/template → edit content/theme/pages → save → reload/resume, and two-tab stale revision rejection. Evidence: `Support/GUIDED-BUILDER-DRAFTS.md` and `.agent-work/qa/2026-10-17-builder-flow.md`; API regression 462 checks pass. This is a design-draft editor. Signup continuation into builder, AI planning/generation, Part 2B source/project materialization/export/build/publish and persisted undo are still incomplete; do not describe the full flow above as delivered.
