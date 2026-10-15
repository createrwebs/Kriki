<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(__('installer::installer.title', ['name' => config('installer.name', 'App')])); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(config('installer.favicon', asset('favicon.png'))); ?>">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sora:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(route('installer.assets.css')); ?>">
    <?php
        $accent = config('installer.theme.accent', config('installer.theme.primary', '#262626'));
        $accentForeground = config('installer.theme.accent_foreground', '#ffffff');
        $accentDark = config('installer.theme.accent_dark', '#ffffff');
        $accentDarkForeground = config('installer.theme.accent_dark_foreground', '#1a1a1a');
        $themeMode = config('installer.theme.mode', 'system');
    ?>
    <style>
        :root {
            --theme-accent: <?php echo e($accent); ?>;
            --theme-accent-foreground: <?php echo e($accentForeground); ?>;
            --theme-accent-dark: <?php echo e($accentDark); ?>;
            --theme-accent-dark-foreground: <?php echo e($accentDarkForeground); ?>;
        }
        html { background-color: var(--color-krikkit-canvas, #fff); color-scheme: light; }
        html.dark { background-color: var(--color-krikkit-canvas, #0a0a0a); color-scheme: dark; }
        [x-cloak] { display: none !important; }
    </style>
    <script>
        (() => {
            const configured = <?php echo \Illuminate\Support\Js::from($themeMode)->toHtml() ?>;
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const dark = configured === 'dark' || (configured === 'system' && prefersDark);
            document.documentElement.classList.toggle('dark', dark);
            document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
        })();
    </script>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body>
    <?php echo e($slot); ?>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html>
<?php /**PATH D:\codea\krikkit-ai-website\vendor\deep42\hitchhiker\resources\views\layouts\installer.blade.php ENDPATH**/ ?>