<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
    <?php if (isset($component)) { $__componentOriginald99998d64b0875088b034c3416ebc71a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald99998d64b0875088b034c3416ebc71a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
        <p class="mb-4 text-sm font-medium text-krikkit-muted"><?php echo e(__('dashboard.Buttons')); ?></p>
        <div class="flex flex-wrap items-center gap-2">
            <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['variant' => 'outline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline']); ?><?php echo e(__('dashboard.Button')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $attributes = $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $component = $__componentOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['variant' => 'primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary']); ?><?php echo e(__('dashboard.Primary')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $attributes = $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $component = $__componentOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['variant' => 'fill']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'fill']); ?><?php echo e(__('dashboard.Filled')); ?> <?php echo $__env->renderComponent(); ?>
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
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald99998d64b0875088b034c3416ebc71a)): ?>
<?php $attributes = $__attributesOriginald99998d64b0875088b034c3416ebc71a; ?>
<?php unset($__attributesOriginald99998d64b0875088b034c3416ebc71a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald99998d64b0875088b034c3416ebc71a)): ?>
<?php $component = $__componentOriginald99998d64b0875088b034c3416ebc71a; ?>
<?php unset($__componentOriginald99998d64b0875088b034c3416ebc71a); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginald99998d64b0875088b034c3416ebc71a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald99998d64b0875088b034c3416ebc71a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
        <p class="mb-1 text-sm font-semibold text-krikkit-fg"><?php echo e(__('dashboard.Select your payment method')); ?></p>
        <p class="mb-4 text-xs text-krikkit-muted"><?php echo e(__('dashboard.Radio group')); ?></p>
        <div class="space-y-3">
            <?php if (isset($component)) { $__componentOriginal79dffdb7249fe22066ce22573f971f9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79dffdb7249fe22066ce22573f971f9d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::radio','data' => ['name' => 'theme_pay','value' => 'card','label' => __('dashboard.Credit Card'),'checked' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::radio'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'theme_pay','value' => 'card','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Credit Card')),'checked' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal79dffdb7249fe22066ce22573f971f9d)): ?>
<?php $attributes = $__attributesOriginal79dffdb7249fe22066ce22573f971f9d; ?>
<?php unset($__attributesOriginal79dffdb7249fe22066ce22573f971f9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal79dffdb7249fe22066ce22573f971f9d)): ?>
<?php $component = $__componentOriginal79dffdb7249fe22066ce22573f971f9d; ?>
<?php unset($__componentOriginal79dffdb7249fe22066ce22573f971f9d); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal79dffdb7249fe22066ce22573f971f9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79dffdb7249fe22066ce22573f971f9d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::radio','data' => ['name' => 'theme_pay','value' => 'paypal','label' => __('dashboard.Paypal')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::radio'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'theme_pay','value' => 'paypal','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Paypal'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal79dffdb7249fe22066ce22573f971f9d)): ?>
<?php $attributes = $__attributesOriginal79dffdb7249fe22066ce22573f971f9d; ?>
<?php unset($__attributesOriginal79dffdb7249fe22066ce22573f971f9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal79dffdb7249fe22066ce22573f971f9d)): ?>
<?php $component = $__componentOriginal79dffdb7249fe22066ce22573f971f9d; ?>
<?php unset($__componentOriginal79dffdb7249fe22066ce22573f971f9d); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal79dffdb7249fe22066ce22573f971f9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79dffdb7249fe22066ce22573f971f9d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::radio','data' => ['name' => 'theme_pay','value' => 'bank','label' => __('dashboard.Bank transfer')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::radio'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'theme_pay','value' => 'bank','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Bank transfer'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal79dffdb7249fe22066ce22573f971f9d)): ?>
<?php $attributes = $__attributesOriginal79dffdb7249fe22066ce22573f971f9d; ?>
<?php unset($__attributesOriginal79dffdb7249fe22066ce22573f971f9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal79dffdb7249fe22066ce22573f971f9d)): ?>
<?php $component = $__componentOriginal79dffdb7249fe22066ce22573f971f9d; ?>
<?php unset($__componentOriginal79dffdb7249fe22066ce22573f971f9d); ?>
<?php endif; ?>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald99998d64b0875088b034c3416ebc71a)): ?>
<?php $attributes = $__attributesOriginald99998d64b0875088b034c3416ebc71a; ?>
<?php unset($__attributesOriginald99998d64b0875088b034c3416ebc71a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald99998d64b0875088b034c3416ebc71a)): ?>
<?php $component = $__componentOriginald99998d64b0875088b034c3416ebc71a; ?>
<?php unset($__componentOriginald99998d64b0875088b034c3416ebc71a); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginald99998d64b0875088b034c3416ebc71a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald99998d64b0875088b034c3416ebc71a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
        <p class="mb-1 text-sm font-semibold text-krikkit-fg"><?php echo e(__('dashboard.Role')); ?></p>
        <p class="mb-4 text-xs text-krikkit-muted"><?php echo e(__('dashboard.Checklist')); ?></p>
        <div class="space-y-3">
            <?php if (isset($component)) { $__componentOriginal405efd7a45a1d87a7ddd39b5608145e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal405efd7a45a1d87a7ddd39b5608145e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::checkbox','data' => ['name' => 'theme_role_admin','checked' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'theme_role_admin','checked' => true]); ?>
                <span class="block font-medium text-krikkit-fg"><?php echo e(__('dashboard.Administrator')); ?></span>
                <span class="block text-xs text-krikkit-muted"><?php echo e(__('dashboard.Administrator users can perform any action.')); ?></span>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal405efd7a45a1d87a7ddd39b5608145e8)): ?>
