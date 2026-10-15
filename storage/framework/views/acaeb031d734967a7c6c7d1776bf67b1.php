<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'user',
    'site',
    'planTitle',
    'packOffer' => null,
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
    'user',
    'site',
    'planTitle',
    'packOffer' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

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

    <?php echo $__env->make('livewire.studio.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="relative flex min-w-0 flex-1 flex-col bg-krikkit-canvas">
        <div
            class="pointer-events-none absolute inset-x-0 top-0 z-0 h-96 bg-[radial-gradient(ellipse_at_top,color-mix(in_oklab,var(--color-accent)_28%,transparent),transparent_70%)]"
            aria-hidden="true"
        ></div>
        <div class="relative z-10 mx-auto flex min-h-0 w-full max-w-screen-2xl flex-1 flex-col">
            <?php echo $__env->make('livewire.studio.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="flex min-h-0 flex-1 flex-col">
                <?php echo e($slot); ?>

            </div>
            <?php echo $__env->make('livewire.studio.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\components\studio\frame.blade.php ENDPATH**/ ?>