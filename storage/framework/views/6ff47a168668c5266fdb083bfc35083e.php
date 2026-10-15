<?php
    $section = $section ?? 'overview';
    $nav = $nav ?? [];
?>

<nav class="flex flex-wrap gap-1 border-b border-krikkit-line pb-px" aria-label="<?php echo e(__('dashboard.Finance')); ?>">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $nav; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <?php $active = $section === $item['key']; ?>
        <a
            href="<?php echo e(route($item['route'])); ?>"
            wire:navigate
            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                '-mb-px shrink-0 border-b px-3 py-2 text-sm font-medium transition',
                'border-krikkit-fg text-krikkit-fg' => $active,
                'border-transparent text-krikkit-muted hover:text-krikkit-fg' => ! $active,
            ]); ?>"
        >
            <?php echo e($item['label']); ?>

        </a>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
</nav>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\dashboard\finance\options\nav.blade.php ENDPATH**/ ?>