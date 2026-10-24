<?php

// Public presentation only. This is not a tenant permission or release gate.
return [
    ['key' => 'websites', 'title' => 'สร้างหน้าเว็บและแก้ไขด้วย AI', 'status' => 'available', 'label' => 'ใช้งานได้', 'limitations' => 'เริ่มจาก prompt หรือตัวอย่าง ปรับหลายหน้าและดูตัวอย่าง React ได้ ผลลัพธ์ต้องตรวจและทดสอบก่อนเผยแพร่'],
    ['key' => 'forms', 'title' => 'ฟอร์มจองและข้อมูลจาก Supabase', 'status' => 'limited', 'label' => 'ต้องตั้งค่าเพิ่ม', 'limitations' => 'มีฟอร์มจองแบบจัดการได้ ต้องเชื่อมบัญชี Supabase และเตรียมฐานข้อมูลก่อนรับข้อมูลจริง ไม่ใช่ทุกฟอร์มที่ AI วาดจะบันทึกข้อมูลได้ทันที'],
    ['key' => 'github', 'title' => 'นำเข้าโค้ดและต่อยอดโปรเจกต์', 'status' => 'limited', 'label' => 'มีข้อจำกัด', 'limitations' => 'มีการเชื่อม GitHub และนำเข้า source แต่ต้องตรวจ stack และ build แยก ไม่รับประกันว่า repository ทุกแบบจะรันได้'],
    ['key' => 'commerce', 'title' => 'ตะกร้า ออเดอร์ PromptPay และสลิป', 'status' => 'planned', 'label' => 'อยู่ในแผนพัฒนา', 'limitations' => 'ยังไม่เปิดเป็นระบบร้านค้าที่ใช้งานจริง การมีหน้าร้านหรือตะกร้าตัวอย่างไม่เท่ากับระบบชำระเงิน'],
    ['key' => 'server-runtime', 'title' => 'รันระบบ backend ของลูกค้า', 'status' => 'planned', 'label' => 'ยังไม่เปิดใช้งาน', 'limitations' => 'PHP, Python และ Node server ยังไม่เปิดรันในระบบหลัก การนำเข้า source ไม่ได้ให้สิทธิ์รันโค้ดบนเซิร์ฟเวอร์'],
];
