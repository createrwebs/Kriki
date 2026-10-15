<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'placeholder' => 'Type a command…',
    'name' => 'command',
    'hotkey' => null,
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
    'placeholder' => 'Type a command…',
    'name' => 'command',
    'hotkey' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $hotkey = $hotkey ?? $name === 'command';
?>

<div
    x-data="{
        open: false,
        q: '',
        name: <?php echo \Illuminate\Support\Js::from($name)->toHtml() ?>,
        hotkey: <?php echo \Illuminate\Support\Js::from((bool) $hotkey)->toHtml() ?>,
        show() { this.open = true; this.$nextTick(() => this.$refs.input?.focus()) },
        hide() { this.open = false; this.q = '' },
        matches(detail) {
            const named = typeof detail === 'string'
                ? detail
                : (detail && typeof detail === 'object' ? detail.name : null)

            if (! named) return this.hotkey

            return named === this.name
        },
    }"
    x-on:krikkit-command-open.window="if (matches($event.detail)) show()"
    <?php if($hotkey): ?>
        x-on:keydown.meta.k.window.prevent="show()"
        x-on:keydown.ctrl.k.window.prevent="show()"
    <?php endif; ?>
    x-on:keydown.escape.window="if (open) hide()"
    x-on:livewire:navigating.window="hide()"
    <?php echo e($attributes->class('contents')); ?>

>
    <template x-teleport="body">
        <div x-cloak x-show="open" class="fixed inset-0 z-[70] flex items-center justify-center bg-black/40 p-4 sm:items-start sm:pt-[15vh]" @click.self="hide()">
            <div
                x-show="open"
                x-transition
                class="w-full max-w-lg overflow-hidden rounded-2xl border border-krikkit-line bg-krikkit-surface"
                @click.stop
            >
                <div class="flex items-center gap-2 border-b border-krikkit-line px-3">
                    <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'search','class' => 'size-4 text-krikkit-subtle']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'size-4 text-krikkit-subtle']); ?>
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
                    <input
                        x-ref="input"
                        type="text"
                        x-model="q"
                        placeholder="<?php echo e($placeholder); ?>"
                        class="h-12 w-full bg-transparent text-sm text-krikkit-fg outline-none placeholder:text-krikkit-subtle"
                    >
                </div>
                <div class="max-h-72 overflow-y-auto p-1" x-show="true">
                    <div class="space-y-0.5" x-bind:data-query="q">
                        <?php echo e($slot); ?>

                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\command.blade.php ENDPATH**/ ?>