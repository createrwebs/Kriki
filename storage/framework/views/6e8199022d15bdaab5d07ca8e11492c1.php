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

<div class="relative border-t border-krikkit-line">
    <div class="krikkit-home-pinstripe h-7" aria-hidden="true"></div>
    <div class="krikkit-home-dots relative overflow-hidden border-y border-krikkit-line">
        <div class="krikkit-home-marquee flex w-max select-none items-center">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [0, 1]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $copy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <ul class="flex items-center" <?php if($copy === 1): ?> aria-hidden="true" <?php endif; ?>>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <li class="flex h-[4.75rem] items-center gap-2.5 px-8 text-krikkit-muted">
                            <?php echo $__env->make('home.partials.brand', ['name' => $brand['id']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <span class="text-sm font-medium tracking-wide"><?php echo e($brand['label']); ?></span>
                        </li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ul>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>
    <div class="krikkit-home-pinstripe h-7" aria-hidden="true"></div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\home\partials\brands.blade.php ENDPATH**/ ?>