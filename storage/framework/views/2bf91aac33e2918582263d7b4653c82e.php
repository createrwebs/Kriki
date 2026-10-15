<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'rows' => 4,
    'resize' => 'y',
    'invalid' => false,
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
    'rows' => 4,
    'resize' => 'y',
    'invalid' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $resizeClass = match ($resize) {
        'none' => 'resize-none',
        'x' => 'resize-x',
        'both' => 'resize',
        default => 'resize-y',
    };
?>

<textarea
    rows="<?php echo e($rows); ?>"
    <?php echo e($attributes->class([
        'w-full rounded-2xl border bg-krikkit-surface px-4 py-2.5 text-sm outline-none transition',
        'text-krikkit-fg placeholder:text-krikkit-subtle',
        'disabled:cursor-not-allowed disabled:opacity-50',
        $resizeClass,
        $invalid
            ? 'border-red-400/70 focus:border-red-500'
            : 'border-transparent focus:border-krikkit-muted/40',
    ])); ?>

><?php echo e($slot); ?></textarea>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\textarea.blade.php ENDPATH**/ ?>