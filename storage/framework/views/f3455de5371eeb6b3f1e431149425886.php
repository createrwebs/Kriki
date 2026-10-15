<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
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
    'name',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<button
    type="button"
    role="tab"
    @click="active = <?php echo \Illuminate\Support\Js::from($name)->toHtml() ?>"
    :aria-selected="active === <?php echo \Illuminate\Support\Js::from($name)->toHtml() ?>"
    <?php echo e($attributes->class('rounded-lg px-3 py-1.5 text-sm font-medium transition')); ?>

    :class="active === <?php echo \Illuminate\Support\Js::from($name)->toHtml() ?>
        ? 'bg-krikkit-surface text-krikkit-fg'
        : 'text-krikkit-muted hover:text-krikkit-fg'"
>
    <?php echo e($slot); ?>

</button>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\tabs\tab.blade.php ENDPATH**/ ?>