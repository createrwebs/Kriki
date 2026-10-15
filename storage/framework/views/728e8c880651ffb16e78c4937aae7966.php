
<?php
    $codeLines = [
        ['n' => '1', 'html' => '&lt;!DOCTYPE <span class="text-emerald-700 dark:text-emerald-400">html</span>&gt;'],
        ['n' => '2', 'html' => '&lt;<span class="text-rose-700 dark:text-rose-400">html</span> <span class="text-cyan-700 dark:text-cyan-400">lang</span>=<span class="text-emerald-700 dark:text-emerald-400">"en"</span>&gt;'],
        ['n' => '3', 'html' => '&lt;<span class="text-rose-700 dark:text-rose-400">head</span>&gt;'],
        ['n' => '4', 'html' => '&nbsp;&nbsp;&lt;<span class="text-rose-700 dark:text-rose-400">title</span>&gt;Ceramic studio&lt;/<span class="text-rose-700 dark:text-rose-400">title</span>&gt;'],
        ['n' => '5', 'html' => '&lt;/<span class="text-rose-700 dark:text-rose-400">head</span>&gt;'],
        ['n' => '6', 'html' => '&lt;<span class="text-rose-700 dark:text-rose-400">body</span>&gt;'],
        ['n' => '7', 'html' => '&nbsp;&nbsp;&lt;<span class="text-rose-700 dark:text-rose-400">h1</span>&gt;Ceramic studio&lt;/<span class="text-rose-700 dark:text-rose-400">h1</span>&gt;'],
        ['n' => '8', 'html' => '&nbsp;&nbsp;&lt;<span class="text-rose-700 dark:text-rose-400">a</span> <span class="text-cyan-700 dark:text-cyan-400">href</span>=<span class="text-emerald-700 dark:text-emerald-400">"/visit"</span>&gt;Visit&lt;/<span class="text-rose-700 dark:text-rose-400">a</span>&gt;'],
        ['n' => '9', 'html' => '&lt;/<span class="text-rose-700 dark:text-rose-400">body</span>&gt;'],
        ['n' => '10', 'html' => '&lt;/<span class="text-rose-700 dark:text-rose-400">html</span>&gt;'],
    ];
?>

