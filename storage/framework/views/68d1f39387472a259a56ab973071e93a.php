<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['position' => 'bottom-right']));

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

foreach (array_filter((['position' => 'bottom-right']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $pos = match ($position) {
        'bottom-left' => 'left-0 bottom-full mb-1',
        'top-right' => 'right-0 top-full mt-1',
        'top-left' => 'left-0 top-full mt-1',
        default => 'right-0 bottom-full mb-1',
    };
?>

<div
    x-data="{ open: false }"
    @contextmenu.prevent="open = true"
    @keydown.escape.window="open = false"
    @click.outside="open = false"
    <?php echo e($attributes->class('relative inline-flex')); ?>

>
    <?php echo e($slot); ?>


    <div
        x-cloak
        x-show="open"
        @click="open = false"
        class="absolute z-50 min-w-40 overflow-hidden rounded-xl bg-krikkit-surface p-1 <?php echo e($pos); ?>"
        role="menu"
    >
        <?php echo e($menu ?? ''); ?>

    </div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\context.blade.php ENDPATH**/ ?>