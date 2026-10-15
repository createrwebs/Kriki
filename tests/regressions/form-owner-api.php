<?php
require __DIR__.'/forms-bootstrap.php';

use App\Lab\Forms\FormDefinitions;
use App\Models\LabForm;
use App\Models\LabFormVersion;
use App\Models\LabProject;
use App\Models\User;
use App\Models\UserDatastoreLink;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Schema::create('users', function (Blueprint $table) { $table->id(); });
Illuminate\Support\Facades\DB::table('users')->insert([['id' => 91], ['id' => 92]]);
(require __DIR__.'/../../database/migrations/2026_08_04_051600_create_lab_projects_table.php')->up();
Schema::table('lab_projects', function (Blueprint $table) { $table->timestamp('frozen_at')->nullable(); $table->timestamp('opened_at')->nullable(); });
(require __DIR__.'/../../database/migrations/2026_08_18_021000_create_user_datastore_links_table.php')->up();
(require __DIR__.'/../../database/migrations/2026_10_15_010000_create_lab_forms_tables.php')->up();
$owner = new User; $owner->id = 91;
$other = new User; $other->id = 92;
$project = LabProject::query()->create(['user_id' => 91, 'title' => 'Fixture']);
$otherProject = LabProject::query()->create(['user_id' => 91, 'title' => 'Other']);
UserDatastoreLink::query()->create(['user_id' => 91, 'driver' => 'supabase', 'host_url' => 'https://fixtureacct.supabase.co', 'publishable_token' => 'fixture-public', 'project_ref' => 'fixtureacct']);
$service = app(FormDefinitions::class);
$form = $service->create($owner, $project, 'จองโต๊ะ');
formCheck($form->status === 'draft' && $form->active_version === null, 'New form is private draft');
formCheck($form->supabase_project_ref === 'fixtureacct', 'Selected account project is pinned');
$v1 = $form->versions()->firstOrFail();
$next = $v1->definition;
$next['fields'][0]['label'] = 'ชื่อผู้ติดต่อ';
$v2 = $service->saveVersion($owner, $project, $form, 1, $next);
formCheck($v2->version === 2 && $form->fresh()->active_version === null, 'Save creates draft version without activation');
formCheck($v1->fresh()->definition['fields'][0]['label'] === 'ชื่อผู้จอง', 'Old version remains immutable');
try { $service->saveVersion($owner, $project, $form, 1, $next); formCheck(false, 'Stale version must conflict'); }
catch (Symfony\Component\HttpKernel\Exception\HttpException $error) { formCheck($error->getStatusCode() === 409, 'Stale save returns 409'); }
foreach ([[$other, $project], [$owner, $otherProject]] as [$actor, $target]) {
    try { $service->detail($actor, $target, $form); formCheck(false, 'Access should be denied'); }
    catch (AuthorizationException) { formCheck(true, 'Account and project scope enforced'); }
}
$project->forceFill(['frozen_at' => now()])->save();
try { $service->saveVersion($owner, $project, $form, 2, $next); formCheck(false, 'Frozen save denied'); }
catch (AuthorizationException) { formCheck(true, 'Frozen project cannot edit'); }
formCheck($service->detail($owner, $project, $form)['latest_version'] === 2, 'Owner can still read frozen form');
try { $v1->definition = $next; $v1->save(); formCheck(false, 'Version cannot be overwritten'); }
catch (LogicException) { formCheck(true, 'Version model refuses updates'); }
formCheck(LabForm::query()->count() === 1 && LabFormVersion::query()->count() === 2, 'No extra records after rejected operations');
formResult('form owner/version API services');
