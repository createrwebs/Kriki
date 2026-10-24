<?php

namespace App\Lab\Modules;

use App\Datastore\SupabaseLinkBroker;
use App\Entitlement\EntitlementGate;
use App\Lab\LabProjectAccess;
use App\Models\LabForm;
use App\Models\LabModuleInstallation;
use App\Models\LabProject;
use App\Models\LabProjectCapability;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

final class ModuleInstallations
{
    public function __construct(private readonly CapabilityPolicy $policy, private readonly SupabaseLinkBroker $broker, private readonly EntitlementGate $entitlements) {}

    public function listing(User $actor, LabProject $project): array
    {
        LabProjectAccess::assertOwner($project, $actor);
        $selected = LabProjectCapability::query()->where('lab_project_id', $project->id)->pluck('enabled', 'key');
        $installations = LabModuleInstallation::query()->where('lab_project_id', $project->id)->where('user_id', $actor->id)->get()->keyBy('key');
        $modules = [];
        foreach (array_keys(ModuleCatalog::all()) as $key) {
            $definition = ModuleCatalog::get($key);
            $modules[] = [...$definition, 'entitled' => $this->entitlements->allows($actor, $definition['feature']),
                'selected' => (bool) ($selected[$key] ?? false),
                'installation' => isset($installations[$key]) ? $this->payload($installations[$key]) : null,
            ];
        }
        $legacy = Schema::hasTable('lab_forms') ? LabForm::query()->where('user_id', $actor->id)->where('lab_project_id', $project->id)->count() : 0;
        return ['revision' => (int) $project->fresh()->modules_revision, 'modules' => $modules,
            'business_writes_ready' => config('lab-modules.business_writes_enabled') === true,
            'legacy_booking_forms' => $legacy,
        ];
    }

    public function select(User $actor, LabProject $project, string $key, bool $enabled, int $expectedRevision): int
    {
        ModuleCatalog::get($key);
        return DB::transaction(function () use ($actor, $project, $key, $enabled, $expectedRevision) {
            $locked = LabProject::query()->whereKey($project->id)->lockForUpdate()->firstOrFail();
            LabProjectAccess::assertWritable($locked, $actor);
            if ($enabled) $this->policy->assertSelection($actor, $locked, $key);
            abort_unless((int) $locked->modules_revision === $expectedRevision, 409, 'Project modules changed. Reload before saving.');
            $capability = LabProjectCapability::query()->firstOrNew(['lab_project_id' => $locked->id, 'key' => $key]);
            if ($capability->exists && $capability->enabled === $enabled) return (int) $locked->modules_revision;
            $capability->enabled = $enabled;
            $capability->save();
            $locked->modules_revision = $expectedRevision + 1;
            $locked->save();
            return $expectedRevision + 1;
        });
    }

    /** Prepare control metadata only. Remote schema installation is a separate approved action. */
    public function prepare(User $actor, LabProject $project, string $key, int $expectedRevision, array $config, string $idempotencyKey): array
    {
        $definition = ModuleCatalog::get($key);
        abort_if($definition['legacy'], 422, 'Manage existing bookings in the booking desk.');
        abort_unless($config === [] && Str::isUuid($idempotencyKey) && $expectedRevision >= 0, 422, 'Invalid module preparation.');
        $hash = hash('sha256', json_encode(['key' => $key, 'expected_revision' => $expectedRevision, 'config' => (object) $config], JSON_THROW_ON_ERROR));
        return DB::transaction(function () use ($actor, $project, $key, $expectedRevision, $config, $idempotencyKey, $hash) {
            $locked = LabProject::query()->whereKey($project->id)->lockForUpdate()->firstOrFail();
            LabProjectAccess::assertOwner($locked, $actor);
            $replay = LabModuleInstallation::query()->where('lab_project_id', $locked->id)->where('idempotency_key', $idempotencyKey)->first();
            if ($replay !== null) {
                abort_unless((int) $replay->user_id === (int) $actor->id && hash_equals($replay->request_hash, $hash), 409, 'This module request conflicts with a saved operation.');
                return ['installation' => $replay, 'created' => false, 'project_revision' => (int) $locked->modules_revision];
            }
            abort_if(LabModuleInstallation::query()->where('lab_project_id', $locked->id)->where('key', $key)->exists(), 409, 'This module is already prepared.');
            abort_unless((int) $locked->modules_revision === $expectedRevision, 409, 'Project modules changed. Reload before preparing.');
            $module = new LabModuleInstallation(['uuid' => (string) Str::uuid(), 'user_id' => $actor->id,
                'lab_project_id' => $locked->id, 'key' => $key, 'revision' => 1, 'schema_version' => 0,
                'status' => 'prepared', 'enabled' => false, 'config' => $config,
                'idempotency_key' => $idempotencyKey, 'request_hash' => $hash,
            ]);
            $this->policy->assertAllowed($actor, $locked, 'prepare', $module);
            $plane = $this->broker->planeFor($actor);
            abort_unless(($plane['ready'] ?? false) && filled($plane['project_ref'] ?? null) && filled($plane['steward_token'] ?? null), 422, 'Connect and select the Supabase project for this module first.');
            $this->policy->assertDependencies($actor, $locked, $key, $plane['project_ref']);
            $module->supabase_project_ref = $plane['project_ref'];
            $module->save();
            $locked->modules_revision = $expectedRevision + 1;
            $locked->save();
            return ['installation' => $module, 'created' => true, 'project_revision' => $expectedRevision + 1];
        });
    }

    public function setState(User $actor, LabProject $project, LabModuleInstallation $module, string $state, int $expectedRevision): LabModuleInstallation
    {
        abort_unless(in_array($state, ['enabled', 'disabled'], true), 422, 'Invalid module state.');
        return DB::transaction(function () use ($actor, $project, $module, $state, $expectedRevision) {
            $lockedProject = LabProject::query()->whereKey($project->id)->lockForUpdate()->firstOrFail();
            $locked = LabModuleInstallation::query()->whereKey($module->id)->lockForUpdate()->firstOrFail();
            $this->policy->assertAllowed($actor, $lockedProject, 'history', $locked);
            LabProjectAccess::assertWritable($lockedProject, $actor);
            abort_unless($locked->revision === $expectedRevision, 409, 'This module changed. Reload before saving.');
            if ($state === 'enabled') {
                $this->policy->assertAllowed($actor, $lockedProject, 'activate', $locked);
            }
            $next = $state === 'enabled';
            if ($locked->enabled === $next) return $locked;
            $locked->enabled = $next;
            $locked->revision++;
            $locked->save();
            $lockedProject->modules_revision = (int) $lockedProject->modules_revision + 1;
            $lockedProject->save();
            return $locked;
        });
    }

    public function plane(LabModuleInstallation $module): array
    {
        abort_unless($module->owner !== null, 503, 'Module owner is unavailable.');
        $plane = $this->broker->planeFor($module->owner);
        abort_unless(($plane['ready'] ?? false) && ($plane['project_ref'] ?? null) === $module->supabase_project_ref
            && filled($plane['steward_token'] ?? null), 503, 'Reconnect the original Supabase project for this module.');
        return $plane;
    }

    public function payload(LabModuleInstallation $module): array
    {
        return ['uuid' => $module->uuid, 'key' => $module->key, 'revision' => $module->revision,
            'schema_version' => $module->schema_version, 'status' => $module->status, 'enabled' => $module->enabled,
            'project_ref' => $module->supabase_project_ref,
        ];
    }
}