<?php $attributes = $__attributesOriginal405efd7a45a1d87a7ddd39b5608145e8; ?>
<?php unset($__attributesOriginal405efd7a45a1d87a7ddd39b5608145e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal405efd7a45a1d87a7ddd39b5608145e8)): ?>
<?php $component = $__componentOriginal405efd7a45a1d87a7ddd39b5608145e8; ?>
<?php unset($__componentOriginal405efd7a45a1d87a7ddd39b5608145e8); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal405efd7a45a1d87a7ddd39b5608145e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal405efd7a45a1d87a7ddd39b5608145e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::checkbox','data' => ['name' => 'theme_role_editor']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'theme_role_editor']); ?>
                <span class="block font-medium text-krikkit-fg"><?php echo e(__('dashboard.Editor')); ?></span>
                <span class="block text-xs text-krikkit-muted"><?php echo e(__('dashboard.Editor users have the ability to read, create, and update.')); ?></span>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal405efd7a45a1d87a7ddd39b5608145e8)): ?>
<?php $attributes = $__attributesOriginal405efd7a45a1d87a7ddd39b5608145e8; ?>
<?php unset($__attributesOriginal405efd7a45a1d87a7ddd39b5608145e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal405efd7a45a1d87a7ddd39b5608145e8)): ?>
<?php $component = $__componentOriginal405efd7a45a1d87a7ddd39b5608145e8; ?>
<?php unset($__componentOriginal405efd7a45a1d87a7ddd39b5608145e8); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal405efd7a45a1d87a7ddd39b5608145e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal405efd7a45a1d87a7ddd39b5608145e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::checkbox','data' => ['name' => 'theme_role_viewer']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'theme_role_viewer']); ?>
                <span class="block font-medium text-krikkit-fg"><?php echo e(__('dashboard.Viewer')); ?></span>
                <span class="block text-xs text-krikkit-muted"><?php echo e(__('dashboard.Viewer users only have the ability to read.')); ?></span>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal405efd7a45a1d87a7ddd39b5608145e8)): ?>
