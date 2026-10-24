<?php

namespace App\Lab\Modules;

use App\Models\LabModuleInstallation;
use App\Models\LabProject;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

final class NewsArticles
{
    public function __construct(private readonly CapabilityPolicy $policy, private readonly ModuleDatastore $store) {}

    public function validateCreate(array $body): array
    {
        abort_unless(array_diff(array_keys($body), ['idempotency_key','slug','title','excerpt','body','status']) === [], 422, 'Unexpected news fields.');
        $data = Validator::make($body, [
            'idempotency_key' => ['required','uuid'],
            'slug' => ['required','string','min:1','max:120','regex:/^[a-z0-9]+(-[a-z0-9]+)*$/D'],
            'title' => ['required','string','max:200'],
            'excerpt' => ['sometimes','string','max:500'],
            'body' => ['required','string','max:20000'],
            'status' => ['required','in:draft,published'],
        ])->validate();
        $values = [
            'slug' => $data['slug'],
            'title' => trim($data['title']),
            'excerpt' => trim($data['excerpt'] ?? ''),
            'body' => trim($data['body']),
            'status' => $data['status'],
        ];
        abort_unless($values['title'] !== '' && $values['body'] !== '', 422, 'Title and body are required.');
        return [
            'key' => $data['idempotency_key'],
            'values' => $values,
            'hash' => hash('sha256', json_encode($values, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)),
        ];
    }

    public function validateUpdate(array $body): array
    {
        abort_unless(array_diff(array_keys($body), ['expected_version','slug','title','excerpt','body','status']) === [], 422, 'Unexpected news update fields.');
        abort_unless(isset($body['expected_version']) && is_int($body['expected_version']) && $body['expected_version'] >= 1, 422, 'Invalid expected version.');
        $data = Validator::make($body, [
            'slug' => ['required','string','min:1','max:120','regex:/^[a-z0-9]+(-[a-z0-9]+)*$/D'],
            'title' => ['required','string','max:200'],
            'excerpt' => ['sometimes','string','max:500'],
            'body' => ['required','string','max:20000'],
            'status' => ['required','in:draft,published,archived'],
        ])->validate();
        $values = [
            'slug' => $data['slug'],
            'title' => trim($data['title']),
            'excerpt' => trim($data['excerpt'] ?? ''),
            'body' => trim($data['body']),
            'status' => $data['status'],
        ];
        abort_unless($values['title'] !== '' && $values['body'] !== '', 422, 'Title and body are required.');
        return ['expected_version' => $body['expected_version'], 'values' => $values];
    }

    public function listing(User $actor, LabProject $project, LabModuleInstallation $module, ?string $status = null): array
    {
        $this->assertNews($actor, $project, $module, 'history');
        $query = [
            'project_uuid' => 'eq.'.$project->uuid,
            'module_uuid' => 'eq.'.$module->uuid,
            'select' => 'uuid,slug,title,excerpt,body,status,version,published_at,created_at,updated_at',
            'order' => 'created_at.desc,uuid.desc',
            'limit' => 100,
        ];
        if ($status !== null) {
            abort_unless(in_array($status, ['draft','published','archived'], true), 422);
            $query['status'] = 'eq.'.$status;
        }
        return ['items' => $this->store->request($module, 'GET', 'krikkit_news_articles', $query), 'limit' => 100];
    }

    public function create(User $actor, LabProject $project, LabModuleInstallation $module, array $body): array
    {
        $data = $this->validateCreate($body);
        $this->assertNews($actor, $project, $module, 'mutate');
        $rows = $this->store->request($module, 'POST', 'rpc/krikkit_create_news', [
            'p_project_uuid' => $project->uuid,
            'p_module_uuid' => $module->uuid,
            'p_key' => $data['key'],
            'p_hash' => $data['hash'],
            'p_values' => $data['values'],
            'p_actor_id' => (int) $actor->id,
        ]);
        abort_unless(isset($rows[0]['article_uuid'], $rows[0]['status'], $rows[0]['version'], $rows[0]['replayed'])
            && Str::isUuid($rows[0]['article_uuid'])
            && in_array($rows[0]['status'], ['draft','published','archived'], true)
            && is_int($rows[0]['version']) && is_bool($rows[0]['replayed']), 503, 'Unexpected news receipt.');
        return $rows[0];
    }

    public function update(User $actor, LabProject $project, LabModuleInstallation $module, string $articleUuid, array $body): array
    {
        abort_unless(Str::isUuid($articleUuid), 422, 'Invalid article identifier.');
        $data = $this->validateUpdate($body);
        $this->assertNews($actor, $project, $module, 'mutate');
        $rows = $this->store->request($module, 'POST', 'rpc/krikkit_update_news', [
            'p_project_uuid' => $project->uuid,
            'p_module_uuid' => $module->uuid,
            'p_article_uuid' => $articleUuid,
            'p_expected_version' => $data['expected_version'],
            'p_values' => $data['values'],
            'p_actor_id' => (int) $actor->id,
        ]);
        abort_unless(isset($rows[0]['article_uuid'], $rows[0]['status'], $rows[0]['version'])
            && Str::isUuid($rows[0]['article_uuid'])
            && in_array($rows[0]['status'], ['draft','published','archived'], true)
            && is_int($rows[0]['version']), 503, 'Unexpected news update response.');
        return $rows[0];
    }

    public function publicFeed(LabModuleInstallation $module, ?string $slug = null): array
    {
        abort_unless($module->key === 'news', 404);
        return DB::transaction(function () use ($module, $slug) {
            $project = LabProject::query()->whereKey($module->lab_project_id)->lockForUpdate()->firstOrFail();
            $module = LabModuleInstallation::query()->whereKey($module->id)->lockForUpdate()->firstOrFail();
            $this->policy->assertAllowed($module->owner, $project, 'mutate', $module);
            abort_unless($module->schema_version === 1 && $module->status === 'ready', 409, 'News storage is not ready.');
            $query = [
                'project_uuid' => 'eq.'.$project->uuid,
                'module_uuid' => 'eq.'.$module->uuid,
                'status' => 'eq.published',
                'select' => 'uuid,slug,title,excerpt,body,published_at,updated_at',
                'order' => 'published_at.desc,uuid.desc',
                'limit' => $slug !== null ? 1 : 25,
            ];
            if ($slug !== null) {
                abort_unless(preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/D', $slug) && strlen($slug) <= 120, 422, 'Invalid news slug.');
                $query['slug'] = 'eq.'.$slug;
            }
            $rows = $this->store->request($module, 'GET', 'krikkit_news_articles', $query);
            if ($slug !== null) {
                abort_unless(isset($rows[0]), 404, 'Article not found.');
                return ['article' => $rows[0]];
            }
            return ['items' => $rows, 'limit' => 25];
        });
    }

    private function assertNews(User $actor, LabProject $project, LabModuleInstallation $module, string $operation): void
    {
        $this->policy->assertAllowed($actor, $project, $operation, $module);
        abort_unless($module->key === 'news' && $module->schema_version === 1 && $module->status === 'ready', 409, 'News storage is not ready.');
    }
}
