<?php

// Run local Postman simulation4900 and PostgreSQL adapter4910 first. Fictional data only.
require __DIR__.'/managed-module-bootstrap.php';
(require __DIR__.'/../../database/migrations/2026_08_16_081000_create_lab_publications_table.php')->up();

use App\Lab\Modules\ContactMessages;
use App\Lab\Modules\ModuleInstallations;
use App\Lab\Modules\SchemaProposals;
use App\Models\UserDatastoreLink;
use Illuminate\Support\Str;

config(['services.supabase.api_url' => 'http://127.0.0.1:4900/supabase', 'app.url' => 'http://localhost:8892', 'app.debug' => false]);
Illuminate\Support\Facades\Http::allowStrayRequests(['http://127.0.0.1:4900/supabase/*']);
UserDatastoreLink::create(['user_id' => $owner->id, 'driver' => 'supabase', 'host_url' => 'http://127.0.0.1:4900/supabase/pglite', 'publishable_token' => 'fictional-public', 'steward_token' => 'fictional-private', 'access_token' => 'fictional-management', 'project_ref' => 'pglitefixture']);
$allow('contact'); $modules = app(ModuleInstallations::class); $proposals = app(SchemaProposals::class); $contacts = app(ContactMessages::class);
$modules->select($owner, $project, 'contact', true, 0);
$module = $modules->prepare($owner, $project, 'contact', 1, [], (string) Str::uuid())['installation'];
$proposal = $proposals->prepare($owner, $project, $module, 1);
$receipt = $proposals->apply($owner, $project, $proposal, $proposal->impact_hash, 1);
formCheck($receipt['verified'] && $module->fresh()->status === 'ready', 'Actual PostgreSQL schema and registered binding through real HTTP simulation');
config(['lab-modules.business_writes_enabled' => true]); // Isolated test process only.
$modules->setState($owner, $project, $module->fresh(), 'enabled', 2);
$module->refresh();
app()->instance('env', 'local');
app()->instance(Deep42\Hitchhiker\Contracts\InstallationStateManager::class, new class implements Deep42\Hitchhiker\Contracts\InstallationStateManager {
    public function isInstalled(): bool { return true; }
    public function markInstalled(): void { throw new RuntimeException('No installation mutation'); }
});
$http = function (array $body, ?string $origin = 'http://localhost:8892', string $method = 'POST') use ($module) {
    $server = ['HTTP_ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json', 'REMOTE_ADDR' => '192.0.2.54'];
    if ($origin !== null) $server['HTTP_ORIGIN'] = $origin;
    $request = Illuminate\Http\Request::create('/api/lab-widgets/'.$module->uuid.'/contact', $method, [], [], [], $server, json_encode($body));
    return app(Illuminate\Contracts\Http\Kernel::class)->handle($request);
};
$values = ['idempotency_key' => (string) Str::uuid(), 'name' => 'ผู้ทดสอบสมมติ', 'email' => 'fictional@example.invalid', 'phone' => '', 'subject' => 'ทดสอบฟอร์มติดต่อ', 'message' => 'ข้อความทดสอบการบันทึกฐานข้อมูลจริง', 'consent' => true, 'website' => ''];
$denied = $http($values, 'https://attacker.invalid');
formCheck($denied->getStatusCode() === 403, 'Wrong origin cannot submit, got '.$denied->getStatusCode().' '.$denied->getContent());
formCheck($http($values, null)->getStatusCode() === 403, 'Missing origin fails closed');
$preflight = $http([], 'http://localhost:8892', 'OPTIONS');
formCheck($preflight->getStatusCode() === 204 && $preflight->headers->get('Access-Control-Allow-Origin') === 'http://localhost:8892' && !$preflight->headers->has('Access-Control-Allow-Credentials'), 'Exact credential-free CORS preflight');
$first = $http($values); $saved = json_decode($first->getContent(), true);
formCheck($first->getStatusCode() === 201 && Str::isUuid($saved['reference'] ?? '') && ($saved['status'] ?? null) === 'new', 'Public form persists actual PostgreSQL row and returns minimal receipt');
formCheck(array_keys($saved) === ['reference','status'] && str_contains($first->headers->get('Cache-Control'), 'no-store'), 'Public receipt does not expose contact PII');
$again = $http($values);
formCheck($again->getStatusCode() === 200 && json_decode($again->getContent(), true) === $saved, 'Lost response retry returns original row without duplication');
formCheck($http([...$values, 'message' => 'changed'])->getStatusCode() === 409, 'Same idempotency key changed answers conflict');
formCheck($http([...$values, 'idempotency_key' => (string) Str::uuid(), 'status' => 'read'])->getStatusCode() === 422, 'Public cannot choose controlled status');
formCheck($http([...$values, 'idempotency_key' => (string) Str::uuid(), 'consent' => 'true'])->getStatusCode() === 422, 'String consent rejected');
formCheck($http([...$values, 'idempotency_key' => (string) Str::uuid(), 'website' => 'bot'])->getStatusCode() === 422, 'Honeypot blocks automated garbage');
$listing = $contacts->listing($owner, $project, $module);
formCheck(count($listing['items']) === 1 && $listing['items'][0]['message'] === $values['message'], 'Owner inbox reads the persisted database message');
$expect(fn () => $contacts->listing($owner, $sibling, $module), 404, 'Same owner other project cannot read inbox');
$expect(fn () => $contacts->listing($other, $foreign, $module), 404, 'Another account cannot read inbox');
$transition = $contacts->transition($owner, $project, $module, $saved['reference'], 1, 'read');
formCheck($transition['status'] === 'read' && $transition['version'] === 2, 'Owner CAS marks message read');
$expect(fn () => $contacts->transition($owner, $project, $module, $saved['reference'], 1, 'archived'), 409, 'Stale inbox change cannot overwrite accepted status');
formCheck($contacts->transition($owner, $project, $module, $saved['reference'], 2, 'archived')['version'] === 3, 'Archive retains message and advances audit history');
$modules->setState($owner, $project, $module, 'disabled', 3);
formCheck(count($contacts->listing($owner, $project, $module->fresh())['items']) === 1, 'Disable preserves owner history');
formCheck($http($values)->getStatusCode() === 200, 'Exact accepted retry remains replayable after disable');
formCheck($http([...$values, 'idempotency_key' => (string) Str::uuid()])->getStatusCode() === 409, 'Disabled module rejects new customer messages');
formCheck($http([], 'http://localhost:8892', 'GET')->getStatusCode() === 405, 'Anonymous API has no inbox/list action');
formResult('actual contact flow over Postman HTTP and local PostgreSQL');
