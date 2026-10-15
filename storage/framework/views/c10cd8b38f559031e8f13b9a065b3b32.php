<div class="space-y-8">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div class="min-w-0">
            <h1 class="text-lg font-semibold tracking-tight text-krikkit-fg"><?php echo e(__('dashboard.Users')); ?></h1>
            <p class="mt-0.5 text-xs text-krikkit-muted"><?php echo e(__('dashboard.People in this workspace and their access role.')); ?></p>
        </div>
        <div class="flex shrink-0 items-center gap-2">
            <div
                x-data="{
                    open: false,
                    expand() {
                        this.open = true
                        this.$nextTick(() => this.$refs.query?.focus())
                    },
                    collapse() {
                        this.$refs.query?.blur()
                        this.open = false
                        if ($wire.search !== '') {
                            $wire.set('search', '')
                        }
                    },
                }"
                x-on:click.outside="if (open) collapse()"
                x-on:keydown.escape.window="if (open) collapse()"
                class="flex items-center"
            >
                <div
                    class="flex h-8 items-center overflow-hidden rounded-full border transition-[width,border-color,background-color] duration-300 ease-out"
                    :class="open
                        ? 'w-56 border-krikkit-line/50 bg-krikkit-surface'
                        : 'w-8 border-transparent bg-transparent'"
                >
                    <button
                        type="button"
                        class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-krikkit-fg-soft transition hover:bg-krikkit-soft hover:text-krikkit-fg"
                        x-on:click.stop="open ? $refs.query?.focus() : expand()"
                        aria-label="<?php echo e(__('dashboard.Search')); ?>"
                        title="<?php echo e(__('dashboard.Search')); ?>"
                    >
                        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'search','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'size-4']); ?>
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
                    <input
                        x-ref="query"
                        type="text"
                        inputmode="search"
                        autocomplete="off"
                        wire:model.live.debounce.250ms="search"
                        placeholder="<?php echo e(__('dashboard.Search…')); ?>"
                        class="h-8 min-w-0 flex-1 bg-transparent pr-2 text-sm leading-5 text-krikkit-fg outline-none placeholder:text-krikkit-subtle"
                        :tabindex="open ? 0 : -1"
                        x-bind:aria-hidden="(! open).toString()"
                    />
                    <button
                        type="button"
                        class="mr-1 inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-krikkit-subtle transition hover:bg-krikkit-soft hover:text-krikkit-fg"
                        x-show="open"
                        x-cloak
                        x-on:click.stop="collapse()"
                        aria-label="<?php echo e(__('dashboard.Clear')); ?>"
                        title="<?php echo e(__('dashboard.Clear')); ?>"
                    >
                        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'x','class' => 'size-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'size-3.5']); ?>
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
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($directoryHasUsers): ?>
                <?php if (isset($component)) { $__componentOriginal768e8fc940702d540fb0480605850979 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal768e8fc940702d540fb0480605850979 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::dropdown','data' => ['align' => 'end']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'end']); ?>
                     <?php $__env->slot('trigger', null, []); ?> 
                        <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['type' => 'button','variant' => 'ghost','square' => true,'size' => 'sm','ariaLabel' => ''.e(__('dashboard.Export')).'','title' => ''.e(__('dashboard.Export')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'ghost','square' => true,'size' => 'sm','aria-label' => ''.e(__('dashboard.Export')).'','title' => ''.e(__('dashboard.Export')).'']); ?>
                            <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'arrow-down-tray','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-down-tray','class' => 'size-4']); ?>
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
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $attributes = $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $component = $__componentOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
                     <?php $__env->endSlot(); ?>
                     <?php $__env->slot('menu', null, []); ?> 
                        <?php if (isset($component)) { $__componentOriginal5dc871dce4923f548320c53d3ad5baf1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dc871dce4923f548320c53d3ad5baf1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::dropdown.item','data' => ['wire:click' => 'export(\'csv\')','xOn:click' => 'open = false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::dropdown.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'export(\'csv\')','x-on:click' => 'open = false']); ?>
                            <?php echo e(__('dashboard.CSV')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dc871dce4923f548320c53d3ad5baf1)): ?>
<?php $attributes = $__attributesOriginal5dc871dce4923f548320c53d3ad5baf1; ?>
<?php unset($__attributesOriginal5dc871dce4923f548320c53d3ad5baf1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dc871dce4923f548320c53d3ad5baf1)): ?>
<?php $component = $__componentOriginal5dc871dce4923f548320c53d3ad5baf1; ?>
<?php unset($__componentOriginal5dc871dce4923f548320c53d3ad5baf1); ?>
<?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canAdvancedExport): ?>
                            <?php if (isset($component)) { $__componentOriginal5dc871dce4923f548320c53d3ad5baf1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dc871dce4923f548320c53d3ad5baf1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::dropdown.item','data' => ['wire:click' => 'export(\'tsv\')','xOn:click' => 'open = false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::dropdown.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'export(\'tsv\')','x-on:click' => 'open = false']); ?>
                                <?php echo e(__('dashboard.TSV')); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dc871dce4923f548320c53d3ad5baf1)): ?>
<?php $attributes = $__attributesOriginal5dc871dce4923f548320c53d3ad5baf1; ?>
<?php unset($__attributesOriginal5dc871dce4923f548320c53d3ad5baf1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dc871dce4923f548320c53d3ad5baf1)): ?>
<?php $component = $__componentOriginal5dc871dce4923f548320c53d3ad5baf1; ?>
<?php unset($__componentOriginal5dc871dce4923f548320c53d3ad5baf1); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal5dc871dce4923f548320c53d3ad5baf1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dc871dce4923f548320c53d3ad5baf1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::dropdown.item','data' => ['wire:click' => 'export(\'json\')','xOn:click' => 'open = false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::dropdown.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'export(\'json\')','x-on:click' => 'open = false']); ?>
                                <?php echo e(__('dashboard.JSON')); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dc871dce4923f548320c53d3ad5baf1)): ?>
