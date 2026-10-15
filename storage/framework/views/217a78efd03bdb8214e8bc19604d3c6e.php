<?php
    $allStepIds = array_keys($steps);
    $currentIndex = array_search($step->id(), $allStepIds);
    $appName = config('installer.name', 'Installer');
    $brandLetter = mb_strtoupper(mb_substr($appName, 0, 1));
    $logo = config('installer.logo');
?>

<div
    class="installer-page"
    x-data="{
        loading: <?php if ((object) ('loading') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('loading'->value()); ?>')<?php echo e('loading'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('loading'); ?>')<?php endif; ?>,
        error: null,
        isFinishing: false,
        isSuccess: false,
        redirectUrl: '',
        stepKey: 0
    }"
    x-on:installer-finishing.window="isFinishing = true"
    x-on:installation-success.window="
        isFinishing = false;
        isSuccess = true;
        redirectUrl = $event.detail[0].redirectUrl || '/admin';
    "
    x-on:step-changed.window="stepKey++"
    x-cloak
>
    <aside class="installer-sidebar">
        <div class="installer-sidebar-top">
            <div class="installer-brand">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logo): ?>
                    <img src="<?php echo e($logo); ?>" alt="<?php echo e($appName); ?>" class="installer-brand-logo">
                <?php else: ?>
                    <span class="installer-brand-mark"><?php echo e($brandLetter); ?></span>
                    <span class="installer-brand-name"><?php echo e($appName); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <p class="installer-eyebrow"><?php echo e(__('installer::installer.eyebrow')); ?></p>
            <p class="installer-tagline"><?php echo e(__('installer::installer.tagline')); ?></p>
            <span class="installer-accent-bar" aria-hidden="true"></span>
        </div>

        <nav class="installer-progress" aria-label="<?php echo e(__('installer::installer.eyebrow')); ?>">
            <div class="installer-progress-list">
                <?php $currentFound = false; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $isCurrent = $s->id() === $step->id();
                        if ($isCurrent) $currentFound = true;
                        $isPast = ! $currentFound && ! $isCurrent;
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isPast): ?>
                        <button type="button" wire:click="goToStep('<?php echo e($s->id()); ?>')" class="installer-progress-item installer-progress-item--past">
                            <span class="installer-progress-indicator" aria-hidden="true">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="14" height="14">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            <span class="installer-progress-label"><?php echo e($s->label()); ?></span>
                        </button>
                    <?php else: ?>
                        <div class="installer-progress-item <?php echo e($isCurrent ? 'installer-progress-item--current' : 'installer-progress-item--future'); ?>" <?php if($isCurrent): ?> aria-current="step" <?php endif; ?>>
                            <span class="installer-progress-indicator"><?php echo e($loop->iteration); ?></span>
                            <span class="installer-progress-label"><?php echo e($s->label()); ?></span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </nav>
    </aside>

    <div class="installer-content">
        <div class="installer-content-inner">
            <div class="installer-panel">
                <div class="installer-mobile-brand">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logo): ?>
                        <img src="<?php echo e($logo); ?>" alt="<?php echo e($appName); ?>" class="installer-brand-logo">
                    <?php else: ?>
                        <span class="installer-brand-mark"><?php echo e($brandLetter); ?></span>
                        <span class="installer-brand-name"><?php echo e($appName); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="installer-mobile-meta">
                    <p class="installer-step-counter">
                        <?php echo e(__('installer::installer.step_of', ['current' => $currentIndex + 1, 'total' => count($steps)])); ?>

                    </p>
                    <div class="installer-progress-dots" aria-hidden="true">
                        <?php $dotFound = false; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php
                                $isCurrent = $s->id() === $step->id();
                                if ($isCurrent) $dotFound = true;
                                $isPast = ! $dotFound && ! $isCurrent;
                            ?>
                            <span class="installer-progress-dot <?php echo e($isCurrent ? 'installer-progress-dot--current' : ($isPast ? 'installer-progress-dot--past' : 'installer-progress-dot--future')); ?>"></span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->has('global')): ?>
                    <div class="msg-box" role="alert">
                        <span class="msg-box-icon msg-box-icon--fail" aria-hidden="true">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </span>
                        <span><?php echo e($errors->first('global')); ?></span>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <form
                    class="installer-form"
                    x-show="!isSuccess"
                    wire:submit.prevent="next"
                >
                    <div
                        class="installer-form-body"
                        x-data
                        x-show="true"
                        x-transition:enter="installer-step-enter"
                        x-transition:enter-start="installer-step-enter-start"
                        x-transition:enter-end="installer-step-enter-end"
                        :key="stepKey"
                    >
                        <?php echo $__env->make($step->view(), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>

                    <div class="installer-actions">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$isFirstStep): ?>
                            <button type="button" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'back-btn'; ?>wire:key="back-btn" wire:click="previous" wire:loading.attr="disabled" class="btn btn--back">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                <?php echo e(__('installer::installer.btn_back')); ?>

                            </button>
                        <?php else: ?>
                            <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'no-back-btn'; ?>wire:key="no-back-btn"></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="installer-actions-end">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($step->id() === 'permit'): ?>
                                <button
                                    type="button"
                                    <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'skip-permit-btn'; ?>wire:key="skip-permit-btn"
                                    class="btn btn--back"
                                    wire:loading.attr="disabled"
                                    x-on:click="$wire.set('state.permit_token', '').then(() => $wire.next())"
                                >
                                    <?php echo e(__('installer::installer.btn_skip')); ?>

                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <button type="submit" wire:loading.attr="disabled" :disabled="isFinishing" class="btn btn--continue">
                                <span wire:loading.remove wire:target="next" x-show="!isFinishing" class="btn-text-icon">
                                    <?php echo e($isLastStep ? __('installer::installer.btn_complete') : __('installer::installer.btn_continue')); ?>

                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </span>

                                <span x-show="isFinishing" style="display: none;" class="btn-text-icon">
                                    <span class="spinner"></span>
                                    <?php echo e(__('installer::installer.btn_finalizing')); ?>

                                </span>

                                <span wire:loading wire:target="next" x-show="!isFinishing" class="btn-text-icon">
                                    <span class="spinner"></span>
                                    <?php echo e(__('installer::installer.btn_processing')); ?>

                                </span>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="installer-success" x-show="isSuccess" x-cloak style="display: none;">
                    <div class="installer-success-mark" aria-hidden="true">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 class="installer-success-title"><?php echo e(__('installer::installer.success_title')); ?></h2>
                    <p class="installer-success-copy"><?php echo e(__('installer::installer.success_copy')); ?></p>
                    <a :href="redirectUrl" class="btn btn--continue">
                        <span class="btn-text-icon">
                            <?php echo e(__('installer::installer.success_login')); ?>

                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\vendor\deep42\hitchhiker\resources\views\installer.blade.php ENDPATH**/ ?>