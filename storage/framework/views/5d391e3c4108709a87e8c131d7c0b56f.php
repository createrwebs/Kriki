<?php
    use App\Finance\FinanceCopy;
?>

<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-krikkit-fg"><?php echo e(__('dashboard.Credits')); ?></h1>
        <p class="mt-0.5 text-xs text-krikkit-muted"><?php echo e(__('dashboard.Purchased credits, manual grants, and adjustments. Usage stays in Lab.')); ?></p>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('allows', 'finance.revise')): ?>
        <form wire:submit="grant" class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <?php if (isset($component)) { $__componentOriginal33ee6131e877bec48f910ca20ac959e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33ee6131e877bec48f910ca20ac959e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => __('dashboard.User')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.User'))]); ?>
                    <?php if (isset($component)) { $__componentOriginal7a952551f265692c915c9af677c5ecd6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7a952551f265692c915c9af677c5ecd6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select','data' => ['size' => 'md','searchable' => true,'wire:model' => 'userPublicId','value' => $userPublicId,'placeholder' => ''.e(__('dashboard.Choose…')).'','invalid' => $errors->has('userPublicId')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','searchable' => true,'wire:model' => 'userPublicId','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($userPublicId),'placeholder' => ''.e(__('dashboard.Choose…')).'','invalid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->has('userPublicId'))]); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $people; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $person): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalbc5c56c7c0bb48775665c8519b14d96e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select.option','data' => ['value' => $person->public_id,'selected' => $userPublicId === $person->public_id]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select.option'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($person->public_id),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($userPublicId === $person->public_id)]); ?>
                                <?php echo e($person->name); ?> · <?php echo e($person->email); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e)): ?>
<?php $attributes = $__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e; ?>
<?php unset($__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc5c56c7c0bb48775665c8519b14d96e)): ?>
<?php $component = $__componentOriginalbc5c56c7c0bb48775665c8519b14d96e; ?>
<?php unset($__componentOriginalbc5c56c7c0bb48775665c8519b14d96e); ?>
<?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['userPublicId'];
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => __('dashboard.Kind')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Kind'))]); ?>
                    <?php if (isset($component)) { $__componentOriginal7a952551f265692c915c9af677c5ecd6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7a952551f265692c915c9af677c5ecd6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select','data' => ['size' => 'md','wire:model.live' => 'kind','value' => $kind,'placeholder' => ''.e(__('dashboard.Choose…')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','wire:model.live' => 'kind','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($kind),'placeholder' => ''.e(__('dashboard.Choose…')).'']); ?>
                        <?php if (isset($component)) { $__componentOriginalbc5c56c7c0bb48775665c8519b14d96e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select.option','data' => ['value' => 'grant','selected' => $kind === 'grant']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select.option'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'grant','selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($kind === 'grant')]); ?><?php echo e(__('dashboard.Manual grant')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select.option','data' => ['value' => 'topup','selected' => $kind === 'topup']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select.option'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'topup','selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($kind === 'topup')]); ?><?php echo e(__('dashboard.Top-up')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select.option','data' => ['value' => 'adjustment','selected' => $kind === 'adjustment']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select.option'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'adjustment','selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($kind === 'adjustment')]); ?><?php echo e(__('dashboard.Adjustment')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php if (isset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $attributes = $__attributesOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $component = $__componentOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__componentOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kind === 'topup'): ?>
                    <?php if (isset($component)) { $__componentOriginal33ee6131e877bec48f910ca20ac959e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33ee6131e877bec48f910ca20ac959e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => __('dashboard.Top-up pack')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Top-up pack'))]); ?>
                        <?php if (isset($component)) { $__componentOriginal7a952551f265692c915c9af677c5ecd6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7a952551f265692c915c9af677c5ecd6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select','data' => ['size' => 'md','wire:model' => 'planPublicId','value' => $planPublicId,'placeholder' => ''.e(__('dashboard.Choose…')).'','invalid' => $errors->has('planPublicId')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','wire:model' => 'planPublicId','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($planPublicId),'placeholder' => ''.e(__('dashboard.Choose…')).'','invalid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->has('planPublicId'))]); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginalbc5c56c7c0bb48775665c8519b14d96e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select.option','data' => ['value' => $plan->public_id,'selected' => $planPublicId === $plan->public_id]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select.option'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($plan->public_id),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($planPublicId === $plan->public_id)]); ?>
                                    <?php echo e($plan->title); ?>

                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e)): ?>
