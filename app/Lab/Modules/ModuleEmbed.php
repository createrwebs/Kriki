<?php

namespace App\Lab\Modules;

use App\Lab\SiteWorkspace;
use App\Models\LabModuleInstallation;
use App\Models\LabProject;
use App\Models\User;

final class ModuleEmbed
{
    public function __construct(private readonly CapabilityPolicy $policy, private readonly SiteWorkspace $workspace) {}

    public function embed(User $actor, LabProject $project, LabModuleInstallation $module, string $route, string $expectedAppHash): array
    {
        $this->policy->assertAllowed($actor, $project, 'prepare', $module);
        abort_unless(in_array($module->key, ['contact', 'news', 'catalog', 'orders', 'manual_payments'], true) && $module->status === 'ready', 409, 'Install and verify module schema first.');
        $app = $this->workspace->read($project, 'src/App.jsx');
        abort_unless(hash_equals(hash('sha256', $app), $expectedAppHash), 409, 'The page source changed. Reload before adding the widget.');
        $site = json_decode($this->workspace->read($project, 'src/krikkit-site/site.json'), true);
        abort_unless(in_array($route, array_column($site['pages'] ?? [], 'route'), true), 422, 'Choose an existing page route.');
        $wrapper = file_get_contents(resource_path('js/lab/widget-runtime/app-wrapper.jsx'));
        $root = $this->workspace->root($project);
        $files = [];
        if ($app !== $wrapper) {
            abort_unless($app === file_get_contents(resource_path('builder/generated-app.jsx')) && !file_exists($root.'/src/SiteContent.jsx'), 409, 'This page has custom source. Add the widget component manually to preserve your changes.');
            $files['src/SiteContent.jsx'] = $app;
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
        foreach (['ContactForm.jsx','NewsFeed.jsx','StorefrontWidget.jsx','widgets.css'] as $file) {
            $path = 'src/krikkit-widgets/'.$file;
            $contents = file_get_contents(resource_path('js/lab/widget-runtime/'.$file));
            abort_if(is_file($root.'/'.$path) && $this->workspace->read($project, $path) !== $contents, 409, 'Widget source has custom changes. Review before replacing.');
            $files[$path] = $contents;
        }
        $files['src/krikkit-widgets/config.json'] = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n";
        return ['paths' => $this->workspace->commitAtomic($project, $files), 'files' => $files];
    }
}
