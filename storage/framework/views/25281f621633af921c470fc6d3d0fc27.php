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

<?php
    $id = $attributes->get('id') ?? 'checkbox-'.uniqid();
    $checked = $attributes->has('checked') && $attributes->get('checked') !== false;
?>

<label
    for="<?php echo e($id); ?>"
    <?php echo e($attributes->only('class')->merge(['class' => 'group inline-flex cursor-pointer items-start gap-2.5 text-sm text-krikkit-fg'])); ?>

>
    <span class="relative mt-0.5 inline-flex size-4 shrink-0">
        <input
            id="<?php echo e($id); ?>"
            type="checkbox"
            class="peer sr-only"
            <?php if($checked): echo 'checked'; endif; ?>
            <?php echo e($attributes->except(['class', 'id', 'checked'])); ?>

        >
        <span
            class="absolute inset-0 rounded-[5px] border border-krikkit-line bg-transparent transition
                peer-focus-visible:ring-2 peer-focus-visible:ring-krikkit-fg/20
                peer-checked:border-krikkit-fill peer-checked:bg-krikkit-fill"
        ></span>
        <svg
            class="pointer-events-none absolute inset-0 m-auto size-3 text-krikkit-on-fill opacity-0 transition peer-checked:opacity-100"
            viewBox="0 0 12 12"
            fill="none"
            aria-hidden="true"
        >
            <path d="M2.5 6.2 4.8 8.5 9.5 3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </span>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($label || $slot->isNotEmpty()): ?>
        <span class="leading-5"><?php echo e($label ?? $slot); ?></span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</label>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\checkbox.blade.php ENDPATH**/ ?>