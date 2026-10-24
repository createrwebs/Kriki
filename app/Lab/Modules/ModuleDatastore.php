<?php

namespace App\Lab\Modules;

use App\Datastore\DatastoreHttp;
use App\Models\LabModuleInstallation;

final class ModuleDatastore
{
    public function __construct(private readonly ModuleInstallations $modules) {}

    public function request(LabModuleInstallation $module, string $method, string $path, array $data): array
    {
        abort_unless(in_array($method, ['GET', 'POST'], true) && in_array($path, [
            'krikkit_contact_messages', 'krikkit_contact_events', 'rpc/krikkit_submit_contact', 'rpc/krikkit_transition_contact',
            'krikkit_news_articles', 'krikkit_news_events', 'rpc/krikkit_create_news', 'rpc/krikkit_update_news',
            'krikkit_catalog_products', 'krikkit_catalog_events', 'rpc/krikkit_create_product', 'rpc/krikkit_update_product',
            'krikkit_commerce_orders', 'krikkit_commerce_order_events', 'rpc/krikkit_create_order',
            'krikkit_payment_settings', 'krikkit_payment_attempts', 'krikkit_payment_events',
            'rpc/krikkit_save_payment_settings', 'rpc/krikkit_submit_payment_slip', 'rpc/krikkit_review_payment_slip',
        ], true), 403, 'Unsupported managed storage operation.');
        $plane = $this->modules->plane($module);
        try {
            $http = DatastoreHttp::make()->withHeaders(['apikey' => $plane['steward_token'], 'Authorization' => 'Bearer '.$plane['steward_token']])->acceptJson();
            $response = $method === 'GET' ? $http->get(rtrim($plane['host_url'], '/').'/rest/v1/'.$path, $data)
                : $http->post(rtrim($plane['host_url'], '/').'/rest/v1/'.$path, $data);
        } catch (\Throwable) { abort(503, 'Module storage is temporarily unavailable.'); }
        if (!$response->successful()) {
            $message = $response->json('message');
            if (in_array($message, ['idempotency_conflict', 'version_conflict', 'invalid_transition', 'slug_conflict', 'sku_conflict'], true)) abort(409, 'This request conflicts with a saved operation. Refresh and try again.');
            if (in_array($message, ['slug_immutable', 'invalid_article', 'sku_immutable', 'invalid_product', 'invalid_order', 'invalid_payment_settings', 'invalid_payment_slip', 'invalid_review'], true)) abort(422, 'Please review the submitted details.');
            if (in_array($message, ['contact_not_found', 'article_not_found', 'product_not_found', 'order_not_found', 'attempt_not_found'], true)) abort(404);
            abort(503, 'Module storage is temporarily unavailable.');
        }
        $payload = $response->json();
        abort_unless(is_array($payload) && array_is_list($payload), 503, 'Unexpected module storage response.');
        return $payload;
    }
}
