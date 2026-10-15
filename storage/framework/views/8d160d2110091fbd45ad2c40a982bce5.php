<section id="how" class="scroll-mt-20 border-b border-krikkit-line">
    <div class="relative mx-auto max-w-7xl overflow-hidden border-x border-krikkit-line">
        <div class="pointer-events-none absolute inset-0 krikkit-home-dots" aria-hidden="true"></div>
        <div class="relative flex flex-col items-center px-6 pt-20 text-center md:px-12 md:pt-28">
            <?php echo $__env->make('home.partials.badge', ['label' => __('home.How it works')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <h2 class="mt-6 max-w-3xl text-3xl font-medium leading-[1.15] tracking-tight text-krikkit-fg md:text-5xl">
                <?php echo e(__('home.From kickoff to a live page in one sitting')); ?>

            </h2>
            <p class="mt-5 max-w-2xl text-sm leading-relaxed text-krikkit-muted sm:text-base">
                <?php echo e(__('home.A short path that keeps the tools you already have — browser, files, domain — in the loop.')); ?>

            </p>
        </div>
        <div class="relative mt-12 grid gap-px border-t border-krikkit-line bg-krikkit-line lg:grid-cols-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                ['n' => __('home.Step 01'), 'title' => __('home.Write the brief'), 'copy' => __('home.Say what the site is for. Lab turns that into pages, type, and layout.')],
                ['n' => __('home.Step 02'), 'title' => __('home.Review the canvas'), 'copy' => __('home.Watch the preview update. Open a file when you want a precise change.')],
                ['n' => __('home.Step 03'), 'title' => __('home.Publish with a clear view'), 'copy' => __('home.Ship when it holds. Custom domain and GitHub import unlock on paid packs.')],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <article class="<?php echo \Illuminate\Support\Arr::toCssClasses(['bg-krikkit-canvas p-6 md:p-10', 'lg:border-r lg:border-krikkit-line' => $index < 2]); ?>">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e($step['n']); ?></p>
                    <h3 class="mt-4 text-lg font-medium text-krikkit-fg"><?php echo e($step['title']); ?></h3>
                    <p class="mt-3 text-sm leading-relaxed text-krikkit-muted"><?php echo e($step['copy']); ?></p>
                </article>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
        <div class="relative flex flex-col items-start justify-between gap-6 border-t border-krikkit-line px-6 py-10 md:flex-row md:items-center md:px-12">
            <div>
                <p class="text-sm text-krikkit-muted"><?php echo e(__('home.Time to a first preview')); ?></p>
                <p class="mt-1 text-3xl font-medium tracking-tight text-krikkit-fg"><?php echo e(__('home.One sitting')); ?></p>
            </div>
            <a href="#overview" class="inline-flex items-center gap-2 text-sm font-medium text-krikkit-fg hover:text-accent-content">
                <?php echo e(__('home.See how a turn runs')); ?>

                <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'arrow-right','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-right','class' => 'size-4']); ?>
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
            </a>
        </div>
    </div>
</section>

<section class="border-b border-krikkit-line">
    <div class="relative mx-auto max-w-7xl overflow-hidden border-x border-krikkit-line">
        <div class="pointer-events-none absolute inset-0 krikkit-home-dots" aria-hidden="true"></div>
        <blockquote class="relative px-6 py-16 md:px-12 md:py-24">
            <p class="max-w-3xl text-2xl font-medium leading-snug tracking-tight text-krikkit-fg md:text-4xl">
                “<?php echo e(__('home.We went from a sentence to a page we could click through before the meeting ended.')); ?>”
            </p>
            <footer class="mt-8 text-sm text-krikkit-muted">
                <span class="font-medium text-krikkit-fg"><?php echo e(__('home.Studio lead')); ?></span>
                <span> · <?php echo e(__('home.Independent practice')); ?></span>
            </footer>
        </blockquote>
    </div>
</section>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\home\partials\steps.blade.php ENDPATH**/ ?>