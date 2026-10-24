<?php

// Force isolation before any provider can resolve the configured database or stores.
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=:memory:');
putenv('DB_URL=');
putenv('CACHE_STORE=array');
putenv('SESSION_DRIVER=array');
putenv('LOG_CHANNEL=null');
putenv('APP_CONFIG_CACHE='.__DIR__.'/no-builder-config-cache.php');
require __DIR__.'/forms-bootstrap.php';

use App\Http\Controllers\Lab\WebsiteBuilderController;
use App\Lab\Builder\TemplateCatalog;
use App\Models\User;
use App\Models\WebsiteDraft;
use App\Support\Site\SiteSettings;
use Deep42\Hitchhiker\Contracts\InstallationStateManager;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Cookie\CookieValuePrefix;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

Http::fake();
Notification::fake();
DB::statement('PRAGMA foreign_keys = ON');
formCheck(DB::connection()->getDatabaseName() === ':memory:' && DB::connection()->getDriverName() === 'sqlite', 'SQLite memory fixture only');
Schema::create('users', function (Blueprint $table): void {
    $table->id(); $table->string('public_id')->unique(); $table->string('name'); $table->string('email')->unique();
    $table->string('password'); $table->timestamp('email_verified_at')->nullable(); $table->rememberToken(); $table->timestamps();
});
Schema::create('site_settings', function (Blueprint $table): void { $table->id(); $table->text('payload')->nullable(); $table->timestamps(); });
foreach (['lab_projects', 'credit_grants', 'user_business_profiles', 'user_entitlements'] as $tableName) {
    Schema::create($tableName, function (Blueprint $table): void { $table->id(); $table->text('fixture'); });
    DB::table($tableName)->insert(['fixture' => 'Private unchanged fixture']);
}
// User creation observers must not provision an entitlement in this isolated fixture.
$owner = User::withoutEvents(fn () => User::forceCreate(['public_id' => (string) Str::ulid(), 'name' => 'Owner fixture', 'email' => 'owner@example.invalid', 'password' => 'Fixture-password-123', 'email_verified_at' => now()]));
$other = User::withoutEvents(fn () => User::forceCreate(['public_id' => (string) Str::ulid(), 'name' => 'Other fixture', 'email' => 'other@example.invalid', 'password' => 'Fixture-password-123', 'email_verified_at' => now()]));
$unverified = User::withoutEvents(fn () => User::forceCreate(['public_id' => (string) Str::ulid(), 'name' => 'Unverified fixture', 'email' => 'unverified@example.invalid', 'password' => 'Fixture-password-123']));
app()->instance(SiteSettings::class, new SiteSettings);
app()->instance(InstallationStateManager::class, new class implements InstallationStateManager {
    public function isInstalled(): bool { return true; }
    public function markInstalled(): void { throw new RuntimeException('No installation mutation'); }
});
config(['app.env' => 'local', 'app.debug' => false, 'session.driver' => 'array', 'cache.default' => 'array', 'logging.default' => 'null']);
app()->instance('env', 'local'); // Run real CSRF middleware.
app('session')->forgetDrivers();
app()->forgetInstance('session.store');
app('redirect')->setSession(app('session')->driver());
Auth::forgetGuards();
RateLimiter::swap(new Illuminate\Cache\RateLimiter(app('cache')->store('array')));

function draftActor(?User $user): void
{
    app('session')->driver()->invalidate();
    app('session')->driver()->regenerateToken();
    Auth::guard('web')->forgetUser();
    if ($user) Auth::guard('web')->login($user);
    app('cache')->store('array')->flush();
}

