<?php
require __DIR__.'/form-owner-api.php';

use App\Lab\Forms\FormDatastore;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Factory;

$project->forceFill(['frozen_at' => null])->save();
$link = App\Models\UserDatastoreLink::query()->where('user_id', 91)->firstOrFail();
$link->forceFill(['steward_token' => 'fixture-private', 'access_token' => 'fixture-management'])->save();
Http::swap(new Factory); Http::preventStrayRequests();
Http::fake(['*' => Http::response([])]);
$service->setState($owner, $project, $form, 'active', 2);
formCheck($form->fresh()->active_version === 2 && $form->fresh()->status === 'active', 'Activation uses latest approved version');
$next['fields'][0]['label'] = 'ชื่อใหม่';
$service->saveVersion($owner, $project, $form, 2, $next);
formCheck($form->fresh()->active_version === 2, 'Saving draft keeps active version unchanged');
$service->setState($owner, $project, $form, 'closed', 3);
formCheck($form->fresh()->status === 'closed', 'Close stops accepting requests');
$store = app(FormDatastore::class);
try { $store->listing($form, ['cursor' => 'broken']); formCheck(false, 'Invalid cursor rejected'); }
catch (Symfony\Component\HttpKernel\Exception\HttpException $error) { formCheck($error->getStatusCode() === 422, 'Invalid cursor gives 422'); }
$store->listing($form, ['date_from' => '2030-01-01', 'date_to' => '2030-01-01', 'q' => 'มะลิ']);
$request = Http::recorded()->last()[0];
formCheck(str_contains($request->data()['and'], '2029-12-31T17:00:00'), 'Bangkok date filter uses UTC bounds');
formCheck($request->data()['form_uuid'] === 'eq.'.$form->uuid, 'Owner list always scopes form');
formResult('owner activation/submissions');
