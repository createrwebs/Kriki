<?php

namespace App\Http\Controllers\Api\Hosting;

use App\Hosting\SecurityScanner;
use App\Http\Controllers\Controller;
use App\Lab\LabProjectAccess;
use App\Lab\SiteWorkspace;
use App\Models\LabProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Security scan API.
 *
 * Adapted from Reflex's scan.py:
 *   - POST /scan → zip source, scan for vulnerabilities, report
 *   - Supports --fail-on severity threshold
 */
class ScanController extends Controller
{
    public function __construct(
        private readonly SecurityScanner $scanner,
        private readonly SiteWorkspace $workspaces,
    ) {}

    /**
     * Run a security scan on a project's source code.
     */
    public function store(Request $request, LabProject $project): JsonResponse
    {
        LabProjectAccess::assertOwner($project, $request->user());

        $request->validate([
            'fail_on' => 'nullable|string|in:critical,high,medium,low,none',
        ]);

        $failOn = $request->input('fail_on', 'low');
        $sourceRoot = $this->workspaces->root($project);

        if (! is_dir($sourceRoot)) {
            return response()->json(['message' => 'Project workspace not found.'], 404);
        }

        try {
            $result = $this->scanner->scan($project, $sourceRoot);
        } catch (\Throwable) {
            return response()->json(['message' => 'Could not scan the project workspace.'], 422);
        }

        // Determine if the scan should "fail" based on severity threshold
        $severityOrder = ['critical' => 0, 'high' => 1, 'medium' => 2, 'low' => 3];
        $threshold = $severityOrder[$failOn] ?? 3;
        $failed = $failOn !== 'none' && collect($result['violations'])
            ->contains(fn ($v) => ($severityOrder[$v['severity']] ?? 9) <= $threshold);

        return response()->json([
            'violations' => $result['violations'],
            'files_scanned' => $result['files_scanned'],
            'scanned_at' => $result['scanned_at'],
            'violation_count' => count($result['violations']),
            'failed' => $failed,
            'fail_on' => $failOn,
        ], $failed ? 422 : 200);
    }
}
