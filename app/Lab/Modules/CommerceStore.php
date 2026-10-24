<?php

namespace App\Lab\Modules;

use App\Models\LabModuleInstallation;
use App\Models\LabProject;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

final class CommerceStore
{
    public function __construct(private readonly CapabilityPolicy $policy, private readonly ModuleDatastore $store) {}

    public function listProducts(User $actor, LabProject $project, LabModuleInstallation $module): array
    {
        $this->assertModule($actor, $project, $module, 'catalog', 'history');
        $rows = $this->store->request($module, 'GET', 'krikkit_catalog_products', [
            'project_uuid' => 'eq.'.$project->uuid,
            'module_uuid' => 'eq.'.$module->uuid,
            'select' => 'uuid,sku,title,description,price_satang,active,version,created_at,updated_at',
            'order' => 'created_at.desc,uuid.desc',
            'limit' => 100,
        ]);
        return ['items' => $rows, 'limit' => 100];
    }

    public function createProduct(User $actor, LabProject $project, LabModuleInstallation $module, array $body): array
    {
        abort_unless(array_diff(array_keys($body), ['idempotency_key','sku','title','description','price_satang','active']) === [], 422, 'Unexpected product fields.');
        abort_unless(isset($body['price_satang']) && is_int($body['price_satang']) && $body['price_satang'] >= 100 && $body['price_satang'] <= 100_000_000, 422, 'Price in satang must be an integer between 100 and 100000000.');
        abort_unless(!isset($body['active']) || is_bool($body['active']), 422, 'Active flag must be a boolean.');
        $data = Validator::make($body, [
            'idempotency_key' => ['required','uuid'],
            'sku' => ['required','string','min:1','max:64','regex:/^[A-Za-z0-9][A-Za-z0-9_-]{0,63}$/D'],
            'title' => ['required','string','max:160'],
            'description' => ['sometimes','string','max:2000'],
        ])->validate();
        $values = [
            'sku' => $data['sku'],
            'title' => trim($data['title']),
            'description' => trim($data['description'] ?? ''),
            'price_satang' => $body['price_satang'],
            'active' => $body['active'] ?? true,
        ];
        abort_unless($values['title'] !== '', 422, 'Product title is required.');
        $hash = hash('sha256', json_encode($values, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));

        $this->assertModule($actor, $project, $module, 'catalog', 'mutate');
        $rows = $this->store->request($module, 'POST', 'rpc/krikkit_create_product', [
            'p_project_uuid' => $project->uuid,
            'p_module_uuid' => $module->uuid,
            'p_key' => $data['idempotency_key'],
            'p_hash' => $hash,
            'p_values' => $values,
            'p_actor_id' => (int) $actor->id,
        ]);
        abort_unless(isset($rows[0]['product_uuid'], $rows[0]['version'], $rows[0]['replayed']) && Str::isUuid($rows[0]['product_uuid']), 503, 'Unexpected product creation response.');
        return $rows[0];
    }

    public function updateProduct(User $actor, LabProject $project, LabModuleInstallation $module, string $productUuid, array $body): array
    {
        abort_unless(Str::isUuid($productUuid), 422, 'Invalid product identifier.');
        abort_unless(array_diff(array_keys($body), ['expected_version','sku','title','description','price_satang','active']) === [], 422, 'Unexpected product update fields.');
        abort_unless(isset($body['expected_version']) && is_int($body['expected_version']) && $body['expected_version'] >= 1, 422, 'Invalid expected version.');
        abort_unless(isset($body['price_satang']) && is_int($body['price_satang']) && $body['price_satang'] >= 100 && $body['price_satang'] <= 100_000_000, 422, 'Price in satang must be an integer between 100 and 100000000.');
        abort_unless(isset($body['active']) && is_bool($body['active']), 422, 'Active flag must be a boolean.');
        $data = Validator::make($body, [
            'sku' => ['required','string','min:1','max:64','regex:/^[A-Za-z0-9][A-Za-z0-9_-]{0,63}$/D'],
            'title' => ['required','string','max:160'],
            'description' => ['sometimes','string','max:2000'],
        ])->validate();
        $values = [
            'sku' => $data['sku'],
            'title' => trim($data['title']),
            'description' => trim($data['description'] ?? ''),
            'price_satang' => $body['price_satang'],
            'active' => $body['active'],
        ];
        abort_unless($values['title'] !== '', 422, 'Product title is required.');

        $this->assertModule($actor, $project, $module, 'catalog', 'mutate');
        $rows = $this->store->request($module, 'POST', 'rpc/krikkit_update_product', [
            'p_project_uuid' => $project->uuid,
            'p_module_uuid' => $module->uuid,
            'p_product_uuid' => $productUuid,
            'p_expected_version' => $body['expected_version'],
            'p_values' => $values,
            'p_actor_id' => (int) $actor->id,
        ]);
        abort_unless(isset($rows[0]['product_uuid'], $rows[0]['version']) && Str::isUuid($rows[0]['product_uuid']), 503, 'Unexpected product update response.');
        return $rows[0];
    }

