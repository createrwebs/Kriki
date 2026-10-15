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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::input','data' => ['size' => 'md','wire:model' => 'faq.title']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','wire:model' => 'faq.title']); ?>
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
    <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.FAQ')); ?></p>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $faq['items'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <div class="space-y-3 border-t border-krikkit-line pt-4 first:border-t-0 first:pt-0">
            <div class="flex justify-end">
                <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['type' => 'button','variant' => 'ghost','square' => true,'size' => 'sm','wire:click' => 'removeFaq('.e($index).')','ariaLabel' => __('dashboard.Remove')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'ghost','square' => true,'size' => 'sm','wire:click' => 'removeFaq('.e($index).')','aria-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Remove'))]); ?>
                    <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'x-mark','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x-mark','class' => 'size-4']); ?>
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
            <?php if (isset($component)) { $__componentOriginal33ee6131e877bec48f910ca20ac959e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33ee6131e877bec48f910ca20ac959e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => __('dashboard.Question')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Question'))]); ?>
                <?php if (isset($component)) { $__componentOriginal79c913eebc08aa2f1af8927c57771386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79c913eebc08aa2f1af8927c57771386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::input','data' => ['size' => 'md','wire:model' => 'faq.items.'.e($index).'.q']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','wire:model' => 'faq.items.'.e($index).'.q']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => __('dashboard.Answer')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Answer'))]); ?>
                <?php if (isset($component)) { $__componentOriginal45db5a78be6c3060441cff930126a416 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal45db5a78be6c3060441cff930126a416 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::textarea','data' => ['rows' => '3','wire:model' => 'faq.items.'.e($index).'.a']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['rows' => '3','wire:model' => 'faq.items.'.e($index).'.a']); ?>
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
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['type' => 'button','variant' => 'outline','size' => 'sm','wire:click' => 'addFaq']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','size' => 'sm','wire:click' => 'addFaq']); ?><?php echo e(__('dashboard.Add question')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $attributes = $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $component = $__componentOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
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
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\dashboard\landing\options\faq.blade.php ENDPATH**/ ?>