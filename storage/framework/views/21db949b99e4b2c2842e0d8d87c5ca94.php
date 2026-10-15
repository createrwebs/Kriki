<div class="space-y-8">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div class="min-w-0">
            <h1 class="text-lg font-semibold tracking-tight text-krikkit-fg"><?php echo e(__('dashboard.Packs')); ?></h1>
            <p class="mt-0.5 text-xs text-krikkit-muted"><?php echo e(__('dashboard.Create, lock, and tune workspace packs.')); ?></p>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('allows', 'packs.compose')): ?>
            <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['href' => ''.e(route('dashboard.packs.create')).'','variant' => 'ghost','square' => true,'size' => 'sm','ariaLabel' => ''.e(__('dashboard.New pack')).'','title' => ''.e(__('dashboard.New pack')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('dashboard.packs.create')).'','variant' => 'ghost','square' => true,'size' => 'sm','aria-label' => ''.e(__('dashboard.New pack')).'','title' => ''.e(__('dashboard.New pack')).'']); ?>
                <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'plus','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'plus','class' => 'size-4']); ?>
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
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['lock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <?php if (isset($component)) { $__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::callout','data' => ['tone' => 'danger']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::callout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'danger']); ?><?php echo e($message); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05)): ?>
<?php $attributes = $__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05; ?>
<?php unset($__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05)): ?>
<?php $component = $__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05; ?>
<?php unset($__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05); ?>
<?php endif; ?>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['retire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <?php if (isset($component)) { $__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::callout','data' => ['tone' => 'danger']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::callout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'danger']); ?><?php echo e($message); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05)): ?>
<?php $attributes = $__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05; ?>
<?php unset($__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05)): ?>
<?php $component = $__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05; ?>
<?php unset($__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05); ?>
<?php endif; ?>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($packs->isEmpty()): ?>
        <p class="text-sm text-krikkit-muted"><?php echo e(__('dashboard.No packs yet.')); ?></p>
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
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Summary')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Pricing')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Holders')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Slug')); ?> <?php echo $__env->renderComponent(); ?>
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
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $packs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pack): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
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
                            <div class="flex min-w-0 items-center gap-2">
                                <p class="truncate text-xs font-medium text-krikkit-fg"><?php echo e($pack->title); ?></p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pack->is_default): ?>
                                    <?php if (isset($component)) { $__componentOriginal0bc981f99055f90423de9ceafd4f5f18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::badge','data' => ['size' => 'xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'xs']); ?><?php echo e(__('dashboard.Default')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $attributes = $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $component = $__componentOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
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
                            <span class="text-krikkit-fg-soft"><?php echo e($pack->summary ?: '—'); ?></span>
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
                            <span class="tabular-nums text-krikkit-muted"><?php echo e($pack->priceLine()); ?></span>
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
                            <span class="text-krikkit-muted"><?php echo e($pack->entitlements_count); ?></span>
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
                            <span class="text-krikkit-muted"><?php echo e($pack->slug); ?></span>
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
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pack->isLocked()): ?>
                                <?php if (isset($component)) { $__componentOriginal0bc981f99055f90423de9ceafd4f5f18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::badge','data' => ['color' => 'red','size' => 'xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'red','size' => 'xs']); ?><?php echo e(__('dashboard.Locked')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $attributes = $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $component = $__componentOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
                            <?php else: ?>
                                <?php if (isset($component)) { $__componentOriginal0bc981f99055f90423de9ceafd4f5f18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::badge','data' => ['color' => 'teal','size' => 'xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'teal','size' => 'xs']); ?><?php echo e(__('dashboard.Active')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $attributes = $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $component = $__componentOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => ['align' => 'right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right']); ?>
                            <div class="inline-flex items-center gap-1">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('allows', 'packs.revise')): ?>
                                    <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['href' => ''.e(route('dashboard.packs.edit', $pack)).'','variant' => 'ghost','square' => true,'size' => 'sm','ariaLabel' => ''.e(__('dashboard.Edit')).'','title' => ''.e(__('dashboard.Edit')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('dashboard.packs.edit', $pack)).'','variant' => 'ghost','square' => true,'size' => 'sm','aria-label' => ''.e(__('dashboard.Edit')).'','title' => ''.e(__('dashboard.Edit')).'']); ?>
                                        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'pencil','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'pencil','class' => 'size-4']); ?>
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
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($pack->is_default && $pack->is_active)): ?>
                                        <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['type' => 'button','variant' => 'ghost','square' => true,'size' => 'sm','wire:click' => 'askLock(\''.e($pack->public_id).'\')','ariaLabel' => ''.e($pack->isLocked() ? __('dashboard.Unlock') : __('dashboard.Lock')).'','title' => ''.e($pack->isLocked() ? __('dashboard.Unlock') : __('dashboard.Lock')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'ghost','square' => true,'size' => 'sm','wire:click' => 'askLock(\''.e($pack->public_id).'\')','aria-label' => ''.e($pack->isLocked() ? __('dashboard.Unlock') : __('dashboard.Lock')).'','title' => ''.e($pack->isLocked() ? __('dashboard.Unlock') : __('dashboard.Lock')).'']); ?>
                                            <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => $pack->isLocked() ? 'lock-open' : 'lock-closed','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pack->isLocked() ? 'lock-open' : 'lock-closed'),'class' => 'size-4']); ?>
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
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('allows', 'packs.retire')): ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($pack->isBuiltIn() || $pack->is_default)): ?>
                                        <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['type' => 'button','variant' => 'ghost','square' => true,'size' => 'sm','wire:click' => 'askRetire(\''.e($pack->public_id).'\')','ariaLabel' => ''.e(__('dashboard.Retire')).'','title' => ''.e(__('dashboard.Retire')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'ghost','square' => true,'size' => 'sm','wire:click' => 'askRetire(\''.e($pack->public_id).'\')','aria-label' => ''.e(__('dashboard.Retire')).'','title' => ''.e(__('dashboard.Retire')).'']); ?>
                                            <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'trash','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'trash','class' => 'size-4']); ?>
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
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
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
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal34261a58e399aca11ff469798d1463d5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal34261a58e399aca11ff469798d1463d5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::confirm','data' => ['name' => 'pack-confirm','title' => $confirmAction === 'retire' ? __('dashboard.Retire pack') : __('dashboard.Lock pack'),'copy' => $confirmAction === 'retire' ? __('dashboard.Retire this pack?') : __('dashboard.Lock this pack? Holders keep it; new assignments stop.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::confirm'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'pack-confirm','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($confirmAction === 'retire' ? __('dashboard.Retire pack') : __('dashboard.Lock pack')),'copy' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($confirmAction === 'retire' ? __('dashboard.Retire this pack?') : __('dashboard.Lock this pack? Holders keep it; new assignments stop.'))]); ?>
         <?php $__env->slot('action', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['type' => 'button','variant' => 'danger','wire:click' => 'confirmPending']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'danger','wire:click' => 'confirmPending']); ?>
                <?php echo e($confirmAction === 'retire' ? __('dashboard.Retire') : __('dashboard.Lock')); ?>

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
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal34261a58e399aca11ff469798d1463d5)): ?>
<?php $attributes = $__attributesOriginal34261a58e399aca11ff469798d1463d5; ?>
<?php unset($__attributesOriginal34261a58e399aca11ff469798d1463d5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal34261a58e399aca11ff469798d1463d5)): ?>
<?php $component = $__componentOriginal34261a58e399aca11ff469798d1463d5; ?>
<?php unset($__componentOriginal34261a58e399aca11ff469798d1463d5); ?>
<?php endif; ?>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\dashboard\packs\packs.blade.php ENDPATH**/ ?>