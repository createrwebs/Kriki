<?php

require __DIR__.'/forms-bootstrap.php';

use App\Entitlement\EntitlementCatalog;
use App\Entitlement\EntitlementGate;
use App\Finance\CreditKind;
use App\Models\CreditGrant;
use App\Models\EntitlementPlan;
use App\Models\User;
use App\Models\UserEntitlement;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email');
    $table->string('password');
    $table->timestamp('email_verified_at')->nullable();
    $table->timestamps();
});
(require __DIR__.'/../../database/migrations/2026_08_15_231300_create_entitlement_tables.php')->up();
(require __DIR__.'/../../database/migrations/2026_08_04_051600_create_lab_projects_table.php')->up();
Schema::create('credit_grants', function (Blueprint $table) {
    $table->id();
    $table->ulid('public_id')->unique();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->integer('amount');
    $table->string('kind');
    $table->string('reason')->nullable();
    $table->foreignId('granted_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('entitlement_plan_id')->nullable()->constrained('entitlement_plans')->nullOnDelete();
    $table->foreignId('invoice_id')->nullable();
    $table->timestamps();
});

$alice = User::withoutEvents(fn () => User::forceCreate([
    'name' => 'Alice fixture', 'email' => 'alice@example.invalid', 'password' => 'fixture', 'email_verified_at' => now(),
]));
$bob = User::withoutEvents(fn () => User::forceCreate([
    'name' => 'Bob fixture', 'email' => 'bob@example.invalid', 'password' => 'fixture', 'email_verified_at' => now(),
]));

$alicePlan = EntitlementPlan::create(['slug' => 'alice-plan', 'title' => 'Alice plan', 'rank' => 1, 'is_default' => false, 'is_active' => true]);
$bobPlan = EntitlementPlan::create(['slug' => 'bob-plan', 'title' => 'Bob plan', 'rank' => 1, 'is_default' => false, 'is_active' => true]);
foreach ([[$alicePlan, 100], [$bobPlan, 10]] as [$plan, $limit]) {
    $plan->grants()->create(['code' => EntitlementCatalog::LAB_CREDITS, 'kind' => 'quota', 'ceiling' => $limit, 'window' => 'monthly']);
}
UserEntitlement::create(['user_id' => $alice->id, 'entitlement_plan_id' => $alicePlan->id, 'status' => 'active']);
UserEntitlement::create(['user_id' => $bob->id, 'entitlement_plan_id' => $bobPlan->id, 'status' => 'active']);

$window = now()->format('Y-m');
App\Models\UsageLedger::create(['user_id' => $alice->id, 'code' => EntitlementCatalog::LAB_CREDITS, 'window_key' => $window, 'consumed' => 7]);
App\Models\UsageLedger::create(['user_id' => $bob->id, 'code' => EntitlementCatalog::LAB_CREDITS, 'window_key' => $window, 'consumed' => 2]);
CreditGrant::create(['user_id' => $alice->id, 'amount' => 20, 'kind' => CreditKind::Grant, 'reason' => 'fictional Alice bonus', 'entitlement_plan_id' => $alicePlan->id]);

$gate = app(EntitlementGate::class);
$aliceSnapshot = $gate->creditSnapshot($alice);
$bobSnapshot = $gate->creditSnapshot($bob);
formCheck($gate->used($alice, EntitlementCatalog::LAB_CREDITS) === 7, 'Alice sees only Alice credit usage');
formCheck($gate->used($bob, EntitlementCatalog::LAB_CREDITS) === 2, 'Bob sees only Bob credit usage');
formCheck($aliceSnapshot['limit'] === 120 && $aliceSnapshot['used'] === 7 && $aliceSnapshot['remaining'] === 113, 'Alice receives only Alice plan plus bonus');
formCheck($bobSnapshot['limit'] === 10 && $bobSnapshot['used'] === 2 && $bobSnapshot['remaining'] === 8, 'Bob receives only Bob plan with no Alice bonus');

$gate->consume($alice, EntitlementCatalog::LAB_CREDITS, 5);
formCheck($gate->used($alice, EntitlementCatalog::LAB_CREDITS) === 12, 'Alice consumption updates Alice ledger');
formCheck($gate->used($bob, EntitlementCatalog::LAB_CREDITS) === 2, 'Alice consumption does not update Bob ledger');

try {
    $gate->assertQuota($bob, EntitlementCatalog::LAB_CREDITS, 9);
    formCheck(false, 'Bob quota is enforced against Bob usage only');
} catch (App\Entitlement\Exceptions\EntitlementDeniedException) {
    formCheck(true, 'Bob quota is enforced against Bob usage only');
}
$gate->assertQuota($alice, EntitlementCatalog::LAB_CREDITS, 100);
formCheck(true, 'Alice quota is not reduced by Bob usage');

formResult('account entitlement and credit isolation');
