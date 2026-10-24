<?php

// Dedicated SQLite fixture; never touches MAMP/application users or their quotas.
require __DIR__.'/../../regressions/managed-module-bootstrap.php';
(require __DIR__.'/../../../database/migrations/2026_08_16_081000_create_lab_publications_table.php')->up();
$fixture = base_path('.agent-work/qa/module-browser-'.Illuminate\Support\Str::uuid());
Illuminate\Support\Facades\File::ensureDirectoryExists($fixture.'/sessions');
Illuminate\Support\Facades\Schema::table('users', function (Illuminate\Database\Schema\Blueprint $table) { $table->string('public_id')->nullable(); $table->rememberToken(); });
$owner->forceFill(['password' => 'FictionalQaPass123!', 'public_id' => 'fictional-owner'])->save();
$allow('contact');
App\Models\UserDatastoreLink::create(['user_id' => $owner->id, 'driver' => 'supabase', 'host_url' => 'http://127.0.0.1:4900/supabase/pglite', 'publishable_token' => 'fictional-public', 'steward_token' => 'fictional-private', 'access_token' => 'fictional-management', 'project_ref' => 'pglitefixture']);
App\Models\LabPublication::create(['lab_project_id' => $project->id, 'kind' => 'subdomain', 'subdomain' => 'fictional', 'status' => 'live']);
$originalStorage = app()->storagePath(); app()->useStoragePath($fixture);
$workspace = app(App\Lab\SiteWorkspace::class);
$source = (new App\Lab\Builder\BlueprintSource)->compile((new App\Lab\Builder\TemplateCatalog)->blueprint('business','business-studio','เว็บไซต์ติดต่อสมมติ'), (string) Illuminate\Support\Str::uuid(), 1);
$workspace->commitAtomic($project, $source['files']);
$sourceRoot = $workspace->root($project);
app()->useStoragePath($originalStorage);
$database = $fixture.'/control.sqlite';
Illuminate\Support\Facades\DB::statement("VACUUM INTO '".str_replace("'", "''", $database)."'");
$configuration = ['database' => $database, 'storage' => $fixture, 'source' => $sourceRoot, 'project_uuid' => $project->uuid];
file_put_contents(base_path('.agent-work/qa/module-browser-config.json'), json_encode($configuration, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "Dedicated browser fixture created. No real account changed.\n";
