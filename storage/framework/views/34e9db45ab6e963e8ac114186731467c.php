<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'multiple' => false,
    'accept' => null,
    'label' => null,
    'caption' => null,
    'preview' => null,
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
    'multiple' => false,
    'accept' => null,
    'label' => null,
    'caption' => null,
    'preview' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $label = $label ?? __('dashboard.Drop files here or browse');
    $caption = $caption ?? __('dashboard.PNG, JPG, PDF…');
?>

<div
    x-data="{
        dragging: false,
        files: [],
        preview: <?php echo \Illuminate\Support\Js::from($preview)->toHtml() ?>,
        onDrop(e) {
            this.dragging = false
            this.ingest(e.dataTransfer.files)
            this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }))
        },
        onChange(e) {
            this.ingest(e.target.files)
        },
        ingest(list) {
            this.files = [...list]
            this.$refs.input.files = list
            const file = this.files[0]
            if (file && file.type.startsWith('image/')) {
                this.preview = URL.createObjectURL(file)
            }
        },
    }"
    <?php echo e($attributes->only('class')->class('w-full')); ?>

>
    <label
        @dragover.prevent="dragging = true"
        @dragleave.prevent="dragging = false"
        @drop.prevent="onDrop"
        class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border border-dashed px-4 py-8 text-center transition"
        :class="dragging
            ? 'border-accent bg-accent/10'
            : 'border-krikkit-line bg-krikkit-canvas hover:border-krikkit-subtle'"
    >
        <template x-if="preview">
            <img :src="preview" alt="" class="max-h-16 max-w-full object-contain">
        </template>
        <template x-if="! preview">
            <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'upload','class' => 'size-6 text-krikkit-muted']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'upload','class' => 'size-6 text-krikkit-muted']); ?>
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
        </template>
        <span class="text-sm font-medium text-krikkit-fg-soft"><?php echo e($label); ?></span>
        <span
            class="text-xs text-krikkit-muted"
            x-text="files.length ? [...files].map(f => f.name).join(', ') : <?php echo \Illuminate\Support\Js::from($caption)->toHtml() ?>"
        ></span>
        <input
            x-ref="input"
            type="file"
            class="sr-only"
            <?php if($multiple): ?> multiple <?php endif; ?>
            <?php if($accept): ?> accept="<?php echo e($accept); ?>" <?php endif; ?>
            <?php echo e($attributes->except('class')); ?>

            @change="onChange"
        >
    </label>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\fileUpload.blade.php ENDPATH**/ ?>