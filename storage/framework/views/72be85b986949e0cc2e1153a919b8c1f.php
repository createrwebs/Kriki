<div class="mb-6">
    <h3 class="section-title"><?php echo e(__('installer::installer.requirements_title')); ?></h3>
    <p class="section-subtitle"><?php echo e(__('installer::installer.requirements_subtitle')); ?></p>
</div>

<div class="check-grid">
    <?php $requirements = $step->check(); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $requirements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requirement => $passed): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <?php
            $fixHint = '';
            if (! $passed) {
                if (str_starts_with($requirement, 'PHP')) {
                    preg_match('/[\d.]+/', $requirement, $m);
                    $fixHint = __('installer::installer.requirements_fix_php', ['version' => $m[0] ?? '']);
                } elseif (str_contains($requirement, 'Extension')) {
                    $extName = strtolower(str_replace(' Extension', '', $requirement));
                    $fixHint = __('installer::installer.requirements_fix_extension', ['name' => $extName]);
                } elseif (str_contains($requirement, 'Memory')) {
                    preg_match('/[\d.]+[MG]/', $requirement, $min);
                    $fixHint = __('installer::installer.requirements_fix_memory', [
                        'min' => $min[0] ?? '',
                        'current' => ini_get('memory_limit'),
                    ]);
                }
            }
        ?>
        <div class="check-item <?php echo e($passed ? 'check-item--pass' : 'check-item--fail'); ?>">
            <div class="check-icon <?php echo e($passed ? 'check-icon--pass' : 'check-icon--fail'); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($passed): ?>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                <?php else: ?>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="check-info">
                <p class="check-label"><?php echo e($requirement); ?></p>
                <p class="check-status"><?php echo e($passed ? __('installer::installer.requirements_passed_label') : __('installer::installer.requirements_failed_label')); ?></p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fixHint): ?>
                    <p class="check-hint"><?php echo e($fixHint); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array(false, $requirements, true)): ?>
    <div class="msg-box">
        <span class="msg-box-icon msg-box-icon--fail" aria-hidden="true">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </span>
        <span><?php echo e(__('installer::installer.requirements_action_needed')); ?></span>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\codea\krikkit-ai-website\vendor\deep42\hitchhiker\resources\views\steps\requirements.blade.php ENDPATH**/ ?>