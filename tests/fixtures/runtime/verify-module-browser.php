<?php

// Read-only acceptance of the trusted fictional build. No browser execution proof.
require __DIR__.'/../../../vendor/autoload.php';
$repo = dirname(__DIR__,3);
$fixture = json_decode(file_get_contents($repo.'/.agent-work/qa/module-browser-config.json'),true,512,JSON_THROW_ON_ERROR);
$prefix = str_replace('\\','/',$repo).'/.agent-work/qa/module-browser-';
foreach (['database','storage','source'] as $key) {
    if (!str_starts_with(str_replace('\\','/',$fixture[$key]),$prefix)) throw new RuntimeException('Not a dedicated fictional fixture');
}
putenv('DB_CONNECTION=sqlite'); putenv('DB_DATABASE='.$fixture['database']);
putenv('CACHE_STORE=array'); putenv('SESSION_DRIVER=array'); putenv('LOG_CHANNEL=null');
putenv('APP_CONFIG_CACHE='.__DIR__.'/no-module-config-cache.php');
$app = require $repo.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
config(['database.default'=>'sqlite','database.connections.sqlite.database'=>$fixture['database'],'app.url'=>'http://localhost:8896',
    'services.supabase.api_url'=>'http://127.0.0.1:4900/supabase','lab-modules.business_writes_enabled'=>true]);
Illuminate\Support\Facades\DB::purge('sqlite');
Illuminate\Support\Facades\Http::preventStrayRequests();
Illuminate\Support\Facades\Http::allowStrayRequests(['http://127.0.0.1:4900/supabase/*','http://localhost:8896/*']);
$app->useStoragePath($fixture['storage']);
$project = App\Models\LabProject::where('uuid',$fixture['project_uuid'])->firstOrFail();
$artifacts = app(App\Lab\Modules\ModuleArtifact::class);
$artifacts->verify($project,$fixture['source'].'/dist');
if ($artifacts->context($project) !== $fixture['build_context']) throw new RuntimeException('Trusted fixture source changed after build');
$html = file_get_contents($fixture['source'].'/dist/index.html');
foreach (['/','/cart','/checkout','/orders/00000000-0000-4000-8000-000000000001'] as $path) {
    $response = Illuminate\Support\Facades\Http::timeout(5)->withOptions(['proxy'=>''])->withHeaders(['Host'=>'fictional.localhost:8896'])->get('http://localhost:8896'.$path);
    if ($response->status() !== 200 || $response->body() !== $html) throw new RuntimeException('Direct fictional route failed: '.$path);
}
echo "PASS: actual trusted generated artifact passes server source/schema/SDK/byte checks; four direct HTTP SPA routes serve build HTML. This does not establish browser UI behavior.\n";
