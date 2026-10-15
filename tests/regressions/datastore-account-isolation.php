<?php

// Standalone regression: uses only an in-memory database and fake HTTP.
require __DIR__.'/../../vendor/autoload.php';
$app = require __DIR__.'/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Datastore\DatastoreKind;
use App\Datastore\DatastoreRevisor;
use App\Datastore\DatastoreSurveyor;
use App\Datastore\SupabaseCloud;
use App\Datastore\SupabaseLinkBroker;
use App\Models\User;
use App\Models\UserDatastoreLink;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

config([
    'database.default' => 'datastore_regression',
    'database.connections.datastore_regression' => [
        'driver' => 'sqlite', 'database' => ':memory:', 'foreign_key_constraints' => false,
    ],
    'cache.default' => 'array',
    'datastore.schema_cache_seconds' => 0,
    'datastore.kinds.supabase.host_url' => 'https://workspacefixture.supabase.co',
    'datastore.kinds.supabase.publishable_token' => 'fixture-workspace-public',
    'datastore.kinds.supabase.steward_token' => 'fixture-workspace-private',
    'datastore.kinds.supabase.console_token' => 'fixture-workspace-management',
]);
DB::purge('datastore_regression');
(require __DIR__.'/../../database/migrations/2026_08_18_021000_create_user_datastore_links_table.php')->up();
Http::preventStrayRequests();
Http::fake(['*' => Http::response(['definitions' => []])]);

$checks = 0;
$failures = [];
$check = function (bool $ok, string $label) use (&$checks, &$failures): void {
    $checks++;
    if (! $ok) {
        $failures[] = $label;
        echo "FAIL: {$label}\n";
    }
};
$unlinked = new User;
$unlinked->id = 101;
$linked = new User;
$linked->id = 102;
$partial = new User;
$partial->id = 103;

UserDatastoreLink::query()->create([
    'user_id' => $linked->id, 'driver' => 'supabase',
    'host_url' => 'https://accountfixture.supabase.co',
    'publishable_token' => 'fixture-account-public',
    'steward_token' => 'fixture-account-private',
    'access_token' => 'fixture-account-management',
    'project_ref' => 'accountfixture',
]);
UserDatastoreLink::query()->create([
    'user_id' => $partial->id, 'driver' => 'supabase',
    'access_token' => 'fixture-pending-oauth',
]);

$broker = app(SupabaseLinkBroker::class);
foreach ([$unlinked, $partial] as $user) {
    $plane = $broker->planeFor($user);
    $check(! $plane['ready'] && ! $plane['can_revise'], 'Unattached account has no data-plane privileges');
    foreach (['host_url', 'publishable_token', 'steward_token', 'console_token', 'project_ref'] as $key) {
        $check($plane[$key] === '', "Unattached account does not inherit {$key}");
    }
    $survey = app(DatastoreSurveyor::class)->survey(user: $user);
    $check(! $survey['ready'] && $survey['client_env'] === [], 'Survey exposes no workspace client credentials');
    $revision = app(DatastoreRevisor::class)->revise('CREATE TABLE fixture (id int)', user: $user);
    $check(! $revision['ok'] && $revision['applied'] === 0, 'Revision is rejected before contacting workspace database');
    try {
        app(SupabaseCloud::class)->users($user);
        $check(false, 'Unattached account cannot list workspace auth users');
    } catch (RuntimeException $error) {
        $check($error->getMessage() === 'No data plane is attached.', 'Auth admin fails closed for unattached account');
    }
}
$check(Http::recorded()->isEmpty(), 'Unattached accounts make no upstream requests');

$plane = $broker->planeFor($linked);
$check($plane['ready'] && $plane['can_revise'], 'Linked account remains usable');
$check($plane['host_url'] === 'https://accountfixture.supabase.co', 'Linked account uses its selected project');
$check($plane['steward_token'] === 'fixture-account-private', 'Linked account retains its own server credential');
$survey = app(DatastoreSurveyor::class)->survey(user: $linked);
$check($survey['ready'], 'Linked account can survey its project');
$check(($survey['client_env']['VITE_SUPABASE_ANON_KEY'] ?? '') === 'fixture-account-public', 'Survey returns only the selected publishable key');
$check(! str_contains(json_encode($survey), 'fixture-account-private') && ! str_contains(json_encode($survey), 'fixture-account-management'), 'Survey does not expose private credentials');
$check($broker->planeFor(null)['host_url'] === 'https://workspacefixture.supabase.co', 'Explicit system context preserves configured workspace fallback');

$broker->unlink($partial);
$check($broker->planeFor($linked)['host_url'] === 'https://accountfixture.supabase.co', 'Disconnecting another account preserves the linked account');
$broker->unlink($linked);
$plane = $broker->planeFor($linked);
$check(! $plane['ready'] && $plane['steward_token'] === '' && $plane['console_token'] === '', 'Disconnecting an account revokes access instead of restoring workspace credentials');
$check(! $broker->statusFor($linked)['ready'], 'Status remains consistent after disconnect');

if ($failures !== []) {
    echo count($failures)." failed / {$checks} checks\n";
    exit(1);
}
echo "PASS: {$checks} datastore account-isolation checks (SQLite memory, fake HTTP).\n";
