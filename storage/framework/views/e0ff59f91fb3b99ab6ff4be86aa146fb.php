<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-krikkit-fg"><?php echo e(__('dashboard.Settings')); ?></h1>
        <p class="mt-0.5 text-xs text-krikkit-muted">
            <?php echo e(__('dashboard.Cookie banner and data-controller details.')); ?>

        </p>
    </div>

    <?php echo $__env->make('livewire.dashboard.settings.options.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form wire:submit="save" class="space-y-10">
        <?php if (isset($component)) { $__componentOriginal865e544359d7296e2e13038907c989a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal865e544359d7296e2e13038907c989a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::switch','data' => ['align' => 'right','class' => 'w-full justify-between','label' => __('dashboard.Show cookie banner'),'wire:model' => 'enabled']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::switch'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right','class' => 'w-full justify-between','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Show cookie banner')),'wire:model' => 'enabled']); ?>
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

        <?php if (isset($component)) { $__componentOriginald99998d64b0875088b034c3416ebc71a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald99998d64b0875088b034c3416ebc71a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => ['padding' => false,'class' => 'space-y-4 !border-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'class' => 'space-y-4 !border-0']); ?>
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.Banner')); ?></p>

            <?php if (isset($component)) { $__componentOriginal33ee6131e877bec48f910ca20ac959e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33ee6131e877bec48f910ca20ac959e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => __('dashboard.Title')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Title'))]); ?>
                <?php if (isset($component)) { $__componentOriginal79c913eebc08aa2f1af8927c57771386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79c913eebc08aa2f1af8927c57771386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::input','data' => ['size' => 'md','wire:model' => 'title','placeholder' => __('dashboard.We use cookies'),'invalid' => $errors->has('title')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','wire:model' => 'title','placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.We use cookies')),'invalid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->has('title'))]); ?>
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
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <?php if (isset($component)) { $__componentOriginal84c2540c43521235b601ee612603c1c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal84c2540c43521235b601ee612603c1c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field.error','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e($message); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $attributes = $__attributesOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__attributesOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $component = $__componentOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__componentOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $attributes = $__attributesOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $component = $__componentOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__componentOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal33ee6131e877bec48f910ca20ac959e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33ee6131e877bec48f910ca20ac959e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => __('dashboard.Message')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Message'))]); ?>
                <?php if (isset($component)) { $__componentOriginal45db5a78be6c3060441cff930126a416 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal45db5a78be6c3060441cff930126a416 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::textarea','data' => ['rows' => '4','wire:model' => 'message','placeholder' => __('messages.We use essential cookies to run this site. Optional analytics stay off until you accept.'),'invalid' => $errors->has('message')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['rows' => '4','wire:model' => 'message','placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('messages.We use essential cookies to run this site. Optional analytics stay off until you accept.')),'invalid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->has('message'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal45db5a78be6c3060441cff930126a416)): ?>
<?php $attributes = $__attributesOriginal45db5a78be6c3060441cff930126a416; ?>
<?php unset($__attributesOriginal45db5a78be6c3060441cff930126a416); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal45db5a78be6c3060441cff930126a416)): ?>
<?php $component = $__componentOriginal45db5a78be6c3060441cff930126a416; ?>
<?php unset($__componentOriginal45db5a78be6c3060441cff930126a416); ?>
<?php endif; ?>
                 <?php $__env->slot('description', null, []); ?> <?php echo e(__('dashboard.Explain what you store and link to the privacy policy.')); ?> <?php $__env->endSlot(); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <?php if (isset($component)) { $__componentOriginal84c2540c43521235b601ee612603c1c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal84c2540c43521235b601ee612603c1c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field.error','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e($message); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $attributes = $__attributesOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__attributesOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $component = $__componentOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__componentOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $attributes = $__attributesOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $component = $__componentOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__componentOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>

            <div class="grid gap-4 sm:grid-cols-2">
                <?php if (isset($component)) { $__componentOriginal33ee6131e877bec48f910ca20ac959e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33ee6131e877bec48f910ca20ac959e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => __('dashboard.Accept label')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Accept label'))]); ?>
                    <?php if (isset($component)) { $__componentOriginal79c913eebc08aa2f1af8927c57771386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79c913eebc08aa2f1af8927c57771386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::input','data' => ['size' => 'md','wire:model' => 'acceptLabel','placeholder' => __('dashboard.Accept'),'invalid' => $errors->has('acceptLabel')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','wire:model' => 'acceptLabel','placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Accept')),'invalid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->has('acceptLabel'))]); ?>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['acceptLabel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <?php if (isset($component)) { $__componentOriginal84c2540c43521235b601ee612603c1c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal84c2540c43521235b601ee612603c1c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field.error','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e($message); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $attributes = $__attributesOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__attributesOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $component = $__componentOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__componentOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $attributes = $__attributesOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $component = $__componentOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__componentOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal33ee6131e877bec48f910ca20ac959e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33ee6131e877bec48f910ca20ac959e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => __('dashboard.Reject label')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Reject label'))]); ?>
                    <?php if (isset($component)) { $__componentOriginal79c913eebc08aa2f1af8927c57771386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79c913eebc08aa2f1af8927c57771386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::input','data' => ['size' => 'md','wire:model' => 'rejectLabel','placeholder' => __('dashboard.Reject'),'invalid' => $errors->has('rejectLabel')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','wire:model' => 'rejectLabel','placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Reject')),'invalid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->has('rejectLabel'))]); ?>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['rejectLabel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <?php if (isset($component)) { $__componentOriginal84c2540c43521235b601ee612603c1c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal84c2540c43521235b601ee612603c1c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field.error','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e($message); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $attributes = $__attributesOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__attributesOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $component = $__componentOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__componentOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $attributes = $__attributesOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $component = $__componentOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__componentOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
            </div>

            <?php if (isset($component)) { $__componentOriginal865e544359d7296e2e13038907c989a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal865e544359d7296e2e13038907c989a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::switch','data' => ['align' => 'right','class' => 'w-full justify-between','label' => __('dashboard.Show reject button'),'wire:model' => 'showReject']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::switch'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right','class' => 'w-full justify-between','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Show reject button')),'wire:model' => 'showReject']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::switch','data' => ['align' => 'right','class' => 'w-full justify-between','label' => __('dashboard.Wait for consent before analytics'),'wire:model' => 'consentAnalytics']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::switch'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right','class' => 'w-full justify-between','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Wait for consent before analytics')),'wire:model' => 'consentAnalytics']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => ['padding' => false,'class' => 'space-y-4 !border-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'class' => 'space-y-4 !border-0']); ?>
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.Controller')); ?></p>

            <div class="grid gap-4 sm:grid-cols-2">
                <?php if (isset($component)) { $__componentOriginal33ee6131e877bec48f910ca20ac959e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33ee6131e877bec48f910ca20ac959e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => __('dashboard.Controller name')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Controller name'))]); ?>
                    <?php if (isset($component)) { $__componentOriginal79c913eebc08aa2f1af8927c57771386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79c913eebc08aa2f1af8927c57771386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::input','data' => ['size' => 'md','wire:model' => 'controllerName','placeholder' => __('dashboard.Acme Inc.'),'invalid' => $errors->has('controllerName')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','wire:model' => 'controllerName','placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Acme Inc.')),'invalid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->has('controllerName'))]); ?>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['controllerName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <?php if (isset($component)) { $__componentOriginal84c2540c43521235b601ee612603c1c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal84c2540c43521235b601ee612603c1c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field.error','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e($message); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $attributes = $__attributesOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__attributesOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $component = $__componentOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__componentOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $attributes = $__attributesOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $component = $__componentOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__componentOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal33ee6131e877bec48f910ca20ac959e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33ee6131e877bec48f910ca20ac959e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => __('dashboard.Controller email')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Controller email'))]); ?>
                    <?php if (isset($component)) { $__componentOriginal79c913eebc08aa2f1af8927c57771386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79c913eebc08aa2f1af8927c57771386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::input','data' => ['size' => 'md','type' => 'email','wire:model' => 'controllerEmail','placeholder' => __('dashboard.privacy@example.com'),'invalid' => $errors->has('controllerEmail')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','type' => 'email','wire:model' => 'controllerEmail','placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.privacy@example.com')),'invalid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->has('controllerEmail'))]); ?>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['controllerEmail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <?php if (isset($component)) { $__componentOriginal84c2540c43521235b601ee612603c1c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal84c2540c43521235b601ee612603c1c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field.error','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e($message); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $attributes = $__attributesOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__attributesOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $component = $__componentOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__componentOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $attributes = $__attributesOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $component = $__componentOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__componentOriginal33ee6131e877bec48f910ca20ac959e1); ?>
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

        <div class="flex justify-end">
            <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['type' => 'submit','wire:loading.attr' => 'disabled']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','wire:loading.attr' => 'disabled']); ?>
                <?php echo e(__('dashboard.Save changes')); ?>

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
    </form>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\dashboard\settings\options\gdpr.blade.php ENDPATH**/ ?>