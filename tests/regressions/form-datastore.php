<?php
require __DIR__.'/form-owner-api.php';

use App\Lab\Forms\FormDatastore;
use App\Models\UserDatastoreLink;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Factory;

$store = app(FormDatastore::class);
$link = UserDatastoreLink::query()->where('user_id', 91)->firstOrFail();
$link->steward_token = 'fixture-private';
$link->access_token = 'fixture-management';
$link->save();
Http::swap(new Factory);
Http::preventStrayRequests();
Http::fake(['*' => Http::response([['submission_uuid' => 'fixture-uuid', 'status' => 'pending', 'replayed' => false]])]);
formCheck($store->forForm($form)['project_ref'] === 'fixtureacct', 'Datastore uses pinned ref');
$result = $store->submit($form, ['customer_name' => 'Test', 'phone' => '0800000000', 'requested_at' => '2030-01-01T05:00:00Z', 'party_size' => 2, 'notes' => ''], [], $v1->definition, 1, 'fixture-key', str_repeat('a', 64));
formCheck($result['status'] === 'pending', 'Submit returns result');
$request = Http::recorded()->first()[0];
formCheck(str_ends_with($request->url(), '/rest/v1/rpc/krikkit_submit_form'), 'Submit uses fixed RPC');
formCheck($request->data()['p_form_uuid'] === $form->uuid, 'Submit scopes UUID');
formCheck($request->hasHeader('apikey', 'fixture-private'), 'Server credential used');
$link->project_ref = 'anotherref'; $link->save();
try { $store->forForm($form); formCheck(false, 'Mismatch denied'); }
catch (Symfony\Component\HttpKernel\Exception\HttpException $error) { formCheck($error->getStatusCode() === 503, 'Ref mismatch fails closed'); }
$link->project_ref = 'fixtureacct'; $link->steward_token = null; $link->save();
try { $store->forForm($form); formCheck(false, 'Missing private credential denied'); }
catch (Symfony\Component\HttpKernel\Exception\HttpException $error) { formCheck($error->getStatusCode() === 503, 'Missing private credential fails closed'); }
$link->delete();
try { $store->forForm($form); formCheck(false, 'Disconnected denied'); }
catch (Symfony\Component\HttpKernel\Exception\HttpException $error) { formCheck($error->getStatusCode() === 503, 'Disconnected fails closed'); }
formCheck(Http::recorded()->count() === 1, 'Rejected resolutions make no upstream request');
formResult('form datastore');
