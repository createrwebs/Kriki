<?php

namespace App\Lab\Modules;

use App\Models\LabModuleInstallation;
use App\Models\LabProject;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

final class ContactMessages
{
    public function __construct(private readonly CapabilityPolicy $policy, private readonly ModuleDatastore $store) {}

    public function validate(array $body): array
    {
        abort_unless(array_diff(array_keys($body), ['idempotency_key','name','email','phone','subject','message','consent','website']) === [], 422, 'Unexpected contact fields.');
        abort_unless(($body['consent'] ?? null) === true && (!isset($body['website']) || $body['website'] === ''), 422, 'Please review consent and contact fields.');
        $data = Validator::make($body, ['idempotency_key' => ['required','uuid'], 'name' => ['required','string','max:120'], 'email' => ['required','string','email:rfc','max:254'],
            'phone' => ['sometimes','string','max:40'], 'subject' => ['sometimes','string','max:180'], 'message' => ['required','string','max:5000'],
        ])->validate();
        $values = ['name' => trim($data['name']), 'email' => trim($data['email']), 'phone' => trim($data['phone'] ?? ''), 'subject' => trim($data['subject'] ?? ''), 'message' => trim($data['message']), 'consent' => true];
        abort_unless($values['name'] !== '' && $values['message'] !== '', 422, 'Name and message are required.');
        return ['key' => $data['idempotency_key'], 'values' => $values, 'hash' => hash('sha256', json_encode($values, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR))];
    }

    public function submit(LabModuleInstallation $module, array $body): array
    {
        $data = $this->validate($body);
        abort_unless($module->key === 'contact', 404);
        return DB::transaction(function () use ($module, $data) {
            $project = LabProject::query()->whereKey($module->lab_project_id)->lockForUpdate()->firstOrFail();
            $module = LabModuleInstallation::query()->whereKey($module->id)->lockForUpdate()->firstOrFail();
            $scope = ['project_uuid' => 'eq.'.$project->uuid, 'module_uuid' => 'eq.'.$module->uuid];
            $previous = $this->store->request($module, 'GET', 'krikkit_contact_messages', [...$scope, 'request_key' => 'eq.'.$data['key'], 'select' => 'uuid,status,version,request_hash', 'limit' => 1]);
            if (isset($previous[0])) {
                abort_unless(is_string($previous[0]['request_hash'] ?? null) && hash_equals($previous[0]['request_hash'], $data['hash']), 409, 'This retry differs from the saved message.');
                return ['reference' => $previous[0]['uuid'], 'status' => $previous[0]['status'], 'replayed' => true];
            }
            $this->policy->assertAllowed($module->owner, $project, 'mutate', $module);
            $rows = $this->store->request($module, 'POST', 'rpc/krikkit_submit_contact', ['p_project_uuid' => $project->uuid, 'p_module_uuid' => $module->uuid,
                'p_key' => $data['key'], 'p_hash' => $data['hash'], 'p_values' => $data['values']]);
            abort_unless(isset($rows[0]['submission_uuid'], $rows[0]['status'], $rows[0]['replayed']) && Str::isUuid($rows[0]['submission_uuid']) && in_array($rows[0]['status'], ['new','read','archived'], true) && is_bool($rows[0]['replayed']), 503, 'Unexpected contact receipt.');
            return ['reference' => $rows[0]['submission_uuid'], 'status' => $rows[0]['status'], 'replayed' => $rows[0]['replayed']];
        });
    }

    public function listing(User $actor, LabProject $project, LabModuleInstallation $module, ?string $status = null): array
    {
        $this->assertContact($actor, $project, $module, 'history');
        $query = ['project_uuid' => 'eq.'.$project->uuid, 'module_uuid' => 'eq.'.$module->uuid, 'select' => 'uuid,customer_name,email,phone,subject,message,status,version,created_at', 'order' => 'created_at.desc,uuid.desc', 'limit' => 100];
        if ($status !== null) { abort_unless(in_array($status, ['new','read','archived'], true), 422); $query['status'] = 'eq.'.$status; }
        return ['items' => $this->store->request($module, 'GET', 'krikkit_contact_messages', $query), 'limit' => 100];
    }

    public function transition(User $actor, LabProject $project, LabModuleInstallation $module, string $message, int $version, string $status): array
    {
        $this->assertContact($actor, $project, $module, 'mutate');
        abort_unless(Str::isUuid($message) && $version >= 1 && in_array($status, ['new','read','archived'], true), 422, 'Invalid contact transition.');
        $rows = $this->store->request($module, 'POST', 'rpc/krikkit_transition_contact', ['p_project_uuid' => $project->uuid, 'p_module_uuid' => $module->uuid, 'p_message_uuid' => $message, 'p_expected_version' => $version, 'p_status' => $status, 'p_actor_id' => (int) $actor->id]);
        abort_unless(isset($rows[0]['submission_uuid'], $rows[0]['status'], $rows[0]['version']), 503, 'Unexpected contact transition response.');
        return $rows[0];
    }

    private function assertContact(User $actor, LabProject $project, LabModuleInstallation $module, string $operation): void
    {
        $this->policy->assertAllowed($actor, $project, $operation, $module);
        abort_unless($module->key === 'contact' && $module->schema_version === 1 && $module->status === 'ready', 409, 'Contact storage is not ready.');
    }
}
