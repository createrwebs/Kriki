#!/usr/bin/env python3
import subprocess

# 1. Seed OpenClaw Product (PID 8) in WHMCS MySQL (tblproducts & tblpricing)
sql = """SET NAMES utf8mb4;
DELETE FROM tblproducts WHERE id = 8;
CREATE TEMPORARY TABLE tmp_prod SELECT * FROM tblproducts WHERE id = 1;
UPDATE tmp_prod SET
    id = 8,
    gid = 1,
    type = 'hostingaccount',
    name = 'OpenClaw AI Agent Cloud (ติดตั้งอัตโนมัติ)',
    description = '<ul><li><strong>OpenClaw AI Agent (v2026)</strong> ติดตั้งพร้อมใช้งานทันทีบนโดเมนของคุณ</li><li><strong>Control UI &amp; WebSocket Gateway</strong> หน้าแดชบอร์ดแชทและตั้งค่าส่วนตัว</li><li><strong>รองรับเชื่อมต่อแชทครบวงจร:</strong> Telegram, WhatsApp, Discord, Slack, LINE</li><li><strong>รองรับโมเดล AI:</strong> OpenAI (GPT-4o/o3), Claude, Gemini, DeepSeek (ใส่ API Key ได้ในคลิกเดียว)</li><li><strong>รันด้วย Node.js v24 + PM2 Auto-Restart</strong> บน NVMe SSD 50 GB</li><li><strong>ฟรี SSL (HTTPS)</strong> และระบบจัดการโดเมน/DNS ครบวงจร</li></ul>',
    hidden = 0,
    showdomainoptions = 1,
    paytype = 'recurring',
    autosetup = 'payment',
    servertype = 'hestia',
    servergroup = 1,
    configoption1 = 'Single_Web_Hosting',
    configoption2 = 'yes',
    configoption3 = '',
    configoption4 = 'openclaw',
    `order` = 1,
    retired = 0,
    is_featured = 1,
    created_at = NOW(),
    updated_at = NOW();
INSERT INTO tblproducts SELECT * FROM tmp_prod;
DROP TEMPORARY TABLE tmp_prod;

DELETE FROM tblpricing WHERE type = 'product' AND relid = 8;
INSERT INTO tblpricing (type, currency, relid, msetupfee, qsetupfee, ssetupfee, asetupfee, bsetupfee, tsetupfee, monthly, quarterly, semiannually, annually, biennially, triennially)
VALUES
('product', 1, 8, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 5.99, 16.99, 32.99, 59.90, 109.00, 159.00),
('product', 2, 8, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 199.00, 569.00, 1090.00, 1990.00, 3690.00, 5190.00);

DELETE FROM tblknowledgebase WHERE id = 4;
DELETE FROM tblknowledgebaselinks WHERE articleid = 4;
INSERT INTO tblknowledgebase (id, title, article, views, useful, votes, private, `order`, parentid, language) VALUES
(4, 'วิธีสั่งซื้อและติดตั้ง OpenClaw AI Agent ส่วนตัวในคลิกเดียว (1-Click Deploy)', '<p>คุณสามารถสร้างผู้ช่วย <strong>OpenClaw AI Agent</strong> ส่วนตัวที่เชื่อมต่อกับ Telegram, WhatsApp, Discord, Slack และโมเดล OpenAI / Claude / Gemini บนโดเมนของคุณเองได้ง่ายๆ ดังนี้:</p><ol><li><strong>สั่งซื้อแพ็กเกจ OpenClaw AI Agent Cloud (฿199/เดือน):</strong> ระบบจะสร้างโฮสติ้งและติดตั้ง OpenClaw ให้อัตโนมัติทันทีที่ชำระเงินสำเร็จ</li><li><strong>หรือติดตั้งเพิ่มบนโฮสติ้งที่มีอยู่:</strong> เข้าไปที่หน้า <strong>บริการของฉัน (My Services)</strong> ในระบบสมาชิก WHMCS แล้วมองหาแผง <strong>&#129438; OpenClaw Personal AI Agent (1-Click Self-Hosted Installer)</strong></li><li><strong>ใส่ API Key &amp; Telegram Bot Token:</strong> เลือกโดเมนที่ต้องการ กรอก <code>OPENAI_API_KEY</code> และ <code>TELEGRAM_BOT_TOKEN</code> (ถ้ามี) แล้วกดปุ่ม <strong>ติดตั้ง / อัปเดต OpenClaw (1-Click)</strong></li><li><strong>เข้าสู่หน้าควบคุม:</strong> คลิกปุ่ม <strong>&#128640; เปิดหน้าควบคุม OpenClaw Control UI</strong> ซึ่งจะแนบ <code>Gateway Token</code> พาเข้าสู่หน้าแดชบอร์ดแชทและตั้งค่าตัวแทน AI ของคุณทันที</li></ol>', 164, 58, 58, '', 4, 0, '');
INSERT INTO tblknowledgebaselinks (categoryid, articleid) VALUES (1, 4), (3, 4);
"""

subprocess.run(['docker', 'exec', '-i', 'mysql-server', 'mysql', '--default-character-set=utf8mb4', '-uwhmcs_user', '-pWhmcsPass2026!', 'whmcs_db'], input=sql.encode("utf-8"), check=True)
print("Seeded OpenClaw product (id=8, 199 THB/mo) in WHMCS database.")

fp_path = '/opt/webstack/wordpress/wp-content/themes/krikkit-pulseai/front-page.php'
with open(fp_path, 'r', encoding='utf-8') as f:
    fp_txt = f.read()
