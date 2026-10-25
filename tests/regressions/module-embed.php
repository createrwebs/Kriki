<?php

require __DIR__.'/managed-module-bootstrap.php';

use App\Lab\Builder\BlueprintSource;
use App\Lab\Builder\TemplateCatalog;
use App\Lab\Modules\ModuleEmbed;
use App\Lab\Modules\ModuleInstallations;
use App\Lab\SiteWorkspace;
use App\Models\UserDatastoreLink;
use Illuminate\Support\Str;

$previousStorage = app()->storagePath();
$fixtureStorage = base_path('.agent-work/qa/contact-widget-'.Str::uuid());
app()->useStoragePath($fixtureStorage);
try {
    $workspace = app(SiteWorkspace::class); $modules = app(ModuleInstallations::class); $embed = app(ModuleEmbed::class);
    $allow('contact'); $modules->select($owner, $project, 'contact', true, 0);
    UserDatastoreLink::create(['user_id' => $owner->id, 'driver' => 'supabase', 'host_url' => 'https://fixturemodules.supabase.co', 'publishable_token' => 'fictional-public', 'steward_token' => 'fictional-private', 'access_token' => 'fictional-management', 'project_ref' => 'fixturemodules']);
    $module = $modules->prepare($owner, $project, 'contact', 1, [], (string) Str::uuid())['installation'];
    $module->forceFill(['status' => 'ready','schema_version' => 1])->save();
    $source = (new BlueprintSource)->compile((new TemplateCatalog)->blueprint('business','business-studio','เว็บไซต์ติดต่อสมมติ'), (string) Str::uuid(), 1);
    $workspace->commitAtomic($project, $source['files']);
    $original = $workspace->read($project, 'src/App.jsx'); $hash = hash('sha256', $original);
    $expect(fn () => $embed->embed($other, $project, $module, '/', $hash), 403, 'Another actor cannot add widgets');
    $expect(fn () => $embed->embed($owner, $sibling, $module, '/', $hash), 404, 'Same-owner foreign project cannot add widgets');
    $expect(fn () => $embed->embed($owner, $project, $module, '/', str_repeat('0',64)), 409, 'Stale App hash cannot overwrite source');
    $expect(fn () => $embed->embed($owner, $project, $module, '/missing', $hash), 422, 'Widget must target an existing page');
    $result = $embed->embed($owner, $project, $module, '/', $hash);
    formCheck($workspace->read($project, 'src/SiteContent.jsx') === $original, 'Original generated page source is preserved byte-for-byte');
    formCheck(isset($result['files']['src/krikkit-widgets/ContactForm.jsx']) && str_contains($workspace->read($project, 'src/App.jsx'), '<ContactForm'), 'Actual App imports and renders the contact widget');
    formCheck(!str_contains(json_encode($result), 'fictional-private') && !str_contains(json_encode($result), 'fictional-management'), 'Generated files contain no private database credentials');
    $wrapperHash = hash('sha256', $workspace->read($project, 'src/App.jsx'));
    $embed->embed($owner, $project, $module, '/', $wrapperHash);
    $config = json_decode($workspace->read($project, 'src/krikkit-widgets/config.json'), true);
    formCheck(count($config['widgets']) === 1 && $config['widgets'][0]['moduleUuid'] === $module->uuid, 'Repeated installation updates one widget binding');
    try { $workspace->write($project, 'src/krikkit-widgets/ContactForm.jsx', '// ordinary AI write'); throw new RuntimeException('Managed source was writable'); }
    catch (InvalidArgumentException) { formCheck(true, 'Ordinary file tools cannot modify managed SDK source'); }
    Illuminate\Support\Facades\File::put($workspace->root($project).'/src/krikkit-widgets/ContactForm.jsx', '// custom edited widget');
    $expect(fn () => $embed->embed($owner, $project, $module, '/', $wrapperHash), 409, 'Custom widget source is preserved');
    Illuminate\Support\Facades\File::put($workspace->root($project).'/src/krikkit-widgets/ContactForm.jsx', $result['files']['src/krikkit-widgets/ContactForm.jsx']);
    $workspace->write($project, 'src/App.jsx', '// custom edited app');
    $expect(fn () => $embed->embed($owner, $project, $module, '/', hash('sha256','// custom edited app')), 409, 'Custom page source is preserved');
    $workspace->write($project, 'src/App.jsx', file_get_contents(resource_path('js/lab/widget-runtime/app-wrapper.jsx')));
    $plan = $embed->plan($owner, $project, $module, '/');
    $configurationPath = 'src/krikkit-widgets/config.json';
    $configuration = $workspace->read($project, $configurationPath);
    Illuminate\Support\Facades\File::put($workspace->root($project).'/'.$configurationPath, $configuration."\n");
    $expect(fn () => $embed->embed($owner, $project, $module, '/', $wrapperHash, $plan['expected_files'], $plan['impact_hash']), 409, 'Concurrent config changes invalidate the full source snapshot');
    formCheck($workspace->read($project, $configurationPath) === $configuration."\n", 'Rejected CAS preserves the changed config');
    Illuminate\Support\Facades\File::put($workspace->root($project).'/'.$configurationPath, $configuration);
    $manifestPath = App\Lab\Modules\ModuleEmbed::MANIFEST;
    $originalManifest = $workspace->read($project, $manifestPath);
    $manifest = json_decode($originalManifest, true);
    formCheck(App\Lab\Modules\ModuleEmbed::verifyManifest($manifest, $project->uuid), 'Managed ownership manifest is authenticated and scoped');
    $manifest['sdk_version'] = 99;
    Illuminate\Support\Facades\File::put($workspace->root($project).'/'.$manifestPath, json_encode($manifest));
    $expect(fn () => $embed->plan($owner, $project, $module, '/'), 409, 'Forged manifest cannot claim ownership of custom source');
    Illuminate\Support\Facades\File::put($workspace->root($project).'/'.$manifestPath, $originalManifest);
    try { $workspace->commitAtomic($project, ['src/App.jsx' => '// should not apply', $configurationPath => '{}']); throw new RuntimeException('Managed batch was writable'); }
    catch (InvalidArgumentException) { formCheck(hash('sha256', $workspace->read($project,'src/App.jsx')) === $wrapperHash, 'A mixed ordinary batch is rejected before touching any files'); }
    try { $workspace->delete($project, 'src'); throw new RuntimeException('Managed ancestor was deletable'); }
    catch (InvalidArgumentException) { formCheck(is_file($workspace->root($project).'/'.$manifestPath), 'Ordinary deletion cannot remove an SDK ancestor'); }
    $protectedFile = $workspace->root($project).'/src/krikkit-widgets/ContactForm.jsx';
    $protectedHash = hash_file('sha256',$protectedFile);
    try { $workspace->write($project,'src/krikkit-widgets./ContactForm.jsx','// Windows alias overwrite'); throw new RuntimeException('Windows alias bypassed protected SDK'); }
    catch (InvalidArgumentException) { formCheck(hash_file('sha256',$protectedFile) === $protectedHash,'Windows trailing-dot aliases cannot bypass managed source protection'); }
    formResult('generated contact widget source and custom-edit preservation');
    echo 'FIXTURE_ROOT='.$workspace->root($project).PHP_EOL;
} finally { app()->useStoragePath($previousStorage); }
