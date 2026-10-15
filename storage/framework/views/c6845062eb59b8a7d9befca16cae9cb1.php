<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => null,
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
    'label' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<span <?php echo e($attributes->class('inline-flex items-center gap-2 border border-krikkit-line bg-krikkit-soft/50 px-3 py-1.5')); ?>>
    <span class="size-1.5 shrink-0 bg-accent"></span>
    <span class="text-[11px] font-semibold uppercase tracking-[0.15em] text-krikkit-muted">
        <?php echo e($label ?? $slot); ?>

    </span>
</span>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\home\partials\badge.blade.php ENDPATH**/ ?>