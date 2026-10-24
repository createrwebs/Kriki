<?php

putenv('DB_CONNECTION=sqlite'); putenv('DB_DATABASE=:memory:');
putenv('CACHE_STORE=array'); putenv('SESSION_DRIVER=array'); putenv('LOG_CHANNEL=null');
putenv('APP_CONFIG_CACHE='.__DIR__.'/no-module-config-cache.php');
require __DIR__.'/forms-bootstrap.php';

use App\Lab\Modules\CapabilityPolicy;
use App\Lab\Modules\ModuleCatalog;
use App\Lab\Modules\ModuleInstallations;
use App\Models\LabModuleInstallation;
use App\Models\LabProject;
use App\Models\User;
use App\Models\UserDatastoreLink;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

Http::fake(); Illuminate\Support\Facades\Notification::fake();
formCheck(DB::getDriverName() === 'sqlite' && DB::connection()->getDatabaseName() === ':memory:', 'Only in-memory control database');
Schema::create('users', function (Blueprint $table) { $table->id(); $table->string('name'); $table->string('email'); $table->string('password'); $table->timestamp('email_verified_at')->nullable(); $table->timestamps(); });
(require __DIR__.'/../../database/migrations/2026_08_04_051600_create_lab_projects_table.php')->up();
Schema::table('lab_projects', function (Blueprint $table) { $table->timestamp('frozen_at')->nullable(); $table->timestamp('opened_at')->nullable(); });
(require __DIR__.'/../../database/migrations/2026_08_18_021000_create_user_datastore_links_table.php')->up();
(require __DIR__.'/../../database/migrations/2026_10_15_010000_create_lab_forms_tables.php')->up();
(require __DIR__.'/../../database/migrations/2026_10_17_110000_create_lab_module_control_tables.php')->up();
Schema::create('entitlement_plans', function (Blueprint $table) {
    $table->id(); $table->string('public_id'); $table->string('slug'); $table->string('title'); $table->integer('rank')->default(0);
    $table->boolean('is_active')->default(true); $table->boolean('is_default')->default(true); $table->decimal('price_monthly')->nullable(); $table->decimal('price_yearly')->nullable(); $table->timestamps();
});
Schema::create('plan_grants', function (Blueprint $table) { $table->id(); $table->foreignId('entitlement_plan_id'); $table->string('code'); $table->string('kind'); $table->boolean('allowed')->default(false); $table->integer('ceiling')->nullable(); $table->string('window'); $table->timestamps(); });
Schema::create('user_entitlements', function (Blueprint $table) { $table->id(); $table->string('public_id'); $table->foreignId('user_id'); $table->foreignId('entitlement_plan_id'); $table->string('status'); $table->timestamp('started_at')->nullable(); $table->timestamp('ends_at')->nullable(); $table->timestamps(); });
$owner = User::withoutEvents(fn () => User::forceCreate(['name' => 'Owner', 'email' => 'module-owner@example.invalid', 'password' => 'fixture', 'email_verified_at' => now()]));
$other = User::withoutEvents(fn () => User::forceCreate(['name' => 'Other', 'email' => 'module-other@example.invalid', 'password' => 'fixture', 'email_verified_at' => now()]));
$project = LabProject::create(['user_id' => $owner->id, 'title' => 'Fictional modules']);
$sibling = LabProject::create(['user_id' => $owner->id, 'title' => 'Sibling modules']);
$foreign = LabProject::create(['user_id' => $other->id, 'title' => 'Foreign modules']);
$plan = App\Models\EntitlementPlan::create(['slug' => 'module-fixture', 'title' => 'Module fixture', 'rank' => 0, 'is_active' => true, 'is_default' => true]);
foreach ([$owner, $other] as $actor) App\Models\UserEntitlement::create(['user_id' => $actor->id, 'entitlement_plan_id' => $plan->id, 'status' => 'active']);
UserDatastoreLink::create(['user_id' => $owner->id, 'driver' => 'supabase', 'host_url' => 'https://fixturemodules.supabase.co', 'publishable_token' => 'fictional-public', 'steward_token' => 'fictional-private', 'access_token' => 'fictional-management', 'project_ref' => 'fixturemodules']);
$service = app(ModuleInstallations::class); $policy = app(CapabilityPolicy::class);
$expect = function (callable $action, int $status, string $label) {
    try { $action(); formCheck(false, $label); }
    catch (HttpExceptionInterface $error) { formCheck($error->getStatusCode() === $status, $label); }
    catch (Illuminate\Auth\Access\AuthorizationException $error) { formCheck($status === 403, $label); }
    catch (App\Entitlement\Exceptions\EntitlementDeniedException $error) { formCheck($status === 402, $label); }
};
$allow = function (string $key, bool $enabled = true) use ($plan, $owner, $other) {
    $plan->grants()->updateOrCreate(['code' => 'lab_module_'.$key], ['kind' => 'feature', 'allowed' => $enabled, 'window' => 'none']);
    $owner->unsetRelation('entitlement'); $other->unsetRelation('entitlement');
};
formCheck(count(ModuleCatalog::all()) === 7 && ModuleCatalog::get('manual_payments')['dependencies'] === ['orders'], 'Versioned fixed catalog and dependencies');
formCheck(App\Entitlement\EntitlementCatalog::definition('lab_module_contact') !== null && $plan->grants()->count() === 0, 'Feature declaration does not grant existing plans');
$expect(fn () => $service->select($owner, $project, 'contact', true, 0), 402, 'Missing account feature denies selection');
$expect(fn () => $service->select($other, $project, 'contact', true, 0), 403, 'Foreign owner cannot select capability');
$expect(fn () => $service->select($owner, $project, 'forged', true, 0), 422, 'Unknown capability denied');
$allow('contact'); $allow('news'); $allow('catalog'); $allow('orders'); $allow('manual_payments');
formCheck($service->select($owner, $project, 'contact', true, 0) === 1, 'Explicit entitled project capability selected');
$expect(fn () => $service->select($owner, $project, 'news', true, 0), 409, 'Stale project revision rejected');
$key = (string) Str::uuid();
$prepared = $service->prepare($owner, $project, 'contact', 1, [], $key);
$module = $prepared['installation'];
formCheck($prepared['created'] && $prepared['project_revision'] === 2 && $module->status === 'prepared' && !$module->enabled && $module->schema_version === 0, 'Preparation creates inactive control state only');
formCheck($module->supabase_project_ref === 'fixturemodules' && $module->lab_project_id === $project->id, 'Preparation pins selected database and project');
$replay = $service->prepare($owner, $project, 'contact', 1, [], $key);
formCheck(!$replay['created'] && $replay['installation']->id === $module->id && LabModuleInstallation::count() === 1, 'Exact lost-response replay returns existing module');
$expect(fn () => $service->prepare($owner, $project, 'news', 1, [], $key), 409, 'Idempotency key cannot change request');
$expect(fn () => $service->prepare($owner, $project, 'contact', 2, [], (string) Str::uuid()), 409, 'New key cannot duplicate installed module');
$expect(fn () => $service->prepare($owner, $sibling, 'contact', 0, [], (string) Str::uuid()), 403, 'Same-owner sibling has no inherited project capability');
$expect(fn () => $policy->assertAllowed($owner, $sibling, 'history', $module), 404, 'Same-owner wrong-project installation denied');
$expect(fn () => $policy->assertAllowed($other, $foreign, 'history', $module), 404, 'Foreign installation denied');
$expect(fn () => $service->setState($owner, $project, $module, 'enabled', 1), 409, 'Unverified schema cannot activate');
$module->forceFill(['status' => 'ready', 'schema_version' => 1])->save();
$expect(fn () => $service->setState($owner, $project, $module, 'enabled', 1), 423, 'Readiness gate denies new business activation');
config(['lab-modules.business_writes_enabled' => true]); // Isolated fixture only, never application config.
$active = $service->setState($owner, $project, $module, 'enabled', 1);
formCheck($active->enabled && $active->revision === 2, 'Verified entitled module activates under fixture gate');
$policy->assertAllowed($owner, $project, 'mutate', $active);
formCheck(true, 'Effective capability intersects account/project/ready/enabled');
$expect(fn () => $service->setState($owner, $project, $module, 'disabled', 1), 409, 'Stale module state revision rejected');
$allow('contact', false);
$policy->assertAllowed($owner, $project, 'history', $active); formCheck(true, 'Downgrade preserves owner history');
$expect(fn () => $policy->assertAllowed($owner, $project, 'mutate', $active), 402, 'Downgrade blocks new mutation');
$inactive = $service->setState($owner, $project, $active, 'disabled', 2);
formCheck(!$inactive->enabled && $inactive->revision === 3, 'Downgraded owner can disable without deleting history');
$allow('contact');
$link = UserDatastoreLink::where('user_id', $owner->id)->firstOrFail();
$link->forceFill(['project_ref' => 'differentfixture'])->save();
$expect(fn () => $service->plane($inactive->fresh()), 503, 'Changed selected Supabase cannot silently rebind');
formCheck($service->prepare($owner, $project, 'contact', 1, [], $key)['installation']->supabase_project_ref === 'fixturemodules', 'Replay preserves immutable original database binding');
$link->forceFill(['project_ref' => 'fixturemodules'])->save();
$revision = $service->select($owner, $project, 'orders', true, (int) $project->fresh()->modules_revision);
$expect(fn () => $service->prepare($owner, $project, 'orders', $revision, [], (string) Str::uuid()), 409, 'Missing catalog dependency denies order installation');
$catalogRevision = $service->select($owner, $project, 'catalog', true, (int) $project->fresh()->modules_revision);
$catalogFixture = LabModuleInstallation::create(['uuid' => (string) Str::uuid(), 'user_id' => $owner->id, 'lab_project_id' => $project->id,
    'key' => 'catalog', 'supabase_project_ref' => 'fixturemodules', 'revision' => 1, 'schema_version' => 1, 'status' => 'ready', 'enabled' => true,
    'config' => [], 'idempotency_key' => (string) Str::uuid(), 'request_hash' => str_repeat('a', 64)]);
