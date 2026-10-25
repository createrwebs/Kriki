<?php

putenv('DB_CONNECTION=sqlite'); putenv('DB_DATABASE=:memory:');
putenv('CACHE_STORE=array'); putenv('SESSION_DRIVER=array'); putenv('LOG_CHANNEL=null');
putenv('APP_CONFIG_CACHE='.__DIR__.'/no-module-config-cache.php');
require __DIR__.'/forms-bootstrap.php';

use App\Http\Controllers\Lab\LabDatastoreController;
use App\Models\LabModuleInstallation;
use App\Models\LabProject;
use App\Models\User;
use App\Models\UserDatastoreLink;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

Schema::create('users', function (Blueprint $table) { $table->id(); $table->string('name'); $table->string('email'); $table->string('password'); $table->timestamp('email_verified_at')->nullable(); $table->timestamps(); });
(require __DIR__.'/../../database/migrations/2026_08_04_051600_create_lab_projects_table.php')->up();
Schema::table('lab_projects', function (Blueprint $table) { $table->timestamp('frozen_at')->nullable(); $table->timestamp('opened_at')->nullable(); });
(require __DIR__.'/../../database/migrations/2026_08_18_021000_create_user_datastore_links_table.php')->up();
(require __DIR__.'/../../database/migrations/2026_10_17_110000_create_lab_module_control_tables.php')->up();
$owner = User::withoutEvents(fn () => User::forceCreate(['name' => 'Scope fixture', 'email' => 'scope-owner@example.invalid', 'password' => 'fixture', 'email_verified_at' => now()]));
$other = User::withoutEvents(fn () => User::forceCreate(['name' => 'Other fixture', 'email' => 'scope-other@example.invalid', 'password' => 'fixture', 'email_verified_at' => now()]));
$project = LabProject::create(['user_id' => $owner->id, 'title' => 'Scope A']);
$sibling = LabProject::create(['user_id' => $owner->id, 'title' => 'Scope B']);
$foreign = LabProject::create(['user_id' => $other->id, 'title' => 'Scope C']);
$link = UserDatastoreLink::create(['user_id' => $owner->id, 'driver' => 'supabase', 'host_url' => 'https://scopefixture.supabase.co', 'publishable_token' => 'scope-public-secret-marker', 'steward_token' => 'scope-private-secret-marker', 'access_token' => 'scope-console-secret-marker', 'project_ref' => 'scopefixture']);
foreach ([[$project, $owner, 'contact'], [$sibling, $owner, 'news'], [$foreign, $other, 'catalog']] as [$target, $actor, $key]) {
    LabModuleInstallation::create(['uuid' => (string) Str::uuid(), 'user_id' => $actor->id, 'lab_project_id' => $target->id, 'key' => $key, 'supabase_project_ref' => 'scopefixture', 'revision' => 1, 'schema_version' => 1, 'status' => 'ready', 'enabled' => true, 'config' => ['private' => 'configuration-secret-marker'], 'idempotency_key' => (string) Str::uuid(), 'request_hash' => str_repeat('a', 64)]);
}
Http::fake(['*' => Http::response(['definitions' => ['sibling_private_external' => ['properties' => ['private_column' => ['type' => 'string']]]]])]);
$controller = app(LabDatastoreController::class);
$invoke = function (string $action, ?User $actor, array $input = []) use ($controller) {
    $request = Request::create('/lab/datastore/'.$action, 'POST', $input);
    app()->instance('request', $request);
    $request->setUserResolver(fn () => $actor);
    return app()->call([$controller, $action], ['request' => $request]);
};
$expect = function (callable $action, int $status, string $label) {
    try { $response = $action(); formCheck($response->getStatusCode() === $status, $label); }
    catch (Illuminate\Validation\ValidationException) { formCheck($status === 422, $label); }
    catch (Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $error) { formCheck($error->getStatusCode() === $status, $label.' (HTTP '.$error->getStatusCode().')'); }
    catch (Illuminate\Auth\Access\AuthorizationException) { formCheck($status === 403, $label); }
};
$expect(fn () => $invoke('survey', $owner), 422, 'Missing project cannot inspect a remote-wide schema');
$expect(fn () => $invoke('survey', null, ['project_uuid' => $project->uuid]), 401, 'Anonymous survey cannot enter system fallback');
$expect(fn () => $invoke('survey', $owner, ['project_uuid' => $foreign->uuid]), 404, 'Foreign project denied before upstream');
$expect(fn () => $invoke('survey', $owner, ['project_uuid' => 'bad']), 422, 'Malformed project rejected');
$expect(fn () => $invoke('survey', $owner, ['project_uuid' => $project->uuid, 'owner_id' => $other->id]), 422, 'Caller cannot add an owner override');
foreach ([$project, $sibling, $project] as $target) {
    $payload = $invoke('survey', $owner, ['project_uuid' => $target->uuid])->getData(true);
    formCheck($payload['scope'] === 'project' && $payload['lab_project_uuid'] === $target->uuid, 'Response names the server-bound project');
    formCheck(array_column($payload['modules'], 'key') === [$target->id === $project->id ? 'contact' : 'news'], 'Alternating projects cannot reuse sibling metadata');
    formCheck($payload['tables'] === [] && $payload['client_env'] === [] && $payload['suggested_files'] === [], 'Unmapped tables and remote-wide client are not exposed');
    formCheck(!str_contains(json_encode($payload), 'secret-marker') && !str_contains(json_encode($payload), 'sibling_private_external'), 'AI output contains neither credentials, config nor external schema');
}
$link->update(['project_ref' => 'changedfixture']);
$changed = $invoke('survey', $owner, ['project_uuid' => $project->uuid])->getData(true);
formCheck(!$changed['modules'][0]['connection_matches'], 'Changing the account connection does not rebind existing modules');
$project->update(['frozen_at' => now()]);
formCheck(count($invoke('survey', $owner, ['project_uuid' => $project->uuid])->getData(true)['modules']) === 1, 'Frozen owner can still inspect registration history');
$link->delete();
$missing = $invoke('survey', $owner, ['project_uuid' => $project->uuid])->getData(true);
formCheck(!$missing['ready'] && $missing['status'] === 'missing' && !$missing['modules'][0]['connection_matches'], 'Disconnected metadata never implies a ready connection');
foreach (['records', 'catalog', 'auth', 'saveAuth', 'users', 'addUser', 'restart'] as $action) {
    $denied = $invoke($action, $owner, ['project_uuid' => $project->uuid, 'table' => 'sibling_private_external', 'topic' => 'secrets']);
    formCheck($denied->getStatusCode() === 403 && $denied->getData(true)['code'] === 'project_scope_required', 'Generic '.$action.' cannot bypass scoped APIs');
}
formCheck(Http::recorded()->isEmpty(), 'Scoped inspection and bypass denials make no remote-wide HTTP requests');
formCheck(config('lab-modules.business_writes_enabled') === false, 'Readiness remains closed');