function draftHttp(string $path, string $method = 'GET', mixed $data = null, bool $csrf = true, bool $json = true): array
{
    $session = app('session')->driver();
    $session->start(); $session->save();
    $cookieName = config('session.cookie');
    $encrypter = app('encrypter');
    $cookie = $encrypter->encrypt(CookieValuePrefix::create($cookieName, $encrypter->getKey()).$session->getId(), false);
    $server = ['HTTP_ACCEPT' => 'application/json', 'REMOTE_ADDR' => '192.0.2.50'];
    if ($csrf) $server['HTTP_X_CSRF_TOKEN'] = $session->token();
    if ($json) $server['CONTENT_TYPE'] = 'application/json';
    $content = is_string($data) ? $data : ($data === null ? null : json_encode($data, JSON_THROW_ON_ERROR));
    $request = Request::create($path, $method, [], [$cookieName => $cookie], [], $server, $content);
    $response = app(Kernel::class)->handle($request);
    return [$response, json_decode($response->getContent(), true), $request];
}

function draftStatus(array $result, int $status, string $label): void
{
    formCheck($result[0]->getStatusCode() === $status, $label.' (got '.$result[0]->getStatusCode().')');
    formCheck(str_contains($result[0]->headers->get('Cache-Control'), 'no-store') && str_contains($result[0]->headers->get('Cache-Control'), 'private'), $label.' is private no-store');
}

