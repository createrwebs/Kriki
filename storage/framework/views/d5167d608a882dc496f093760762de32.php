<?php
    use App\Finance\FinanceCopy;
?>

<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-krikkit-fg"><?php echo e(__('dashboard.Subscriptions')); ?></h1>
        <p class="mt-0.5 text-xs text-krikkit-muted"><?php echo e(__('dashboard.Who holds which pack, interval, and provider subscription.')); ?></p>
    </div>

    <?php echo $__env->make('livewire.dashboard.finance.options.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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
        <div class="w-44">
            <?php if (isset($component)) { $__componentOriginal7a952551f265692c915c9af677c5ecd6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7a952551f265692c915c9af677c5ecd6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select','data' => ['size' => 'sm','wire:model.live' => 'status','value' => $status,'placeholder' => ''.e(__('dashboard.Status')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','wire:model.live' => 'status','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($status),'placeholder' => ''.e(__('dashboard.Status')).'']); ?>
                <?php if (isset($component)) { $__componentOriginalbc5c56c7c0bb48775665c8519b14d96e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select.option','data' => ['value' => '','selected' => $status === '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select.option'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => '','selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($status === '')]); ?><?php echo e(__('dashboard.All')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select.option','data' => ['value' => 'active','selected' => $status === 'active']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select.option'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'active','selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($status === 'active')]); ?><?php echo e(__('dashboard.Active')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select.option','data' => ['value' => 'past_due','selected' => $status === 'past_due']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select.option'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'past_due','selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($status === 'past_due')]); ?><?php echo e(__('dashboard.Past due')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select.option','data' => ['value' => 'canceled','selected' => $status === 'canceled']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select.option'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'canceled','selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($status === 'canceled')]); ?><?php echo e(__('dashboard.Canceled')); ?> <?php echo $__env->renderComponent(); ?>
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
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rows->isEmpty()): ?>
        <p class="text-sm text-krikkit-muted"><?php echo e(__('dashboard.No subscriptions match.')); ?></p>
    <?php else: ?>
        <?php if (isset($component)) { $__componentOriginald9f9621c9792e26cb0a786db19dbfd95 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald9f9621c9792e26cb0a786db19dbfd95 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table','data' => ['fit' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['fit' => true]); ?>
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
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Pack')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Interval')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Status')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Period')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Last payment')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => ['align' => 'right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right']); ?> <?php echo $__env->renderComponent(); ?>
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
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php $tone = FinanceCopy::subscription($row->status); ?>
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
                            <p class="truncate text-xs font-medium text-krikkit-fg"><?php echo e($row->user?->name ?: '—'); ?></p>
                            <p class="truncate text-[11px] text-krikkit-muted"><?php echo e($row->user?->email); ?></p>
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
                            <span class="text-krikkit-fg"><?php echo e($row->plan?->title ?: '—'); ?></span>
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
                            <span class="text-krikkit-muted"><?php echo e(FinanceCopy::interval($row->interval)); ?></span>
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
                            <?php if (isset($component)) { $__componentOriginal0bc981f99055f90423de9ceafd4f5f18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::badge','data' => ['color' => $tone['color'],'size' => 'xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tone['color']),'size' => 'xs']); ?><?php echo e($tone['label']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $attributes = $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $component = $__componentOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
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
                            <p class="text-[11px] text-krikkit-muted">
                                <?php echo e($row->started_at?->format('M j, Y') ?: '—'); ?>

                                →
                                <?php echo e($row->ends_at?->format('M j, Y') ?: __('dashboard.Open')); ?>

                            </p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($row->provider_subscription_id)): ?>
                                <p class="truncate text-[11px] text-krikkit-subtle"><?php echo e($row->provider_subscription_id); ?></p>
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
                            <span class="text-[11px] text-krikkit-muted"><?php echo e($row->last_paid_at?->format('M j, Y') ?: '—'); ?></span>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => ['align' => 'right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right']); ?>
                            <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['href' => ''.e(route('dashboard.finance.subscriptions.show', $row)).'','variant' => 'ghost','square' => true,'size' => 'sm','ariaLabel' => ''.e(__('dashboard.Show')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('dashboard.finance.subscriptions.show', $row)).'','variant' => 'ghost','square' => true,'size' => 'sm','aria-label' => ''.e(__('dashboard.Show')).'']); ?>
                                <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'eye','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'eye','class' => 'size-4']); ?>
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
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\dashboard\finance\options\subscriptions.blade.php ENDPATH**/ ?>