<?php
    $agents = $landing->section('agents');
    $items = is_array($agents['items'] ?? null) ? $agents['items'] : [];
?>

<section id="agents" class="border-b border-krikkit-line">
    <div class="relative mx-auto max-w-7xl overflow-hidden border-x border-krikkit-line">
        <div class="relative px-6 pt-20 sm:px-10 md:px-12 md:pt-28">
            <h2 class="max-w-2xl text-3xl font-medium leading-[1.15] tracking-tight text-krikkit-fg sm:text-5xl">
                <?php echo e($agents['title']); ?>

            </h2>
            <p class="mt-5 max-w-xl text-sm leading-relaxed text-krikkit-muted sm:text-base">
                <?php echo e($agents['copy']); ?>

            </p>
        </div>
        <div class="relative mt-12 grid gap-px border-t border-krikkit-line bg-krikkit-line sm:grid-cols-2 lg:grid-cols-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <article class="bg-krikkit-canvas p-6 sm:p-8">
                    <h3 class="text-base font-semibold text-krikkit-fg"><?php echo e($agent['title']); ?></h3>
                    <p class="mt-3 text-sm leading-relaxed text-krikkit-muted"><?php echo e($agent['copy']); ?></p>
                </article>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>
</section>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\home\partials\agents.blade.php ENDPATH**/ ?>