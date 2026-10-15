<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value' => 0,
    'max' => 100,
    'size' => 'md',
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
    'value' => 0,
    'max' => 100,
    'size' => 'md',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $pct = $max > 0 ? min(100, max(0, ($value / $max) * 100)) : 0;
    $heights = match ($size) {
        'sm' => 'h-1.5',
        'lg' => 'h-3',
        default => 'h-2',
    };
?>

<div
    <?php echo e($attributes->class(['w-full overflow-hidden rounded-full bg-krikkit-soft', $heights])); ?>

    role="progressbar"
    aria-valuenow="<?php echo e($value); ?>"
    aria-valuemin="0"
    aria-valuemax="<?php echo e($max); ?>"
>
    <div class="h-full rounded-full bg-accent transition-all" style="width: <?php echo e($pct); ?>%"></div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\progress.blade.php ENDPATH**/ ?>