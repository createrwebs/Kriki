<?php
    $user?->loadMissing('entitlement.plan');
    $account = [
        ['label' => __('settings.Profile'), 'route' => 'settings.profile', 'icon' => 'user', 'match' => ['settings.profile', 'settings.password', 'settings.two-factor']],
        ['label' => __('settings.General'), 'route' => 'settings.index', 'icon' => 'cog-6-tooth', 'match' => ['settings.index']],
        ['label' => __('settings.Applications'), 'route' => 'settings.applications', 'icon' => 'squares-2x2', 'match' => ['settings.applications']],
    ];
    $workspace = [
        ['label' => __('settings.Subscription'), 'route' => 'settings.subscription', 'icon' => 'credit-card', 'match' => ['settings.subscription']],
        ['label' => __('settings.Credits'), 'route' => 'settings.credits', 'icon' => 'banknotes', 'match' => ['settings.credits']],
        ['label' => __('settings.Usage'), 'route' => 'settings.usage', 'icon' => 'chart-bar', 'match' => ['settings.usage']],
    ];
    $planTitle = $user?->entitlement?->plan?->title ?: __('dashboard.Free');
?>

<aside
    id="krikkit-settings-nav"
    class="fixed inset-y-0 left-0 z-50 flex h-dvh w-[17.5rem] shrink-0 flex-col border-r border-krikkit-line/50 bg-krikkit-canvas max-lg:-translate-x-full lg:sticky lg:top-0 lg:z-auto lg:self-start lg:translate-none"
    x-bind:style="! desktop && navOpen ? 'translate: 0' : null"
    x-bind:inert="! desktop && ! navOpen"
    x-bind:aria-hidden="(! desktop && ! navOpen).toString()"
>
    <div class="flex items-center gap-3 px-5 pb-5 pt-4">
        <a href="<?php echo e(route('home')); ?>" wire:navigate class="flex min-w-0 flex-1 items-center gap-3">
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
            <span class="truncate text-base font-semibold tracking-tight text-krikkit-fg"><?php echo e(__('settings.Settings')); ?></span>
        </a>
        <button
            type="button"
            class="inline-flex size-9 cursor-pointer items-center justify-center text-krikkit-subtle transition hover:text-krikkit-fg lg:hidden"
            x-on:click="closeNav()"
            aria-label="<?php echo e(__('studio.Close navigation')); ?>"
        >
            <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'x','class' => 'size-[18px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'size-[18px]']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2f88b3d15b59738674618f1a73b04e5e)): ?>
<?php $attributes = $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e; ?>
<?php unset($__attributesOriginal2f88b3d15b59738674618f1a73b04e5e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2f88b3d15b59738674618f1a73b04e5e)): ?>
<?php $component = $__componentOriginal2f88b3d15b59738674618f1a73b04e5e; ?>
<?php unset($__componentOriginal2f88b3d15b59738674618f1a73b04e5e); ?>
<?php endif; ?>
        </button>
    </div>

    <nav class="flex min-h-0 flex-1 flex-col overflow-y-auto px-3.5 pb-3 pt-1">
        <a
            href="<?php echo e(route('home')); ?>"
            wire:navigate
            x-on:click="closeNav()"
            class="mb-3 flex items-center gap-3 rounded-md px-2.5 py-2.5 text-sm text-krikkit-muted transition hover:text-krikkit-fg"
        >
            <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'arrow-left','class' => 'size-[18px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-left','class' => 'size-[18px]']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2f88b3d15b59738674618f1a73b04e5e)): ?>
<?php $attributes = $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e; ?>
<?php unset($__attributesOriginal2f88b3d15b59738674618f1a73b04e5e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2f88b3d15b59738674618f1a73b04e5e)): ?>
<?php $component = $__componentOriginal2f88b3d15b59738674618f1a73b04e5e; ?>
<?php unset($__componentOriginal2f88b3d15b59738674618f1a73b04e5e); ?>
<?php endif; ?>
            <span class="truncate"><?php echo e(__('settings.Back to home')); ?></span>
        </a>

        <div class="mx-2.5 mb-4 border-t border-krikkit-line/60"></div>

        <p class="px-2.5 pb-2 text-xs font-medium text-krikkit-subtle"><?php echo e(__('settings.Account')); ?></p>
        <ul class="space-y-0.5">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $account; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php $active = request()->routeIs($item['match']); ?>
                <li>
                    <a
                        href="<?php echo e(route($item['route'])); ?>"
                        wire:navigate
                        x-on:click="closeNav()"
                        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                            'flex items-center gap-3 rounded-md px-2.5 py-2.5 text-sm transition',
                            'text-krikkit-fg' => $active,
                            'text-krikkit-muted hover:text-krikkit-fg' => ! $active,
                        ]); ?>"
                    >
                        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => $item['icon'],'class' => 'size-[18px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon']),'class' => 'size-[18px]']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2f88b3d15b59738674618f1a73b04e5e)): ?>
