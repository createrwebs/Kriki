<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'title' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e($documentDir ?? 'ltr'); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['dark' => \App\Support\Ui\ThemePalette::documentIsDark()]); ?>">
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
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
            <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        <?php else: ?>
            <script src="https://cdn.tailwindcss.com"></script>
            <script>tailwind.config = { darkMode: 'class' }</script>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    </head>
    <body class="min-h-screen bg-krikkit-canvas font-sans text-krikkit-fg antialiased">
        <?php echo e($slot); ?>

        <?php if (isset($component)) { $__componentOriginal4f2e38263c3e65df9305882d54a9692e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4f2e38263c3e65df9305882d54a9692e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.cookieBanner','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.cookieBanner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4f2e38263c3e65df9305882d54a9692e)): ?>
<?php $attributes = $__attributesOriginal4f2e38263c3e65df9305882d54a9692e; ?>
<?php unset($__attributesOriginal4f2e38263c3e65df9305882d54a9692e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4f2e38263c3e65df9305882d54a9692e)): ?>
<?php $component = $__componentOriginal4f2e38263c3e65df9305882d54a9692e; ?>
<?php unset($__componentOriginal4f2e38263c3e65df9305882d54a9692e); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal91104845e677e54551961253c3a80058 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91104845e677e54551961253c3a80058 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::toast','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::toast'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91104845e677e54551961253c3a80058)): ?>
<?php $attributes = $__attributesOriginal91104845e677e54551961253c3a80058; ?>
<?php unset($__attributesOriginal91104845e677e54551961253c3a80058); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91104845e677e54551961253c3a80058)): ?>
<?php $component = $__componentOriginal91104845e677e54551961253c3a80058; ?>
<?php unset($__componentOriginal91104845e677e54551961253c3a80058); ?>
<?php endif; ?>
        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    </body>
</html>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views/components/layouts/app.blade.php ENDPATH**/ ?>