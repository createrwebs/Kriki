<?php
require __DIR__.'/form-owner-api.php';

use Illuminate\Http\Request;
use Illuminate\Contracts\Http\Kernel;

(require __DIR__.'/../../database/migrations/2026_08_16_081000_create_lab_publications_table.php')->up();

$form->forceFill(['status' => 'active', 'active_version' => 2])->save();
$kernel = app(Kernel::class);
$get = function (string $path, string $method = 'GET', array $headers = [], string $body = '') use ($kernel) {
    $server = ['HTTP_ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json'];
    foreach ($headers as $key => $value) $server['HTTP_'.strtoupper(str_replace('-', '_', $key))] = $value;
    return $kernel->handle(Request::create('http://localhost:8889'.$path, $method, [], [], [], $server, $body));
};
$path = '/api/lab-forms/'.$form->uuid;
$response = $get($path);
formCheck($response->getStatusCode() === 200 && str_contains($response->headers->get('Content-Type'), 'json'), 'API returns JSON not SPA HTML through HTTP kernel');
formCheck(json_decode($response->getContent(), true)['version'] === 2, 'Route binding returns active definition');
$response = $get($path, 'OPTIONS', ['Origin' => 'http://localhost:8889', 'Access-Control-Request-Method' => 'POST']);
formCheck($response->getStatusCode() === 204 && $response->headers->get('Access-Control-Allow-Origin') === 'http://localhost:8889', 'Exact local preflight allowed');
formCheck(! $response->headers->has('Access-Control-Allow-Credentials'), 'Public API not credentialed');
foreach (['null', 'https://evil.example', 'http://localhost:8889.evil.example'] as $origin) {
    $response = $get($path, 'OPTIONS', ['Origin' => $origin, 'Access-Control-Request-Method' => 'POST']);
    formCheck($response->getStatusCode() === 403 && ! $response->headers->has('Access-Control-Allow-Origin'), 'Untrusted preflight rejected: '.$origin.' status '.$response->getStatusCode());
}
$response = $get($path.'/submissions', 'POST', ['Origin' => 'http://localhost:8889'], '{}');
formCheck($response->getStatusCode() === 409 && $response->headers->get('Access-Control-Allow-Origin') === 'http://localhost:8889', 'Error response retains exact CORS origin');
$response = $get($path.'/submissions', 'GET');
formCheck($response->getStatusCode() === 405, 'No public list route');
$response = $get('/lab/'.$project->uuid.'/forms');
formCheck($response->getStatusCode() === 401, 'Owner routes require authentication through kernel');
$response = $get($path.'/submissions', 'POST', [], str_repeat('x', 17000));
formCheck($response->getStatusCode() === 413, 'Large body rejected before parsing');
App\Models\LabPublication::query()->create(['lab_project_id' => $project->id, 'kind' => 'subdomain', 'subdomain' => 'fixture-booking', 'status' => 'live']);
$localUrl = app(App\Lab\Publish\LabPublishConfig::class)->subdomainUrl('fixture-booking');
formCheck($localUrl === 'http://fixture-booking.localhost:8889', 'Local publication URL preserves app port');
$response = $get($path, 'OPTIONS', ['Origin' => $localUrl, 'Access-Control-Request-Method' => 'POST']);
formCheck($response->getStatusCode() === 204 && $response->headers->get('Access-Control-Allow-Origin') === $localUrl, 'Exact live local publication allowed');
formResult('form HTTP pipeline/CORS');
