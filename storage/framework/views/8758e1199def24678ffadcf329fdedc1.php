<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value',
    'label' => null,
    'selected' => false,
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
    'value',
    'label' => null,
    'selected' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $text = $label ?? trim(preg_replace('/\s+/u', ' ', strip_tags((string) $slot)));
    $stringValue = (string) $value;
?>

<button
    type="button"
    role="option"
    x-init="
        optionLabels.push(<?php echo \Illuminate\Support\Js::from($text)->toHtml() ?>);
        if (<?php echo \Illuminate\Support\Js::from($selected)->toHtml() ?> || value === <?php echo \Illuminate\Support\Js::from($stringValue)->toHtml() ?>) {
            value = <?php echo \Illuminate\Support\Js::from($stringValue)->toHtml() ?>;
            label = <?php echo \Illuminate\Support\Js::from($text)->toHtml() ?>;
        }
    "
    x-show="matches(<?php echo \Illuminate\Support\Js::from($text)->toHtml() ?>)"
    x-on:click.stop="choose(<?php echo \Illuminate\Support\Js::from($stringValue)->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($text)->toHtml() ?>)"
    <?php echo e($attributes->class('flex w-full items-center rounded-lg px-2.5 py-2 text-left text-sm text-krikkit-fg-soft transition hover:bg-krikkit-soft')); ?>

    :class="value === <?php echo \Illuminate\Support\Js::from($stringValue)->toHtml() ?> ? 'bg-krikkit-soft font-medium text-krikkit-fg' : ''"
>
    <?php echo e($text !== '' ? $text : $slot); ?>

</button>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\select\option.blade.php ENDPATH**/ ?>