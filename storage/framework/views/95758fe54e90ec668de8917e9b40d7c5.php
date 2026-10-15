<?php echo $__env->make('livewire.dashboard.settings.options.legal', [
    'section' => $section,
    'nav' => $nav,
    'heading' => __('dashboard.Terms'),
    'copy' => __('dashboard.Public terms of use. Unpublished pages return 404.'),
    'publicRoute' => 'terms',
    'published' => $published,
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\dashboard\settings\options\terms.blade.php ENDPATH**/ ?>