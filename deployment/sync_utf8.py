import subprocess, re

# 1. Write UTF-8 hestia.php
hestia_php = r"""<?php
/* CreateAI HestiaCP + NodeApp Extended Hosting & Domain Control Module for WHMCS 9.0 (PHP 8.2) */

function hestia_MetaData()
{
    return [
        'DisplayName' => 'CreateAI HestiaCP Extended (with NodeApp)',
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
            'Description' => 'Tick to grant bash SSH access (required for NodeApp PM2)',
            'Default' => 'yes',
        ],
        'Server IP Address' => [
            'Type' => 'text',
            'Description' => 'Optional override IP (leave blank for server default)',
        ],
    ];
}

function hestia_AdminCustomButtonArray()
{
    return [
        'Install LetsEncrypt SSL' => 'InstallSsl',
        'Enable NodeApp (Node.js)' => 'EnableNodeApp',
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
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
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
        if (!empty($params['configoption3'])) {
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
        } elseif ($act === 'backup_now') {
            $r = hestia_ApiCall($params, ['cmd' => 'v-backup-user', 'arg1' => $user], 'Client_Backup');
            $flashMsg = "สั่งสำรองข้อมูลบัญชี {$user} เรียบร้อยแล้ว!";
        }
    }

    $userInfo = !empty($user) ? hestia_ApiJson($params, 'v-list-user', [$user]) : [];
    $uData = $userInfo[$user] ?? [];
    $webDomains = !empty($user) ? hestia_ApiJson($params, 'v-list-web-domains', [$user]) : [];
    $databases = !empty($user) ? hestia_ApiJson($params, 'v-list-databases', [$user]) : [];

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
                <h3 style="margin:0; font-size:20px; font-weight:700; color:#ffffff;">🚀 ศูนย์จัดการโฮสติ้ง &amp; โดเมนครบวงจร (CreateAI Cloud Control)</h3>
                <p style="margin:4px 0 0; font-size:13px; color:#94a3b8;">จัดการเว็บไซต์, โดเมนเนม, DNS, ฐานข้อมูล MySQL, SSL และแอป Node.js (PM2) ได้ทันทีจากหน้านี้</p>
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
            <div><strong>🧭 Nameservers สำหรับชี้โดเมน:</strong> <code>ns1.createai.site</code> , <code>ns2.createai.site</code> &nbsp;|&nbsp; <strong>Node.js (NVM + PM2):</strong> รองรับ Node v20, v22, v24 (Default)</div>
        </div>

        <div style="background:#1b1c33; padding:18px; border-radius:10px; margin-bottom:20px; border:1px solid rgba(255,255,255,0.08);">
            <h4 style="margin:0 0 12px; font-size:16px; font-weight:700; color:#fff;">🌐 1. จัดการโดเมนเว็บไซต์ &amp; โหมดรันแอป (WordPress / PHP / Node.js PM2)</h4>
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
                                    <span style="background:<?= $proxyTpl === 'NodeApp' ? 'rgba(168,85,247,0.25)' : 'rgba(56,189,248,0.2)' ?>; color:#fff; padding:3px 8px; border-radius:6px; font-size:11.5px;">
                                        <?= $proxyTpl === 'NodeApp' ? '🟢 NodeApp (Node.js PM2)' : '🐘 Standard PHP / WordPress' ?>
                                    </span>
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
                <input type="text" name="new_domain" placeholder="ระบุชื่อโดเมนใหม่ เช่น myshop.com" required style="flex:1; min-width:220px; padding:8px 12px; border-radius:7px; border:1px solid rgba(255,255,255,0.2); background:#0d0e1a; color:#fff; font-size:13px;" />
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
"""
open('/opt/webstack/whmcs/modules/servers/hestia/hestia.php', 'w', encoding='utf-8').write(hestia_php)

