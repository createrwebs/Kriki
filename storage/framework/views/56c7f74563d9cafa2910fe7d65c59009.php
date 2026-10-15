<?php
    $site = $site ?? app(\App\Support\Site\SiteSettings::class);
    $logoLight = $site->logoUrl('light');
    $logoDark = $site->logoUrl('dark');
    $iconLight = $site->iconUrl('light');
    $iconDark = $site->iconUrl('dark');
    $showLogo = $logoLight || $logoDark;
    $showIcon = ! $showLogo && ($iconLight || $iconDark);
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showLogo): ?>
    <span <?php echo e($attributes->class('inline-flex items-center')); ?>>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logoLight): ?>
            <img src="<?php echo e($logoLight); ?>" alt="<?php echo e($site->name()); ?>" class="h-8 max-w-40 object-contain dark:hidden">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logoDark || $logoLight): ?>
            <img src="<?php echo e($logoDark ?? $logoLight); ?>" alt="<?php echo e($site->name()); ?>" class="<?php echo e($logoLight ? 'hidden dark:block' : ''); ?> h-8 max-w-40 object-contain">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </span>
<?php elseif($showIcon): ?>
    <span <?php echo e($attributes->class('inline-flex items-center')); ?>>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($iconLight): ?>
            <img src="<?php echo e($iconLight); ?>" alt="<?php echo e($site->name()); ?>" class="size-8 object-contain dark:hidden">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($iconDark || $iconLight): ?>
            <img src="<?php echo e($iconDark ?? $iconLight); ?>" alt="<?php echo e($site->name()); ?>" class="<?php echo e($iconLight ? 'hidden dark:block' : ''); ?> size-8 object-contain">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </span>
<?php else: ?>
    <span <?php echo e($attributes->class('inline-flex size-8 items-center justify-center rounded-lg bg-accent text-xs font-semibold tracking-tight text-accent-foreground')); ?>>
        <?php echo e(strtoupper(substr($site->name(), 0, 1))); ?>

    </span>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\components\site\mark.blade.php ENDPATH**/ ?>