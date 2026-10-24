<?php

namespace App\Lab\Runtime;

use App\Lab\SiteWorkspace;
use App\Models\LabProject;

final class RuntimePolicy
{
    /**
     * Execution availability only, not a certification of browser-origin isolation.
     * No configuration flag may enable customer PHP inside the Laravel process.
     *
     * @return array{mode: string, can_execute: bool, reason: ?string}
     */
    public function verdict(LabProject $project): array
    {
        if (($project->project_type ?: 'react') === 'react' && $project->stack !== SiteWorkspace::PHP_STACK) {
            return ['mode' => 'browser_static', 'can_execute' => true, 'reason' => null];
        }

        return [
            'mode' => 'source_only',
            'can_execute' => false,
            'reason' => 'โปรเจกต์นี้ยังแก้ไขไฟล์ได้ แต่ยังไม่เปิดให้รันโค้ดฝั่งเซิร์ฟเวอร์จนกว่าจะมีระบบรันที่แยกจากแพลตฟอร์มอย่างปลอดภัย',
        ];
    }
}