<div class="pointer-events-none flex h-full select-none flex-col overflow-hidden rounded-[12px] border border-krikkit-line bg-krikkit-canvas text-[11px] leading-none" aria-hidden="true">
    <header class="flex h-10 shrink-0 items-stretch border-b border-krikkit-line bg-krikkit-canvas">
        <div class="flex w-[14.5rem] min-w-0 shrink-0 items-center gap-1.5 border-r border-krikkit-line/80 px-3">
            <span class="truncate text-[11px] font-semibold tracking-tight text-krikkit-fg"><?php echo e($site->name()); ?></span>
            <span class="shrink-0 text-krikkit-subtle">/</span>
            <span class="shrink-0 text-[11px] text-krikkit-muted"><?php echo e(__('messages.Lab')); ?></span>
            <span class="ml-auto inline-flex shrink-0 items-center font-mono text-[10px] tabular-nums leading-none text-krikkit-muted">
                <?php echo e(__('home.Credits')); ?>

                <span class="ml-1 text-krikkit-fg">12 / 100</span>
            </span>
        </div>
        <div class="flex min-w-0 flex-1 items-center pl-2 pr-3">
            <span class="inline-flex h-7 items-center gap-1 px-2 text-[11px] text-krikkit-muted">
                <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'computer-desktop','variant' => 'mini','class' => 'block size-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'computer-desktop','variant' => 'mini','class' => 'block size-3']); ?>
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
                <span><?php echo e(__('messages.Preview')); ?></span>
            </span>
            <span class="mx-0.5 h-3 w-px shrink-0 bg-krikkit-line"></span>
            <span class="relative inline-flex h-7 items-center gap-1 px-2 text-[11px] text-krikkit-fg">
                <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'document-text','variant' => 'mini','class' => 'block size-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'document-text','variant' => 'mini','class' => 'block size-3']); ?>
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
                <span>index.html</span>
                <span class="absolute inset-x-1.5 bottom-0 h-px bg-krikkit-fg"></span>
            </span>
            <span class="inline-flex h-7 items-center gap-1 px-2 text-[11px] text-krikkit-muted">
                <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'folder','variant' => 'mini','class' => 'block size-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'folder','variant' => 'mini','class' => 'block size-3']); ?>
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
                <span><?php echo e(__('messages.Files')); ?></span>
            </span>
            <span class="inline-flex size-6 items-center justify-center text-krikkit-muted">
                <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'plus','variant' => 'mini','class' => 'block size-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'plus','variant' => 'mini','class' => 'block size-3']); ?>
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
            <span class="ml-auto inline-flex h-7 items-center gap-1 rounded-md bg-accent px-2 text-[11px] font-medium text-accent-foreground">
                <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'globe-alt','variant' => 'mini','class' => 'block size-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'globe-alt','variant' => 'mini','class' => 'block size-3']); ?>
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
                <span><?php echo e(__('messages.Publish')); ?></span>
            </span>
        </div>
    </header>

    <div class="flex min-h-0 flex-1 overflow-hidden">
        <aside class="flex w-[14.5rem] shrink-0 flex-col border-r border-krikkit-line/80 bg-krikkit-canvas">
            <div class="min-h-0 flex-1 space-y-2 overflow-hidden p-2.5">
                <div class="flex justify-end">
                    <p class="max-w-[92%] rounded-lg rounded-br-sm bg-accent px-2 py-1 text-left text-[10px] leading-snug text-accent-foreground line-clamp-3">
                        <?php echo e(__('messages.A landing page for a ceramic studio. Warm type, large photography, a short about, and a visit form.')); ?>

                    </p>
                </div>
                <div class="flex items-start gap-1.5">
                    <span class="mt-px inline-flex size-4 shrink-0 items-center justify-center rounded-full bg-accent/15 text-[8px] font-semibold leading-none text-accent-content">
                        <?php echo e(strtoupper(substr($site->name(), 0, 1))); ?>

                    </span>
                    <p class="min-w-0 pt-px text-[10px] leading-snug text-krikkit-fg line-clamp-2">
                        <?php echo e(__('home.Laying out the landing — warm type, photography, and a visit form.')); ?>

                    </p>
                </div>
            </div>
            <div class="shrink-0 p-2">
                <div class="rounded-xl bg-krikkit-surface p-1.5">
                    <p class="px-1 py-0.5 text-[11px] leading-none text-krikkit-subtle"><?php echo e(__('home.Reply…')); ?></p>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="inline-flex size-6 items-center justify-center text-krikkit-muted">
                            <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'plus','variant' => 'mini','class' => 'block size-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'plus','variant' => 'mini','class' => 'block size-3']); ?>
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
                        <span class="inline-flex size-6 items-center justify-center rounded-full bg-accent text-accent-foreground">
                            <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'arrow-up','variant' => 'mini','class' => 'block size-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-up','variant' => 'mini','class' => 'block size-3']); ?>
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
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 overflow-hidden">
            <div class="flex min-w-0 flex-1 flex-col bg-krikkit-canvas">
                <div class="flex h-8 shrink-0 items-center justify-between gap-2 border-b border-krikkit-line px-2.5">
                    <p class="truncate text-[11px] font-medium leading-none text-krikkit-fg">index.html</p>
                    <p class="truncate text-[10px] leading-none text-krikkit-subtle"><?php echo e(__('home.editing')); ?></p>
                </div>
                <div class="min-h-0 flex-1 overflow-hidden font-mono text-[10px] leading-4 text-krikkit-fg">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $codeLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                            'flex items-start gap-2 px-2 py-px',
                            'bg-krikkit-soft/70' => $i === 6,
                        ]); ?>">
                            <span class="w-4 shrink-0 text-right tabular-nums text-krikkit-subtle"><?php echo e($line['n']); ?></span>
                            <span class="min-w-0 truncate"><?php echo $line['html']; ?></span>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>

            <div class="hidden w-[9.5rem] shrink-0 flex-col border-l border-krikkit-line/80 bg-krikkit-canvas md:flex">
                <div class="flex h-8 shrink-0 items-center justify-between gap-2 border-b border-krikkit-line px-2">
                    <p class="truncate text-[11px] font-medium leading-none text-krikkit-fg"><?php echo e(__('messages.Files')); ?></p>
                    <p class="text-[10px] tabular-nums leading-none text-krikkit-subtle">3</p>
                </div>
                <ul class="min-h-0 flex-1 space-y-0.5 overflow-hidden p-1.5">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                        ['name' => 'index.html', 'on' => true],
                        ['name' => 'styles.css', 'on' => false],
                        ['name' => 'visit.html', 'on' => false],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <li class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                            'flex items-center gap-1.5 rounded-md px-1.5 py-1',
                            'bg-krikkit-soft text-krikkit-fg' => $file['on'],
                            'text-krikkit-muted' => ! $file['on'],
                        ]); ?>">
                            <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'code-bracket','variant' => 'mini','class' => 'block size-3 shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'code-bracket','variant' => 'mini','class' => 'block size-3 shrink-0']); ?>
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
                            <span class="min-w-0 truncate text-[10px] leading-none"><?php echo e($file['name']); ?></span>
                        </li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\home\partials\board.blade.php ENDPATH**/ ?>