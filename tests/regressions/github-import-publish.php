<?php

require __DIR__.'/managed-module-bootstrap.php';

use App\Lab\Github\GithubRepoImporter;
use App\Lab\Publish\LabPublicationManager;
use App\Lab\SiteWorkspace;
use App\Models\LabPublication;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

(require __DIR__.'/../../database/migrations/2026_08_16_081000_create_lab_publications_table.php')->up();

$base = 'http://127.0.0.1:4904/github';
config(['services.github.api_url' => $base, 'app.url' => 'http://localhost:8889']);
Http::allowStrayRequests([$base.'/*']);

$oldStorage = app()->storagePath();
$fixtureStorage = base_path('.agent-work/qa/github-import-publish-'.Str::uuid());
app()->useStoragePath($fixtureStorage);

try {
    $workspace = app(SiteWorkspace::class);
    Http::post($base.'/__qa/reset')->throw();
    $imported = app(GithubRepoImporter::class)->import($project, 'fictional', 'healthy', 'main', rootDirectory: 'app', replaceWorkspace: true);
    formCheck($imported['commit_sha'] === str_repeat('1', 40), 'GitHub import pins the source commit before publishing');
    formCheck(str_contains(File::get($workspace->root($project).'/src/App.jsx'), 'Fictional source only'), 'Imported source is present in the project workspace');

    $manager = app(LabPublicationManager::class);
    $manager->saveAndPublish($project, $owner, ['kind' => LabPublication::KIND_SUBDOMAIN]);
    $publication = $project->fresh('publication')->publication;
    formCheck($publication->status === LabPublication::STATUS_BUILDING && filled($publication->subdomain), 'Owner publish starts a subdomain build with an assigned slug');

    $archive = $fixtureStorage.'/artifact.zip';
    $zip = new ZipArchive;
    formCheck($zip->open($archive, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true, 'Fixture production artifact can be created');
    $zip->addFromString('index.html', '<!doctype html><title>Imported fixture</title><main>Fictional source only</main>');
    $zip->addFromString('assets/app.js', 'console.log("fixture");');
    $zip->close();
    $manager->deployArtifact($project, $owner, new Illuminate\Http\UploadedFile($archive, 'dist.zip', 'application/zip', null, true));
    $publication = $publication->fresh();
    formCheck($publication->status === LabPublication::STATUS_LIVE, 'Imported artifact is promoted to live publication');

    $kernel = app(Kernel::class);
    $request = Request::create('http://'.$publication->subdomain.'.localhost:8889/', 'GET', [], [], [], ['HTTP_HOST' => $publication->subdomain.'.localhost:8889']);
    $response = $kernel->handle($request);
    $responseBody = $response instanceof BinaryFileResponse
        ? file_get_contents($response->getFile()->getPathname())
        : $response->getContent();
    fwrite(STDERR, 'HTTP '.$response->getStatusCode().' '.substr((string) $responseBody, 0, 180).PHP_EOL);
    formCheck($response->getStatusCode() === 200 && str_contains((string) $responseBody, 'Imported fixture'), 'Published subdomain serves the imported artifact through the HTTP kernel');
    $asset = Request::create('http://'.$publication->subdomain.'.localhost:8889/assets/app.js', 'GET', [], [], [], ['HTTP_HOST' => $publication->subdomain.'.localhost:8889']);
    $assetResponse = $kernel->handle($asset);
    $assetBody = $assetResponse instanceof BinaryFileResponse
        ? file_get_contents($assetResponse->getFile()->getPathname())
        : $assetResponse->getContent();
    formCheck($assetResponse->getStatusCode() === 200 && str_contains((string) $assetBody, 'fixture'), 'Published subdomain serves imported assets with the correct route');
    formResult('GitHub import to subdomain publication');
} finally {
    app()->useStoragePath($oldStorage);
    File::deleteDirectory($fixtureStorage);
}
