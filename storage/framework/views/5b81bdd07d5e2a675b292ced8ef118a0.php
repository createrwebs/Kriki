<section class="border-b border-krikkit-line">
    <div class="relative mx-auto max-w-7xl overflow-hidden border-x border-krikkit-line">
        <div class="pointer-events-none absolute inset-0 krikkit-home-dots" aria-hidden="true"></div>
        <div class="relative px-6 pt-20 text-center md:px-12 md:pt-28">
            <?php echo $__env->make('home.partials.badge', ['label' => __('home.By the numbers')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <h2 class="mx-auto mt-6 max-w-2xl text-3xl font-medium leading-[1.15] tracking-tight text-krikkit-fg md:text-5xl">
                <?php echo e(__('home.Built around the Lab desk, not a pile of tabs.')); ?>

            </h2>
        </div>
        <div class="relative mt-12 grid gap-px border-t border-krikkit-line bg-krikkit-line sm:grid-cols-2 lg:grid-cols-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                ['value' => __('home.One desk'), 'hint' => __('home.Brief, files, and canvas')],
                ['value' => __('home.Real files'), 'hint' => __('home.Markup you can open')],
                ['value' => __('home.Pack credits'), 'hint' => __('home.Spend only on generation')],
                ['value' => __('home.Your domain'), 'hint' => __('home.When a pack unlocks it')],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <article class="bg-krikkit-canvas px-6 py-12 text-center md:px-8">
                    <p class="text-3xl font-medium tracking-tight text-krikkit-fg md:text-4xl"><?php echo e($stat['value']); ?></p>
                    <p class="mt-2 text-sm text-krikkit-muted"><?php echo e($stat['hint']); ?></p>
                </article>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>
</section>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\home\partials\numbers.blade.php ENDPATH**/ ?>