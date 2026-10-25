<?php

namespace App\Lab\Modules;

use App\Lab\SiteWorkspace;
use App\Models\LabModuleInstallation;
use App\Models\LabProject;
use App\Models\User;

final class ModuleEmbed
{
    public const SDK_VERSION = 3;
    public const MANIFEST = 'src/krikkit-widgets/runtime-manifest.json';
    public const RUNTIME_FILES = ['ContactForm.jsx','NewsFeed.jsx','StorefrontWidget.jsx','storeState.js','commerceRoutes.js','widgets.css'];
    public static function verifyManifest(array $manifest, string $projectUuid): bool
    {
        $signature = $manifest['signature'] ?? '';
        unset($manifest['signature']);
        return ($manifest['project_uuid'] ?? null) === $projectUuid && is_string($signature)
            && hash_equals(hash_hmac('sha256', json_encode($manifest, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR), (string) config('app.key')), $signature);
    }
    public function __construct(private readonly CapabilityPolicy $policy, private readonly SiteWorkspace $workspace) {}

    public function embed(User $actor, LabProject $project, LabModuleInstallation $module, string $route, string $expectedAppHash, ?array $expectedFiles = null, ?string $impactHash = null): array
    {
        return $this->workspace->withWriteLock($project, function () use ($actor, $project, $module, $route, $expectedAppHash, $expectedFiles, $impactHash) {
            $plan = $this->plan($actor, $project, $module, $route);
            abort_unless($plan['expected_files']['src/App.jsx'] === $expectedAppHash, 409, 'The page source changed. Review the module update again.');
            if ($expectedFiles !== null) {
                ksort($expectedFiles);
                abort_unless($expectedFiles === $plan['expected_files'] && $impactHash === $plan['impact_hash'], 409, 'Workspace or widget versions changed. Review the module update again.');
            }
            return ['paths' => $this->workspace->commitAtomic($project, $plan['files'], $plan['expected_files'], managed: true), 'files' => $plan['files'], 'expected_files' => $plan['expected_files']];
        });
    }

