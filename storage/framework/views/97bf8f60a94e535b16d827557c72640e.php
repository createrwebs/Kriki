<?php
    $hero = $landing->section('hero');
    $guestEntry = $landing->guestEntryHref();
?>

<section id="overview" class="relative overflow-hidden border-b border-krikkit-line">
    <div class="relative mx-auto max-w-7xl border-x border-krikkit-line">
        <div class="flex flex-col items-center px-6 pb-16 pt-28 text-center sm:px-10 sm:pt-32 lg:pt-36">
            <span class="inline-flex items-center gap-2 border border-krikkit-line bg-krikkit-soft px-3 py-1.5">
                <span class="size-1.5 bg-accent"></span>
                <span class="text-[11px] font-semibold uppercase tracking-[0.15em] text-krikkit-fg-soft"><?php echo e($hero['eyebrow']); ?></span>
            </span>
            <h1 class="mt-6 max-w-3xl text-4xl font-medium leading-[1.1] tracking-tight text-krikkit-fg sm:text-5xl lg:text-[3.5rem]">
                <?php echo e($hero['title']); ?>

            </h1>
            <p class="mt-5 max-w-xl text-sm leading-relaxed text-krikkit-muted sm:text-base">
                <?php echo e($hero['copy']); ?>

            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a href="<?php echo e($landing->href($hero['primary_href'] ?? '', auth()->check() ? route('lab') : $guestEntry)); ?>" class="inline-flex items-center gap-2 bg-accent px-6 py-3 text-sm font-medium text-accent-foreground transition hover:opacity-90">
                    <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'lab','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'lab','class' => 'size-4']); ?>
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
                    <?php echo e($hero['primary_label']); ?>

                </a>
                <a href="<?php echo e($landing->href($hero['secondary_href'] ?? '', route('contact'))); ?>" class="inline-flex items-center border border-krikkit-line bg-krikkit-soft px-6 py-3 text-sm font-medium text-krikkit-fg transition hover:bg-krikkit-soft"><?php echo e($hero['secondary_label']); ?></a>
            </div>
            <p class="mt-5 text-sm text-krikkit-subtle"><?php echo e($hero['footnote']); ?></p>
        </div>
        <?php echo $__env->make('home.partials.brands', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</section>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\home\partials\hero.blade.php ENDPATH**/ ?>