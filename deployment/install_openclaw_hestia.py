#!/usr/bin/env python3
import os

v_install = r'''#!/bin/bash
# v-install-openclaw USER DOMAIN [OPENAI_KEY] [TELEGRAM_TOKEN]
USER="$1"
DOMAIN="$2"
OPENAI_KEY="$3"
TELEGRAM_TOKEN="$4"

if [ -z "$USER" ] || [ -z "$DOMAIN" ]; then
    echo "Error: USER and DOMAIN are required"
    exit 1
fi

HOMEDIR="/home/$USER"
WEBDIR="$HOMEDIR/web/$DOMAIN"
NODEAPP_DIR="$WEBDIR/nodeapp"

if [ ! -d "$WEBDIR" ]; then
    echo "Error: Domain directory $WEBDIR does not exist"
    exit 2
fi

# Ensure user has bash shell so hcpp-nodeapp PM2 runner works
/usr/local/hestia/bin/v-change-user-shell "$USER" bash >/dev/null 2>&1 || true

# First switch proxy template to NodeApp so hcpp-nodeapp creates nodeapp folder & allocates port
/usr/local/hestia/bin/v-change-web-domain-proxy-tpl "$USER" "$DOMAIN" "NodeApp" >/dev/null 2>&1 || true

mkdir -p "$NODEAPP_DIR/.restart"
mkdir -p "$HOMEDIR/.openclaw"

if [ ! -f "$NODEAPP_DIR/app.config.js" ]; then
    cp -f /usr/local/hestia/plugins/nodeapp/nodeapp/app.config.js "$NODEAPP_DIR/app.config.js"
fi
echo "24" > "$NODEAPP_DIR/.nvmrc"

# Generate or read existing gateway token
ENV_FILE="$NODEAPP_DIR/.openclaw-env.json"
TOKEN=""
if [ -f "$ENV_FILE" ]; then
    TOKEN=$(python3 -c "import json; d=json.load(open('$ENV_FILE')); print(d.get('OPENCLAW_GATEWAY_TOKEN',''))" 2>/dev/null)
fi
if [ -z "$TOKEN" ]; then
    TOKEN=$(openssl rand -hex 16)
fi

python3 - "$ENV_FILE" "$TOKEN" "$OPENAI_KEY" "$TELEGRAM_TOKEN" "$HOMEDIR/.openclaw" << 'PYEOF'
import sys, json, os
env_file, token, openai_key, tg_token, state_dir = sys.argv[1:6]
data = {}
if os.path.exists(env_file):
    try:
        with open(env_file, 'r') as f:
            data = json.load(f)
    except Exception:
        data = {}

data['OPENCLAW_GATEWAY_TOKEN'] = token
data['OPENCLAW_STATE_DIR'] = state_dir
if openai_key and openai_key != '__KEEP__':
    data['OPENAI_API_KEY'] = openai_key
if tg_token and tg_token != '__KEEP__':
    data['TELEGRAM_BOT_TOKEN'] = tg_token

with open(env_file, 'w') as f:
    json.dump(data, f, indent=2)

app_js = """const { spawn } = require('child_process');
const fs = require('fs');
const path = require('path');

let port = 3000;
for (let i = 0; i < process.argv.length; i++) {
    if (process.argv[i] === '-p' && process.argv[i + 1]) {
        port = parseInt(process.argv[i + 1], 10);
    }
}

let customEnv = {};
try {
    customEnv = JSON.parse(fs.readFileSync(path.join(__dirname, '.openclaw-env.json'), 'utf8'));
} catch (e) {}

// Locate Node 24 + OpenClaw binary inside /opt/nvm/versions/node
let nodeBin = process.execPath;
let openclawBin = path.join(path.dirname(nodeBin), 'openclaw');
if (!fs.existsSync(openclawBin)) {
    try {
        const dirs = fs.readdirSync('/opt/nvm/versions/node').filter(d => d.startsWith('v24.')).sort();
        if (dirs.length > 0) {
            const v24Dir = path.join('/opt/nvm/versions/node', dirs[dirs.length - 1], 'bin');
            nodeBin = path.join(v24Dir, 'node');
            openclawBin = path.join(v24Dir, 'openclaw');
        }
    } catch (e) {}
}

const env = Object.assign({}, process.env, customEnv, {
    PORT: String(port),
    OPENCLAW_GATEWAY_PORT: String(port),
    NODE_ENV: 'production',
    NODE_OPTIONS: '--max-old-space-size=384',
    PATH: path.dirname(nodeBin) + ':' + (process.env.PATH || '')
});

const token = env.OPENCLAW_GATEWAY_TOKEN || 'createai-openclaw';

const child = spawn(nodeBin, [
    openclawBin,
    'gateway',
    'run',
    '--allow-unconfigured',
    '--dev',
    '--ambient-channels',
    '--force',
    '--bind', 'loopback',
    '--port', String(port),
    '--token', token
], {
    stdio: 'inherit',
    env: env
});

child.on('exit', (code) => {
    process.exit(code || 0);
});
"""

app_js_path = os.path.join(os.path.dirname(env_file), 'app.js')
with open(app_js_path, 'w') as f:
    f.write(app_js)

pkg_path = os.path.join(os.path.dirname(env_file), 'package.json')
with open(pkg_path, 'w') as f:
    json.dump({
        "name": "openclaw-instance",
        "version": "1.0.0",
        "description": "OpenClaw Personal AI Assistant managed by CreateAI",
        "main": "app.js",
        "scripts": {
            "start": "node app.js"
        }
    }, f, indent=2)
PYEOF

chown -R "$USER:$USER" "$NODEAPP_DIR" "$HOMEDIR/.openclaw"
chmod 600 "$ENV_FILE"

# Rebuild web domain so Hestia + hcpp-nodeapp restarts PM2 with Node 24 and flushes Nginx
/usr/local/hestia/bin/v-rebuild-web-domain "$USER" "$DOMAIN" >/dev/null 2>&1 || true
/usr/local/hestia/bin/v-invoke-plugin nodeapp_debounce >/dev/null 2>&1 || true
su - "$USER" -c "source /opt/nvm/nvm.sh && cd '$NODEAPP_DIR' && (pm2 delete 'app | $DOMAIN' >/dev/null 2>&1 || true) && pm2 start app.config.js >/dev/null 2>&1 && pm2 save --force >/dev/null 2>&1" || true

echo "OK|$TOKEN"
'''