<?php $attributes = $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e; ?>
<?php unset($__attributesOriginal2f88b3d15b59738674618f1a73b04e5e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2f88b3d15b59738674618f1a73b04e5e)): ?>
<?php $component = $__componentOriginal2f88b3d15b59738674618f1a73b04e5e; ?>
<?php unset($__componentOriginal2f88b3d15b59738674618f1a73b04e5e); ?>
<?php endif; ?>
                        <span class="truncate"><?php echo e($item['label']); ?></span>
                    </a>
                </li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </ul>

        <div class="mx-2.5 mb-4 mt-5 border-t border-krikkit-line/60"></div>

        <p class="px-2.5 pb-2 text-xs font-medium text-krikkit-subtle"><?php echo e(__('settings.Workspace')); ?></p>
        <ul class="space-y-0.5">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $workspace; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php $active = request()->routeIs($item['match']); ?>
                <li>
                    <a
                        href="<?php echo e(route($item['route'])); ?>"
                        wire:navigate
                        x-on:click="closeNav()"
                        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                            'flex items-center gap-3 rounded-md px-2.5 py-2.5 text-sm transition',
                            'text-krikkit-fg' => $active,
                            'text-krikkit-muted hover:text-krikkit-fg' => ! $active,
                        ]); ?>"
                    >
                        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => $item['icon'],'class' => 'size-[18px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon']),'class' => 'size-[18px]']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2f88b3d15b59738674618f1a73b04e5e)): ?>
<?php $attributes = $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e; ?>
<?php unset($__attributesOriginal2f88b3d15b59738674618f1a73b04e5e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2f88b3d15b59738674618f1a73b04e5e)): ?>
<?php $component = $__componentOriginal2f88b3d15b59738674618f1a73b04e5e; ?>
<?php unset($__componentOriginal2f88b3d15b59738674618f1a73b04e5e); ?>
<?php endif; ?>
                        <span class="truncate"><?php echo e($item['label']); ?></span>
                    </a>
                </li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </ul>
    </nav>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user): ?>
        <div class="border-t border-krikkit-line px-3.5 py-3.5">
            <a
                href="<?php echo e(route('settings.profile')); ?>"
                wire:navigate
                x-on:click="closeNav()"
                class="flex w-full items-center gap-3 text-left"
            >
                <?php if (isset($component)) { $__componentOriginal11129def8fa621557df03772d50fb352 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal11129def8fa621557df03772d50fb352 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::avatar','data' => ['name' => $user->name,'src' => $user->avatarUrl(),'size' => 'sm','circle' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->name),'src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->avatarUrl()),'size' => 'sm','circle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal11129def8fa621557df03772d50fb352)): ?>
<?php $attributes = $__attributesOriginal11129def8fa621557df03772d50fb352; ?>
<?php unset($__attributesOriginal11129def8fa621557df03772d50fb352); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11129def8fa621557df03772d50fb352)): ?>
<?php $component = $__componentOriginal11129def8fa621557df03772d50fb352; ?>
<?php unset($__componentOriginal11129def8fa621557df03772d50fb352); ?>
<?php endif; ?>
                <span class="min-w-0 flex-1">
                    <span class="flex items-center gap-2">
                        <span class="truncate text-sm font-medium text-krikkit-fg"><?php echo e($user->name ?: $user->email); ?></span>
                        <span class="shrink-0 text-[11px] uppercase tracking-[0.12em] text-krikkit-subtle"><?php echo e($planTitle); ?></span>
                    </span>
                    <span class="mt-0.5 block truncate text-xs text-krikkit-muted"><?php echo e($user->email); ?></span>
                </span>
            </a>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</aside>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\settings\partials\sidebar.blade.php ENDPATH**/ ?>