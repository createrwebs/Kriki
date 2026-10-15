<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e($documentDir ?? 'ltr'); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['dark' => \App\Support\Ui\ThemePalette::documentIsDark()]); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <?php if (isset($component)) { $__componentOriginalf6aca4facd77f4571970930d2bda5ef7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf6aca4facd77f4571970930d2bda5ef7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.partials.themeBoot','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.partials.themeBoot'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf6aca4facd77f4571970930d2bda5ef7)): ?>
<?php $attributes = $__attributesOriginalf6aca4facd77f4571970930d2bda5ef7; ?>
<?php unset($__attributesOriginalf6aca4facd77f4571970930d2bda5ef7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf6aca4facd77f4571970930d2bda5ef7)): ?>
<?php $component = $__componentOriginalf6aca4facd77f4571970930d2bda5ef7; ?>
<?php unset($__componentOriginalf6aca4facd77f4571970930d2bda5ef7); ?>
<?php endif; ?>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=sora:400,500,600,700&display=swap" rel="stylesheet" />
        <?php if (isset($component)) { $__componentOriginalb4310b2bf4c6b97ee5cbfb4533e94064 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb4310b2bf4c6b97ee5cbfb4533e94064 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.partials.brand','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.partials.brand'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb4310b2bf4c6b97ee5cbfb4533e94064)): ?>
<?php $attributes = $__attributesOriginalb4310b2bf4c6b97ee5cbfb4533e94064; ?>
<?php unset($__attributesOriginalb4310b2bf4c6b97ee5cbfb4533e94064); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb4310b2bf4c6b97ee5cbfb4533e94064)): ?>
<?php $component = $__componentOriginalb4310b2bf4c6b97ee5cbfb4533e94064; ?>
<?php unset($__componentOriginalb4310b2bf4c6b97ee5cbfb4533e94064); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal2d87f67cf40ec64138dcedbd77d428d4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2d87f67cf40ec64138dcedbd77d428d4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.partials.seo','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.partials.seo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2d87f67cf40ec64138dcedbd77d428d4)): ?>
<?php $attributes = $__attributesOriginal2d87f67cf40ec64138dcedbd77d428d4; ?>
<?php unset($__attributesOriginal2d87f67cf40ec64138dcedbd77d428d4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2d87f67cf40ec64138dcedbd77d428d4)): ?>
<?php $component = $__componentOriginal2d87f67cf40ec64138dcedbd77d428d4; ?>
<?php unset($__componentOriginal2d87f67cf40ec64138dcedbd77d428d4); ?>
<?php endif; ?>
        
        <script type="text/javascript">
            (function () {
                if (!('serviceWorker' in navigator)) return;
                var VER = '29';
                var SW = '/__krikkit_lab_sw__.js?v=host-vite-5173-29';
                var post = function (sw) {
                    try { sw.postMessage({ type: 'krikkit-host-active' }) } catch (e) {}
                }
                if (navigator.serviceWorker.controller) post(navigator.serviceWorker.controller)
                navigator.serviceWorker.register(SW, { scope: '/' }).then(function (reg) {
                    if (reg.active) post(reg.active)
                    var installing = reg.installing
                    if (!installing) return
                    installing.addEventListener('statechange', function () {
                        if (this.state !== 'activated') return
                        try {
                            if (sessionStorage.getItem('krikkit-sw') === VER) return
                            sessionStorage.setItem('krikkit-sw', VER)
                        } catch (e) {}
                        location.reload()
                    })
                }).catch(function () {})
                navigator.serviceWorker.ready.then(function (reg) {
                    if (reg.active) post(reg.active)
                }).catch(function () {})
            })()
        </script>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/lab/lab.jsx']); ?>
        <script type="text/javascript">
            (function () {
                if (!('serviceWorker' in navigator)) return;
                var origin = null;
                try {
                    var tag = document.querySelector('script[src*="/@vite/client"]')
                    if (tag && tag.src) origin = new URL(tag.src).origin
                } catch (e) {}
                var post = function (sw) {
                    try {
                        if (origin) sw.postMessage({ type: 'krikkit-host-vite', origin: origin })
                    } catch (e) {}
                }
                if (navigator.serviceWorker.controller) post(navigator.serviceWorker.controller)
                navigator.serviceWorker.ready.then(function (reg) {
                    if (reg.active) post(reg.active)
                }).catch(function () {})
            })()
        </script>
        <?php if (isset($component)) { $__componentOriginal7535147e3c68f35abc03824f14d5620f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7535147e3c68f35abc03824f14d5620f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.partials.themeStyle','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.partials.themeStyle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7535147e3c68f35abc03824f14d5620f)): ?>
