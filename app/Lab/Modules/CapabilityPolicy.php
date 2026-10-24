<?php

namespace App\Lab\Modules;

use App\Entitlement\EntitlementGate;
use App\Datastore\SupabaseLinkBroker;
use App\Lab\LabProjectAccess;
use App\Models\LabModuleInstallation;
use App\Models\LabProject;
use App\Models\LabProjectCapability;
use App\Models\User;

final class CapabilityPolicy
{
    public function __construct(private readonly EntitlementGate $entitlements, private readonly SupabaseLinkBroker $broker) {}

    public function assertSelection(User $actor, LabProject $project, string $key): void
    {
        LabProjectAccess::assertWritable($project, $actor);
        $this->entitlements->assertFeature($actor, ModuleCatalog::get($key)['feature']);
    }

    public function assertAllowed(User $actor, LabProject $project, string $operation, ?LabModuleInstallation $module = null): void
    {
        LabProjectAccess::assertOwner($project, $actor);
        if ($module !== null) {
            abort_unless((int) $module->user_id === (int) $actor->id && (int) $module->lab_project_id === (int) $project->id, 404);
        }
        if ($operation === 'history') return;
        abort_unless($module !== null && in_array($operation, ['prepare', 'activate', 'mutate'], true), 403, 'Unsupported module operation.');
        $this->assertSelection($actor, $project, $module->key);
        abort_unless(LabProjectCapability::query()->where('lab_project_id', $project->id)->where('key', $module->key)->where('enabled', true)->exists(), 403, 'Enable this module for the selected project first.');
        $this->assertDependencies($actor, $project, $module->key, $module->supabase_project_ref);
        if ($operation === 'prepare') return;
        $catalog = ModuleCatalog::get($module->key);
        abort_unless($module->status === 'ready' && $module->schema_version === $catalog['schema_version'], 409, 'Apply and verify this module schema first.');
        $plane = $this->broker->planeFor($actor);
        abort_unless(($plane['ready'] ?? false) && ($plane['project_ref'] ?? null) === $module->supabase_project_ref
            && filled($plane['steward_token'] ?? null), 503, 'Reconnect the original Supabase project for this module.');
        abort_unless(config('lab-modules.business_writes_enabled') === true, 423, 'Managed database widgets are awaiting isolation and storage readiness checks.');
        if ($operation === 'mutate') abort_unless($module->enabled, 409, 'This module is disabled.');
    }

    public function assertDependencies(User $actor, LabProject $project, string $key, ?string $projectRef = null): void
    {
        foreach (ModuleCatalog::get($key)['dependencies'] as $dependency) {
            $this->entitlements->assertFeature($actor, ModuleCatalog::get($dependency)['feature']);
            $required = LabModuleInstallation::query()->where('lab_project_id', $project->id)->where('key', $dependency)->first();
            abort_unless($required !== null && $required->enabled && $required->status === 'ready'
                && $required->schema_version === ModuleCatalog::get($dependency)['schema_version'], 409, 'A required module is not ready.');
            if ($projectRef !== null) abort_unless($required->supabase_project_ref === $projectRef, 409, 'Required modules must use the same pinned Supabase project.');
            abort_unless(LabProjectCapability::query()->where('lab_project_id', $project->id)->where('key', $dependency)->where('enabled', true)->exists(), 409, 'A required project capability is disabled.');
            $this->assertDependencies($actor, $project, $dependency, $projectRef);
        }
    }
}
