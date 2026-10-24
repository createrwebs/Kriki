<?php

// Isolate provider bootstrap too, before the shared harness changes its connection.
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=:memory:');

require __DIR__.'/forms-bootstrap.php';

use App\Http\Controllers\Lab\LabPreviewController;
use App\Lab\SiteWorkspace;
use App\Models\LabProject;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

$owner = new User;
$owner->forceFill(['id' => 701]);
$phpProject = new LabProject;
$phpProject->forceFill(['uuid' => 'f956e2ae-3dcb-4e1b-812e-990d958aaea6', 'user_id' => 701, 'project_type' => 'php', 'title' => '<script>not trusted</script>', 'workspace_status' => 'ready', 'stack' => SiteWorkspace::PHP_STACK]);
$fixtureRoot = sys_get_temp_dir().DIRECTORY_SEPARATOR.'krikkit-runtime-'.bin2hex(random_bytes(12));
$previousStorage = app()->storagePath();
app()->useStoragePath($fixtureRoot);
$workspace = new SiteWorkspace;
$projectRoot = $workspace->root($phpProject);
mkdir($projectRoot, 0700, true);
copy(__DIR__.'/../fixtures/runtime/untrusted-preview.php', $projectRoot.'/index.php');
file_put_contents($projectRoot.'/.krikkit-kit', '3');
$controller = app(LabPreviewController::class);
$request = Request::create('/lab/'.$phpProject->uuid.'/php-preview', 'GET', [], [], [], ['HTTP_ACCEPT' => 'application/json']);
$request->setUserResolver(fn () => $owner);