$link->forceFill(['project_ref' => 'differentfixture'])->save();
$expect(fn () => $service->prepare($owner, $project, 'orders', $catalogRevision, [], (string) Str::uuid()), 409, 'Dependent module cannot bind a different Supabase project');
$wrongPlane = new LabModuleInstallation(['user_id' => $owner->id, 'lab_project_id' => $project->id, 'key' => 'orders', 'supabase_project_ref' => 'differentfixture', 'schema_version' => 1, 'status' => 'ready', 'enabled' => true]);
$expect(fn () => $policy->assertAllowed($owner, $project, 'mutate', $wrongPlane), 409, 'Mutation rechecks dependency database consistency');
$link->forceFill(['project_ref' => 'fixturemodules'])->save();
$catalogFixture->delete(); // Only disposable in-memory dependency fixture.
$project->forceFill(['frozen_at' => now()])->save();
$expect(fn () => $service->select($owner, $project, 'news', true, (int) $project->fresh()->modules_revision), 403, 'Frozen project cannot select new capability');
formCheck(count($service->listing($owner, $project)['modules']) === 7, 'Frozen project history/catalog remains readable');
$project->forceFill(['frozen_at' => null])->save();
$booking = app(App\Lab\Forms\FormDefinitions::class)->create($owner, $project, 'Existing fictional booking');
formCheck(app(App\Lab\Forms\FormDefinitions::class)->detail($owner, $project, $booking)['latest_version'] === 1, 'Legacy booking access preserved without new booking feature grant');
formCheck($service->listing($owner, $project)['legacy_booking_forms'] === 1 && LabModuleInstallation::count() === 1, 'Legacy forms not copied or converted to new commerce capabilities');
formCheck(Http::recorded()->isEmpty(), 'Preparation makes no external schema/API call');
config(['lab-modules.business_writes_enabled' => false]);

