<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'align' => 'start',
    'position' => 'bottom',
    'width' => 'auto',
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
    'align' => 'start',
    'position' => 'bottom',
    'width' => 'auto',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $panelAlign = match ($align) {
        'end' => 'right-0',
        'center' => 'left-1/2 -translate-x-1/2',
        default => 'left-0',
    };

    $panelPos = match ($position) {
        'top' => 'bottom-full mb-1.5',
        'left' => 'right-full mr-1.5 top-0',
        'right' => 'left-full ml-1.5 top-0',
        default => 'top-full mt-1.5',
    };

    $panelWidth = $width === 'full' ? 'w-full' : 'min-w-[15rem]';
?>

<div
    x-data="{ open: false }"
    @keydown.escape.window="open = false"
    @click.outside="open = false"
    <?php echo e($attributes->class('relative inline-flex')); ?>

>
    <div @click="open = ! open" class="inline-flex w-full">
        <?php echo e($trigger ?? $slot); ?>

    </div>

    <div
        x-cloak
        x-show="open"
        <?php if($position === 'top'): ?>
            x-transition.origin.bottom.left
        <?php else: ?>
            x-transition.origin.top.left
        <?php endif; ?>
        class="absolute z-50 overflow-visible rounded-xl border border-krikkit-line bg-[color-mix(in_oklab,var(--color-krikkit-canvas)_55%,var(--color-krikkit-surface)_45%)] p-1 <?php echo e($panelPos); ?> <?php echo e($panelAlign); ?> <?php echo e($panelWidth); ?>"
        role="menu"
    >
        <?php echo e($menu ?? ''); ?>

    </div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views/krikkit/dropdown.blade.php ENDPATH**/ ?>