try {
    $response = app()->call([$controller, 'php'], ['request' => $request, 'project' => $phpProject]);
    // This must run first: Red demonstrates actual old execution, not a missing class.
    formCheck(! file_exists($projectRoot.'/executed.txt'), 'PHP preview never executes an untrusted workspace file');
    formCheck($response->getStatusCode() === 423, 'Unavailable PHP runtime returns423');
    $payload = json_decode($response->getContent(), true);
    formCheck(($payload['code'] ?? null) === 'runtime_unavailable', 'JSON response has stable runtime error code');
    formCheck(($payload['runtime']['mode'] ?? null) === 'source_only' && ! $payload['runtime']['can_execute'], 'Unsupported runtime is source only');
    formCheck(! str_contains($response->getContent(), 'UNTRUSTED_PHP_FIXTURE_OUTPUT'), 'Response does not expose source output');

    $request->headers->set('Accept', 'text/html');
    $html = app()->call([$controller, 'php'], ['request' => $request, 'project' => $phpProject]);
    formCheck($html->getStatusCode() === 423 && str_contains($html->getContent(), 'runtime-unavailable'), 'Browser receives trusted unavailable notice');
    formCheck(! str_contains($html->getContent(), '<script>not trusted</script>'), 'Untrusted project title is escaped');
    formCheck(str_contains($html->getContent(), '/lab/'.$phpProject->uuid.'/workspace'), 'Notice links back to owner workspace');
    formCheck(str_contains($html->headers->get('Content-Security-Policy'), "default-src 'none'") && str_contains($html->headers->get('Cache-Control'), 'no-store'), 'Notice cannot execute scripts and is not cached');

    $request->headers->set('Accept', 'application/json');
    $fullPage = app()->call([$controller, '__invoke'], ['request' => $request, 'project' => $phpProject]);
    formCheck($fullPage->getStatusCode() === 423, 'Full-page preview cannot bypass source-only policy');
    $unknownProject = clone $phpProject;
    $unknownProject->project_type = 'python';
    $unknown = app()->call([$controller, '__invoke'], ['request' => $request, 'project' => $unknownProject]);
    formCheck($unknown->getStatusCode() === 423, 'Unknown backend runtime fails closed');
    $missingProject = clone $phpProject;
    $missingProject->uuid = 'e3a8d3f7-789d-45aa-9da5-4af9cbd0fced';
    $blocked = app()->call([$controller, 'php'], ['request' => $request, 'project' => $missingProject]);
    formCheck($blocked->getStatusCode() === 423 && ! is_dir($workspace->root($missingProject)), 'Unavailable preview does not create a workspace');
    $request->query->set('path', '../../anything.php');
    $blockedPath = app()->call([$controller, 'php'], ['request' => $request, 'project' => $phpProject]);
    formCheck($blockedPath->getStatusCode() === 423 && ! file_exists($projectRoot.'/executed.txt'), 'Path changes cannot bypass the disabled runtime');

    $request->setUserResolver(fn () => null);
    try {
        app()->call([$controller, 'php'], ['request' => $request, 'project' => $phpProject]);
        formCheck(false, 'Guest cannot reach project runtime notice');
    } catch (AuthorizationException) {
        formCheck(true, 'Guest remains denied before runtime handling');
    }
    $other = new User;
    $other->forceFill(['id' => 702]);
    $request->setUserResolver(fn () => $other);
    try {
        app()->call([$controller, 'php'], ['request' => $request, 'project' => $phpProject]);
        formCheck(false, 'Different owner cannot reach runtime notice');
    } catch (AuthorizationException) {
        formCheck(true, 'Different owner remains denied');
    }

    $request->setUserResolver(fn () => $owner);
    $reactProject = clone $phpProject;
    $reactProject->project_type = 'react';
    try {
        app()->call([$controller, 'php'], ['request' => $request, 'project' => $reactProject]);
        formCheck(false, 'PHP endpoint does not accept React projects');
    } catch (Symfony\Component\HttpKernel\Exception\HttpException $error) {
        formCheck($error->getStatusCode() === 404, 'React on PHP-only endpoint remains404');
    }
    $policy = app(App\Lab\Runtime\RuntimePolicy::class);
    $reactProject->stack = SiteWorkspace::STACK;
    formCheck($policy->verdict($reactProject)['mode'] === 'browser_static' && $policy->verdict($reactProject)['can_execute'], 'React browser runtime remains available');
    $request->query->set('path', '//outside.example');
    $reactResponse = app()->call([$controller, '__invoke'], ['request' => $request, 'project' => $reactProject]);
    formCheck($reactResponse->getStatusCode() === 200 && str_contains($reactResponse->getContent(), 'lab-preview-bootstrap'), 'React full-page preview still renders the existing shell');
    formCheck($reactResponse->getOriginalContent()->getData()['guestPath'] === '/', 'React preview retains guest path sanitization');
    formCheck($reactResponse->headers->get('Cross-Origin-Opener-Policy') === 'same-origin' && $reactResponse->headers->get('Cross-Origin-Embedder-Policy') === 'credentialless', 'React preview retains cross-origin isolation headers');
    $reactProject->project_type = null;
    formCheck($policy->verdict($reactProject)['can_execute'], 'Legacy unspecified project type retains React behavior');
    $reactProject->stack = SiteWorkspace::PHP_STACK;
    formCheck(! $policy->verdict($reactProject)['can_execute'], 'PHP stack cannot bypass policy with legacy project type');
    config(['lab.runtime.allow_php' => true]);
    formCheck(! $policy->verdict($phpProject)['can_execute'], 'Configuration flag cannot enable in-process PHP');
    formCheck(file_get_contents($projectRoot.'/index.php') === file_get_contents(__DIR__.'/../fixtures/runtime/untrusted-preview.php'), 'PHP source remains unchanged');
    formResult('runtime execution stopgap');
} finally {
    app()->useStoragePath($previousStorage);
    foreach (['executed.txt', 'index.php', '.krikkit-kit'] as $fixtureFile) {
        if (is_file($projectRoot.'/'.$fixtureFile)) unlink($projectRoot.'/'.$fixtureFile);
    }
    foreach ([$projectRoot, $fixtureRoot.'/app/lab/projects', $fixtureRoot.'/app/lab', $fixtureRoot.'/app', $fixtureRoot] as $fixtureDirectory) rmdir($fixtureDirectory);
}
