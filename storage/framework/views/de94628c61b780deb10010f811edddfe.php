<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'solid',
    'size' => 'md',
    'type' => 'button',
    'square' => false,
    'loading' => false,
    'href' => null,
    // Full-document pages (Lab, auth, etc.) must opt out — wire:navigate breaks them.
    'navigate' => true,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'variant' => 'solid',
    'size' => 'md',
    'type' => 'button',
    'square' => false,
    'loading' => false,
    'href' => null,
    // Full-document pages (Lab, auth, etc.) must opt out — wire:navigate breaks them.
    'navigate' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $sizes = match ($size) {
        'xs' => 'h-8 min-h-8 max-h-8 px-3 text-xs gap-1.5',
        'sm' => 'h-9 min-h-9 max-h-9 px-3.5 text-sm gap-1.5',
        'lg' => 'h-12 min-h-12 max-h-12 px-5 text-base gap-2',
        default => 'h-10 min-h-10 max-h-10 px-4 text-sm gap-2',
    };

    $squares = match ($size) {
        'xs' => 'size-8',
        'sm' => 'size-9',
        'lg' => 'size-12',
        default => 'size-10',
    };

    $variants = match ($variant) {
        'outline' => 'border-krikkit-line bg-transparent text-krikkit-fg hover:bg-krikkit-soft/70',
        'ghost' => 'border-transparent bg-transparent text-krikkit-fg-soft hover:bg-krikkit-soft',
        'danger' => 'border-red-600 bg-red-600 text-krikkit-on-fill hover:border-red-700 hover:bg-red-700',
        'subtle' => 'border-krikkit-soft bg-krikkit-soft text-krikkit-fg',
        // Inverse solid (not theme accent) — rare; prefer default/primary for actions.
        'fill' => 'border-krikkit-fill bg-krikkit-fill text-krikkit-on-fill hover:opacity-90',
        // Default + primary = Settings → Themes accent (red/blue/… + dark flip).
        'primary', 'solid' => 'border-accent bg-accent text-accent-foreground hover:opacity-90',
        default => 'border-accent bg-accent text-accent-foreground hover:opacity-90',
    };

    $classes = [
        'inline-flex shrink-0 cursor-pointer items-center justify-center rounded-full border border-solid box-border py-0 font-medium leading-none whitespace-nowrap transition outline-none',
        'focus-visible:ring-2 focus-visible:ring-krikkit-fg/15',
        'disabled:pointer-events-none disabled:opacity-50',
        $square ? $squares : $sizes,
        $variants,
    ];
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($href): ?>
    <a
        href="<?php echo e($href); ?>"
        <?php if($navigate): ?> wire:navigate <?php endif; ?>
        <?php echo e($attributes->class($classes)); ?>

    ><?php echo e($slot); ?></a>
<?php else: ?>
    <button
        type="<?php echo e($type); ?>"
        <?php echo e($attributes->class($classes)); ?>

        <?php if($loading): echo 'disabled'; endif; ?>
        <?php if($loading): ?> data-loading="true" aria-busy="true" <?php endif; ?>
    ><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($loading): ?>
            <svg class="size-4 shrink-0 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity="0.25" />
                <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
            </svg>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php echo e($slot); ?></button>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\button.blade.php ENDPATH**/ ?>