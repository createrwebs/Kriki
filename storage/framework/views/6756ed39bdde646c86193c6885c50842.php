<?php
    use App\Support\Navigation\WorkspaceNav;

    $user = auth()->user();
    $items = collect(WorkspaceNav::visibleGroups($user))->flatten(1);
?>

<aside
    id="krikkit-dashboard-nav"
    class="fixed inset-y-0 left-0 z-50 flex h-dvh w-60 shrink-0 flex-col border-r border-krikkit-line/50 bg-krikkit-canvas transition-[translate] duration-200 ease-out max-lg:-translate-x-full lg:static lg:z-auto lg:h-full lg:translate-none"
    x-bind:style="! desktop && navOpen ? 'translate: 0' : null"
    x-bind:inert="! desktop && ! navOpen"
    x-bind:aria-hidden="(! desktop && ! navOpen).toString()"
>
    <div class="flex items-center gap-2 px-3.5 pb-5 pt-4">
        <a href="<?php echo e(route('dashboard.home')); ?>" wire:navigate class="group flex min-w-0 flex-1 items-center gap-2.5">
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
            <span class="min-w-0 truncate text-sm font-semibold tracking-tight text-krikkit-fg">
                <?php echo e(app(\App\Support\Site\SiteSettings::class)->name()); ?>

            </span>
        </a>
        <button
            type="button"
            class="inline-flex size-9 cursor-pointer items-center justify-center rounded-full text-krikkit-fg-soft transition hover:bg-krikkit-soft lg:hidden"
            x-on:click="closeNav()"
            aria-label="<?php echo e(__('dashboard.Close navigation')); ?>"
        >
            <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'x','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'size-4']); ?>
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

    <nav class="krikkit-scroll-hover flex-1 overflow-y-auto overscroll-contain px-3 pb-4 pt-6">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($items->isEmpty()): ?>
            <p class="px-2 text-sm text-krikkit-muted"><?php echo e(__('dashboard.No navigation available.')); ?></p>
        <?php else: ?>
            <ul class="space-y-1">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $children = $item['children'] ?? [];
                        $hasChildren = count($children) > 0;
                        $childActive = collect($children)->contains(
                            fn (array $child) => WorkspaceNav::routeIsActive(
                                $child['route'],
                                $child['route_params'] ?? []
                            )
                        );
                        $parentActive = filled($item['route'])
                            && WorkspaceNav::routeIsActive($item['route'])
                            && ! $childActive;
                        $branchActive = $parentActive || $childActive;
                    ?>
                    <li
                        <?php if($hasChildren): ?>
                            x-data="{ open: <?php echo e($childActive ? 'true' : 'false'); ?> }"
                        <?php endif; ?>
                    >
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasChildren): ?>
                            <button
                                type="button"
                                x-on:click="open = ! open"
                                :aria-expanded="open.toString()"
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'group flex w-full cursor-pointer items-center gap-2 rounded-lg px-2.5 py-2 text-sm font-medium transition',
                                    'bg-krikkit-soft text-krikkit-fg' => $branchActive,
                                    'text-krikkit-muted hover:bg-krikkit-soft hover:text-krikkit-fg' => ! $branchActive,
                                ]); ?>"
                            >
                                <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'flex size-4 shrink-0 items-center justify-center transition',
                                    'text-accent-content' => $branchActive,
                                    'text-krikkit-muted group-hover:text-accent-content' => ! $branchActive,
                                ]); ?>">
                                    <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => $item['icon'],'class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon']),'class' => 'size-4']); ?>
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
                                </span>
                                <span class="min-w-0 flex-1 truncate text-left"><?php echo e($item['label']); ?></span>
                                <span
                                    class="inline-flex shrink-0 text-krikkit-muted transition group-hover:text-krikkit-fg"
                                    :class="open ? 'rotate-90' : ''"
                                >
                                    <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'chevron-right','class' => 'size-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right','class' => 'size-3.5']); ?>
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
                                </span>
                            </button>
                            <ul x-show="open" x-cloak class="mt-1 space-y-0.5 pl-8">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php
                                        $childParams = $child['route_params'] ?? [];
                                        $active = WorkspaceNav::routeIsActive($child['route'], $childParams);
                                    ?>
                                    <li>
                                        <a
                                            href="<?php echo e(route($child['route'], $childParams)); ?>"
                                            wire:navigate
                                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                'block cursor-pointer rounded-lg px-2.5 py-1.5 text-[13px] font-medium transition',
                                                'text-krikkit-fg' => $active,
                                                'text-krikkit-muted hover:text-krikkit-fg' => ! $active,
                                            ]); ?>"
                                        >
                                            <?php echo e($child['label']); ?>

                                        </a>
                                    </li>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </ul>
                        <?php else: ?>
                            <?php
                                $active = filled($item['route']) && WorkspaceNav::routeIsActive($item['route']);
                            ?>
                            <a
                                href="<?php echo e(route($item['route'])); ?>"
                                wire:navigate
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'group flex items-center gap-2 rounded-lg px-2.5 py-2 text-sm font-medium transition',
                                    'bg-krikkit-soft text-krikkit-fg' => $active,
                                    'text-krikkit-muted hover:bg-krikkit-soft hover:text-krikkit-fg' => ! $active,
                                ]); ?>"
                            >
                                <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'flex size-4 shrink-0 items-center justify-center transition',
                                    'text-accent-content' => $active,
                                    'text-krikkit-muted group-hover:text-accent-content' => ! $active,
                                ]); ?>">
                                    <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => $item['icon'],'class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon']),'class' => 'size-4']); ?>
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
                                </span>
                                <span class="truncate"><?php echo e($item['label']); ?></span>
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </nav>

    <div class="border-t border-krikkit-line p-3">
        <?php if (isset($component)) { $__componentOriginal768e8fc940702d540fb0480605850979 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal768e8fc940702d540fb0480605850979 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::dropdown','data' => ['position' => 'top','width' => 'full','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['position' => 'top','width' => 'full','class' => 'w-full']); ?>
             <?php $__env->slot('trigger', null, []); ?> 
                <button
                    type="button"
                    class="flex w-full cursor-pointer items-center gap-2.5 rounded-lg px-1.5 py-1.5 text-left transition hover:bg-krikkit-soft"
                    aria-haspopup="menu"
                    :aria-expanded="open.toString()"
                >
                    <?php if (isset($component)) { $__componentOriginal11129def8fa621557df03772d50fb352 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal11129def8fa621557df03772d50fb352 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::avatar','data' => ['name' => $user->name,'src' => $user->avatarUrl(),'size' => 'xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->name),'src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->avatarUrl()),'size' => 'xs']); ?>
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
                        <span class="block truncate text-sm font-medium text-krikkit-fg"><?php echo e($user->name); ?></span>
                        <span class="block truncate text-[11px] text-krikkit-muted"><?php echo e($user->accessRole?->title ?? __('dashboard.Member')); ?></span>
                    </span>
                    <span class="inline-flex shrink-0 text-krikkit-muted" :class="open ? 'rotate-180' : ''">
                        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'chevron-up','class' => 'size-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-up','class' => 'size-3.5']); ?>
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
                    </span>
                </button>
             <?php $__env->endSlot(); ?>
             <?php $__env->slot('menu', null, []); ?> 
                <?php echo $__env->make('components.dashboard.accountMenu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal768e8fc940702d540fb0480605850979)): ?>
<?php $attributes = $__attributesOriginal768e8fc940702d540fb0480605850979; ?>
<?php unset($__attributesOriginal768e8fc940702d540fb0480605850979); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal768e8fc940702d540fb0480605850979)): ?>
<?php $component = $__componentOriginal768e8fc940702d540fb0480605850979; ?>
<?php unset($__componentOriginal768e8fc940702d540fb0480605850979); ?>
<?php endif; ?>
    </div>
</aside>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\components\dashboard\sidebar.blade.php ENDPATH**/ ?>