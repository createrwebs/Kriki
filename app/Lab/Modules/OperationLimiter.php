<?php

namespace App\Lab\Modules;

use App\Lab\LabProjectAccess;
use App\Models\LabOperationLease;
use App\Models\LabProject;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class OperationLimiter
{
    public function acquire(User $actor, LabProject $project, string $operation, string $key, bool $exclusive = false): LabOperationLease
    {
        abort_unless(in_array($operation, ['build', 'import', 'schema_apply'], true) && Str::isUuid($key), 422, 'Invalid operation lease.');
        return DB::transaction(function () use ($actor, $project, $operation, $key, $exclusive) {
            User::query()->whereKey($actor->id)->lockForUpdate()->firstOrFail();
            $locked = LabProject::query()->whereKey($project->id)->lockForUpdate()->firstOrFail();
            LabProjectAccess::assertWritable($locked, $actor);
            $existing = LabOperationLease::query()->where('lab_project_id', $locked->id)->where('operation', $operation)->where('operation_key', $key)->first();
            if ($existing !== null && ($existing->status === 'completed' || ($existing->status === 'active' && $existing->expires_at->isFuture()))) {
                abort_unless((int) $existing->user_id === (int) $actor->id, 403);
                abort_if($exclusive, 409, 'This operation has already started.');
                return $existing;
            }
            $active = LabOperationLease::query()->where('status', 'active')->where('expires_at', '>', now());
            $count = $operation === 'schema_apply'
                ? $active->where('lab_project_id', $locked->id)->where('operation', 'schema_apply')->count()
                : $active->where('user_id', $actor->id)->whereIn('operation', ['build', 'import'])->count();
            abort_if($count >= ($operation === 'schema_apply' ? 1 : 2), 429, 'Another operation is still running. Try again when it finishes.');
            $lease = $existing ?? new LabOperationLease(['uuid' => (string) Str::uuid(), 'user_id' => $actor->id, 'lab_project_id' => $locked->id, 'operation' => $operation, 'operation_key' => $key]);
            if ($existing !== null) $lease->uuid = (string) Str::uuid();
            $lease->status = 'active'; $lease->expires_at = now()->addMinutes(5); $lease->save();
            return $lease;
        });
    }

    public function renew(User $actor, LabProject $project, LabOperationLease $lease): LabOperationLease
    {
        return DB::transaction(function () use ($actor, $project, $lease) {
            User::query()->whereKey($actor->id)->lockForUpdate()->firstOrFail();
            $project = LabProject::query()->whereKey($project->id)->lockForUpdate()->firstOrFail();
            LabProjectAccess::assertWritable($project, $actor);
            $locked = LabOperationLease::query()->whereKey($lease->id)->lockForUpdate()->firstOrFail();
            abort_unless((int) $locked->user_id === (int) $actor->id && (int) $locked->lab_project_id === (int) $project->id, 404);
            abort_unless($locked->uuid === $lease->uuid && $locked->status === 'active' && $locked->expires_at->isFuture(), 409, 'This operation lease has expired.');
            $locked->expires_at = now()->addMinutes(5); $locked->save();
            return $locked;
        });
    }

    /** Called inside the workspace promotion transaction, after acquiring its filesystem lock. */
    public function assertImportPromotion(User $actor, LabProject $project, LabOperationLease $lease): void
    {
        if (DB::transactionLevel() < 1) throw new \LogicException('Import promotion requires a transaction.');
        User::query()->whereKey($actor->id)->lockForUpdate()->firstOrFail();
        $currentProject = LabProject::query()->whereKey($project->id)->lockForUpdate()->firstOrFail();
        LabProjectAccess::assertWritable($currentProject, $actor);
        $current = LabOperationLease::query()->whereKey($lease->id)->lockForUpdate()->firstOrFail();
        abort_unless((int) $current->user_id === (int) $actor->id && (int) $current->lab_project_id === (int) $currentProject->id
            && $current->operation === 'import', 404);
        abort_unless($current->uuid === $lease->uuid && $current->status === 'active' && $current->expires_at->isFuture(), 409, 'Import admission expired or was cancelled. Retry after reviewing the current source.');
    }

    public function finalize(User $actor, LabProject $project, LabOperationLease $lease, bool $successful): void
    {
        LabProjectAccess::assertOwner($project, $actor);
        abort_unless((int) $lease->user_id === (int) $actor->id && (int) $lease->lab_project_id === (int) $project->id, 404);
        LabOperationLease::query()->whereKey($lease->id)->where('uuid', $lease->uuid)->where('status', 'active')->update(['status' => $successful ? 'completed' : 'failed', 'updated_at' => now()]);
    }
}
