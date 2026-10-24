<?php

// Requires: postman simulation run postman/simulations/managed-modules.sim.yaml --port 4900 --no-history
require __DIR__.'/managed-module-bootstrap.php';

use App\Lab\Modules\ModuleInstallations;
use App\Lab\Modules\ModuleSchema;
use App\Lab\Modules\OperationLimiter;
use App\Lab\Modules\SchemaProposals;
use App\Models\LabChangeProposal;
use App\Models\LabModuleInstallation;
use App\Models\UserDatastoreLink;
use Illuminate\Support\Str;

config(['services.supabase.api_url' => 'http://127.0.0.1:4900/supabase']);
Illuminate\Support\Facades\Http::allowStrayRequests(['http://127.0.0.1:4900/supabase/*']);
$link = UserDatastoreLink::create(['user_id' => $owner->id, 'driver' => 'supabase', 'host_url' => 'http://127.0.0.1:4900/supabase', 'publishable_token' => 'fictional-public', 'steward_token' => 'fictional-private', 'access_token' => 'fictional-management', 'project_ref' => 'fixturemodules']);
$allow('contact');
$modules = app(ModuleInstallations::class); $service = app(SchemaProposals::class); $limiter = app(OperationLimiter::class);
$modules->select($owner, $project, 'contact', true, 0);
$module = $modules->prepare($owner, $project, 'contact', 1, [], (string) Str::uuid())['installation'];
$proposal = $service->prepare($owner, $project, $module, 1);
$detail = $service->detail($owner, $project, $proposal);
formCheck($detail['sql'] === ModuleSchema::sql('contact') && $proposal->payload['sql_sha256'] === hash('sha256', $detail['sql']), 'Proposal preserves exact reviewed SQL bytes');
formCheck($proposal->expires_at->diffInSeconds(now(), true) <= 901 && $proposal->state === 'prepared', 'Owner proposal expires in fifteen minutes');
formCheck(!str_contains(json_encode($detail), 'fictional-private') && !str_contains(json_encode($detail), 'fictional-management'), 'Proposal exposes no credentials');
try { $proposal->payload = []; $proposal->save(); formCheck(false, 'Approval material immutable'); }
catch (LogicException) { formCheck(true, 'Approval material immutable'); }
$proposal->refresh();
formCheck(ModuleSchema::hash(array_reverse($proposal->payload, true)) === $proposal->impact_hash, 'Approval hash survives MySQL JSON key reordering');
$expect(fn () => $service->detail($owner, $sibling, $proposal), 404, 'Same owner wrong project denied');
$expect(fn () => $service->detail($other, $foreign, $proposal), 404, 'Other owner same remote ref denied');
$expect(fn () => $service->apply($owner, $project, $proposal, str_repeat('0', 64), 1), 409, 'Forged hash cannot approve');
$expect(fn () => $service->apply($owner, $project, $proposal, $proposal->impact_hash, 2), 409, 'Forged base revision cannot approve');
$cancelled = $service->prepare($owner, $project, $module, 1);
$service->cancel($owner, $project, $cancelled);
$expect(fn () => $service->apply($owner, $project, $cancelled->fresh(), $cancelled->impact_hash, 1), 409, 'Cancelled proposal performs no schema change');
$expired = $service->prepare($owner, $project, $module, 1);
Illuminate\Support\Carbon::setTestNow(now()->addMinutes(16));
$expect(fn () => $service->apply($owner, $project, $expired, $expired->impact_hash, 1), 409, 'Expired approval denied');
Illuminate\Support\Carbon::setTestNow();
$link->forceFill(['project_ref' => 'anotherfixture'])->save();
$expect(fn () => $service->apply($owner, $project, $proposal, $proposal->impact_hash, 1), 503, 'Changed selected database cannot receive approved SQL');
$link->forceFill(['project_ref' => 'fixturemodules'])->save();
$stale = $service->prepare($owner, $project, $module, 1);
$receipt = $service->apply($owner, $project, $proposal, $proposal->impact_hash, 1);
formCheck($receipt['verified'] === true && $receipt['project_uuid'] === $project->uuid && $receipt['module_uuid'] === $module->uuid, 'Real HTTP Management plus REST binding verified');
formCheck($module->fresh()->status === 'ready' && $module->fresh()->schema_version === 1 && !$module->fresh()->enabled && $module->fresh()->revision === 2, 'Only verified schema becomes ready and remains disabled');
formCheck($service->apply($owner, $project, $proposal->fresh(), $proposal->impact_hash, 1) === $receipt, 'Exact Apply replay returns saved receipt');
$expect(fn () => $service->apply($owner, $project, $stale, $stale->impact_hash, 1), 409, 'Stale module proposal rejected');
$allow('contact', false); $project->forceFill(['frozen_at' => now()])->save();
formCheck($service->apply($owner, $project, $proposal->fresh(), $proposal->impact_hash, 1) === $receipt, 'Applied receipt readable after downgrade and freezing');
$project->forceFill(['frozen_at' => null])->save(); $allow('contact');
foreach (['deniedfixture', 'invalidmarker'] as $ref) {
    $module->refresh()->forceFill(['supabase_project_ref' => $ref, 'status' => 'prepared', 'schema_version' => 0])->save();
    $link->forceFill(['project_ref' => $ref])->save();
    $failed = $service->prepare($owner, $project, $module, 2);
    $expect(fn () => $service->apply($owner, $project, $failed, $failed->impact_hash, 2), 503, 'Unsuccessful or unverified upstream does not activate '.$ref);
    formCheck($module->fresh()->status === 'prepared' && $failed->fresh()->state === 'prepared' && !$module->fresh()->enabled, 'Failure leaves safe retryable metadata '.$ref);
}
$module->refresh()->forceFill(['supabase_project_ref' => 'fixturemodules'])->save(); $link->forceFill(['project_ref' => 'fixturemodules'])->save();
$first = $limiter->acquire($owner, $project, 'build', (string) Str::uuid());
$second = $limiter->acquire($owner, $sibling, 'import', (string) Str::uuid());
$expect(fn () => $limiter->acquire($owner, $project, 'import', (string) Str::uuid()), 429, 'Build/import combined quota two per account');
$independent = $limiter->acquire($other, $foreign, 'build', (string) Str::uuid());
formCheck($independent->status === 'active', 'Another account has independent capacity');
formCheck($limiter->acquire($owner, $project, 'build', $first->operation_key)->uuid === $first->uuid, 'Same active lease retry is stable');
$limiter->finalize($owner, $project, $first, true); $limiter->finalize($owner, $project, $first, true);
formCheck($first->fresh()->status === 'completed', 'Finalization is idempotent');
$third = $limiter->acquire($owner, $project, 'build', (string) Str::uuid());
Illuminate\Support\Carbon::setTestNow(now()->addMinutes(6));
$renewed = $limiter->acquire($owner, $project, 'build', $third->operation_key);
formCheck($renewed->uuid !== $third->uuid, 'Crash expiry renews lease with fresh fence');
$limiter->finalize($owner, $project, $third, false);
formCheck($renewed->fresh()->status === 'active', 'Expired worker cannot finalize renewed lease');
Illuminate\Support\Carbon::setTestNow();
$schemaLease = $limiter->acquire($owner, $project, 'schema_apply', (string) Str::uuid());
$expect(fn () => $limiter->acquire($owner, $project, 'schema_apply', (string) Str::uuid()), 429, 'Only one schema operation per project');
$siblingLease = $limiter->acquire($owner, $sibling, 'schema_apply', (string) Str::uuid());
formCheck($siblingLease->status === 'active', 'Sibling schema has its own capacity');
$expect(fn () => $limiter->acquire($other, $project, 'build', (string) Str::uuid()), 403, 'Lease cannot cross account');

