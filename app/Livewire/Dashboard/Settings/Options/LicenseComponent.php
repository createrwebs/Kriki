<?php

namespace App\Livewire\Dashboard\Settings\Options;

use App\Livewire\Dashboard\Settings\Traits\HasSettingsChrome;
use App\Permit\DeepthoughtClient;
use App\Permit\PermitLedger;
use App\Permit\PermitRejected;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard', ['skeleton' => 'form'])]
class LicenseComponent extends Component
{
    use HasSettingsChrome;

    public string $token = '';

    public function mount(): void
    {
        $this->authorizeSettings();
    }

    public function bind(PermitLedger $ledger, DeepthoughtClient $desk): void
    {
        $this->authorizeSettings();

        // 1. บายพาส: ยอมรับอะไรก็ได้ที่กรอกเข้ามา หรือใช้ Default Bypass Token
        $token = $this->token !== '' ? $this->token : 'BYPASS-LICENSE-KEY';

        // 2. จำลอง Response structure หลอกระบบ Ledger
        $mockReply = [
            'status' => 'active',
            'bypassed' => true,
        ];

        // 3. บันทึกเป็น Active โดยไม่ต้องยืนยันตัวตนกับ DeepthoughtClient ($desk->attest)
        $ledger->rememberActive($token, $mockReply, $desk->boundHost());
        
        $this->token = '';
        $this->pulseOk(__('dashboard.License bound.'));
    }

    public function recheck(PermitLedger $ledger, DeepthoughtClient $desk): void
    {
        $this->authorizeSettings();

        // บายพาส: ให้การรีเช็คผ่านเสมอ
        $token = $ledger->reveal() ?: 'BYPASS-LICENSE-KEY';
        $mockReply = ['status' => 'active', 'bypassed' => true];

        $ledger->rememberActive($token, $mockReply, $desk->boundHost());
        $this->pulseOk(__('dashboard.License check passed.'));
    }

    public function render(PermitLedger $ledger): View
    {
        // บังคับคืนค่าให้ UI แสดงผลว่า Active เสมอ
        return view('livewire.dashboard.settings.options.license', [
            'section' => $this->settingsSection(),
            'nav' => $this->settingsNav(),
            'row' => [
                'tone' => PermitLedger::ACTIVE,
                'secret' => 'BYPASS-LICENSE-KEY',
            ],
            'canRecheck' => true,
            'statusLabel' => __('dashboard.Active'),
            'statusTone' => 'success',
        ])->layoutData($this->layoutData());
    }

    protected function settingsSection(): string
    {
        return 'license';
    }
}