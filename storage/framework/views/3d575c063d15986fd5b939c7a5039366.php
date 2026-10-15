<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['dark' => \App\Support\Ui\ThemePalette::documentIsDark()]); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <style>
            body { margin: 0; }
        </style>
    </head>
    <body class="flex min-h-dvh items-center justify-center bg-krikkit-canvas px-6 text-center text-sm text-krikkit-muted">
        <p><?php echo e($ok ? __('dashboard.Supabase connected. You can close this window.') : $message); ?></p>
        <script>
            (function () {
                var payload = {
                    source: 'krikkit-lab',
                    kind: 'datastore-oauth',
                    ok: <?php echo json_encode((bool) $ok, 15, 512) ?>,
                    message: <?php echo json_encode((string) $message, 15, 512) ?>,
                    t: Date.now(),
                }
                try {
                    if (typeof BroadcastChannel === 'function') {
                        var channel = new BroadcastChannel('krikkit-lab-datastore-oauth')
                        channel.postMessage(payload)
                        channel.close()
                    }
                } catch (e) {}
                try {
                    window.localStorage.setItem('krikkit-lab-datastore-oauth', JSON.stringify(payload))
                } catch (e) {}
                try {
                    if (window.opener && ! window.opener.closed) {
                        window.opener.postMessage(payload, '*')
                    }
                } catch (e) {}
                window.close()
            })()
        </script>
    </body>
</html>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\lab\oauth\popup\popup.blade.php ENDPATH**/ ?>