import subprocess

# 1. Link all domain TLDs in tbldomainpricing to 'enom' registrar
sql = """SET NAMES utf8mb4;
UPDATE tbldomainpricing SET autoreg = 'enom';
SELECT id, extension, autoreg FROM tbldomainpricing;
"""
res = subprocess.run(['docker', 'exec', '-i', 'mysql-server', 'mysql', '--default-character-set=utf8mb4', '-uwhmcs_user', '-pWhmcsPass2026!', 'whmcs_db'], input=sql.encode('utf-8'), capture_output=True)
print("=== DOMAIN PRICING AUTOREG ===")
print(res.stdout.decode('utf-8', errors='ignore'))

# 2. Write upgraded WHMCS banktransfer.php & promptpay.php with EMVCo Thai QR PromptPay (0639304353)
gateway_code = r"""<?php
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function banktransfer_config()
{
    return [
        "FriendlyName" => [
            "Type" => "System",
            "Value" => "โอนเงินผ่านธนาคาร / สแกน QR พร้อมเพย์ (PromptPay)",
        ],
        "instructions" => [
            "FriendlyName" => "Bank Transfer Instructions",
            "Type" => "textarea",
            "Rows" => "5",
            "Value" => "ธนาคาร กรุงไทย เลขที่บัญชี 687-0-065918\nชื่อบัญชี นพนันท์ ธีระชาติ\nหรือ PromptPay 0639304353",
            "Description" => "ข้อมูลบัญชีธนาคารและพร้อมเพย์ที่แสดงในใบแจ้งหนี้",
        ],
        "promptpay_id" => [
            "FriendlyName" => "PromptPay Phone / ID",
            "Type" => "text",
            "Size" => "20",
            "Default" => "0639304353",
            "Description" => "เบอร์โทรศัพท์หรือเลขประจำตัวที่ผูก PromptPay",
        ],
        "account_name" => [
            "FriendlyName" => "Account Name",
            "Type" => "text",
            "Size" => "40",
            "Default" => "นพนันท์ ธีระชาติ",
        ],
    ];
}

function cai_promptpay_tlv($id, $val)
{
    return $id . str_pad((string)strlen($val), 2, '0', STR_PAD_LEFT) . $val;
}

function cai_promptpay_crc16($data)
{
    $crc = 0xFFFF;
    $len = strlen($data);
    for ($i = 0; $i < $len; $i++) {
        $x = (($crc >> 8) ^ ord($data[$i])) & 0xFF;
        $x ^= $x >> 4;
        $crc = (($crc << 8) ^ ($x << 12) ^ ($x << 5) ^ $x) & 0xFFFF;
    }
    return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
}

function cai_promptpay_payload($phoneOrId, $amount)
{
    $digits = preg_replace('/\D+/', '', (string)$phoneOrId);
    if (strlen($digits) === 10 && substr($digits, 0, 1) === '0') {
        $subTag = cai_promptpay_tlv('01', '0066' . substr($digits, 1));
    } elseif (strlen($digits) === 13) {
        $subTag = cai_promptpay_tlv('02', $digits);
    } else {
        $subTag = cai_promptpay_tlv('01', '0066639304353');
    }
    $merchant = cai_promptpay_tlv('00', 'A000000677010111') . $subTag;
    $amtFloat = (float)$amount;
    $parts = [
        cai_promptpay_tlv('00', '01'),
        cai_promptpay_tlv('01', $amtFloat > 0 ? '12' : '11'),
        cai_promptpay_tlv('29', $merchant),
        cai_promptpay_tlv('58', 'TH'),
        cai_promptpay_tlv('53', '764'),
    ];
    if ($amtFloat > 0) {
        $parts[] = cai_promptpay_tlv('54', number_format($amtFloat, 2, '.', ''));
    }
    $raw = implode('', $parts) . '6304';
    return $raw . cai_promptpay_crc16($raw);
}

function banktransfer_link($params)
{
    $ppId = !empty($params['promptpay_id']) ? preg_replace('/\D+/', '', $params['promptpay_id']) : '0639304353';
    if ($ppId === '') {
        $ppId = '0639304353';
    }
    $accName = !empty($params['account_name']) ? $params['account_name'] : 'นพนันท์ ธีระชาติ';
    $amount = isset($params['amount']) ? number_format((float)$params['amount'], 2, '.', '') : '0.00';
    $invoiceId = (int)($params['invoiceid'] ?? 0);
    $payload = cai_promptpay_payload($ppId, $amount);
    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&margin=8&data=' . rawurlencode($payload);
    $fallbackQrUrl = 'https://promptpay.io/' . rawurlencode($ppId) . '/' . rawurlencode($amount) . '.png';

    $instructions = nl2br(htmlspecialchars((string)($params['instructions'] ?? "ธนาคาร กรุงไทย เลขที่บัญชี 687-0-065918\nชื่อบัญชี นพนันท์ ธีระชาติ\nหรือ PromptPay 0639304353")));

    $html = '<div style="max-width:380px; margin:12px auto; background:#ffffff; color:#0f172a; border-radius:14px; border:2px solid #1e3a8a; overflow:hidden; box-shadow:0 10px 25px rgba(0,0,0,0.12); font-family:\'Prompt\',\'Inter\',sans-serif; text-align:center;">'
        . '<div style="background:#113566; color:#ffffff; padding:12px 16px; font-weight:700; font-size:15px; letter-spacing:0.3px;">'
        . '🇹🇭 THAI QR PAYMENT · พร้อมเพย์ (PromptPay)'
        . '</div>'
        . '<div style="padding:18px 16px;">'
        . '<img src="' . htmlspecialchars($qrUrl) . '" onerror="this.onerror=null;this.src=\'' . htmlspecialchars($fallbackQrUrl) . '\';" alt="PromptPay QR Code" width="200" height="200" style="display:block; margin:0 auto 12px; border:1px solid #e2e8f0; border-radius:10px; padding:6px; background:#fff;" />'
        . '<div style="font-size:18px; font-weight:800; color:#113566; margin-bottom:4px;">ยอดชำระ: ฿' . htmlspecialchars($amount) . ' THB</div>'
        . '<div style="font-size:13.5px; font-weight:600; color:#1e293b;">พร้อมเพย์: <span style="color:#0284c7;">063-930-4353</span> (' . htmlspecialchars($accName) . ')</div>'
        . '<div style="margin-top:12px; padding-top:12px; border-top:1px dashed #cbd5e1; font-size:12.5px; color:#475569; line-height:1.6; text-align:left;">'
        . $instructions
        . '<br><strong>อ้างอิงใบแจ้งหนี้ (Ref):</strong> #' . $invoiceId
        . '</div>'
        . '</div>'
        . '</div>';

    return $html;
}
"""

open('/opt/webstack/whmcs/modules/gateways/banktransfer.php', 'w', encoding='utf-8').write(gateway_code)

# Also create dedicated promptpay.php gateway module in WHMCS
promptpay_module = gateway_code.replace('function banktransfer_config()', 'function promptpay_config()').replace('function banktransfer_link($params)', 'function promptpay_link($params)')
open('/opt/webstack/whmcs/modules/gateways/promptpay.php', 'w', encoding='utf-8').write(promptpay_module)

print("PROMPTPAY_AND_ENOM_CONFIGURED_OK")
