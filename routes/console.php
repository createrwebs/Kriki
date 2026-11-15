<?php

use App\Support\Readiness\ProductionReadiness;
use Illuminate\Support\Facades\Artisan;

Artisan::command('krikkit:readiness {--json : Render a machine-readable report} {--strict : Treat warnings as failures}', function (ProductionReadiness $readiness): int {
    $report = $readiness->inspect();

    if ($this->option('json')) {
        $this->line((string) json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    } else {
        foreach ($report['checks'] as $check) {
            $this->line(sprintf('%-7s %-30s %s', strtoupper($check['status']), $check['key'], $check['message']));
        }

        $this->newLine();
        $this->line(sprintf(
            'Summary: %d pass, %d warning, %d fail.',
            $report['summary']['pass'],
            $report['summary']['warning'],
            $report['summary']['fail'],
        ));
    }

    return ($report['summary']['fail'] > 0 || ($this->option('strict') && $report['summary']['warning'] > 0))
        ? 1
        : 0;
})->purpose('Check local configuration and production readiness gates without mutating data');

// Host Vite reap schedule removed — Lab preview is browser-only (no Node on server).