<?php $attributes = $__attributesOriginal405efd7a45a1d87a7ddd39b5608145e8; ?>
<?php unset($__attributesOriginal405efd7a45a1d87a7ddd39b5608145e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal405efd7a45a1d87a7ddd39b5608145e8)): ?>
<?php $component = $__componentOriginal405efd7a45a1d87a7ddd39b5608145e8; ?>
<?php unset($__componentOriginal405efd7a45a1d87a7ddd39b5608145e8); ?>
<?php endif; ?>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald99998d64b0875088b034c3416ebc71a)): ?>
<?php $attributes = $__attributesOriginald99998d64b0875088b034c3416ebc71a; ?>
<?php unset($__attributesOriginald99998d64b0875088b034c3416ebc71a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald99998d64b0875088b034c3416ebc71a)): ?>
<?php $component = $__componentOriginald99998d64b0875088b034c3416ebc71a; ?>
<?php unset($__componentOriginald99998d64b0875088b034c3416ebc71a); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginald99998d64b0875088b034c3416ebc71a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald99998d64b0875088b034c3416ebc71a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
        <p class="mb-1 text-sm font-semibold text-krikkit-fg"><?php echo e(__('dashboard.Communication emails')); ?></p>
        <p class="mb-4 text-xs text-krikkit-muted"><?php echo e(__('dashboard.Receive emails about your account activity.')); ?></p>
        <div class="space-y-3">
            <?php if (isset($component)) { $__componentOriginal865e544359d7296e2e13038907c989a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal865e544359d7296e2e13038907c989a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::switch','data' => ['name' => 'theme_mail_activity','label' => __('dashboard.Communication emails'),'checked' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::switch'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'theme_mail_activity','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Communication emails')),'checked' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal865e544359d7296e2e13038907c989a8)): ?>
<?php $attributes = $__attributesOriginal865e544359d7296e2e13038907c989a8; ?>
<?php unset($__attributesOriginal865e544359d7296e2e13038907c989a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal865e544359d7296e2e13038907c989a8)): ?>
<?php $component = $__componentOriginal865e544359d7296e2e13038907c989a8; ?>
<?php unset($__componentOriginal865e544359d7296e2e13038907c989a8); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal865e544359d7296e2e13038907c989a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal865e544359d7296e2e13038907c989a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::switch','data' => ['name' => 'theme_mail_marketing','label' => __('dashboard.Marketing emails')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::switch'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'theme_mail_marketing','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Marketing emails'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal865e544359d7296e2e13038907c989a8)): ?>
<?php $attributes = $__attributesOriginal865e544359d7296e2e13038907c989a8; ?>
<?php unset($__attributesOriginal865e544359d7296e2e13038907c989a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal865e544359d7296e2e13038907c989a8)): ?>
<?php $component = $__componentOriginal865e544359d7296e2e13038907c989a8; ?>
<?php unset($__componentOriginal865e544359d7296e2e13038907c989a8); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal865e544359d7296e2e13038907c989a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal865e544359d7296e2e13038907c989a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::switch','data' => ['name' => 'theme_mail_social','label' => __('dashboard.Social emails')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::switch'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'theme_mail_social','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Social emails'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal865e544359d7296e2e13038907c989a8)): ?>
<?php $attributes = $__attributesOriginal865e544359d7296e2e13038907c989a8; ?>
<?php unset($__attributesOriginal865e544359d7296e2e13038907c989a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal865e544359d7296e2e13038907c989a8)): ?>
<?php $component = $__componentOriginal865e544359d7296e2e13038907c989a8; ?>
<?php unset($__componentOriginal865e544359d7296e2e13038907c989a8); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal865e544359d7296e2e13038907c989a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal865e544359d7296e2e13038907c989a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::switch','data' => ['name' => 'theme_mail_security','label' => __('dashboard.Security emails'),'checked' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::switch'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'theme_mail_security','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Security emails')),'checked' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal865e544359d7296e2e13038907c989a8)): ?>
<?php $attributes = $__attributesOriginal865e544359d7296e2e13038907c989a8; ?>
<?php unset($__attributesOriginal865e544359d7296e2e13038907c989a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal865e544359d7296e2e13038907c989a8)): ?>
<?php $component = $__componentOriginal865e544359d7296e2e13038907c989a8; ?>
<?php unset($__componentOriginal865e544359d7296e2e13038907c989a8); ?>
<?php endif; ?>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald99998d64b0875088b034c3416ebc71a)): ?>
<?php $attributes = $__attributesOriginald99998d64b0875088b034c3416ebc71a; ?>
<?php unset($__attributesOriginald99998d64b0875088b034c3416ebc71a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald99998d64b0875088b034c3416ebc71a)): ?>
<?php $component = $__componentOriginald99998d64b0875088b034c3416ebc71a; ?>
<?php unset($__componentOriginald99998d64b0875088b034c3416ebc71a); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginald99998d64b0875088b034c3416ebc71a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald99998d64b0875088b034c3416ebc71a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
        <p class="mb-1 text-sm font-semibold text-krikkit-fg"><?php echo e(__('dashboard.Shipping')); ?></p>
        <p class="mb-4 text-xs text-krikkit-muted"><?php echo e(__('dashboard.Select')); ?></p>
        <?php if (isset($component)) { $__componentOriginal7a952551f265692c915c9af677c5ecd6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7a952551f265692c915c9af677c5ecd6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select','data' => ['name' => 'theme_shipping','placeholder' => ''.e(__('dashboard.Choose…')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'theme_shipping','placeholder' => ''.e(__('dashboard.Choose…')).'']); ?>
            <?php if (isset($component)) { $__componentOriginalbc5c56c7c0bb48775665c8519b14d96e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select.option','data' => ['value' => 'standard','selected' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select.option'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'standard','selected' => true]); ?><?php echo e(__('dashboard.Standard · 4–10 business days')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e)): ?>
