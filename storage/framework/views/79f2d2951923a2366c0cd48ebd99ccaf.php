<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($packOffer): ?>
    <a href="<?php echo e($packOffer['href']); ?>" class="group relative block">
        <span
            class="pointer-events-none absolute -inset-4 bg-[radial-gradient(ellipse_at_center,color-mix(in_oklab,var(--color-accent)_42%,transparent),transparent_72%)] opacity-0 transition-opacity duration-500 group-hover:opacity-70"
            aria-hidden="true"
        ></span>
        <span class="relative flex items-center gap-3 rounded-2xl border border-krikkit-line bg-krikkit-soft px-3.5 py-3">
            <span class="min-w-0 flex-1">
                <span class="block truncate text-sm font-semibold tracking-tight text-krikkit-fg"><?php echo e($packOffer['title']); ?></span>
                <span class="mt-0.5 block truncate text-[13px] leading-snug text-krikkit-muted"><?php echo e($packOffer['copy']); ?></span>
            </span>
            <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-krikkit-canvas text-accent-content">
                <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'bolt','class' => 'size-[18px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bolt','class' => 'size-[18px]']); ?>
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
        </span>
    </a>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\studio\partials\pack.blade.php ENDPATH**/ ?>