<?php
    $platform = $landing->section('platform');
    $layers = is_array($platform['layers'] ?? null) ? $platform['layers'] : [];
    $stats = is_array($platform['stats'] ?? null) ? $platform['stats'] : [];
?>

<section id="platform" class="border-b border-krikkit-line">
    <div class="relative mx-auto max-w-7xl overflow-hidden border-x border-krikkit-line">
        <div class="relative px-6 pt-20 sm:px-10 md:px-12 md:pt-28">
            <span class="inline-flex items-center gap-2 border border-krikkit-line bg-krikkit-soft px-3 py-1.5">
                <span class="size-1.5 bg-accent"></span>
                <span class="text-[11px] font-semibold uppercase tracking-[0.15em] text-krikkit-fg-soft"><?php echo e($platform['eyebrow']); ?></span>
            </span>
            <h2 class="mt-6 max-w-2xl text-3xl font-medium leading-[1.15] tracking-tight text-krikkit-fg sm:text-5xl">
                <?php echo e($platform['title']); ?>

            </h2>
            <p class="mt-5 max-w-xl text-sm leading-relaxed text-krikkit-muted sm:text-base">
                <?php echo e($platform['copy']); ?>

            </p>
        </div>
        <div class="relative mt-12 grid border-t border-krikkit-line lg:grid-cols-2">
            <div class="border-b border-krikkit-line lg:border-b-0 lg:border-r">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $layers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $layer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="border-b border-krikkit-line last:border-b-0">
                        <button
                            type="button"
                            class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left sm:px-8"
                            x-on:click="stack = stack === <?php echo e($index); ?> ? -1 : <?php echo e($index); ?>"
                        >
                            <span class="text-sm font-semibold text-krikkit-fg sm:text-base"><?php echo e($layer['title']); ?></span>
                            <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'chevron-down','class' => 'size-4 shrink-0 text-krikkit-muted transition','xBind:class' => 'stack === '.e($index).' ? \'rotate-180\' : \'\'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-down','class' => 'size-4 shrink-0 text-krikkit-muted transition','x-bind:class' => 'stack === '.e($index).' ? \'rotate-180\' : \'\'']); ?>
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
                        </button>
                        <div
                            class="krikkit-home-fold"
                            data-open="<?php echo e($index === 0 ? '1' : '0'); ?>"
                            :data-open="stack === <?php echo e($index); ?> ? '1' : '0'"
                        >
                            <div class="min-h-0 overflow-hidden">
                                <p class="px-6 pb-5 text-sm leading-relaxed text-krikkit-muted sm:px-8"><?php echo e($layer['copy']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
            <div class="flex flex-col justify-between bg-accent p-8 sm:p-10 lg:p-12">
                <p class="text-2xl font-semibold leading-snug text-accent-foreground sm:text-3xl">
                    <?php echo e($platform['quote']); ?>

                </p>
                <div class="mt-10">
                    <p class="text-sm font-semibold text-accent-foreground"><?php echo e($platform['quote_name']); ?></p>
                    <p class="text-sm text-accent-foreground/70"><?php echo e($platform['quote_role']); ?></p>
                </div>
                <div class="mt-10 flex flex-wrap gap-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <span class="bg-black/40 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-accent-foreground"><?php echo e($stat); ?></span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\home\partials\platform.blade.php ENDPATH**/ ?>