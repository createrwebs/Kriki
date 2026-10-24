<?php

putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=:memory:');
require __DIR__.'/forms-bootstrap.php';

use App\Hosting\Contracts\HostingProvider;
use App\Hosting\DeploymentManager;
use App\Hosting\Providers\LocalProvider;
use App\Hosting\SecretManager;
use App\Hosting\SecurityScanner;
use App\Http\Controllers\Api\Hosting\DeploymentController;
use App\Models\AppSecret;
use App\Models\Deployment;
use App\Models\LabProject;
use App\Models\LabPublication;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

Schema::create('users', function (Blueprint $table) {
    $table->id(); $table->string('email')->nullable(); $table->timestamp('email_verified_at')->nullable();
});
DB::table('users')->insert(['id' => 801, 'email' => 'fixture@example.invalid', 'email_verified_at' => now()]);
foreach (['2026_08_04_051600_create_lab_projects_table.php', '2026_08_16_081000_create_lab_publications_table.php', '2026_10_16_000001_create_deployments_table.php', '2026_10_16_000002_create_hosting_tables.php'] as $migration) {
    (require __DIR__.'/../../database/migrations/'.$migration)->up();
}
Schema::table('lab_projects', function (Blueprint $table) {
    $table->timestamp('frozen_at')->nullable(); $table->timestamp('opened_at')->nullable(); $table->string('project_type')->nullable(); $table->string('stack')->nullable();
});
$owner = User::findOrFail(801);
$project = LabProject::create(['user_id' => 801, 'title' => 'Hosting fixture']);
$fixtureRoot = sys_get_temp_dir().'/krikkit-hosting-'.bin2hex(random_bytes(12));
$oldStorage = app()->storagePath();
app()->useStoragePath($fixtureRoot);
config(['filesystems.disks.local.root' => $fixtureRoot.'/private', 'app.debug' => false, 'hosting.enabled' => true]);
Storage::forgetDisk('local');
mkdir($fixtureRoot.'/source', 0700, true);
$failures = [];
$check = function (bool $ok, string $label) use (&$failures) {
    if (! $ok) { $failures[] = $label; echo 'FAIL: '.$label.PHP_EOL; }
    else { formCheck(true, $label); }
};

