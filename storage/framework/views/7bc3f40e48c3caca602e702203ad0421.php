<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'placeholder' => 'Select…',
    'value' => null,
    'invalid' => false,
    'name' => null,
    'searchable' => false,
    'searchPlaceholder' => null,
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
    'placeholder' => 'Select…',
    'value' => null,
    'invalid' => false,
    'name' => null,
    'searchable' => false,
    'searchPlaceholder' => null,
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
    $name = $name ?? $attributes->get('name');
    $initial = $value ?? $attributes->get('value');
    $searchPlaceholder = $searchPlaceholder ?? __('dashboard.Search…');
    $triggerSize = match ($size) {
        'sm' => 'h-9 px-3.5 text-sm leading-5',
        'lg' => 'h-12 px-4 text-base leading-6',
        default => 'h-10 px-4 text-sm leading-5',
    };
?>

<div
    x-data="{
        open: false,
        placement: 'bottom',
        value: <?php echo \Illuminate\Support\Js::from($initial !== null ? (string) $initial : null)->toHtml() ?>,
        label: '',
        placeholder: <?php echo \Illuminate\Support\Js::from($placeholder)->toHtml() ?>,
        searchable: <?php echo \Illuminate\Support\Js::from((bool) $searchable)->toHtml() ?>,
        query: '',
        optionLabels: [],
        choose(v, l) {
            this.value = String(v)
            this.label = l
            this.query = ''
            this.open = false
            this.$nextTick(() => this.emit())
        },
        emit() {
            const input = this.$refs.input
            if (! input) return
            input.value = this.value ?? ''
            input.dispatchEvent(new Event('input', { bubbles: true }))
            input.dispatchEvent(new Event('change', { bubbles: true }))
        },
        matches(text) {
            if (! this.searchable) return true
            const q = this.query.trim().toLowerCase()
            if (! q) return true
            return String(text).toLowerCase().includes(q)
        },
        noResults() {
            if (! this.searchable || ! this.query.trim()) return false
            return ! this.optionLabels.some((text) => this.matches(text))
        },
        toggle() {
            if (this.open) {
                this.close()
                return
            }
            this.updatePlacement()
            this.open = true
            if (this.searchable) {
                this.$nextTick(() => this.$refs.search?.focus())
            }
        },
        close() {
            this.open = false
            this.query = ''
        },
        updatePlacement() {
            const rect = this.$el.getBoundingClientRect()
            const spaceBelow = window.innerHeight - rect.bottom
            const spaceAbove = rect.top
            const panelMax = 280
            this.placement = (spaceBelow < panelMax && spaceAbove > spaceBelow) ? 'top' : 'bottom'
        }
    }"
    x-on:keydown.escape.window="close()"
    x-on:click.outside="close()"
    <?php echo e($attributes->only('class')->class('relative w-full')); ?>

>
    <button
        type="button"
        x-on:click="toggle()"
        class="flex w-full items-center justify-between gap-2 rounded-full border bg-krikkit-surface text-left outline-none transition
            text-krikkit-fg
            <?php echo e($triggerSize); ?>

            <?php echo e($invalid ? 'border-red-400/70' : 'border-transparent focus-visible:border-krikkit-muted/40'); ?>"
        :aria-expanded="open.toString()"
    >
        <span class="min-w-0 flex-1 truncate leading-[inherit]" :class="label ? '' : 'text-krikkit-subtle'" x-text="label || placeholder"></span>
        <span class="shrink-0 text-krikkit-subtle transition" :class="open ? 'rotate-180' : ''">
            <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'chevron-down','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-down','class' => 'size-4']); ?>
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
        </span>
    </button>

    <input
        x-ref="input"
        type="hidden"
        <?php if($name): ?> name="<?php echo e($name); ?>" <?php endif; ?>
        :value="value ?? ''"
        <?php echo e($attributes->whereStartsWith('wire:')); ?>

    >

    <div
        x-cloak
        x-show="open"
        x-transition.opacity.duration.100ms
        class="absolute z-50 flex w-full flex-col overflow-hidden rounded-lg bg-krikkit-surface"
        :class="placement === 'top' ? 'bottom-full mb-1.5' : 'top-full mt-1.5'"
        role="listbox"
    >
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($searchable): ?>
            <div class="shrink-0 border-b border-krikkit-line/50 p-1.5" x-on:click.stop>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-2.5 flex items-center text-krikkit-subtle">
                        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'magnifying-glass','class' => 'size-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'magnifying-glass','class' => 'size-3.5']); ?>
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
                    </span>
                    <input
                        x-ref="search"
                        type="search"
                        x-model="query"
                        placeholder="<?php echo e($searchPlaceholder); ?>"
                        autocomplete="off"
                        class="h-8 w-full rounded-md border-0 bg-krikkit-soft pl-8 pr-2.5 text-sm text-krikkit-fg outline-none placeholder:text-krikkit-subtle"
                        x-on:keydown.enter.prevent
                        x-on:keydown.escape.stop="close()"
                    >
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="flex max-h-52 flex-col gap-0.5 overflow-auto p-1.5">
            <?php echo e($slot); ?>


            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($searchable): ?>
                <p
                    x-show="noResults()"
                    x-cloak
                    class="px-2.5 py-2 text-sm text-krikkit-muted"
                ><?php echo e(__('dashboard.No results.')); ?></p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\select.blade.php ENDPATH**/ ?>