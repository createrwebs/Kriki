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

<?php
    $site = app(\App\Support\Site\SiteSettings::class);
    $status = session('status');
    $statusCopy = match ($status) {
        'verification-link-sent' => __('messages.A new verification link has been sent.'),
        default => is_string($status) && $status !== '' ? $status : null,
    };
    $tagline = $site->tagline();
    if ($tagline === '') {
        $tagline = __('messages.From a brief to a living site.');
    }
?>

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
        <div class="grid min-h-screen lg:grid-cols-[minmax(20rem,2fr)_minmax(26rem,3fr)]">
            <aside class="relative hidden flex-col justify-between border-r border-krikkit-line bg-krikkit-surface px-10 py-10 lg:flex xl:px-14">
                <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-3">
                    <?php if (isset($component)) { $__componentOriginal956824a8769013cddb9947cefd34cfad = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal956824a8769013cddb9947cefd34cfad = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.mark','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.mark'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal956824a8769013cddb9947cefd34cfad)): ?>
<?php $attributes = $__attributesOriginal956824a8769013cddb9947cefd34cfad; ?>
<?php unset($__attributesOriginal956824a8769013cddb9947cefd34cfad); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal956824a8769013cddb9947cefd34cfad)): ?>
<?php $component = $__componentOriginal956824a8769013cddb9947cefd34cfad; ?>
<?php unset($__componentOriginal956824a8769013cddb9947cefd34cfad); ?>
<?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($site->logoUrl())): ?>
                        <span class="text-sm font-semibold tracking-tight text-krikkit-fg"><?php echo e($site->name()); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </a>

                <div class="max-w-sm">
                    <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e($site->name()); ?></p>
                    <p class="mt-4 text-3xl font-medium leading-tight tracking-tight text-krikkit-fg xl:text-4xl"><?php echo e($tagline); ?></p>
                    <span class="mt-8 block h-px w-10 bg-accent" aria-hidden="true"></span>
                </div>

                <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-krikkit-subtle">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($site->privacyPublished()): ?>
                        <a href="<?php echo e(route('privacy')); ?>" class="transition hover:text-krikkit-fg"><?php echo e(__('messages.Privacy policy')); ?></a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($site->termsPublished()): ?>
                        <a href="<?php echo e(route('terms')); ?>" class="transition hover:text-krikkit-fg"><?php echo e(__('messages.Terms of use')); ?></a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </aside>

            <div class="flex min-h-screen flex-col">
                <main class="flex flex-1 flex-col items-center justify-center px-5 py-12 sm:px-8">
                    <div class="w-full max-w-[22rem]">
                        <a href="<?php echo e(route('home')); ?>" class="mb-8 flex items-center gap-2.5 lg:hidden">
                            <?php if (isset($component)) { $__componentOriginal956824a8769013cddb9947cefd34cfad = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal956824a8769013cddb9947cefd34cfad = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.mark','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.mark'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal956824a8769013cddb9947cefd34cfad)): ?>
<?php $attributes = $__attributesOriginal956824a8769013cddb9947cefd34cfad; ?>
<?php unset($__attributesOriginal956824a8769013cddb9947cefd34cfad); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal956824a8769013cddb9947cefd34cfad)): ?>
<?php $component = $__componentOriginal956824a8769013cddb9947cefd34cfad; ?>
<?php unset($__componentOriginal956824a8769013cddb9947cefd34cfad); ?>
<?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($site->logoUrl())): ?>
                                <span class="truncate text-sm font-semibold tracking-tight text-krikkit-fg"><?php echo e($site->name()); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </a>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($statusCopy): ?>
                            <?php if (isset($component)) { $__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::callout','data' => ['tone' => 'success','class' => 'mb-6','icon' => 'check-circle']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::callout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'success','class' => 'mb-6','icon' => 'check-circle']); ?>
                                <?php if (isset($component)) { $__componentOriginal617e293475666fd5160886d17e8563d4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal617e293475666fd5160886d17e8563d4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::callout.text','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::callout.text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e($statusCopy); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal617e293475666fd5160886d17e8563d4)): ?>
<?php $attributes = $__attributesOriginal617e293475666fd5160886d17e8563d4; ?>
<?php unset($__attributesOriginal617e293475666fd5160886d17e8563d4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal617e293475666fd5160886d17e8563d4)): ?>
<?php $component = $__componentOriginal617e293475666fd5160886d17e8563d4; ?>
<?php unset($__componentOriginal617e293475666fd5160886d17e8563d4); ?>
<?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05)): ?>
<?php $attributes = $__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05; ?>
<?php unset($__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05)): ?>
<?php $component = $__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05; ?>
<?php unset($__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05); ?>
<?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php echo e($slot); ?>

                    </div>
                </main>
            </div>
        </div>
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
<?php /**PATH D:\codea\krikkit-ai-website\resources\views/components/layouts/guest.blade.php ENDPATH**/ ?>