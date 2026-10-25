<?php

// Run local Postman simulation 4900 and PostgreSQL adapter 4910 first. Fictional data only.
require __DIR__.'/managed-module-bootstrap.php';
(require __DIR__.'/../../database/migrations/2026_08_16_081000_create_lab_publications_table.php')->up();

use App\Lab\Modules\CommerceStore;
use App\Lab\Modules\ModuleInstallations;
use App\Lab\Modules\SchemaProposals;
use App\Models\UserDatastoreLink;
use Illuminate\Support\Str;

config(['services.supabase.api_url' => 'http://127.0.0.1:4900/supabase', 'app.url' => 'http://localhost:8892', 'app.debug' => false]);
Illuminate\Support\Facades\Http::allowStrayRequests(['http://127.0.0.1:4900/supabase/*']);
UserDatastoreLink::create([
    'user_id' => $owner->id,
    'driver' => 'supabase',
    'host_url' => 'http://127.0.0.1:4900/supabase/pglite',
    'publishable_token' => 'fictional-public',
    'steward_token' => 'fictional-private',
    'access_token' => 'fictional-management',
    'project_ref' => 'pglitefixture',
]);

$allow('catalog');
$allow('orders');
$allow('manual_payments');

$modules = app(ModuleInstallations::class);
$proposals = app(SchemaProposals::class);
$commerce = app(CommerceStore::class);

config(['lab-modules.business_writes_enabled' => true]);

$installAndEnable = function (string $key) use ($owner, $project, $modules, $proposals) {
    $rev = (int) $project->fresh()->modules_revision;
    $modules->select($owner, $project, $key, true, $rev);
    $rev = (int) $project->fresh()->modules_revision;
    $mod = $modules->prepare($owner, $project, $key, $rev, [], (string) Str::uuid())['installation'];
    $prop = $proposals->prepare($owner, $project, $mod, 1);
    $receipt = $proposals->apply($owner, $project, $prop, $prop->impact_hash, 1);
    formCheck($receipt['verified'] && $mod->fresh()->status === 'ready', "Installed and verified {$key} schema in PostgreSQL");
    $modules->setState($owner, $project, $mod->fresh(), 'enabled', 2);
    return $mod->fresh();
};

$catalogMod = $installAndEnable('catalog');
$ordersMod = $installAndEnable('orders');
$paymentsMod = $installAndEnable('manual_payments');

app()->instance('env', 'local');
app()->instance(Deep42\Hitchhiker\Contracts\InstallationStateManager::class, new class implements Deep42\Hitchhiker\Contracts\InstallationStateManager {
    public function isInstalled(): bool { return true; }
    public function markInstalled(): void { throw new RuntimeException('No installation mutation'); }
});

$publicHttp = function (string $path, string $method = 'GET', ?array $body = null, array $headers = [], ?string $origin = 'http://localhost:8892') {
    $server = ['HTTP_ACCEPT' => 'application/json', 'REMOTE_ADDR' => '192.0.2.55'];
    if ($origin !== null) $server['HTTP_ORIGIN'] = $origin;
    if ($body !== null) $server['CONTENT_TYPE'] = 'application/json';
    foreach ($headers as $k => $v) {
        $server['HTTP_'.strtoupper(str_replace('-', '_', $k))] = $v;
    }
    $content = $body !== null ? json_encode($body, JSON_UNESCAPED_UNICODE) : null;
    $request = Illuminate\Http\Request::create($path, $method, [], [], [], $server, $content);
    return app(Illuminate\Contracts\Http\Kernel::class)->handle($request);
};

// 1. Create products & update product
$p1Key = (string) Str::uuid();
$prod1 = $commerce->createProduct($owner, $project, $catalogMod, [
    'idempotency_key' => $p1Key,
    'sku' => 'BEAN-ARABICA',
    'title' => 'เมล็ดกาแฟคั่วกลาง Arabica 250g',
    'description' => 'หอมละมุนจากเชียงราย',
    'price_satang' => 25000,
    'active' => true,
]);
formCheck($prod1['price_satang'] === 25000 && !$prod1['replayed'], 'Owner creates product 1 at 250 THB');
$prod1Uuid = $prod1['product_uuid'];

