import os, glob, shutil, subprocess

theme_dir = "/usr/local/hestia/web/css/themes/custom"
js_dir = "/usr/local/hestia/web/js/custom_scripts"
img_dir = "/usr/local/hestia/web/images"

os.makedirs(theme_dir, exist_ok=True)
os.makedirs(js_dir, exist_ok=True)

# 1. Copy all MaxTheme dark & light minified CSS files and maxtheme.min.js
for f in glob.glob("/tmp/maxtheme/themes/css/dark/*.min.css") + glob.glob("/tmp/maxtheme/themes/css/light/*.min.css"):
    shutil.copy(f, os.path.join(theme_dir, os.path.basename(f)))

shutil.copy("/tmp/maxtheme/themes/js/maxtheme.min.js", os.path.join(js_dir, "maxtheme.min.js"))

# 2. Build createai-dark.min.css based on maxtheme-dark-purple.min.css + CreateAI glassmorphic overrides
base_purple = open("/tmp/maxtheme/themes/css/dark/maxtheme-dark-purple.min.css", "r", encoding="utf-8").read()

createai_extra = """
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Prompt:wght@300;400;500;600;700&display=swap');
:root {
    --cai-bg: #0d0e1a;
    --cai-surface: #141526;
    --cai-card: #1b1c33;
    --cai-purple: #673de6;
    --cai-pink: #ee5586;
}
body, html, input, select, textarea, button {
    font-family: 'Prompt', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
}
body {
    background-color: #0d0e1a !important;
    color: #f8fafc !important;
}
.app-header {
    background: linear-gradient(90deg, #0d0e1a 0%, #17153a 55%, #1e163b 100%) !important;
    border-bottom: 1px solid rgba(103, 61, 230, 0.35) !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.45) !important;
}
.top-bar {
    background: #111222 !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
}
.login {
    background: radial-gradient(circle at top right, rgba(103, 61, 230, 0.28), transparent 55%),
                radial-gradient(circle at bottom left, rgba(238, 85, 134, 0.22), transparent 55%),
                #0d0e1a !important;
    border: 1px solid rgba(103, 61, 230, 0.35) !important;
    border-radius: 16px !important;
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.55) !important;
}
.button, .ui-button {
    border-radius: 8px !important;
}
"""

open(os.path.join(theme_dir, "createai-dark.min.css"), "w", encoding="utf-8").write(base_purple + "\n" + createai_extra)

# 3. Backup original Hestia logos and install CreateAI SVG logos
for fn in ["logo.svg", "logo-header.svg"]:
    orig = os.path.join(img_dir, fn)
    bak = os.path.join(img_dir, fn + ".bak")
    if os.path.exists(orig) and not os.path.exists(bak):
        shutil.copy(orig, bak)

# Login logo (viewBox 0 0 160 144)
logo_login_svg = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 144" width="120" height="108">
  <defs>
    <linearGradient id="caiG" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#06b6d4"/>
      <stop offset="50%" stop-color="#673de6"/>
      <stop offset="100%" stop-color="#ee5586"/>
    </linearGradient>
    <linearGradient id="caiT" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#ee5586"/>
      <stop offset="100%" stop-color="#f59e0b"/>
    </linearGradient>
  </defs>
  <rect x="44" y="12" width="72" height="72" rx="20" fill="url(#caiG)"/>
  <path d="M80 26L85.5 42.5L102 48L85.5 53.5L80 70L74.5 53.5L58 48L74.5 42.5L80 26Z" fill="#ffffff"/>
  <circle cx="96" cy="32" r="4" fill="#fde047"/>
  <text x="80" y="114" text-anchor="middle" fill="#ffffff" font-family="'Inter', 'Prompt', Arial, sans-serif" font-size="26" font-weight="800" letter-spacing="-0.5">Create<tspan fill="url(#caiT)">AI</tspan></text>
  <text x="80" y="132" text-anchor="middle" fill="#94a3b8" font-family="'Inter', 'Prompt', Arial, sans-serif" font-size="8.5" font-weight="600" letter-spacing="1.5">CLOUD CONTROL PANEL</text>
</svg>"""

# Header logo (viewBox 0 0 155 38)
logo_header_svg = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 155 38" width="130" height="32">
  <defs>
    <linearGradient id="caiGH" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#06b6d4"/>
      <stop offset="50%" stop-color="#673de6"/>
      <stop offset="100%" stop-color="#ee5586"/>
    </linearGradient>
    <linearGradient id="caiTH" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#ee5586"/>
      <stop offset="100%" stop-color="#f59e0b"/>
    </linearGradient>
  </defs>
  <rect x="2" y="4" width="30" height="30" rx="8" fill="url(#caiGH)"/>
  <path d="M17 9.5L19.2 16.3L26 18.5L19.2 20.7L17 27.5L14.8 20.7L8 18.5L14.8 16.3L17 9.5Z" fill="#ffffff"/>
  <circle cx="23.5" cy="12" r="1.8" fill="#fde047"/>
  <text x="38" y="26" fill="#ffffff" font-family="'Inter', 'Prompt', Arial, sans-serif" font-size="21" font-weight="800" letter-spacing="-0.5">Create<tspan fill="url(#caiTH)">AI</tspan></text>
</svg>"""

open(os.path.join(img_dir, "logo.svg"), "w", encoding="utf-8").write(logo_login_svg)
open(os.path.join(img_dir, "logo-header.svg"), "w", encoding="utf-8").write(logo_header_svg)

# 4. Activate createai-dark theme and set APP_NAME in HestiaCP
subprocess.run(["/usr/local/hestia/bin/v-change-sys-theme", "createai-dark"], check=False)
subprocess.run(["/usr/local/hestia/bin/v-change-sys-config-value", "APP_NAME", "CreateAI Cloud Panel"], check=False)

print("INSTALLED_THEMES_COUNT:", len(os.listdir(theme_dir)))
print("HESTIA_THEME_SETUP_COMPLETE")
