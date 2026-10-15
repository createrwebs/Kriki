<div class="space-y-8">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div class="min-w-0">
            <h1 class="text-lg font-semibold tracking-tight text-krikkit-fg"><?php echo e($language->name); ?></h1>
            <p class="mt-0.5 text-xs text-krikkit-muted"><?php echo e(__('dashboard.Translate existing UI phrases.')); ?></p>
        </div>
        <div class="flex shrink-0 items-center gap-2">
            <div
                x-data="{
                    open: <?php echo \Illuminate\Support\Js::from(filled($search))->toHtml() ?>,
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
                x-on:click.outside="if (open && ! $wire.search) collapse()"
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

            <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['type' => 'button','variant' => 'ghost','size' => 'sm','wire:click' => 'translatePage','wire:loading.attr' => 'disabled','wire:target' => 'translatePage','title' => ''.e($modelLabel ?: __('dashboard.Translate with AI')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'ghost','size' => 'sm','wire:click' => 'translatePage','wire:loading.attr' => 'disabled','wire:target' => 'translatePage','title' => ''.e($modelLabel ?: __('dashboard.Translate with AI')).'']); ?>
                <span wire:loading.remove wire:target="translatePage"><?php echo e(__('dashboard.Translate with AI')); ?></span>
                <span wire:loading wire:target="translatePage"><?php echo e(__('dashboard.Translating…')); ?></span>
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
        </div>
    </div>

    <nav class="-mx-4 flex gap-1 overflow-x-auto border-b border-krikkit-line px-4 pb-px sm:-mx-5 sm:px-5 lg:mx-0 lg:overflow-visible lg:px-0" aria-label="<?php echo e(__('dashboard.Phrase group')); ?>">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php $active = $group === $name; ?>
            <button
                type="button"
                wire:click="selectGroup('<?php echo e($name); ?>')"
                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                    '-mb-px shrink-0 border-b px-3 py-2 text-sm font-medium capitalize transition',
                    'border-krikkit-fg text-krikkit-fg' => $active,
                    'border-transparent text-krikkit-muted hover:text-krikkit-fg' => ! $active,
                ]); ?>"
            >
                <?php echo e($name); ?>

            </button>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </nav>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rows === []): ?>
        <p class="text-sm text-krikkit-muted">
            <?php echo e(filled($search) ? __('dashboard.No results.') : __('dashboard.No phrases on this page.')); ?>

        </p>
    <?php else: ?>
        <div class="space-y-5">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal33ee6131e877bec48f910ca20ac959e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33ee6131e877bec48f910ca20ac959e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => $row['key']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($row['key'])]); ?>
                    <?php if (isset($component)) { $__componentOriginal79c913eebc08aa2f1af8927c57771386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79c913eebc08aa2f1af8927c57771386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::input','data' => ['size' => 'md','wire:model' => 'rows.'.e($index).'.value','placeholder' => $row['source']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','wire:model' => 'rows.'.e($index).'.value','placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($row['source'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal79c913eebc08aa2f1af8927c57771386)): ?>
<?php $attributes = $__attributesOriginal79c913eebc08aa2f1af8927c57771386; ?>
<?php unset($__attributesOriginal79c913eebc08aa2f1af8927c57771386); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal79c913eebc08aa2f1af8927c57771386)): ?>
<?php $component = $__componentOriginal79c913eebc08aa2f1af8927c57771386; ?>
<?php unset($__componentOriginal79c913eebc08aa2f1af8927c57771386); ?>
<?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($row['source'] !== $row['key']): ?>
                         <?php $__env->slot('description', null, []); ?> <?php echo e($row['source']); ?> <?php $__env->endSlot(); ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $attributes = $__attributesOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $component = $__componentOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__componentOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="flex flex-wrap items-center justify-between gap-3">
        <?php if (isset($component)) { $__componentOriginal7d2f8b5bd08c6d86125d7a58297905d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7d2f8b5bd08c6d86125d7a58297905d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::pagination','data' => ['class' => 'min-w-0 flex-1','current' => $page,'last' => $lastPage,'total' => $total,'firstItem' => $firstItem,'lastItem' => $lastItem,'goto' => 'gotoPage']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'min-w-0 flex-1','current' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($page),'last' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($lastPage),'total' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($total),'first-item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($firstItem),'last-item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($lastItem),'goto' => 'gotoPage']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7d2f8b5bd08c6d86125d7a58297905d1)): ?>
<?php $attributes = $__attributesOriginal7d2f8b5bd08c6d86125d7a58297905d1; ?>
<?php unset($__attributesOriginal7d2f8b5bd08c6d86125d7a58297905d1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7d2f8b5bd08c6d86125d7a58297905d1)): ?>
<?php $component = $__componentOriginal7d2f8b5bd08c6d86125d7a58297905d1; ?>
<?php unset($__componentOriginal7d2f8b5bd08c6d86125d7a58297905d1); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['type' => 'button','wire:click' => 'save','wire:loading.attr' => 'disabled','wire:target' => 'save']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'save','wire:loading.attr' => 'disabled','wire:target' => 'save']); ?>
            <?php echo e(__('dashboard.Save changes')); ?>

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
    </div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\dashboard\languages\options\translate.blade.php ENDPATH**/ ?>