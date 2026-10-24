<?php

namespace App\Lab\Modules;

final class ModuleCatalog
{
    public static function all(): array
    {
        return [
            'booking' => ['title' => 'Bookings', 'schema_version' => 1, 'dependencies' => [], 'legacy' => true],
            'contact' => ['title' => 'Contact form', 'schema_version' => 1, 'dependencies' => [], 'legacy' => false],
            'news' => ['title' => 'News', 'schema_version' => 1, 'dependencies' => [], 'legacy' => false],
            'catalog' => ['title' => 'Product catalog', 'schema_version' => 1, 'dependencies' => [], 'legacy' => false],
            'orders' => ['title' => 'Cart and orders', 'schema_version' => 1, 'dependencies' => ['catalog'], 'legacy' => false],
            'manual_payments' => ['title' => 'Manual PromptPay review', 'schema_version' => 1, 'dependencies' => ['orders'], 'legacy' => false],
            'custom_data' => ['title' => 'Custom data', 'schema_version' => 1, 'dependencies' => [], 'legacy' => false],
        ];
    }

    public static function get(string $key): array
    {
        abort_unless(isset(self::all()[$key]), 422, 'Unknown managed module.');
        return ['key' => $key, 'feature' => 'lab_module_'.$key, ...self::all()[$key]];
    }
}
