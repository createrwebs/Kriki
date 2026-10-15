<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-krikkit-fg"><?php echo e(__('dashboard.Failed turns')); ?></h1>
        <p class="mt-0.5 text-xs text-krikkit-muted"><?php echo e(__('dashboard.Failed and stuck assistant turns that need a look.')); ?></p>
    </div>

    <div class="space-y-3">
        <h2 class="text-sm font-medium text-krikkit-fg"><?php echo e(__('dashboard.Failed')); ?></h2>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($failed === []): ?>
            <p class="text-sm text-krikkit-muted"><?php echo e(__('dashboard.No failed turns.')); ?></p>
        <?php else: ?>
            <?php echo $__env->make('livewire.dashboard.lab.options.turnRows', ['rows' => $failed, 'kind' => 'failed'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="space-y-3">
        <h2 class="text-sm font-medium text-krikkit-fg"><?php echo e(__('dashboard.Stuck')); ?></h2>
        <p class="text-[11px] text-krikkit-muted"><?php echo e(__('dashboard.In-progress chrome older than :minutes minutes.', ['minutes' => 10])); ?></p>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stuck === []): ?>
            <p class="text-sm text-krikkit-muted"><?php echo e(__('dashboard.No stuck turns.')); ?></p>
        <?php else: ?>
            <?php echo $__env->make('livewire.dashboard.lab.options.turnRows', ['rows' => $stuck, 'kind' => 'stuck'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\dashboard\lab\options\turns.blade.php ENDPATH**/ ?>