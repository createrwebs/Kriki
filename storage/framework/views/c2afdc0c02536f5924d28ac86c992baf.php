<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'value' => null,
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
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'value' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $minN = (float) $min;
    $maxN = (float) $max;
    $current = $value !== null && $value !== '' ? (float) $value : $minN;
    $span = $maxN - $minN;
    $fill = $span > 0 ? (($current - $minN) / $span) * 100 : 0;
?>

<input
    type="range"
    min="<?php echo e($min); ?>"
    max="<?php echo e($max); ?>"
    step="<?php echo e($step); ?>"
    <?php if($value !== null): ?> value="<?php echo e($value); ?>" <?php endif; ?>
    style="--krikkit-slider-fill: <?php echo e($fill); ?>%"
    oninput="this.style.setProperty('--krikkit-slider-fill', ((Number(this.value)-Number(this.min))/(Number(this.max)-Number(this.min)||1)*100)+'%')"
    <?php echo e($attributes->class([
        'krikkit-slider h-5 w-full cursor-pointer appearance-none bg-transparent accent-accent',
        'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-krikkit-fg/20',
        'disabled:cursor-not-allowed disabled:opacity-50',
    ])); ?>

>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\slider.blade.php ENDPATH**/ ?>