<?php $attributes = $__attributesOriginal5dc871dce4923f548320c53d3ad5baf1; ?>
<?php unset($__attributesOriginal5dc871dce4923f548320c53d3ad5baf1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dc871dce4923f548320c53d3ad5baf1)): ?>
<?php $component = $__componentOriginal5dc871dce4923f548320c53d3ad5baf1; ?>
<?php unset($__componentOriginal5dc871dce4923f548320c53d3ad5baf1); ?>
<?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('allows', 'users.compose')): ?>
                <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['href' => ''.e(route('dashboard.users.create')).'','variant' => 'ghost','square' => true,'size' => 'sm','ariaLabel' => ''.e(__('dashboard.New user')).'','title' => ''.e(__('dashboard.New user')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('dashboard.users.create')).'','variant' => 'ghost','square' => true,'size' => 'sm','aria-label' => ''.e(__('dashboard.New user')).'','title' => ''.e(__('dashboard.New user')).'']); ?>
                    <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'plus','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'plus','class' => 'size-4']); ?>
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
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $attributes = $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $component = $__componentOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($users->isEmpty()): ?>
        <p class="text-sm text-krikkit-muted">
            <?php echo e(filled($search) ? __('dashboard.No results.') : __('dashboard.No users yet.')); ?>

        </p>
    <?php else: ?>
        <?php if (isset($component)) { $__componentOriginald9f9621c9792e26cb0a786db19dbfd95 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald9f9621c9792e26cb0a786db19dbfd95 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <?php if (isset($component)) { $__componentOriginal8ea4f77adc03babec838eacd9b78df96 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ea4f77adc03babec838eacd9b78df96 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.columns','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.columns'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Username')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Email')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Role')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Pack')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Status')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Country')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => ['align' => 'right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8ea4f77adc03babec838eacd9b78df96)): ?>
<?php $attributes = $__attributesOriginal8ea4f77adc03babec838eacd9b78df96; ?>
<?php unset($__attributesOriginal8ea4f77adc03babec838eacd9b78df96); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8ea4f77adc03babec838eacd9b78df96)): ?>
<?php $component = $__componentOriginal8ea4f77adc03babec838eacd9b78df96; ?>
<?php unset($__componentOriginal8ea4f77adc03babec838eacd9b78df96); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.rows','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.rows'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $status = $user->statusEnum();
                        $statusColor = match ($status) {
                            \App\Enums\UserStatus::Active => 'teal',
                            \App\Enums\UserStatus::Invited => 'amber',
                            \App\Enums\UserStatus::Disabled => 'red',
                        };
                        $countryLabel = \App\Support\Geography\Countries::label($user->country);
                    ?>
                    <?php if (isset($component)) { $__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.row','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <div class="min-w-0">
                                <p class="truncate text-xs font-medium text-krikkit-fg"><?php echo e($user->name); ?></p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($user->username)): ?>
                                    <p class="truncate text-[11px] text-krikkit-muted"><?php echo e($user->username); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <span class="text-krikkit-fg-soft"><?php echo e($user->email); ?></span>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <?php echo e($user->accessRole?->title ?? __('dashboard.Unassigned')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <span class="text-krikkit-fg-soft"><?php echo e($user->entitlement?->plan?->title ?? __('dashboard.Free')); ?></span>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <?php if (isset($component)) { $__componentOriginal0bc981f99055f90423de9ceafd4f5f18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::badge','data' => ['color' => $statusColor,'size' => 'xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($statusColor),'size' => 'xs']); ?><?php echo e($status->label()); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $attributes = $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $component = $__componentOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <span class="text-krikkit-muted"><?php echo e($countryLabel ?: '—'); ?></span>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => ['align' => 'right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right']); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('allows', 'users.revise')): ?>
                                <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['href' => ''.e(route('dashboard.users.edit', $user)).'','variant' => 'ghost','square' => true,'size' => 'sm','ariaLabel' => ''.e(__('dashboard.Edit')).'','title' => ''.e(__('dashboard.Edit')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('dashboard.users.edit', $user)).'','variant' => 'ghost','square' => true,'size' => 'sm','aria-label' => ''.e(__('dashboard.Edit')).'','title' => ''.e(__('dashboard.Edit')).'']); ?>
                                    <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'pencil','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'pencil','class' => 'size-4']); ?>
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
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $attributes = $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $component = $__componentOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f)): ?>
<?php $attributes = $__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f; ?>
<?php unset($__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f)): ?>
<?php $component = $__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f; ?>
<?php unset($__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f); ?>
<?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7)): ?>
<?php $attributes = $__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7; ?>
<?php unset($__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7)): ?>
<?php $component = $__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7; ?>
<?php unset($__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald9f9621c9792e26cb0a786db19dbfd95)): ?>
<?php $attributes = $__attributesOriginald9f9621c9792e26cb0a786db19dbfd95; ?>
<?php unset($__attributesOriginald9f9621c9792e26cb0a786db19dbfd95); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald9f9621c9792e26cb0a786db19dbfd95)): ?>
<?php $component = $__componentOriginald9f9621c9792e26cb0a786db19dbfd95; ?>
<?php unset($__componentOriginald9f9621c9792e26cb0a786db19dbfd95); ?>
<?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\dashboard\users\users.blade.php ENDPATH**/ ?>