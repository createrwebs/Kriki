<?php

putenv('DB_CONNECTION=sqlite'); putenv('DB_DATABASE=:memory:');
require __DIR__.'/forms-bootstrap.php';

use App\Datastore\DatastoreRevisor;
use App\Models\User;
use Illuminate\Support\Facades\Http;

config([
    'datastore.kinds.supabase.host_url' => 'https://sqlfixture.supabase.co',
    'datastore.kinds.supabase.publishable_token' => 'fixture-public',
    'datastore.kinds.supabase.steward_token' => 'fixture-private',
    'datastore.kinds.supabase.console_token' => 'fixture-management',
]);
Http::fake(['*' => Http::response([])]);
foreach ([null, (new User)->forceFill(['id' => 931])] as $actor) {
    foreach ([false, true] as $ack) {
        $result = app(DatastoreRevisor::class)->revise('CREATE TABLE fictional_fixture (id int)', $ack, user: $actor);
        formCheck(!$result['ok'] && $result['applied'] === 0 && !$result['needs_ack'], 'Missing/customer context and forged ack cannot authorize SQL');
        formCheck(($result['code'] ?? null) === 'schema_approval_required' && $result['statements'] === [], 'Stable approval error never echoes SQL');
    }
}
formCheck(Http::recorded()->isEmpty(), 'Rejected raw SQL makes no schema or broker request');
$survey = app(App\Datastore\DatastoreSurveyor::class)->survey(user: null);
formCheck(!$survey['can_revise'] && !str_contains($survey['message'], 'revise_datastore'), 'Public survey cannot advertise disabled SQL capability');
$lens = app(App\Datastore\Lenses\SupabaseLens::class);
$visible = $lens->tablesFromOpenApi(['definitions' => ['krikkit_contact_messages' => [], 'krikkit_form_submissions' => [], 'legacy_external' => []]]);
formCheck(array_column($visible, 'name') === ['legacy_external'], 'Generic survey hides managed namespaces across projects');
$before = Http::recorded()->count();
$managedRows = $lens->rows(['host_url' => 'https://sqlfixture.supabase.co', 'steward_token' => 'fixture-private'], 'krikkit_contact_messages');
formCheck(!$managedRows['ok'] && $managedRows['rows'] === [] && Http::recorded()->count() === $before, 'Generic row browser cannot bypass scoped managed APIs');
$tools = app(App\Ai\Lab\LabToolCatalog::class)->definitions();
formCheck(!in_array('revise_datastore', array_column(array_column($tools, 'function'), 'name'), true), 'Customer native tool catalog cannot offer raw SQL');
$recovered = app(App\Ai\Lab\PseudoToolCallParser::class)->recover('{"name":"revise_datastore","arguments":{"sql":"DROP TABLE fictional","acknowledge_destructive":true}}');
formCheck($recovered['toolCalls'] === [], 'Pseudo tool cannot recover raw SQL execution');
formResult('customer raw SQL default deny');
