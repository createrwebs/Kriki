<?php

// Run local Postman simulation4900 and PostgreSQL adapter4910 first. Fictional data only.
require __DIR__.'/managed-module-bootstrap.php';
(require __DIR__.'/../../database/migrations/2026_08_16_081000_create_lab_publications_table.php')->up();

use App\Lab\Modules\ModuleInstallations;
use App\Lab\Modules\NewsArticles;
use App\Lab\Modules\SchemaProposals;
use App\Models\UserDatastoreLink;
use Illuminate\Support\Str;

config(['services.supabase.api_url' => 'http://127.0.0.1:4900/supabase', 'app.url' => 'http://localhost:8892', 'app.debug' => false]);
Illuminate\Support\Facades\Http::allowStrayRequests(['http://127.0.0.1:4900/supabase/*']);
UserDatastoreLink::create(['user_id' => $owner->id, 'driver' => 'supabase', 'host_url' => 'http://127.0.0.1:4900/supabase/pglite', 'publishable_token' => 'fictional-public', 'steward_token' => 'fictional-private', 'access_token' => 'fictional-management', 'project_ref' => 'pglitefixture']);
$allow('news'); $modules = app(ModuleInstallations::class); $proposals = app(SchemaProposals::class); $news = app(NewsArticles::class);
$modules->select($owner, $project, 'news', true, 0);
$module = $modules->prepare($owner, $project, 'news', 1, [], (string) Str::uuid())['installation'];
$proposal = $proposals->prepare($owner, $project, $module, 1);
$receipt = $proposals->apply($owner, $project, $proposal, $proposal->impact_hash, 1);
formCheck($receipt['verified'] && $module->fresh()->status === 'ready', 'Actual PostgreSQL news schema and registered binding through real HTTP simulation');
config(['lab-modules.business_writes_enabled' => true]); // Isolated test process only.
$modules->setState($owner, $project, $module->fresh(), 'enabled', 2);
$module->refresh();

app()->instance('env', 'local');
app()->instance(Deep42\Hitchhiker\Contracts\InstallationStateManager::class, new class implements Deep42\Hitchhiker\Contracts\InstallationStateManager {
    public function isInstalled(): bool { return true; }
    public function markInstalled(): void { throw new RuntimeException('No installation mutation'); }
});

$publicHttp = function (string $query = '', ?string $origin = 'http://localhost:8892', string $method = 'GET') use ($module) {
    $server = ['HTTP_ACCEPT' => 'application/json', 'REMOTE_ADDR' => '192.0.2.55'];
    if ($origin !== null) $server['HTTP_ORIGIN'] = $origin;
    $request = Illuminate\Http\Request::create('/api/lab-widgets/'.$module->uuid.'/news'.($query ? '?'.$query : ''), $method, [], [], [], $server);
    return app(Illuminate\Contracts\Http\Kernel::class)->handle($request);
};

$draftKey = (string) Str::uuid();
$draftArticle = $news->create($owner, $project, $module, [
    'idempotency_key' => $draftKey,
    'slug' => 'draft-news-item',
    'title' => 'ข่าวร่างยังไม่เผยแพร่',
    'excerpt' => 'คำโปรยร่าง',
    'body' => 'เนื้อหาข่าวร่างที่ไม่ควรแสดงต่อสาธารณะ',
    'status' => 'draft',
]);
formCheck($draftArticle['status'] === 'draft' && $draftArticle['version'] === 1 && !$draftArticle['replayed'], 'Owner creates draft news article');
$replayed = $news->create($owner, $project, $module, [
    'idempotency_key' => $draftKey,
    'slug' => 'draft-news-item',
    'title' => 'ข่าวร่างยังไม่เผยแพร่',
    'excerpt' => 'คำโปรยร่าง',
    'body' => 'เนื้อหาข่าวร่างที่ไม่ควรแสดงต่อสาธารณะ',
    'status' => 'draft',
]);
formCheck($replayed['article_uuid'] === $draftArticle['article_uuid'] && $replayed['replayed'], 'Exact create retry replays saved article');
$expect(fn () => $news->create($owner, $project, $module, [
    'idempotency_key' => (string) Str::uuid(),
    'slug' => 'draft-news-item',
    'title' => 'ซ้ำ slug',
    'excerpt' => '',
    'body' => 'เนื้อหา',
    'status' => 'draft',
]), 409, 'Duplicate slug in same module rejected');