// Exercise the real session/verification/CSRF middleware and strict API body.
app()->instance(Deep42\Hitchhiker\Contracts\InstallationStateManager::class, new class implements Deep42\Hitchhiker\Contracts\InstallationStateManager {
    public function isInstalled(): bool { return true; }
    public function markInstalled(): void { throw new RuntimeException('No installation mutation'); }
});
config(['app.env' => 'local', 'app.debug' => false]); app()->instance('env', 'local');
app('session')->forgetDrivers(); app()->forgetInstance('session.store'); app('redirect')->setSession(app('session')->driver()); Illuminate\Support\Facades\Auth::forgetGuards();
$as = function (?App\Models\User $actor) { app('session')->driver()->invalidate(); app('session')->driver()->regenerateToken(); Illuminate\Support\Facades\Auth::guard('web')->forgetUser(); if ($actor) Illuminate\Support\Facades\Auth::guard('web')->login($actor); app('cache')->store('array')->flush(); };
$http = function (string $path, string $method = 'GET', mixed $body = null, bool $csrf = true) {
    $session = app('session')->driver(); $session->start(); $session->save(); $name = config('session.cookie'); $encrypter = app('encrypter');
    $cookie = $encrypter->encrypt(Illuminate\Cookie\CookieValuePrefix::create($name, $encrypter->getKey()).$session->getId(), false);
    $server = ['HTTP_ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json', 'REMOTE_ADDR' => '192.0.2.53'];
    if ($csrf) $server['HTTP_X_CSRF_TOKEN'] = $session->token();
    $request = Illuminate\Http\Request::create($path, $method, [], [$name => $cookie], [], $server, is_string($body) ? $body : json_encode($body));
    return app(Illuminate\Contracts\Http\Kernel::class)->handle($request);
};
$url = '/lab/'.$project->uuid.'/modules/proposals/'.$proposal->uuid;
$as(null); formCheck($http($url)->getStatusCode() === 401, 'Unauthenticated proposal read denied');
$other->forceFill(['email_verified_at' => null])->save(); $as($other); formCheck($http($url)->getStatusCode() === 403, 'Unverified proposal read denied');
$other->forceFill(['email_verified_at' => now()])->save(); $as($other); formCheck($http($url)->getStatusCode() === 403, 'Foreign owner proposal denied');
$as($owner);
formCheck($http('/lab/'.$sibling->uuid.'/modules/proposals/'.$proposal->uuid)->getStatusCode() === 404, 'Same owner wrong route project denied');
$read = $http($url);
formCheck($read->getStatusCode() === 200 && str_contains($read->headers->get('Cache-Control'), 'no-store'), 'Owner reads immutable approval and receipt without caching');
formCheck($http($url.'/apply', 'POST', ['impact_hash' => $proposal->impact_hash, 'expected_revision' => 1], false)->getStatusCode() === 419, 'Apply requires real CSRF token');
formCheck($http($url.'/apply', 'POST', ['impact_hash' => $proposal->impact_hash, 'expected_revision' => 1, 'acknowledge_destructive' => true])->getStatusCode() === 422, 'Forged acknowledgement field cannot authorize Apply');
formCheck($http($url.'/apply', 'POST', ['impact_hash' => $proposal->impact_hash, 'expected_revision' => '1'])->getStatusCode() === 422, 'Approval revision must be an actual integer');
formCheck($http($url.'/apply', 'POST', ['impact_hash' => $proposal->impact_hash, 'expected_revision' => 1])->getStatusCode() === 200, 'HTTP Apply replay returns receipt');
formCheck($http($url.'/cancel', 'POST', '{}')->getStatusCode() === 409, 'Applied proposal cannot be cancelled');
formCheck($http('/lab/'.$project->uuid.'/modules/'.$module->uuid.'/proposals', 'POST', ['expected_revision' => 2, 'sql' => 'DROP TABLE fictional'])->getStatusCode() === 422, 'Proposal API never accepts caller SQL');
formResult('scoped schema approvals and operation leases over Postman HTTP simulation');
