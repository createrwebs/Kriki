<?php
require __DIR__.'/form-submissions-owner.php';

use App\Lab\Forms\FormSubmissionValidator;
use App\Http\Controllers\Api\LabPublicFormController;
use App\Lab\Forms\FormDatastore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Factory;

$validator = new FormSubmissionValidator;
$body = ['version' => 2, 'core' => ['customer_name' => 'มะลิ', 'phone' => '0800000000', 'requested_at' => '2030-01-01T12:00:00+07:00', 'party_size' => 2, 'notes' => ''], 'answers' => [], 'idempotency_key' => '11111111-1111-4111-8111-111111111111', 'website' => ''];
$definition = $v2->definition;
$valid = $validator->validate($body, $definition);
formCheck($valid['core']['requested_at'] === '2030-01-01T05:00:00+00:00', 'Booking time normalized to UTC');
foreach ([['party_size', 21], ['party_size', '2'], ['phone', 'bad'], ['requested_at', '2000-01-01T12:00:00Z'], ['requested_at', '2030-01-01T12:00:00']] as [$key, $value]) {
    $bad = $body; $bad['core'][$key] = $value;
    formInvalid(fn () => $validator->validate($bad, $definition), 'Invalid core rejected: '.$key);
}
$bad = $body; $bad['answers']['unknown'] = 'data';
formInvalid(fn () => $validator->validate($bad, $definition), 'Unknown answer rejected');
$bad = $body; $bad['answers'] = 'not-an-object';
formInvalid(fn () => $validator->validate($bad, $definition), 'Malformed answer object rejected');
$bad = $body; $bad['user_id'] = 91;
formInvalid(fn () => $validator->validate($bad, $definition), 'Forged owner rejected');
$bad = $body; $bad['website'] = 'spam';
formInvalid(fn () => $validator->validate($bad, $definition), 'Honeypot rejected');
$controller = app(LabPublicFormController::class);
$form->forceFill(['status' => 'active', 'active_version' => 2])->save();
Http::swap(new Factory); Http::preventStrayRequests();
Http::fake(['*/rpc/krikkit_submit_form' => Http::response([['submission_uuid' => 'a02467f6-640d-46a1-8220-7e90cd1f108c', 'status' => 'pending', 'replayed' => false]]), '*' => Http::response([])]);
$request = Request::create('/api/lab-forms/'.$form->uuid.'/submissions', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($body));
$response = $controller->store($request, $form, $validator, app(FormDatastore::class));
formCheck($response->getStatusCode() === 201, 'Public valid submission returns 201');
formCheck($response->getData(true)['status'] === 'pending', 'Client cannot auto-confirm');
$bad = $body; $bad['version'] = 1;
$request = Request::create('/', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($bad));
try { $controller->store($request, $form, $validator, app(FormDatastore::class)); formCheck(false, 'Stale version denied'); }
catch (Symfony\Component\HttpKernel\Exception\HttpException $error) { formCheck($error->getStatusCode() === 409, 'Stale public version conflicts'); }
$form->forceFill(['status' => 'closed'])->save();
try { $controller->show($form); formCheck(false, 'Closed definition hidden'); }
catch (Symfony\Component\HttpKernel\Exception\HttpException $error) { formCheck($error->getStatusCode() === 404, 'Closed form gives 404'); }
$canonical = ['version' => $valid['version'], 'core' => $valid['core'], 'answers' => $valid['answers']];
ksort($canonical['core']);
$hash = hash('sha256', json_encode($canonical, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION));
Http::swap(new Factory); Http::preventStrayRequests();
Http::fake(['*' => Http::response([['uuid' => 'a02467f6-640d-46a1-8220-7e90cd1f108c', 'status' => 'confirmed', 'definition_version' => 2, 'definition_snapshot' => $definition, 'payload_hash' => $hash]])]);
$request = Request::create('/', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($body));
$response = $controller->store($request, $form, $validator, app(FormDatastore::class));
formCheck($response->getStatusCode() === 200 && $response->getData(true)['status'] === 'confirmed', 'Lost response replay works after form closes');
Illuminate\Support\Carbon::setTestNow('2031-01-01');
$response = $controller->store($request, $form, $validator, app(FormDatastore::class));
formCheck($response->getStatusCode() === 200, 'Exact receipt replay works after booking time passes');
Illuminate\Support\Carbon::setTestNow();
$bad = $body; $bad['core']['party_size'] = 3;
$request = Request::create('/', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($bad));
try { $controller->store($request, $form, $validator, app(FormDatastore::class)); formCheck(false, 'Changed retry body rejected'); }
catch (Symfony\Component\HttpKernel\Exception\HttpException $error) { formCheck($error->getStatusCode() === 409, 'Changed receipt payload conflicts'); }
formResult('public form validation/API');
