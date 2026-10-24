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
    $workspace->write($project, 'src/krikkit-widgets/ContactForm.jsx', '// custom edited widget');
    $expect(fn () => $embed->embed($owner, $project, $module, '/', $wrapperHash), 409, 'Custom widget source is preserved');
    $workspace->write($project, 'src/krikkit-widgets/ContactForm.jsx', $result['files']['src/krikkit-widgets/ContactForm.jsx']);
    $workspace->write($project, 'src/App.jsx', '// custom edited app');
    $expect(fn () => $embed->embed($owner, $project, $module, '/', hash('sha256','// custom edited app')), 409, 'Custom page source is preserved');
    $workspace->write($project, 'src/App.jsx', file_get_contents(resource_path('js/lab/widget-runtime/app-wrapper.jsx')));
    formResult('generated contact widget source and custom-edit preservation');
    echo 'FIXTURE_ROOT='.$workspace->root($project).PHP_EOL;
} finally { app()->useStoragePath($previousStorage); }
