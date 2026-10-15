<div class="mb-6">
    <h3 class="section-title"><?php echo e(__('dashboard.License')); ?></h3>
    <p class="section-subtitle"><?php echo e(__('dashboard.Enter a purchase code to bind this install, or skip and do it later from Settings → License.')); ?></p>
</div>

<div class="form-grid">
    <div class="col-span-full">
        <label class="form-label"><?php echo e(__('dashboard.License key')); ?></label>
        <input
            type="text"
            wire:model="state.permit_token"
            class="form-input"
            autocomplete="off"
            spellcheck="false"
            placeholder="<?php echo e(__('dashboard.Paste your key')); ?>"
        >
        <p class="form-hint"><?php echo e(__('dashboard.Leave empty to continue without a key.')); ?></p>
    </div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\installer\permit\permit.blade.php ENDPATH**/ ?>