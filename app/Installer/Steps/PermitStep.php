<?php

declare(strict_types=1);

namespace App\Installer\Steps;

use App\Permit\DeepthoughtClient;
use App\Permit\PermitLedger;
use App\Support\Ui\Pulse;
use Deep42\Hitchhiker\Contracts\InstallerStep;

final class PermitStep implements InstallerStep
{
    public function id(): string
    {
        return 'permit';
    }

    public function label(): string
    {
        return __('dashboard.License');
    }

    public function view(): string
    {
        return 'installer.permit.permit';
    }

    public function isSkipped(): bool
    {
        // สั่งข้าม Step นี้จาก UI หน้า Installer ไปเลยก็ได้ (ถ้าคืนค่า true)
        // แต่ถ้าอยากให้แสดงหน้าให้กด Next ผ่านไปเฉยๆ ให้คงไว้เป็น false
        return false; 
    }

    public function validate(array $data = []): bool
    {
        return true;
    }

    public function process(array $data = []): void
    {
        $ledger = app(PermitLedger::class);

        // กำหนด Token ปลอมไว้กรณีไม่ได้กรอก
        $token = trim((string) ($data['permit_token'] ?? 'BYPASS-LICENSE-KEY'));
        if ($token === '') {
            $token = 'BYPASS-LICENSE-KEY';
        }

        // Mock Reply หลอกระบบ Ledger
        $mockReply = [
            'status' => 'active',
            'bypassed' => true,
        ];

        // บันทึกสถานะเป็น Active ทันทีโดยไม่ต้องยิง DeepthoughtClient::attest()
        $ledger->rememberActive($token, $mockReply);

        session()->now('installer.pulse', Pulse::craft(
            (string) __('dashboard.License bound.'),
            __('dashboard.Saved'),
            'ok',
        ));
    }
}