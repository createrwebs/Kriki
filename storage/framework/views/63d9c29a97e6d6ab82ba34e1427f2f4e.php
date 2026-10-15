<?php
    use App\Support\Site\SiteSettings;
    use App\Support\Ui\ThemePalette;

    $siteTheme = app(SiteSettings::class)->theme();
    $userTheme = ThemePalette::normalize(auth()->user()?->appearance);
    $savedTheme = ($siteTheme['lock_members'] ?? false) ? null : $userTheme;
    $fallbackTheme = ThemePalette::normalize([
        'accent' => $siteTheme['accent'],
        'base' => $siteTheme['base'],
        'mode' => in_array($siteTheme['mode'], ['light', 'dark'], true) ? $siteTheme['mode'] : 'light',
    ]);
?>
<style>
    html { background-color: var(--color-krikkit-canvas, #fff); color-scheme: light; }
    html.dark { background-color: var(--color-krikkit-canvas, #0a0a0a); color-scheme: dark; }
</style>
<script type="text/javascript">
    (() => {
        const root = document.documentElement;
        var saved = <?php echo \Illuminate\Support\Js::from($savedTheme)->toHtml() ?>;
        var site = <?php echo \Illuminate\Support\Js::from($fallbackTheme)->toHtml() ?>;
        var siteMode = <?php echo \Illuminate\Support\Js::from($siteTheme['mode'])->toHtml() ?>;
        var locked = <?php echo \Illuminate\Support\Js::from((bool) $siteTheme['lock_members'])->toHtml() ?>;
        var cookieKey = <?php echo \Illuminate\Support\Js::from(ThemePalette::MODE_COOKIE)->toHtml() ?>;

        if (saved && !locked) {
            localStorage.setItem('krikkit-theme', saved.mode)
            localStorage.setItem('krikkit-theme-accent', saved.accent)
            localStorage.setItem('krikkit-theme-base', saved.base)
        } else if (site) {
            if (locked || !localStorage.getItem('krikkit-theme-accent')) {
                localStorage.setItem('krikkit-theme-accent', site.accent)
                localStorage.setItem('krikkit-theme-base', site.base)
            }
            if (locked || !localStorage.getItem('krikkit-theme')) {
                if (siteMode === 'light' || siteMode === 'dark') {
                    localStorage.setItem('krikkit-theme', siteMode)
                }
            }
        }

        var modeStored = localStorage.getItem('krikkit-theme')
        var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
        var mode = modeStored === 'dark' || modeStored === 'light'
            ? modeStored
            : (prefersDark ? 'dark' : 'light')

        root.classList.toggle('dark', mode === 'dark')
        root.style.colorScheme = mode

        try {
            document.cookie = cookieKey + '=' + mode
                + '; Path=/; Max-Age=31536000; SameSite=Lax'
                + (location.protocol === 'https:' ? '; Secure' : '')
        } catch (e) {}
    })()
</script>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views/components/layouts/partials/themeBoot.blade.php ENDPATH**/ ?>