v_info = r'''#!/bin/bash
# v-get-openclaw-info USER DOMAIN
USER="$1"
DOMAIN="$2"
ENV_FILE="/home/$USER/web/$DOMAIN/nodeapp/.openclaw-env.json"
PORT_FILE="/usr/local/hestia/data/hcpp/ports/$USER/$DOMAIN.ports"

if [ ! -f "$ENV_FILE" ]; then
    echo '{"installed": false}'
    exit 0
fi

python3 - "$ENV_FILE" "$PORT_FILE" "$DOMAIN" << 'PYEOF'
import sys, json, os, re
env_file, port_file, domain = sys.argv[1:4]
port = ""
if os.path.exists(port_file):
    try:
        txt = open(port_file).read()
        m = re.search(r'app_port\s+(\d+)', txt)
        if m:
            port = m.group(1)
    except Exception:
        pass
try:
    with open(env_file, 'r') as f:
        d = json.load(f)
    token = d.get('OPENCLAW_GATEWAY_TOKEN', '')
    has_openai = bool(d.get('OPENAI_API_KEY'))
    has_tg = bool(d.get('TELEGRAM_BOT_TOKEN'))
    openai_masked = (d.get('OPENAI_API_KEY','')[:7] + '...' + d.get('OPENAI_API_KEY','')[-4:]) if len(d.get('OPENAI_API_KEY','')) > 12 else ('Configured' if has_openai else '')
    tg_masked = (d.get('TELEGRAM_BOT_TOKEN','')[:8] + '...') if len(d.get('TELEGRAM_BOT_TOKEN','')) > 10 else ('Configured' if has_tg else '')
    print(json.dumps({
        "installed": True,
        "domain": domain,
        "port": port,
        "token": token,
        "has_openai": has_openai,
        "openai_masked": openai_masked,
        "has_telegram": has_tg,
        "telegram_masked": tg_masked,
        "url": f"http://{domain}/?token={token}",
        "ssl_url": f"https://{domain}/?token={token}"
    }))
except Exception as e:
    print(json.dumps({"installed": False, "error": str(e)}))
PYEOF
'''

with open('/usr/local/hestia/bin/v-install-openclaw', 'w', newline='\n') as f:
    f.write(v_install)
os.chmod('/usr/local/hestia/bin/v-install-openclaw', 0o755)

with open('/usr/local/hestia/bin/v-get-openclaw-info', 'w', newline='\n') as f:
    f.write(v_info)
os.chmod('/usr/local/hestia/bin/v-get-openclaw-info', 0o755)

print("Installed v-install-openclaw and v-get-openclaw-info (Node 24 forced)")