$prod2 = $commerce->createProduct($owner, $project, $catalogMod, [
    'idempotency_key' => (string) Str::uuid(),
    'sku' => 'DRIP-SET-01',
    'title' => 'ชุดดริปกาแฟเซรามิก',
    'description' => 'พร้อมกระดาษกรอง 40 แผ่น',
    'price_satang' => 45000,
    'active' => true,
]);
formCheck($prod2['price_satang'] === 45000, 'Owner creates product 2 at 450 THB');
$prod2Uuid = $prod2['product_uuid'];

$updatedProd1 = $commerce->updateProduct($owner, $project, $catalogMod, $prod1Uuid, [
    'expected_version' => 1,
    'sku' => 'BEAN-ARABICA',
    'title' => 'เมล็ดกาแฟคั่วกลาง Arabica 250g (คัดพิเศษ)',
    'description' => 'หอมละมุนจากดอยช้าง',
    'price_satang' => 28000,
    'active' => true,
]);
formCheck($updatedProd1['version'] === 2 && $updatedProd1['price_satang'] === 28000, 'Owner updates product 1 price to 280 THB');

// 2. Configure PromptPay settings
$initSettings = $commerce->getPaymentSettings($owner, $project, $paymentsMod)['settings'];
formCheck($initSettings['version'] === 1 && $initSettings['enabled'] === false, 'Initial PromptPay settings loaded');

$savedSettings = $commerce->savePaymentSettings($owner, $project, $paymentsMod, [
    'expected_version' => 1,
    'recipient_type' => 'phone',
    'recipient' => '081-234-5678',
    'payee_label' => 'บจก. คริกคิต คอฟฟี่ โรสเตอร์',
    'instructions' => 'โอนแล้วแนบสลิปได้ทันที',
    'enabled' => true,
])['settings'];
formCheck($savedSettings['recipient'] === '0812345678' && $savedSettings['enabled'] === true && $savedSettings['version'] === 2, 'Owner configures and enables PromptPay recipient');

// 3. Public storefront fetch
$sfRes = $publicHttp("/api/lab-widgets/{$catalogMod->uuid}/storefront");
$sfData = json_decode($sfRes->getContent(), true);
formCheck(
    $sfRes->getStatusCode() === 200
    && count($sfData['products']) === 2
    && $sfData['checkout_ready'] === true
    && $sfData['payment']['masked_recipient'] === '081-XXX-5678',
    'Public storefront returns active products and masked PromptPay recipient'
);

// 4. Public create order (2x 280 THB + 1x 450 THB = 1010 THB = 101000 satang)
$orderRes = $publicHttp("/api/lab-widgets/{$catalogMod->uuid}/orders", 'POST', [
    'quote_uuid' => $commerce->publicQuote($catalogMod, ['items' => [['product_uuid' => $prod1Uuid, 'quantity' => 2], ['product_uuid' => $prod2Uuid, 'quantity' => 1]]])['quote']['quote_uuid'],
    'idempotency_key' => (string) Str::uuid(),
    'customer_name' => 'คุณลูกค้า ทดสอบ',
    'customer_email' => 'customer@example.test',
    'customer_phone' => '0899998888',
    'note' => 'จัดส่งด่วน',
    'items' => [
        ['product_uuid' => $prod1Uuid, 'quantity' => 2],
        ['product_uuid' => $prod2Uuid, 'quantity' => 1],
    ],
]);
$orderData = json_decode($orderRes->getContent(), true);
formCheck(
    $orderRes->getStatusCode() === 201
    && $orderData['order']['total_satang'] === 101000
    && $orderData['order']['status'] === 'awaiting_payment'
    && str_contains($orderData['order']['promptpay']['qr_svg'], '<svg')
    && str_contains($orderData['order']['promptpay']['qr_payload'], '1010.00'),
    'Public checkout creates order with server-calculated 1010.00 THB total and PromptPay QR SVG; HTTP '.$orderRes->getStatusCode().' '.($orderData['message'] ?? '')
);
$orderUuid = $orderData['order']['uuid'];
$accessToken = $orderData['access_token'];