    public function getPaymentSettings(User $actor, LabProject $project, LabModuleInstallation $module): array
    {
        $this->assertModule($actor, $project, $module, 'manual_payments', 'history');
        $rows = $this->store->request($module, 'GET', 'krikkit_payment_settings', [
            'project_uuid' => 'eq.'.$project->uuid,
            'module_uuid' => 'eq.'.$module->uuid,
            'select' => 'module_uuid,recipient_type,recipient,payee_label,instructions,enabled,version,updated_at',
            'limit' => 1,
        ]);
        abort_unless(isset($rows[0]), 404, 'Payment settings not initialized.');
        return ['settings' => $rows[0]];
    }

    public function savePaymentSettings(User $actor, LabProject $project, LabModuleInstallation $module, array $body): array
    {
        abort_unless(array_diff(array_keys($body), ['expected_version','recipient_type','recipient','payee_label','instructions','enabled']) === [], 422, 'Unexpected payment settings fields.');
        abort_unless(isset($body['expected_version']) && is_int($body['expected_version']) && $body['expected_version'] >= 1, 422, 'Invalid expected version.');
        abort_unless(isset($body['enabled']) && is_bool($body['enabled']), 422, 'Enabled flag must be a boolean.');
        $data = Validator::make($body, [
            'recipient_type' => ['required','in:phone,national_id,ewallet'],
            'recipient' => ['required','string','max:30'],
            'payee_label' => ['required','string','max:160'],
            'instructions' => ['sometimes','string','max:1000'],
        ])->validate();
        $sanitizedRecipient = PromptPayQr::sanitizeRecipient($data['recipient_type'], $data['recipient']);
        $payeeLabel = trim($data['payee_label']);
        abort_unless($payeeLabel !== '', 422, 'Payee display name is required.');
        $values = [
            'recipient_type' => $data['recipient_type'],
            'recipient' => $sanitizedRecipient,
            'payee_label' => $payeeLabel,
            'instructions' => trim($data['instructions'] ?? ''),
            'enabled' => $body['enabled'],
        ];

        $this->assertModule($actor, $project, $module, 'manual_payments', 'mutate');
        $rows = $this->store->request($module, 'POST', 'rpc/krikkit_save_payment_settings', [
            'p_project_uuid' => $project->uuid,
            'p_module_uuid' => $module->uuid,
            'p_expected_version' => $body['expected_version'],
            'p_values' => $values,
            'p_actor_id' => (int) $actor->id,
        ]);
        abort_unless(isset($rows[0]['module_uuid'], $rows[0]['version']), 503, 'Unexpected payment settings update response.');
        return ['settings' => $rows[0]];
    }

