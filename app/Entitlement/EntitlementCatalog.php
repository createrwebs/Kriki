<?php

namespace App\Entitlement;

/**
 * Built-in entitlement keys for Krikkit packs (features + quotas).
 */
final class EntitlementCatalog
{
    public const PROJECTS = 'projects';

    public const LAB_CREDITS = 'lab_credits';

    /** @deprecated Use LAB_CREDITS — ledger now stores credit spend, not chat turns. */
    public const AI_GENERATIONS = self::LAB_CREDITS;

    public const CUSTOM_DOMAIN = 'custom_domain';

    public const CUSTOM_SUBDOMAIN = 'custom_subdomain';

    public const ADVANCED_EXPORT = 'advanced_export';

    public const GITHUB_IMPORT = 'github_import';

    /**
     * @return list<array{
     *     code: string,
     *     kind: GrantKind,
     *     window: UsageWindow,
     *     meter: 'ledger'|'projects'|'none',
     *     title: string
     * }>
     */
    public static function definitions(): array
    {
        $definitions = [
            [
                'code' => self::PROJECTS,
                'kind' => GrantKind::Quota,
                'window' => UsageWindow::Lifetime,
                'meter' => 'projects',
                'title' => 'Projects',
            ],
            [
                'code' => self::LAB_CREDITS,
                'kind' => GrantKind::Quota,
                'window' => UsageWindow::Monthly,
                'meter' => 'ledger',
                'title' => 'Lab credits',
            ],
            [
                'code' => self::CUSTOM_SUBDOMAIN,
                'kind' => GrantKind::Feature,
                'window' => UsageWindow::None,
                'meter' => 'none',
                'title' => 'Custom subdomain',
            ],
            [
                'code' => self::CUSTOM_DOMAIN,
                'kind' => GrantKind::Feature,
                'window' => UsageWindow::None,
                'meter' => 'none',
                'title' => 'Custom domain',
            ],
            [
                'code' => self::ADVANCED_EXPORT,
                'kind' => GrantKind::Feature,
                'window' => UsageWindow::None,
                'meter' => 'none',
                'title' => 'Advanced export',
            ],
            [
                'code' => self::GITHUB_IMPORT,
                'kind' => GrantKind::Feature,
                'window' => UsageWindow::None,
                'meter' => 'none',
                'title' => 'GitHub import',
            ],
        ];
        // Defined for explicit operator plan configuration. Existing plans get
        // no new grants merely because a module was added to the catalog.
        foreach (['booking', 'contact', 'news', 'catalog', 'orders', 'manual_payments', 'custom_data'] as $module) {
            $definitions[] = [
                'code' => 'lab_module_'.$module, 'kind' => GrantKind::Feature,
                'window' => UsageWindow::None, 'meter' => 'none',
                'title' => 'Lab module: '.str_replace('_', ' ', $module),
            ];
        }
        return $definitions;
    }

    /**
     * @return array{
     *     code: string,
     *     kind: GrantKind,
     *     window: UsageWindow,
     *     meter: 'ledger'|'projects'|'none',
     *     title: string
     * }|null
     */
    public static function definition(string $code): ?array
    {
        foreach (self::definitions() as $definition) {
            if ($definition['code'] === $code) {
                return $definition;
            }
        }

        return null;
    }

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_column(self::definitions(), 'code');
    }

    public static function title(string $code): string
    {
        return self::definition($code)['title'] ?? $code;
    }
}