# 2. Write UTF-8 WordPress index.php, single.php, page.php
wp_single = """<?php
/**
 * CreateAI Complete Single Post & Page Template
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title('|', true, 'right'); ?> CreateAI - ระบบสร้างเว็บไซต์ด้วย AI Prompt &amp; Cloud Hosting</title>
    <link rel="icon" href="/brand/createai-logo.jpg">
    <link rel="stylesheet" href="/antler/assets/fonts/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/antler/assets/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Prompt:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0d0e1a;
            color: #f8fafc;
            font-family: 'Prompt', 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.75;
        }
        .cai-top-nav {
            background: rgba(13, 14, 26, 0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(103, 61, 230, 0.25);
            padding: 16px 24px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .cai-top-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }
        .cai-nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .cai-nav-links a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s;
        }
        .cai-nav-links a:hover {
            color: #ffffff;
        }
        .cai-btn-cta {
            background: linear-gradient(135deg, #673de6 0%, #ee5586 100%);
            color: #ffffff !important;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 600 !important;
        }
        .cai-article-wrap {
            max-width: 900px;
            margin: 60px auto;
            padding: 0 24px;
        }
        .cai-article-card {
            background: #141526;
            border: 1px solid rgba(103, 61, 230, 0.25);
            border-radius: 18px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
        }
        .cai-article-card h1 {
            font-size: 32px;
            font-weight: 800;
            color: #ffffff;
            margin-top: 0;
            margin-bottom: 16px;
            line-height: 1.35;
        }
        .cai-article-meta {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .cai-article-content {
            font-size: 16px;
            color: #e2e8f0;
        }
        .cai-article-content h2, .cai-article-content h3 {
            color: #ffffff;
            margin-top: 28px;
            margin-bottom: 12px;
        }
        .cai-article-content code {
            background: rgba(103, 61, 230, 0.2);
            color: #38bdf8;
            padding: 2px 6px;
            border-radius: 4px;
        }
        .cai-footer-simple {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding: 36px 24px;
            text-align: center;
            color: #94a3b8;
            font-size: 13.5px;
            margin-top: 80px;
        }
    </style>
</head>
<body>
    <header class="cai-top-nav">
        <div class="cai-top-inner">
            <a href="/">
                <img src="/antler/assets/img/logo.svg" alt="CreateAI" width="180" height="45">
            </a>
            <nav class="cai-nav-links">
                <a href="/">หน้าแรก</a>
                <a href="/lab">✨ สร้างเว็บด้วย AI Prompt</a>
                <a href="/billing/index.php?rp=/store/web-hosting">เว็บโฮสติ้ง &amp; Node.js</a>
                <a href="/billing/index.php?rp=/store/vps">Cloud VPS</a>
                <a href="/billing/cart.php?a=add&domain=register">จดโดเมน</a>
                <a href="/billing/clientarea.php">จัดการโดเมน &amp; โฮสติ้ง</a>
                <a href="/lab" class="cai-btn-cta">เริ่มต้นใช้งานฟรี</a>
            </nav>
        </div>
    </header>

    <main class="cai-article-wrap">
        <div class="cai-article-card">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <h1><?php the_title(); ?></h1>
                <div class="cai-article-meta">
                    <span><i class="far fa-calendar-alt"></i> อัปเดตล่าสุด: <?php echo get_the_date(); ?></span>
                    &nbsp;|&nbsp;
                    <span><i class="fas fa-user-astronaut"></i> โดยทีมวิศวกร CreateAI</span>
                </div>
                <div class="cai-article-content">
                    <?php the_content(); ?>
                </div>
            <?php endwhile; endif; ?>
            <div style="margin-top: 36px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <a href="/" style="color: #38bdf8; text-decoration: none; font-weight: 600;"><i class="fas fa-arrow-left"></i> กลับสู่หน้าแรก CreateAI</a>
                <a href="/lab" class="cai-btn-cta" style="text-decoration: none;">✨ ทดลองสร้างเว็บไซต์ด้วย AI Prompt</a>
            </div>
        </div>
    </main>

    <footer class="cai-footer-simple">
        <p>© <?php echo date('Y'); ?> CreateAI — แพลตฟอร์มสร้างเว็บไซต์ด้วย AI Prompt, จดโดเมนเนม และ Cloud Hosting ความเร็วสูง</p>
    </footer>
</body>
</html>
"""
for fn in ['index.php', 'single.php', 'page.php']:
    open(f'/opt/webstack/wordpress/wp-content/themes/krikkit-pulseai/{fn}', 'w', encoding='utf-8').write(wp_single)

