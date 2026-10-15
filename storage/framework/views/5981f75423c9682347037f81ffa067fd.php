<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value' => null,
    'name' => null,
    'swatches' => null,
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
    'value' => null,
    'name' => null,
    'swatches' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $name = $name ?? $attributes->get('name');
    $initial = $value ?? $attributes->get('value') ?? '#0f766e';
    $palette = $swatches ?? [
        '#18181b', '#71717a', '#ffffff',
        '#0f766e', '#0891b2', '#2563eb',
        '#7c3aed', '#db2777', '#dc2626',
        '#d97706', '#16a34a', '#ca8a04',
    ];
?>

<div
    x-data="{
        open: false,
        color: <?php echo \Illuminate\Support\Js::from($initial)->toHtml() ?>,
        palette: <?php echo \Illuminate\Support\Js::from($palette)->toHtml() ?>,
        pick(c) {
            this.color = c
            this.open = false
            this.$dispatch('krikkit-color', c)
        },
        onHex(e) {
            let v = e.target.value.trim()
            if (! v.startsWith('#')) v = '#' + v
            if (/^#[0-9A-Fa-f]{6}$/.test(v)) {
                this.color = v.toLowerCase()
                this.$dispatch('krikkit-color', this.color)
            }
        }
    }"
    @keydown.escape.window="open = false"
    <?php echo e($attributes->only('class')->class('relative w-full')); ?>

>
    <button
        type="button"
        @click="open = ! open"
        class="flex h-10 w-full items-center gap-2 rounded-full border border-transparent bg-krikkit-surface px-4 text-left text-sm outline-none transition focus-visible:border-krikkit-muted/40"
    >
        <span class="size-4 shrink-0 rounded border border-krikkit-line" :style="`background:${color}`"></span>
        <span class="font-mono text-xs uppercase text-krikkit-fg-soft" x-text="color"></span>
    </button>

    <input
        type="hidden"
        <?php if($name): ?> name="<?php echo e($name); ?>" <?php endif; ?>
        :value="color"
        <?php echo e($attributes->whereStartsWith('wire:')); ?>

    >

    <div
        x-cloak
        x-show="open"
        @click.outside="open = false"
        class="absolute z-50 mt-1 w-52 rounded-lg bg-krikkit-surface p-2"
    >
        <div class="grid grid-cols-6 gap-1.5">
            <template x-for="c in palette" :key="c">
                <button
                    type="button"
                    class="size-6 rounded border border-krikkit-line transition"
                    :class="color.toLowerCase() === c.toLowerCase() ? 'ring-2 ring-krikkit-fg ring-offset-1 dark:ring-krikkit-fg dark:ring-offset-krikkit-surface' : ''"
                    :style="`background:${c}`"
                    @click.stop="pick(c)"
                    :aria-label="c"
                ></button>
            </template>
        </div>
        <div class="mt-2 flex items-center gap-2 border-t border-krikkit-line pt-2">
            <span class="size-5 rounded border border-krikkit-line" :style="`background:${color}`"></span>
            <input
                type="text"
                maxlength="7"
                class="h-7 w-full rounded-lg border border-krikkit-line bg-transparent px-3 font-mono text-xs uppercase outline-none focus:border-krikkit-fg dark:focus:border-krikkit-muted"
                :value="color"
                @change="onHex"
                @keydown.enter.prevent="onHex($event)"
            >
        </div>
    </div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\colorPicker.blade.php ENDPATH**/ ?>