<?php $attributes = $__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e; ?>
<?php unset($__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc5c56c7c0bb48775665c8519b14d96e)): ?>
<?php $component = $__componentOriginalbc5c56c7c0bb48775665c8519b14d96e; ?>
<?php unset($__componentOriginalbc5c56c7c0bb48775665c8519b14d96e); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalbc5c56c7c0bb48775665c8519b14d96e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select.option','data' => ['value' => 'fast']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select.option'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'fast']); ?><?php echo e(__('dashboard.Fast · 2–5 business days')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e)): ?>
<?php $attributes = $__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e; ?>
<?php unset($__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc5c56c7c0bb48775665c8519b14d96e)): ?>
<?php $component = $__componentOriginalbc5c56c7c0bb48775665c8519b14d96e; ?>
<?php unset($__componentOriginalbc5c56c7c0bb48775665c8519b14d96e); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalbc5c56c7c0bb48775665c8519b14d96e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select.option','data' => ['value' => 'next']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select.option'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'next']); ?><?php echo e(__('dashboard.Next day · 1 business day')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e)): ?>
<?php $attributes = $__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e; ?>
<?php unset($__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc5c56c7c0bb48775665c8519b14d96e)): ?>
<?php $component = $__componentOriginalbc5c56c7c0bb48775665c8519b14d96e; ?>
<?php unset($__componentOriginalbc5c56c7c0bb48775665c8519b14d96e); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7a952551f265692c915c9af677c5ecd6)): ?>
<?php $attributes = $__attributesOriginal7a952551f265692c915c9af677c5ecd6; ?>
<?php unset($__attributesOriginal7a952551f265692c915c9af677c5ecd6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7a952551f265692c915c9af677c5ecd6)): ?>
<?php $component = $__componentOriginal7a952551f265692c915c9af677c5ecd6; ?>
<?php unset($__componentOriginal7a952551f265692c915c9af677c5ecd6); ?>
<?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald99998d64b0875088b034c3416ebc71a)): ?>
<?php $attributes = $__attributesOriginald99998d64b0875088b034c3416ebc71a; ?>
<?php unset($__attributesOriginald99998d64b0875088b034c3416ebc71a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald99998d64b0875088b034c3416ebc71a)): ?>
<?php $component = $__componentOriginald99998d64b0875088b034c3416ebc71a; ?>
<?php unset($__componentOriginald99998d64b0875088b034c3416ebc71a); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginald99998d64b0875088b034c3416ebc71a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald99998d64b0875088b034c3416ebc71a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
        <div class="mb-4 flex items-center gap-3">
            <?php if (isset($component)) { $__componentOriginal11129def8fa621557df03772d50fb352 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal11129def8fa621557df03772d50fb352 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::avatar','data' => ['name' => 'Acme Inc']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'Acme Inc']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal11129def8fa621557df03772d50fb352)): ?>
<?php $attributes = $__attributesOriginal11129def8fa621557df03772d50fb352; ?>
<?php unset($__attributesOriginal11129def8fa621557df03772d50fb352); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11129def8fa621557df03772d50fb352)): ?>
<?php $component = $__componentOriginal11129def8fa621557df03772d50fb352; ?>
<?php unset($__componentOriginal11129def8fa621557df03772d50fb352); ?>
<?php endif; ?>
            <div>
                <p class="text-sm font-semibold text-krikkit-fg"><?php echo e(__('dashboard.Acme Inc.')); ?></p>
                <p class="text-xs text-krikkit-muted"><?php echo e(__('dashboard.Theming Krikkit')); ?></p>
            </div>
        </div>
        <?php if (isset($component)) { $__componentOriginal6e0d478c2baf3d546733bd9994607e08 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6e0d478c2baf3d546733bd9994607e08 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::tabs','data' => ['selected' => 'profile']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::tabs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['selected' => 'profile']); ?>
            <?php if (isset($component)) { $__componentOriginal0a658b227c40cc273cc1f0843267864b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a658b227c40cc273cc1f0843267864b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::tabs.list','data' => ['class' => 'mb-3 rounded-xl bg-krikkit-soft p-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::tabs.list'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-3 rounded-xl bg-krikkit-soft p-1']); ?>
                <?php if (isset($component)) { $__componentOriginal3832f3b903a28c51b6388b6b6df0f2f1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3832f3b903a28c51b6388b6b6df0f2f1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::tabs.tab','data' => ['name' => 'profile']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::tabs.tab'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'profile']); ?><?php echo e(__('dashboard.Profile')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3832f3b903a28c51b6388b6b6df0f2f1)): ?>
