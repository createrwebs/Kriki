<?php

namespace App\Http\Controllers\Api\Hosting;

use App\Hosting\SecretManager;
use App\Http\Controllers\Controller;
use App\Lab\LabProjectAccess;
use App\Models\LabProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Secrets / environment variables API.
 *
 * Adapted from Reflex's secrets.py:
 *   - GET    /secrets       → list keys (never values)
 *   - PUT    /secrets       → update (key=value pairs or .env file)
 *   - DELETE /secrets/{key} → delete one
 *   - DELETE /secrets       → delete all
 */
class SecretController extends Controller
{
    public function __construct(
        private readonly SecretManager $secrets,
    ) {}

    /**
     * List secret keys for a project.
     * Values are NEVER exposed (Reflex pattern: "names only").
     */
    public function index(Request $request, LabProject $project): JsonResponse
    {
        LabProjectAccess::assertOwner($project, $request->user());

        $keys = $this->secrets->keys($project);

        return response()->json([
            'keys' => $keys,
            'count' => count($keys),
        ])->header('Cache-Control', 'no-store, private');
    }

    /**
     * Update secrets.
     * Accepts either JSON {env: {KEY: VALUE}} or a .env file upload.
     */
    public function update(Request $request, LabProject $project): JsonResponse
    {
        LabProjectAccess::assertWritable($project, $request->user());

        $request->validate([
            'env' => 'nullable|array',
            'env.*' => 'nullable|string|max:10000',
            'envfile' => 'nullable|file|mimes:env,txt|max:1024',
        ]);

        $vars = [];

        // .env file takes precedence (like Reflex's --envfile over --env)
        if ($request->hasFile('envfile')) {
            $content = $request->file('envfile')->get();
            $vars = $this->secrets->parseEnvFile((string) $content);
        } elseif ($request->has('env') && is_array($request->input('env'))) {
            $vars = $request->input('env');
        }

        if (empty($vars)) {
            return response()->json(['message' => 'No environment variables provided.'], 422);
        }

        $updated = $this->secrets->update($project, $vars);

        return response()->json([
            'updated' => $updated,
            'count' => count($updated),
            'message' => count($updated).' secret(s) stored. Static hosting does not inject runtime secrets.',
        ]);
    }

    /**
     * Delete a single secret by key.
     */
    public function destroy(Request $request, LabProject $project, string $key): JsonResponse
    {
        LabProjectAccess::assertWritable($project, $request->user());

        $deleted = $this->secrets->delete($project, $key);

        if (! $deleted) {
            return response()->json(['message' => "Secret '{$key}' not found."], 404);
        }

        return response()->json([
            'key' => $key,
            'deleted' => true,
            'message' => 'Stored secret deleted.',
        ]);
    }

    /**
     * Delete all secrets for a project.
     */
    public function destroyAll(Request $request, LabProject $project): JsonResponse
    {
        LabProjectAccess::assertWritable($project, $request->user());

        $count = $this->secrets->deleteAll($project);

        return response()->json([
            'deleted' => $count,
            'message' => "{$count} stored secret(s) deleted.",
        ]);
    }
}
