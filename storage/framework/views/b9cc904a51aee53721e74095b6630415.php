<?php echo $__env->make('livewire.dashboard.settings.options.legal', [
    'section' => $section,
    'nav' => $nav,
    'heading' => __('dashboard.Privacy policy'),
    'copy' => __('dashboard.Public privacy policy. Unpublished pages return 404.'),
    'publicRoute' => 'privacy',
    'published' => $published,
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\dashboard\settings\options\privacy.blade.php ENDPATH**/ ?>