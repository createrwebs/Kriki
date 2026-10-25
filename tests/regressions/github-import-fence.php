<?php

require __DIR__.'/managed-module-bootstrap.php';
(require __DIR__.'/../../database/migrations/2026_08_19_060800_create_lab_project_remotes_table.php')->up();

use App\Http\Controllers\Lab\LabGithubController;
use App\Lab\SiteWorkspace;
use App\Models\LabOperationLease;
use Illuminate\Http\Client\Events\ResponseReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

$base = 'http://127.0.0.1:4904/github';
config(['services.github.api_url' => $base, 'lab.github.timeout' => 5]);
Http::allowStrayRequests([$base.'/*']);
$plan->grants()->create(['code' => App\Entitlement\EntitlementCatalog::GITHUB_IMPORT, 'kind' => 'feature', 'allowed' => true, 'window' => 'none']);
$owner->unsetRelation('entitlement');
$fixtureRoot = base_path('.agent-work/qa/github-fence-'.Str::uuid());
$originalStorage = app()->storagePath(); app()->useStoragePath($fixtureRoot);
$workspace = app(SiteWorkspace::class); $root = $workspace->ensure($project);
File::put($root.'/src/App.jsx', 'LAST GOOD SOURCE');
$revision = $workspace->importRevision($project);
$case = 'expired'; $changed = false;
Event::listen(ResponseReceived::class, function (ResponseReceived $event) use (&$case, &$changed, $project) {
    if ($case === 'valid' || $changed || !str_contains($event->request->url(), '/git/blobs/')) return;
    $changed = true;
    $lease = LabOperationLease::where('lab_project_id', $project->id)->where('operation', 'import')->where('status', 'active')->firstOrFail();
    match ($case) {
        'expired' => $lease->update(['expires_at' => now()->subSecond()]),
        'cancelled' => $lease->update(['status' => 'failed']),
        'replaced' => $lease->update(['uuid' => (string) Str::uuid()]),
        'frozen' => $project->update(['frozen_at' => now()]),
    };
});
try {
    foreach (['expired','cancelled','replaced','frozen'] as $case) {
        $changed = false;
        $project->forceFill(['frozen_at' => null])->save();
        LabOperationLease::where('lab_project_id', $project->id)->update(['status' => 'failed']);
        Http::post($base.'/__qa/reset')->throw();
        $request = Request::create('/lab/github/import', 'POST', ['url' => 'https://github.com/fictional/healthy', 'project_uuid' => $project->uuid, 'branch' => 'main', 'root_directory' => 'app']);
        app()->instance('request', $request); $request->setUserResolver(fn () => $owner);
        $status = null;
        try { $status = app()->call([app(LabGithubController::class), 'import'], ['request' => $request])->getStatusCode(); }
        catch (Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $error) { $status = $error->getStatusCode(); }
        catch (Illuminate\Auth\Access\AuthorizationException) { $status = 403; }
        formCheck($changed, 'Fixture invalidated actual in-flight admission: '.$case);
        formCheck($workspace->importRevision($project) === $revision, 'Stale import cannot promote after '.$case);
        formCheck($status === ($case === 'frozen' ? 403 : 409), 'Authoritative promotion failure status: '.$case);
        formCheck(glob($root.'.import-*') === [] && glob($root.'.previous-*') === [], 'Rejected promotion cleans only its stage: '.$case);
        if ($case === 'replaced') formCheck(LabOperationLease::where('lab_project_id', $project->id)->where('status', 'active')->count() === 1, 'Old worker cannot finalize the replacement lease');
    }
    $case = 'valid';
    $project->forceFill(['frozen_at' => null])->save();
    LabOperationLease::where('lab_project_id', $project->id)->update(['status' => 'failed']);
    Http::post($base.'/__qa/reset')->throw();
    $success = app()->call([app(LabGithubController::class), 'import'], ['request' => $request]);
    formCheck($success->getStatusCode() === 200 && $success->getData(true)['commit_sha'] === str_repeat('1',40), 'Live admission promotes exact pinned source');
    formCheck(str_contains(File::get($root.'/src/App.jsx'), 'Fictional source only'), 'Successful promotion changes actual source bytes');
    formCheck(LabOperationLease::where('lab_project_id', $project->id)->where('status', 'completed')->count() === 1, 'Only the successful admission completes');

    $limiter = app(App\Lab\Modules\OperationLimiter::class);
    $lease = $limiter->acquire($owner, $project, 'import', (string) Str::uuid());
    $expect(fn () => Illuminate\Support\Facades\DB::transaction(fn () => $limiter->assertImportPromotion($other, $project, $lease)), 403, 'Foreign actor cannot promote');
    $expect(fn () => Illuminate\Support\Facades\DB::transaction(fn () => $limiter->assertImportPromotion($owner, $sibling, $lease)), 404, 'Sibling project cannot consume admission');
    $build = $limiter->acquire($owner, $project, 'build', (string) Str::uuid());
    $expect(fn () => Illuminate\Support\Facades\DB::transaction(fn () => $limiter->assertImportPromotion($owner, $project, $build)), 404, 'Build lease cannot authorize import');
    $tree = $fixtureRoot.'/source-tree'; File::ensureDirectoryExists($tree); File::put($tree.'/index.html', 'UNPROMOTED');
    $after = $workspace->importRevision($project);
    $expect(fn () => $workspace->importTree($project, $tree, replace:true, beforePromotion:fn () => abort(409)), 409, 'Archive/tree source invokes the promotion boundary');
    formCheck($workspace->importRevision($project) === $after, 'Rejected archive/tree promotion preserves latest source');
    formResult('GitHub import promotion lease fencing');
} finally {
    app()->useStoragePath($originalStorage);
    $qaRoot = realpath(base_path('.agent-work/qa')); $resolved = realpath($fixtureRoot);
    if (!$qaRoot || !$resolved || !str_starts_with($resolved, $qaRoot.DIRECTORY_SEPARATOR)) throw new RuntimeException('Refuse out-of-scope fixture cleanup');
    File::deleteDirectory($resolved);
}