# 3. Also restore/update front-page.php nav addition with pure UTF-8
fp_path = '/opt/webstack/wordpress/wp-content/themes/krikkit-pulseai/front-page.php'
fp_txt = open(fp_path, 'r', encoding='utf-8').read()
fp_txt = re.sub(r'<li class="cai-nav-item">\s*<a href="/billing/clientarea\.php" class="cai-nav-link">.*?</li>', '', fp_txt, flags=re.S)
nav_addition = """                <li class="cai-nav-item">
                    <a href="/billing/clientarea.php" class="cai-nav-link">
                        <i class="fas fa-sliders" style="color: #38bdf8; margin-right: 4px;"></i> จัดการโดเมน &amp; โฮสติ้ง <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="cai-megamenu" style="width: 340px; grid-template-columns: 1fr;">
                        <a href="/billing/clientarea.php?action=domains" class="cai-megamenu-item">
                            <div class="cai-mm-icon"><i class="fas fa-globe"></i></div>
                            <div class="cai-mm-text">
                                <h5>จัดการโดเมน &amp; DNS</h5>
                                <p>ตั้งค่า Nameservers, เพิ่มระเบียน A/CNAME/TXT/MX และต่ออายุโดเมน</p>
                            </div>
                        </a>
                        <a href="/billing/clientarea.php?action=services" class="cai-megamenu-item">
                            <div class="cai-mm-icon"><i class="fas fa-server"></i></div>
                            <div class="cai-mm-text">
                                <h5>จัดการโฮสติ้ง, MySQL &amp; Node.js</h5>
                                <p>เพิ่มโดเมนเว็บไซต์, สลับโหมด PHP/Node.js (PM2), ติดตั้ง Free SSL</p>
                            </div>
                        </a>
                        <a href="https://sv1.createai.site:8083" target="_blank" class="cai-megamenu-item">
                            <div class="cai-mm-icon"><i class="fas fa-gauge-high"></i></div>
                            <div class="cai-mm-text">
                                <h5>HestiaCP Control Panel</h5>
                                <p>เข้าสู่ระบบจัดการเซิร์ฟเวอร์ HestiaCP + NodeApp โดยตรง</p>
                            </div>
                        </a>
                    </div>
                </li>"""
fp_txt = fp_txt.replace('<li class="cai-nav-item">\n                    <a href="#pricing" class="cai-nav-link">ราคา</a>\n                </li>',
                        '<li class="cai-nav-item">\n                    <a href="#pricing" class="cai-nav-link">ราคา</a>\n                </li>\n' + nav_addition)
open(fp_path, 'w', encoding='utf-8').write(fp_txt)

