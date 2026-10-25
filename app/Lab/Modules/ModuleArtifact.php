<?php

namespace App\Lab\Modules;

use App\Lab\SiteWorkspace;
use App\Models\LabModuleInstallation;
use App\Models\LabProject;
use Symfony\Component\Finder\Finder;

final class ModuleArtifact
{
    public const FILE = '.krikkit-artifact.json';
    public function __construct(private readonly SiteWorkspace $workspace) {}

    public function context(LabProject $project): ?array
    {
        return $this->workspace->withWriteLock($project, function () use ($project) {
            $root = $this->workspace->root($project);
            if (!is_file($root.'/src/krikkit-widgets/config.json')) return null;
            abort_unless(is_file($root.'/'.ModuleEmbed::MANIFEST), 409, 'Upgrade the managed widgets before building this site.');
            $manifest = json_decode($this->workspace->read($project, ModuleEmbed::MANIFEST), true);
            abort_unless(is_array($manifest) && ModuleEmbed::verifyManifest($manifest, $project->uuid)
                && ($manifest['version'] ?? null) === 1 && ($manifest['sdk_version'] ?? null) === ModuleEmbed::SDK_VERSION
                && ($manifest['runtime_version'] ?? null) === 'krikkit-runtime-v1', 409, 'Widget SDK or runtime is incompatible. Review the module upgrade.');
            foreach (ModuleEmbed::RUNTIME_FILES as $name) {
                $path = 'src/krikkit-widgets/'.$name;
                abort_unless(is_file($root.'/'.$path) && hash_file('sha256', $root.'/'.$path) === hash_file('sha256', resource_path('js/lab/widget-runtime/'.$name)), 409, 'Managed widget source changed. Restore or upgrade the reviewed SDK before building.');
            }
            $configPath = 'src/krikkit-widgets/config.json';
            abort_unless(($manifest['files'][$configPath] ?? null) === hash_file('sha256', $root.'/'.$configPath), 409, 'Widget configuration changed outside the installer.');
            $bindings = [];
            foreach ($manifest['modules'] ?? [] as $binding) {
                $module = LabModuleInstallation::query()->where('lab_project_id', $project->id)->where('uuid', $binding['uuid'] ?? null)->first();
                abort_unless($module !== null && $module->key === ($binding['key'] ?? null), 409, 'Widget module binding is incompatible.');
                $bindings[$module->key] = $module;
            }
            if (array_intersect(array_keys($bindings), ['catalog','orders','manual_payments'])) {
                foreach (LabModuleInstallation::query()->where('lab_project_id', $project->id)->whereIn('key',['catalog','orders','manual_payments'])->where('status','ready')->get() as $module) $bindings[$module->key] = $module;
            }
            $versions = []; $pinned = null;
            foreach ($bindings as $module) {
                abort_unless((int) $module->user_id === (int) $project->user_id && $module->status === 'ready' && $module->schema_version === ModuleCatalog::get($module->key)['schema_version'], 409, 'Apply the required module schema before building.');
                $pinned ??= $module->supabase_project_ref;
                abort_unless($pinned === $module->supabase_project_ref, 409, 'Widgets require the same original datastore.');
                app(ModuleInstallations::class)->plane($module);
                $versions[$module->key] = ['uuid' => $module->uuid, 'schema_version' => $module->schema_version];
            }
            ksort($versions);
            $sources = [];
            foreach (Finder::create()->files()->in($root)->ignoreDotFiles(false)->ignoreVCS(true)->exclude(['node_modules','dist','build','out','.krikkit-tx','.cache','.vite']) as $file) {
                $path = str_replace('\\', '/', $file->getRelativePathname());
                if (!$this->workspace->isWritableRelative($path)) continue;
                abort_if($file->isLink(), 409, 'Managed builds cannot contain linked source files.');
                $sources[$path] = hash_file('sha256', $file->getPathname());
                abort_if(count($sources) > 5000, 413, 'Managed source contains too many files.');
            }
            ksort($sources);
            return ['version' => 1, 'project_uuid' => $project->uuid, 'sdk_version' => ModuleEmbed::SDK_VERSION,
                'runtime_version' => 'krikkit-runtime-v1', 'modules' => $versions, 'source_hash' => hash('sha256', json_encode($sources, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)), 'source_files' => $sources];
        });
    }

    public function verify(LabProject $project, string $directory): void
    {
        $context = $this->context($project);
        if ($context === null) return;
        abort_unless(config('lab-modules.business_writes_enabled') === true, 423, 'Managed widget publishing is awaiting readiness acceptance.');
        $path = $directory.'/'.self::FILE;
        abort_unless(is_file($path) && filesize($path) <= 1024 * 1024, 409, 'The managed build manifest is missing or too large.');
        $artifact = json_decode(file_get_contents($path), true);
        unset($context['source_files']);
        foreach ($context as $key => $value) abort_unless(($artifact[$key] ?? null) === $value, 409, 'Build source, module or schema versions changed. Rebuild before publishing.');
        $expected = $artifact['artifact_files'] ?? null;
        abort_unless(is_array($expected) && count($expected) <= 5000, 409, 'Invalid artifact file manifest.');
        $actual = [];
        foreach (Finder::create()->files()->in($directory)->ignoreDotFiles(false) as $file) {
            $relative = str_replace('\\', '/', $file->getRelativePathname());
            if ($relative !== self::FILE) $actual[$relative] = hash_file('sha256', $file->getPathname());
        }
        ksort($actual); ksort($expected);
        abort_unless($actual === $expected, 409, 'Build artifact contents do not match the manifest.');
    }
}
