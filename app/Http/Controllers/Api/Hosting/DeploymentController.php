<?php

namespace App\Http\Controllers\Api\Hosting;

use App\Hosting\DeploymentManager;
use App\Http\Controllers\Controller;
use App\Lab\LabProjectAccess;
use App\Models\Deployment;
use App\Models\LabProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Deployment lifecycle API.
 * Adapted from Reflex's apps.py + cli.py.
 */
class DeploymentController extends Controller
{
    public function __construct(
        private readonly DeploymentManager $manager,
    ) {}

    /** Deploy a project artifact (multipart: artifact zip + options). */
    public function store(Request $request, LabProject $project): JsonResponse
    {
        LabProjectAccess::assertWritable($project, $request->user());

        $request->validate([
            'artifact' => 'required|file|mimes:zip|max:51200',
            'provider' => 'nullable|string|max:32',
            'region' => 'nullable|string|max:32',
            'vmtype' => 'nullable|string|max:64',
            'strategy' => 'nullable|string|in:immediate,rolling,bluegreen,canary',
            'description' => 'nullable|string|max:500',
            'hostname' => 'prohibited',
            'min_instances' => 'prohibited',
            'max_instances' => 'prohibited',
            'env' => 'prohibited',
        ]);

        $artifact = $request->file('artifact');
        $path = $artifact->store('lab/deployments', 'local');
        if (! is_string($path) || $path === '') {
            return response()->json(['message' => 'Could not store the build archive.'], 422);
        }
        $absolutePath = Storage::disk('local')->path($path);

        try {
            $deployment = $this->manager->deploy(
                $project,
                $request->user(),
                $absolutePath,
                $request->only([
                    'provider', 'region', 'vmtype', 'strategy',
                    'description',
                ]),
            );

            return response()->json([
                'deployment' => $deployment->toApiArray(),
                'message' => 'Deployment successful.',
            ], 201);
        } catch (\Throwable) {
            return response()->json(['message' => 'Deployment failed. Check publication settings and upload a supported static build.'], 422);
        } finally {
            Storage::disk('local')->delete($path);
        }
    }

    /** List deployment history (paginated). */
    public function index(Request $request, LabProject $project): JsonResponse
    {
        LabProjectAccess::assertOwner($project, $request->user());

        $perPage = max(1, min((int) $request->query('per_page', 20), 100));
        $deployments = $this->manager->history($project, $perPage);

        return response()->json([
            'data' => collect($deployments->items())->map->toApiArray()->all(),
            'meta' => [
                'current_page' => $deployments->currentPage(),
                'last_page' => $deployments->lastPage(),
                'per_page' => $deployments->perPage(),
                'total' => $deployments->total(),
            ],
        ]);
    }

    /** Inspect a single deployment. */
    public function show(Request $request, LabProject $project, Deployment $deployment): JsonResponse
    {
        LabProjectAccess::assertOwner($project, $request->user());
        abort_unless($deployment->lab_project_id === $project->id, 404);

        return response()->json(['deployment' => $deployment->toApiArray()]);
    }

    /** Rollback to a previous version. */
    public function rollback(Request $request, LabProject $project, Deployment $deployment): JsonResponse
    {
        LabProjectAccess::assertWritable($project, $request->user());
        abort_unless($deployment->lab_project_id === $project->id, 404);

        if (! $deployment->canRollback()) {
            return response()->json(['message' => 'This deployment cannot be rolled back.'], 422);
        }

        try {
            $rollback = $this->manager->rollback($deployment, $request->user());

            return response()->json([
                'deployment' => $rollback->toApiArray(),
                'message' => "Rolled back to v{$rollback->version}.",
            ]);
        } catch (\Throwable) {
            return response()->json(['message' => 'Rollback failed.'], 422);
        }
    }

    /** Fetch deployment logs. */
    public function logs(Request $request, LabProject $project, Deployment $deployment): JsonResponse
    {
        LabProjectAccess::assertOwner($project, $request->user());
        abort_unless($deployment->lab_project_id === $project->id, 404);

        $limit = max(1, min((int) $request->query('limit', 100), 1000));

        $dbLogs = $deployment->logs()
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->reverse()
            ->map->toApiArray()
            ->values()
            ->all();

        if (! empty($dbLogs)) {
            return response()->json(['logs' => $dbLogs]);
        }

        return response()->json(['logs' => $this->manager->logs($deployment, $limit)]);
    }

    /** Stop a running deployment. */
    public function stop(Request $request, LabProject $project, Deployment $deployment): JsonResponse
    {
        LabProjectAccess::assertWritable($project, $request->user());
        abort_unless($deployment->lab_project_id === $project->id, 404);

        $success = $this->manager->stop($deployment);

        return response()->json([
            'success' => $success,
            'status' => $deployment->fresh()?->status,
            'message' => $success ? 'Deployment stopped.' : 'Provider does not support stop.',
        ], $success ? 200 : 422);
    }

    /** Start a stopped deployment. */
    public function start(Request $request, LabProject $project, Deployment $deployment): JsonResponse
    {
        LabProjectAccess::assertWritable($project, $request->user());
        abort_unless($deployment->lab_project_id === $project->id, 404);

        $success = $this->manager->start($deployment);

        return response()->json([
            'success' => $success,
            'status' => $deployment->fresh()?->status,
            'message' => $success ? 'Deployment started.' : 'Provider does not support start.',
        ], $success ? 200 : 422);
    }

    /** Get the current live deployment. */
    public function current(Request $request, LabProject $project): JsonResponse
    {
        LabProjectAccess::assertOwner($project, $request->user());

        return response()->json([
            'deployment' => $this->manager->current($project)?->toApiArray(),
        ]);
    }
}