draftActor(null);
draftStatus(draftHttp('/lab/builder/catalog'), 401, 'JSON guest is rejected by existing authentication');
draftActor($unverified);
draftStatus(draftHttp('/lab/builder/catalog'), 403, 'Unverified user cannot enter builder');
draftActor($owner);
[$catalogResponse, $catalog] = draftHttp('/lab/builder/catalog');
formCheck($catalogResponse->getStatusCode() === 200 && count($catalog['templates']) === 4, 'Catalog route precedes project route and works before migration');
formCheck(str_contains($catalogResponse->headers->get('Cache-Control'), 'no-store') && str_contains($catalogResponse->headers->get('Cache-Control'), 'private'), 'Catalog is private no-store');
foreach ([['GET', '/lab/builder/drafts'], ['POST', '/lab/builder/drafts'], ['GET', '/lab/builder/drafts/'.Str::uuid()], ['PUT', '/lab/builder/drafts/'.Str::uuid()]] as [$method, $path]) {
    [$response, $payload] = draftHttp($path, $method, []);
    formCheck($response->getStatusCode() === 503 && array_keys($payload) === ['message'] && ! str_contains($response->getContent(), 'SQL'), 'Missing table fails safely '.$method);
}
$migration = require __DIR__.'/../../database/migrations/2026_10_16_110000_create_website_drafts_table.php';
$migration->up();
formCheck(Schema::hasTable('website_drafts'), 'Actual named additive migration creates table');
formCheck(! in_array('user_id', (new WebsiteDraft)->getFillable(), true), 'Ownership is not publicly mass assignable');
$forged = new WebsiteDraft(['user_id' => $other->id]);
formCheck($forged->user_id === null, 'Untrusted mass assignment cannot select owner');
$blueprint = (new TemplateCatalog)->blueprint('business', 'business-studio', '  Edited Thai ก fixture  ', '  summary whitespace  ');
$blueprint['shared']['footer'] = '';
$blueprint['pages'][0]['sections'][0]['heading'] = '  Edited heading  ';
$blueprint['pages'][0]['sections'][0]['body'] = '';
$blueprint['pages'][0]['id'] = 'ABCDEF12-1234-4567-8123-ABCDEF123456';
$requestId = 'a1234567-a123-4123-8123-a123456789ab';
$create = ['request_id' => $requestId, 'blueprint' => $blueprint];
draftStatus(draftHttp('/lab/builder/drafts', 'POST', $create, false), 419, 'Write requires real CSRF header');
draftStatus(draftHttp('/lab/builder/drafts', 'POST', $create, true, false), 422, 'Non-JSON write is rejected explicitly');
draftStatus(draftHttp('/lab/builder/drafts', 'POST', '{'), 422, 'Malformed JSON is rejected');
[$createdResponse, $created, $createdRequest] = draftHttp('/lab/builder/drafts', 'POST', $create);
formCheck($createdResponse->getStatusCode() === 201, 'Atomic edited snapshot create returns 201');
$draft = $created['draft']; $uuid = $draft['uuid'];
formCheck(array_keys($created) === ['draft'] && array_keys($draft) === ['uuid', 'revision', 'blueprint', 'updated_at'], 'Draft exposes only explicit public fields');
formCheck($draft['revision'] === 1 && $draft['blueprint'] === $blueprint, 'Full edited snapshot preserves IDs, empty bodies and content whitespace');
formCheck($createdRequest->input('blueprint') === $blueprint, 'Global middleware preserves POST JSON strings before controller decoding');
formCheck(WebsiteDraft::sole()->user_id === $owner->id && WebsiteDraft::sole()->creation_hash !== '', 'Server assigns owner and creation hash');
formCheck(str_contains($createdResponse->headers->get('Cache-Control'), 'private') && str_contains($createdResponse->headers->get('Cache-Control'), 'no-store'), 'Created draft is private no-store');
[$retryResponse, $retry] = draftHttp('/lab/builder/drafts', 'POST', $create);
formCheck($retryResponse->getStatusCode() === 200 && $retry['draft']['uuid'] === $uuid && WebsiteDraft::count() === 1, 'Identical retry reuses saved draft');
[$caseResponse, $caseRetry] = draftHttp('/lab/builder/drafts', 'POST', ['request_id' => strtoupper($requestId), 'blueprint' => $blueprint]);
formCheck($caseResponse->getStatusCode() === 200 && $caseRetry['draft']['uuid'] === $uuid && WebsiteDraft::count() === 1, 'Case-variant UUID request key replays the same saved draft');
$reordered = $blueprint;
$reordered['theme'] = array_reverse($reordered['theme'], true);
$reordered['pages'][0] = array_reverse($reordered['pages'][0], true);
$reordered = array_reverse($reordered, true);
[$orderResponse, $orderRetry] = draftHttp('/lab/builder/drafts', 'POST', ['blueprint' => $reordered, 'request_id' => $requestId]);
formCheck($orderResponse->getStatusCode() === 200 && $orderRetry['draft']['uuid'] === $uuid, 'Recursive object property order does not change idempotency hash');
$different = $blueprint; $different['title'] = 'Different snapshot';
draftStatus(draftHttp('/lab/builder/drafts', 'POST', ['request_id' => $requestId, 'blueprint' => $different]), 409, 'Reused key with another snapshot conflicts');
draftStatus(draftHttp('/lab/builder/drafts', 'POST', ['request_id' => strtoupper($requestId), 'blueprint' => $different]), 409, 'Case-variant key cannot create another snapshot');
$different = $blueprint; $different['pages'][0]['sections'] = array_reverse($different['pages'][0]['sections']);
draftStatus(draftHttp('/lab/builder/drafts', 'POST', ['request_id' => $requestId, 'blueprint' => $different]), 409, 'List order remains significant in creation hash');
[$shownResponse, $shown] = draftHttp('/lab/builder/drafts/'.$uuid);
formCheck($shownResponse->getStatusCode() === 200 && $shown['draft'] === $draft, 'Owner reloads exact persisted snapshot');
formCheck(str_contains($shownResponse->headers->get('Cache-Control'), 'no-store'), 'Shown draft cannot be cached');
[$upperShowResponse, $upperShown] = draftHttp('/lab/builder/drafts/'.strtoupper($uuid));
formCheck($upperShowResponse->getStatusCode() === 200 && $upperShown['draft'] === $draft, 'Uppercase draft UUID link loads the owner snapshot');
[$listResponse, $list] = draftHttp('/lab/builder/drafts');
formCheck($listResponse->getStatusCode() === 200 && array_keys($list) === ['drafts'] && count($list['drafts']) === 1 && array_keys($list['drafts'][0]) === ['uuid', 'title', 'kind', 'revision', 'updated_at'], 'Owner list exposes summary allowlist');
formCheck(str_contains($listResponse->headers->get('Cache-Control'), 'no-store'), 'Draft summaries cannot be cached');

