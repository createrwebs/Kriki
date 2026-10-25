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
    public function __construct(private readonly CapabilityPolicy $policy, private readonly ModuleDatastore $store, private readonly PrivateSlipStorage $images) {}

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

    public function listOrders(User $actor, LabProject $project, LabModuleInstallation $module, ?string $status = null, ?string $cursor = null): array
    {
        $this->assertModule($actor, $project, $module, 'orders', 'history');
        $query = [
            'project_uuid' => 'eq.'.$project->uuid,
            'module_uuid' => 'eq.'.$module->uuid,
            'select' => 'uuid,reference,customer_name,customer_email,customer_phone,note,items,total_satang,currency,promptpay_snapshot,status,access_revoked_at,version,created_at,updated_at',
            'order' => 'created_at.desc,uuid.desc',
            'limit' => 26,
        ];
        if ($status !== null) {
            abort_unless(in_array($status, ['awaiting_payment','pending_review','paid','rejected','cancelled'], true), 422);
            $query['status'] = 'eq.'.$status;
        }
        $page = $this->historyPage($this->store->request($module, 'GET', 'krikkit_commerce_orders', $this->historyCursor($query, $cursor, 'created_at')), 'created_at');
        $orders = $page['items'];

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
                'select' => 'uuid,order_uuid,payer_name,payer_phone,amount_satang,transfer_ref,claimed_transferred_at,slip_mime,slip_sha256,slip_bytes,slip_width,slip_height,slip_bucket,status,reviewer_id,review_reason,verified_at,version,uploaded_at,updated_at',
                'order' => 'uploaded_at.desc,uuid.desc',
                'limit' => 100,
            ]);
            foreach ($attempts as $att) {
                $att['image_available'] = !empty($att['slip_bucket']);
                unset($att['slip_bucket']);
                $attemptsByOrder[$att['order_uuid']][] = $att;
            }
        }

        foreach ($orders as &$ord) {
            $ord['payment_attempts'] = $attemptsByOrder[$ord['uuid']] ?? [];
        }
        unset($ord);

        return [...$page, 'items' => $orders];
    }

    public function listOrderAttempts(User $actor, LabProject $project, LabModuleInstallation $module, string $order, ?string $cursor = null): array
    {
        $this->assertModule($actor, $project, $module, 'orders', 'history');
        abort_unless(Str::isUuid($order), 404);
        $saved = $this->store->request($module, 'GET', 'krikkit_commerce_orders', ['project_uuid' => 'eq.'.$project->uuid, 'module_uuid' => 'eq.'.$module->uuid, 'uuid' => 'eq.'.$order, 'select' => 'uuid', 'limit' => 1]);
        abort_unless(isset($saved[0]), 404);
        $payments = LabModuleInstallation::query()->where('lab_project_id', $project->id)->where('key', 'manual_payments')->where('status', 'ready')->first();
        if ($payments === null) return ['items' => [], 'limit' => 25, 'next_cursor' => null];
        $this->assertModule($actor, $project, $payments, 'manual_payments', 'history');
        $query = ['project_uuid' => 'eq.'.$project->uuid, 'module_uuid' => 'eq.'.$payments->uuid, 'order_uuid' => 'eq.'.$order,
            'select' => 'uuid,order_uuid,payer_name,payer_phone,amount_satang,transfer_ref,claimed_transferred_at,slip_mime,slip_sha256,slip_bytes,slip_width,slip_height,slip_bucket,status,reviewer_id,review_reason,verified_at,version,uploaded_at,updated_at',
            'order' => 'uploaded_at.desc,uuid.desc', 'limit' => 26];
        $page = $this->historyPage($this->store->request($payments, 'GET', 'krikkit_payment_attempts', $this->historyCursor($query, $cursor, 'uploaded_at')), 'uploaded_at');
        foreach ($page['items'] as &$attempt) { $attempt['image_available'] = !empty($attempt['slip_bucket']); unset($attempt['slip_bucket']); }
        unset($attempt);
        return $page;
    }

    private function historyCursor(array $query, ?string $cursor, string $timestamp): array
    {
        if ($cursor === null) return $query;
        $decoded = strlen($cursor) <= 256 ? base64_decode($cursor, true) : false;
        $values = $decoded === false ? null : json_decode($decoded, true);
        abort_unless(is_array($values) && array_is_list($values) && count($values) === 2 && is_string($values[0]) && is_string($values[1])
            && preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d{1,6})?(?:Z|[+-]\d{2}:\d{2})$/D', $values[0]) && Str::isUuid($values[1]), 422, 'Invalid history cursor.');
        try { new \DateTimeImmutable($values[0]); $dateErrors = \DateTimeImmutable::getLastErrors(); }
        catch (\Exception) { abort(422, 'Invalid history cursor.'); }
        abort_if($dateErrors !== false && ($dateErrors['warning_count'] || $dateErrors['error_count']), 422, 'Invalid history cursor.');
        $query['or'] = '('.$timestamp.'.lt.'.$values[0].',and('.$timestamp.'.eq.'.$values[0].',uuid.lt.'.$values[1].'))';
        return $query;
    }

    private function historyPage(array $rows, string $timestamp): array
    {
        $items = array_slice($rows, 0, 25);
        $last = count($rows) > 25 ? end($items) : null;
        return ['items' => $items, 'limit' => 25, 'next_cursor' => $last === null ? null : base64_encode(json_encode([$last[$timestamp],$last['uuid']], JSON_THROW_ON_ERROR))];
    }

    public function reviewPaymentSlip(User $actor, LabProject $project, LabModuleInstallation $module, string $attemptUuid, array $body): array
    {
        abort_unless(Str::isUuid($attemptUuid), 422, 'Invalid payment attempt identifier.');
        abort_unless(array_diff(array_keys($body), ['expected_version','decision','reason','bank_verified']) === [], 422, 'Unexpected payment review fields.');
        abort_unless(isset($body['expected_version']) && is_int($body['expected_version']) && $body['expected_version'] >= 1, 422, 'Invalid expected version.');
        $data = Validator::make($body, [
            'decision' => ['required','in:accepted,rejected'],
            'reason' => ['sometimes','string','max:500'],
        ])->validate();
        $reason = trim($data['reason'] ?? '');
        if ($data['decision'] === 'accepted') abort_unless(($body['bank_verified'] ?? null) === true, 422, 'Confirm that you checked the actual bank credit before accepting this payment.');
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
            $checkoutAllowed = $bundle['orders'] !== null && $bundle['manual_payments'] !== null;
            if ($checkoutAllowed) {
                try {
                    $this->assertModule($catalogModule->owner, $project, $bundle['orders'], 'orders', 'mutate');
                    $this->assertModule($catalogModule->owner, $project, $bundle['manual_payments'], 'manual_payments', 'mutate');
                } catch (\App\Entitlement\Exceptions\EntitlementDeniedException) {
                    $checkoutAllowed = false;
                } catch (\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $exception) {
                    if (!in_array($exception->getStatusCode(), [402,403,409,423,503], true)) throw $exception;
                    $checkoutAllowed = false;
                }
            }
            if ($checkoutAllowed) {
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

    public function publicQuote(LabModuleInstallation $module, array $body): array
    {
        abort_unless(array_keys($body) === ['items'], 422, 'Only cart items are accepted.');
        $data = Validator::make($body, ['items' => ['required','array','min:1','max:25']])->validate();
        foreach ($data['items'] as &$line) {
            if ($line instanceof \stdClass) $line = (array) $line;
            abort_unless(is_array($line) && count($line) === 2 && isset($line['product_uuid'],$line['quantity']) && is_string($line['product_uuid'])
                && Str::isUuid($line['product_uuid']) && is_int($line['quantity']) && $line['quantity'] >= 1 && $line['quantity'] <= 99, 422, 'Invalid cart item.');
        }
        unset($line);
        abort_unless(count(array_unique(array_column($data['items'], 'product_uuid'))) === count($data['items']), 422, 'Duplicate cart item.');
        return DB::transaction(function () use ($module, $data) {
            $bundle = $this->resolvePublicBundle($module);
            abort_unless($bundle['orders'] !== null && $bundle['manual_payments'] !== null, 409, 'Checkout is not ready.');
            foreach (['orders','manual_payments'] as $key) $this->assertModule($bundle[$key]->owner, $bundle['project'], $bundle[$key], $key, 'mutate');
            $rows = $this->store->request($bundle['orders'], 'POST', 'rpc/krikkit_quote_order', [
                'p_project_uuid' => $bundle['project']->uuid, 'p_module_uuid' => $bundle['orders']->uuid,
                'p_catalog' => $bundle['catalog']->uuid, 'p_payments' => $bundle['manual_payments']->uuid, 'p_items' => $data['items'],
            ]);
            abort_unless(isset($rows[0]['uuid'],$rows[0]['expires_at']), 503, 'Quote could not be confirmed.');
            return ['quote' => ['quote_uuid' => $rows[0]['uuid'], 'catalog_version' => $rows[0]['catalog_version'], 'items' => $rows[0]['items'],
                'total_satang' => $rows[0]['total_satang'], 'currency' => 'THB', 'expires_at' => $rows[0]['expires_at']]];
        });
    }

    public function revokeOrderAccess(User $actor, LabProject $project, LabModuleInstallation $module, string $order): array
    {
        $this->assertModule($actor, $project, $module, 'orders', 'history');
        abort_unless(Str::isUuid($order), 404);
        $rows = $this->store->request($module, 'POST', 'rpc/krikkit_revoke_order_access', ['p_project_uuid' => $project->uuid, 'p_module_uuid' => $module->uuid, 'p_order' => $order, 'p_actor' => $actor->id]);
        abort_unless(($rows[0]['revoked'] ?? null) === true, 503, 'Order access could not be revoked.');
        return ['revoked' => true];
    }

    public function publicCreateOrder(LabModuleInstallation $module, array $body): array
    {
        abort_unless(array_diff(array_keys($body), ['idempotency_key','quote_uuid','customer_name','customer_email','customer_phone','note','items']) === [], 422, 'Unexpected order fields.');
        $data = Validator::make($body, [
            'idempotency_key' => ['required','uuid'],
            'quote_uuid' => ['required','uuid'],
            'customer_name' => ['required','string','max:120'],
            'customer_email' => ['required','email:rfc','max:160'],
            'customer_phone' => ['sometimes','string','max:40'],
            'note' => ['sometimes','string','max:1000'],
            'items' => ['required','array','min:1','max:25'],
        ])->validate();
        $normalizedItems = [];
        foreach ($data['items'] as $item) {
            if ($item instanceof \stdClass) $item = (array) $item;
            abort_unless(is_array($item) && array_diff(array_keys($item), ['product_uuid','quantity']) === []
                && isset($item['product_uuid'], $item['quantity']) && is_string($item['product_uuid']) && Str::isUuid($item['product_uuid'])
                && is_int($item['quantity']) && $item['quantity'] >= 1 && $item['quantity'] <= 99
                && !isset($normalizedItems[$item['product_uuid']]), 422, 'Invalid or duplicate cart line item.');
            $normalizedItems[$item['product_uuid']] = ['product_uuid' => $item['product_uuid'], 'quantity' => $item['quantity']];
        }
        $data['items'] = array_values($normalizedItems);
        $requestHash = hash('sha256', json_encode(['quote_uuid' => $data['quote_uuid'], 'customer_name' => trim($data['customer_name']), 'customer_email' => strtolower(trim($data['customer_email'])),
            'customer_phone' => trim($data['customer_phone'] ?? ''), 'note' => trim($data['note'] ?? ''), 'items' => $data['items'],
        ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));

        return DB::transaction(function () use ($module, $data, $requestHash) {
            // Recover an already accepted creation before checking mutable
            // catalog/settings state. Only the matching creation key + hash can replay.
            $history = $this->resolvePublicBundle($module, true);
            abort_unless($history['orders'] !== null, 409, 'Checkout is not installed.');
            $saved = $this->store->request($history['orders'], 'GET', 'krikkit_commerce_orders', [
                'project_uuid' => 'eq.'.$history['project']->uuid, 'module_uuid' => 'eq.'.$history['orders']->uuid,
                'request_key' => 'eq.'.$data['idempotency_key'], 'select' => 'uuid,request_hash', 'limit' => 1,
            ]);
            if (isset($saved[0])) {
                abort_unless(hash_equals((string) $saved[0]['request_hash'], $requestHash), 409, 'This retry differs from the saved order.');
                $token = $this->orderToken($history['orders'], $data['idempotency_key']);
                return [...$this->publicGetOrder($module, $saved[0]['uuid'], $token), 'access_token' => $token, 'replayed' => true];
            }
            $bundle = $this->resolvePublicBundle($module);
            abort_unless($bundle['orders'] !== null && $bundle['manual_payments'] !== null, 409, 'Checkout and PromptPay payment modules are not enabled for this store.');
            $project = $bundle['project'];
            $catalogModule = $bundle['catalog'];
            $ordersModule = $bundle['orders'];
            $paymentsModule = $bundle['manual_payments'];
            $this->assertModule($ordersModule->owner, $project, $ordersModule, 'orders', 'mutate');
            $this->assertModule($paymentsModule->owner, $project, $paymentsModule, 'manual_payments', 'mutate');

            $quotes = $this->store->request($ordersModule, 'GET', 'krikkit_commerce_quotes', [
                'project_uuid' => 'eq.'.$project->uuid, 'module_uuid' => 'eq.'.$ordersModule->uuid,
                'uuid' => 'eq.'.$data['quote_uuid'], 'select' => 'uuid,items,total_satang,settings', 'limit' => 1,
            ]);
            abort_unless(isset($quotes[0]), 409, 'Review a fresh quote before ordering.');
            $settings = $quotes[0]['settings'];
            $snapshotItems = $quotes[0]['items'];
            $totalSatang = (int) $quotes[0]['total_satang'];
            $customerName = trim($data['customer_name']);
            $customerEmail = strtolower(trim($data['customer_email']));
            $customerPhone = trim($data['customer_phone'] ?? '');
            $note = trim($data['note'] ?? '');
            abort_unless($customerName !== '', 422, 'Customer name is required.');

            // Deterministic order access token from idempotency_key + app.key so replays return the exact same guest token
            $accessToken = $this->orderToken($ordersModule, $data['idempotency_key']);
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

            $rows = $this->store->request($ordersModule, 'POST', 'rpc/krikkit_create_quoted_order', [
                'p_project_uuid' => $project->uuid,
                'p_module_uuid' => $ordersModule->uuid,
                'p_key' => $data['idempotency_key'],
                'p_hash' => $requestHash,
                'p_values' => $values,
                'p_quote' => $data['quote_uuid'], 'p_cart' => $data['items'],
            ]);
            abort_unless(isset($rows[0]['order_uuid'], $rows[0]['reference'], $rows[0]['status'], $rows[0]['replayed']), 503, 'Unexpected order creation response.');

            return [...$this->publicGetOrder($module, $rows[0]['order_uuid'], $accessToken), 'access_token' => $accessToken, 'replayed' => (bool) $rows[0]['replayed']];
        });
    }

    public function publicGetOrder(LabModuleInstallation $module, string $orderUuid, string $accessToken): array
    {
        abort_unless(Str::isUuid($orderUuid) && $accessToken !== '', 404);
        return DB::transaction(function () use ($module, $orderUuid, $accessToken) {
            $bundle = $this->resolvePublicBundle($module, true);
            abort_unless($bundle['orders'] !== null, 404);
            $project = $bundle['project'];
            $ordersModule = $bundle['orders'];

            $rows = $this->store->request($ordersModule, 'GET', 'krikkit_commerce_orders', [
                'project_uuid' => 'eq.'.$project->uuid,
                'module_uuid' => 'eq.'.$ordersModule->uuid,
                'uuid' => 'eq.'.$orderUuid,
                'select' => 'uuid,reference,customer_name,items,total_satang,currency,promptpay_snapshot,access_token_hash,access_revoked_at,status,version,created_at,updated_at',
                'limit' => 1,
            ]);
            abort_unless(isset($rows[0]), 404, 'Order not found.');
            $order = $rows[0];
            $this->assertOrderAccess($order, $accessToken);

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
            'slip_data_url' => ['required','string','min:32','max:7100000'],
        ])->validate();

        return DB::transaction(function () use ($module, $orderUuid, $accessToken, $data, $body) {
            $bundle = $this->resolvePublicBundle($module, true);
            abort_unless($bundle['orders'] !== null && $bundle['manual_payments'] !== null, 409, 'Manual payment review is not enabled.');
            $project = $bundle['project'];
            $ordersModule = $bundle['orders'];
            $paymentsModule = $bundle['manual_payments'];

            $orderRows = $this->store->request($ordersModule, 'GET', 'krikkit_commerce_orders', [
                'project_uuid' => 'eq.'.$project->uuid,
                'module_uuid' => 'eq.'.$ordersModule->uuid,
                'uuid' => 'eq.'.$orderUuid,
                'select' => 'uuid,total_satang,access_token_hash,access_revoked_at,status,created_at',
                'limit' => 1,
            ]);
            abort_unless(isset($orderRows[0]), 404, 'Order not found.');
            $order = $orderRows[0];
            $this->assertOrderAccess($order, $accessToken);
            $this->assertModule($bundle['catalog']->owner, $project, $bundle['catalog'], 'catalog', 'mutate');
            $this->assertModule($ordersModule->owner, $project, $ordersModule, 'orders', 'mutate');
            $this->assertModule($paymentsModule->owner, $project, $paymentsModule, 'manual_payments', 'mutate');
            $image = SlipImage::inspect($data['slip_data_url']);

            $payerName = trim($data['payer_name']);
            abort_unless($payerName !== '', 422, 'Payer name is required.');

            $values = [
                'payer_name' => $payerName,
                'payer_phone' => trim($data['payer_phone'] ?? ''),
                'amount_satang' => $body['amount_satang'],
                'transfer_ref' => trim($data['transfer_ref'] ?? ''),
                'claimed_transferred_at' => trim($data['claimed_transferred_at'] ?? ''),
            ];
            $hash = hash('sha256', json_encode([
                'order_uuid' => $orderUuid,
                'payer_name' => $values['payer_name'],
                'payer_phone' => $values['payer_phone'],
                'amount_satang' => $values['amount_satang'],
                'transfer_ref' => $values['transfer_ref'],
                'claimed_transferred_at' => $values['claimed_transferred_at'],
                'slip_mime' => $image['mime'],
                'slip_sha256' => $image['sha256'],
            ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));

            $lease = (string) Str::uuid();
            $rows = $this->store->request($paymentsModule, 'POST', 'rpc/krikkit_begin_slip_upload', [
                'p_project_uuid' => $project->uuid,
                'p_module_uuid' => $paymentsModule->uuid,
                'p_order_uuid' => $orderUuid,
                'p_key' => $data['idempotency_key'],
                'p_hash' => $hash,
                'p_lease' => $lease,
                'p_image' => array_intersect_key($image, array_flip(['sha256','bytes','width','height'])),
            ]);
            abort_unless(isset($rows[0]['uuid'], $rows[0]['state']), 503, 'Unexpected slip upload receipt.');
            $receipt = $rows[0];
            $replayed = $receipt['state'] === 'committed';
            if (!$replayed) {
                try {
                    $this->images->upload($paymentsModule, $receipt, $image);
                    $rows = $this->store->request($paymentsModule, 'POST', 'rpc/krikkit_commit_slip_upload', [
                        'p_project_uuid' => $project->uuid, 'p_module_uuid' => $paymentsModule->uuid,
                        'p_upload' => $receipt['uuid'], 'p_lease' => $lease, 'p_values' => $values,
                    ]);
                    abort_unless(($rows[0]['state'] ?? null) === 'committed', 503, 'Slip submission could not be confirmed.');
                    $receipt = $rows[0];
                } catch (\Throwable $failure) {
                    // Claim under the same remote row lock before deleting. An
                    // ambiguous commit may already have accepted the evidence.
                    try { $recovered = $this->cleanupUpload($paymentsModule, $receipt, $lease); }
                    catch (\Throwable) { throw $failure; }
                    if ($recovered['state'] !== 'committed') throw $failure;
                    $receipt = $recovered;
                }
            }
            $attempt = $this->store->request($paymentsModule, 'GET', 'krikkit_payment_attempts', [
                'project_uuid' => 'eq.'.$project->uuid, 'module_uuid' => 'eq.'.$paymentsModule->uuid,
                'uuid' => 'eq.'.$receipt['attempt_uuid'], 'select' => 'uuid,order_uuid,status', 'limit' => 1,
            ]);
            abort_unless(isset($attempt[0]), 503, 'Slip submission could not be confirmed.');
            $current = $this->publicGetOrder($module, $orderUuid, $accessToken)['order'];
            return [
                'slip' => [
                    'uuid' => $attempt[0]['uuid'],
                    'order_uuid' => $attempt[0]['order_uuid'],
                    'status' => $attempt[0]['status'],
                    'order_status' => $current['status'],
                ],
                'replayed' => $replayed,
            ];
        });
    }

    public function slipImage(User $actor, LabProject $project, LabModuleInstallation $module, string $attemptUuid): array
    {
        $this->assertModule($actor, $project, $module, 'manual_payments', 'history');
        abort_unless(Str::isUuid($attemptUuid), 404);
        $rows = $this->store->request($module, 'GET', 'krikkit_payment_attempts', [
            'project_uuid' => 'eq.'.$project->uuid, 'module_uuid' => 'eq.'.$module->uuid,
            'uuid' => 'eq.'.$attemptUuid, 'select' => 'uuid,slip_bucket,slip_object', 'limit' => 1,
        ]);
        abort_unless(isset($rows[0]), 404);
        if (empty($rows[0]['slip_bucket']) || empty($rows[0]['slip_object'])) return ['url' => '/lab/'.$project->uuid.'/modules/'.$module->uuid.'/payment-attempts/'.$attemptUuid.'/legacy-image', 'expires_at' => now()->addMinutes(5)->toIso8601String(), 'legacy' => true];
        return $this->images->signedImage($module, $rows[0]['slip_bucket'], $rows[0]['slip_object']);
    }

    public function legacySlipImage(User $actor, LabProject $project, LabModuleInstallation $module, string $attemptUuid): string
    {
        $this->assertModule($actor, $project, $module, 'manual_payments', 'history');
        abort_unless(Str::isUuid($attemptUuid), 404);
        $rows = $this->store->request($module, 'GET', 'krikkit_payment_attempts', ['project_uuid'=>'eq.'.$project->uuid, 'module_uuid'=>'eq.'.$module->uuid, 'uuid'=>'eq.'.$attemptUuid, 'select'=>'uuid,slip_data_url,slip_bucket', 'limit'=>1]);
        abort_unless(isset($rows[0]) && empty($rows[0]['slip_bucket']) && is_string($rows[0]['slip_data_url'] ?? null), 404);
        return SlipImage::inspect($rows[0]['slip_data_url'])['contents'];
    }

    public function reconcileSlips(User $actor, LabProject $project, LabModuleInstallation $module): array
    {
        $this->assertModule($actor, $project, $module, 'manual_payments', 'history');
        $rows = $this->store->request($module, 'GET', 'krikkit_slip_uploads', [
            'project_uuid' => 'eq.'.$project->uuid, 'module_uuid' => 'eq.'.$module->uuid,
            'state' => 'in.(staged,cleanup_pending,abandoned)', 'lease_expires_at' => 'lt.'.now()->toIso8601String(),
            'select' => 'uuid,bucket_id,object_path,state,attempt_uuid,lease_expires_at', 'order' => 'lease_expires_at.asc,uuid.asc', 'limit' => 10,
        ]);
        $cleaned = 0; $pending = 0;
        foreach ($rows as $receipt) {
            try { if ($this->cleanupUpload($module, $receipt, null)['state'] === 'abandoned') $cleaned++; }
            catch (\Throwable) { $pending++; }
        }
        return ['checked' => count($rows), 'cleaned' => $cleaned, 'pending' => $pending];
    }

    private function cleanupUpload(LabModuleInstallation $module, array $receipt, ?string $lease): array
    {
        $cleanup = (string) Str::uuid();
        $scope = ['p_project_uuid' => $module->project->uuid, 'p_module_uuid' => $module->uuid, 'p_upload' => $receipt['uuid']];
        $rows = $this->store->request($module, 'POST', 'rpc/krikkit_claim_slip_cleanup', [...$scope, 'p_lease' => $lease, 'p_cleanup' => $cleanup]);
        abort_unless(isset($rows[0]['state']), 503, 'Upload recovery is temporarily unavailable.');
        $receipt = $rows[0];
        if ($receipt['state'] !== 'cleanup_pending') return $receipt;
        $this->images->deleteUncommitted($module, $receipt);
        $this->store->request($module, 'POST', 'rpc/krikkit_finish_slip_cleanup', [...$scope, 'p_lease' => $cleanup]);
        return [...$receipt, 'state' => 'abandoned'];
    }

    private function orderToken(LabModuleInstallation $module, string $key): string
    {
        return hash_hmac('sha256', 'krikkit-order-token:'.$module->uuid.':'.$key, (string) config('app.key'));
    }

    private function assertOrderAccess(array $order, string $token): void
    {
        abort_unless(preg_match('/^[a-f0-9]{64}$/D', $token) && hash_equals((string) $order['access_token_hash'], hash('sha256', $token))
            && empty($order['access_revoked_at']) && \Illuminate\Support\Carbon::parse($order['created_at'])->addHours(24)->isFuture(), 404, 'Order access is unavailable or expired.');
    }

    private function resolvePublicBundle(LabModuleInstallation $entryModule, bool $history = false): array
    {
        abort_unless(in_array($entryModule->key, ['catalog', 'orders', 'manual_payments'], true), 404);
        $project = LabProject::query()->whereKey($entryModule->lab_project_id)->lockForUpdate()->firstOrFail();
        $installations = LabModuleInstallation::query()
            ->where('lab_project_id', $project->id)
            ->lockForUpdate()
            ->get()
            ->keyBy('key');

        $catalog = $installations['catalog'] ?? null;
        abort_unless($catalog !== null && $catalog->status === 'ready' && $catalog->schema_version === 1 && ($history || $catalog->enabled), 409, 'Store catalog is not active.');
        $this->policy->assertAllowed($catalog->owner, $project, $history ? 'history' : 'mutate', $catalog);

        $orders = $installations['orders'] ?? null;
        if ($orders !== null && ($orders->status !== 'ready' || (!$history && !$orders->enabled) || $orders->schema_version !== ModuleCatalog::get('orders')['schema_version'])) {
            $orders = null;
        }
        $manualPayments = $installations['manual_payments'] ?? null;
        if ($manualPayments !== null && ($manualPayments->status !== 'ready' || (!$history && !$manualPayments->enabled) || $manualPayments->schema_version !== ModuleCatalog::get('manual_payments')['schema_version'])) {
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
        abort_unless($module->key === $expectedKey && $module->schema_version === ModuleCatalog::get($expectedKey)['schema_version'] && $module->status === 'ready', 409, 'Module storage is not ready.');
    }
}