    public function listOrders(User $actor, LabProject $project, LabModuleInstallation $module, ?string $status = null): array
    {
        $this->assertModule($actor, $project, $module, 'orders', 'history');
        $query = [
            'project_uuid' => 'eq.'.$project->uuid,
            'module_uuid' => 'eq.'.$module->uuid,
            'select' => 'uuid,reference,customer_name,customer_email,customer_phone,note,items,total_satang,currency,promptpay_snapshot,status,version,created_at,updated_at',
            'order' => 'created_at.desc,uuid.desc',
            'limit' => 100,
        ];
        if ($status !== null) {
            abort_unless(in_array($status, ['awaiting_payment','pending_review','paid','rejected','cancelled'], true), 422);
            $query['status'] = 'eq.'.$status;
        }
        $orders = $this->store->request($module, 'GET', 'krikkit_commerce_orders', $query);

        $paymentModule = LabModuleInstallation::query()
            ->where('lab_project_id', $project->id)
            ->where('key', 'manual_payments')
            ->where('status', 'ready')
            ->first();

        $attemptsByOrder = [];
        if ($paymentModule !== null) {
            $attempts = $this->store->request($paymentModule, 'GET', 'krikkit_payment_attempts', [
                'project_uuid' => 'eq.'.$project->uuid,
                'module_uuid' => 'eq.'.$paymentModule->uuid,
                'select' => 'uuid,order_uuid,payer_name,payer_phone,amount_satang,transfer_ref,claimed_transferred_at,slip_mime,slip_sha256,slip_data_url,status,reviewer_id,review_reason,verified_at,version,uploaded_at,updated_at',
                'order' => 'uploaded_at.desc,uuid.desc',
                'limit' => 100,
            ]);
            foreach ($attempts as $att) {
                $attemptsByOrder[$att['order_uuid']][] = $att;
            }
        }

        foreach ($orders as &$ord) {
            $ord['payment_attempts'] = $attemptsByOrder[$ord['uuid']] ?? [];
        }
        unset($ord);

        return ['items' => $orders, 'limit' => 100];
    }

    public function reviewPaymentSlip(User $actor, LabProject $project, LabModuleInstallation $module, string $attemptUuid, array $body): array
    {
        abort_unless(Str::isUuid($attemptUuid), 422, 'Invalid payment attempt identifier.');
        abort_unless(array_diff(array_keys($body), ['expected_version','decision','reason']) === [], 422, 'Unexpected payment review fields.');
        abort_unless(isset($body['expected_version']) && is_int($body['expected_version']) && $body['expected_version'] >= 1, 422, 'Invalid expected version.');
        $data = Validator::make($body, [
            'decision' => ['required','in:accepted,rejected'],
            'reason' => ['sometimes','string','max:500'],
        ])->validate();
        $reason = trim($data['reason'] ?? '');
        if ($data['decision'] === 'rejected') {
            abort_unless($reason !== '', 422, 'Please provide a reason when rejecting a payment slip.');
        }

        $this->assertModule($actor, $project, $module, 'manual_payments', 'mutate');
        $rows = $this->store->request($module, 'POST', 'rpc/krikkit_review_payment_slip', [
            'p_project_uuid' => $project->uuid,
            'p_module_uuid' => $module->uuid,
            'p_attempt_uuid' => $attemptUuid,
            'p_expected_version' => $body['expected_version'],
            'p_decision' => $data['decision'],
            'p_reason' => $reason,
            'p_actor_id' => (int) $actor->id,
        ]);
        abort_unless(isset($rows[0]['attempt_uuid'], $rows[0]['order_status'], $rows[0]['version']), 503, 'Unexpected payment review response.');
        return ['review' => $rows[0]];
    }

