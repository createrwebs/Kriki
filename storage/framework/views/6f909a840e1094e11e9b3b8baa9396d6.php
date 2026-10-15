<?php
    $corners = [
        '-left-px -top-px border-l-2 border-t-2',
        '-right-px -top-px border-r-2 border-t-2',
        '-bottom-px -left-px border-b-2 border-l-2',
        '-bottom-px -right-px border-b-2 border-r-2',
    ];

    $rings = [
        ['label' => __('messages.Brief'), 'circles' => ['hatch', 'hatch']],
        ['label' => __('messages.Canvas'), 'circles' => ['empty', 'fill']],
        ['label' => __('messages.Files'), 'circles' => ['sky', 'empty']],
        ['label' => __('messages.Publish'), 'circles' => ['fill', 'empty'], 'hide' => true],
    ];

    $circleClass = [
        'empty' => 'border-accent',
        'hatch' => 'border-accent bg-[repeating-linear-gradient(-45deg,var(--color-krikkit-line),var(--color-krikkit-line)_1px,transparent_1px,transparent_4px)]',
        'fill' => 'border-accent bg-krikkit-canvas bg-[repeating-linear-gradient(-45deg,var(--color-accent),var(--color-accent)_1px,transparent_1px,transparent_4px)]',
        'sky' => 'z-10 border-sky-500 bg-krikkit-canvas bg-[repeating-linear-gradient(-45deg,var(--color-sky-500),var(--color-sky-500)_1px,transparent_1px,transparent_4px)]',
    ];
?>

<section id="mosaic" class="scroll-mt-20 border-b border-krikkit-line">
    <div class="relative mx-auto max-w-7xl overflow-hidden border-x border-krikkit-line py-16 md:py-24">
        <div class="pointer-events-none absolute inset-0 krikkit-home-dots" aria-hidden="true"></div>
        <div class="relative mx-auto grid max-w-5xl gap-4 px-6 lg:grid-cols-2">
            <article class="group relative border border-krikkit-line bg-krikkit-canvas">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $corners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $corner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <span class="absolute <?php echo e($corner); ?> block size-2 border-accent"></span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="p-6 pb-3">
                    <p class="flex items-center gap-2 text-sm text-krikkit-muted">
                        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'folder','variant' => 'mini','class' => 'block size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'folder','variant' => 'mini','class' => 'block size-4']); ?>
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
                        <?php echo e(__('messages.Files')); ?>

                    </p>
                    <p class="mt-8 text-2xl font-medium leading-snug tracking-tight text-krikkit-fg">
                        <?php echo e(__('home.Open the markup. The canvas is a preview of those files.')); ?>

                    </p>
                </div>
                <div class="relative mb-6 overflow-hidden border-t border-dashed border-krikkit-line sm:mb-0">
                    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(125%_125%_at_50%_0%,transparent_40%,var(--color-krikkit-soft),var(--color-krikkit-canvas)_125%)]"></div>
                    <div class="aspect-[76/59]">
                        <?php echo $__env->make('home.partials.desk', ['tone' => 'layers', 'framed' => false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>
            </article>

            <article class="group relative border border-krikkit-line bg-krikkit-canvas">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $corners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $corner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <span class="absolute <?php echo e($corner); ?> block size-2 border-accent"></span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="p-6 pb-3">
                    <p class="flex items-center gap-2 text-sm text-krikkit-muted">
                        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'computer-desktop','variant' => 'mini','class' => 'block size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'computer-desktop','variant' => 'mini','class' => 'block size-4']); ?>
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
                        <?php echo e(__('messages.Canvas')); ?>

                    </p>
                    <p class="mt-8 text-2xl font-medium leading-snug tracking-tight text-krikkit-fg">
                        <?php echo e(__('home.The preview refreshes as files land. No export step.')); ?>

                    </p>
                </div>
                <div class="relative mb-6 overflow-hidden sm:mb-0">
                    <div class="pointer-events-none absolute -inset-6 bg-[radial-gradient(50%_50%_at_75%_50%,transparent,var(--color-krikkit-canvas)_100%)]"></div>
                    <div class="aspect-[76/59] border-t border-krikkit-line">
                        <?php echo $__env->make('home.partials.desk', ['tone' => 'page', 'framed' => false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>
            </article>

            <article class="relative border border-krikkit-line bg-krikkit-canvas p-6 lg:col-span-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $corners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $corner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <span class="absolute <?php echo e($corner); ?> block size-2 border-accent"></span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <p class="mx-auto my-6 max-w-md text-balance text-center text-2xl font-medium tracking-tight text-krikkit-fg">
                    <?php echo e(__('home.One desk from the brief to a live page.')); ?>

                </p>
                <div class="flex justify-center gap-6 overflow-hidden">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $rings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ring): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="<?php echo \Illuminate\Support\Arr::toCssClasses(['hidden sm:block' => $ring['hide'] ?? false]); ?>">
                            <div class="w-fit bg-gradient-to-b from-krikkit-line to-transparent p-px">
                                <div class="relative flex aspect-square w-fit items-center -space-x-4 bg-gradient-to-b from-krikkit-canvas to-krikkit-soft/40 p-4">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ring['circles']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pattern): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <span class="size-7 rounded-full border sm:size-8 <?php echo e($circleClass[$pattern]); ?>"></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            </div>
                            <span class="mt-1.5 block text-center text-sm text-krikkit-muted"><?php echo e($ring['label']); ?></span>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </article>
        </div>
    </div>
</section>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\home\partials\mosaic.blade.php ENDPATH**/ ?>