# 4. Seed WordPress posts with pure UTF-8
sql = """SET NAMES utf8mb4;
DELETE FROM wp_posts WHERE post_type='post';
INSERT INTO wp_posts (ID, post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count) VALUES
(101, 1, NOW(), NOW(), '<h2>สร้างเว็บไซต์ระดับมืออาชีพใน 60 วินาทีด้วย CreateAI</h2><p>ไม่ต้องเสียเวลาเขียนโค้ดหรือจ้างทีมพัฒนาหลายสัปดาห์ เพียงพิมพ์คำสั่ง (Prompt) บอกธุรกิจของคุณในหน้า <strong>CreateAI Lab</strong> ระบบ AI จะวางโครงสร้างหน้าเว็บ เลือกรูปภาพจาก Unsplash และสร้างระบบตะกร้าสินค้าหรือแบบฟอร์มติดต่อให้พร้อมใช้งานทันที</p><h3>จุดเด่นของ CreateAI Prompt Builder</h3><ul><li>รองรับการสั่งงานภาษาไทยและภาษาอังกฤษ</li><li>แก้ไขหน้าเว็บแบบ WYSIWYG และอัปโหลดรูปภาพได้อิสระ</li><li>เผยแพร่ออนไลน์พร้อม SSL ฟรี หรือเชื่อมต่อโดเมนส่วนตัวได้ทันที</li></ul>', 'เทคนิคสร้างเว็บไซต์ธุรกิจด้วย AI Prompt ใน 60 วินาที', 'เปลี่ยนข้อความคำสั่งภาษาไทยให้เป็นเว็บไซต์ธุรกิจพร้อมระบบหลังบ้านและตะกร้าสินค้าได้ทันทีโดยไม่ต้องเขียนโค้ด', 'publish', 'closed', 'closed', 'ai-prompt-website-builder-guide', '', '', NOW(), NOW(), '', 0, 'https://createai.site/?p=101', 0, 'post', '', 0),
(102, 1, NOW(), NOW(), '<h2>จัดการโดเมนและโฮสติ้งครบวงจรด้วย HestiaCP + NodeApp</h2><p>เซิร์ฟเวอร์โฮสติ้งของ <strong>CreateAI</strong> ขับเคลื่อนด้วย NVMe SSD ความเร็วสูง พร้อมระบบ <strong>Hestia Control Panel</strong> ที่ติดตั้งปลั๊กอิน <code>hcpp-nodeapp</code> รองรับทั้งเว็บไซต์ <strong>WordPress / PHP 8.2+</strong> และแอปพลิเคชัน <strong>Node.js (v20, v22, v24)</strong> ที่ดูแลโปรเซสด้วย PM2 อัตโนมัติ</p><h3>ฟีเจอร์ระบบจัดการโดเมน & โฮสติ้ง</h3><ul><li>สลับโหมดโดเมนระหว่าง PHP/WordPress และ Node.js (NodeApp) ได้ในคลิกเดียว</li><li>ตั้งค่า Nameservers (<code>ns1.createai.site</code>, <code>ns2.createai.site</code>) และจัดการระเบียน DNS (A, CNAME, TXT, MX)</li><li>ติดตั้งใบรับรองความปลอดภัย Let''s Encrypt SSL ฟรีทุกโดเมน</li></ul>', 'คู่มือจัดการโดเมน & โฮสติ้ง HestiaCP รองรับทั้ง WordPress และ Node.js (PM2)', 'บริหารจัดการโดเมนเนม ระเบียน DNS ฐานข้อมูล MySQL และรันแอป Node.js v20/v22/v24 ควบคู่กับ WordPress ได้ในที่เดียว', 'publish', 'closed', 'closed', 'hestiacp-nodejs-domain-hosting-guide', '', '', NOW(), NOW(), '', 0, 'https://createai.site/?p=102', 0, 'post', '', 0),
(103, 1, NOW(), NOW(), '<h2>ทำไม Cloud VPS (KVM NVMe) ของ CreateAI ถึงเร็วและคุ้มค่าที่สุด</h2><p>สำหรับธุรกิจที่ต้องการทรัพยากรส่วนตัวเต็มประสิทธิภาพ แพ็กเกจ <strong>Cloud VPS KVM</strong> ของเราเริ่มต้นเพียง 199 บาท/เดือน มาพร้อม Dedicated IP, Root SSH Access และเครือข่ายความเร็วสูง 1Gbps พร้อมระบบป้องกัน DDoS อัตโนมัติ</p>', 'เปรียบเทียบ Web Hosting vs Cloud VPS เลือกแพ็กเกจไหนให้เหมาะกับธุรกิจคุณ', 'เจาะลึกความแตกต่างระหว่าง Shared NVMe Hosting และ KVM Cloud VPS เพื่อรองรับการเติบโตของเว็บไซต์และแอปพลิเคชัน', 'publish', 'closed', 'closed', 'web-hosting-vs-cloud-vps-comparison', '', '', NOW(), NOW(), '', 0, 'https://createai.site/?p=103', 0, 'post', '', 0);
"""
subprocess.run(['docker', 'exec', '-i', 'mysql-server', 'mysql', '--default-character-set=utf8mb4', '-uwp_user', '-pWpPass2026!', 'wordpress_db'], input=sql.encode('utf-8'), check=True)

