<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => null,
    'align' => 'left',
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
    'align' => 'left',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $id = $attributes->get('id') ?? 'switch-'.uniqid();
?>

<label
    for="<?php echo e($id); ?>"
    <?php echo e($attributes->only('class')->class([
        'inline-flex cursor-pointer items-center gap-3 text-sm text-krikkit-fg',
        $align === 'right' ? 'flex-row-reverse' : '',
    ])); ?>

>
    <span class="relative inline-flex h-6 w-11 shrink-0 items-center">
        <input
            id="<?php echo e($id); ?>"
            type="checkbox"
            role="switch"
            <?php echo e($attributes->except(['class', 'id'])->class('peer sr-only')); ?>

        >
        
        <span
            class="absolute inset-0 rounded-full bg-krikkit-line transition
                peer-checked:bg-accent
                peer-focus-visible:ring-2 peer-focus-visible:ring-krikkit-fg/20
                peer-disabled:opacity-50"
        ></span>
        <span
            class="absolute left-0.5 size-5 rounded-full bg-krikkit-canvas transition
                peer-checked:translate-x-5 peer-checked:bg-accent-foreground"
        ></span>
    </span>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($label || $slot->isNotEmpty()): ?>
        <span class="min-w-0"><?php echo e($label ?? $slot); ?></span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</label>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\switch.blade.php ENDPATH**/ ?>