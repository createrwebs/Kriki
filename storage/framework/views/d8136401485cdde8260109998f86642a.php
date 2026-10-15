<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value' => null,
    'placeholder' => 'Pick a date',
    'name' => null,
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
    'placeholder' => 'Pick a date',
    'name' => null,
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
?>

<div
    x-data="krikkitDateDesk({ value: <?php echo \Illuminate\Support\Js::from($initial)->toHtml() ?> })"
    @keydown.escape.window="open = false"
    @click.outside="open = false"
    <?php echo e($attributes->only('class')->class('relative w-full')); ?>

>
    <button
        type="button"
        @click="open = ! open; pane = 'days'"
        class="flex h-10 w-full items-center justify-between gap-2 rounded-full border bg-krikkit-surface px-4 text-left text-sm outline-none transition
            focus-visible:border-krikkit-muted
            <?php echo e($invalid ? 'border-red-400/70' : 'border-krikkit-line focus-visible:border-krikkit-muted/40'); ?>"
    >
        <span :class="value ? 'text-krikkit-fg' : 'text-krikkit-subtle'" x-text="value ? format(value) : <?php echo \Illuminate\Support\Js::from($placeholder)->toHtml() ?>"></span>
        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'calendar','class' => 'size-4 shrink-0 text-krikkit-subtle']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'calendar','class' => 'size-4 shrink-0 text-krikkit-subtle']); ?>
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
        class="absolute z-40 mt-1.5 w-[18.5rem] rounded-lg border border-krikkit-line bg-krikkit-surface p-3"
    >
        <div class="mb-2 flex items-center justify-between gap-1">
            <button type="button" class="rounded-lg p-1.5 text-krikkit-muted hover:bg-krikkit-soft hover:text-krikkit-fg" @click="prev()" aria-label="<?php echo e(__('messages.Previous month')); ?>">
                <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'chevron-left','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-left','class' => 'size-4']); ?>
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
            <div class="flex min-w-0 items-center gap-0.5">
                <button
                    type="button"
                    class="rounded-lg px-1.5 py-1 text-sm font-semibold hover:bg-krikkit-soft"
                    :class="pane === 'days' ? 'bg-krikkit-soft text-krikkit-fg' : 'text-krikkit-muted'"
                    @click="show('days')"
                >
                    <span x-text="dayStamp()"></span>
                </button>
                <button
                    type="button"
                    class="rounded-lg px-1.5 py-1 text-sm font-semibold hover:bg-krikkit-soft"
                    :class="pane === 'months' ? 'bg-krikkit-soft text-krikkit-fg' : 'text-krikkit-muted'"
                    @click="show('months')"
                >
                    <span x-text="monthStamp()"></span>
                </button>
                <button
                    type="button"
                    class="rounded-lg px-1.5 py-1 text-sm font-semibold hover:bg-krikkit-soft"
                    :class="pane === 'years' ? 'bg-krikkit-soft text-krikkit-fg' : 'text-krikkit-muted'"
                    @click="show('years')"
                >
                    <span x-text="cursor.y"></span>
                </button>
            </div>
            <button type="button" class="rounded-lg p-1.5 text-krikkit-muted hover:bg-krikkit-soft hover:text-krikkit-fg" @click="next()" aria-label="<?php echo e(__('messages.Next month')); ?>">
                <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'chevron-right','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right','class' => 'size-4']); ?>
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
        </div>

        <div x-show="pane === 'days'">
            <div class="mb-1 grid grid-cols-7 gap-1 text-center text-[11px] font-medium text-krikkit-muted">
                <template x-for="d in weekdays" :key="d"><span class="py-1" x-text="d"></span></template>
            </div>
            <div class="grid grid-cols-7 gap-1">
                <template x-for="day in days()" :key="day.iso">
                    <button
                        type="button"
                        class="aspect-square rounded-lg text-sm transition"
                        :class="{
                            'bg-krikkit-fill text-krikkit-on-fill': value === day.iso,
                            'text-krikkit-subtle': ! day.inMonth && value !== day.iso,
                            'text-krikkit-fg hover:bg-krikkit-soft': day.inMonth && value !== day.iso,
                        }"
                        @click="pick(day.iso)"
                        x-text="day.day"
                    ></button>
                </template>
            </div>
        </div>

        <div x-show="pane === 'months'" class="grid grid-cols-3 gap-1">
            <template x-for="month in months()" :key="month.m">
                <button
                    type="button"
                    class="rounded-lg px-2 py-2 text-sm font-medium hover:bg-krikkit-soft"
                    :class="cursor.m === month.m ? 'bg-krikkit-soft text-krikkit-fg' : 'text-krikkit-muted'"
                    @click="pickMonth(month.m)"
                    x-text="month.label"
                ></button>
            </template>
        </div>

        <div x-show="pane === 'years'" class="grid grid-cols-3 gap-1">
            <template x-for="year in years()" :key="year">
                <button
                    type="button"
                    class="rounded-lg px-2 py-2 text-sm font-medium hover:bg-krikkit-soft"
                    :class="cursor.y === year ? 'bg-krikkit-soft text-krikkit-fg' : 'text-krikkit-muted'"
                    @click="pickYear(year)"
                    x-text="year"
                ></button>
            </template>
        </div>
    </div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\datePicker.blade.php ENDPATH**/ ?>