// Real owner middleware pipeline, including encrypted session cookie and CSRF.
app()->instance(Deep42\Hitchhiker\Contracts\InstallationStateManager::class, new class implements Deep42\Hitchhiker\Contracts\InstallationStateManager {
    public function isInstalled(): bool { return true; }
    public function markInstalled(): void { throw new RuntimeException('No installation mutation'); }
});
config(['app.env' => 'local', 'app.debug' => false, 'session.driver' => 'array', 'cache.default' => 'array']); app()->instance('env', 'local');
app('session')->forgetDrivers(); app()->forgetInstance('session.store'); app('redirect')->setSession(app('session')->driver()); Illuminate\Support\Facades\Auth::forgetGuards();
$as = function (?User $actor) { app('session')->driver()->invalidate(); app('session')->driver()->regenerateToken(); Illuminate\Support\Facades\Auth::guard('web')->forgetUser(); if ($actor) Illuminate\Support\Facades\Auth::guard('web')->login($actor); app('cache')->store('array')->flush(); };
$http = function (string $path, string $method = 'GET', mixed $body = null, bool $csrf = true) {
    $session = app('session')->driver(); $session->start(); $session->save(); $name = config('session.cookie'); $encrypter = app('encrypter');
    $cookie = $encrypter->encrypt(Illuminate\Cookie\CookieValuePrefix::create($name, $encrypter->getKey()).$session->getId(), false);
    $server = ['HTTP_ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json', 'REMOTE_ADDR' => '192.0.2.52'];
    if ($csrf) $server['HTTP_X_CSRF_TOKEN'] = $session->token();
    $request = Illuminate\Http\Request::create($path, $method, [], [$name => $cookie], [], $server, is_string($body) ? $body : json_encode($body));
    return app(Illuminate\Contracts\Http\Kernel::class)->handle($request);
};
$status = function ($response, int $expected, string $label) {
    formCheck($response->getStatusCode() === $expected, $label.' got '.$response->getStatusCode());
    formCheck(str_contains($response->headers->get('Cache-Control'), 'no-store'), $label.' is no-store');
};
$url = '/lab/'.$project->uuid.'/modules';
$as(null); $status($http($url), 401, 'Guest owner API denied');
$other->forceFill(['email_verified_at' => null])->save(); $as($other); $status($http($url), 403, 'Unverified owner denied');
$other->forceFill(['email_verified_at' => now()])->save(); $as($other); $status($http($url), 403, 'Foreign owner API denied');
$as($owner); $status($http($url.'/capabilities/news', 'PUT', ['enabled' => true, 'expected_revision' => (int) $project->fresh()->modules_revision], false), 419, 'Real CSRF required');
foreach (['{', '[]', '{"enabled":true,"expected_revision":0.0}', '{"enabled":true,"expected_revision":0,"user_id":1}', '{"enabled":"true","expected_revision":0}'] as $body) {
    $as($owner); $status($http($url.'/capabilities/news', 'PUT', $body), 422, 'Strict capability object validation');
}
$as($owner); $listed = $http($url); $status($listed, 200, 'Owner module metadata readable');
formCheck(!str_contains($listed->getContent(), 'fictional-private') && !str_contains($listed->getContent(), 'fictional-management') && !str_contains($listed->getContent(), 'request_hash'), 'Metadata never exposes credentials or internal request hash');
$as($owner); $status($http($url, 'POST', ['key' => 'news', 'expected_revision' => 0, 'config' => ['ready' => true], 'idempotency_key' => (string) Str::uuid()]), 422, 'Client cannot forge module readiness/config');
$as($owner); $status($http('/lab/'.$sibling->uuid.'/modules/'.$module->uuid.'/state', 'PATCH', ['state' => 'disabled', 'expected_revision' => 3]), 404, 'Wrong project HTTP module binding denied');
formResult('managed module capabilities and control API');
