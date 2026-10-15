<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-krikkit-fg"><?php echo e(__('dashboard.Edit post')); ?></h1>
        <p class="mt-0.5 text-xs text-krikkit-muted"><?php echo e(__('dashboard.Write and publish posts for the public blog.')); ?></p>
    </div>

    <?php echo $__env->make('livewire.dashboard.blog.options.form', ['publicUrl' => $publicUrl], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\dashboard\blog\options\edit.blade.php ENDPATH**/ ?>