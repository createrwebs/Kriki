<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => 'dialog',
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
    'name' => 'dialog',
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
    $widths = match ($size) {
        'sm' => 'max-w-sm',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        default => 'max-w-lg',
    };
?>

<div
    x-data="{
        open: false,
        name: <?php echo \Illuminate\Support\Js::from($name)->toHtml() ?>,
        payload: null,
        show(detail = null) {
            this.payload = detail && typeof detail === 'object' ? detail : null
            this.open = true
        },
        hide() {
            this.open = false
            this.payload = null
        },
    }"
    x-on:krikkit-modal-open.window="
        const detail = $event.detail
        if (detail === name) show()
        else if (detail && detail.name === name) show(detail)
    "
    x-on:krikkit-modal-close.window="if (! $event.detail || $event.detail === name || $event.detail?.name === name) hide()"
    @keydown.escape.window="if (open) hide()"
    <?php echo e($attributes->class('contents')); ?>

>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($trigger)): ?>
        <div @click="show()"><?php echo e($trigger); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <template x-teleport="body">
        <div
            x-cloak
            x-show="open"
            class="fixed inset-0 z-50 flex items-end justify-center p-4 sm:items-center"
            role="dialog"
            aria-modal="true"
        >
            <div
                x-show="open"
                x-transition.opacity
                class="absolute inset-0 bg-black/40"
                @click="hide()"
            ></div>

            <div
                x-show="open"
                x-transition
                class="relative z-10 w-full <?php echo e($widths); ?> rounded-2xl border border-krikkit-line bg-krikkit-surface p-5 text-krikkit-fg"
            >
                <?php echo e($slot); ?>

            </div>
        </div>
    </template>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views/krikkit/modal.blade.php ENDPATH**/ ?>