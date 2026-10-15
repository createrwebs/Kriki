<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'month' => null,
    'year' => null,
    'selected' => null,
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
    'month' => null,
    'year' => null,
    'selected' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $month = (int) ($month ?? now()->month);
    $year = (int) ($year ?? now()->year);
    $start = \Carbon\Carbon::create($year, $month, 1)->startOfWeek(\Carbon\Carbon::MONDAY);
    $end = \Carbon\Carbon::create($year, $month, 1)->endOfMonth()->endOfWeek(\Carbon\Carbon::SUNDAY);
    $days = [];
    for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
        $days[] = $d->copy();
    }
    $selectedDate = $selected ? \Carbon\Carbon::parse($selected)->toDateString() : null;
    $label = \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y');
?>

<div
    x-data="{ selected: <?php echo \Illuminate\Support\Js::from($selectedDate)->toHtml() ?> }"
    <?php echo e($attributes->class('w-full max-w-sm rounded-xl bg-krikkit-surface p-3')); ?>

>
    <div class="mb-3 flex items-center justify-between px-1">
        <p class="text-sm font-semibold text-krikkit-fg"><?php echo e($label); ?></p>
    </div>
    <div class="mb-1 grid grid-cols-7 gap-1 text-center text-[11px] font-medium text-krikkit-muted">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <span class="py-1"><?php echo e($dow); ?></span>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>
    <div class="grid grid-cols-7 gap-1">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php
                $inMonth = $day->month === $month;
                $iso = $day->toDateString();
            ?>
            <button
                type="button"
                @click="selected = <?php echo \Illuminate\Support\Js::from($iso)->toHtml() ?>; $dispatch('krikkit-day', <?php echo \Illuminate\Support\Js::from($iso)->toHtml() ?>)"
                class="aspect-square rounded-lg text-sm transition"
                :class="{
                    'bg-krikkit-fill text-krikkit-on-fill': selected === <?php echo \Illuminate\Support\Js::from($iso)->toHtml() ?>,
                    'text-krikkit-subtle': <?php echo \Illuminate\Support\Js::from(! $inMonth)->toHtml() ?> && selected !== <?php echo \Illuminate\Support\Js::from($iso)->toHtml() ?>,
                    'text-krikkit-fg hover:bg-krikkit-soft': <?php echo \Illuminate\Support\Js::from($inMonth)->toHtml() ?> && selected !== <?php echo \Illuminate\Support\Js::from($iso)->toHtml() ?>,
                }"
            >
                <?php echo e($day->day); ?>

            </button>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\calendar.blade.php ENDPATH**/ ?>