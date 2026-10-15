<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'content' => null,
    'position' => 'top',
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
    'content' => null,
    'position' => 'top',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $pos = match ($position) {
        'bottom' => 'top-full mt-1.5 left-1/2 -translate-x-1/2',
        'left' => 'right-full mr-1.5 top-1/2 -translate-y-1/2',
        'right' => 'left-full ml-1.5 top-1/2 -translate-y-1/2',
        default => 'bottom-full mb-1.5 left-1/2 -translate-x-1/2',
    };
?>

<span
    x-data="{ open: false }"
    @mouseenter="open = true"
    @mouseleave="open = false"
    @focusin="open = true"
    @focusout="open = false"
    <?php echo e($attributes->class('relative inline-flex')); ?>

>
    <?php echo e($slot); ?>

    <span
        x-cloak
        x-show="open"
        x-transition.opacity
        role="tooltip"
        class="pointer-events-none absolute z-50 whitespace-nowrap rounded-md bg-krikkit-fill px-2 py-1 text-xs text-krikkit-on-fill <?php echo e($pos); ?>"
    >
        <?php echo e($content ?? ($tip ?? '')); ?>

    </span>
</span>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\tooltip.blade.php ENDPATH**/ ?>