try {
    $kernel = app(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(Request::create('http://localhost:8889/api/hosting/providers/regions', 'GET', [], [], [], ['HTTP_ACCEPT' => 'application/json']));
    $check($response->getStatusCode() === 401, 'Guest returns 401 rather than missing auth guard 500');
    Auth::guard('web')->setUser($owner);

    $call = function (string $path, string $method = 'GET', string $body = '') use ($kernel) {
        return $kernel->handle(Request::create('http://localhost:8889/api/hosting/'.$path, $method, [], [], [], [
            'HTTP_ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json', 'HTTP_X_CSRF_TOKEN' => app('session')->token(),
        ], $body));
    };
    $response = $call('tokens', 'POST', '{"name":"fixture"}');
    $check($response->getStatusCode() === 501 && App\Models\ApiToken::count() === 0, 'Valid CSRF reaches explicit token-unavailable response without issuance');
    $response = $call($project->uuid.'/secrets', 'PUT', '{"env":{"FIXTURE_KEY":"fixture-secret-not-real"}}');
    $check($response->getStatusCode() === 200 && AppSecret::count() === 1, 'Valid CSRF and owner session can save a secret');
    $response = $call($project->uuid.'/secrets');
    $check($response->getStatusCode() === 200 && ! str_contains($response->getContent(), 'fixture-secret-not-real') && str_contains($response->headers->get('Cache-Control'), 'no-store'), 'Secret API returns uncached keys only');
    $paddedSecret = "  fixture-value\nsecond-line\n  ";
    $response = $call($project->uuid.'/secrets', 'PUT', json_encode(['env' => ['FIXTURE_KEY' => $paddedSecret]]));
    $check($response->getStatusCode() === 200 && app(SecretManager::class)->all($project)['FIXTURE_KEY'] === $paddedSecret, 'HTTP secret update preserves significant whitespace and multiline bytes');
    $other = clone $owner; $other->id = 802;
    Auth::guard('web')->setUser($other);
    $response = $call($project->uuid.'/secrets');
    $check($response->getStatusCode() === 403, 'Another account cannot read project secrets');
    Auth::guard('web')->setUser($owner);
    $response = $kernel->handle(Request::create('http://localhost:8889/api/hosting/providers/regions', 'GET', [], [], [], ['HTTP_ACCEPT' => 'application/json']));
    $check($response->getStatusCode() === 200, 'Existing verified web session can read provider metadata');
    $response = $kernel->handle(Request::create('http://localhost:8889/api/hosting/'.$project->uuid.'/secrets', 'PUT', [], [], [], ['HTTP_ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json'], '{"env":{"FIXTURE":"never-save"}}'));
    $check($response->getStatusCode() === 419 && ! AppSecret::where('key', 'FIXTURE')->exists(), 'Cookie-auth mutation without CSRF fails before writing');
    $unverified = clone $owner; $unverified->email_verified_at = null;
    Auth::guard('web')->setUser($unverified);
    $response = $kernel->handle(Request::create('http://localhost:8889/api/hosting/providers/regions', 'GET', [], [], [], ['HTTP_ACCEPT' => 'application/json']));
    $check($response->getStatusCode() === 403, 'Unverified account denied');
    Auth::guard('web')->setUser($owner);

    $secrets = app(SecretManager::class);
    $secrets->update($project, ['FIXTURE_KEY' => 'fixture-secret-not-real']);
    $check($secrets->all($project)['FIXTURE_KEY'] === 'fixture-secret-not-real', 'Existing secret encoding roundtrips');
    $check(! str_contains(AppSecret::first()->toJson(), 'fixture-secret-not-real') && ! array_key_exists('value', AppSecret::first()->toArray()), 'Secret value hidden from model serialization');
    $project->load('secrets');
    $check(! str_contains($project->toJson(), 'fixture-secret-not-real'), 'Project relationship serialization hides secret values');
    $check(! str_contains(DB::table('app_secrets')->value('value'), 'fixture-secret-not-real'), 'Database secret remains encrypted');

    file_put_contents($fixtureRoot.'/source/app.js', 'const password = "fixture-secret-not-real";');
    $scan = app(SecurityScanner::class)->scan($project, $fixtureRoot.'/source');
    $check(count($scan['violations']) > 0, 'Scanner still detects matching source');
    $check(! str_contains(json_encode($scan), 'fixture-secret-not-real'), 'Scan response never echoes secret source snippets');

    $provider = new class implements HostingProvider {
        public bool $sawArtifact = false;
        public bool $fail = false;
        public bool $sawEnv = false;
        public function key(): string { return 'fixture'; }
        public function displayName(): string { return 'Fixture'; }
        public function available(LabProject $project): bool { return true; }
        public function regions(): array { return []; }
        public function vmTypes(): array { return []; }
        public function deploy(LabProject $project, string $artifactPath, array $options = []): array {
            $this->sawArtifact = is_file($artifactPath);
            $this->sawEnv = ! empty($options['env']);
            if (! $this->sawArtifact) throw new RuntimeException('Missing artifact');
            if ($this->fail) throw new RuntimeException('fixture-secret-not-real');
            return ['deployment_id' => 'fixture', 'status' => 'live', 'url' => 'https://fixture.invalid'];
        }
        public function status(string $deploymentId): array { return ['status' => 'live']; }
        public function logs(string $deploymentId, int $limit = 100): array { return []; }
        public function stop(string $deploymentId): bool { return false; }
        public function start(string $deploymentId): bool { return false; }
        public function rollback(string $deploymentId, string $targetVersion): bool { return false; }
    };
    $manager = new DeploymentManager; $manager->register($provider);
    $zip = new ZipArchive; $zip->open($fixtureRoot.'/fixture.zip', ZipArchive::CREATE); $zip->addFromString('index.html', '<h1>Fixture</h1>'); $zip->close();
    $request = Request::create('/', 'POST', ['provider' => 'fixture']);
    $request->files->set('artifact', new UploadedFile($fixtureRoot.'/fixture.zip', 'fixture.zip', 'application/zip', null, true));
    $request->setUserResolver(fn () => $owner);
    $response = (new DeploymentController($manager))->store($request, $project);
    $check($response->getStatusCode() === 201 && $provider->sawArtifact, 'Upload resolves actual local disk path');
    $check(Storage::disk('local')->allFiles('lab/deployments') === [], 'Synchronous temporary upload is cleaned after deployment');
    $check(! $provider->sawEnv, 'Stored secrets are not injected into a static provider');
    $provider->fail = true;
    $response = (new DeploymentController($manager))->store($request, $project);
    $check($response->getStatusCode() === 422 && ! str_contains($response->getContent(), 'fixture-secret-not-real'), 'Provider exceptions are not exposed in API errors');
    $check(! str_contains(Deployment::orderByDesc('id')->first()->last_error, 'fixture-secret-not-real') && ! str_contains(App\Models\DeploymentLog::all()->toJson(), 'fixture-secret-not-real'), 'Provider exception secrets are not stored in deployment logs/errors');
    $check(Storage::disk('local')->allFiles('lab/deployments') === [], 'Failed deployment also cleans temporary upload');

    $localDeployment = new Deployment; $localDeployment->forceFill(['provider' => 'local', 'status' => 'live']);
    $check(! $localDeployment->canRollback(), 'Local deployment does not advertise unsupported rollback');
    $publication = LabPublication::create(['lab_project_id' => $project->id, 'kind' => 'custom', 'custom_host' => 'fixture.invalid', 'status' => 'building']);
    $local = app(LocalProvider::class);
    try { $local->deploy($project, $fixtureRoot.'/fixture.zip'); $check(false, 'Unverified custom host cannot deploy'); }
    catch (RuntimeException $error) { $check(str_contains(strtolower($error->getMessage()), 'verif'), 'Unverified custom host rejected before deploy'); }
    $check(! is_dir($publication->liveRoot()), 'Rejected domain never writes live files');
    $publication->forceFill(['kind' => 'subdomain', 'custom_host' => null, 'subdomain' => 'fixture-hosting'])->save();
    foreach ([['strategy' => 'canary'], ['env' => ['FIXTURE' => 'secret']], ['hostname' => 'other.invalid'], ['vmtype' => 'large']] as $options) {
        try { $local->deploy($project, $fixtureRoot.'/fixture.zip', $options); $check(false, 'Unsupported local options rejected'); }
        catch (RuntimeException) { $check(! is_dir($publication->liveRoot()), 'Unsupported local options rejected before filesystem mutation'); }
    }
    $project->forceFill(['project_type' => 'php'])->save();
    try { $local->deploy($project, $fixtureRoot.'/fixture.zip'); $check(false, 'Source-only PHP project cannot use Hosting to bypass policy'); }
    catch (RuntimeException) { $check(! is_dir($publication->liveRoot()), 'Source-only PHP project cannot use Hosting to bypass policy'); }
    $project->forceFill(['project_type' => 'react'])->save();
    $result = $local->deploy($project, $fixtureRoot.'/fixture.zip');
    $check($result['status'] === 'live' && file_get_contents($publication->liveRoot().'/index.html') === '<h1>Fixture</h1>' && $publication->fresh()->status === 'live', 'Verified static happy path writes fixture artifact and marks publication live');

    $project->forceFill(['frozen_at' => now()])->save();
    try { (new DeploymentController($manager))->store($request, $project); $check(false, 'Frozen project mutation denied'); }
    catch (Illuminate\Auth\Access\AuthorizationException) { $check(true, 'Frozen project mutation denied'); }
    $response = $call($project->uuid.'/secrets', 'PUT', '{"env":{"ANOTHER":"never-save"}}');
    $check($response->getStatusCode() === 403 && AppSecret::count() === 1, 'Frozen project secret writes denied through middleware');
    $project->forceFill(['frozen_at' => null])->save();
    $provider->fail = false;
    app()->instance(DeploymentManager::class, $manager);
    $upload = Request::create('http://localhost:8889/api/hosting/'.$project->uuid.'/deploy', 'POST', ['provider' => 'fixture'], [], ['artifact' => new UploadedFile($fixtureRoot.'/fixture.zip', 'fixture.zip', 'application/zip', null, true)], ['HTTP_ACCEPT' => 'application/json', 'HTTP_X_CSRF_TOKEN' => app('session')->token()]);
    $response = $kernel->handle($upload);
    $check($response->getStatusCode() === 201 && $provider->sawArtifact, 'Multipart deploy works through owner binding, session, CSRF and controller');
    $otherProject = LabProject::create(['user_id' => 801, 'title' => 'Second fixture']);
    $foreignDeployment = Deployment::create(['lab_project_id' => $otherProject->id, 'user_id' => 801, 'provider' => 'local', 'version' => 1, 'status' => 'live']);
    $response = $call($project->uuid.'/deployments/'.$foreignDeployment->id);
    $check($response->getStatusCode() === 404, 'Deployment from another project cannot be read via current project route');
    config(['hosting.enabled' => false]);
    $response = $call('providers/regions');
    $check($response->getStatusCode() === 503, 'Operator can keep experimental hosting closed');
    config(['hosting.enabled' => true]);
    Schema::drop('deployment_logs');
    $response = $call('providers/regions');
    $check($response->getStatusCode() === 503, 'Missing hosting migration fails with readiness response not 500');
    Auth::guard('web')->forgetUser();
    $response = $kernel->handle(Request::create('http://localhost:8889/api/hosting/providers/regions', 'GET', [], [], [], ['HTTP_ACCEPT' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer krik_fixture_not_a_real_token']));
    $check($response->getStatusCode() === 401, 'Bearer string does not bypass session authentication');
} finally {
    Storage::forgetDisk('local');
    app()->useStoragePath($oldStorage);
    // Exactly the random fixture directory created by this suite, never project storage.
    File::deleteDirectory($fixtureRoot);
}
if ($failures) throw new RuntimeException(count($failures).' hosting regression(s) failed.');
formResult('hosting safety');