// 5. Public submit payment slip -> status pending_review (never auto-paid)
$fixtureImage = imagecreatetruecolor(130,130);
for ($x=0; $x<130; $x++) for ($y=0; $y<130; $y++) imagesetpixel($fixtureImage,$x,$y,random_int(0,0xffffff));
ob_start(); imagepng($fixtureImage); $fixturePng = ob_get_clean(); imagedestroy($fixtureImage);
$png1x1 = 'data:image/png;base64,'.base64_encode($fixturePng);
formCheck(strlen($png1x1) > 20*1024, 'Real slip fixture exceeds former middleware limit');
$slipRes = $publicHttp("/api/lab-widgets/{$catalogMod->uuid}/orders/{$orderUuid}/slips", 'POST', [
    'idempotency_key' => (string) Str::uuid(),
    'payer_name' => 'คุณลูกค้า ทดสอบ',
    'payer_phone' => '0899998888',
    'amount_satang' => 101000,
    'transfer_ref' => 'SLIP-20261017-001',
    'claimed_transferred_at' => '2026-10-17 16:55',
    'slip_data_url' => $png1x1,
], ['X-Krikkit-Order-Token' => $accessToken]);
$slipData = json_decode($slipRes->getContent(), true);
formCheck(
    $slipRes->getStatusCode() === 201
    && $slipData['slip']['status'] === 'submitted'
    && $slipData['slip']['order_status'] === 'pending_review',
    'Uploaded slip marks attempt submitted and order pending_review (never auto-paid); HTTP '.$slipRes->getStatusCode().' '.($slipData['message'] ?? '')
);
$attemptUuid = $slipData['slip']['uuid'];
$expect(fn () => $commerce->publicSubmitSlip($catalogMod, $orderUuid, $accessToken, ['idempotency_key' => (string) Str::uuid(), 'payer_name' => 'Fake image', 'amount_satang' => 101000, 'slip_data_url' => 'data:image/png;base64,'.base64_encode('<html>not a decoded payment image</html>')]), 422, 'Disguised HTML is not accepted as a payment slip');

// 6. Cross-tenant isolation check
$expect(fn () => $commerce->listOrders($other, $foreign, $ordersMod), 404, 'Other account cannot read merchant orders');

// 7. Owner reviews and approves payment slip
$ownerOrders = $commerce->listOrders($owner, $project, $ordersMod)['items'];
formCheck(count($ownerOrders) === 1 && count($ownerOrders[0]['payment_attempts']) === 1, 'Owner lists order with attached payment slip in back-office');

$expect(fn () => $commerce->reviewPaymentSlip($owner, $project, $paymentsMod, $attemptUuid, ['expected_version' => 1, 'decision' => 'accepted', 'reason' => 'UI alone is insufficient']), 422, 'Bank check acknowledgement enforced on server');
$review = $commerce->reviewPaymentSlip($owner, $project, $paymentsMod, $attemptUuid, [
    'expected_version' => 1,
    'decision' => 'accepted',
    'bank_verified' => true,
    'reason' => 'ยอดเงินเข้าบัญชีเรียบร้อย',
])['review'];
formCheck($review['attempt_status'] === 'accepted' && $review['order_status'] === 'paid', 'Owner approval transitions slip to accepted and order to paid');

// 8. Customer checks order status with guest token and sees paid
$statusRes = $publicHttp("/api/lab-widgets/{$catalogMod->uuid}/orders/{$orderUuid}", 'GET', null, ['X-Krikkit-Order-Token' => $accessToken]);
$statusData = json_decode($statusRes->getContent(), true);
formCheck(
    $statusRes->getStatusCode() === 200
    && $statusData['order']['status'] === 'paid'
    && $statusData['order']['latest_slip']['status'] === 'accepted',
    'Customer order status reflects paid after owner approval'
);