<?php $attributes = $__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e; ?>
<?php unset($__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc5c56c7c0bb48775665c8519b14d96e)): ?>
<?php $component = $__componentOriginalbc5c56c7c0bb48775665c8519b14d96e; ?>
<?php unset($__componentOriginalbc5c56c7c0bb48775665c8519b14d96e); ?>
<?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['planPublicId'];
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
                <?php else: ?>
                    <?php if (isset($component)) { $__componentOriginal33ee6131e877bec48f910ca20ac959e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33ee6131e877bec48f910ca20ac959e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => __('dashboard.Amount')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Amount'))]); ?>
                        <?php if (isset($component)) { $__componentOriginal79c913eebc08aa2f1af8927c57771386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79c913eebc08aa2f1af8927c57771386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::input','data' => ['size' => 'md','type' => 'number','wire:model' => 'amount','placeholder' => '+100','invalid' => $errors->has('amount')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','type' => 'number','wire:model' => 'amount','placeholder' => '+100','invalid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->has('amount'))]); ?>
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['amount'];
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
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal33ee6131e877bec48f910ca20ac959e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33ee6131e877bec48f910ca20ac959e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => __('dashboard.Reason')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Reason'))]); ?>
                    <?php if (isset($component)) { $__componentOriginal79c913eebc08aa2f1af8927c57771386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79c913eebc08aa2f1af8927c57771386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::input','data' => ['size' => 'md','wire:model' => 'reason','placeholder' => __('dashboard.Why these credits were added.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','wire:model' => 'reason','placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Why these credits were added.'))]); ?>
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
            </div>

            <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit']); ?><?php echo e(__('dashboard.Grant credits')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $attributes = $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $component = $__componentOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
        </form>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="flex flex-wrap items-end gap-3">
        <div class="min-w-56 flex-1">
            <?php if (isset($component)) { $__componentOriginal79c913eebc08aa2f1af8927c57771386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79c913eebc08aa2f1af8927c57771386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::input','data' => ['size' => 'sm','wire:model.live.debounce.300ms' => 'search','placeholder' => __('dashboard.Search…')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','wire:model.live.debounce.300ms' => 'search','placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Search…'))]); ?>
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
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rows->isEmpty()): ?>
        <p class="text-sm text-krikkit-muted"><?php echo e(__('dashboard.No credit grants yet. Manual grants and top-ups appear here.')); ?></p>
    <?php else: ?>
        <?php if (isset($component)) { $__componentOriginald9f9621c9792e26cb0a786db19dbfd95 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald9f9621c9792e26cb0a786db19dbfd95 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <?php if (isset($component)) { $__componentOriginal8ea4f77adc03babec838eacd9b78df96 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ea4f77adc03babec838eacd9b78df96 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.columns','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.columns'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.User')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Amount')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Kind')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Reason')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Granted by')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.When')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8ea4f77adc03babec838eacd9b78df96)): ?>
<?php $attributes = $__attributesOriginal8ea4f77adc03babec838eacd9b78df96; ?>
<?php unset($__attributesOriginal8ea4f77adc03babec838eacd9b78df96); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8ea4f77adc03babec838eacd9b78df96)): ?>
<?php $component = $__componentOriginal8ea4f77adc03babec838eacd9b78df96; ?>
<?php unset($__componentOriginal8ea4f77adc03babec838eacd9b78df96); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.rows','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.rows'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.row','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <p class="truncate text-xs font-medium text-krikkit-fg"><?php echo e($grant->user?->name ?: '—'); ?></p>
                            <p class="truncate text-[11px] text-krikkit-muted"><?php echo e($grant->user?->email); ?></p>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <span class="tabular-nums font-medium text-krikkit-fg"><?php echo e($grant->amount > 0 ? '+'.$grant->amount : $grant->amount); ?></span>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <span class="text-krikkit-muted"><?php echo e(FinanceCopy::creditKind($grant->kind)); ?></span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($grant->plan): ?>
                                <p class="text-[11px] text-krikkit-subtle"><?php echo e($grant->plan->title); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <span class="text-krikkit-muted"><?php echo e($grant->reason ?: '—'); ?></span>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <span class="text-krikkit-muted"><?php echo e($grant->actor?->name ?: '—'); ?></span>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <span class="text-[11px] text-krikkit-muted"><?php echo e($grant->created_at?->format('M j, Y H:i')); ?></span>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f)): ?>
<?php $attributes = $__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f; ?>
<?php unset($__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f)): ?>
<?php $component = $__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f; ?>
<?php unset($__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f); ?>
<?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7)): ?>
<?php $attributes = $__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7; ?>
<?php unset($__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7)): ?>
<?php $component = $__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7; ?>
<?php unset($__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald9f9621c9792e26cb0a786db19dbfd95)): ?>
<?php $attributes = $__attributesOriginald9f9621c9792e26cb0a786db19dbfd95; ?>
<?php unset($__attributesOriginald9f9621c9792e26cb0a786db19dbfd95); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald9f9621c9792e26cb0a786db19dbfd95)): ?>
<?php $component = $__componentOriginald9f9621c9792e26cb0a786db19dbfd95; ?>
<?php unset($__componentOriginald9f9621c9792e26cb0a786db19dbfd95); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal7d2f8b5bd08c6d86125d7a58297905d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7d2f8b5bd08c6d86125d7a58297905d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::pagination','data' => ['goto' => 'gotoPage','current' => $rows->currentPage(),'last' => $rows->lastPage(),'total' => $rows->total(),'firstItem' => $rows->firstItem(),'lastItem' => $rows->lastItem()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['goto' => 'gotoPage','current' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($rows->currentPage()),'last' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($rows->lastPage()),'total' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($rows->total()),'first-item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($rows->firstItem()),'last-item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($rows->lastItem())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7d2f8b5bd08c6d86125d7a58297905d1)): ?>
<?php $attributes = $__attributesOriginal7d2f8b5bd08c6d86125d7a58297905d1; ?>
<?php unset($__attributesOriginal7d2f8b5bd08c6d86125d7a58297905d1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7d2f8b5bd08c6d86125d7a58297905d1)): ?>
<?php $component = $__componentOriginal7d2f8b5bd08c6d86125d7a58297905d1; ?>
<?php unset($__componentOriginal7d2f8b5bd08c6d86125d7a58297905d1); ?>
<?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\dashboard\credits\credits.blade.php ENDPATH**/ ?>