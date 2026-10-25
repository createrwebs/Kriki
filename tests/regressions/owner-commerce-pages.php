<?php

require __DIR__.'/commerce-quotes-flow.php';

use Illuminate\Support\Str;

$createdFixtures = [];
for ($i=0; $i<26; $i++) $createdFixtures[] = $create();
$page = $commerce->listOrders($owner, $project, $ordersMod);
formCheck(count($page['items']) === 25 && $page['limit'] === 25 && is_string($page['next_cursor']), 'Owner orders have bounded keyset pagination');
$firstIds = array_column($page['items'], 'uuid');
$second = $commerce->listOrders($owner, $project, $ordersMod, null, $page['next_cursor']);
formCheck(count($second['items']) > 0 && array_intersect($firstIds, array_column($second['items'], 'uuid')) === [], 'Next order page contains older rows without overlap');
$create();
$afterInsert = $commerce->listOrders($owner, $project, $ordersMod, null, $page['next_cursor']);
formCheck(array_column($afterInsert['items'], 'uuid') === array_column($second['items'], 'uuid'), 'New arrivals do not shift a saved history cursor');
$target = $createdFixtures[0];
$fixtureImage = imagecreatetruecolor(10,10); ob_start(); imagepng($fixtureImage); $smallImage = 'data:image/png;base64,'.base64_encode(ob_get_clean()); imagedestroy($fixtureImage);
for ($i=0; $i<26; $i++) $commerce->publicSubmitSlip($catalogMod->fresh(), $target['order']['uuid'], $target['access_token'], ['idempotency_key' => (string) Str::uuid(), 'payer_name' => 'Fictional page '.$i, 'amount_satang' => 29000, 'slip_data_url' => $smallImage]);
$attemptPage = $commerce->listOrderAttempts($owner, $project, $ordersMod, $target['order']['uuid']);
$attemptNext = $commerce->listOrderAttempts($owner, $project, $ordersMod, $target['order']['uuid'], $attemptPage['next_cursor']);
formCheck(count($attemptPage['items']) === 25 && count($attemptNext['items']) === 1 && $attemptNext['next_cursor'] === null, 'Per-order attempt history includes every evidence row beyond the first page');
formCheck(count(array_unique([...array_column($attemptPage['items'], 'uuid'), ...array_column($attemptNext['items'], 'uuid')])) === 26 && !str_contains(json_encode($attemptPage), 'data:image') && !str_contains(json_encode($attemptPage), 'slip_object') && $attemptPage['items'][0]['image_available'], 'Attempt pages contain unique scoped metadata without image/object leaks');
$as($owner);
$listPath = "/lab/{$project->uuid}/modules/{$ordersMod->uuid}/orders";
$historyPath = $listPath.'/'.$target['order']['uuid'].'/payment-attempts';
formCheck($ownerHttp($listPath.'?cursor='.urlencode($page['next_cursor']))->getStatusCode() === 200, 'Actual owner order route accepts next-page cursor');
formCheck($ownerHttp($historyPath.'?cursor='.urlencode($attemptPage['next_cursor']))->getStatusCode() === 200, 'Actual owner attempt route accepts scoped history cursor');
foreach (['not-base64', base64_encode(json_encode(['2026-99-99T00:00:00Z',(string) Str::uuid()])), base64_encode(json_encode(['2026-10-25T00:00:00Z','forged,or=(true)']))] as $invalid) formCheck($ownerHttp($historyPath.'?cursor='.urlencode($invalid))->getStatusCode() === 422, 'Malformed history cursor is rejected before datastore query');
formCheck($ownerHttp($historyPath.'?project_uuid=forged')->getStatusCode() === 422, 'Caller cannot override history scope');
$expect(fn () => $commerce->listOrderAttempts($owner, $sibling, $ordersMod, $target['order']['uuid']), 404, 'Same owner foreign project cannot read attempt history');
$expect(fn () => $commerce->listOrderAttempts($owner, $project, $ordersMod, (string) Str::uuid()), 404, 'Attempt history requires an order bound to this module');
$as($other); formCheck($ownerHttp($historyPath)->getStatusCode() === 403, 'Actual history endpoint rejects another account');
$as($owner);
formResult('owner order/attempt keyset pagination, complete history and scoped HTTP boundaries');
