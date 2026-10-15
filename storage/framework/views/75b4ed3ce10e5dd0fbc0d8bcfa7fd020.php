<section id="stack" class="scroll-mt-20 border-b border-krikkit-line">
    <div class="relative mx-auto max-w-7xl overflow-hidden border-x border-krikkit-line">
        <div class="pointer-events-none absolute inset-0 krikkit-home-dots" aria-hidden="true"></div>
        <div class="relative px-6 pt-20 md:px-12 md:pt-28">
            <?php echo $__env->make('home.partials.badge', ['label' => __('home.Stack')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <h2 class="mt-6 max-w-2xl text-3xl font-medium leading-[1.15] tracking-tight text-krikkit-fg md:text-5xl">
                <?php echo e(__('home.Connect the desk to what you already run')); ?>

            </h2>
            <p class="mt-5 max-w-xl text-sm leading-relaxed text-krikkit-muted sm:text-base">
                <?php echo e(__('home.GitHub import, domains, credits, and preview sit on the same workspace — not a pile of extra products.')); ?>

            </p>
        </div>
        <div class="relative mt-12 grid grid-cols-2 gap-px border-t border-krikkit-line bg-krikkit-line sm:grid-cols-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                ['icon' => 'document-text', 'label' => __('messages.Brief')],
                ['icon' => 'folder', 'label' => __('messages.Files')],
                ['icon' => 'eye', 'label' => __('messages.Preview')],
                ['icon' => 'code-bracket', 'label' => __('home.GitHub')],
                ['icon' => 'globe-alt', 'label' => __('home.Domain')],
                ['icon' => 'sparkles', 'label' => __('home.Credits')],
                ['icon' => 'rectangle-stack', 'label' => __('home.Packs')],
                ['icon' => 'user-group', 'label' => __('home.Roles')],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tile): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="flex flex-col items-center gap-3 bg-krikkit-canvas px-4 py-10">
                    <span class="inline-flex size-12 items-center justify-center border border-krikkit-line bg-krikkit-surface text-krikkit-fg">
                        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => $tile['icon'],'class' => 'size-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tile['icon']),'class' => 'size-5']); ?>
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
                    </span>
                    <p class="text-sm font-medium text-krikkit-fg"><?php echo e($tile['label']); ?></p>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>
</section>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\home\partials\stack.blade.php ENDPATH**/ ?>