    public function publicStorefront(LabModuleInstallation $module): array
    {
        return DB::transaction(function () use ($module) {
            $bundle = $this->resolvePublicBundle($module);
            $catalogModule = $bundle['catalog'];
            $project = $bundle['project'];

            $products = $this->store->request($catalogModule, 'GET', 'krikkit_catalog_products', [
                'project_uuid' => 'eq.'.$project->uuid,
                'module_uuid' => 'eq.'.$catalogModule->uuid,
                'active' => 'eq.true',
                'select' => 'uuid,sku,title,description,price_satang',
                'order' => 'created_at.desc,uuid.desc',
                'limit' => 100,
            ]);

            $checkoutReady = false;
            $paymentInfo = null;
            if ($bundle['orders'] !== null && $bundle['manual_payments'] !== null) {
                $settingsRows = $this->store->request($bundle['manual_payments'], 'GET', 'krikkit_payment_settings', [
                    'project_uuid' => 'eq.'.$project->uuid,
                    'module_uuid' => 'eq.'.$bundle['manual_payments']->uuid,
                    'select' => 'recipient_type,recipient,payee_label,instructions,enabled',
                    'limit' => 1,
                ]);
                if (isset($settingsRows[0]) && ($settingsRows[0]['enabled'] ?? false) === true && ($settingsRows[0]['recipient'] ?? '') !== '') {
                    $checkoutReady = true;
                    $paymentInfo = [
                        'method' => 'promptpay_manual',
                        'payee_label' => $settingsRows[0]['payee_label'],
                        'masked_recipient' => PromptPayQr::maskRecipient($settingsRows[0]['recipient_type'], $settingsRows[0]['recipient']),
                        'instructions' => $settingsRows[0]['instructions'],
                    ];
                }
            }

            return [
                'products' => $products,
                'currency' => 'THB',
                'checkout_ready' => $checkoutReady,
                'payment' => $paymentInfo,
            ];
        });
    }

