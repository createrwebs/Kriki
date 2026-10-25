<?php

require __DIR__.'/managed-module-bootstrap.php';

use App\Lab\SiteWorkspace;
use App\Models\LabProject;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

$fixtureRoot = base_path('.agent-work/qa/github-preservation-'.Str::uuid());
$originalStorage = app()->storagePath();
app()->useStoragePath($fixtureRoot);
$workspace = app(SiteWorkspace::class);
$root = $workspace->ensure($project);
$workspace->ensure($foreign);
File::put($root.'/src/App.jsx', 'ORIGINAL SOURCE');
File::put($root.'/notes.txt', 'KEEP UNTIL SUCCESS');
File::put($workspace->root($foreign).'/src/App.jsx', 'FOREIGN SOURCE');
$original = hash_file('sha256', $root.'/src/App.jsx');
$reject = function (callable $operation, string $label) use ($root, $original) {
    try { $operation(); throw new RuntimeException('Import incorrectly succeeded: '.$label); }
    catch (InvalidArgumentException) {
        formCheck(hash_file('sha256', $root.'/src/App.jsx') === $original, $label.' preserves live source');
        formCheck(File::get($root.'/notes.txt') === 'KEEP UNTIL SUCCESS', $label.' preserves unrelated live files');
    }
};
try {
    $reject(fn () => $workspace->importFileMap($project, ['src/App.jsx'=>'PARTIAL OVERWRITE', '../escape.txt'=>'UNSAFE']), 'Invalid later map path');
    $reject(fn () => $workspace->importFileMap($project, ['.git/config'=>'NO SOURCE']), 'Empty filtered import');
    config(['lab.github.max_files'=>100]);
    $files = ['src/App.jsx'=>'PARTIAL OVERWRITE'];
    for ($i=0; $i<100; $i++) $files['files/'.$i.'.txt']='FICTIONAL';
    $reject(fn () => $workspace->importFileMap($project, $files), 'Over-limit map');
    $tree = $fixtureRoot.'/download';
    File::ensureDirectoryExists($tree.'/src');
    File::put($tree.'/src/App.jsx', 'PARTIAL OVERWRITE');
    for ($i=0; $i<101; $i++) File::put($tree.'/file-'.$i.'.txt', 'FICTIONAL');
    $reject(fn () => $workspace->importTree($project, $tree), 'Over-limit tree');

    $revision = $workspace->importRevision($project);
    File::put($root.'/notes.txt', 'CONCURRENT EDIT');
    $expect(fn () => $workspace->importFileMap($project, ['src/App.jsx'=>'STALE SOURCE'], $revision), 409, 'Stale imported snapshot denied');
    formCheck(File::get($root.'/notes.txt') === 'CONCURRENT EDIT' && hash_file('sha256', $root.'/src/App.jsx') === $original, 'Conflict preserves the concurrent edit and original source');
    File::put($root.'/notes.txt', 'KEEP UNTIL SUCCESS');

    $plan->grants()->create(['code'=>App\Entitlement\EntitlementCatalog::GITHUB_IMPORT, 'kind'=>'feature', 'allowed'=>true, 'window'=>'none']);
    $owner->unsetRelation('entitlement');
    $request = Illuminate\Http\Request::create('/lab/api/github/import', 'POST', [
        'url'=>'https://github.com/fictional/example', 'project_uuid'=>$project->uuid, 'root_directory'=>'../invalid',
    ]);
    app()->instance('request', $request);
    $request->setUserResolver(fn () => $owner);
    $response = app()->call([app(App\Http\Controllers\Lab\LabGithubController::class), 'import'], ['request'=>$request]);
    formCheck($response->getStatusCode() === 422 && hash_file('sha256', $root.'/src/App.jsx') === $original, 'Controller import validation leaves the existing workspace intact: '.$response->getContent());
    formCheck(App\Models\LabOperationLease::where('lab_project_id', $project->id)->where('status', 'failed')->count() === 1, 'Rejected controller import finalizes its admission lease');

    config(['services.github.api_url'=>'http://127.0.0.1:1/github', 'lab.github.timeout'=>1, 'lab.github.connect_timeout'=>1]);
    Illuminate\Support\Facades\Http::allowStrayRequests(['http://127.0.0.1:1/github/*']);
    $upstreamPaths = [];
    Illuminate\Support\Facades\Http::globalRequestMiddleware(function ($outgoing) use (&$upstreamPaths) {
        $upstreamPaths[] = $outgoing->getUri()->getPath();
        return $outgoing;
    });
    try {
        app(App\Lab\Github\GithubRepoImporter::class)->import($project, 'fictional', 'example', 'main', rootDirectory: 'web');
        throw new RuntimeException('Unreachable upstream unexpectedly succeeded');
    } catch (Illuminate\Http\Client\ConnectionException) {
        formCheck(hash_file('sha256', $root.'/src/App.jsx') === $original, 'Actual unreachable upstream preserves previous source');
        formCheck($upstreamPaths === ['/github/repos/fictional/example/commits/main'], 'Failed subtree does not silently fall back to a second ZIP request');
    }

    $failSave = true;
    LabProject::saving(function ($saving) use ($project, &$failSave) {
        if ($failSave && $saving->id === $project->id) throw new RuntimeException('Fictional metadata failure');
    });
    try {
        $workspace->importFileMap($project, ['src/App.jsx'=>'NOT COMMITTED']);
        throw new RuntimeException('Metadata failure was ignored');
    } catch (RuntimeException $error) {
        formCheck($error->getMessage() === 'Fictional metadata failure' && hash_file('sha256', $root.'/src/App.jsx') === $original, 'Metadata failure restores previous source');
        formCheck(File::get($root.'/notes.txt') === 'KEEP UNTIL SUCCESS', 'Metadata failure retains previous directory');
    } finally { $failSave = false; }

    $workspace->importFileMap($project, ['src/App.jsx'=>'OVERLAY SOURCE']);
    formCheck(File::get($root.'/notes.txt') === 'KEEP UNTIL SUCCESS', 'Existing pull/overlay semantics retain unrelated source on success');
    $result = $workspace->importFileMap($project, ['src/App.jsx'=>'NEW SOURCE', 'LICENSE'=>'FICTIONAL LICENSE'], replace: true);
    formCheck($result['paths'] === ['src/App.jsx','LICENSE'] && $result['skipped'] === 0, 'Successful import retains response shape');
    formCheck(File::get($root.'/src/App.jsx') === 'NEW SOURCE' && !file_exists($root.'/notes.txt'), 'Successful replacement promotes only the validated source');
    formCheck(File::get($root.'/LICENSE') === 'FICTIONAL LICENSE' && is_file($root.'/.krikkit-kit'), 'Import marker and license retained');
    $safeTree = $fixtureRoot.'/valid-download';
    File::ensureDirectoryExists($safeTree.'/src');
    File::put($safeTree.'/src/App.jsx', 'TREE SOURCE');
    File::put($safeTree.'/source.config.ts', "import 'fumadocs-mdx';");
    $workspace->importTree($project, $safeTree, replace: true);
    formCheck(File::get($root.'/src/App.jsx') === 'TREE SOURCE' && !is_file($root.'/LICENSE'), 'Successful full tree replacement does not keep unrelated previous source');
    formCheck(is_file($root.'/.source/index.ts'), 'Existing preview stub is generated inside the stage before promotion');
    formCheck(File::get($workspace->root($foreign).'/src/App.jsx') === 'FOREIGN SOURCE', 'Foreign project unchanged');
    formCheck(glob($root.'.import-*') === [] && glob($root.'.previous-*') === [], 'No temporary import directories remain');
    formResult('GitHub source import failure preservation and atomic promotion');
} finally {
    app()->useStoragePath($originalStorage);
    File::deleteDirectory($fixtureRoot);
}
