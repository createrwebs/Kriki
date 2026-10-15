<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => null,
    'skeleton' => 'page',
    'breadcrumbs' => [],
    'wide' => false,
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
    'skeleton' => 'page',
    'breadcrumbs' => [],
    'wide' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e($documentDir ?? 'ltr'); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['h-full', 'dark' => \App\Support\Ui\ThemePalette::documentIsDark()]); ?>">
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
    <body class="h-full overflow-hidden bg-krikkit-canvas font-sans text-krikkit-fg antialiased">
        <div
            class="flex h-full"
            x-data="{
                navOpen: false,
                desktop: false,
                init() {
                    const mq = window.matchMedia('(min-width: 1024px)')
                    const sync = () => {
                        this.desktop = mq.matches
                        if (this.desktop) this.navOpen = false
                    }
                    sync()
                    mq.addEventListener('change', sync)
                },
                openNav() { this.navOpen = true },
                closeNav() { this.navOpen = false },
                toggleNav() { this.navOpen = ! this.navOpen },
            }"
            x-on:keydown.escape.window="if (navOpen) closeNav()"
            x-on:livewire:navigating.window="closeNav()"
            x-on:krikkit-nav-open.window="openNav()"
            x-on:krikkit-nav-close.window="closeNav()"
            x-on:krikkit-nav-toggle.window="toggleNav()"
        >
            <div
                x-cloak
                x-show="navOpen"
                x-transition.opacity.duration.200ms
                class="fixed inset-0 z-40 bg-black/40 lg:hidden"
                x-on:click="closeNav()"
            ></div>

            <?php if (isset($component)) { $__componentOriginal060abe2a9b4511e378911474e77b046d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal060abe2a9b4511e378911474e77b046d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal060abe2a9b4511e378911474e77b046d)): ?>
<?php $attributes = $__attributesOriginal060abe2a9b4511e378911474e77b046d; ?>
<?php unset($__attributesOriginal060abe2a9b4511e378911474e77b046d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal060abe2a9b4511e378911474e77b046d)): ?>
<?php $component = $__componentOriginal060abe2a9b4511e378911474e77b046d; ?>
<?php unset($__componentOriginal060abe2a9b4511e378911474e77b046d); ?>
<?php endif; ?>

            <div class="flex min-h-0 min-w-0 flex-1 flex-col bg-krikkit-canvas">
                <?php if (isset($component)) { $__componentOriginald3b387f4f5efe74d7afe13be62871c47 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3b387f4f5efe74d7afe13be62871c47 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.header','data' => ['breadcrumbs' => $breadcrumbs]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['breadcrumbs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($breadcrumbs)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3b387f4f5efe74d7afe13be62871c47)): ?>
