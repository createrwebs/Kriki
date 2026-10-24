<?php

putenv('DB_CONNECTION=sqlite'); putenv('DB_DATABASE=:memory:');
putenv('CACHE_STORE=array'); putenv('SESSION_DRIVER=array'); putenv('LOG_CHANNEL=null');
putenv('APP_CONFIG_CACHE='.__DIR__.'/no-module-config-cache.php');
require __DIR__.'/forms-bootstrap.php';

use App\Models\LabProject;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

formCheck(DB::getDriverName() === 'sqlite' && DB::connection()->getDatabaseName() === ':memory:', 'Only in-memory control database');
Illuminate\Support\Facades\Notification::fake();
Schema::create('users', function (Blueprint $table) { $table->id(); $table->string('name'); $table->string('email'); $table->string('password'); $table->timestamp('email_verified_at')->nullable(); $table->timestamps(); });
(require __DIR__.'/../../database/migrations/2026_08_04_051600_create_lab_projects_table.php')->up();
(require __DIR__.'/../../database/migrations/2026_08_04_070600_add_workspace_columns_to_lab_projects_table.php')->up();
(require __DIR__.'/../../database/migrations/2026_09_26_000000_add_project_type_to_lab_projects_table.php')->up();
Schema::table('lab_projects', function (Blueprint $table) { $table->timestamp('frozen_at')->nullable(); $table->timestamp('opened_at')->nullable(); });
(require __DIR__.'/../../database/migrations/2026_08_18_021000_create_user_datastore_links_table.php')->up();
(require __DIR__.'/../../database/migrations/2026_10_15_010000_create_lab_forms_tables.php')->up();
(require __DIR__.'/../../database/migrations/2026_10_17_110000_create_lab_module_control_tables.php')->up();
(require __DIR__.'/../../database/migrations/2026_10_17_120000_create_lab_change_proposals.php')->up();
Schema::create('entitlement_plans', function (Blueprint $table) {
    $table->id(); $table->string('public_id'); $table->string('slug'); $table->string('title'); $table->integer('rank')->default(0);
    $table->boolean('is_active')->default(true); $table->boolean('is_default')->default(true); $table->decimal('price_monthly')->nullable(); $table->decimal('price_yearly')->nullable(); $table->timestamps();
});
Schema::create('plan_grants', function (Blueprint $table) { $table->id(); $table->foreignId('entitlement_plan_id'); $table->string('code'); $table->string('kind'); $table->boolean('allowed')->default(false); $table->integer('ceiling')->nullable(); $table->string('window'); $table->timestamps(); });
Schema::create('user_entitlements', function (Blueprint $table) { $table->id(); $table->string('public_id'); $table->foreignId('user_id'); $table->foreignId('entitlement_plan_id'); $table->string('status'); $table->timestamp('started_at')->nullable(); $table->timestamp('ends_at')->nullable(); $table->timestamps(); });
$owner = User::withoutEvents(fn () => User::forceCreate(['name' => 'Fictional owner', 'email' => 'schema-owner@example.invalid', 'password' => 'fixture', 'email_verified_at' => now()]));
$other = User::withoutEvents(fn () => User::forceCreate(['name' => 'Fictional other', 'email' => 'schema-other@example.invalid', 'password' => 'fixture', 'email_verified_at' => now()]));
$project = LabProject::create(['user_id' => $owner->id, 'title' => 'Fictional schema']);
$sibling = LabProject::create(['user_id' => $owner->id, 'title' => 'Fictional sibling']);
$foreign = LabProject::create(['user_id' => $other->id, 'title' => 'Fictional foreign']);
$plan = App\Models\EntitlementPlan::create(['slug' => 'module-fixture', 'title' => 'Module fixture', 'rank' => 0, 'is_active' => true, 'is_default' => true]);
foreach ([$owner, $other] as $actor) App\Models\UserEntitlement::create(['user_id' => $actor->id, 'entitlement_plan_id' => $plan->id, 'status' => 'active']);
$allow = function (string $key, bool $enabled = true) use ($plan, $owner, $other) {
    $plan->grants()->updateOrCreate(['code' => 'lab_module_'.$key], ['kind' => 'feature', 'allowed' => $enabled, 'window' => 'none']);
    $owner->unsetRelation('entitlement'); $other->unsetRelation('entitlement');
};
$expect = function (callable $action, int $status, string $label) {
    try { $action(); formCheck(false, $label); }
    catch (Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $error) { formCheck($error->getStatusCode() === $status, $label.' got '.$error->getStatusCode()); }
    catch (Illuminate\Auth\Access\AuthorizationException $error) { formCheck($status === 403, $label); }
    catch (App\Entitlement\Exceptions\EntitlementDeniedException $error) { formCheck($status === 402, $label); }
};