draftActor($owner);
foreach (['user_id', 'project_id', 'owner', 'status', 'budget', '_token', 'kind', 'template_id'] as $key) {
    draftStatus(draftHttp('/lab/builder/drafts', 'POST', [...$create, $key => $key === '_token' ? app('session')->driver()->token() : 'forged']), 422, 'Unknown create field rejected '.$key);
}
foreach ([null, [], 'string', 1, true] as $bad) {
    draftStatus(draftHttp('/lab/builder/drafts', 'POST', ['request_id' => (string) Str::uuid(), 'blueprint' => $bad]), 422, 'Blueprint must be JSON object');
}
foreach (['summary', 'title'] as $key) {
    $bad = $blueprint; $bad[$key] = null;
    draftStatus(draftHttp('/lab/builder/drafts', 'POST', ['request_id' => (string) Str::uuid(), 'blueprint' => $bad]), 422, 'Null is not normalized to string '.$key);
}
$bad = $blueprint; $bad['pages'][0]['route'] = ' / ';
draftStatus(draftHttp('/lab/builder/drafts', 'POST', ['request_id' => (string) Str::uuid(), 'blueprint' => $bad]), 422, 'Space-padded route is rejected without normalization');
$bad = $blueprint; $bad['pages'][0]['sections'][0]['body'] = null;
draftStatus(draftHttp('/lab/builder/drafts', 'POST', ['request_id' => (string) Str::uuid(), 'blueprint' => $bad]), 422, 'Null section body rejected');
$bad = $blueprint; $bad['pages'][0]['sections'][0]['items'] = (object) [];
draftStatus(draftHttp('/lab/builder/drafts', 'POST', ['request_id' => (string) Str::uuid(), 'blueprint' => $bad]), 422, 'Empty JSON object cannot masquerade as items list');
foreach (['/lab', '/../private', '/Uppercase', '/x%2fy'] as $route) {
    $bad = $blueprint; $bad['pages'][1]['route'] = $route;
    draftStatus(draftHttp('/lab/builder/drafts', 'POST', ['request_id' => (string) Str::uuid(), 'blueprint' => $bad]), 422, 'Invalid route rejected '.$route);
}
formCheck(WebsiteDraft::count() === 1 && WebsiteDraft::sole()->revision === 1, 'Rejected creates and retries do not duplicate or revise draft');