<?php $attributes = $__attributesOriginal3832f3b903a28c51b6388b6b6df0f2f1; ?>
<?php unset($__attributesOriginal3832f3b903a28c51b6388b6b6df0f2f1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3832f3b903a28c51b6388b6b6df0f2f1)): ?>
<?php $component = $__componentOriginal3832f3b903a28c51b6388b6b6df0f2f1; ?>
<?php unset($__componentOriginal3832f3b903a28c51b6388b6b6df0f2f1); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal3832f3b903a28c51b6388b6b6df0f2f1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3832f3b903a28c51b6388b6b6df0f2f1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::tabs.tab','data' => ['name' => 'user']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::tabs.tab'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'user']); ?><?php echo e(__('dashboard.User')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3832f3b903a28c51b6388b6b6df0f2f1)): ?>
<?php $attributes = $__attributesOriginal3832f3b903a28c51b6388b6b6df0f2f1; ?>
<?php unset($__attributesOriginal3832f3b903a28c51b6388b6b6df0f2f1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3832f3b903a28c51b6388b6b6df0f2f1)): ?>
<?php $component = $__componentOriginal3832f3b903a28c51b6388b6b6df0f2f1; ?>
<?php unset($__componentOriginal3832f3b903a28c51b6388b6b6df0f2f1); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal3832f3b903a28c51b6388b6b6df0f2f1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3832f3b903a28c51b6388b6b6df0f2f1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::tabs.tab','data' => ['name' => 'billing']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::tabs.tab'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'billing']); ?><?php echo e(__('dashboard.Billing')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3832f3b903a28c51b6388b6b6df0f2f1)): ?>
<?php $attributes = $__attributesOriginal3832f3b903a28c51b6388b6b6df0f2f1; ?>
<?php unset($__attributesOriginal3832f3b903a28c51b6388b6b6df0f2f1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3832f3b903a28c51b6388b6b6df0f2f1)): ?>
<?php $component = $__componentOriginal3832f3b903a28c51b6388b6b6df0f2f1; ?>
<?php unset($__componentOriginal3832f3b903a28c51b6388b6b6df0f2f1); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a658b227c40cc273cc1f0843267864b)): ?>
<?php $attributes = $__attributesOriginal0a658b227c40cc273cc1f0843267864b; ?>
<?php unset($__attributesOriginal0a658b227c40cc273cc1f0843267864b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a658b227c40cc273cc1f0843267864b)): ?>
<?php $component = $__componentOriginal0a658b227c40cc273cc1f0843267864b; ?>
<?php unset($__componentOriginal0a658b227c40cc273cc1f0843267864b); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal272dfa44b2e917b59308a753153e2f3e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal272dfa44b2e917b59308a753153e2f3e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::tabs.panel','data' => ['name' => 'profile']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::tabs.panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'profile']); ?>
                <p class="text-sm text-krikkit-muted">
                    <?php echo e(__('dashboard.Live preview of Krikkit components with your palette.')); ?>

                </p>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal272dfa44b2e917b59308a753153e2f3e)): ?>
