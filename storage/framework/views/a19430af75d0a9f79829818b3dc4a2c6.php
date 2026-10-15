<?php if (isset($component)) { $__componentOriginalb5ef3d2b72b7e0baad3346138f67d40e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb5ef3d2b72b7e0baad3346138f67d40e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.studio.frame','data' => ['user' => $user,'site' => $site,'planTitle' => $planTitle,'packOffer' => $packOffer]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('studio.frame'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user),'site' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($site),'plan-title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($planTitle),'pack-offer' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($packOffer)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <?php echo $__env->make('livewire.studio.partials.hero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $isEmpty): ?>
        <?php echo $__env->make('livewire.studio.partials.projects', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php echo $__env->make('livewire.studio.partials.projectModals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb5ef3d2b72b7e0baad3346138f67d40e)): ?>
<?php $attributes = $__attributesOriginalb5ef3d2b72b7e0baad3346138f67d40e; ?>
<?php unset($__attributesOriginalb5ef3d2b72b7e0baad3346138f67d40e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb5ef3d2b72b7e0baad3346138f67d40e)): ?>
<?php $component = $__componentOriginalb5ef3d2b72b7e0baad3346138f67d40e; ?>
<?php unset($__componentOriginalb5ef3d2b72b7e0baad3346138f67d40e); ?>
<?php endif; ?>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views/livewire/studio/studio.blade.php ENDPATH**/ ?>