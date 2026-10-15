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
                var SW = '/__krikkit_lab_sw__.js?v=host-vite-5173-29';
                navigator.serviceWorker.register(SW, { scope: '/' }).catch(function () {});
            })();
        </script>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/lab/preview/preview.jsx']); ?>
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
        <?php
            $previewBootstrap = [
                'uuid' => $project['uuid'] ?? null,
                'title' => $project['title'] ?? null,
                'path' => $guestPath ?? '/',
                'workspaceUrl' => isset($project['uuid']) ? '/lab/'.$project['uuid'].'/workspace' : '/lab',
            ];
        ?>
        <script type="application/json" id="lab-preview-bootstrap"><?php echo json_encode($previewBootstrap, 15, 512) ?></script>
        <div id="lab-preview-root" class="min-h-dvh bg-krikkit-canvas">
            <div class="flex h-dvh flex-col" role="status" aria-label="<?php echo e(__('dashboard.Loading…')); ?>">
                <header class="flex h-12 shrink-0 items-center gap-2 border-b border-krikkit-line px-4">
                    <span class="truncate text-sm font-semibold tracking-tight text-krikkit-fg">Krikkit</span>
                    <span class="text-krikkit-subtle" aria-hidden>/</span>
                    <span class="text-sm font-medium text-krikkit-fg">Lab</span>
                    <span class="text-krikkit-subtle" aria-hidden>/</span>
                    <span class="text-sm text-krikkit-muted">Preview</span>
                </header>
                <div class="flex min-h-0 flex-1 items-center justify-center">
                    <span class="h-8 w-40 animate-pulse rounded-lg bg-krikkit-soft" aria-hidden></span>
                </div>
            </div>
        </div>
    </body>
</html>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\lab\preview\preview.blade.php ENDPATH**/ ?>