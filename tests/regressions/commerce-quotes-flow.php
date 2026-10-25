<?php

require __DIR__.'/private-slip-flow.php';

use Illuminate\Support\Str;

app('cache')->store('array')->flush();
$quotePath = "/api/lab-widgets/{$catalogMod->uuid}/quotes";
$ordersPath = "/api/lab-widgets/{$catalogMod->uuid}/orders";
$cart = ['items' => [['product_uuid' => $prod1Uuid, 'quantity' => 1]]];
formCheck($publicHttp($quotePath, 'POST', $cart, [], 'https://foreign.invalid')->getStatusCode() === 403, 'Quote rejects a foreign publication origin');
formCheck($publicHttp($quotePath, 'POST', $cart, [], null)->getStatusCode() === 403, 'Quote requires an authorized Origin');
formCheck($publicHttp($quotePath, 'POST', $cart + ['total_satang' => 1])->getStatusCode() === 422, 'Caller cannot inject a quote total');
$quoted = $publicHttp($quotePath, 'POST', $cart);
$quote = json_decode($quoted->getContent(), true)['quote'] ?? [];
formCheck($quoted->getStatusCode() === 201 && ($quote['total_satang'] ?? 0) === 28000 && !isset($quote['settings']) && str_contains($quoted->headers->get('Cache-Control'), 'no-store'), 'Actual quote route returns authoritative amount without private recipient snapshot');
$creation = ['idempotency_key' => (string) Str::uuid(), 'quote_uuid' => $quote['quote_uuid'], 'customer_name' => 'Fictional quote customer', 'customer_email' => 'quote@example.invalid', ...$cart];
$product = collect($commerce->listProducts($owner, $project, $catalogMod)['items'])->firstWhere('uuid', $prod1Uuid);
$commerce->updateProduct($owner, $project, $catalogMod, $prod1Uuid, ['expected_version' => $product['version'], 'sku' => $product['sku'], 'title' => $product['title'], 'description' => $product['description'], 'price_satang' => 29000, 'active' => true]);
formCheck($publicHttp($ordersPath, 'POST', $creation)->getStatusCode() === 409, 'Actual create route requires a fresh confirmation after price change');
$creation['quote_uuid'] = json_decode($publicHttp($quotePath, 'POST', $cart)->getContent(), true)['quote']['quote_uuid'];
$created = $publicHttp($ordersPath, 'POST', $creation);
$receipt = json_decode($created->getContent(), true);
formCheck($created->getStatusCode() === 201 && ($receipt['order']['total_satang'] ?? 0) === 29000, 'Explicit fresh quote creates the newly confirmed amount');
formCheck($publicHttp($ordersPath, 'POST', $creation)->getStatusCode() === 200, 'Actual create route recovers accepted creation with the same quote/key');
$revokePath = "/lab/{$project->uuid}/modules/{$ordersMod->uuid}/orders/{$receipt['order']['uuid']}/revoke-access";
$as(null); formCheck($ownerHttp($revokePath, 'POST', '{}')->getStatusCode() === 401, 'Guest cannot revoke another order');
$as($other); formCheck($ownerHttp($revokePath, 'POST', '{}')->getStatusCode() === 403, 'Other account cannot revoke owner order');
$as($owner); formCheck($ownerHttp($revokePath, 'POST', '{}', false)->getStatusCode() === 419, 'Owner revoke requires CSRF');
formCheck($ownerHttp($revokePath, 'POST', ['actor_id' => 999])->getStatusCode() === 422, 'Owner revoke actor comes from session, not caller body');
$project->forceFill(['frozen_at' => now()])->save(); $allow('orders', false);
formCheck($ownerHttp($revokePath, 'POST', '{}')->getStatusCode() === 200, 'Security revocation remains available after freeze and downgrade');
$path = $ordersPath.'/'.$receipt['order']['uuid'];
$headers = ['X-Krikkit-Order-Token' => $receipt['access_token']];
formCheck($publicHttp($path, 'GET', null, $headers)->getStatusCode() === 404, 'Revoked guest capability cannot read history');
formCheck($publicHttp($path.'/slips', 'POST', $body(), $headers)->getStatusCode() === 404, 'Revoked guest capability cannot upload evidence');
$project->forceFill(['frozen_at' => null])->save(); $allow('orders');
app('cache')->store('array')->flush();
$statuses = [];
for ($i=0; $i<11; $i++) $statuses[] = $publicHttp($quotePath, 'POST', $cart)->getStatusCode();
formCheck($statuses === [...array_fill(0,10,201),429], 'Quote endpoint has an independent ten-per-minute limiter');
formResult('commerce quotes/reprice and owner guest-access revocation over actual HTTP');
