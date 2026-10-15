<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'placeholder' => 'Add…',
    'name' => null,
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
    'placeholder' => 'Add…',
    'name' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div
    x-data="{
        items: [],
        draft: '',
        add() {
            const v = this.draft.trim()
            if (! v || this.items.includes(v)) return
            this.items.push(v)
            this.draft = ''
        },
        remove(i) { this.items.splice(i, 1) }
    }"
    <?php echo e($attributes->only('class')->class('w-full')); ?>

>
    <div class="flex min-h-10 flex-wrap items-center gap-1.5 rounded-full border border-transparent bg-krikkit-surface px-3 py-1 focus-within:border-krikkit-muted/40">
        <template x-for="(item, i) in items" :key="item + i">
            <span class="inline-flex items-center gap-1.5 rounded bg-krikkit-soft px-2 py-0.5 text-xs text-krikkit-fg-soft">
                <span x-text="item"></span>
                <button type="button" class="text-krikkit-subtle hover:text-krikkit-fg" @click="remove(i)" aria-label="<?php echo e(__('messages.Remove')); ?>">&times;</button>
            </span>
        </template>
        <input
            type="text"
            x-model="draft"
            @keydown.enter.prevent="add"
            @keydown.comma.prevent="add"
            placeholder="<?php echo e($placeholder); ?>"
            class="min-w-[6rem] flex-1 border-0 bg-transparent px-1 py-0.5 text-sm text-krikkit-fg outline-none placeholder:text-krikkit-subtle"
        >
    </div>
    <template x-for="item in items" :key="'h-'+item">
        <input type="hidden" <?php if($name): ?> name="<?php echo e($name); ?>[]" <?php endif; ?> :value="item">
    </template>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\pillbox.blade.php ENDPATH**/ ?>