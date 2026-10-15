<?php
    $brands = [
        ['id' => 'claude', 'label' => 'Claude'],
        ['id' => 'openai', 'label' => 'OpenAI'],
        ['id' => 'github', 'label' => 'GitHub'],
        ['id' => 'deepseek', 'label' => 'DeepSeek'],
        ['id' => 'xai', 'label' => 'xAI'],
        ['id' => 'mistral', 'label' => 'Mistral'],
        ['id' => 'zhipu', 'label' => 'Zhipu'],
        ['id' => 'gemini', 'label' => 'Google AI Studio'],
        ['id' => 'groq', 'label' => 'Groq'],
    ];
?>

<?php
    $integrations = $landing->section('integrations');
?>

<section id="integrations" class="border-b border-krikkit-line">
    <div class="relative mx-auto max-w-7xl overflow-hidden border-x border-krikkit-line">
        <div class="relative flex flex-col items-center px-6 pt-20 text-center sm:px-10 md:pt-28">
            <span class="inline-flex items-center gap-2 border border-krikkit-line bg-krikkit-soft px-3 py-1.5">
                <span class="size-1.5 bg-accent"></span>
                <span class="text-[11px] font-semibold uppercase tracking-[0.15em] text-krikkit-fg-soft"><?php echo e($integrations['eyebrow']); ?></span>
            </span>
            <h2 class="mt-6 max-w-2xl text-3xl font-medium leading-[1.15] tracking-tight text-krikkit-fg sm:text-5xl">
                <?php echo e($integrations['title']); ?>

            </h2>
        </div>
        <div class="relative mt-12 grid grid-cols-3 gap-px border-t border-krikkit-line bg-krikkit-line">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="flex h-40 flex-col items-center justify-center gap-3 bg-krikkit-canvas sm:h-52">
                    <?php echo $__env->make('home.partials.brand', ['name' => $brand['id'], 'iconClass' => 'size-12 sm:size-16'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <span class="px-2 text-center text-sm font-medium tracking-wide text-krikkit-fg-soft"><?php echo e($brand['label']); ?></span>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
        <div class="relative border-t border-krikkit-line px-6 py-8 text-center">
            <a href="<?php echo e($landing->href($integrations['cta_href'] ?? '', '#questions')); ?>" class="text-sm font-medium text-krikkit-fg hover:text-accent-content"><?php echo e($integrations['cta_label']); ?></a>
        </div>
    </div>
</section>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\home\partials\integrations.blade.php ENDPATH**/ ?>