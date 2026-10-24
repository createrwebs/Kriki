<?php

putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=:memory:');
putenv('CACHE_STORE=array');
putenv('SESSION_DRIVER=array');
putenv('LOG_CHANNEL=null');
putenv('APP_CONFIG_CACHE='.__DIR__.'/no-builder-config-cache.php');
require __DIR__.'/forms-bootstrap.php';

use App\Lab\Builder\DraftMaterializer;
use App\Lab\Builder\TemplateCatalog;
use App\Lab\SiteWorkspace;
use App\Models\LabProject;
use App\Models\User;
use App\Models\WebsiteDraft;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

Http::fake(); Notification::fake();
formCheck(DB::getDriverName() === 'sqlite' && DB::connection()->getDatabaseName() === ':memory:', 'No actual application database');
formCheck(class_exists(DraftMaterializer::class), 'Saved draft materialization service implemented');
Schema::create('users', function (Blueprint $table) { $table->id(); $table->string('name'); $table->string('email'); $table->string('password'); $table->timestamp('email_verified_at')->nullable(); $table->timestamps(); });
Schema::create('lab_projects', function (Blueprint $table) {
    $table->id(); $table->uuid('uuid')->unique(); $table->foreignId('user_id'); $table->string('title'); $table->text('notes')->nullable();
    $table->string('project_type')->nullable(); $table->string('kit_version')->nullable(); $table->string('stack')->nullable();
    $table->string('workspace_status'); $table->timestamp('opened_at')->nullable(); $table->timestamps();
});
(require __DIR__.'/../../database/migrations/2026_10_16_110000_create_website_drafts_table.php')->up();
(require __DIR__.'/../../database/migrations/2026_10_17_100000_create_website_draft_projects_table.php')->up();
$owner = User::withoutEvents(fn () => User::forceCreate(['name' => 'Owner', 'email' => 'owner@example.invalid', 'password' => 'fixture']));
$other = User::withoutEvents(fn () => User::forceCreate(['name' => 'Other', 'email' => 'other@example.invalid', 'password' => 'fixture']));
$draft = (new WebsiteDraft)->forceFill(['uuid' => (string) Str::uuid(), 'user_id' => $owner->id, 'client_request_id' => (string) Str::uuid(), 'creation_hash' => str_repeat('1', 64), 'revision' => 1, 'blueprint' => (new TemplateCatalog)->blueprint('business', 'business-studio', 'Fictional source site')]);
$draft->save();
$fixtureRoot = sys_get_temp_dir().'/krikkit-builder-materialization-'.Str::uuid();
$app->useStoragePath($fixtureRoot);
$workspace = new SiteWorkspace;
$filesystem = new class extends Illuminate\Filesystem\Filesystem {
    public bool $fail = false;
    public function put($path, $contents, $lock = false) {
        if ($this->fail && str_ends_with(str_replace('\\', '/', $path), '/src/App.jsx')) throw new RuntimeException('Fixture filesystem failure');
        return parent::put($path, $contents, $lock);
    }
};
File::swap($filesystem);
Schema::create('entitlement_plans', function (Blueprint $table) {
    $table->id(); $table->string('public_id'); $table->string('slug'); $table->string('title');
    $table->integer('rank')->default(0); $table->boolean('is_active')->default(true); $table->boolean('is_default')->default(true);
    $table->decimal('price_monthly')->nullable(); $table->decimal('price_yearly')->nullable(); $table->timestamps();
});
Schema::create('plan_grants', function (Blueprint $table) {
    $table->id(); $table->foreignId('entitlement_plan_id'); $table->string('code'); $table->string('kind');
    $table->boolean('allowed')->default(true); $table->integer('ceiling')->nullable(); $table->string('window'); $table->timestamps();
});
$plan = App\Models\EntitlementPlan::create(['slug' => 'fixture', 'title' => 'Fixture', 'rank' => 0, 'is_active' => true, 'is_default' => true]);
$grant = $plan->grants()->create(['code' => 'projects', 'kind' => 'quota', 'allowed' => true, 'ceiling' => 2, 'window' => 'lifetime']);
$entitlement = new App\Models\UserEntitlement(['status' => 'active']);
$entitlement->setRelation('plan', $plan);
$owner->setRelation('entitlement', $entitlement);
$service = new DraftMaterializer($workspace);
try {
    try { $service->create($other, $draft->uuid, 1); formCheck(false, 'Foreign draft denied'); } catch (Illuminate\Database\Eloquent\ModelNotFoundException) { formCheck(true, 'Foreign draft returns not found'); }
    try { $service->create($owner, $draft->uuid, 2); formCheck(false, 'Stale revision denied'); } catch (HttpExceptionInterface $e) { formCheck($e->getStatusCode() === 409, 'Stale revision conflicts'); }
    [$project, $source, $created] = $service->create($owner, $draft->uuid, 1);
    formCheck($created && $project->user_id === $owner->id && LabProject::count() === 1, 'Real project created for current owner only');
    formCheck(File::exists($workspace->root($project).'/src/App.jsx') && File::exists($workspace->root($project).'/package.json'), 'Real generated source files written');
    File::put($workspace->root($project).'/src/App.jsx', '// Manual edit must survive retry');
    $grant->forceFill(['ceiling' => 1])->save();
    [$retry, $retrySource, $createdAgain] = $service->create($owner, $draft->uuid, 1);
    formCheck(!$createdAgain && $retry->id === $project->id && LabProject::count() === 1, 'Retry reuses project even when quota is full');
    formCheck(File::get($workspace->root($project).'/src/App.jsx') === '// Manual edit must survive retry', 'Retry never overwrites source edits');
    formCheck($source['blueprint_hash'] === $retrySource['blueprint_hash'] && DB::table('website_draft_projects')->count() === 1, 'One immutable source binding');
    $draft->forceFill(['revision' => 2])->save();
    $grant->forceFill(['ceiling' => 2])->save();
    $filesystem->fail = true;
    try { $service->create($owner, $draft->uuid, 2); formCheck(false, 'Filesystem failure surfaced'); } catch (RuntimeException $e) { formCheck($e->getMessage() === 'Fixture filesystem failure', 'Filesystem error preserved'); }
    formCheck(LabProject::count() === 1 && DB::table('website_draft_projects')->count() === 1 && count(File::directories($fixtureRoot.'/app/lab/projects')) === 1, 'Filesystem failure rolls back new DB rows and only cleans the new workspace');
    $filesystem->fail = false;
    $grant->forceFill(['ceiling' => 1])->save();
    try { $service->create($owner, $draft->uuid, 2); formCheck(false, 'Quota required'); } catch (App\Entitlement\Exceptions\EntitlementDeniedException) { formCheck(true, 'Quota denied before project/files created'); }
    formCheck(LabProject::count() === 1 && WebsiteDraft::count() === 1 && $draft->fresh()->revision === 2, 'Existing draft and project preserved');
    formCheck(Http::recorded()->isEmpty(), 'No external API/AI call');

    Schema::create('user_entitlements', function (Blueprint $table) {
        $table->id(); $table->string('public_id'); $table->foreignId('user_id'); $table->foreignId('entitlement_plan_id');
        $table->string('status'); $table->timestamp('started_at')->nullable(); $table->timestamp('ends_at')->nullable(); $table->timestamps();
    });
    $owner->forceFill(['email_verified_at' => now()])->save();
    App\Models\UserEntitlement::create(['user_id' => $owner->id, 'entitlement_plan_id' => $plan->id, 'status' => 'active']);
    app()->instance(Deep42\Hitchhiker\Contracts\InstallationStateManager::class, new class implements Deep42\Hitchhiker\Contracts\InstallationStateManager {
        public function isInstalled(): bool { return true; }
        public function markInstalled(): void { throw new RuntimeException('No installation mutation'); }
    });
    app()->instance(App\Support\Site\SiteSettings::class, new App\Support\Site\SiteSettings);
    app()->instance(SiteWorkspace::class, $workspace);
    config(['app.env' => 'local', 'app.debug' => false, 'session.driver' => 'array', 'cache.default' => 'array']);
    app()->instance('env', 'local');
    app('session')->forgetDrivers(); app()->forgetInstance('session.store');
    app('redirect')->setSession(app('session')->driver());
    Illuminate\Support\Facades\Auth::forgetGuards();
    $as = function (?User $actor) {
        app('session')->driver()->invalidate(); app('session')->driver()->regenerateToken();
        Illuminate\Support\Facades\Auth::guard('web')->forgetUser();
        if ($actor) Illuminate\Support\Facades\Auth::guard('web')->login($actor);
        app('cache')->store('array')->flush();
    };
    $http = function (mixed $body, bool $csrf = true, bool $json = true) use ($draft) {
        $session = app('session')->driver(); $session->start(); $session->save();
        $cookieName = config('session.cookie'); $encrypter = app('encrypter');
        $cookie = $encrypter->encrypt(Illuminate\Cookie\CookieValuePrefix::create($cookieName, $encrypter->getKey()).$session->getId(), false);
        $server = ['HTTP_ACCEPT' => 'application/json', 'REMOTE_ADDR' => '192.0.2.51'];
        if ($csrf) $server['HTTP_X_CSRF_TOKEN'] = $session->token();
        if ($json) $server['CONTENT_TYPE'] = 'application/json';
        $request = Illuminate\Http\Request::create('/lab/builder/drafts/'.$draft->uuid.'/project', 'POST', [], [$cookieName => $cookie], [], $server, is_string($body) ? $body : json_encode($body));
        return app(Illuminate\Contracts\Http\Kernel::class)->handle($request);
    };
    $status = function ($response, int $expected, string $message) {
        formCheck($response->getStatusCode() === $expected, $message.' (got '.$response->getStatusCode().')');
        formCheck(str_contains($response->headers->get('Cache-Control'), 'no-store'), $message.' is no-store');
    };
    $as(null); $status($http(['expected_revision' => 2]), 401, 'Guest HTTP denied');
    $as($other); $status($http(['expected_revision' => 2]), 403, 'Unverified HTTP denied');
    $other->forceFill(['email_verified_at' => now()])->save();
    $as($other); $status($http(['expected_revision' => 2]), 404, 'Foreign draft hidden over HTTP');
    $as($owner); $status($http(['expected_revision' => 2], false), 419, 'Real CSRF required');
    foreach (['{', '[]', '{"expected_revision":2.0}', '{"expected_revision":"2"}', '{"expected_revision":0}', '{"expected_revision":2,"user_id":1}'] as $invalid) {
        $as($owner); $status($http($invalid), 422, 'Strict JSON contract rejects malformed/forged input');
    }
    $as($owner); $status($http(['expected_revision' => 2], true, false), 422, 'Non-JSON body rejected');
    $as($owner); $status($http(['expected_revision' => 1]), 409, 'Stale saved revision rejected over HTTP');
    $as($owner); $status($http(['expected_revision' => 2]), 402, 'Actual existing entitlement quota enforced over HTTP');
    $grant->forceFill(['ceiling' => 2])->save();
    $as($owner); $createdHttp = $http(['expected_revision' => 2]); $status($createdHttp, 201, 'HTTP materializes a real source project');
    $wire = json_decode($createdHttp->getContent(), true);
    formCheck($wire['source']['draft_revision'] === 2 && Str::isUuid($wire['project']['uuid']) && !str_contains($createdHttp->getContent(), 'user_id'), 'Public source binding excludes owner/private metadata');
    $as($owner); $status($http(['expected_revision' => 2]), 200, 'HTTP retry recovers same project at full quota');
} finally {
    File::deleteDirectory($fixtureRoot);
}
formResult('builder materialization');
