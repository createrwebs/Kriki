<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'text',
    'size' => 'md',
    'invalid' => false,
    'copyable' => false,
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
    'type' => 'text',
    'size' => 'md',
    'invalid' => false,
    'copyable' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $hasPrefix = isset($prefix);
    $hasSuffix = isset($suffix);
    $wrapped = $copyable || $hasPrefix || $hasSuffix;

    $height = match ($size) {
        'sm' => 'h-9 text-sm leading-5',
        'lg' => 'h-12 text-base leading-6',
        default => 'h-10 text-sm leading-5',
    };

    $pl = match ($size) {
        'sm' => 'pl-3.5',
        'lg' => 'pl-4',
        default => 'pl-4',
    };
    $pr = match ($size) {
        'sm' => 'pr-3.5',
        'lg' => 'pr-4',
        default => 'pr-4',
    };

    if ($hasPrefix) {
        $pl = match ($size) {
            'sm' => 'pl-8',
            'lg' => 'pl-10',
            default => 'pl-9',
        };
    }

    if ($copyable) {
        $pr = match ($size) {
            'sm' => 'pr-14',
            'lg' => 'pr-16',
            default => 'pr-14',
        };
    } elseif ($hasSuffix) {
        $pr = match ($size) {
            'sm' => 'pr-12',
            'lg' => 'pr-14',
            default => 'pr-12',
        };
    }

    $copyBtnHeight = match ($size) {
        'sm' => 'h-7',
        'lg' => 'h-9',
        default => 'h-8',
    };

    $affixInset = match ($size) {
        'sm' => 'px-3.5',
        'lg' => 'px-4',
        default => 'px-4',
    };

    $inputClasses = [
        'w-full rounded-full border bg-krikkit-surface outline-none transition',
        'text-krikkit-fg placeholder:text-krikkit-subtle',
        'disabled:cursor-not-allowed disabled:opacity-50',
        $height,
        $pl,
        $pr,
        $invalid
            ? 'border-red-400/70 focus:border-red-500'
            : 'border-transparent focus:border-krikkit-muted/40',
    ];
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($wrapped): ?>
    <div
        <?php echo e($attributes->only('class')->class('relative w-full')); ?>

        <?php if($copyable): ?>
            x-data="{
                copied: false,
                async copy() {
                    const el = this.$refs.input
                    const text = el?.value ?? ''
                    try {
                        await navigator.clipboard.writeText(text)
                        this.copied = true
                        clearTimeout(this._copyTimer)
                        this._copyTimer = setTimeout(() => { this.copied = false }, 1600)
                    } catch (e) {}
                },
            }"
        <?php endif; ?>
    >
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($prefix)): ?>
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center <?php echo e($affixInset); ?> text-sm tabular-nums text-krikkit-muted"><?php echo e($prefix); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <input
            <?php if($copyable): ?> x-ref="input" <?php endif; ?>
            type="<?php echo e($type); ?>"
            <?php echo e($attributes->except('class')->class($inputClasses)); ?>

        >

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($suffix)): ?>
            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center <?php echo e($affixInset); ?> text-xs font-medium tracking-wide text-krikkit-muted"><?php echo e($suffix); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($copyable): ?>
            <button
                type="button"
                class="absolute inset-y-0 right-1.5 my-auto inline-flex <?php echo e($copyBtnHeight); ?> shrink-0 items-center rounded-full px-2.5 text-xs font-medium text-krikkit-muted transition hover:bg-krikkit-soft hover:text-krikkit-fg"
                x-on:click="copy()"
                x-bind:aria-label="copied ? <?php echo \Illuminate\Support\Js::from(__('dashboard.Copied'))->toHtml() ?> : <?php echo \Illuminate\Support\Js::from(__('dashboard.Copy'))->toHtml() ?>"
            >
                <span x-text="copied ? <?php echo \Illuminate\Support\Js::from(__('dashboard.Copied'))->toHtml() ?> : <?php echo \Illuminate\Support\Js::from(__('dashboard.Copy'))->toHtml() ?>"></span>
            </button>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
<?php else: ?>
    <input
        type="<?php echo e($type); ?>"
        <?php echo e($attributes->class($inputClasses)); ?>

    >
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views/krikkit/input.blade.php ENDPATH**/ ?>