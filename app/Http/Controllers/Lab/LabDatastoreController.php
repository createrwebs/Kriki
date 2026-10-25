<?php

namespace App\Http\Controllers\Lab;

use App\Datastore\DatastoreKind;
use App\Datastore\DatastoreRevisor;
use App\Datastore\DatastoreSurveyor;
use App\Datastore\SupabaseLinkBroker;
use App\Http\Controllers\Controller;
use App\Models\LabProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;
use RuntimeException;

class LabDatastoreController extends Controller
{
    public function status(Request $request, SupabaseLinkBroker $broker): JsonResponse
    {
        $user = $request->user();
        if ($user === null) {
            abort(401);
        }

        return response()->json([...$broker->statusFor($user), 'scope' => 'account', 'can_revise' => false]);
    }

    public function projects(Request $request, SupabaseLinkBroker $broker): JsonResponse
    {
        $user = $request->user();
        if ($user === null) {
            abort(401);
        }

        try {
            return response()->json(['projects' => $broker->projectsFor($user)]);
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function attach(Request $request, SupabaseLinkBroker $broker): JsonResponse
    {
        $user = $request->user();
        if ($user === null) {
            abort(401);
        }

        $ref = trim((string) $request->input('project_ref', ''));
        if ($ref === '') {
            return response()->json(['message' => 'A project is required.'], 422);
        }

        try {
            $broker->attachProject($user, $ref);
        } catch (InvalidArgumentException|RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([...$broker->statusFor($user), 'scope' => 'account', 'can_revise' => false]);
    }

    public function unlink(Request $request, SupabaseLinkBroker $broker): JsonResponse
    {
        $user = $request->user();
        if ($user === null) {
            abort(401);
        }

        $broker->unlink($user);

        return response()->json([...$broker->statusFor($user), 'scope' => 'account', 'can_revise' => false]);
    }

    public function survey(Request $request, DatastoreSurveyor $surveyor): JsonResponse
    {
        $actor = $request->user();
        abort_unless($actor !== null, 401);
        abort_unless(array_diff(array_keys($request->all()), ['project_uuid', 'fresh']) === [], 422, 'Unexpected survey fields.');
        $input = $request->validate([
            'project_uuid' => ['required', 'uuid'],
            'fresh' => ['sometimes', 'boolean'],
        ]);
        $project = LabProject::query()->where('uuid', $input['project_uuid'])->where('user_id', $actor->id)->first();
        abort_unless($project !== null, 404);

        return response()->json($surveyor->forProject($actor, $project))
            ->header('Cache-Control', 'no-store');
    }

    public function revise(Request $request, DatastoreRevisor $revisor): JsonResponse
    {
        $sql = trim((string) $request->input('sql', ''));
        if ($sql === '' || mb_strlen($sql) > 40_000) {
            return response()->json(['message' => 'SQL is required (max 40 000 characters).'], 422);
        }

        $payload = $revisor->revise(
            sql: $sql,
            acknowledgeDestructive: $request->boolean('acknowledge_destructive'),
            kind: DatastoreKind::SUPABASE,
            user: $request->user(),
        );

        $status = ($payload['code'] ?? null) === 'schema_approval_required' ? 403 : ($payload['ok'] ? 200 : 422);

        return response()->json($payload, $status);
    }

    public function records(Request $request): JsonResponse
    {
        return $this->unscopedUnavailable($request);
    }

    public function auth(Request $request): JsonResponse
    {
        return $this->unscopedUnavailable($request);
    }

    public function saveAuth(Request $request): JsonResponse
    {
        return $this->unscopedUnavailable($request);
    }

    public function users(Request $request): JsonResponse
    {
        return $this->unscopedUnavailable($request);
    }

    public function addUser(Request $request): JsonResponse
    {
        return $this->unscopedUnavailable($request);
    }

    public function restart(Request $request): JsonResponse
    {
        return $this->unscopedUnavailable($request);
    }

    public function catalog(Request $request): JsonResponse
    {
        return $this->unscopedUnavailable($request);
    }

    private function unscopedUnavailable(Request $request): JsonResponse
    {
        abort_unless($request->user() !== null, 401);

        return response()->json([
            'code' => 'project_scope_required',
            'message' => 'Account-wide database administration is unavailable in Lab. Use the project Modules desk and scoped APIs. External tables and customer identity require a reviewed project mapping.',
        ], 403)->header('Cache-Control', 'no-store');
    }
}
