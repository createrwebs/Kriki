import re

with open(r"D:\PulseAI\front-page-new.php", "r", encoding="utf-8") as f:
    html = f.read()

# 1. Update Title and description
html = re.sub(
    r"<title>.*?</title>",
    "<title>CreateAI - ระบบสร้างเว็บไซต์ด้วย AI Prompt, เว็บโฮสติ้งความเร็วสูง & Cloud VPS</title>",
    html
)

# 2. Add AI Prompt button in the nav menu before pricing or at the start of nav
prompt_nav_item = '''
                <!-- Menu Item: AI Website Builder -->
                <li class="cai-nav-item">
                    <a href="/lab" class="cai-nav-link" style="background: linear-gradient(135deg, rgba(147, 51, 234, 0.25), rgba(238, 85, 134, 0.25)); border: 1px solid #a855f7; border-radius: 8px; padding: 6px 14px; color: #ffffff; font-weight: 600;">
                        <i class="fas fa-wand-magic-sparkles" style="color: #fdd700; margin-right: 4px;"></i> ระบบสร้างเว็บด้วย AI Prompt <span class="cai-nav-pill-badge badge-hot" style="margin-left: 6px;">NEW</span>
                    </a>
                </li>
'''

if '<ul class="cai-nav-menu">' in html:
    html = html.replace('<ul class="cai-nav-menu">', '<ul class="cai-nav-menu">' + prompt_nav_item, 1)

# 3. Enhance Hero Tag, Title, Subtitle, and Buttons
old_hero_tag = '<i class="fas fa-sparkles text-warning"></i> แพลตฟอร์ม AGENTIC AI & WEB CLOUD ชั้นนำ'
new_hero_tag = '<i class="fas fa-sparkles text-warning"></i> ระบบสำหรับสร้าง WEBSITE ด้วย AI PROMPT & WEB CLOUD ชั้นนำ'
html = html.replace(old_hero_tag, new_hero_tag)

old_hero_title = '''เปลี่ยนทุกไอเดียของคุณ <br>ให้กลายเป็นธุรกิจออนไลน์อัจฉริยะ <span class="gradient-text">ใน 60 วินาที</span>'''
new_hero_title = '''ระบบสร้าง Website อัจฉริยะด้วย <br><span class="gradient-text">AI Prompt ใน 60 วินาที</span>'''
html = html.replace(old_hero_title, new_hero_title)

old_hero_sub = '''แพลตฟอร์ม Agentic สำหรับการสร้าง ดำเนินการ และขยายธุรกิจออนไลน์ของคุณอย่างก้าวกระโดด รวมโฮสติ้งความเร็วสูงระดับ Enterprise, คลาวด์ VPS และตัวแทน AI Automation จัดการงานแทนคุณตลอด 24 ชั่วโมง'''
new_hero_sub = '''เพียงแค่พิมพ์ข้อความคำสั่ง (Prompt) ที่คุณต้องการ ระบบ AI จะสร้างเว็บไซต์ ดีไซน์หน้าเว็บ วางเลย์เอาต์ และเลือกรูปภาพให้ทันที พร้อมเว็บโฮสติ้งความเร็วสูงระดับ Enterprise, คลาวด์ VPS KVM และระบบบริหารจัดการครบวงจร'''
html = html.replace(old_hero_sub, new_hero_sub)

# Hero buttons
old_hero_btns = '''            <div class="cai-hero-btns">
                <a href="#pricing" class="cai-btn-primary cai-btn-large">
                    ดูแพ็กเกจโฮสติ้ง เริ่ม ฿29/ด. <i class="fas fa-arrow-right"></i>
                </a>
                <a href="#openclaw" class="cai-btn-secondary cai-btn-large">
                    <i class="fas fa-robot" style="color: #c084fc;"></i> สำรวจตัวแทน AI (OpenClaw)
                </a>
            </div>'''

new_hero_btns = '''            <div class="cai-hero-btns">
                <a href="/lab" class="cai-btn-primary cai-btn-large" style="background: linear-gradient(135deg, #a855f7 0%, #ee5586 100%); box-shadow: 0 4px 25px rgba(238, 85, 134, 0.5);">
                    <i class="fas fa-wand-magic-sparkles"></i> เริ่มต้นสร้างเว็บด้วย AI Prompt <i class="fas fa-arrow-right"></i>
                </a>
                <a href="#pricing" class="cai-btn-secondary cai-btn-large">
                    <i class="fas fa-server"></i> ดูแพ็กเกจเว็บโฮสติ้ง & VPS (เริ่ม ฿29/ด.)
                </a>
            </div>'''
html = html.replace(old_hero_btns, new_hero_btns)

# Write output
with open(r"d:\codea\krikkit-ai-website\deployment\wordpress-theme\front-page.php", "w", encoding="utf-8") as f:
    f.write(html)

print("front-page.php generated successfully!")