draftActor($owner);
foreach ([null, '', 'bad', '00000000-0000-0000-0000-000000000000', '11111111-1111-f111-8111-111111111111', '11111111-1111-4111-7111-111111111111', $requestId."\n", ' '.$requestId] as $badId) {
    draftStatus(draftHttp('/lab/builder/drafts', 'POST', ['request_id' => $badId, 'blueprint' => $blueprint]), 422, 'Creation request ID is exact RFC UUID');
}
draftStatus(draftHttp('/lab/builder/drafts', 'POST', ['request_id' => (string) Str::uuid()]), 422, 'Missing blueprint rejected');
draftStatus(draftHttp('/lab/builder/drafts', 'POST', ['blueprint' => $blueprint]), 422, 'Missing request ID rejected');
$updateQueries = [];
$observeUpdate = true;
DB::listen(function ($query) use (&$updateQueries, &$observeUpdate): void {
    if ($observeUpdate && str_contains($query->sql, 'website_drafts')) {
        $updateQueries[] = [str_starts_with($query->sql, 'update'), DB::transactionLevel()];
    }
});
$edited = $blueprint; $edited['summary'] = ''; $edited['pages'][0]['sections'][0]['body'] = "  body whitespace\n  ";
[$updateResponse, $updated, $updatedRequest] = draftHttp('/lab/builder/drafts/'.strtoupper($uuid), 'PUT', ['expected_revision' => 1, 'blueprint' => $edited]);
$observeUpdate = false;
formCheck($updateResponse->getStatusCode() === 200 && $updated['draft']['revision'] === 2 && $updated['draft']['blueprint'] === $edited, 'CAS update increments once and preserves empty/whitespace content');
formCheck(str_contains($updateResponse->headers->get('Cache-Control'), 'no-store'), 'Updated draft cannot be cached');
formCheck($updatedRequest->input('blueprint') === $edited, 'Global middleware preserves PUT JSON strings before controller decoding');
formCheck(array_slice($updateQueries, -2) === [[true, 1], [false, 1]] && DB::transactionLevel() === 0, 'CAS mutation and success snapshot read share one transaction');
[$staleResponse, $stale] = draftHttp('/lab/builder/drafts/'.$uuid, 'PUT', ['expected_revision' => 1, 'blueprint' => $blueprint]);
formCheck($staleResponse->getStatusCode() === 409 && array_keys($stale) === ['message', 'current_revision'] && $stale['current_revision'] === 2 && WebsiteDraft::sole()->blueprint === $edited, 'Stale CAS returns current revision without overwriting');
foreach ([0, -1, '2', 2.0, null, true] as $revision) {
    // Preserve float wire type; ordinary json_encode(2.0) erases it.
    $wire = json_encode(['expected_revision' => $revision, 'blueprint' => $edited], JSON_PRESERVE_ZERO_FRACTION | JSON_THROW_ON_ERROR);
    draftStatus(draftHttp('/lab/builder/drafts/'.$uuid, 'PUT', $wire), 422, 'Expected revision is an exact positive integer');
}
draftStatus(draftHttp('/lab/builder/drafts/'.$uuid, 'PUT', ['expected_revision' => 2, 'blueprint' => $edited, 'user_id' => $other->id]), 422, 'Update owner forgery rejected');
$bad = $edited; $bad['pages'][0]['route'] = ' / ';
draftStatus(draftHttp('/lab/builder/drafts/'.$uuid, 'PUT', ['expected_revision' => 2, 'blueprint' => $bad]), 422, 'PUT also rejects padded routes');
[$reloadResponse, $reloaded] = draftHttp('/lab/builder/drafts/'.$uuid);
formCheck($reloadResponse->getStatusCode() === 200 && $reloaded['draft'] === $updated['draft'], 'Reload confirms only accepted CAS snapshot persists');

draftActor($other);
draftStatus(draftHttp('/lab/builder/drafts/'.$uuid), 404, 'Another account cannot read UUID');
draftStatus(draftHttp('/lab/builder/drafts/'.strtoupper($uuid)), 404, 'Uppercase draft UUID still enforces read ownership');
draftStatus(draftHttp('/lab/builder/drafts/'.$uuid, 'PUT', ['expected_revision' => 2, 'blueprint' => $edited]), 404, 'Another account cannot update UUID');
draftStatus(draftHttp('/lab/builder/drafts/'.strtoupper($uuid), 'PUT', ['expected_revision' => 2, 'blueprint' => $edited]), 404, 'Uppercase draft UUID still enforces update ownership');
[$otherListResponse, $otherList] = draftHttp('/lab/builder/drafts');
formCheck($otherListResponse->getStatusCode() === 200 && $otherList === ['drafts' => []], 'Another account sees no owner summaries');
[$otherCreateResponse, $otherCreated] = draftHttp('/lab/builder/drafts', 'POST', [...$create, 'request_id' => strtoupper($requestId)]);
formCheck($otherCreateResponse->getStatusCode() === 201 && $otherCreated['draft']['uuid'] !== $uuid, 'Request ID scope is per owner');
formCheck(WebsiteDraft::where('user_id', $other->id)->sole()->client_request_id === $requestId, 'Uppercase first request key is persisted in canonical lowercase');
draftStatus(draftHttp('/lab/builder/drafts/'.Str::uuid()), 404, 'Missing UUID returns 404');
draftStatus(draftHttp('/lab/builder/drafts/not-a-uuid'), 404, 'Invalid UUID path returns 404');

