<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'page',
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
    'variant' => 'page',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $variant = in_array($variant, ['page', 'table', 'cards', 'form'], true) ? $variant : 'page';
?>

<div <?php echo e($attributes->class('animate-pulse space-y-6')); ?> aria-hidden="true" role="status">
    <div class="space-y-3">
        <?php if (isset($component)) { $__componentOriginal77b268ecebe925cd66da867f68dee49c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77b268ecebe925cd66da867f68dee49c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-8 w-48 max-w-full','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-8 w-48 max-w-full','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-4 w-2/3 max-w-md','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-4 w-2/3 max-w-md','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($variant === 'table'): ?>
        <div class="overflow-hidden rounded-xl border border-krikkit-line">
            <div class="grid grid-cols-2 gap-4 border-b border-krikkit-line bg-krikkit-soft/60 px-4 py-3 sm:grid-cols-4">
                <?php if (isset($component)) { $__componentOriginal77b268ecebe925cd66da867f68dee49c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77b268ecebe925cd66da867f68dee49c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-3 w-16','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-3 w-16','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'hidden h-3 w-20 sm:block','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'hidden h-3 w-20 sm:block','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'hidden h-3 w-14 sm:block','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'hidden h-3 w-14 sm:block','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'ml-auto h-3 w-10','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'ml-auto h-3 w-10','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = range(1, 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="grid grid-cols-2 gap-4 border-b border-krikkit-line px-4 py-3.5 last:border-b-0 sm:grid-cols-4">
                    <div class="flex items-center gap-2">
                        <?php if (isset($component)) { $__componentOriginal77b268ecebe925cd66da867f68dee49c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77b268ecebe925cd66da867f68dee49c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'size-7 shrink-0 rounded-full','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-7 shrink-0 rounded-full','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-3.5 w-24 max-w-full','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-3.5 w-24 max-w-full','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
                    <?php if (isset($component)) { $__componentOriginal77b268ecebe925cd66da867f68dee49c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77b268ecebe925cd66da867f68dee49c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'hidden h-3.5 w-36 max-w-full sm:block','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'hidden h-3.5 w-36 max-w-full sm:block','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'hidden h-5 w-20 rounded-full sm:block','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'hidden h-5 w-20 rounded-full sm:block','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'ml-auto size-7 rounded-lg','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'ml-auto size-7 rounded-lg','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    <?php elseif($variant === 'cards'): ?>
        <div class="grid gap-3 sm:grid-cols-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = range(1, 4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="space-y-4 rounded-xl border border-krikkit-line bg-krikkit-surface p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0 flex-1 space-y-2">
                            <?php if (isset($component)) { $__componentOriginal77b268ecebe925cd66da867f68dee49c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77b268ecebe925cd66da867f68dee49c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-5 w-28','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-28','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-3 w-40 max-w-full','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-3 w-40 max-w-full','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
                        <?php if (isset($component)) { $__componentOriginal77b268ecebe925cd66da867f68dee49c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77b268ecebe925cd66da867f68dee49c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-5 w-12 rounded-full','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-12 rounded-full','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
                    <div class="flex gap-1">
                        <?php if (isset($component)) { $__componentOriginal77b268ecebe925cd66da867f68dee49c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77b268ecebe925cd66da867f68dee49c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-5 w-16 rounded-full','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-16 rounded-full','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-5 w-14 rounded-full','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-14 rounded-full','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-5 w-10 rounded-full','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-10 rounded-full','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
                    <?php if (isset($component)) { $__componentOriginal77b268ecebe925cd66da867f68dee49c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77b268ecebe925cd66da867f68dee49c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-8 w-20 rounded-lg','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-8 w-20 rounded-lg','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    <?php elseif($variant === 'form'): ?>
        <div class="max-w-lg space-y-5">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = range(1, 4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="space-y-2">
                    <?php if (isset($component)) { $__componentOriginal77b268ecebe925cd66da867f68dee49c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77b268ecebe925cd66da867f68dee49c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-3.5 w-24','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-3.5 w-24','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-9 w-full rounded-lg','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-9 w-full rounded-lg','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal77b268ecebe925cd66da867f68dee49c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77b268ecebe925cd66da867f68dee49c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-9 w-28 rounded-lg','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-9 w-28 rounded-lg','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
    <?php else: ?>
        <div class="space-y-3">
            <?php if (isset($component)) { $__componentOriginal77b268ecebe925cd66da867f68dee49c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77b268ecebe925cd66da867f68dee49c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-4 w-full','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-4 w-full','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-4 w-11/12','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-4 w-11/12','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-4 w-4/5','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-4 w-4/5','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
        <div class="grid gap-3 sm:grid-cols-2">
            <?php if (isset($component)) { $__componentOriginal77b268ecebe925cd66da867f68dee49c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77b268ecebe925cd66da867f68dee49c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-28 w-full rounded-xl','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-28 w-full rounded-xl','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-28 w-full rounded-xl','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-28 w-full rounded-xl','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
        <div class="space-y-3 rounded-xl border border-krikkit-line p-4">
            <?php if (isset($component)) { $__componentOriginal77b268ecebe925cd66da867f68dee49c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77b268ecebe925cd66da867f68dee49c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-4 w-1/3','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-4 w-1/3','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-4 w-full','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-4 w-full','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-4 w-5/6','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-4 w-5/6','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::skeleton','data' => ['class' => 'h-24 w-full rounded-lg','animate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-24 w-full rounded-lg','animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <span class="sr-only"><?php echo e(__('dashboard.Loading…')); ?></span>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\components\dashboard\pageSkeleton.blade.php ENDPATH**/ ?>