<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Lab\LabProjectAccess;
use App\Lab\Modules\OperationLimiter;
use App\Lab\Runtime\RuntimePolicy;
use App\Models\LabOperationLease;
use App\Models\LabProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class LabOperationController extends Controller
{
    public function start(Request $request, LabProject $project, OperationLimiter $limiter, RuntimePolicy $runtime): JsonResponse
    {
        $body = $this->body($request, ['operation','operation_key']);
        LabProjectAccess::assertWritable($project, $request->user());
        abort_unless(($body->operation ?? null) === 'build' && is_string($body->operation_key ?? null) && Str::isUuid($body->operation_key), 422, 'Invalid build request.');
        abort_unless($runtime->verdict($project)['can_execute'], 423, 'This project is source-only.');
        return $this->reply($limiter->acquire($request->user(), $project, 'build', $body->operation_key, true));
    }

    public function update(Request $request, LabProject $project, string $operation, OperationLimiter $limiter): JsonResponse
    {
        LabProjectAccess::assertOwner($project, $request->user());
        $body = $this->body($request, ['state']);
        abort_unless(in_array($body->state ?? null, ['active','completed','failed'], true), 422, 'Invalid operation state.');
        $lease = LabOperationLease::query()->where('uuid', $operation)->where('user_id', $request->user()->id)->where('lab_project_id', $project->id)->where('operation', 'build')->firstOrFail();
        if ($body->state === 'active') $lease = $limiter->renew($request->user(), $project, $lease);
        else { $limiter->finalize($request->user(), $project, $lease, $body->state === 'completed'); $lease->refresh(); }
        return $this->reply($lease);
    }

    private function body(Request $request, array $keys): \stdClass
    {
        abort_unless($request->isJson() && strlen($request->getContent()) <= 1024, 422, 'A small JSON object is required.');
        try { $body = json_decode($request->getContent(), false, 8, JSON_THROW_ON_ERROR); }
        catch (\JsonException) { abort(422); }
        abort_unless($body instanceof \stdClass && array_diff(array_keys((array) $body), $keys) === [], 422, 'Unexpected operation fields.');
        return $body;
    }

    private function reply(LabOperationLease $lease): JsonResponse
    {
        return response()->json(['uuid' => $lease->uuid, 'state' => $lease->status, 'expires_at' => $lease->expires_at->toIso8601String()], 200, ['Cache-Control' => 'private, no-store']);
    }
}