<?php $attributes = $__attributesOriginal272dfa44b2e917b59308a753153e2f3e; ?>
<?php unset($__attributesOriginal272dfa44b2e917b59308a753153e2f3e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal272dfa44b2e917b59308a753153e2f3e)): ?>
<?php $component = $__componentOriginal272dfa44b2e917b59308a753153e2f3e; ?>
<?php unset($__componentOriginal272dfa44b2e917b59308a753153e2f3e); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal272dfa44b2e917b59308a753153e2f3e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal272dfa44b2e917b59308a753153e2f3e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::tabs.panel','data' => ['name' => 'user']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::tabs.panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'user']); ?>
                <p class="text-sm text-krikkit-muted"><?php echo e(__('dashboard.User settings go here.')); ?></p>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal272dfa44b2e917b59308a753153e2f3e)): ?>
<?php $attributes = $__attributesOriginal272dfa44b2e917b59308a753153e2f3e; ?>
<?php unset($__attributesOriginal272dfa44b2e917b59308a753153e2f3e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal272dfa44b2e917b59308a753153e2f3e)): ?>
<?php $component = $__componentOriginal272dfa44b2e917b59308a753153e2f3e; ?>
<?php unset($__componentOriginal272dfa44b2e917b59308a753153e2f3e); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal272dfa44b2e917b59308a753153e2f3e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal272dfa44b2e917b59308a753153e2f3e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::tabs.panel','data' => ['name' => 'billing']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::tabs.panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'billing']); ?>
                <p class="text-sm text-krikkit-muted"><?php echo e(__('dashboard.Billing details go here.')); ?></p>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal272dfa44b2e917b59308a753153e2f3e)): ?>
<?php $attributes = $__attributesOriginal272dfa44b2e917b59308a753153e2f3e; ?>
<?php unset($__attributesOriginal272dfa44b2e917b59308a753153e2f3e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal272dfa44b2e917b59308a753153e2f3e)): ?>
<?php $component = $__componentOriginal272dfa44b2e917b59308a753153e2f3e; ?>
<?php unset($__componentOriginal272dfa44b2e917b59308a753153e2f3e); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6e0d478c2baf3d546733bd9994607e08)): ?>
<?php $attributes = $__attributesOriginal6e0d478c2baf3d546733bd9994607e08; ?>
<?php unset($__attributesOriginal6e0d478c2baf3d546733bd9994607e08); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6e0d478c2baf3d546733bd9994607e08)): ?>
<?php $component = $__componentOriginal6e0d478c2baf3d546733bd9994607e08; ?>
<?php unset($__componentOriginal6e0d478c2baf3d546733bd9994607e08); ?>
<?php endif; ?>
        <div class="mt-4">
            <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['variant' => 'danger','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'danger','size' => 'sm']); ?><?php echo e(__('dashboard.Shut down')); ?> <?php echo $__env->renderComponent(); ?>
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
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald99998d64b0875088b034c3416ebc71a)): ?>
<?php $attributes = $__attributesOriginald99998d64b0875088b034c3416ebc71a; ?>
<?php unset($__attributesOriginald99998d64b0875088b034c3416ebc71a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald99998d64b0875088b034c3416ebc71a)): ?>
<?php $component = $__componentOriginald99998d64b0875088b034c3416ebc71a; ?>
<?php unset($__componentOriginald99998d64b0875088b034c3416ebc71a); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginald99998d64b0875088b034c3416ebc71a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald99998d64b0875088b034c3416ebc71a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => ['class' => 'sm:col-span-2 xl:col-span-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'sm:col-span-2 xl:col-span-3']); ?>
        <p class="mb-4 text-sm font-medium text-krikkit-muted"><?php echo e(__('dashboard.More Krikkit')); ?></p>
        <div class="flex flex-wrap items-center gap-3">
            <?php if (isset($component)) { $__componentOriginal0bc981f99055f90423de9ceafd4f5f18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::badge','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Default')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $attributes = $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $component = $__componentOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal0bc981f99055f90423de9ceafd4f5f18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::badge','data' => ['color' => 'teal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'teal']); ?><?php echo e(__('dashboard.Teal')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $attributes = $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $component = $__componentOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal0bc981f99055f90423de9ceafd4f5f18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::badge','data' => ['color' => 'amber']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'amber']); ?><?php echo e(__('dashboard.Amber')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $attributes = $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $component = $__componentOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal0bc981f99055f90423de9ceafd4f5f18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::badge','data' => ['color' => 'red']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'red']); ?><?php echo e(__('dashboard.Red')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $attributes = $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $component = $__componentOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal11ab4d18f1867a2f4ba0bbc52a8dbd24 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal11ab4d18f1867a2f4ba0bbc52a8dbd24 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::progress','data' => ['value' => 62,'class' => 'max-w-xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::progress'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 62,'class' => 'max-w-xs']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal11ab4d18f1867a2f4ba0bbc52a8dbd24)): ?>
