<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($delta === null): ?>
    <span class="tabular-nums text-krikkit-subtle">—</span>
<?php else: ?>
    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
        'tabular-nums font-medium',
        'text-teal-700 dark:text-teal-300' => $delta >= 0,
        'text-red-700 dark:text-red-300' => $delta < 0,
    ]); ?>"><?php echo e(($delta >= 0 ? '+' : '').$delta); ?>%</span>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\dashboard\finance\partials\delta.blade.php ENDPATH**/ ?>