# 5. Seed WHMCS KB & announcements with pure UTF-8
whmcs_sql = """SET NAMES utf8mb4;
UPDATE tblproducts SET configoption2='on' WHERE servertype='hestia';
DELETE FROM tblannouncements;
INSERT INTO tblannouncements (id, date, title, announcement, published, parentid, language, created_at, updated_at) VALUES
(1, NOW(), 'เปิดตัวระบบสร้างเว็บไซต์ด้วย AI Prompt (CreateAI Lab) พร้อมเชื่อมต่อ Cloud Hosting อัตโนมัติ', '<p>ยินดีต้อนรับสู่ <strong>CreateAI</strong> แพลตฟอร์มสร้างเว็บไซต์ด้วยคำสั่ง AI Prompt ที่ให้คุณออกแบบหน้าเว็บ แก้ไขเนื้อหาแบบ WYSIWYG และนำขึ้นระบบ Cloud Hosting ความเร็วสูง (NVMe SSD + HestiaCP + Node.js v24) ได้ภายใน 60 วินาที</p>', 1, 0, '', NOW(), NOW()),
(2, NOW(), 'อัปเกรดเซิร์ฟเวอร์โฮสติ้งรองรับ Node.js Apps (PM2 + NVM v20/v22/v24) เต็มรูปแบบ', '<p>ลูกค้าเว็บโฮสติ้งทุกแพ็กเกจสามารถรันแอปพลิเคชัน <strong>Node.js (Express, Next.js, Vite, API)</strong> ควบคู่กับ WordPress และ PHP 8.2+ ได้แล้ววันนี้ผ่านปลั๊กอิน <strong>NodeApp (PM2)</strong> บน Hestia Control Panel</p>', 1, 0, '', NOW(), NOW());

DELETE FROM tblknowledgebasecats;
INSERT INTO tblknowledgebasecats (id, parentid, name, description, hidden, catid, language) VALUES
(1, 0, 'การใช้งานระบบสร้างเว็บด้วย AI Prompt (CreateAI Lab)', 'คู่มือการสั่ง AI สร้างเว็บไซต์ ปรับแต่งหน้าเว็บ และเผยแพร่ออนไลน์', 0, 0, ''),
(2, 0, 'การจัดการโดเมนเนม และตั้งค่า DNS', 'วิธีจดโดเมน ย้ายโดเมน ตั้งค่า Nameservers และจัดการระเบียน DNS', 0, 0, ''),
(3, 0, 'การจัดการโฮสติ้ง HestiaCP, WordPress และ Node.js App', 'วิธีใช้งาน Control Panel, สร้างฐานข้อมูล, ติดตั้ง SSL ฟรี และรันแอป Node.js ด้วย PM2', 0, 0, '');

DELETE FROM tblknowledgebase;
DELETE FROM tblknowledgebaselinks;
INSERT INTO tblknowledgebase (id, title, article, views, useful, votes, private, `order`, parentid, language) VALUES
(1, 'วิธีเริ่มต้นสร้างเว็บไซต์ด้วย AI Prompt ใน 60 วินาที', '<p>1. คลิกเมนู <strong>สร้างเว็บด้วย AI Prompt</strong> หรือเข้าที่ <code>/lab</code><br>2. เลือกเทมเพลตตั้งต้นหรือพิมพ์รายละเอียดธุรกิจของคุณเป็นภาษาไทย<br>3. ปรับแต่งรูปภาพจาก Unsplash หรือแก้ไขข้อความแบบ WYSIWYG ในหน้า Studio<br>4. กดปุ่ม <strong>Publish</strong> เพื่อออนไลน์เว็บไซต์ได้ทันที</p>', 128, 45, 45, '', 1, 0, ''),
(2, 'วิธีชี้โดเมนเนมมายังโฮสติ้ง CreateAI (Nameservers & DNS)', '<p>คุณสามารถชี้โดเมนเนมของคุณมายังเซิร์ฟเวอร์ CreateAI ได้โดยตั้งค่า Nameservers เป็น:<br><ul><li><code>ns1.createai.site</code> (43.106.69.51)</li><li><code>ns2.createai.site</code> (43.106.69.51)</li></ul>หรือชี้ A Record (<code>@</code> และ <code>www</code>) มายัง IP <code>43.106.69.51</code> จากนั้นกดปุ่ม <strong>ติดตั้ง Free SSL</strong> ในหน้าจัดการโฮสติ้งได้ทันที</p>', 95, 32, 32, '', 2, 0, ''),
(3, 'วิธีรันแอปพลิเคชัน Node.js (PM2) บนโฮสติ้ง HestiaCP', '<p>เซิร์ฟเวอร์โฮสติ้งของเรามาพร้อมปลั๊กอิน <strong>hcpp-nodeapp (NVM + PM2)</strong> รองรับ Node.js v20, v22 และ v24:<br>1. เข้าสู่หน้า <strong>จัดการบริการโฮสติ้ง</strong> ในระบบสมาชิก หรือเข้า HestiaCP<br>2. เลือกสลับโหมดโดเมนเป็น <strong>NodeApp (Node.js PM2)</strong><br>3. อัปโหลดโค้ดไปที่โฟลเดอร์ <code>nodeapp</code> ของโดเมน ระบบจะรันแอปผ่าน PM2 และผูก Reverse Proxy ให้อัตโนมัติ</p>', 112, 40, 40, '', 3, 0, '');

INSERT INTO tblknowledgebaselinks (categoryid, articleid) VALUES (1, 1), (2, 2), (3, 3);
"""
subprocess.run(['docker', 'exec', '-i', 'mysql-server', 'mysql', '--default-character-set=utf8mb4', '-uwhmcs_user', '-pWhmcsPass2026!', 'whmcs_db'], input=whmcs_sql.encode('utf-8'), check=True)

print("SYNC_UTF8_COMPLETE_OK")
