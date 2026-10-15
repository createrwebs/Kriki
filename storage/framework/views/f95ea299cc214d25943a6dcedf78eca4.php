<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'animate' => true,
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
    'animate' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="block min-w-0" aria-hidden="true">
    <?php if (isset($component)) { $__componentOriginal77b268ecebe925cd66da867f68dee49c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77b268ecebe925cd66da867f68dee49c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['animate' => $animate,'class' => 'aspect-video w-full rounded-xl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($animate),'class' => 'aspect-video w-full rounded-xl']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal77b268ecebe925cd66da867f68dee49c)): ?>
<?php $attributes = $__attributesOriginal77b268ecebe925cd66da867f68dee49c; ?>
<?php unset($__attributesOriginal77b268ecebe925cd66da867f68dee49c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal77b268ecebe925cd66da867f68dee49c)): ?>
<?php $component = $__componentOriginal77b268ecebe925cd66da867f68dee49c; ?>
<?php unset($__componentOriginal77b268ecebe925cd66da867f68dee49c); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal77b268ecebe925cd66da867f68dee49c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77b268ecebe925cd66da867f68dee49c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['animate' => $animate,'class' => 'mt-2.5 h-3.5 w-3/4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($animate),'class' => 'mt-2.5 h-3.5 w-3/4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal77b268ecebe925cd66da867f68dee49c)): ?>
<?php $attributes = $__attributesOriginal77b268ecebe925cd66da867f68dee49c; ?>
<?php unset($__attributesOriginal77b268ecebe925cd66da867f68dee49c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal77b268ecebe925cd66da867f68dee49c)): ?>
<?php $component = $__componentOriginal77b268ecebe925cd66da867f68dee49c; ?>
<?php unset($__componentOriginal77b268ecebe925cd66da867f68dee49c); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal77b268ecebe925cd66da867f68dee49c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77b268ecebe925cd66da867f68dee49c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['animate' => $animate,'class' => 'mt-1.5 h-2.5 w-1/3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($animate),'class' => 'mt-1.5 h-2.5 w-1/3']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal77b268ecebe925cd66da867f68dee49c)): ?>
<?php $attributes = $__attributesOriginal77b268ecebe925cd66da867f68dee49c; ?>
<?php unset($__attributesOriginal77b268ecebe925cd66da867f68dee49c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal77b268ecebe925cd66da867f68dee49c)): ?>
<?php $component = $__componentOriginal77b268ecebe925cd66da867f68dee49c; ?>
<?php unset($__componentOriginal77b268ecebe925cd66da867f68dee49c); ?>
<?php endif; ?>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views/livewire/studio/partials/projectSkeleton.blade.php ENDPATH**/ ?>