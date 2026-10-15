<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'paginator' => null,
    'current' => null,
    'last' => null,
    'total' => null,
    'firstItem' => null,
    'lastItem' => null,
    'goto' => null,
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
    'paginator' => null,
    'current' => null,
    'last' => null,
    'total' => null,
    'firstItem' => null,
    'lastItem' => null,
    'goto' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    use App\Support\Ui\PaginationPages;
    use Illuminate\Contracts\Pagination\LengthAwarePaginator;
    use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;

    $pager = $paginator instanceof PaginatorContract ? $paginator : null;

    if ($pager instanceof LengthAwarePaginator) {
        $current = $pager->currentPage();
        $last = $pager->lastPage();
        $total = $pager->total();
        $firstItem = $pager->firstItem();
        $lastItem = $pager->lastItem();
    }

    $current = max(1, (int) ($current ?? 1));
    $last = max(1, (int) ($last ?? 1));
    $pages = PaginationPages::numbers($current, $last);
    $hasRange = $total !== null && (int) $total > 0;
    $onFirst = $current <= 1;
    $onLast = $current >= $last;
    $live = is_string($goto) && $goto !== '';
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pager || $last > 1 || $hasRange): ?>
    <div <?php echo e($attributes->class('flex flex-wrap items-center justify-between gap-2')); ?>>
        <p class="text-xs text-krikkit-muted">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasRange): ?>
                <span class="font-medium text-krikkit-fg-soft"><?php echo e($firstItem); ?>–<?php echo e($lastItem); ?></span>
                / <?php echo e($total); ?>

            <?php elseif($pager instanceof LengthAwarePaginator && $pager->total() === 0): ?>
                <?php echo e(__('dashboard.No results.')); ?>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </p>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($last > 1): ?>
            <nav class="inline-flex items-center gap-0.5" aria-label="<?php echo e(__('messages.Pagination')); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($live): ?>
                    <button
                        type="button"
                        wire:click="<?php echo e($goto); ?>(<?php echo e($current - 1); ?>)"
                        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                            'inline-flex size-6 items-center justify-center rounded text-krikkit-muted transition',
                            $onFirst ? 'pointer-events-none opacity-35' : 'hover:bg-krikkit-soft',
                        ]); ?>"
                        <?php if($onFirst): ?> disabled aria-disabled="true" <?php endif; ?>
                        aria-label="<?php echo e(__('messages.Previous')); ?>"
                    >
                        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'chevron-left','class' => 'size-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-left','class' => 'size-3.5']); ?>
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
                <?php else: ?>
                    <a
                        href="<?php echo e($onFirst ? '#' : ($pager?->previousPageUrl() ?: $pager?->url($current - 1) ?: '#')); ?>"
                        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                            'inline-flex size-6 items-center justify-center rounded text-krikkit-muted transition',
                            $onFirst ? 'pointer-events-none opacity-35' : 'hover:bg-krikkit-soft',
                        ]); ?>"
                        <?php if($onFirst): ?> aria-disabled="true" tabindex="-1" <?php endif; ?>
                        aria-label="<?php echo e(__('messages.Previous')); ?>"
                    >
                        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'chevron-left','class' => 'size-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-left','class' => 'size-3.5']); ?>
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
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($page === null): ?>
                        <span class="px-0.5 text-xs text-krikkit-subtle">…</span>
                    <?php elseif($live): ?>
                        <button
                            type="button"
                            wire:click="<?php echo e($goto); ?>(<?php echo e($page); ?>)"
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'inline-flex size-6 items-center justify-center rounded text-xs font-medium transition',
                                $page === $current
                                    ? 'bg-krikkit-fill text-krikkit-on-fill'
                                    : 'text-krikkit-muted hover:bg-krikkit-soft',
                            ]); ?>"
                            <?php if($page === $current): ?> aria-current="page" <?php endif; ?>
                        ><?php echo e($page); ?></button>
                    <?php else: ?>
                        <a
                            href="<?php echo e($pager?->url($page) ?: '#'); ?>"
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'inline-flex size-6 items-center justify-center rounded text-xs font-medium transition',
                                $page === $current
                                    ? 'bg-krikkit-fill text-krikkit-on-fill'
                                    : 'text-krikkit-muted hover:bg-krikkit-soft',
                            ]); ?>"
                            <?php if($page === $current): ?> aria-current="page" <?php endif; ?>
                        ><?php echo e($page); ?></a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($live): ?>
                    <button
                        type="button"
                        wire:click="<?php echo e($goto); ?>(<?php echo e($current + 1); ?>)"
                        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                            'inline-flex size-6 items-center justify-center rounded text-krikkit-muted transition',
                            $onLast ? 'pointer-events-none opacity-35' : 'hover:bg-krikkit-soft',
                        ]); ?>"
                        <?php if($onLast): ?> disabled aria-disabled="true" <?php endif; ?>
                        aria-label="<?php echo e(__('messages.Next')); ?>"
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
                    </button>
                <?php else: ?>
                    <a
                        href="<?php echo e($onLast ? '#' : ($pager?->nextPageUrl() ?: $pager?->url($current + 1) ?: '#')); ?>"
                        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                            'inline-flex size-6 items-center justify-center rounded text-krikkit-muted transition',
                            $onLast ? 'pointer-events-none opacity-35' : 'hover:bg-krikkit-soft',
                        ]); ?>"
                        <?php if($onLast): ?> aria-disabled="true" tabindex="-1" <?php endif; ?>
                        aria-label="<?php echo e(__('messages.Next')); ?>"
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
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </nav>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
<?php else: ?>
    <nav <?php echo e($attributes->class('inline-flex items-center gap-0.5')); ?> aria-label="<?php echo e(__('messages.Pagination')); ?>">
        <?php echo e($slot); ?>

    </nav>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\pagination.blade.php ENDPATH**/ ?>