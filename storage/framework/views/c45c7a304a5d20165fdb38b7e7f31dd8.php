<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'role' => null,
    'clusters',
    'selected' => [],
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
    'role' => null,
    'clusters',
    'selected' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $title = old('title', $role?->title);
    $summary = old('summary', $role?->summary);
?>

<div class="space-y-4">
    <?php if (isset($component)) { $__componentOriginald99998d64b0875088b034c3416ebc71a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald99998d64b0875088b034c3416ebc71a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => ['class' => 'space-y-4 !border-0 !p-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-4 !border-0 !p-5']); ?>
        <p class="mb-4 text-xs font-semibold uppercase tracking-[0.14em] text-krikkit-subtle"><?php echo e(__('dashboard.Identity')); ?></p>

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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::input','data' => ['name' => 'title','id' => 'title','value' => $title,'placeholder' => __('dashboard.Editor'),'invalid' => $errors->has('title'),'required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'title','id' => 'title','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Editor')),'invalid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->has('title')),'required' => true]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => __('dashboard.Summary')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Summary'))]); ?>
            <?php if (isset($component)) { $__componentOriginal79c913eebc08aa2f1af8927c57771386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79c913eebc08aa2f1af8927c57771386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::input','data' => ['name' => 'summary','id' => 'summary','value' => $summary,'placeholder' => __('dashboard.Can revise content'),'invalid' => $errors->has('summary')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'summary','id' => 'summary','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($summary),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Can revise content')),'invalid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->has('summary'))]); ?>
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['summary'];
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

    <div class="space-y-4">
        <p class="px-5 text-xs font-semibold uppercase tracking-[0.14em] text-krikkit-subtle"><?php echo e(__('dashboard.Abilities')); ?></p>
        <?php if (isset($component)) { $__componentOriginalb3243ba10e007ba53a91aa300c38d246 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb3243ba10e007ba53a91aa300c38d246 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.access.abilityPicker','data' => ['clusters' => $clusters,'selected' => $selected]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('access.ability-picker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['clusters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($clusters),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selected)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb3243ba10e007ba53a91aa300c38d246)): ?>
<?php $attributes = $__attributesOriginalb3243ba10e007ba53a91aa300c38d246; ?>
<?php unset($__attributesOriginalb3243ba10e007ba53a91aa300c38d246); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb3243ba10e007ba53a91aa300c38d246)): ?>
<?php $component = $__componentOriginalb3243ba10e007ba53a91aa300c38d246; ?>
<?php unset($__componentOriginalb3243ba10e007ba53a91aa300c38d246); ?>
<?php endif; ?>
    </div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\dashboard\accessRoles\options\form.blade.php ENDPATH**/ ?>