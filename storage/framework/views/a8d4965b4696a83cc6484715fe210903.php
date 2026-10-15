<?php
    $landing = app(\App\Support\Site\LandingCopy::class);
    $hasBlog = \App\Support\Content\PublicIndex::blogIsLive();
    $links = $landing->headerLinks($hasBlog);
?>

<header class="relative z-10 flex min-h-14 items-center gap-3 bg-transparent px-4 py-2 sm:px-6 lg:px-10">
    <div class="flex min-w-0 flex-1 items-center">
        <button
            type="button"
            class="inline-flex size-9 cursor-pointer items-center justify-center rounded-full text-krikkit-fg-soft transition hover:bg-krikkit-soft lg:hidden"
            x-on:click="toggleNav()"
            x-bind:aria-expanded="navOpen.toString()"
            aria-controls="krikkit-studio-nav"
            aria-label="<?php echo e(__('studio.Open navigation')); ?>"
        >
            <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'bars-3','class' => 'size-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bars-3','class' => 'size-5']); ?>
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
        <a href="<?php echo e(route('home')); ?>" wire:navigate class="flex items-center gap-2 lg:hidden">
            <span class="truncate text-sm font-semibold tracking-tight text-krikkit-fg"><?php echo e($site->name()); ?></span>
        </a>
    </div>

    <nav class="hidden items-center gap-1 lg:flex">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php if($link['label'] === ''): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php continue; ?><?php endif; ?>
            <a href="<?php echo e($link['href']); ?>" class="rounded-lg px-3 py-1.5 text-sm text-krikkit-muted transition hover:bg-krikkit-soft hover:text-krikkit-fg"><?php echo e($link['label']); ?></a>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </nav>

    <div class="hidden min-w-0 flex-1 lg:block"></div>
</header>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\studio\partials\header.blade.php ENDPATH**/ ?>