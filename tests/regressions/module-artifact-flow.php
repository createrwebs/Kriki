<?php

require __DIR__.'/private-slip-flow.php';

use App\Lab\Modules\ModuleArtifact;
use App\Lab\Modules\ModuleEmbed;
use App\Lab\SiteWorkspace;
use App\Models\LabPublication;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

$previousStorage = app()->storagePath();
$isolatedStorage = base_path('.agent-work/qa/module-artifact-'.Str::uuid());
app()->useStoragePath($isolatedStorage);
try {
    $workspace = app(SiteWorkspace::class);
    $source = (new App\Lab\Builder\BlueprintSource)->compile((new App\Lab\Builder\TemplateCatalog)->blueprint('business','business-studio','Fictional artifact'), (string) Str::uuid(), 1);
    $workspace->commitAtomic($project, $source['files']);
    $path = "/lab/{$project->uuid}/modules/{$catalogMod->uuid}/embed";
    $as($owner);
    $preview = $ownerHttp($path.'?route=%2F');
    $plan = json_decode($preview->getContent(), true);
    formCheck($preview->getStatusCode() === 200 && isset($plan['expected_files']['src/App.jsx'],$plan['impact_hash']) && !is_file($workspace->root($project).'/'.ModuleEmbed::MANIFEST), 'HTTP embed plan previews exact source without installing it');
    $body = ['route' => '/', 'expected_app_hash' => $plan['expected_files']['src/App.jsx'], 'expected_files' => $plan['expected_files'], 'impact_hash' => $plan['impact_hash']];
    formCheck($ownerHttp($path,'POST',array_diff_key($body,['expected_files'=>true]))->getStatusCode() === 422, 'HTTP embedding requires a full source snapshot');
    formCheck($ownerHttp($path,'POST',$body)->getStatusCode() === 200, 'Reviewed exact-source plan installs managed widgets');
    $preview = json_decode($ownerHttp($path.'?route=%2F')->getContent(), true);
    $originalPage = $workspace->read($project,'src/SiteContent.jsx');
    $workspace->write($project,'src/SiteContent.jsx',$originalPage."\n// saved elsewhere\n");
    formCheck($ownerHttp($path,'POST',['route'=>'/','expected_app_hash'=>$preview['expected_files']['src/App.jsx'],'expected_files'=>$preview['expected_files'],'impact_hash'=>$preview['impact_hash']])->getStatusCode() === 409, 'HTTP CAS includes source files beyond App.jsx');
    $workspace->write($project,'src/SiteContent.jsx',$originalPage);
    $filesPath = "/lab/{$project->uuid}/files";
    formCheck($ownerHttp($filesPath.'/content','PUT',['path'=>'src/krikkit-widgets/StorefrontWidget.jsx','content'=>'// replaced'])->getStatusCode() === 422, 'Ordinary HTTP file tools cannot replace protected commerce code');
    formCheck($ownerHttp($filesPath,'DELETE',['path'=>'src/krikkit-widgets'])->getStatusCode() === 422, 'Ordinary HTTP deletion cannot remove the managed SDK');
    $service = app(ModuleArtifact::class);
    $context = $service->context($project);
    formCheck($context['sdk_version'] === ModuleEmbed::SDK_VERSION && isset($context['modules']['manual_payments'],$context['source_files']['src/SiteContent.jsx']) && !str_contains(json_encode($context),'fictional-private'), 'Build context binds SDK/schema, source hashes and optional commerce dependencies without secrets');
    $contextPath = "/lab/{$project->uuid}/modules/build-context";
    formCheck($ownerHttp($contextPath)->getStatusCode() === 200, 'Actual build-context owner route returns scoped compatibility metadata');
    $as($other); formCheck($ownerHttp($contextPath)->getStatusCode() === 403, 'Other account cannot inspect build context'); $as($owner);
    $zipFor = function (array $overrides = [], bool $tamper = false, bool $missing = false) use ($context, $isolatedStorage) {
        $files = ['index.html' => '<h1>FICTIONAL BUILD</h1>', 'assets/app.js' => 'console.log("trusted fixture")'];
        $manifest = $context; unset($manifest['source_files']);
        $manifest['artifact_files'] = array_map(fn ($contents) => hash('sha256',$contents), $files);
        $manifest = array_replace($manifest,$overrides);
        if ($tamper) $files['index.html'] = '<h1>UNMANIFESTED BYTES</h1>';
        if (!$missing) $files[ModuleArtifact::FILE] = json_encode($manifest, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        $file = $isolatedStorage.'/artifact-'.Str::uuid().'.zip';
        $zip = new ZipArchive; if ($zip->open($file,ZipArchive::CREATE|ZipArchive::OVERWRITE) !== true) throw new RuntimeException('Could not create fixture zip');
        foreach ($files as $name => $contents) $zip->addFromString($name,$contents);
        $zip->close();
        return new Illuminate\Http\UploadedFile($file,'fixture.zip','application/zip',null,true);
    };
    $destination = $isolatedStorage.'/app/lab/live/'.$project->uuid;
    File::ensureDirectoryExists($destination); File::put($destination.'/index.html','LAST GOOD');
    $publication = LabPublication::create(['lab_project_id'=>$project->id,'kind'=>'subdomain','subdomain'=>'fictional-build','status'=>'building','published_at'=>now()]);
    $manager = app(App\Lab\Publish\LabPublicationManager::class);
    $manager->deployArtifact($project,$owner,$zipFor());
    formCheck(File::get($destination.'/index.html') === '<h1>FICTIONAL BUILD</h1>' && $publication->fresh()->status === 'live', 'Verified manifest artifact stages and promotes successfully');
    $reject = function (callable $archive, int $status, string $label) use ($publication,$expect,$manager,$project,$owner,$destination) {
        $publication->refresh()->forceFill(['status'=>'building'])->save();
        $expect(fn () => $manager->deployArtifact($project,$owner,$archive()),$status,$label);
        formCheck(File::get($destination.'/index.html') === '<h1>FICTIONAL BUILD</h1>' && $publication->fresh()->status === 'live', 'Failed promotion preserves last-good files and live state');
    };
    $reject(fn () => $zipFor([],true),409,'Artifact bytes must match file hashes');
    $reject(fn () => $zipFor([],false,true),409,'Managed build cannot omit its artifact manifest');
    $reject(fn () => $zipFor(['sdk_version'=>2]),409,'Old SDK artifact cannot be promoted as current');
    $reject(fn () => $zipFor(['project_uuid'=>(string) Str::uuid()]),409,'Artifact cannot cross project binding');
    $app = $workspace->read($project,'src/App.jsx'); $workspace->write($project,'src/App.jsx',$app."\n// changed after build\n");
    $reject(fn () => $zipFor(),409,'Source changed after build cannot promote a stale artifact');
    $workspace->write($project,'src/App.jsx',$app);
    $ordersMod->forceFill(['schema_version'=>1])->save();
    $reject(fn () => $zipFor(),409,'Schema incompatibility blocks promotion and code rollback');
    $ordersMod->forceFill(['schema_version'=>2])->save();
    config(['lab-modules.business_writes_enabled'=>false]);
    $reject(fn () => $zipFor(),423,'Closed readiness gate prevents managed publication');
    config(['lab-modules.business_writes_enabled'=>true]);
    $publication->refresh()->forceFill(['status'=>'building'])->save();
    formCheck($publication->fresh()->isLive() && LabPublication::findLiveByHost('fictional-build.localhost',app(App\Lab\Publish\LabPublishConfig::class),app(App\Lab\Publish\PublishHost::class)) !== null, 'Last-good site remains available while a replacement builds');
    formCheck($commerce->publicGetOrder($catalogMod,$orderUuid,$accessToken)['order']['status'] === 'paid', 'Source/artifact failures never roll back accepted payment history');
    $manager->unpublish($project,$owner);
    try { $manager->deployArtifact($project,$owner,$zipFor()); throw new RuntimeException('Late artifact republished an unpublished site'); }
    catch (InvalidArgumentException) { formCheck($publication->fresh()->status === 'unpublished' && File::get($destination.'/index.html') === '<h1>FICTIONAL BUILD</h1>', 'Late artifact cannot undo unpublish and last-good files remain recoverable'); }
    $manager->saveAndPublish($project,$owner,['kind'=>'subdomain']);
    formCheck($publication->fresh()->status === 'building','Explicit owner publication starts a new build after unpublish');
    $manager->deployArtifact($project,$owner,$zipFor());
    formCheck($publication->fresh()->status === 'live','Explicit replacement can promote after owner restarts publication');
    formResult('reviewed source CAS, scoped build manifest and last-good artifact promotion');
} finally { app()->useStoragePath($previousStorage); }
