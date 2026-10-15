<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'color' => 'zinc',
    'size' => 'sm',
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
    'color' => 'zinc',
    'size' => 'sm',
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
        'xs' => 'px-1.5 py-0.5 text-[10px]',
        'lg' => 'px-2.5 py-1 text-sm',
        default => 'px-2 py-0.5 text-xs',
    };

    $colors = match ($color) {
        'teal' => 'bg-teal-50 text-teal-800 ring-teal-600/20 dark:bg-teal-950/50 dark:text-teal-200 dark:ring-teal-400/20',
        'red' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-950/50 dark:text-red-200 dark:ring-red-400/20',
        'amber' => 'bg-amber-50 text-amber-800 ring-amber-600/20 dark:bg-amber-950/50 dark:text-amber-200 dark:ring-amber-400/20',
        'green' => 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-950/50 dark:text-green-200 dark:ring-green-400/20',
        'blue' => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-950/50 dark:text-blue-200 dark:ring-blue-400/20',
        default => 'bg-krikkit-soft text-krikkit-fg-soft ring-krikkit-muted/20',
    };
?>

<span <?php echo e($attributes->class(['inline-flex items-center gap-1 rounded-md font-medium ring-1 ring-inset', $sizes, $colors])); ?>>
    <?php echo e($slot); ?>

</span>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\badge.blade.php ENDPATH**/ ?>