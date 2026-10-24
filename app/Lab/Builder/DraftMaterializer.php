<?php

namespace App\Lab\Builder;

use App\Entitlement\EntitlementCatalog;
use App\Entitlement\EntitlementGate;
use App\Lab\LabProjectAccess;
use App\Lab\SiteWorkspace;
use App\Models\LabProject;
use App\Models\User;
use App\Models\WebsiteDraft;
use Illuminate\Support\Facades\DB;
use Throwable;

final class DraftMaterializer
{
    public function __construct(private readonly SiteWorkspace $workspace) {}

    public function create(User $owner, string $draftUuid, int $revision): array
    {
        $newProject = null;
        try {
            return DB::transaction(function () use ($owner, $draftUuid, $revision, &$newProject): array {
                User::whereKey($owner->id)->lockForUpdate()->firstOrFail();
                $draft = WebsiteDraft::where('user_id', $owner->id)->where('uuid', strtolower($draftUuid))->lockForUpdate()->firstOrFail();
                abort_unless($draft->revision === $revision, 409, 'Save or reload the current draft revision before creating a website project.');
                $binding = DB::table('website_draft_projects')->where('website_draft_id', $draft->id)->where('draft_revision', $revision)->first();
                if ($binding) {
                    $project = LabProject::findOrFail($binding->lab_project_id);
                    LabProjectAccess::assertOwner($project, $owner);
                    return [$project, [
                        'draft_uuid' => $draft->uuid, 'draft_revision' => $revision,
                        'blueprint_hash' => $binding->blueprint_hash, 'renderer_version' => $binding->renderer_version,
                    ], false];
                }
                app(EntitlementGate::class)->assertQuota($owner, EntitlementCatalog::PROJECTS);
                $compiled = (new BlueprintSource)->compile($draft->blueprint, $draft->uuid, $revision);
                $project = new LabProject([
                    'user_id' => $owner->id, 'title' => $draft->blueprint['title'],
                    'project_type' => 'react', 'workspace_status' => 'pending', 'stack' => SiteWorkspace::STACK,
                ]);
                $project->save();
                // A fresh UUID workspace must never alias an existing directory.
                abort_if(file_exists($this->workspace->root($project)), 409, 'The new project workspace is already occupied.');
                $newProject = $project;
                $this->workspace->commitAtomic($project, $compiled['files']);
                $project->forceFill(['workspace_status' => 'ready', 'kit_version' => SiteWorkspace::KIT_VERSION])->save();
                DB::table('website_draft_projects')->insert([
                    'website_draft_id' => $draft->id, 'draft_revision' => $revision, 'lab_project_id' => $project->id,
                    'blueprint_hash' => $compiled['manifest']['blueprint_hash'], 'renderer_version' => BlueprintSource::RENDERER_VERSION,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
                return [$project, $compiled['manifest'], true];
            });
        } catch (Throwable $error) {
            if ($newProject) $this->workspace->forget($newProject);
            throw $error;
        }
    }
}
