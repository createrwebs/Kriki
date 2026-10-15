<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => null,
    'src' => null,
    'initials' => null,
    'size' => 'md',
    'circle' => true,
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
    'name' => null,
    'src' => null,
    'initials' => null,
    'size' => 'md',
    'circle' => true,
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
        'xs' => 'size-7 text-[11px]',
        'sm' => 'size-9 text-xs',
        'lg' => 'size-14 text-base',
        'xl' => 'size-20 text-xl',
        default => 'size-11 text-sm',
    };

    $shape = $circle ? 'rounded-full' : 'rounded-lg';
    $label = $initials ?? (is_string($name) ? collect(preg_split('/\s+/', trim($name)))->take(2)->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('') : '');
?>

<span
    <?php echo e($attributes->class([
        'inline-flex items-center justify-center overflow-hidden bg-krikkit-soft font-semibold text-krikkit-fg-soft',
        $sizes,
        $shape,
    ])); ?>

    <?php if($name): ?> title="<?php echo e($name); ?>" <?php endif; ?>
>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($src): ?>
        <img src="<?php echo e($src); ?>" alt="<?php echo e($name ?? ''); ?>" class="size-full object-cover">
    <?php elseif($slot->isNotEmpty()): ?>
        <?php echo e($slot); ?>

    <?php else: ?>
        <?php echo e($label ?: '?'); ?>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</span>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views/krikkit/avatar.blade.php ENDPATH**/ ?>