<?php $attributes = $__attributesOriginal7535147e3c68f35abc03824f14d5620f; ?>
<?php unset($__attributesOriginal7535147e3c68f35abc03824f14d5620f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7535147e3c68f35abc03824f14d5620f)): ?>
<?php $component = $__componentOriginal7535147e3c68f35abc03824f14d5620f; ?>
<?php unset($__componentOriginal7535147e3c68f35abc03824f14d5620f); ?>
<?php endif; ?>
    </head>
    <body class="min-h-screen bg-krikkit-canvas font-sans text-krikkit-fg antialiased">
        
        <script type="application/json" id="lab-bootstrap"><?php echo json_encode($project, 15, 512) ?></script>
        <script type="application/json" id="lab-workspace"><?php echo json_encode((bool) ($workspace ?? false), 15, 512) ?></script>
        <script type="application/json" id="lab-prefs"><?php echo json_encode(\App\Support\Account\DeskPreferences::for(auth()->user()), 15, 512) ?></script>
        <script type="application/json" id="lab-pulse"><?php echo json_encode(\App\Support\Ui\Pulse::pending(), 15, 512) ?></script>
        <?php
            $labConfig = [
                // Import UI is always on (public URL import). Connect GitHub still needs OAuth.
                'github_import' => true,
                'github_oauth' => app(\App\Integrations\Github\GithubLinkBroker::class)->isOauthReady(),
                'auto_switch_gate' => (bool) config('lab.auto_switch_gate', true),
                'auto_switch_gate_ms' => (int) config('lab.auto_switch_gate_ms', 15_000),
                'entitlement' => $entitlementSnapshot,
                'console' => app(\App\Support\Site\SiteSettings::class)->labConsole(),
                'i18n' => [
                    'close' => __('dashboard.Close'),
                    'chooseAnAccount' => __('dashboard.Choose an account'),
                    'connectGithub' => __('dashboard.Connect GitHub'),
                    'connectGithubHint' => __('dashboard.Import repositories from your GitHub account.'),
                    'disconnect' => __('dashboard.Disconnect'),
                    'enterPublicGithubUrl' => __('dashboard.Enter a public GitHub repository URL.'),
                    'githubAccount' => __('dashboard.GitHub account'),
                    'githubUnavailable' => __('dashboard.GitHub connection is unavailable.'),
                    'import' => __('dashboard.Import'),
                    'importFromAUrl' => __('dashboard.Import from a URL'),
                    'importFromGithub' => __('dashboard.Import from GitHub'),
                    'importing' => __('dashboard.Importing…'),
                    'loading' => __('dashboard.Loading…'),
                    'noGithubAccount' => __('dashboard.No GitHub account connected.'),
                    'noRepositoriesFound' => __('dashboard.No repositories found.'),
                    'repositoryUrlPlaceholder' => __('dashboard.https://github.com/owner/repo'),
                    'searchRepositories' => __('dashboard.Search repositories…'),
                    'selectARepository' => __('dashboard.Select a Repository'),
                    'branch' => __('dashboard.Branch'),
                    'rootDirectory' => __('dashboard.Root directory'),
                    'rootDirectoryPlaceholder' => __('dashboard.apps/web'),
                    'firstPrompt' => __('dashboard.First prompt'),
                    'firstPromptPlaceholder' => __('dashboard.What should Lab build from this repository?'),
                    'optional' => __('dashboard.optional'),
                    'githubDesk' => __('dashboard.GitHub'),
                    'githubPush' => __('dashboard.Push'),
                    'githubPull' => __('dashboard.Pull'),
                    'githubCompare' => __('dashboard.Tree vs'),
                    'githubFork' => __('dashboard.Fork'),
                    'githubLink' => __('dashboard.Link'),
                    'githubCreate' => __('dashboard.Create'),
                    'githubUnlink' => __('dashboard.Unlink'),
                    'githubCommitMessage' => __('dashboard.Commit message'),
                    'githubForcePush' => __('dashboard.Force push if the branch diverged'),
                    'githubCreateAndPush' => __('dashboard.Create repo and push'),
                    'githubForkImport' => __('dashboard.Fork and import'),
                    'githubLinkRepo' => __('dashboard.Link repository'),
                    'githubRepoName' => __('dashboard.Repository name'),
                    'githubWaitConnect' => __('dashboard.Chat is waiting for GitHub to be connected.'),
                    'githubWaitRemote' => __('dashboard.Chat is waiting for a GitHub repository on this project.'),
                    'githubClean' => __('dashboard.Local matches GitHub.'),
                    'githubAdded' => __('dashboard.Added'),
                    'githubModified' => __('dashboard.Modified'),
                    'githubRemoved' => __('dashboard.Removed'),
                    'githubNeedProject' => __('dashboard.Open a Lab project first, then connect GitHub.'),
                ],
            ];
        ?>
        <script type="application/json" id="lab-config"><?php echo json_encode($labConfig, 15, 512) ?></script>
        <div id="lab-root">
            
            <div class="relative flex h-dvh flex-col overflow-hidden bg-krikkit-canvas" role="status" aria-label="<?php echo e(__('dashboard.Loading…')); ?>">
                <header class="lab-app-header flex h-14 shrink-0 items-stretch overflow-hidden border-b border-krikkit-line bg-krikkit-canvas">
                    <div class="flex w-full min-w-0 flex-[1_1_0%] items-center px-4 sm:px-6">
                        <span class="truncate text-sm font-semibold tracking-tight text-krikkit-fg">Krikkit</span>
                        <span class="mx-2 shrink-0 text-krikkit-subtle" aria-hidden>/</span>
                        <span class="shrink-0 text-sm font-medium text-krikkit-fg">Lab</span>
                        <div class="ml-auto flex shrink-0 items-center gap-3 pl-3">
                            <span class="inline-flex h-8 w-8 animate-pulse rounded-lg bg-krikkit-soft" aria-hidden></span>
                        </div>
                    </div>
                </header>

                <main class="flex min-h-0 flex-1 flex-row overflow-hidden">
                    <div class="relative flex w-full min-h-0 min-w-0 flex-[1_1_0%] flex-col overflow-hidden">
                        <div class="min-h-[10vh] flex-[1_1_0%]" aria-hidden></div>

                        
                        <div class="mx-auto mb-8 w-full max-w-2xl px-4 sm:px-6">
                            <div class="flex flex-col items-center text-center">
                                <span class="mb-3 h-3 w-8 animate-pulse rounded-full bg-krikkit-soft" aria-hidden></span>
                                <span class="mb-3 h-9 w-[17rem] max-w-full animate-pulse rounded-lg bg-krikkit-soft sm:h-10 sm:w-[22rem]" aria-hidden></span>
                                <span class="mx-auto h-4 w-full max-w-md animate-pulse rounded-md bg-krikkit-soft" aria-hidden></span>
                                <span class="mx-auto mt-2 h-4 w-4/5 max-w-sm animate-pulse rounded-md bg-krikkit-soft" aria-hidden></span>
                            </div>
                        </div>

                        
                        <div class="relative mx-auto w-full min-w-0 max-w-2xl shrink-0 px-4 sm:px-6">
                            <div class="rounded-2xl bg-krikkit-surface p-3.5 sm:p-4">
                                <div class="min-h-[72px] w-full">
                                    <span class="mt-1 block h-3.5 w-3/4 max-w-md animate-pulse rounded-md bg-krikkit-soft" aria-hidden></span>
                                </div>
                                <div class="mt-2 flex items-center justify-between gap-3">
                                    <span class="inline-flex h-9 w-9 animate-pulse rounded-full bg-krikkit-soft" aria-hidden></span>
                                    <span class="inline-flex h-9 w-9 animate-pulse rounded-full bg-krikkit-soft" aria-hidden></span>
                                </div>
                            </div>
                        </div>

                        
                        <div class="mx-auto mt-6 w-full max-w-2xl px-4 sm:px-6">
                            <div class="flex w-full justify-center">
                                <div class="flex w-max max-w-full flex-nowrap items-center gap-2 overflow-hidden">
                                    <span class="inline-flex h-8 shrink-0 items-center gap-2 rounded-full border border-krikkit-line px-3.5">
                                        <span class="size-3.5 animate-pulse rounded bg-krikkit-soft" aria-hidden></span>
                                        <span class="h-2.5 w-14 animate-pulse rounded-full bg-krikkit-soft" aria-hidden></span>
                                    </span>
                                    <span class="inline-flex h-8 shrink-0 items-center gap-2 rounded-full border border-krikkit-line px-3.5">
                                        <span class="size-3.5 animate-pulse rounded bg-krikkit-soft" aria-hidden></span>
                                        <span class="h-2.5 w-16 animate-pulse rounded-full bg-krikkit-soft" aria-hidden></span>
                                    </span>
                                    <span class="inline-flex h-8 shrink-0 items-center gap-2 rounded-full border border-krikkit-line px-3.5">
                                        <span class="size-3.5 animate-pulse rounded bg-krikkit-soft" aria-hidden></span>
                                        <span class="h-2.5 w-12 animate-pulse rounded-full bg-krikkit-soft" aria-hidden></span>
                                    </span>
                                    <span class="inline-flex h-8 shrink-0 items-center gap-2 rounded-full border border-krikkit-line px-3.5">
                                        <span class="size-3.5 animate-pulse rounded bg-krikkit-soft" aria-hidden></span>
                                        <span class="h-2.5 w-14 animate-pulse rounded-full bg-krikkit-soft" aria-hidden></span>
                                    </span>
                                    <span class="inline-flex h-8 w-8 shrink-0 animate-pulse rounded-full border border-krikkit-line bg-krikkit-soft" aria-hidden></span>
                                </div>
                            </div>
                        </div>

                        <div class="min-h-[10vh] flex-[1_1_0%]" aria-hidden></div>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views/lab/lab.blade.php ENDPATH**/ ?>