<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'keywords' => '',
    'href' => null,
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
    'keywords' => '',
    'href' => null,
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
    x-show="! q || String(<?php echo \Illuminate\Support\Js::from(strtolower($keywords))->toHtml() ?>).includes(String(q).toLowerCase())"
    <?php echo e($attributes->class('flex w-full items-center gap-2 rounded-lg px-2.5 py-2 text-left text-sm text-krikkit-fg-soft hover:bg-krikkit-soft')); ?>

    <?php if($href): ?> @click="window.location.href = <?php echo \Illuminate\Support\Js::from($href)->toHtml() ?>" <?php endif; ?>
>
    <?php echo e($slot); ?>

</button>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\command\item.blade.php ENDPATH**/ ?>