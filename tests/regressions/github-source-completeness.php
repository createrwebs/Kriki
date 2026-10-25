<?php

require __DIR__.'/managed-module-bootstrap.php';

use App\Lab\Github\GithubApiClient;
use App\Lab\Github\GithubRepoImporter;
use App\Lab\SiteWorkspace;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

$base = 'http://127.0.0.1:4904/github';
config(['services.github.api_url' => $base, 'lab.github.timeout' => 5, 'lab.github.connect_timeout' => 2, 'lab.github.max_files' => 2500, 'lab.github.max_file_bytes' => 2097152]);
Http::allowStrayRequests([$base.'/*']);
$fixtureRoot = base_path('.agent-work/qa/github-completeness-'.Str::uuid());
$originalStorage = app()->storagePath();
app()->useStoragePath($fixtureRoot);
$workspace = app(SiteWorkspace::class);
$root = $workspace->ensure($project);
$workspace->ensure($foreign);
File::put($root.'/src/App.jsx', 'ORIGINAL SOURCE');
File::put($root.'/notes.txt', 'ORIGINAL NOTES');
$original = $workspace->importRevision($project);
$foreignRevision = $workspace->importRevision($foreign);
$api = app(GithubApiClient::class);
$importer = app(GithubRepoImporter::class);
$reset = fn () => Http::post($base.'/__qa/reset')->throw();
try {
    $reset();
    $truncatedError = null;
    try { $api->fetchRecursiveTree('fictional', 'truncated', str_repeat('1', 40)); }
    catch (RuntimeException $error) { $truncatedError = $error->getMessage(); }
    formCheck($truncatedError !== null && str_contains(strtolower($truncatedError), 'truncat'), 'Truncated tree has actionable failure');
    foreach (['malformed','truncated','failed-blob','bad-base64','wrong-hash','oversized','actual-oversized','invalid-size','symlink','submodule','collision','bad-path','over-count','over-total'] as $scenario) {
        $reset(); $rejected = false;
        try { $importer->import($project, 'fictional', $scenario, 'main', rootDirectory:'app', replaceWorkspace:true); }
        catch (RuntimeException|InvalidArgumentException $error) {
            $rejected = true;
            formCheck(!str_contains($error->getMessage(), 'UPSTREAM DETAILS'), 'Upstream body is not echoed for '.$scenario);
        }
        formCheck($rejected, 'Incomplete/unsafe import rejected: '.$scenario);
        formCheck($workspace->importRevision($project) === $original, 'Live source preserved: '.$scenario);
        $journal = Http::get($base.'/__qa/journal')->throw()->json();
        formCheck(!array_filter($journal, fn ($row) => str_contains($row['path'], '/zipball')), 'No silent ZIP fallback: '.$scenario);
    }
    $reset();
    $result = $importer->import($project, 'fictional', 'healthy', 'main', replaceWorkspace:true);
    formCheck($result['commit_sha'] === str_repeat('1', 40) && $result['requested_ref'] === 'main', 'Successful import reports pinned commit and selected ref');
    formCheck($result['root'] === 'app' && count($result['paths']) === 5 && $result['skipped'] === 0, 'Complete selected subtree is retained');
    formCheck(File::get($root.'/package-lock.json') === '{"name":"fictional-import","lockfileVersion":3,"packages":{}}', 'Dependency lockfile remains inert source');
    formCheck(File::get($root.'/public/icon.bin') === pack('C*',137,80,78,71,0,255,13,10), 'Binary source asset preserved byte for byte');
    formCheck(is_file($root.'/LICENSE') && str_contains(File::get($root.'/src/App.jsx'), 'Fictional source only'), 'Source and license retained without execution');
    $journal = Http::get($base.'/__qa/journal')->throw()->json();
    $resolutions = array_filter($journal, fn ($row) => str_ends_with($row['path'], '/commits/main'));
    formCheck(count($resolutions) === 1, 'Mutable branch resolves exactly once');
    foreach ($journal as $row) if (str_contains($row['path'], '/contents/')) formCheck($row['ref'] === str_repeat('1',40), 'Root detection uses the same pinned commit');
    formCheck($workspace->importRevision($foreign) === $foreignRevision, 'Foreign workspace unchanged');
    formCheck(glob($root.'.import-*') === [] && glob($root.'.previous-*') === [], 'No temporary stages remain');
    formResult('GitHub complete pinned source through Postman HTTP');
} finally {
    app()->useStoragePath($originalStorage);
    $qaRoot = realpath(base_path('.agent-work/qa'));
    $resolved = realpath($fixtureRoot);
    if (!$qaRoot || !$resolved || !str_starts_with($resolved, $qaRoot.DIRECTORY_SEPARATOR)) throw new RuntimeException('Refuse out-of-scope fixture cleanup');
    File::deleteDirectory($resolved);
}