fp_txt = fp_txt.replace(
    '<a href="/billing/cart.php?a=add&pid=1" class="cai-oc-btn">',
    '<a href="/billing/cart.php?a=add&pid=8" class="cai-oc-btn">'
).replace(
    '<li><a href="/billing/cart.php?a=add&pid=1">แอป AI Automation (OpenClaw)</a></li>',
    '<li><a href="/billing/cart.php?a=add&pid=8">แอป AI Automation (OpenClaw)</a></li>'
)
with open(fp_path, 'w', encoding='utf-8') as f:
    f.write(fp_txt)
print("Updated front-page.php OpenClaw links to pid=8.")

# 2. Update /opt/webstack/whmcs/modules/servers/hestia/hestia.php with OpenClaw auto-provisioning and 1-Click Installer UI
hestia_php = r'''<?php
/* CreateAI HestiaCP + NodeApp + OpenClaw AI Agent Extended Module for WHMCS 9.0 (PHP 8.2) */

function hestia_MetaData()
{
    return [
        'DisplayName' => 'CreateAI HestiaCP Extended (with NodeApp & OpenClaw AI)',
        'APIVersion' => '1.1',
        'RequiresServer' => true,
        'DefaultNonSSLPort' => '8083',
        'DefaultSSLPort' => '8083',
        'ServiceSingleSignOnLabel' => 'Login to HestiaCP',
        'AdminSingleSignOnLabel' => 'Login as Admin',
    ];
}

function hestia_ConfigOptions($params)
{
    return [
        'Package Name' => [
            'Type' => 'text',
            'Default' => 'default',
        ],
        'SSH Access' => [
            'Type' => 'yesno',
            'Description' => 'Tick to grant bash SSH access (required for NodeApp PM2 & OpenClaw)',
            'Default' => 'yes',
        ],
        'Server IP Address' => [
            'Type' => 'text',
            'Description' => 'Optional override IP (leave blank for server default)',
        ],
        'Auto-Deploy AI App' => [
            'Type' => 'dropdown',
            'Options' => ',openclaw',
            'Description' => 'Select openclaw to auto-provision OpenClaw AI Agent on account creation',
            'Default' => '',
        ],
    ];
}

function hestia_AdminCustomButtonArray()
{
    return [
        'Install LetsEncrypt SSL' => 'InstallSsl',
        'Enable NodeApp (Node.js)' => 'EnableNodeApp',
        'Deploy OpenClaw AI Agent' => 'InstallOpenClaw',
    ];
}

function hestia_ApiEndpoint($params)
{
    $host = !empty($params['serverhostname']) ? $params['serverhostname'] : $params['serverip'];
    $port = !empty($params['serverport']) ? $params['serverport'] : '8083';
    return 'https://' . $host . ':' . $port . '/api/';
}

function hestia_ApiCall($params, array $postvars, $actionName = 'ApiCall', $returnCode = true)
{
    $postvars['hash'] = $params['serveraccesshash'];
    if ($returnCode) {
        $postvars['returncode'] = 'yes';
    }
    $url = hestia_ApiEndpoint($params);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 45);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postvars));
    $answer = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if (function_exists('logModuleCall')) {
        logModuleCall('hestia', $actionName, $postvars, $answer !== false ? $answer : $err);
    }

    if ($answer === false || $err !== '') {
        return 'Connection error: ' . ($err ?: 'empty response');
    }
    return trim((string)$answer);
}

function hestia_ApiJson($params, $cmd, array $args = [])
{
    $postvars = ['cmd' => $cmd];
    $i = 1;
    foreach ($args as $a) {
        $postvars['arg' . $i] = $a;
        $i++;
    }
    $postvars['arg' . $i] = 'json';
    $raw = hestia_ApiCall($params, $postvars, $cmd, false);
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}

function hestia_GetOpenClawInfo($params, $user, $domain)
{
    if (empty($user) || empty($domain)) {
        return ['installed' => false];
    }
    $raw = hestia_ApiCall($params, [
        'cmd' => 'v-get-openclaw-info',
        'arg1' => $user,
        'arg2' => $domain,
    ], 'GetOpenClawInfo', false);
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : ['installed' => false];
}

function hestia_TestConnection(array $params)
{
    try {
        $data = hestia_ApiJson($params, 'v-list-sys-info');
        if (isset($data['sysinfo'])) {
            return ['success' => true, 'error' => ''];
        }
        return ['success' => false, 'error' => 'Unexpected API response'];
    } catch (\Throwable $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function hestia_CreateAccount($params)
{
    $res = hestia_ApiCall($params, [
        'cmd' => 'v-add-user',
        'arg1' => $params['username'],
        'arg2' => $params['password'],
        'arg3' => $params['clientsdetails']['email'],
        'arg4' => $params['configoption1'] ?: 'default',
        'arg5' => $params['clientsdetails']['firstname'],
        'arg6' => $params['clientsdetails']['lastname'],
    ], 'CreateAccount_UserAccount');

    if ($res !== '0') {
        return $res;
    }

    hestia_ApiCall($params, [
        'cmd' => 'v-change-user-shell',
        'arg1' => $params['username'],
        'arg2' => 'bash',
    ], 'CreateAccount_EnableSSH');

    if (!empty($params['domain'])) {
        $domVars = [
            'cmd' => 'v-add-domain',
            'arg1' => $params['username'],
            'arg2' => $params['domain'],
        ];
        if (!empty($params['configoption3']) && $params['configoption3'] !== 'openclaw') {
            $domVars['arg3'] = $params['configoption3'];
        }
        $res = hestia_ApiCall($params, $domVars, 'CreateAccount_AddDomain');
        if ($res !== '0') {
            return $res;
        }

        hestia_ApiCall($params, [
            'cmd' => 'v-add-web-domain-ftp',
            'arg1' => $params['username'],
            'arg2' => $params['domain'],
            'arg3' => 'ftp',
            'arg4' => $params['password'],
            'arg5' => 'public_html',
        ], 'CreateAccount_AddFtp');

        $autoApp = strtolower(trim((string)($params['configoption4'] ?? $params['configoption3'] ?? '')));
        if ($autoApp === 'openclaw' || (isset($params['pid']) && (int)$params['pid'] === 8)) {
            hestia_ApiCall($params, [
                'cmd' => 'v-install-openclaw',
                'arg1' => $params['username'],
                'arg2' => $params['domain'],
            ], 'CreateAccount_InstallOpenClaw', false);
        }
    }

    return 'success';
}

function hestia_TerminateAccount($params)
{
    $res = hestia_ApiCall($params, [
        'cmd' => 'v-delete-user',
        'arg1' => $params['username'],
    ], 'TerminateAccount');
    return $res === '0' ? 'success' : $res;
}

function hestia_SuspendAccount($params)
{
    $res = hestia_ApiCall($params, [
        'cmd' => 'v-suspend-user',
        'arg1' => $params['username'],
    ], 'SuspendAccount');
    return $res === '0' ? 'success' : $res;
}

function hestia_UnsuspendAccount($params)
{
    $res = hestia_ApiCall($params, [
        'cmd' => 'v-unsuspend-user',
        'arg1' => $params['username'],
    ], 'UnsuspendAccount');
    return $res === '0' ? 'success' : $res;
}

function hestia_ChangePassword($params)
{
    $res = hestia_ApiCall($params, [
        'cmd' => 'v-change-user-password',
        'arg1' => $params['username'],
        'arg2' => $params['password'],
    ], 'ChangePassword');
    return $res === '0' ? 'success' : $res;
}

function hestia_ChangePackage($params)
{
    $res = hestia_ApiCall($params, [
        'cmd' => 'v-change-user-package',
        'arg1' => $params['username'],
        'arg2' => $params['configoption1'] ?: 'default',
    ], 'ChangePackage');
    return $res === '0' ? 'success' : $res;
}

function hestia_InstallSsl($params)
{
    $res = hestia_ApiCall($params, [
        'cmd' => 'v-add-letsencrypt-domain',
        'arg1' => $params['username'],
        'arg2' => $params['domain'],
        'arg3' => '',
        'arg4' => 'yes',
    ], 'InstallSSL');
    return $res === '0' ? 'success' : $res;
}

function hestia_EnableNodeApp($params)
{
    $res = hestia_ApiCall($params, [
        'cmd' => 'v-change-web-domain-proxy-tpl',
        'arg1' => $params['username'],
        'arg2' => $params['domain'],
        'arg3' => 'NodeApp',
        'arg4' => 'yes',
    ], 'EnableNodeApp');
    return $res === '0' ? 'success' : $res;
}

function hestia_InstallOpenClaw($params)
{
    $out = hestia_ApiCall($params, [
        'cmd' => 'v-install-openclaw',
        'arg1' => $params['username'],
        'arg2' => $params['domain'],
    ], 'Admin_InstallOpenClaw', false);
    return strpos($out, 'OK|') !== false ? 'success' : ('OpenClaw install error: ' . $out);
}

function hestia_ClientArea($params)
{
    $host = !empty($params['serverhostname']) ? $params['serverhostname'] : $params['serverip'];
    $port = !empty($params['serverport']) ? $params['serverport'] : '8083';
    $user = $params['username'];
    $pass = $params['password'];
    $mainDomain = $params['domain'];

    $flashMsg = '';
    $flashType = 'success';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['cai_hestia_action']) && !empty($user)) {
        $act = (string)$_POST['cai_hestia_action'];
        if ($act === 'add_domain' && !empty($_POST['new_domain'])) {
            $dom = strtolower(trim(preg_replace('/[^a-zA-Z0-9\.\-]/', '', $_POST['new_domain'])));
            $r = hestia_ApiCall($params, ['cmd' => 'v-add-domain', 'arg1' => $user, 'arg2' => $dom], 'Client_AddDomain');
            if ($r === '0') {
                $flashMsg = "เพิ่มโดเมน {$dom} สำเร็จแล้ว! ชี้ Nameserver มาที่ ns1.createai.site และ ns2.createai.site เพื่อเริ่มใช้งาน";
            } else {
                $flashMsg = "ไม่สามารถเพิ่มโดเมนได้ (รหัส: {$r})";
                $flashType = 'danger';
            }
        } elseif ($act === 'install_openclaw' && !empty($_POST['target_domain'])) {
            $dom = strtolower(trim(preg_replace('/[^a-zA-Z0-9\.\-]/', '', $_POST['target_domain'])));
            $openaiKey = trim((string)($_POST['openai_api_key'] ?? ''));
            $tgToken = trim((string)($_POST['telegram_bot_token'] ?? ''));
            $openaiArg = $openaiKey !== '' ? $openaiKey : '__KEEP__';
            $tgArg = $tgToken !== '' ? $tgToken : '__KEEP__';
            $out = hestia_ApiCall($params, [
                'cmd' => 'v-install-openclaw',
                'arg1' => $user,
                'arg2' => $dom,
                'arg3' => $openaiArg,
                'arg4' => $tgArg,
            ], 'Client_InstallOpenClaw', false);
            if (strpos($out, 'OK|') !== false) {
                $parts = explode('|', $out);
                $tok = trim(end($parts));
                $flashMsg = "🦞 ติดตั้งและรัน OpenClaw AI Agent บนโดเมน {$dom} สำเร็จแล้ว! (Gateway Token: {$tok})";
            } else {
                $flashMsg = "ไม่สามารถติดตั้ง OpenClaw ได้: " . $out;
                $flashType = 'danger';
            }
        } elseif ($act === 'toggle_nodeapp' && !empty($_POST['target_domain'])) {
            $dom = trim($_POST['target_domain']);
            $tpl = ($_POST['proxy_tpl'] ?? 'default') === 'NodeApp' ? 'NodeApp' : 'default';
            $r = hestia_ApiCall($params, ['cmd' => 'v-change-web-domain-proxy-tpl', 'arg1' => $user, 'arg2' => $dom, 'arg3' => $tpl, 'arg4' => 'yes'], 'Client_ToggleNodeApp');
            if ($r === '0') {
                $flashMsg = "เปลี่ยนโหมดรันเว็บของ {$dom} เป็น {$tpl} สำเร็จแล้ว!";
            } else {
                $flashMsg = "ไม่สามารถเปลี่ยนโหมดได้ (รหัส: {$r})";
                $flashType = 'danger';
            }
        } elseif ($act === 'install_ssl' && !empty($_POST['target_domain'])) {
            $dom = trim($_POST['target_domain']);
            $r = hestia_ApiCall($params, ['cmd' => 'v-add-letsencrypt-domain', 'arg1' => $user, 'arg2' => $dom, 'arg3' => '', 'arg4' => 'yes'], 'Client_InstallSSL');
            if ($r === '0') {
                $flashMsg = "ติดตั้งใบรับรอง Let's Encrypt SSL สำหรับ {$dom} สำเร็จแล้ว!";
            } else {
                $flashMsg = "กำลังตรวจสอบการชี้ DNS ของ {$dom} ก่อนออกใบรับรอง SSL (รหัส: {$r})";
                $flashType = 'warning';
            }
        } elseif ($act === 'add_db' && !empty($_POST['db_name']) && !empty($_POST['db_user']) && !empty($_POST['db_pass'])) {
            $dbn = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['db_name']);
            $dbu = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['db_user']);
            $dbp = trim($_POST['db_pass']);
            $r = hestia_ApiCall($params, ['cmd' => 'v-add-database', 'arg1' => $user, 'arg2' => $dbn, 'arg3' => $dbu, 'arg4' => $dbp], 'Client_AddDB');
            if ($r === '0') {
                $flashMsg = "สร้างฐานข้อมูล {$user}_{$dbn} และผู้ใช้ {$user}_{$dbu} สำเร็จแล้ว!";
            } else {
                $flashMsg = "สร้างฐานข้อมูลไม่สำเร็จ (รหัส: {$r})";
                $flashType = 'danger';
            }
        } elseif ($act === 'add_dns' && !empty($_POST['dns_domain']) && !empty($_POST['dns_rec']) && !empty($_POST['dns_type']) && !empty($_POST['dns_val'])) {
            $ddom = trim($_POST['dns_domain']);
            $drec = trim($_POST['dns_rec']);
            $dtype = strtoupper(trim($_POST['dns_type']));
            $dval = trim($_POST['dns_val']);
            $r = hestia_ApiCall($params, ['cmd' => 'v-add-dns-record', 'arg1' => $user, 'arg2' => $ddom, 'arg3' => $drec, 'arg4' => $dtype, 'arg5' => $dval], 'Client_AddDNS');
            if ($r === '0') {
                $flashMsg = "เพิ่มระเบียน DNS ({$drec} {$dtype} -> {$dval}) ในโดเมน {$ddom} สำเร็จแล้ว!";
            } else {
                $flashMsg = "เพิ่มระเบียน DNS ไม่สำเร็จ (รหัส: {$r})";
                $flashType = 'danger';
            }
        }
    }

    $userInfo = !empty($user) ? hestia_ApiJson($params, 'v-list-user', [$user]) : [];
    $uData = $userInfo[$user] ?? [];
    $webDomains = !empty($user) ? hestia_ApiJson($params, 'v-list-web-domains', [$user]) : [];
    $databases = !empty($user) ? hestia_ApiJson($params, 'v-list-databases', [$user]) : [];

    // Fetch OpenClaw status for mainDomain (or first domain)
    $domainKeys = !empty($webDomains) ? array_keys($webDomains) : (!empty($mainDomain) ? [$mainDomain] : []);
    $openclawMap = [];
    foreach ($domainKeys as $dk) {
        $info = hestia_GetOpenClawInfo($params, $user, $dk);
        if (!empty($info['installed'])) {
            $openclawMap[$dk] = $info;
        }
    }

    $diskUsed = (int)($uData['U_DISK'] ?? 0);
    $diskQuota = $uData['DISK_QUOTA'] ?? 'unlimited';
    $bwUsed = (int)($uData['U_BANDWIDTH'] ?? 0);
    $bwQuota = $uData['BANDWIDTH'] ?? 'unlimited';
    $webCount = (int)($uData['U_WEB_DOMAINS'] ?? count($webDomains));
    $dbCount = (int)($uData['U_DATABASES'] ?? count($databases));

    $hHost = htmlspecialchars($host);
    $hPort = htmlspecialchars($port);
    $hUser = htmlspecialchars($user);
    $hPass = htmlspecialchars($pass);
    $hDom = htmlspecialchars($mainDomain);

    ob_start();
    ?>
    <div style="text-align:left; font-family:'Prompt','Inter',sans-serif; background:#141526; color:#f8fafc; padding:24px; border-radius:14px; border:1px solid rgba(103,61,230,0.35); margin-top:15px;">
        <div style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; border-bottom:1px solid rgba(255,255,255,0.1); padding-bottom:16px; margin-bottom:20px;">
            <div>
                <h3 style="margin:0; font-size:20px; font-weight:700; color:#ffffff;">🚀 ศูนย์จัดการโฮสติ้ง, โดเมน &amp; AI Agent (CreateAI Cloud Control)</h3>
                <p style="margin:4px 0 0; font-size:13px; color:#94a3b8;">จัดการเว็บไซต์, โดเมนเนม, DNS, ฐานข้อมูล MySQL, SSL และติดตั้ง OpenClaw AI Agent ของคุณเองในคลิกเดียว</p>
            </div>
            <div style="display:flex; flex-wrap:wrap; gap:8px;">
                <form action="https://<?= $hHost ?>:<?= $hPort ?>/login/" method="post" target="_blank" style="margin:0;">
                    <input type="hidden" name="user" value="<?= $hUser ?>" />
                    <input type="hidden" name="password" value="<?= $hPass ?>" />
                    <button type="submit" style="background:linear-gradient(135deg,#673de6,#ee5586); color:#fff; border:none; padding:9px 16px; border-radius:8px; font-weight:600; font-size:13px; cursor:pointer;">
                        ⚡ เข้าสู่ HestiaCP Control Panel
                    </button>
                </form>
                <a href="https://<?= $hHost ?>/phpmyadmin/" target="_blank" style="background:#1e293b; color:#f8fafc; border:1px solid rgba(255,255,255,0.15); padding:9px 14px; border-radius:8px; font-weight:600; font-size:13px; text-decoration:none;">
                    🗄️ phpMyAdmin
                </a>
                <a href="/lab" target="_blank" style="background:#0f766e; color:#ffffff; padding:9px 14px; border-radius:8px; font-weight:600; font-size:13px; text-decoration:none;">
                    ✨ สร้างเว็บด้วย AI Prompt
                </a>
            </div>
        </div>

        <?php if ($flashMsg !== ''): ?>
            <div style="padding:12px 16px; border-radius:8px; margin-bottom:18px; background:<?= $flashType === 'danger' ? 'rgba(239,68,68,0.2)' : 'rgba(16,185,129,0.2)' ?>; border:1px solid <?= $flashType === 'danger' ? '#ef4444' : '#10b981' ?>; color:#fff; font-size:13.5px;">
                <?= htmlspecialchars($flashMsg) ?>
            </div>
        <?php endif; ?>

        <!-- OpenClaw 1-Click AI Agent Installer & Dashboard Panel -->
        <div style="background:linear-gradient(135deg, rgba(103,61,230,0.22), rgba(238,85,134,0.16)); padding:20px; border-radius:12px; margin-bottom:22px; border:1px solid rgba(238,85,134,0.45);">
            <div style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; margin-bottom:14px;">
                <div>
                    <span style="background:#ee5586; color:#fff; font-size:11px; font-weight:700; padding:3px 9px; border-radius:20px; text-transform:uppercase;">AI Automation App</span>
                    <h4 style="margin:6px 0 4px; font-size:17px; font-weight:700; color:#fff;">🦞 OpenClaw Personal AI Agent (1-Click Self-Hosted Installer)</h4>
                    <p style="margin:0; font-size:12.5px; color:#cbd5e1;">สร้างผู้ช่วย AI ส่วนตัวของคุณเอง (เชื่อมต่อ Telegram, WhatsApp, Discord, Slack, Web Chat) รันแยกอิสระบนโดเมนของคุณด้วย PM2 + Node.js 24</p>
                </div>
            </div>

            <?php if (!empty($openclawMap)): ?>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:12px; margin-bottom:16px;">
                    <?php foreach ($openclawMap as $ocDom => $ocInfo):
                        $ocSsl = (($webDomains[$ocDom]['SSL'] ?? 'no') === 'yes');
                        $ocUrl = $ocSsl ? ($ocInfo['ssl_url'] ?? "https://{$ocDom}/?token={$ocInfo['token']}") : ($ocInfo['url'] ?? "http://{$ocDom}/?token={$ocInfo['token']}");
                    ?>
                        <div style="background:#0d0e1a; padding:14px 16px; border-radius:10px; border:1px solid rgba(16,185,129,0.4);">
                            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
                                <strong style="color:#34d399; font-size:14px;">🟢 OpenClaw กำลังทำงานบน: <?= htmlspecialchars($ocDom) ?></strong>
                                <span style="font-size:11.5px; color:#94a3b8;">Internal Port: <code><?= htmlspecialchars((string)($ocInfo['port'] ?: 'Auto')) ?></code></span>
                            </div>
                            <div style="font-size:12.5px; color:#e2e8f0; margin-bottom:10px; line-height:1.7;">
                                <div>🔑 <strong>Gateway Token:</strong> <code style="background:#1e293b; padding:2px 6px; border-radius:4px; color:#38bdf8;"><?= htmlspecialchars((string)($ocInfo['token'] ?? '')) ?></code></div>
                                <div>🤖 <strong>OpenAI API Key:</strong> <?= !empty($ocInfo['has_openai']) ? '<span style="color:#34d399;">ตั้งค่าแล้ว (' . htmlspecialchars($ocInfo['openai_masked']) . ')</span>' : '<span style="color:#fbbf24;">ยังไม่ได้ระบุ (ตั้งค่าด้านล่างหรือในหน้า Web UI ได้)</span>' ?></div>
                                <div>✈️ <strong>Telegram Bot:</strong> <?= !empty($ocInfo['has_telegram']) ? '<span style="color:#34d399;">เชื่อมต่อแล้ว (' . htmlspecialchars($ocInfo['telegram_masked']) . ')</span>' : '<span style="color:#94a3b8;">ยังไม่ได้ระบุ</span>' ?></div>
                            </div>
                            <div style="display:flex; flex-wrap:wrap; gap:8px;">
                                <a href="<?= htmlspecialchars($ocUrl) ?>" target="_blank" style="background:linear-gradient(135deg,#10b981,#059669); color:#fff; padding:7px 14px; border-radius:7px; font-size:12.5px; font-weight:700; text-decoration:none; display:inline-block;">
                                    🚀 เปิดหน้าควบคุม OpenClaw Control UI
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" style="background:rgba(13,14,26,0.75); padding:14px; border-radius:10px; border:1px solid rgba(255,255,255,0.1); display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:10px; align-items:end;">
                <input type="hidden" name="cai_hestia_action" value="install_openclaw" />
                <div>
                    <label style="display:block; font-size:12px; color:#cbd5e1; margin-bottom:4px;">เลือกโดเมนที่จะติดตั้ง / อัปเดต OpenClaw</label>
                    <select name="target_domain" required style="width:100%; padding:8px 10px; border-radius:7px; border:1px solid rgba(255,255,255,0.2); background:#141526; color:#fff; font-size:13px;">
                        <?php foreach ($domainKeys as $dk): ?>
                            <option value="<?= htmlspecialchars($dk) ?>"><?= htmlspecialchars($dk) ?><?= isset($openclawMap[$dk]) ? ' (ติดตั้งแล้ว - อัปเดตค่า)' : '' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:12px; color:#cbd5e1; margin-bottom:4px;">OpenAI / LLM API Key (ไม่บังคับ)</label>
                    <input type="password" name="openai_api_key" placeholder="sk-... (เว้นว่างเพื่อใช้ค่าเดิม)" style="width:100%; padding:8px 10px; border-radius:7px; border:1px solid rgba(255,255,255,0.2); background:#141526; color:#fff; font-size:13px;" />
                </div>
                <div>
                    <label style="display:block; font-size:12px; color:#cbd5e1; margin-bottom:4px;">Telegram Bot Token (ไม่บังคับ)</label>
                    <input type="password" name="telegram_bot_token" placeholder="123456:ABC-DEF... (เว้นว่างได้)" style="width:100%; padding:8px 10px; border-radius:7px; border:1px solid rgba(255,255,255,0.2); background:#141526; color:#fff; font-size:13px;" />
                </div>
                <div>
                    <button type="submit" style="width:100%; background:linear-gradient(135deg,#673de6,#ee5586); color:#fff; border:none; padding:9px 14px; border-radius:7px; font-weight:700; font-size:13px; cursor:pointer;">
                        🦞 ติดตั้ง / อัปเดต OpenClaw (1-Click)
                    </button>
                </div>
            </form>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(150px, 1fr)); gap:12px; margin-bottom:22px;">
            <div style="background:#1b1c33; padding:14px; border-radius:10px; border:1px solid rgba(255,255,255,0.07);">
                <div style="font-size:12px; color:#94a3b8;">พื้นที่จัดเก็บ (NVMe SSD)</div>
                <div style="font-size:18px; font-weight:700; color:#38bdf8; margin-top:4px;"><?= $diskUsed ?> MB / <?= htmlspecialchars((string)$diskQuota) ?> MB</div>
            </div>
            <div style="background:#1b1c33; padding:14px; border-radius:10px; border:1px solid rgba(255,255,255,0.07);">
                <div style="font-size:12px; color:#94a3b8;">แบนด์วิดท์ (Bandwidth)</div>
                <div style="font-size:18px; font-weight:700; color:#a855f7; margin-top:4px;"><?= $bwUsed ?> MB / <?= htmlspecialchars((string)$bwQuota) ?></div>
            </div>
            <div style="background:#1b1c33; padding:14px; border-radius:10px; border:1px solid rgba(255,255,255,0.07);">
                <div style="font-size:12px; color:#94a3b8;">โดเมนเว็บไซต์ที่ผูกไว้</div>
                <div style="font-size:18px; font-weight:700; color:#10b981; margin-top:4px;"><?= $webCount ?> โดเมน</div>
            </div>
            <div style="background:#1b1c33; padding:14px; border-radius:10px; border:1px solid rgba(255,255,255,0.07);">
                <div style="font-size:12px; color:#94a3b8;">ฐานข้อมูล MySQL</div>
                <div style="font-size:18px; font-weight:700; color:#f59e0b; margin-top:4px;"><?= $dbCount ?> ฐานข้อมูล</div>
            </div>
        </div>

        <div style="background:#1b1c33; padding:14px 18px; border-radius:10px; margin-bottom:22px; font-size:13px; line-height:1.8; border-left:4px solid #673de6;">
            <div><strong>🌐 เซิร์ฟเวอร์โฮสติ้ง:</strong> <?= $hHost ?> (IP: <?= htmlspecialchars($params['serverip'] ?: '43.106.69.51') ?>) &nbsp;|&nbsp; <strong>ชื่อผู้ใช้ (SSH / SFTP / Panel):</strong> <code><?= $hUser ?></code></div>
            <div><strong>🧭 Nameservers สำหรับชี้โดเมน:</strong> <code>ns1.createai.site</code> , <code>ns2.createai.site</code> &nbsp;|&nbsp; <strong>Node.js (NVM + PM2):</strong> รองรับ Node v20, v22, v24 (พร้อม OpenClaw Runtime)</div>
        </div>

        <div style="background:#1b1c33; padding:18px; border-radius:10px; margin-bottom:20px; border:1px solid rgba(255,255,255,0.08);">
            <h4 style="margin:0 0 12px; font-size:16px; font-weight:700; color:#fff;">🌐 1. จัดการโดเมนเว็บไซต์ &amp; โหมดรันแอป (WordPress / PHP / Node.js PM2 / OpenClaw)</h4>
            <?php if (!empty($webDomains)): ?>
                <div style="overflow-x:auto; margin-bottom:14px;">
                    <table style="width:100%; border-collapse:collapse; font-size:13px; color:#e2e8f0;">
                        <thead>
                            <tr style="border-bottom:1px solid rgba(255,255,255,0.12); text-align:left; color:#94a3b8;">
                                <th style="padding:8px;">โดเมน</th>
                                <th style="padding:8px;">IP Address</th>
                                <th style="padding:8px;">SSL (HTTPS)</th>
                                <th style="padding:8px;">โหมดเซิร์ฟเวอร์ (Proxy Template)</th>
                                <th style="padding:8px;">จัดการด่วน</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($webDomains as $dName => $dInfo):
                            $proxyTpl = $dInfo['PROXY'] ?? 'default';
                            $sslEnabled = ($dInfo['SSL'] ?? 'no') === 'yes';
                            $hasOc = isset($openclawMap[$dName]);
                        ?>
                            <tr style="border-bottom:1px solid rgba(255,255,255,0.06);">
                                <td style="padding:8px; font-weight:600;"><a href="http://<?= htmlspecialchars($dName) ?>" target="_blank" style="color:#38bdf8;"><?= htmlspecialchars($dName) ?></a></td>
                                <td style="padding:8px;"><?= htmlspecialchars($dInfo['IP'] ?? '') ?></td>
                                <td style="padding:8px;">
                                    <?php if ($sslEnabled): ?>
                                        <span style="background:rgba(16,185,129,0.2); color:#34d399; padding:3px 8px; border-radius:6px; font-size:11.5px;">🔒 เปิดใช้งาน SSL</span>
                                    <?php else: ?>
                                        <span style="background:rgba(245,158,11,0.2); color:#fbbf24; padding:3px 8px; border-radius:6px; font-size:11.5px;">ยังไม่เปิด SSL</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding:8px;">
                                    <?php if ($hasOc && $proxyTpl === 'NodeApp'): ?>
                                        <span style="background:rgba(238,85,134,0.25); color:#fff; padding:3px 8px; border-radius:6px; font-size:11.5px;">
                                            🦞 OpenClaw AI Agent (PM2)
                                        </span>
                                    <?php else: ?>
                                        <span style="background:<?= $proxyTpl === 'NodeApp' ? 'rgba(168,85,247,0.25)' : 'rgba(56,189,248,0.2)' ?>; color:#fff; padding:3px 8px; border-radius:6px; font-size:11.5px;">
                                            <?= $proxyTpl === 'NodeApp' ? '🟢 NodeApp (Node.js PM2)' : '🐘 Standard PHP / WordPress' ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding:8px; display:flex; gap:6px; flex-wrap:wrap;">
                                    <form method="post" style="margin:0;">
                                        <input type="hidden" name="cai_hestia_action" value="toggle_nodeapp" />
                                        <input type="hidden" name="target_domain" value="<?= htmlspecialchars($dName) ?>" />
                                        <input type="hidden" name="proxy_tpl" value="<?= $proxyTpl === 'NodeApp' ? 'default' : 'NodeApp' ?>" />
                                        <button type="submit" style="background:#673de6; color:#fff; border:none; padding:5px 10px; border-radius:6px; font-size:12px; cursor:pointer;">
                                            <?= $proxyTpl === 'NodeApp' ? 'สลับเป็น PHP/WP' : 'สลับเป็น Node.js (NodeApp)' ?>
                                        </button>
                                    </form>
                                    <?php if (!$sslEnabled): ?>
                                    <form method="post" style="margin:0;">
                                        <input type="hidden" name="cai_hestia_action" value="install_ssl" />
                                        <input type="hidden" name="target_domain" value="<?= htmlspecialchars($dName) ?>" />
                                        <button type="submit" style="background:#059669; color:#fff; border:none; padding:5px 10px; border-radius:6px; font-size:12px; cursor:pointer;">
                                            🔒 ติดตั้ง Free SSL
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <form method="post" style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                <input type="hidden" name="cai_hestia_action" value="add_domain" />
                <input type="text" name="new_domain" placeholder="ระบุชื่อโดเมนใหม่ เช่น myagent.createai.site หรือ myshop.com" required style="flex:1; min-width:220px; padding:8px 12px; border-radius:7px; border:1px solid rgba(255,255,255,0.2); background:#0d0e1a; color:#fff; font-size:13px;" />
                <button type="submit" style="background:#ee5586; color:#fff; border:none; padding:8px 16px; border-radius:7px; font-weight:600; font-size:13px; cursor:pointer;">
                    + เพิ่มโดเมนในโฮสติ้ง
                </button>
            </form>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:16px;">
            <div style="background:#1b1c33; padding:18px; border-radius:10px; border:1px solid rgba(255,255,255,0.08);">
                <h4 style="margin:0 0 12px; font-size:15px; font-weight:700; color:#fff;">🧭 2. จัดการระเบียน DNS (A / CNAME / TXT / MX)</h4>
                <form method="post" style="display:flex; flex-direction:column; gap:8px;">
                    <input type="hidden" name="cai_hestia_action" value="add_dns" />
                    <input type="text" name="dns_domain" value="<?= $hDom ?>" placeholder="โดเมนหลัก" required style="padding:7px 10px; border-radius:6px; border:1px solid rgba(255,255,255,0.18); background:#0d0e1a; color:#fff; font-size:12.5px;" />
                    <div style="display:flex; gap:6px;">
                        <input type="text" name="dns_rec" placeholder="Host (@, www)" required style="flex:1; padding:7px 10px; border-radius:6px; border:1px solid rgba(255,255,255,0.18); background:#0d0e1a; color:#fff; font-size:12.5px;" />
                        <select name="dns_type" style="padding:7px 10px; border-radius:6px; border:1px solid rgba(255,255,255,0.18); background:#0d0e1a; color:#fff; font-size:12.5px;">
                            <option value="A">A</option>
                            <option value="CNAME">CNAME</option>
                            <option value="TXT">TXT</option>
                            <option value="MX">MX</option>
                        </select>
                    </div>
                    <input type="text" name="dns_val" placeholder="ค่า IP หรือปลายทาง (เช่น 43.106.69.51)" required style="padding:7px 10px; border-radius:6px; border:1px solid rgba(255,255,255,0.18); background:#0d0e1a; color:#fff; font-size:12.5px;" />
                    <button type="submit" style="background:#673de6; color:#fff; border:none; padding:8px 14px; border-radius:6px; font-weight:600; font-size:12.5px; cursor:pointer;">
                        + เพิ่มระเบียน DNS
                    </button>
                </form>
            </div>

            <div style="background:#1b1c33; padding:18px; border-radius:10px; border:1px solid rgba(255,255,255,0.08);">
                <h4 style="margin:0 0 12px; font-size:15px; font-weight:700; color:#fff;">🗄️ 3. สร้างฐานข้อมูล MySQL ใหม่</h4>
                <form method="post" style="display:flex; flex-direction:column; gap:8px;">
                    <input type="hidden" name="cai_hestia_action" value="add_db" />
                    <input type="text" name="db_name" placeholder="ชื่อฐานข้อมูล (เช่น appdb)" required style="padding:7px 10px; border-radius:6px; border:1px solid rgba(255,255,255,0.18); background:#0d0e1a; color:#fff; font-size:12.5px;" />
                    <input type="text" name="db_user" placeholder="ชื่อผู้ใช้ฐานข้อมูล (เช่น appuser)" required style="padding:7px 10px; border-radius:6px; border:1px solid rgba(255,255,255,0.18); background:#0d0e1a; color:#fff; font-size:12.5px;" />
                    <input type="password" name="db_pass" placeholder="รหัสผ่านฐานข้อมูล" required style="padding:7px 10px; border-radius:6px; border:1px solid rgba(255,255,255,0.18); background:#0d0e1a; color:#fff; font-size:12.5px;" />
                    <button type="submit" style="background:#059669; color:#fff; border:none; padding:8px 14px; border-radius:6px; font-weight:600; font-size:12.5px; cursor:pointer;">
                        + สร้างฐานข้อมูล MySQL
                    </button>
                </form>
                <?php if (!empty($databases)): ?>
                    <div style="margin-top:10px; font-size:12px; color:#94a3b8;">
                        <strong>ฐานข้อมูลที่มีอยู่:</strong> <?= htmlspecialchars(implode(', ', array_keys($databases))) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function hestia_AdminLink($params)
{
    $host = !empty($params['serverhostname']) ? $params['serverhostname'] : $params['serverip'];
    $port = !empty($params['serverport']) ? $params['serverport'] : '8083';
    return '<form action="https://' . htmlspecialchars($host) . ':' . htmlspecialchars($port) . '/login/" method="post" target="_blank">'
        . '<input type="hidden" name="user" value="' . htmlspecialchars($params['serverusername']) . '" />'
        . '<input type="hidden" name="password" value="' . htmlspecialchars($params['serverpassword']) . '" />'
        . '<input type="submit" value="Login to HestiaCP" />'
        . '</form>';
}

function hestia_LoginLink($params)
{
    $host = !empty($params['serverhostname']) ? $params['serverhostname'] : $params['serverip'];
    $port = !empty($params['serverport']) ? $params['serverport'] : '8083';
    echo '<style>#btnLoginLinkTrigger { display: none }</style>'
        . '<div class="col-sm-5">'
        . '<a href="https://' . htmlspecialchars($host) . ':' . htmlspecialchars($port) . '/login/" class="btn btn-primary" target="_blank">'
        . '<i class="fas fa-sign-in fa-fw"></i> เข้าสู่ระบบ Control Panel (HestiaCP)</a></div>';
}

function hestia_UsageUpdate($params)
{
    $results = hestia_ApiJson($params, 'v-list-users');
    foreach ($results as $user => $values) {
        update_query('tblhosting', [
            'diskusage' => $values['U_DISK'] ?? 0,
            'disklimit' => $values['DISK_QUOTA'] ?? 0,
            'bwusage' => $values['U_BANDWIDTH'] ?? 0,
            'bwlimit' => $values['BANDWIDTH'] ?? 0,
            'lastupdate' => 'now()',
        ], ['server' => $params['serverid'], 'username' => $user]);
    }
}
'''

with open('/opt/webstack/whmcs/modules/servers/hestia/hestia.php', 'w', encoding='utf-8') as f:
    f.write(hestia_php)

print("Updated /opt/webstack/whmcs/modules/servers/hestia/hestia.php")
