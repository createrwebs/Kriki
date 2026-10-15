<?php

namespace App\Lab\Forms;

use App\Datastore\SupabaseLinkBroker;
use App\Lab\LabProjectAccess;
use App\Models\LabForm;
use App\Models\LabFormVersion;
use App\Models\LabProject;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

final class FormDefinitions
{
    public function __construct(private readonly FormDefinition $rules, private readonly SupabaseLinkBroker $broker) {}

    public function assertAccess(User $owner, LabProject $project, LabForm $form, bool $writable = false): void
    {
        $writable ? LabProjectAccess::assertWritable($project, $owner) : LabProjectAccess::assertOwner($project, $owner);
        if ((int) $form->user_id !== (int) $owner->id || (int) $form->lab_project_id !== (int) $project->id) {
            throw new AuthorizationException('This form does not belong to the selected project.');
        }
    }

    public function create(User $owner, LabProject $project, string $title): LabForm
    {
        LabProjectAccess::assertWritable($project, $owner);
        Validator::make(['title' => $title], ['title' => ['required', 'string', 'max:120', 'not_regex:/<[^>]*>/']])->validate();
        $plane = $this->broker->planeFor($owner);
        abort_unless($plane['ready'] && $plane['project_ref'] !== '', 422, 'Connect and select a Supabase project first.');

        return DB::transaction(function () use ($owner, $project, $title, $plane) {
            $form = LabForm::query()->create([
                'uuid' => (string) Str::uuid(), 'user_id' => $owner->id, 'lab_project_id' => $project->id,
                'supabase_project_ref' => $plane['project_ref'], 'template' => 'table_booking',
                'title' => trim($title), 'status' => 'draft',
            ]);
            $form->versions()->create(['version' => 1, 'definition' => BookingTemplate::definition()]);
            return $form;
        });
    }

    public function detail(User $owner, LabProject $project, LabForm $form): array
    {
        $this->assertAccess($owner, $project, $form);
        return $this->payload($form);
    }

    public function payload(LabForm $form): array
    {
        $latest = $form->versions()->reorder()->orderByDesc('version')->firstOrFail();
        return [
            'uuid' => $form->uuid, 'title' => $form->title, 'status' => $form->status,
            'active_version' => $form->active_version, 'latest_version' => $latest->version,
            'definition' => $latest->definition, 'project_ref' => $form->supabase_project_ref,
        ];
    }

    public function saveVersion(User $owner, LabProject $project, LabForm $form, int $expectedVersion, array $definition): LabFormVersion
    {
        $this->assertAccess($owner, $project, $form, true);
        return DB::transaction(function () use ($form, $expectedVersion, $definition) {
            $locked = LabForm::query()->whereKey($form->id)->lockForUpdate()->firstOrFail();
            $previous = $locked->versions()->reorder()->orderByDesc('version')->firstOrFail();
            abort_unless($previous->version === $expectedVersion, 409, 'The form changed in another tab. Reload before saving.');
            return $locked->versions()->create([
                'version' => $previous->version + 1,
                'definition' => $this->rules->validate($definition, $previous->definition),
            ]);
        });
    }

    public function setState(User $owner, LabProject $project, LabForm $form, string $state, int $expectedVersion): LabForm
    {
        $this->assertAccess($owner, $project, $form, true);
        Validator::make(['state' => $state], ['state' => ['required', 'in:draft,active,closed']])->validate();
        if ($state === 'active') {
            abort_unless(app()->environment('local') || ($project->publication?->isLive() ?? false), 422, 'Publish and verify the site before opening public bookings.');
            app(FormDatastore::class)->install($form);
        }
        return DB::transaction(function () use ($form, $state, $expectedVersion) {
            $locked = LabForm::query()->whereKey($form->id)->lockForUpdate()->firstOrFail();
            $latest = $locked->versions()->reorder()->orderByDesc('version')->firstOrFail();
            abort_unless($latest->version === $expectedVersion, 409, 'The form changed. Reload before activating.');
            $locked->status = $state;
            if ($state === 'active') $locked->active_version = $latest->version;
            $locked->save();
            return $locked;
        });
    }
}