<?php $attributes = $__attributesOriginal11ab4d18f1867a2f4ba0bbc52a8dbd24; ?>
<?php unset($__attributesOriginal11ab4d18f1867a2f4ba0bbc52a8dbd24); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11ab4d18f1867a2f4ba0bbc52a8dbd24)): ?>
<?php $component = $__componentOriginal11ab4d18f1867a2f4ba0bbc52a8dbd24; ?>
<?php unset($__componentOriginal11ab4d18f1867a2f4ba0bbc52a8dbd24); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal79c913eebc08aa2f1af8927c57771386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79c913eebc08aa2f1af8927c57771386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::input','data' => ['name' => 'theme_search','placeholder' => ''.e(__('dashboard.Search…')).'','class' => 'max-w-xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'theme_search','placeholder' => ''.e(__('dashboard.Search…')).'','class' => 'max-w-xs']); ?>
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
            <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['variant' => 'subtle','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'subtle','size' => 'sm']); ?>
                <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'bell','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bell','class' => 'size-4']); ?>
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
                <?php echo e(__('dashboard.Notify')); ?>

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
        <div class="mt-4">
            <?php if (isset($component)) { $__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::callout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::callout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php if (isset($component)) { $__componentOriginalb0d355939b29a941230acc4f392faae1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb0d355939b29a941230acc4f392faae1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::callout.heading','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::callout.heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Theme preview')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb0d355939b29a941230acc4f392faae1)): ?>
<?php $attributes = $__attributesOriginalb0d355939b29a941230acc4f392faae1; ?>
<?php unset($__attributesOriginalb0d355939b29a941230acc4f392faae1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb0d355939b29a941230acc4f392faae1)): ?>
<?php $component = $__componentOriginalb0d355939b29a941230acc4f392faae1; ?>
<?php unset($__componentOriginalb0d355939b29a941230acc4f392faae1); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal617e293475666fd5160886d17e8563d4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal617e293475666fd5160886d17e8563d4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::callout.text','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::callout.text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php echo __('dashboard.Base remaps <code class="text-xs">neutral</code>; accent drives primary actions and switches.'); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal617e293475666fd5160886d17e8563d4)): ?>
<?php $attributes = $__attributesOriginal617e293475666fd5160886d17e8563d4; ?>
<?php unset($__attributesOriginal617e293475666fd5160886d17e8563d4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal617e293475666fd5160886d17e8563d4)): ?>
<?php $component = $__componentOriginal617e293475666fd5160886d17e8563d4; ?>
<?php unset($__componentOriginal617e293475666fd5160886d17e8563d4); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05)): ?>
<?php $attributes = $__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05; ?>
<?php unset($__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05)): ?>
<?php $component = $__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05; ?>
<?php unset($__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05); ?>
<?php endif; ?>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald99998d64b0875088b034c3416ebc71a)): ?>
<?php $attributes = $__attributesOriginald99998d64b0875088b034c3416ebc71a; ?>
<?php unset($__attributesOriginald99998d64b0875088b034c3416ebc71a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald99998d64b0875088b034c3416ebc71a)): ?>
<?php $component = $__componentOriginald99998d64b0875088b034c3416ebc71a; ?>
<?php unset($__componentOriginald99998d64b0875088b034c3416ebc71a); ?>
<?php endif; ?>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\dashboard\settings\options\preview.blade.php ENDPATH**/ ?>