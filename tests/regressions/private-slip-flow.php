<?php

require __DIR__.'/commerce-flow.php';

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

Http::allowStrayRequests(['http://127.0.0.1:4910/__fixture/*', 'http://127.0.0.1:4900/supabase/*']);
$fixture = fn (string $action, array $values) => Http::timeout(5)->withOptions(['proxy' => ''])->post('http://127.0.0.1:4910/__fixture/'.$action, $values)->throw()->json();
$fault = fn (string $operation) => $fixture('fault', ['module_uuid' => $paymentsMod->uuid, 'operation' => $operation]);
$state = fn () => Http::timeout(5)->withOptions(['proxy' => ''])->get('http://127.0.0.1:4910/__fixture/state', ['module' => $paymentsMod->uuid])->throw()->json();
$create = fn () => $commerce->publicCreateOrder($catalogMod->fresh(), ['idempotency_key' => (string) Str::uuid(), 'quote_uuid' => $commerce->publicQuote($catalogMod->fresh(), ['items' => [['product_uuid' => $prod1Uuid, 'quantity' => 1]]])['quote']['quote_uuid'], 'customer_name' => 'Fictional private storage', 'customer_email' => 'private@example.invalid', 'items' => [['product_uuid' => $prod1Uuid, 'quantity' => 1]]]);
$body = fn () => ['idempotency_key' => (string) Str::uuid(), 'payer_name' => 'Fictional payer', 'amount_satang' => 28000, 'slip_data_url' => $png1x1];
$send = fn (array $order, array $body) => $commerce->publicSubmitSlip($catalogMod->fresh(), $order['order']['uuid'], $order['access_token'], $body);
$image = $commerce->slipImage($owner, $project, $paymentsMod, $attemptUuid);
formCheck(str_contains($image['url'], '/storage/v1/object/sign/krikkit-slips-') && now()->diffInSeconds(Illuminate\Support\Carbon::parse($image['expires_at'])) <= 300, 'Owner image is a five-minute signed URL on pinned Storage origin');
$download = Http::timeout(5)->withOptions(['proxy' => ''])->get($image['url']);
formCheck($download->successful() && str_starts_with($download->body(), "\x89PNG\r\n\x1a\n"), 'Signed URL serves the actual normalized PNG bytes over simulation');
$unsigned = explode('?token=', $image['url'])[0];
formCheck(Http::timeout(5)->withOptions(['proxy' => ''])->get($unsigned)->status() === 403, 'Unsigned private object cannot be downloaded');
$list = $commerce->listOrders($owner, $project, $ordersMod);
$listedAttempts = array_merge(...array_column($list['items'], 'payment_attempts'));
formCheck(!str_contains(json_encode($list), 'data:image') && $listedAttempts[0]['image_available'], 'Owner order list returns metadata and no image bytes or signed URL');
$expect(fn () => $commerce->slipImage($other, $foreign, $paymentsMod, $attemptUuid), 404, 'Other owner cannot request a signed image');
$expect(fn () => $commerce->slipImage($owner, $sibling, $paymentsMod, $attemptUuid), 404, 'Same owner wrong project cannot request image');

