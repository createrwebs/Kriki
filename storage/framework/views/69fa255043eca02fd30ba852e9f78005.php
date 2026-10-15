<?php
    use App\Support\Ui\ThemePalette;

    $savedTheme = ThemePalette::normalize(auth()->user()?->appearance);
?>
<style id="krikkit-theme"><?php echo ThemePalette::runtimeCss($savedTheme['accent'] ?? 'base', $savedTheme['base'] ?? 'neutral'); ?></style>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views/components/layouts/partials/themeStyle.blade.php ENDPATH**/ ?>