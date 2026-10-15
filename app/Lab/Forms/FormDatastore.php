<?php

namespace App\Lab\Forms;

use App\Datastore\DatastoreHttp;
use App\Datastore\SupabaseLinkBroker;
use App\Models\LabForm;

final class FormDatastore
{
    public function __construct(private readonly SupabaseLinkBroker $broker, private readonly SupabaseFormInstaller $installer) {}

    public function forForm(LabForm $form): array
    {
        abort_unless($form->owner !== null, 503, 'Form owner is unavailable.');
        $plane = $this->broker->planeFor($form->owner);
        abort_unless($plane['ready'] && $plane['project_ref'] === $form->supabase_project_ref
            && $plane['steward_token'] !== '', 503, 'Reconnect the original Supabase project for this form.');
        return $plane;
    }

    public function install(LabForm $form): void
    {
        $this->installer->install($this->forForm($form));
    }

    private function request(LabForm $form, string $method, string $path, array $data): array
    {
        $plane = $this->forForm($form);
        try {
            $http = DatastoreHttp::make()->withHeaders([
                'apikey' => $plane['steward_token'], 'Authorization' => 'Bearer '.$plane['steward_token'],
            ])->acceptJson();
            $response = $method === 'GET'
                ? $http->get($plane['host_url'].'/rest/v1/'.$path, $data)
                : $http->post($plane['host_url'].'/rest/v1/'.$path, $data);
        } catch (\Throwable) {
            abort(503, 'Form storage is temporarily unavailable.');
        }
        if (! $response->successful()) {
            $message = $response->json('message');
            if (in_array($message, ['idempotency_conflict', 'status_conflict', 'invalid_transition'], true)) {
                abort(409, 'This request conflicts with a saved operation. Refresh and try again.');
            }
            if ($message === 'submission_not_found') abort(404);
            abort(503, 'Form storage is temporarily unavailable.');
        }
        $payload = $response->json();
        abort_unless(is_array($payload) && array_is_list($payload), 503, 'Unexpected form storage response.');
        return $payload;
    }

    public function receipt(LabForm $form, string $key): ?array
    {
        $rows = $this->request($form, 'GET', 'krikkit_form_submissions', [
            'form_uuid' => 'eq.'.$form->uuid, 'idempotency_key' => 'eq.'.$key,
            'select' => 'uuid,status,definition_version,definition_snapshot,payload_hash', 'limit' => 1,
        ]);
        return $rows[0] ?? null;
    }

    public function submit(LabForm $form, array $core, array $answers, array $snapshot, int $version, string $key, string $hash): array
    {
        $rows = $this->request($form, 'POST', 'rpc/krikkit_submit_form', [
            'p_form_uuid' => $form->uuid, 'p_definition_version' => $version, 'p_definition_snapshot' => $snapshot,
            'p_core' => $core, 'p_answers' => (object) $answers, 'p_key' => $key, 'p_hash' => $hash,
        ]);
        abort_unless(isset($rows[0]['submission_uuid'], $rows[0]['status'], $rows[0]['replayed']), 503, 'Unexpected submission response.');
        return $rows[0];
    }

    public function listing(LabForm $form, array $filters): array
    {
        $query = ['form_uuid' => 'eq.'.$form->uuid, 'select' => 'uuid,customer_name,phone,requested_at,party_size,status,created_at', 'order' => 'created_at.desc,uuid.desc', 'limit' => 26];
        if (isset($filters['status'])) $query['status'] = 'eq.'.$filters['status'];
        if (isset($filters['q']) && $filters['q'] !== '') $query['customer_name'] = 'ilike.*'.str_replace(['*', '%', '_', ',', '(', ')'], '', $filters['q']).'*';
        $bounds = [];
        if (isset($filters['date_from'])) $bounds[] = 'requested_at.gte.'.\Illuminate\Support\Carbon::parse($filters['date_from'], 'Asia/Bangkok')->startOfDay()->utc()->toIso8601String();
        if (isset($filters['date_to'])) $bounds[] = 'requested_at.lte.'.\Illuminate\Support\Carbon::parse($filters['date_to'], 'Asia/Bangkok')->endOfDay()->utc()->toIso8601String();
        if (isset($filters['cursor'])) {
            $cursor = json_decode(base64_decode($filters['cursor'], true) ?: '', true);
            $valid = is_array($cursor) && isset($cursor['created_at'], $cursor['uuid'])
                && is_string($cursor['created_at']) && preg_match('/^\d{4}-\d{2}-\d{2}T[0-9:.+Z-]+$/', $cursor['created_at'])
                && is_string($cursor['uuid']) && \Illuminate\Support\Str::isUuid($cursor['uuid']);
            abort_unless($valid, 422, 'Invalid page cursor.');
            $bounds[] = 'or(created_at.lt.'.$cursor['created_at'].',and(created_at.eq.'.$cursor['created_at'].',uuid.lt.'.$cursor['uuid'].'))';
        }
        if ($bounds) $query['and'] = '('.implode(',', $bounds).')';
        $rows = $this->request($form, 'GET', 'krikkit_form_submissions', $query);
        $hasMore = count($rows) > 25;
        $items = array_slice($rows, 0, 25);
        $last = $items ? end($items) : null;
        return ['items' => $items, 'next_cursor' => $hasMore ? base64_encode(json_encode(['created_at' => $last['created_at'], 'uuid' => $last['uuid']])) : null];
    }

    public function detail(LabForm $form, string $submissionUuid): array
    {
        $rows = $this->request($form, 'GET', 'krikkit_form_submissions', ['form_uuid' => 'eq.'.$form->uuid, 'uuid' => 'eq.'.$submissionUuid, 'select' => 'uuid,definition_version,definition_snapshot,customer_name,phone,requested_at,party_size,notes,answers,status,created_at', 'limit' => 1]);
        abort_unless(isset($rows[0]), 404);
        return $rows[0];
    }

    public function transition(LabForm $form, string $submissionUuid, string $expectedStatus, string $nextStatus, int $actorId): array
    {
        $rows = $this->request($form, 'POST', 'rpc/krikkit_transition_form', ['p_form_uuid' => $form->uuid, 'p_submission_uuid' => $submissionUuid, 'p_expected_status' => $expectedStatus, 'p_next_status' => $nextStatus, 'p_actor_id' => $actorId]);
        abort_unless(isset($rows[0]['submission_uuid'], $rows[0]['status']), 503);
        return $rows[0];
    }
}
