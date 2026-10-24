<?php

namespace App\Lab\Modules;

use App\Lab\LabProjectAccess;
use App\Models\LabChangeProposal;
use App\Models\LabModuleInstallation;
use App\Models\LabProject;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class SchemaProposals
{
    public function __construct(private readonly CapabilityPolicy $policy, private readonly ModuleInstallations $modules,
        private readonly SupabaseModuleInstaller $installer, private readonly OperationLimiter $limiter) {}

    public function prepare(User $actor, LabProject $project, LabModuleInstallation $module, int $expectedRevision): LabChangeProposal
    {
        return DB::transaction(function () use ($actor, $project, $module, $expectedRevision) {
            $project = LabProject::query()->whereKey($project->id)->lockForUpdate()->firstOrFail();
            $module = LabModuleInstallation::query()->whereKey($module->id)->lockForUpdate()->firstOrFail();
            $this->policy->assertAllowed($actor, $project, 'prepare', $module);
            $this->modules->plane($module);
            abort_unless($module->revision === $expectedRevision, 409, 'This module changed. Prepare a new proposal.');
            $payload = ModuleSchema::payload($project, $module);
            return LabChangeProposal::create(['uuid' => (string) Str::uuid(), 'user_id' => $actor->id, 'lab_project_id' => $project->id,
                'lab_module_installation_id' => $module->id, 'kind' => 'install_module', 'base_version' => $module->revision,
                'payload' => $payload, 'impact_hash' => ModuleSchema::hash($payload), 'expires_at' => now()->addMinutes(15), 'state' => 'prepared']);
        });
    }

    public function detail(User $actor, LabProject $project, LabChangeProposal $proposal): array
    {
        $this->assertOwner($actor, $project, $proposal);
        return ['uuid' => $proposal->uuid, 'state' => $proposal->state, 'expected_revision' => $proposal->base_version,
            'impact_hash' => $proposal->impact_hash, 'expires_at' => $proposal->expires_at->toIso8601String(),
            'impact' => array_diff_key($proposal->payload, ['sql' => true]), 'sql' => $proposal->payload['sql'], 'receipt' => $proposal->receipt];
    }

    public function cancel(User $actor, LabProject $project, LabChangeProposal $proposal): array
    {
        return DB::transaction(function () use ($actor, $project, $proposal) {
            $proposal = LabChangeProposal::query()->whereKey($proposal->id)->lockForUpdate()->firstOrFail();
            $this->assertOwner($actor, $project, $proposal);
            abort_unless(in_array($proposal->state, ['prepared', 'cancelled'], true), 409, 'This proposal has already been applied.');
            $proposal->state = 'cancelled'; $proposal->save();
            return $this->detail($actor, $project, $proposal);
        });
    }

    public function apply(User $actor, LabProject $project, LabChangeProposal $proposal, string $impactHash, int $expectedRevision): array
    {
        $this->assertOwner($actor, $project, $proposal);
        abort_unless(hash_equals($proposal->impact_hash, $impactHash) && $proposal->base_version === $expectedRevision, 409, 'Approval does not match this proposal.');
        // Read-only replay remains available after downgrade or freezing.
        if ($proposal->state === 'applied') return $proposal->receipt;
        $lease = $this->limiter->acquire($actor, $project, 'schema_apply', $proposal->uuid);
        $successful = false;
        try {
            $receipt = DB::transaction(function () use ($actor, $project, $proposal, $impactHash, $expectedRevision) {
                User::query()->whereKey($actor->id)->lockForUpdate()->firstOrFail();
                $project = LabProject::query()->whereKey($project->id)->lockForUpdate()->firstOrFail();
                $proposal = LabChangeProposal::query()->whereKey($proposal->id)->lockForUpdate()->firstOrFail();
                $this->assertOwner($actor, $project, $proposal);
                abort_unless(hash_equals($proposal->impact_hash, $impactHash) && $proposal->base_version === $expectedRevision, 409, 'Approval does not match this proposal.');
                if ($proposal->state === 'applied') return $proposal->receipt;
                abort_unless($proposal->state === 'prepared' && $proposal->expires_at->isFuture(), 409, 'This proposal is cancelled or expired. Prepare a new proposal.');
                $module = LabModuleInstallation::query()->whereKey($proposal->lab_module_installation_id)->lockForUpdate()->firstOrFail();
                $this->policy->assertAllowed($actor, $project, 'prepare', $module);
                $this->modules->plane($module);
                abort_unless($module->revision === $expectedRevision && hash_equals($proposal->impact_hash, ModuleSchema::hash(ModuleSchema::payload($project, $module)))
                    && hash_equals($proposal->impact_hash, ModuleSchema::hash($proposal->payload)), 409, 'This module or schema changed. Prepare a new proposal.');
                $receipt = $this->installer->install($project, $module);
                $module->forceFill(['schema_version' => $receipt['schema_version'], 'status' => 'ready', 'enabled' => false, 'revision' => $module->revision + 1])->save();
                $project->modules_revision = (int) $project->modules_revision + 1; $project->save();
                $receipt = [...$receipt, 'proposal_uuid' => $proposal->uuid, 'revision' => $module->revision, 'applied_at' => now()->toIso8601String()];
                $proposal->forceFill(['state' => 'applied', 'receipt' => $receipt, 'applied_at' => now()])->save();
                return $receipt;
            });
            $successful = true;
            return $receipt;
        } finally { $this->limiter->finalize($actor, $project, $lease, $successful); }
    }

    private function assertOwner(User $actor, LabProject $project, LabChangeProposal $proposal): void
    {
        LabProjectAccess::assertOwner($project, $actor);
        abort_unless((int) $proposal->user_id === (int) $actor->id && (int) $proposal->lab_project_id === (int) $project->id && $proposal->kind === 'install_module', 404);
    }
}