$pubFeedEmpty = $publicHttp();
formCheck($pubFeedEmpty->getStatusCode() === 200 && json_decode($pubFeedEmpty->getContent(), true)['items'] === [], 'Public feed excludes draft articles');
formCheck($publicHttp('slug=draft-news-item')->getStatusCode() === 404, 'Public detail cannot read draft slug');

$published = $news->update($owner, $project, $module, $draftArticle['article_uuid'], [
    'expected_version' => 1,
    'slug' => 'draft-news-item',
    'title' => 'ข่าวประกาศอย่างเป็นทางการ',
    'excerpt' => 'คำโปรยข่าวประกาศ',
    'body' => 'เนื้อหาข่าวที่เผยแพร่แล้วและแก้ไขได้',
    'status' => 'published',
]);
formCheck($published['status'] === 'published' && $published['version'] === 2, 'Owner publishes and updates news article');
$expect(fn () => $news->update($owner, $project, $module, $draftArticle['article_uuid'], [
    'expected_version' => 1,
    'slug' => 'draft-news-item',
    'title' => 'แก้ซ้ำด้วยรุ่นเก่า',
    'excerpt' => '',
    'body' => 'เนื้อหา',
    'status' => 'published',
]), 409, 'Stale expected_version rejected');

$pubFeed = $publicHttp();
$pubData = json_decode($pubFeed->getContent(), true);
formCheck($pubFeed->getStatusCode() === 200 && count($pubData['items']) === 1 && $pubData['items'][0]['title'] === 'ข่าวประกาศอย่างเป็นทางการ', 'Public feed lists published article');
formCheck(!isset($pubData['items'][0]['version']) && !isset($pubData['items'][0]['status']), 'Public feed omits internal control fields');
$pubDetail = $publicHttp('slug=draft-news-item');
formCheck($pubDetail->getStatusCode() === 200 && json_decode($pubDetail->getContent(), true)['article']['body'] === 'เนื้อหาข่าวที่เผยแพร่แล้วและแก้ไขได้', 'Public detail reads published article by slug');
formCheck($publicHttp('', 'https://attacker.invalid')->getStatusCode() === 403, 'Foreign origin blocked on public news feed');
formCheck($publicHttp('', null)->getStatusCode() === 403, 'Missing origin blocked on public news feed');

$expect(fn () => $news->listing($owner, $sibling, $module), 404, 'Same owner wrong project cannot read news');
$expect(fn () => $news->listing($other, $foreign, $module), 404, 'Other account cannot read news');

$archived = $news->update($owner, $project, $module, $draftArticle['article_uuid'], [
    'expected_version' => 2,
    'slug' => 'draft-news-item',
    'title' => 'ข่าวประกาศอย่างเป็นทางการ',
    'excerpt' => 'คำโปรยข่าวประกาศ',
    'body' => 'เนื้อหาข่าวที่เผยแพร่แล้วและแก้ไขได้',
    'status' => 'archived',
]);
formCheck($archived['status'] === 'archived' && $archived['version'] === 3, 'Owner archives news article');
formCheck(json_decode($publicHttp()->getContent(), true)['items'] === [], 'Archived article disappears from public feed');
formCheck(count($news->listing($owner, $project, $module)['items']) === 1, 'Owner history retains archived article');

formResult('actual news CRUD and public feed over Postman HTTP and local PostgreSQL');
