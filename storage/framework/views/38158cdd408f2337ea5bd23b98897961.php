<section id="capabilities" class="scroll-mt-20 border-b border-krikkit-line">
    <div class="relative mx-auto max-w-7xl overflow-hidden border-x border-krikkit-line">
        <div class="pointer-events-none absolute inset-0 krikkit-home-dots" aria-hidden="true"></div>
        <div class="relative px-6 pt-20 md:px-12 md:pt-28">
            <?php echo $__env->make('home.partials.badge', ['label' => __('messages.What Lab does')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <h2 class="mt-6 max-w-2xl text-3xl font-medium leading-[1.15] tracking-tight text-krikkit-fg md:text-5xl">
                <?php echo e(__('home.The desk sites run on')); ?>

            </h2>
        </div>
        <div class="relative mt-12 grid gap-px border-t border-krikkit-line bg-krikkit-line sm:grid-cols-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                ['icon' => 'document-text', 'title' => __('home.Brief access'), 'copy' => __('home.Every project keeps the prompt, files, and preview together so the next turn has context.')],
                ['icon' => 'computer-desktop', 'title' => __('home.Canvas updates'), 'copy' => __('home.The preview refreshes as files land. No export step to see what changed.')],
                ['icon' => 'folder', 'title' => __('home.File memory'), 'copy' => __('home.Markup stays in the tree. Come back later and pick up the same pages.')],
                ['icon' => 'globe-alt', 'title' => __('home.Publish controls'), 'copy' => __('home.Preview, domain, and GitHub import sit behind the same workspace permissions.')],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <article class="flex flex-col gap-3 bg-krikkit-canvas p-6 md:p-10">
                    <span class="inline-flex size-9 items-center justify-center border border-krikkit-line bg-krikkit-soft/50 text-krikkit-fg">
                        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => $item['icon'],'variant' => 'mini','class' => 'block size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon']),'variant' => 'mini','class' => 'block size-4']); ?>
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
                    <h3 class="text-[15px] font-medium text-krikkit-fg"><?php echo e($item['title']); ?></h3>
                    <p class="text-sm leading-relaxed text-krikkit-muted"><?php echo e($item['copy']); ?></p>
                </article>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
        <div class="relative border-t border-krikkit-line px-6 py-10 md:px-12">
            <p class="text-sm font-medium text-krikkit-fg"><?php echo e(__('home.Workspace-ready')); ?></p>
            <p class="mt-2 max-w-2xl text-sm text-krikkit-muted"><?php echo e(__('home.Roles, sessions, and pack limits travel with the account — not a separate admin product.')); ?></p>
        </div>
    </div>
</section>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\home\partials\capabilities.blade.php ENDPATH**/ ?>