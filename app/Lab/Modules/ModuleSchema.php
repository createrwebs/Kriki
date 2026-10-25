<?php

namespace App\Lab\Modules;

use App\Models\LabModuleInstallation;
use App\Models\LabProject;

final class ModuleSchema
{
    public static function sql(string $key): string
    {
        $file = match ($key) {
            'contact' => '0001_contact.sql',
            'news' => '0002_news.sql',
            'catalog' => '0003_catalog.sql',
            'orders' => '0004_orders.sql',
            'manual_payments' => '0005_manual_payments.sql',
            default => abort(422, 'This module schema is not available yet.'),
        };
        $sql = file_get_contents(database_path('sql/lab-managed/'.$file));
        if (!in_array($key, ['manual_payments','orders'], true)) return $sql;
        $boundary = strrpos($sql, "\nCOMMIT;");
        abort_unless($boundary !== false, 503, 'Invalid managed schema transaction.');
        $fragment = $key === 'orders' ? '0007_order_quotes.fragment.sql' : '0006_private_slips.fragment.sql';
        return substr($sql, 0, $boundary)."\n".file_get_contents(database_path('sql/lab-managed/'.$fragment))
            ."\nCOMMIT;\nSELECT '{$key}'::text AS krikkit_schema_key,2::integer AS schema_version,true AS verified;\n";
    }

    public static function payload(LabProject $project, LabModuleInstallation $module): array
    {
        $sql = self::sql($module->key);
        return ['operation' => 'install_module', 'actor_id' => (int) $module->user_id, 'project_uuid' => $project->uuid, 'module_uuid' => $module->uuid,
            'key' => $module->key, 'project_ref' => $module->supabase_project_ref, 'base_version' => $module->revision,
            'schema_version' => ModuleCatalog::get($module->key)['schema_version'], 'sql_sha256' => hash('sha256', $sql), 'sql' => $sql,
        ];
    }

    public static function hash(array $payload): string
    {
        // MySQL JSON storage may reorder object keys. Approval binds values,
        // not the serializer's insertion order; this payload is intentionally flat.
        ksort($payload, SORT_STRING);
        return hash('sha256', json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));
    }
}
