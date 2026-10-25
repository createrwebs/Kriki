<?php

// Dedicated SQLite fixture; never touches MAMP/application users or their quotas.
require __DIR__.'/../../regressions/managed-module-bootstrap.php';
(require __DIR__.'/../../../database/migrations/2026_08_16_081000_create_lab_publications_table.php')->up();
$fixture = base_path('.agent-work/qa/module-browser-'.Illuminate\Support\Str::uuid());
Illuminate\Support\Facades\File::ensureDirectoryExists($fixture.'/sessions');
Illuminate\Support\Facades\Schema::table('users', function (Illuminate\Database\Schema\Blueprint $table) { $table->string('public_id')->nullable(); $table->rememberToken(); });
$owner->forceFill(['password' => 'FictionalQaPass123!', 'public_id' => 'fictional-owner'])->save();
foreach (['contact','news','catalog','orders','manual_payments'] as $moduleKey) $allow($moduleKey);
config(['app.url' => 'http://localhost:8896', 'services.supabase.api_url' => 'http://127.0.0.1:4900/supabase', 'lab-modules.business_writes_enabled' => true]);
Illuminate\Support\Facades\Http::allowStrayRequests(['http://127.0.0.1:4900/supabase/*']);
App\Models\UserDatastoreLink::create(['user_id' => $owner->id, 'driver' => 'supabase', 'host_url' => 'http://127.0.0.1:4900/supabase/pglite', 'publishable_token' => 'fictional-public', 'steward_token' => 'fictional-private', 'access_token' => 'fictional-management', 'project_ref' => 'pglitefixture']);
App\Models\LabPublication::create(['lab_project_id' => $project->id, 'kind' => 'subdomain', 'subdomain' => 'fictional', 'status' => 'live']);
$originalStorage = app()->storagePath(); app()->useStoragePath($fixture);
$workspace = app(App\Lab\SiteWorkspace::class);
$source = (new App\Lab\Builder\BlueprintSource)->compile((new App\Lab\Builder\TemplateCatalog)->blueprint('business','business-studio','เว็บไซต์ติดต่อสมมติ'), (string) Illuminate\Support\Str::uuid(), 1);
$workspace->commitAtomic($project, $source['files']);
$registry = app(App\Lab\Modules\ModuleInstallations::class); $approvals = app(App\Lab\Modules\SchemaProposals::class);
foreach (['contact','news','catalog','orders','manual_payments'] as $moduleKey) {
    $registry->select($owner,$project,$moduleKey,true,(int) $project->fresh()->modules_revision);
    $installation = $registry->prepare($owner,$project,$moduleKey,(int) $project->fresh()->modules_revision,[],(string) Illuminate\Support\Str::uuid())['installation'];
    $proposal = $approvals->prepare($owner,$project,$installation,1);
    $approvals->apply($owner,$project,$proposal,$proposal->impact_hash,1);
    $registry->setState($owner,$project,$installation->fresh(),'enabled',2);
    if (in_array($moduleKey,['contact','news','catalog'],true)) app(App\Lab\Modules\ModuleEmbed::class)->embed($owner,$project,$installation->fresh(),'/',hash('sha256',$workspace->read($project,'src/App.jsx')));
}
$sourceRoot = $workspace->root($project);
$buildContext = app(App\Lab\Modules\ModuleArtifact::class)->context($project);
app()->useStoragePath($originalStorage);
$database = $fixture.'/control.sqlite';
Illuminate\Support\Facades\DB::statement("VACUUM INTO '".str_replace("'", "''", $database)."'");
$configuration = ['database' => $database, 'storage' => $fixture, 'source' => $sourceRoot, 'project_uuid' => $project->uuid, 'build_context' => $buildContext];
file_put_contents(base_path('.agent-work/qa/module-browser-config.json'), json_encode($configuration, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "Dedicated browser fixture created. No real account changed.\n";
