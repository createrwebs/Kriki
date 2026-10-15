<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'section' => 'general',
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
    'section' => 'general',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $user = auth()->user();
?>

<div
    class="flex min-h-screen bg-krikkit-canvas"
    x-data="{
        navOpen: false,
        desktop: false,
        init() {
            const mq = window.matchMedia('(min-width: 1024px)')
            const sync = () => {
                this.desktop = mq.matches
                if (this.desktop) this.navOpen = false
            }
            sync()
            mq.addEventListener('change', sync)
        },
        openNav() { this.navOpen = true },
        closeNav() { this.navOpen = false },
        toggleNav() { this.navOpen = ! this.navOpen },
    }"
    x-on:keydown.escape.window="if (navOpen) closeNav()"
>
    <div
        x-cloak
        x-show="navOpen"
        x-transition.opacity.duration.200ms
        class="fixed inset-0 z-40 bg-black/40 lg:hidden"
        x-on:click="closeNav()"
    ></div>

    <?php echo $__env->make('livewire.settings.partials.sidebar', ['section' => $section, 'user' => $user], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="relative flex min-w-0 flex-1 flex-col bg-krikkit-canvas">
        <header class="relative z-10 flex min-h-14 items-center gap-3 bg-transparent px-4 py-2 lg:hidden">
            <button
                type="button"
                class="inline-flex size-9 cursor-pointer items-center justify-center rounded-full text-krikkit-fg-soft transition hover:bg-krikkit-soft"
                x-on:click="toggleNav()"
                x-bind:aria-expanded="navOpen.toString()"
                aria-controls="krikkit-settings-nav"
                aria-label="<?php echo e(__('studio.Open navigation')); ?>"
            >
                <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'bars-3','class' => 'size-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bars-3','class' => 'size-5']); ?>
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
            <span class="truncate text-sm font-semibold tracking-tight text-krikkit-fg"><?php echo e(__('settings.Settings')); ?></span>
        </header>
        <div class="mx-auto w-full max-w-3xl flex-1 px-5 py-10 sm:px-8 sm:py-14">
            <?php echo e($slot); ?>

        </div>
    </div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\components\settings\frame.blade.php ENDPATH**/ ?>