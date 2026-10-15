<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value' => null,
    'placeholder' => 'Pick a time',
    'name' => null,
    'step' => 15,
    'invalid' => false,
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
    'placeholder' => 'Pick a time',
    'name' => null,
    'step' => 15,
    'invalid' => false,
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
    $initial = $value ?? $attributes->get('value');
    $step = max(1, (int) $step);
?>

<div
    x-data="{
        open: false,
        value: <?php echo \Illuminate\Support\Js::from($initial)->toHtml() ?>,
        hour: <?php echo \Illuminate\Support\Js::from($initial)->toHtml() ?> ? Number(String(<?php echo \Illuminate\Support\Js::from($initial)->toHtml() ?>).split(':')[0]) : 9,
        minute: <?php echo \Illuminate\Support\Js::from($initial)->toHtml() ?> ? Number(String(<?php echo \Illuminate\Support\Js::from($initial)->toHtml() ?>).split(':')[1]) : 0,
        step: <?php echo \Illuminate\Support\Js::from($step)->toHtml() ?>,
        hours: Array.from({ length: 24 }, (_, i) => String(i).padStart(2, '0')),
        minutes() {
            const out = []
            for (let m = 0; m < 60; m += this.step) out.push(String(m).padStart(2, '0'))
            return out
        },
        display() {
            if (! this.value) return ''
            return this.value.slice(0, 5)
        },
        sync() {
            this.value = String(this.hour).padStart(2, '0') + ':' + String(this.minute).padStart(2, '0')
            this.$nextTick(() => {
                this.$refs.input?.dispatchEvent(new Event('input', { bubbles: true }))
                this.$refs.input?.dispatchEvent(new Event('change', { bubbles: true }))
            })
        },
        setHour(h) { this.hour = Number(h); this.sync() },
        setMinute(m) { this.minute = Number(m); this.sync() },
    }"
    @keydown.escape.window="open = false"
    @click.outside="open = false"
    <?php echo e($attributes->only('class')->class('relative w-full')); ?>

>
    <button
        type="button"
        @click="open = ! open"
        class="flex h-10 w-full items-center justify-between gap-2 rounded-full border bg-krikkit-surface px-4 text-left text-sm outline-none transition
            focus-visible:border-krikkit-muted
            <?php echo e($invalid ? 'border-red-400/70' : 'border-transparent focus-visible:border-krikkit-muted/40'); ?>"
    >
        <span :class="value ? 'text-krikkit-fg' : 'text-krikkit-subtle'" x-text="value ? display() : <?php echo \Illuminate\Support\Js::from($placeholder)->toHtml() ?>"></span>
        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'clock','class' => 'size-4 shrink-0 text-krikkit-subtle']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'clock','class' => 'size-4 shrink-0 text-krikkit-subtle']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2f88b3d15b59738674618f1a73b04e5e)): ?>
<?php $attributes = $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e; ?>
<?php unset($__attributesOriginal2f88b3d15b59738674618f1a73b04e5e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2f88b3d15b59738674618f1a73b04e5e)): ?>
<?php $component = $__componentOriginal2f88b3d15b59738674618f1a73b04e5e; ?>
<?php unset($__componentOriginal2f88b3d15b59738674618f1a73b04e5e); ?>
<?php endif; ?>
    </button>

    <input
        x-ref="input"
        type="hidden"
        <?php if($name): ?> name="<?php echo e($name); ?>" <?php endif; ?>
        x-model="value"
        <?php echo e($attributes->whereStartsWith('wire:')); ?>

    >

    <div
        x-cloak
        x-show="open"
        x-transition.origin.top
        class="absolute z-40 mt-1.5 w-56 overflow-hidden rounded-lg bg-krikkit-surface"
    >
        <div class="grid grid-cols-2 divide-x divide-krikkit-line">
            <div class="krikkit-scroll-hover max-h-48 overflow-y-auto p-1">
                <p class="px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-krikkit-subtle">Hour</p>
                <template x-for="h in hours" :key="'h'+h">
                    <button
                        type="button"
                        class="flex w-full rounded-lg px-2.5 py-1.5 text-left text-sm transition"
                        :class="String(hour).padStart(2,'0') === h ? 'bg-krikkit-fill text-krikkit-on-fill' : 'text-krikkit-fg-soft hover:bg-krikkit-soft'"
                        @click="setHour(h)"
                        x-text="h"
                    ></button>
                </template>
            </div>
            <div class="krikkit-scroll-hover max-h-48 overflow-y-auto p-1">
                <p class="px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-krikkit-subtle">Min</p>
                <template x-for="m in minutes()" :key="'m'+m">
                    <button
                        type="button"
                        class="flex w-full rounded-lg px-2.5 py-1.5 text-left text-sm transition"
                        :class="String(minute).padStart(2,'0') === m ? 'bg-krikkit-fill text-krikkit-on-fill' : 'text-krikkit-fg-soft hover:bg-krikkit-soft'"
                        @click="setMinute(m)"
                        x-text="m"
                    ></button>
                </template>
            </div>
        </div>
        <div class="border-t border-krikkit-line p-2">
            <button type="button" class="w-full rounded-lg bg-krikkit-soft px-3 py-1.5 text-sm font-medium text-krikkit-fg hover:bg-krikkit-soft" @click="open = false">Done</button>
        </div>
    </div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\timePicker.blade.php ENDPATH**/ ?>