    public function publicCreateOrder(LabModuleInstallation $module, array $body): array
    {
        abort_unless(array_diff(array_keys($body), ['idempotency_key','customer_name','customer_email','customer_phone','note','items']) === [], 422, 'Unexpected order fields.');
        $data = Validator::make($body, [
            'idempotency_key' => ['required','uuid'],
            'customer_name' => ['required','string','max:120'],
            'customer_email' => ['required','email:rfc','max:160'],
            'customer_phone' => ['sometimes','string','max:40'],
            'note' => ['sometimes','string','max:1000'],
            'items' => ['required','array','min:1','max:25'],
        ])->validate();

        return DB::transaction(function () use ($module, $data) {
            $bundle = $this->resolvePublicBundle($module);
            abort_unless($bundle['orders'] !== null && $bundle['manual_payments'] !== null, 409, 'Checkout and PromptPay payment modules are not enabled for this store.');
            $project = $bundle['project'];
            $catalogModule = $bundle['catalog'];
            $ordersModule = $bundle['orders'];
            $paymentsModule = $bundle['manual_payments'];

            $settingsRows = $this->store->request($paymentsModule, 'GET', 'krikkit_payment_settings', [
                'project_uuid' => 'eq.'.$project->uuid,
                'module_uuid' => 'eq.'.$paymentsModule->uuid,
                'select' => 'recipient_type,recipient,payee_label,instructions,enabled',
                'limit' => 1,
            ]);
            abort_unless(isset($settingsRows[0]) && ($settingsRows[0]['enabled'] ?? false) === true && ($settingsRows[0]['recipient'] ?? '') !== '', 409, 'PromptPay payment is not configured by the store owner yet.');
            $settings = $settingsRows[0];

            $products = $this->store->request($catalogModule, 'GET', 'krikkit_catalog_products', [
                'project_uuid' => 'eq.'.$project->uuid,
                'module_uuid' => 'eq.'.$catalogModule->uuid,
                'active' => 'eq.true',
                'select' => 'uuid,sku,title,price_satang',
                'limit' => 100,
            ]);
            $productMap = [];
            foreach ($products as $prod) {
                $productMap[$prod['uuid']] = $prod;
            }

            $normalizedRequestItems = [];
            $snapshotItems = [];
            $totalSatang = 0;

            foreach ($data['items'] as $rawItem) {
                if ($rawItem instanceof \stdClass) {
                    $rawItem = (array) $rawItem;
                }
                abort_unless(is_array($rawItem) && isset($rawItem['product_uuid'], $rawItem['quantity'])
                    && is_string($rawItem['product_uuid']) && Str::isUuid($rawItem['product_uuid'])
                    && is_int($rawItem['quantity']) && $rawItem['quantity'] >= 1 && $rawItem['quantity'] <= 99, 422, 'Invalid cart line item.');
                $prod = $productMap[$rawItem['product_uuid']] ?? null;
                abort_unless($prod !== null, 422, 'One or more products in your cart are no longer available.');
                $unitSatang = (int) $prod['price_satang'];
                $lineSatang = $unitSatang * $rawItem['quantity'];
                $totalSatang += $lineSatang;
                $normalizedRequestItems[] = ['product_uuid' => $prod['uuid'], 'quantity' => $rawItem['quantity']];
                $snapshotItems[] = [
                    'product_uuid' => $prod['uuid'],
                    'sku' => $prod['sku'],
                    'title' => $prod['title'],
                    'unit_price_satang' => $unitSatang,
                    'quantity' => $rawItem['quantity'],
                    'line_total_satang' => $lineSatang,
                ];
            }
            abort_unless($totalSatang >= 100 && $totalSatang <= 100_000_000, 422, 'Order total is out of range.');

            $customerName = trim($data['customer_name']);
            $customerEmail = strtolower(trim($data['customer_email']));
            $customerPhone = trim($data['customer_phone'] ?? '');
            $note = trim($data['note'] ?? '');
            abort_unless($customerName !== '', 422, 'Customer name is required.');

            $requestHash = hash('sha256', json_encode([
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'customer_phone' => $customerPhone,
                'note' => $note,
                'items' => $normalizedRequestItems,
            ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));

            // Deterministic order access token from idempotency_key + app.key so replays return the exact same guest token
            $accessToken = hash_hmac('sha256', 'krikkit-order-token:'.$ordersModule->uuid.':'.$data['idempotency_key'], (string) config('app.key'));
            $accessTokenHash = hash('sha256', $accessToken);
            $reference = 'KRK-'.now()->format('Ymd').'-'.strtoupper(substr(hash('sha256', $ordersModule->uuid.':'.$data['idempotency_key']), 0, 6));

            $qrPayload = PromptPayQr::payload($settings['recipient_type'], $settings['recipient'], $totalSatang);
            $qrSvg = PromptPayQr::svg($qrPayload);

            $promptpaySnapshot = [
                'payee_label' => $settings['payee_label'],
                'masked_recipient' => PromptPayQr::maskRecipient($settings['recipient_type'], $settings['recipient']),
                'instructions' => $settings['instructions'],
                'qr_payload' => $qrPayload,
                'qr_svg' => $qrSvg,
            ];

            $values = [
                'reference' => $reference,
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'customer_phone' => $customerPhone,
                'note' => $note,
                'items' => $snapshotItems,
                'total_satang' => $totalSatang,
                'promptpay_snapshot' => $promptpaySnapshot,
                'access_token_hash' => $accessTokenHash,
            ];

            $rows = $this->store->request($ordersModule, 'POST', 'rpc/krikkit_create_order', [
                'p_project_uuid' => $project->uuid,
                'p_module_uuid' => $ordersModule->uuid,
                'p_key' => $data['idempotency_key'],
                'p_hash' => $requestHash,
                'p_values' => $values,
            ]);
            abort_unless(isset($rows[0]['order_uuid'], $rows[0]['reference'], $rows[0]['status'], $rows[0]['replayed']), 503, 'Unexpected order creation response.');

            return [
                'order' => [
                    'uuid' => $rows[0]['order_uuid'],
                    'reference' => $rows[0]['reference'],
                    'total_satang' => (int) $rows[0]['total_satang'],
                    'currency' => 'THB',
                    'status' => $rows[0]['status'],
                    'items' => $snapshotItems,
                    'promptpay' => $promptpaySnapshot,
                ],
                'access_token' => $accessToken,
                'replayed' => (bool) $rows[0]['replayed'],
            ];
        });
    }

    public function publicGetOrder(LabModuleInstallation $module, string $orderUuid, string $accessToken): array
    {
        abort_unless(Str::isUuid($orderUuid) && $accessToken !== '', 404);
        return DB::transaction(function () use ($module, $orderUuid, $accessToken) {
            $bundle = $this->resolvePublicBundle($module);
            abort_unless($bundle['orders'] !== null, 404);
            $project = $bundle['project'];
            $ordersModule = $bundle['orders'];

            $rows = $this->store->request($ordersModule, 'GET', 'krikkit_commerce_orders', [
                'project_uuid' => 'eq.'.$project->uuid,
                'module_uuid' => 'eq.'.$ordersModule->uuid,
                'uuid' => 'eq.'.$orderUuid,
                'select' => 'uuid,reference,customer_name,items,total_satang,currency,promptpay_snapshot,access_token_hash,status,version,created_at,updated_at',
                'limit' => 1,
            ]);
            abort_unless(isset($rows[0]), 404, 'Order not found.');
            $order = $rows[0];
            abort_unless(hash_equals((string) $order['access_token_hash'], hash('sha256', $accessToken)), 403, 'Invalid order access token.');

            $latestSlip = null;
            if ($bundle['manual_payments'] !== null) {
                $attempts = $this->store->request($bundle['manual_payments'], 'GET', 'krikkit_payment_attempts', [
                    'project_uuid' => 'eq.'.$project->uuid,
                    'module_uuid' => 'eq.'.$bundle['manual_payments']->uuid,
                    'order_uuid' => 'eq.'.$orderUuid,
                    'select' => 'uuid,payer_name,amount_satang,transfer_ref,claimed_transferred_at,status,review_reason,verified_at,uploaded_at',
                    'order' => 'uploaded_at.desc,uuid.desc',
                    'limit' => 1,
                ]);
                $latestSlip = $attempts[0] ?? null;
            }

            return [
                'order' => [
                    'uuid' => $order['uuid'],
                    'reference' => $order['reference'],
                    'customer_name' => $order['customer_name'],
                    'items' => is_string($order['items']) ? json_decode($order['items'], true) : $order['items'],
                    'total_satang' => (int) $order['total_satang'],
                    'currency' => $order['currency'],
                    'status' => $order['status'],
                    'promptpay' => is_string($order['promptpay_snapshot']) ? json_decode($order['promptpay_snapshot'], true) : $order['promptpay_snapshot'],
                    'latest_slip' => $latestSlip,
                    'created_at' => $order['created_at'],
                    'updated_at' => $order['updated_at'],
                ],
            ];
        });
    }

    public function publicSubmitSlip(LabModuleInstallation $module, string $orderUuid, string $accessToken, array $body): array
    {
        abort_unless(Str::isUuid($orderUuid) && $accessToken !== '', 404);
        abort_unless(array_diff(array_keys($body), ['idempotency_key','payer_name','payer_phone','amount_satang','transfer_ref','claimed_transferred_at','slip_data_url']) === [], 422, 'Unexpected payment slip fields.');
        abort_unless(isset($body['amount_satang']) && is_int($body['amount_satang']) && $body['amount_satang'] >= 100 && $body['amount_satang'] <= 100_000_000, 422, 'Invalid transfer amount.');
        $data = Validator::make($body, [
            'idempotency_key' => ['required','uuid'],
            'payer_name' => ['required','string','max:120'],
            'payer_phone' => ['sometimes','string','max:40'],
            'transfer_ref' => ['sometimes','string','max:120'],
            'claimed_transferred_at' => ['sometimes','string','max:64'],
            'slip_data_url' => ['required','string','min:32','max:2500000'],
        ])->validate();

        abort_unless(preg_match('/^data:(image\/(?:png|jpeg|webp));base64,([A-Za-z0-9+\/=]+)$/D', $data['slip_data_url'], $matches) === 1, 422, 'Please upload a valid PNG, JPEG, or WebP payment slip image.');
        $mime = $matches[1];
        $rawBytes = base64_decode($matches[2], true);
        abort_unless(is_string($rawBytes) && strlen($rawBytes) >= 16 && strlen($rawBytes) <= 1_800_000, 422, 'Slip image size must be under 1.8 MB.');
        $slipSha256 = hash('sha256', $rawBytes);

        return DB::transaction(function () use ($module, $orderUuid, $accessToken, $data, $body, $mime, $slipSha256) {
            $bundle = $this->resolvePublicBundle($module);
            abort_unless($bundle['orders'] !== null && $bundle['manual_payments'] !== null, 409, 'Manual payment review is not enabled.');
            $project = $bundle['project'];
            $ordersModule = $bundle['orders'];
            $paymentsModule = $bundle['manual_payments'];

            $orderRows = $this->store->request($ordersModule, 'GET', 'krikkit_commerce_orders', [
                'project_uuid' => 'eq.'.$project->uuid,
                'module_uuid' => 'eq.'.$ordersModule->uuid,
                'uuid' => 'eq.'.$orderUuid,
                'select' => 'uuid,total_satang,access_token_hash,status',
                'limit' => 1,
            ]);
            abort_unless(isset($orderRows[0]), 404, 'Order not found.');
            $order = $orderRows[0];
            abort_unless(hash_equals((string) $order['access_token_hash'], hash('sha256', $accessToken)), 403, 'Invalid order access token.');

            $payerName = trim($data['payer_name']);
            abort_unless($payerName !== '', 422, 'Payer name is required.');

            $values = [
                'payer_name' => $payerName,
                'payer_phone' => trim($data['payer_phone'] ?? ''),
                'amount_satang' => $body['amount_satang'],
                'transfer_ref' => trim($data['transfer_ref'] ?? ''),
                'claimed_transferred_at' => trim($data['claimed_transferred_at'] ?? ''),
                'slip_mime' => $mime,
                'slip_sha256' => $slipSha256,
                'slip_data_url' => $data['slip_data_url'],
            ];
            $hash = hash('sha256', json_encode([
                'order_uuid' => $orderUuid,
                'payer_name' => $values['payer_name'],
                'amount_satang' => $values['amount_satang'],
                'slip_sha256' => $slipSha256,
            ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));

            $rows = $this->store->request($paymentsModule, 'POST', 'rpc/krikkit_submit_payment_slip', [
                'p_project_uuid' => $project->uuid,
                'p_module_uuid' => $paymentsModule->uuid,
                'p_order_uuid' => $orderUuid,
                'p_key' => $data['idempotency_key'],
                'p_hash' => $hash,
                'p_values' => $values,
            ]);
            abort_unless(isset($rows[0]['attempt_uuid'], $rows[0]['status'], $rows[0]['order_status'], $rows[0]['replayed']), 503, 'Unexpected slip submission response.');
            return [
                'slip' => [
                    'uuid' => $rows[0]['attempt_uuid'],
                    'order_uuid' => $rows[0]['order_uuid'],
                    'status' => $rows[0]['status'],
                    'order_status' => $rows[0]['order_status'],
                ],
                'replayed' => (bool) $rows[0]['replayed'],
            ];
        });
    }

    private function resolvePublicBundle(LabModuleInstallation $entryModule): array
    {
        abort_unless(in_array($entryModule->key, ['catalog', 'orders', 'manual_payments'], true), 404);
        $project = LabProject::query()->whereKey($entryModule->lab_project_id)->lockForUpdate()->firstOrFail();
        $installations = LabModuleInstallation::query()
            ->where('lab_project_id', $project->id)
            ->lockForUpdate()
            ->get()
            ->keyBy('key');

        $catalog = $installations['catalog'] ?? null;
        abort_unless($catalog !== null && $catalog->status === 'ready' && $catalog->schema_version === 1 && $catalog->enabled, 409, 'Store catalog is not active.');
        $this->policy->assertAllowed($catalog->owner, $project, 'mutate', $catalog);

        $orders = $installations['orders'] ?? null;
        if ($orders !== null && ($orders->status !== 'ready' || !$orders->enabled || $orders->schema_version !== 1)) {
            $orders = null;
        }
        $manualPayments = $installations['manual_payments'] ?? null;
        if ($manualPayments !== null && ($manualPayments->status !== 'ready' || !$manualPayments->enabled || $manualPayments->schema_version !== 1)) {
            $manualPayments = null;
        }

        return [
            'project' => $project,
            'catalog' => $catalog,
            'orders' => $orders,
            'manual_payments' => $manualPayments,
        ];
    }

    private function assertModule(User $actor, LabProject $project, LabModuleInstallation $module, string $expectedKey, string $operation): void
    {
        $this->policy->assertAllowed($actor, $project, $operation, $module);
        abort_unless($module->key === $expectedKey && $module->schema_version === 1 && $module->status === 'ready', 409, 'Module storage is not ready.');
    }
}