$baseline = count($state()['objects']);
foreach (['upload','upload_after','commit_before'] as $operation) {
    $order = $create(); $submission = $body(); $fault($operation);
    $expect(fn () => $send($order, $submission), 503, $operation.' fails without accepting a slip');
    formCheck(count($state()['objects']) === $baseline && $commerce->publicGetOrder($catalogMod, $order['order']['uuid'], $order['access_token'])['order']['status'] === 'awaiting_payment', 'Failed path cleans only staged object and preserves order state');
    formCheck($send($order, $submission)['slip']['order_status'] === 'pending_review', 'Retry after confirmed cleanup succeeds with a new object');
    $baseline++;
}
$order = $create(); $submission = $body(); $fault('commit_after');
$receipt = $send($order, $submission);
formCheck($receipt['slip']['status'] === 'submitted' && count($state()['objects']) === ++$baseline, 'Lost commit response recovers committed receipt without deleting image');
formCheck($send($order, $submission)['replayed'] && count($state()['objects']) === $baseline, 'Committed submission retry creates no duplicate object/attempt');
$order = $create(); $submission = $body(); $fault('commit_before'); $fault('delete');
$expect(fn () => $send($order, $submission), 503, 'Delete failure leaves durable cleanup pending receipt');
formCheck(count($state()['objects']) === $baseline + 1, 'Uncommitted object retained when cleanup failed, not falsely accepted');
$fixture('expire-uploads', ['module_uuid' => $paymentsMod->uuid]);
$reconciled = $commerce->reconcileSlips($owner, $project, $paymentsMod);
formCheck($reconciled['cleaned'] === 1 && $reconciled['pending'] === 0 && count($state()['objects']) === $baseline, 'Owner reconciliation recovers expired orphan and preserves all committed evidence');
formCheck($send($order, $submission)['slip']['status'] === 'submitted', 'Original submission can retry after recovered orphan');
$fixture('bucket-privacy', ['module_uuid' => $paymentsMod->uuid, 'public' => true]);
$expect(fn () => $commerce->slipImage($owner, $project, $paymentsMod, $attemptUuid), 503, 'Fresh signing fails closed if bucket was made public');
$fixture('bucket-privacy', ['module_uuid' => $paymentsMod->uuid, 'public' => false]);
$fault('sign_url');
$expect(fn () => $commerce->slipImage($owner, $project, $paymentsMod, $attemptUuid), 503, 'Upstream signed URL cannot redirect owner to a foreign host');
$project->forceFill(['frozen_at' => now()])->save(); $allow('manual_payments', false);
formCheck(isset($commerce->slipImage($owner, $project, $paymentsMod, $attemptUuid)['url']), 'Owner image history survives downgrade and freeze');
$project->forceFill(['frozen_at' => null])->save(); $allow('manual_payments');
app('session')->forgetDrivers(); app()->forgetInstance('session.store'); app('redirect')->setSession(app('session')->driver()); Illuminate\Support\Facades\Auth::forgetGuards();
$as = function (?App\Models\User $actor) { app('session')->driver()->invalidate(); app('session')->driver()->regenerateToken(); Illuminate\Support\Facades\Auth::guard('web')->forgetUser(); if ($actor) Illuminate\Support\Facades\Auth::guard('web')->login($actor); app('cache')->store('array')->flush(); };
$ownerHttp = function (string $path, string $method = 'GET', mixed $body = null, bool $csrf = true) {
    $session=app('session')->driver(); $session->start(); $session->save(); $name=config('session.cookie'); $encrypter=app('encrypter');
    $cookie=$encrypter->encrypt(Illuminate\Cookie\CookieValuePrefix::create($name,$encrypter->getKey()).$session->getId(),false);
    $server=['HTTP_ACCEPT'=>'application/json','CONTENT_TYPE'=>'application/json','REMOTE_ADDR'=>'192.0.2.58'];
    if($csrf)$server['HTTP_X_CSRF_TOKEN']=$session->token();
    return app(Illuminate\Contracts\Http\Kernel::class)->handle(Illuminate\Http\Request::create($path,$method,[],[$name=>$cookie],[],$server,is_string($body)?$body:json_encode($body)));
};
$imagePath="/lab/{$project->uuid}/modules/{$paymentsMod->uuid}/payment-attempts/{$attemptUuid}/image";
$cleanupPath="/lab/{$project->uuid}/modules/{$paymentsMod->uuid}/slip-uploads/reconcile";
$as(null); formCheck($ownerHttp($imagePath)->getStatusCode()===401,'Actual owner image endpoint rejects anonymous callers');
$as($other); formCheck($ownerHttp($imagePath)->getStatusCode()===403,'Actual image route rejects another owner');
$as($owner); $imageResponse=$ownerHttp($imagePath);
formCheck($imageResponse->getStatusCode()===200 && str_contains($imageResponse->headers->get('Cache-Control'),'no-store'),'Actual owner image endpoint returns private no-store URL');
formCheck($ownerHttp($cleanupPath,'POST','{}',false)->getStatusCode()===419,'Reconciliation mutation requires CSRF');
formCheck($ownerHttp($cleanupPath,'POST',['object_path'=>'forged.png'])->getStatusCode()===422,'Client cannot choose cleanup object paths');
formCheck($ownerHttp($cleanupPath,'POST','{}')->getStatusCode()===200,'Owner can request bounded receipt reconciliation');
formResult('private slip Storage, signed viewing and actual HTTP failure recovery');
