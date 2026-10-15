<?php
    $hasBlog = \App\Support\Content\PublicIndex::blogIsLive();
    $hasPages = \App\Support\Content\PublicIndex::pagesAreLive();
    $socialX = trim((string) $site->value('general', 'social_x', ''));
    $socialGithub = trim((string) $site->value('general', 'social_github', ''));
?>

<footer class="mt-auto border-t border-krikkit-line/50 px-4 py-12 sm:px-6 lg:px-10">
    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('studio.Product')); ?></p>
            <div class="mt-3 flex flex-col gap-2 text-sm text-krikkit-muted">
                <a href="<?php echo e(route('lab')); ?>" class="hover:text-krikkit-fg"><?php echo e(__('messages.Lab')); ?></a>
                <a href="<?php echo e(route('home')); ?>" wire:navigate class="hover:text-krikkit-fg"><?php echo e(__('studio.Home')); ?></a>
                <a href="<?php echo e(route('projects')); ?>" wire:navigate class="hover:text-krikkit-fg"><?php echo e(__('studio.Projects')); ?></a>
            </div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasBlog || $hasPages || $site->privacyPublished() || $site->termsPublished()): ?>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('studio.More')); ?></p>
                <div class="mt-3 flex flex-col gap-2 text-sm text-krikkit-muted">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasBlog): ?>
                        <a href="<?php echo e(route('blog')); ?>" class="hover:text-krikkit-fg"><?php echo e(__('messages.Blog')); ?></a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasPages): ?>
                        <a href="<?php echo e(route('pages')); ?>" class="hover:text-krikkit-fg"><?php echo e(__('messages.Pages')); ?></a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($site->privacyPublished()): ?>
                        <a href="<?php echo e(route('privacy')); ?>" class="hover:text-krikkit-fg"><?php echo e(__('studio.Privacy')); ?></a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($site->termsPublished()): ?>
                        <a href="<?php echo e(route('terms')); ?>" class="hover:text-krikkit-fg"><?php echo e(__('studio.Terms')); ?></a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($socialX !== '' || $socialGithub !== ''): ?>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('studio.Connect')); ?></p>
                <div class="mt-3 flex flex-col gap-2 text-sm text-krikkit-muted">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($socialX !== ''): ?>
                        <a href="<?php echo e($socialX); ?>" class="hover:text-krikkit-fg" rel="noreferrer">X</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($socialGithub !== ''): ?>
                        <a href="<?php echo e($socialGithub); ?>" class="hover:text-krikkit-fg" rel="noreferrer">GitHub</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</footer>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\studio\partials\footer.blade.php ENDPATH**/ ?>