<?php
    $stats = [
        ['value' => __('home.One desk'), 'hint' => __('home.Brief, files, and canvas')],
        ['value' => __('home.Real files'), 'hint' => __('home.Markup you can open')],
        ['value' => __('home.Pack credits'), 'hint' => __('home.Spend only on generation')],
        ['value' => __('home.Your domain'), 'hint' => __('home.When a pack unlocks it')],
    ];

    $pillars = [
        [
            'id' => 'brief',
            'icon' => 'document-text',
            'kicker' => __('messages.Brief'),
            'tone' => 'cyan',
            'title' => __('home.A shared desk for every brief'),
            'copy' => __('home.Centralize the prompt, the file tree, and the live preview so nothing lives in a side chat.'),
            'mock' => 'brief',
        ],
        [
            'id' => 'canvas',
            'icon' => 'lab',
            'kicker' => __('messages.Canvas'),
            'tone' => 'violet',
            'title' => __('home.Connect Lab to the rest of the stack'),
            'copy' => __('home.Import from GitHub, spend pack credits, and keep generated files in the workspace.'),
            'mock' => 'page',
        ],
        [
            'id' => 'files',
            'icon' => 'folder',
            'kicker' => __('messages.Files'),
            'tone' => 'orange',
            'title' => __('home.Turns that work like a studio partner'),
            'copy' => __('home.Ask for a landing, a shop grid, or a docs page. Lab writes the screens and you review them.'),
            'mock' => 'files',
        ],
        [
            'id' => 'publish',
            'icon' => 'globe-alt',
            'kicker' => __('messages.Publish'),
            'tone' => 'blue',
            'title' => __('home.Oversight for every publish'),
            'copy' => __('home.Preview first. Attach a domain when you are ready. Roll a project back by editing the files.'),
            'mock' => 'publish',
        ],
    ];

    $toneClass = [
        'cyan' => 'border-cyan-500/30 bg-cyan-500/10 text-cyan-600 dark:text-cyan-400',
        'violet' => 'border-violet-500/30 bg-violet-500/10 text-violet-600 dark:text-violet-400',
        'orange' => 'border-orange-500/30 bg-orange-500/10 text-orange-600 dark:text-orange-400',
        'blue' => 'border-blue-500/30 bg-blue-500/10 text-blue-600 dark:text-blue-400',
    ];
?>

<section id="platform" class="scroll-mt-20 border-b border-krikkit-line" x-data="krikkitHomePlatform">
    <div class="relative mx-auto max-w-7xl overflow-hidden border-x border-krikkit-line">
        <div class="pointer-events-none absolute inset-0 krikkit-home-dots" aria-hidden="true"></div>
        <div class="relative px-6 pt-20 md:px-12 md:pt-28">
            <?php echo $__env->make('home.partials.badge', ['label' => __('home.Introducing Lab')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <h2 class="mt-6 max-w-3xl text-3xl font-medium leading-[1.15] tracking-tight text-krikkit-fg md:text-5xl">
                <?php echo e(__('home.The workshop sites need to run from a single brief')); ?>

            </h2>
        </div>

        <div class="relative mt-12 grid items-start gap-10 border-t border-krikkit-line px-6 py-12 md:px-12 md:py-16 lg:grid-cols-12 lg:gap-14">
            <aside class="hidden lg:sticky lg:top-24 lg:col-span-4 lg:block lg:self-start">
                <ul class="divide-y divide-krikkit-line border border-krikkit-line">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <li class="px-5 py-5">
                            <p class="text-2xl font-medium tracking-tight text-krikkit-fg"><?php echo e($stat['value']); ?></p>
                            <p class="mt-1 text-sm text-krikkit-muted"><?php echo e($stat['hint']); ?></p>
                        </li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ul>
            </aside>

            <div class="flex flex-col lg:col-span-8">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pillars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $pillar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <article
                        id="platform-<?php echo e($pillar['id']); ?>"
                        data-platform-panel="<?php echo e($index); ?>"
                        class="scroll-mt-28 border-t border-krikkit-line py-10 first:border-t-0 first:pt-0 md:py-14"
                    >
                        <span class="inline-flex items-center border px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.14em] <?php echo e($toneClass[$pillar['tone']]); ?>">
                            <?php echo e($pillar['kicker']); ?>

                        </span>
                        <h3 class="mt-4 text-2xl font-medium leading-tight tracking-tight text-krikkit-fg md:text-3xl">
                            <?php echo e($pillar['title']); ?>

                        </h3>
                        <p class="mt-3 max-w-xl text-sm leading-relaxed text-krikkit-muted md:text-base">
                            <?php echo e($pillar['copy']); ?>

                        </p>
                        <div class="mt-6">
                            <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['href' => '#capabilities','navigate' => false,'variant' => 'fill','size' => 'sm','class' => '!rounded-none']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '#capabilities','navigate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'variant' => 'fill','size' => 'sm','class' => '!rounded-none']); ?>
                                <?php echo e(__('home.Learn more')); ?>

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
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $attributes = $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $component = $__componentOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($pillar['mock'], ['brief', 'files'], true)): ?>
                            <div class="mt-8 overflow-hidden border border-krikkit-line">
                                <?php echo $__env->make('home.partials.desk', ['tone' => $pillar['mock']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        <?php elseif($pillar['mock'] === 'page'): ?>
                            <div class="mt-8 overflow-hidden border border-krikkit-line bg-krikkit-canvas">
                                <?php echo $__env->make('home.partials.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        <?php else: ?>
                            <ul class="mt-8 divide-y divide-krikkit-line border border-krikkit-line">
                                <li class="flex items-center justify-between px-3 py-2.5">
                                    <span class="text-sm text-krikkit-muted"><?php echo e(__('home.Domain')); ?></span>
                                    <span class="font-mono text-xs text-krikkit-fg">studio.lab</span>
                                </li>
                                <li class="flex items-center justify-between px-3 py-2.5">
                                    <span class="text-sm text-krikkit-muted"><?php echo e(__('home.GitHub')); ?></span>
                                    <span class="text-xs text-krikkit-subtle"><?php echo e(__('home.Ready')); ?></span>
                                </li>
                                <li class="flex h-10 items-center justify-center bg-accent text-sm font-medium text-accent-foreground">
                                    <?php echo e(__('messages.Publish')); ?>

                                </li>
                            </ul>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </article>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\home\partials\features.blade.php ENDPATH**/ ?>