<?php $attributes = $__attributesOriginald3b387f4f5efe74d7afe13be62871c47; ?>
<?php unset($__attributesOriginald3b387f4f5efe74d7afe13be62871c47); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3b387f4f5efe74d7afe13be62871c47)): ?>
<?php $component = $__componentOriginald3b387f4f5efe74d7afe13be62871c47; ?>
<?php unset($__componentOriginald3b387f4f5efe74d7afe13be62871c47); ?>
<?php endif; ?>

                <main class="krikkit-scroll-hover min-h-0 flex-1 overflow-y-auto bg-krikkit-canvas px-4 py-6 sm:px-5 sm:py-8 lg:px-8">
                    <div
                        class="relative mx-auto <?php echo e($wide ? 'max-w-6xl' : 'max-w-5xl'); ?>"
                        x-data="krikkitPageLoad"
                        x-on:livewire:navigating.window="start()"
                        x-on:livewire:navigated.window="stop()"
                        x-on:livewire:navigate-error.window="stop()"
                    >
                        <div
                            x-show="loading"
                            x-cloak
                            class="absolute inset-0 z-10 bg-krikkit-canvas"
                            :style="minHeight ? `min-height: ${minHeight}px` : null"
                        >
                            <?php if (isset($component)) { $__componentOriginaldde57c7410f28c2710dc2e4de9145205 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldde57c7410f28c2710dc2e4de9145205 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.pageSkeleton','data' => ['variant' => $skeleton]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.pageSkeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($skeleton)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldde57c7410f28c2710dc2e4de9145205)): ?>
<?php $attributes = $__attributesOriginaldde57c7410f28c2710dc2e4de9145205; ?>
<?php unset($__attributesOriginaldde57c7410f28c2710dc2e4de9145205); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldde57c7410f28c2710dc2e4de9145205)): ?>
<?php $component = $__componentOriginaldde57c7410f28c2710dc2e4de9145205; ?>
<?php unset($__componentOriginaldde57c7410f28c2710dc2e4de9145205); ?>
<?php endif; ?>
                        </div>

                        
                        <div
                            x-ref="page"
                            :class="loading ? 'invisible pointer-events-none' : ''"
                            :aria-busy="loading ? 'true' : 'false'"
                        >
                            <?php echo e($slot); ?>

                        </div>
                    </div>
                </main>
            </div>
        </div>

        <?php if (isset($component)) { $__componentOriginal6e33faefb51ffe4db47ab742359f9d35 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6e33faefb51ffe4db47ab742359f9d35 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command','data' => ['placeholder' => ''.e(__('dashboard.Jump to…')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => ''.e(__('dashboard.Jump to…')).'']); ?>
            <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'overview home dashboard','href' => route('dashboard.home')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'overview home dashboard','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.home'))]); ?><?php echo e(__('dashboard.Overview')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('allows', 'users.browse')): ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'users directory','href' => route('dashboard.users.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'users directory','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.users.index'))]); ?><?php echo e(__('dashboard.Users')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('allows', 'roles.browse')): ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'roles access','href' => route('dashboard.roles.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'roles access','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.roles.index'))]); ?><?php echo e(__('dashboard.Roles')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('allows', 'projects.browse')): ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'lab projects','href' => route('dashboard.lab.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'lab projects','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.lab.index'))]); ?><?php echo e(__('dashboard.Lab')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'lab usage credits model','href' => route('dashboard.lab.usage')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'lab usage credits model','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.lab.usage'))]); ?><?php echo e(__('dashboard.Usage')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'lab failed stuck turns','href' => route('dashboard.lab.turns')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'lab failed stuck turns','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.lab.turns'))]); ?><?php echo e(__('dashboard.Failed turns')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('allows', 'packs.browse')): ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'plan billing pack quota subscription','href' => route('dashboard.packs.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'plan billing pack quota subscription','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.packs.index'))]); ?><?php echo e(__('dashboard.Packs')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('allows', 'finance.browse')): ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'credits grant top-up','href' => route('dashboard.credits.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'credits grant top-up','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.credits.index'))]); ?><?php echo e(__('dashboard.Credits')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'finance mrr invoices subscriptions credits billing revenue','href' => route('dashboard.finance.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'finance mrr invoices subscriptions credits billing revenue','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.finance.index'))]); ?><?php echo e(__('dashboard.Finance')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'subscriptions packs billing','href' => route('dashboard.finance.subscriptions.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'subscriptions packs billing','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.finance.subscriptions.index'))]); ?><?php echo e(__('dashboard.Subscriptions')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'invoices refund stripe paypal','href' => route('dashboard.invoices.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'invoices refund stripe paypal','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.invoices.index'))]); ?><?php echo e(__('dashboard.Invoices')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'webhooks events stripe paypal','href' => route('dashboard.finance.events.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'webhooks events stripe paypal','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.finance.events.index'))]); ?><?php echo e(__('dashboard.Events')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('allows', 'payments.revise')): ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'payments payment methods stripe paypal checkout keys','href' => route('dashboard.payments.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'payments payment methods stripe paypal checkout keys','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.payments.index'))]); ?><?php echo e(__('dashboard.Payment Methods')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('allows', 'languages.browse')): ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'languages locale translate i18n','href' => route('dashboard.languages.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'languages locale translate i18n','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.languages.index'))]); ?><?php echo e(__('dashboard.Languages')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('allows', 'ai.revise')): ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'api integration ai models providers keys cost estimator github token unsplash pixabay photographs','href' => route('dashboard.integration.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'api integration ai models providers keys cost estimator github token unsplash pixabay photographs','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.integration.index'))]); ?><?php echo e(__('dashboard.API Integration')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('allows', 'sessions.browse')): ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'sessions logins devices activity','href' => route('dashboard.sessions.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'sessions logins devices activity','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.sessions.index'))]); ?><?php echo e(__('dashboard.Sessions')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('allows', 'contacts.browse')): ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'contacts inbox messages agency talk to us','href' => route('dashboard.contacts.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'contacts inbox messages agency talk to us','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.contacts.index'))]); ?><?php echo e(__('dashboard.Contacts')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('allows', 'settings.revise')): ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'landing home page hero footer copy buttons images','href' => route('dashboard.landing.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'landing home page hero footer copy buttons images','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.landing.index'))]); ?><?php echo e(__('dashboard.Landing page')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'newsletter subscribers email list footer','href' => route('dashboard.newsletter.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'newsletter subscribers email list footer','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.newsletter.index'))]); ?><?php echo e(__('dashboard.Newsletter')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'seo meta analytics','href' => route('dashboard.seo.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'seo meta analytics','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.seo.index'))]); ?><?php echo e(__('dashboard.SEO')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'settings general brand logo favicon','href' => route('dashboard.settings.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'settings general brand logo favicon','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.settings.index'))]); ?><?php echo e(__('dashboard.Settings')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'themes colors accent base customize','href' => route('dashboard.settings.themes')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'themes colors accent base customize','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.settings.themes'))]); ?><?php echo e(__('dashboard.Themes')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'mail smtp from email','href' => route('dashboard.settings.mail')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'mail smtp from email','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.settings.mail'))]); ?><?php echo e(__('dashboard.Mail')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'license key purchase code verify','href' => route('dashboard.settings.license')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'license key purchase code verify','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.settings.license'))]); ?><?php echo e(__('dashboard.License')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'publish lab subdomain custom domain wildcard','href' => route('dashboard.settings.publish')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'publish lab subdomain custom domain wildcard','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.settings.publish'))]); ?><?php echo e(__('dashboard.Publish settings')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'lab console deepthought runtime terminal package.json autostart','href' => route('dashboard.settings.lab')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'lab console deepthought runtime terminal package.json autostart','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.settings.lab'))]); ?><?php echo e(__('dashboard.Lab console')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'gdpr cookies consent','href' => route('dashboard.settings.gdpr')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'gdpr cookies consent','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.settings.gdpr'))]); ?><?php echo e(__('dashboard.GDPR')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'privacy policy','href' => route('dashboard.settings.privacy')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'privacy policy','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.settings.privacy'))]); ?><?php echo e(__('dashboard.Privacy policy')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'terms of use legal','href' => route('dashboard.settings.terms')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'terms of use legal','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.settings.terms'))]); ?><?php echo e(__('dashboard.Terms')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('allows', 'security.self')): ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'profile account name avatar','href' => route('dashboard.profile.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'profile account name avatar','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.profile.index'))]); ?><?php echo e(__('dashboard.Profile')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'password security','href' => route('dashboard.profile.password')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'password security','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.profile.password'))]); ?><?php echo e(__('dashboard.Password')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::command.item','data' => ['keywords' => 'two factor 2fa security','href' => route('dashboard.profile.two-factor')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::command.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['keywords' => 'two factor 2fa security','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard.profile.two-factor'))]); ?><?php echo e(__('dashboard.Two-factor')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $attributes = $__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__attributesOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b)): ?>
<?php $component = $__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b; ?>
<?php unset($__componentOriginal0a4da2d3aa9ca99a315435deb1f2ce5b); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6e33faefb51ffe4db47ab742359f9d35)): ?>
<?php $attributes = $__attributesOriginal6e33faefb51ffe4db47ab742359f9d35; ?>
<?php unset($__attributesOriginal6e33faefb51ffe4db47ab742359f9d35); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6e33faefb51ffe4db47ab742359f9d35)): ?>
<?php $component = $__componentOriginal6e33faefb51ffe4db47ab742359f9d35; ?>
<?php unset($__componentOriginal6e33faefb51ffe4db47ab742359f9d35); ?>
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
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\components\layouts\dashboard.blade.php ENDPATH**/ ?>