// Real route, session, verified-email and CSRF middleware, on memory-only control data.
app()->instance(Deep42\Hitchhiker\Contracts\InstallationStateManager::class, new class implements Deep42\Hitchhiker\Contracts\InstallationStateManager {
    public function isInstalled(): bool { return true; }
    public function markInstalled(): void { throw new RuntimeException('No installation mutation'); }
});
config(['app.env' => 'local', 'app.debug' => false]); app()->instance('env', 'local');
app('session')->forgetDrivers(); app()->forgetInstance('session.store'); app('redirect')->setSession(app('session')->driver()); Illuminate\Support\Facades\Auth::forgetGuards();
$as = function (?User $actor) {
    app('session')->driver()->invalidate(); app('session')->driver()->regenerateToken();
    Illuminate\Support\Facades\Auth::guard('web')->forgetUser();
    if ($actor) Illuminate\Support\Facades\Auth::guard('web')->login($actor);
    app('cache')->store('array')->flush();
};
$http = function (string $path, string $method = 'GET', array $body = [], bool $csrf = true) {
    $session = app('session')->driver(); $session->start(); $session->save();
    $name = config('session.cookie'); $encrypter = app('encrypter');
    $cookie = $encrypter->encrypt(Illuminate\Cookie\CookieValuePrefix::create($name, $encrypter->getKey()).$session->getId(), false);
    $server = ['HTTP_ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json', 'REMOTE_ADDR' => '192.0.2.77'];
    if ($csrf) $server['HTTP_X_CSRF_TOKEN'] = $session->token();
    $request = Request::create($path, $method, [], [$name => $cookie], [], $server, json_encode($body));
    return app(Illuminate\Contracts\Http\Kernel::class)->handle($request);
};
$as(null);
formCheck($http('/lab/datastore/survey', 'POST', ['project_uuid' => $project->uuid])->getStatusCode() === 401, 'HTTP anonymous cannot survey');
$as($owner);
formCheck($http('/lab/datastore/survey', 'POST', ['project_uuid' => $project->uuid], false)->getStatusCode() === 419, 'HTTP survey requires CSRF');
formCheck($http('/lab/datastore/survey', 'POST')->getStatusCode() === 422, 'HTTP survey requires project');
formCheck($http('/lab/datastore/survey', 'POST', ['project_uuid' => $foreign->uuid])->getStatusCode() === 404, 'HTTP foreign project is not discoverable');
$current = $http('/lab/datastore/survey', 'POST', ['project_uuid' => $project->uuid]);
formCheck($current->getStatusCode() === 200 && str_contains($current->headers->get('Cache-Control'), 'no-store'), 'HTTP registration metadata never caches');
formCheck(json_decode($current->getContent(), true)['lab_project_uuid'] === $project->uuid, 'HTTP binds exact project');
foreach ([['rows?table=sibling_private_external', 'GET'], ['catalog?topic=secrets', 'GET'], ['auth', 'GET'], ['auth', 'PATCH'], ['users', 'GET'], ['users', 'POST'], ['restart', 'POST']] as [$path, $method]) {
    app('cache')->store('array')->flush(); // Isolate each route from shared throttle counters.
    $response = $http('/lab/datastore/'.$path, $method, ['project_uuid' => $project->uuid]);
    formCheck($response->getStatusCode() === 403 && json_decode($response->getContent(), true)['code'] === 'project_scope_required', 'HTTP '.$method.' '.$path.' cannot inspect account-wide data');
}
$owner->forceFill(['email_verified_at' => null])->save(); $as($owner);
formCheck($http('/lab/datastore/survey', 'POST', ['project_uuid' => $project->uuid])->getStatusCode() === 403, 'Unverified email denied');
formCheck(Http::recorded()->isEmpty(), 'Middleware paths never contact remote-wide datastore');
formResult('project-scoped datastore inspection');