draftActor($owner);
for ($index = 0; $index < 22; $index++) {
    (new WebsiteDraft)->forceFill(['uuid' => (string) Str::uuid(), 'user_id' => $owner->id, 'client_request_id' => (string) Str::uuid(), 'creation_hash' => str_repeat('a', 64), 'blueprint' => [...$blueprint, 'title' => 'Recent '.$index], 'revision' => 1, 'created_at' => now()->addSeconds($index + 1), 'updated_at' => now()->addSeconds($index + 1)])->save();
}
[$latestResponse, $latest] = draftHttp('/lab/builder/drafts');
formCheck($latestResponse->getStatusCode() === 200 && count($latest['drafts']) === 20 && $latest['drafts'][0]['title'] === 'Recent 21' && $latest['drafts'][19]['title'] === 'Recent 2', 'Latest 20 summaries use descending updated time');

draftActor($owner);
for ($attempt = 1; $attempt <= 31; $attempt++) {
    $result = draftHttp('/lab/builder/drafts', 'POST', []);
    draftStatus($result, $attempt <= 30 ? 422 : 429, 'Mutation throttle request '.$attempt);
}
draftActor($owner);
for ($attempt = 1; $attempt <= 121; $attempt++) {
    $result = draftHttp('/lab/builder/catalog');
    draftStatus($result, $attempt <= 120 ? 200 : 429, 'Read throttle request '.$attempt);
}
draftStatus(draftHttp('/lab/builder/drafts', 'POST', $create), 200, 'Read traffic does not consume mutation limit');

draftActor($owner);
$manifestPath = public_path('build/manifest.json');
$manifest = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
if (isset($manifest['resources/js/builder/builder.jsx'])) {
    [$page] = draftHttp('/lab/builder');
    formCheck($page->getStatusCode() === 200 && str_contains($page->getContent(), 'builder-bootstrap') && ! str_contains($page->getContent(), 'Private unchanged fixture'), 'Actual builder page renders trusted bootstrap without private records');
} else {
    $route = app('router')->getRoutes()->match(Request::create('/lab/builder'));
    formCheck($route->getActionName() === WebsiteBuilderController::class && view()->exists('lab.builder'), 'Builder page route points to actual controller and view pending parent Vite build');
}
$beforeCascade = WebsiteDraft::where('user_id', $other->id)->count();
$other->delete();
formCheck($beforeCascade === 1 && WebsiteDraft::where('user_id', $other->id)->count() === 0 && WebsiteDraft::where('uuid', $uuid)->exists(), 'Owner foreign key cascades only deleted account drafts');
try {
    DB::table('website_drafts')->insert(['uuid' => (string) Str::uuid(), 'user_id' => 999999, 'client_request_id' => (string) Str::uuid(), 'creation_hash' => str_repeat('b', 64), 'blueprint' => '{}']);
    formCheck(false, 'Foreign key rejects unknown owner');
} catch (QueryException $error) {
    formCheck(str_contains($error->getMessage(), 'FOREIGN KEY constraint failed'), 'Foreign key rejects unknown owner');
}
foreach (['lab_projects', 'credit_grants', 'user_business_profiles', 'user_entitlements'] as $tableName) {
    formCheck(DB::table($tableName)->get()->map(fn ($row) => (array) $row)->all() === [['id' => 1, 'fixture' => 'Private unchanged fixture']], 'No side effects in '.$tableName);
}
formCheck(Http::recorded()->isEmpty() && Notification::sentNotifications() === [], 'No provider or notification transport used');
$migration->down();
formCheck(! Schema::hasTable('website_drafts') && Schema::hasTable('users'), 'Rollback removes only draft table');
draftStatus(draftHttp('/lab/builder/drafts'), 503, 'Storage operations fail safely after table disappears');
draftStatus(draftHttp('/lab/builder/catalog'), 200, 'Catalog still works without draft storage');
formResult('builder drafts HTTP/storage');
