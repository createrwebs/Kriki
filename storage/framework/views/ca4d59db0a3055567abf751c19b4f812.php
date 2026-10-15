<section id="platform" class="scroll-mt-20 border-b border-krikkit-line">
    <div class="mx-auto max-w-7xl border-x border-krikkit-line">
        <div class="px-6 pb-8 pt-16 md:px-12 md:pt-24">
            <p class="text-sm font-medium text-krikkit-muted"><?php echo e(__('home.Introducing Lab')); ?></p>
            <h2 class="mt-3 max-w-3xl text-3xl font-normal leading-[1.1] tracking-tight text-krikkit-fg md:text-5xl">
                <?php echo e(__('home.The workshop sites need to run from a single brief')); ?>

            </h2>
        </div>
        <div class="grid gap-px border-t border-krikkit-line bg-krikkit-line sm:grid-cols-2 lg:grid-cols-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                ['title' => __('messages.Brief'), 'copy' => __('home.Keep the brief, the files, and the canvas on one shared desk so every turn has context.')],
                ['title' => __('messages.Canvas'), 'copy' => __('home.Describe the page. Lab lays out sections, copy, and structure you can keep editing.')],
                ['title' => __('messages.Files'), 'copy' => __('home.Each turn writes real markup. Stay in the loop as the canvas updates.')],
                ['title' => __('messages.Publish'), 'copy' => __('home.Open any file, change it by hand, and publish when the draft holds.')],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pillar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <article class="bg-krikkit-canvas p-6 md:p-8">
                    <h3 class="text-sm font-medium text-krikkit-fg"><?php echo e($pillar['title']); ?></h3>
                    <p class="mt-3 text-sm leading-relaxed text-krikkit-muted"><?php echo e($pillar['copy']); ?></p>
                </article>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>
</section>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\home\partials\introducing.blade.php ENDPATH**/ ?>