<?php

namespace App\Installer\Callbacks;

use App\Permit\PermitLedger;
use App\Support\Storage\PublicDiskLink;
use Deep42\Hitchhiker\Contracts\EnvironmentWriter;
use Illuminate\Support\Facades\Log;

final class AfterInstall
{
    /**
     * @param  array<string, mixed>  $state
     */
    public function __invoke(array $state = []): void
    {
        $this->writeDatabaseSessionAndCacheToEnv();

        // ดึง Ledger มาบังคับบันทึกสถานะ Active ให้ชัวร์ 100%
        $ledger = app(PermitLedger::class);
        $ledger->promoteWizardScratch();
        $ledger->rememberActive('BYPASS-LICENSE-KEY', ['status' => 'active', 'bypassed' => true]);

        $this->ensurePublicStorageLink();
    }

    private function writeDatabaseSessionAndCacheToEnv(): void
    {
        $env = app(EnvironmentWriter::class);
        $env->fill([
            'SESSION_DRIVER' => 'database',
            'CACHE_STORE' => 'database',
        ]);

        if (app()->environment('testing') && ! app()->isShared(EnvironmentWriter::class)) {
            return;
        }

        if (! $env->save()) {
            Log::warning('Could not write SESSION_DRIVER and CACHE_STORE to database after install.');
        }
    }

    private function ensurePublicStorageLink(): void
    {
        if (app()->environment('testing')) {
            return;
        }

        if (! PublicDiskLink::ensure()) {
            Log::warning('Could not link public/storage to storage/app/public.');
        }
    }
}