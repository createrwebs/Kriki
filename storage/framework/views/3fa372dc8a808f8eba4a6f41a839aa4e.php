<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label',
    'hint' => null,
    'property',
    'preview' => null,
    'accept' => 'image/png,image/jpeg,image/webp,image/svg+xml',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'label',
    'hint' => null,
    'property',
    'preview' => null,
    'accept' => 'image/png,image/jpeg,image/webp,image/svg+xml',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="space-y-1.5">
    <div class="flex items-center justify-between gap-3">
        <?php if (isset($component)) { $__componentOriginale95aca2093cc558f5e1130907b1d1870 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale95aca2093cc558f5e1130907b1d1870 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field.label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e($label); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale95aca2093cc558f5e1130907b1d1870)): ?>
<?php $attributes = $__attributesOriginale95aca2093cc558f5e1130907b1d1870; ?>
<?php unset($__attributesOriginale95aca2093cc558f5e1130907b1d1870); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale95aca2093cc558f5e1130907b1d1870)): ?>
<?php $component = $__componentOriginale95aca2093cc558f5e1130907b1d1870; ?>
<?php unset($__componentOriginale95aca2093cc558f5e1130907b1d1870); ?>
<?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($preview): ?>
            <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['type' => 'button','variant' => 'ghost','size' => 'sm','wire:click' => 'dropAsset(\''.e($property).'\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'ghost','size' => 'sm','wire:click' => 'dropAsset(\''.e($property).'\')']); ?>
                <?php echo e(__('dashboard.Remove')); ?>

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
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php if (isset($component)) { $__componentOriginalf8a78d95f225ff61645792efb2c3c2a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8a78d95f225ff61645792efb2c3c2a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::fileUpload','data' => ['accept' => $accept,'label' => __('dashboard.Drop files here or browse'),'caption' => $hint ?? __('dashboard.PNG, JPG, SVG…'),'preview' => $preview,'wire:model' => ''.e($property).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::fileUpload'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['accept' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($accept),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Drop files here or browse')),'caption' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hint ?? __('dashboard.PNG, JPG, SVG…')),'preview' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($preview),'wire:model' => ''.e($property).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf8a78d95f225ff61645792efb2c3c2a8)): ?>
<?php $attributes = $__attributesOriginalf8a78d95f225ff61645792efb2c3c2a8; ?>
<?php unset($__attributesOriginalf8a78d95f225ff61645792efb2c3c2a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf8a78d95f225ff61645792efb2c3c2a8)): ?>
<?php $component = $__componentOriginalf8a78d95f225ff61645792efb2c3c2a8; ?>
<?php unset($__componentOriginalf8a78d95f225ff61645792efb2c3c2a8); ?>
<?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = [$property];
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
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\dashboard\settings\options\asset.blade.php ENDPATH**/ ?>