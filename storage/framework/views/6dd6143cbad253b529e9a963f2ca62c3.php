<?php if (isset($component)) { $__componentOriginalb5ef3d2b72b7e0baad3346138f67d40e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb5ef3d2b72b7e0baad3346138f67d40e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.studio.frame','data' => ['user' => $user,'site' => $site,'planTitle' => $planTitle,'packOffer' => $packOffer]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('studio.frame'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user),'site' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($site),'plan-title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($planTitle),'pack-offer' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($packOffer)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <section class="px-4 pb-6 pt-8 sm:px-6 sm:pt-10 lg:px-10">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold tracking-tight text-krikkit-fg sm:text-3xl">
                <?php echo e(__('studio.Starred')); ?>

            </h1>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($starredProjects->isNotEmpty()): ?>
                <p class="text-sm text-krikkit-muted">
                    <?php echo e(__('studio.Projects you marked as favorites.')); ?>

                </p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($starredProjects->isEmpty()): ?>
        <div class="flex min-h-[min(28rem,calc(100dvh-18rem))] flex-1 items-center justify-center px-4">
            <?php if (isset($component)) { $__componentOriginal493de964ec33ccc8651d30a91261aaef = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal493de964ec33ccc8651d30a91261aaef = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::empty','data' => ['title' => __('studio.No starred projects'),'copy' => __('studio.Hover a project preview and tap the star to save it here.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::empty'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('studio.No starred projects')),'copy' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('studio.Hover a project preview and tap the star to save it here.'))]); ?>
                <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['href' => ''.e(route('projects')).'','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('projects')).'','wire:navigate' => true]); ?>
                    <?php echo e(__('studio.Browse projects')); ?>

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
<?php if (isset($__attributesOriginal493de964ec33ccc8651d30a91261aaef)): ?>
<?php $attributes = $__attributesOriginal493de964ec33ccc8651d30a91261aaef; ?>
<?php unset($__attributesOriginal493de964ec33ccc8651d30a91261aaef); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal493de964ec33ccc8651d30a91261aaef)): ?>
<?php $component = $__componentOriginal493de964ec33ccc8651d30a91261aaef; ?>
<?php unset($__componentOriginal493de964ec33ccc8651d30a91261aaef); ?>
<?php endif; ?>
        </div>
    <?php else: ?>
        <section class="px-4 pb-16 sm:px-6 lg:px-10">
            <div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $starredProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php echo $__env->make('livewire.studio.partials.projectCard', [
                            'project' => $project,
                            'stamp' => $project->starred_at,
                            'canExport' => $canExport,
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php echo $__env->make('livewire.studio.partials.projectModals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb5ef3d2b72b7e0baad3346138f67d40e)): ?>
<?php $attributes = $__attributesOriginalb5ef3d2b72b7e0baad3346138f67d40e; ?>
<?php unset($__attributesOriginalb5ef3d2b72b7e0baad3346138f67d40e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb5ef3d2b72b7e0baad3346138f67d40e)): ?>
<?php $component = $__componentOriginalb5ef3d2b72b7e0baad3346138f67d40e; ?>
<?php unset($__componentOriginalb5ef3d2b72b7e0baad3346138f67d40e); ?>
<?php endif; ?>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\studio\options\starred.blade.php ENDPATH**/ ?>