    public function plan(User $actor, LabProject $project, LabModuleInstallation $module, string $route): array
    {
        $this->policy->assertAllowed($actor, $project, 'prepare', $module);
        abort_unless(in_array($module->key, ['contact', 'news', 'catalog', 'orders', 'manual_payments'], true) && $module->status === 'ready' && $module->schema_version === ModuleCatalog::get($module->key)['schema_version'], 409, 'Install and verify module schema first.');
        $app = $this->workspace->read($project, 'src/App.jsx');
        $site = json_decode($this->workspace->read($project, 'src/krikkit-site/site.json'), true);
        abort_unless(in_array($route, array_column($site['pages'] ?? [], 'route'), true), 422, 'Choose an existing page route.');
        $wrapper = file_get_contents(resource_path('js/lab/widget-runtime/app-wrapper.jsx'));
        $root = $this->workspace->root($project);
        $files = [];
        $previous = is_file($root.'/'.self::MANIFEST) ? json_decode($this->workspace->read($project, self::MANIFEST), true) : null;
        abort_if($previous !== null && (!is_array($previous) || !self::verifyManifest($previous, $project->uuid)), 409, 'Widget ownership manifest is invalid. Restore the reviewed source before updating.');
        $legacy = json_decode(file_get_contents(resource_path('js/lab/widget-runtime/legacy-hashes.json')), true);
        $owned = fn (string $path, string $contents, string $template) => ($previous['files'][$path] ?? null) === hash('sha256', $contents)
            || in_array(hash('sha256', $contents), $legacy[$template] ?? [], true);
        if ($app !== $wrapper) {
            if (file_exists($root.'/src/SiteContent.jsx')) abort_unless($owned('src/App.jsx', $app, 'app-wrapper.jsx'), 409, 'Widget wrapper has custom changes. Merge them before upgrading.');
            else {
                abort_unless($app === file_get_contents(resource_path('builder/generated-app.jsx')), 409, 'This page has custom source. Add the widget component manually to preserve your changes.');
                $files['src/SiteContent.jsx'] = $app;
            }
            $files['src/App.jsx'] = $wrapper;
        }
        $url = parse_url(config('app.url'));
        abort_unless(isset($url['scheme'], $url['host']) && in_array($url['scheme'], ['http','https'], true), 503, 'Configure the application URL first.');
        $origin = $url['scheme'].'://'.$url['host'].(isset($url['port']) ? ':'.$url['port'] : '');
        $config = ['apiOrigin' => $origin, 'widgets' => []];
        if (is_file($root.'/src/krikkit-widgets/config.json')) {
            $config = json_decode($this->workspace->read($project, 'src/krikkit-widgets/config.json'), true);
            abort_unless(is_array($config) && ($config['apiOrigin'] ?? null) === $origin && is_array($config['widgets'] ?? null), 409, 'Widget configuration changed. Review it before updating.');
        }
        $commerceKeys = ['catalog', 'orders', 'manual_payments'];
        $config['widgets'] = array_values(array_filter($config['widgets'], function ($widget) use ($module, $commerceKeys) {
            $wKey = $widget['key'] ?? null;
            if (in_array($module->key, $commerceKeys, true) && in_array($wKey, $commerceKeys, true)) {
                return false;
            }
            return $wKey !== $module->key;
        }));
        $config['widgets'][] = ['key' => $module->key, 'moduleUuid' => $module->uuid, 'route' => $route];
        if (in_array($module->key, $commerceKeys, true)) foreach (['/cart','/checkout','/orders'] as $suffix) {
            $reserved = rtrim($route, '/').$suffix;
            foreach ($site['pages'] ?? [] as $page) abort_if($page['route'] === $reserved || str_starts_with($page['route'], $reserved.'/'), 409, 'A commerce route conflicts with an existing page. Choose another store page.');
        }
        foreach (self::RUNTIME_FILES as $file) {
            $path = 'src/krikkit-widgets/'.$file;
            $contents = file_get_contents(resource_path('js/lab/widget-runtime/'.$file));
            abort_if(is_file($root.'/'.$path) && $this->workspace->read($project, $path) !== $contents && !$owned($path, $this->workspace->read($project, $path), $file), 409, 'Widget source has custom changes. Review before replacing.');
            $files[$path] = $contents;
        }
        $files['src/krikkit-widgets/config.json'] = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n";
        $hashes = [];
        foreach ($files + ['src/App.jsx' => $wrapper] as $path => $body) if ($path !== 'src/SiteContent.jsx') $hashes[$path] = hash('sha256', $body);
        $bindings = [];
        foreach ($config['widgets'] as $widget) {
            $installed = LabModuleInstallation::query()->where('lab_project_id', $project->id)->where('uuid', $widget['moduleUuid'])->first();
            abort_unless($installed !== null && $installed->key === $widget['key'], 409, 'Widget binding is not owned by this project.');
            $bindings[] = ['key' => $installed->key, 'uuid' => $installed->uuid, 'schema_version' => ModuleCatalog::get($installed->key)['schema_version']];
        }
        $manifest = ['version' => 1, 'sdk_version' => self::SDK_VERSION, 'runtime_version' => 'krikkit-runtime-v1', 'project_uuid' => $project->uuid, 'modules' => $bindings, 'files' => $hashes];
        $manifest['signature'] = hash_hmac('sha256', json_encode($manifest, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR), (string) config('app.key'));
        $files[self::MANIFEST] = json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n";
        $expected = [];
        foreach (array_unique([...array_keys($files), 'src/App.jsx', 'src/SiteContent.jsx', 'src/krikkit-site/site.json']) as $path) $expected[$path] = is_file($root.'/'.$path) ? hash_file('sha256', $root.'/'.$path) : null;
        ksort($expected); ksort($files);
        return ['files' => $files, 'expected_files' => $expected, 'impact_hash' => hash('sha256', json_encode([$files,$expected], JSON_THROW_ON_ERROR))];
    }
}