// Published-site browser preflight must permit the private order header.
$preflight = $publicHttp("/api/lab-widgets/{$catalogMod->uuid}/orders/{$orderUuid}", 'OPTIONS', null, ['Access-Control-Request-Method' => 'GET', 'Access-Control-Request-Headers' => 'x-krikkit-order-token']);
formCheck($preflight->getStatusCode() === 204 && str_contains(strtolower($preflight->headers->get('Access-Control-Allow-Headers', '')), 'x-krikkit-order-token'), 'Order capability header survives cross-origin preflight');
formCheck($publicHttp("/api/lab-widgets/{$catalogMod->uuid}/orders/{$orderUuid}?token=".$accessToken)->getStatusCode() === 404, 'Order token in URL cannot authorize access');

$allow('orders', false);
formCheck(!$commerce->publicStorefront($catalogMod)['checkout_ready'], 'Storefront hides checkout when effective orders capability is denied');
$expect(fn () => $commerce->publicQuote($catalogMod, ['items' => [['product_uuid' => $prod1Uuid, 'quantity' => 1]]]), 402, 'Downgraded orders feature blocks checkout even when catalog remains enabled');
$allow('orders');

// Lost-response replay must use the immutable saved amount/recipient and survive disable.
$createBody = ['idempotency_key' => (string) Str::uuid(), 'customer_name' => 'Replay fixture', 'customer_email' => 'replay@example.invalid', 'items' => [['product_uuid' => $prod2Uuid, 'quantity' => 1]]];
$createBody['quote_uuid'] = $commerce->publicQuote($catalogMod, ['items' => $createBody['items']])['quote']['quote_uuid'];
$original = $commerce->publicCreateOrder($catalogMod, $createBody);
$commerce->updateProduct($owner, $project, $catalogMod, $prod2Uuid, ['expected_version' => 1, 'sku' => 'DRIP-SET-01', 'title' => 'Changed product', 'description' => '', 'price_satang' => 50000, 'active' => true]);
$retry = $commerce->publicCreateOrder($catalogMod, $createBody);
formCheck($retry['replayed'] && $retry['order']['total_satang'] === $original['order']['total_satang'] && $retry['order']['items'] === $original['order']['items'] && $retry['order']['promptpay'] === $original['order']['promptpay'], 'Order replay returns exact immutable snapshots after price change');
$modules->setState($owner, $project, $catalogMod, 'disabled', $catalogMod->revision);
formCheck($commerce->publicGetOrder($catalogMod->fresh(), $orderUuid, $accessToken)['order']['status'] === 'paid', 'Authorized customer history survives module disable');
formCheck($commerce->publicCreateOrder($catalogMod->fresh(), $createBody)['replayed'], 'Accepted creation retry survives module disable');
$modules->setState($owner, $project, $catalogMod->fresh(), 'enabled', $catalogMod->fresh()->revision);
Illuminate\Support\Carbon::setTestNow(now()->addHours(25));
$expect(fn () => $commerce->publicGetOrder($catalogMod->fresh(), $orderUuid, $accessToken), 404, 'Guest capability expires after twenty-four hours');
Illuminate\Support\Carbon::setTestNow();
$rateOrder = (string) Str::uuid();
$rateStatuses = [];
for ($try = 0; $try < 6; $try++) {
    $rateResponse = $publicHttp("/api/lab-widgets/{$catalogMod->uuid}/orders/{$rateOrder}/slips", 'POST', ['idempotency_key' => (string) Str::uuid()]);
    $rateStatuses[] = $rateResponse->getStatusCode();
}
formCheck($rateStatuses === [404,404,404,404,404,429] && $rateResponse->headers->has('Retry-After') && $rateResponse->headers->has('Access-Control-Allow-Origin'), 'Actual slip route enforces five-per-minute limit and retains CORS on429: '.implode(',', $rateStatuses));
formResult('actual commerce catalog, PromptPay QR checkout, slip upload, and owner review over Postman HTTP and local PostgreSQL');
