<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-krikkit-fg"><?php echo e(__('dashboard.New page')); ?></h1>
        <p class="mt-0.5 text-xs text-krikkit-muted"><?php echo e(__('dashboard.Standalone public pages with their own URL.')); ?></p>
    </div>

    <?php echo $__env->make('livewire.dashboard.pages.options.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\dashboard\pages\options\create.blade.php ENDPATH**/ ?>