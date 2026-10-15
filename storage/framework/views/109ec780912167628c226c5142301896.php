<?php
    $footer = $landing->section('footer');
    $columns = is_array($footer['columns'] ?? null) ? $footer['columns'] : [];
    $social = is_array($footer['social'] ?? null) ? $footer['social'] : [];
    $legalExtra = is_array($footer['legal_extra'] ?? null) ? $footer['legal_extra'] : [];
?>

<footer>
    <div class="mx-auto max-w-7xl border-x border-krikkit-line">
        <div class="grid gap-10 border-b border-krikkit-line px-6 py-16 sm:px-10 md:grid-cols-2 md:py-20 lg:grid-cols-6 lg:px-12">
            <div class="lg:col-span-2">
                <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center text-krikkit-fg">
                    <?php if (isset($component)) { $__componentOriginal956824a8769013cddb9947cefd34cfad = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal956824a8769013cddb9947cefd34cfad = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.mark','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.mark'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal956824a8769013cddb9947cefd34cfad)): ?>
<?php $attributes = $__attributesOriginal956824a8769013cddb9947cefd34cfad; ?>
<?php unset($__attributesOriginal956824a8769013cddb9947cefd34cfad); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal956824a8769013cddb9947cefd34cfad)): ?>
<?php $component = $__componentOriginal956824a8769013cddb9947cefd34cfad; ?>
<?php unset($__componentOriginal956824a8769013cddb9947cefd34cfad); ?>
<?php endif; ?>
                </a>
                <p class="mt-4 max-w-xs text-sm leading-relaxed text-krikkit-muted">
                    <?php echo e($footer['blurb']); ?>

                </p>
                <form id="subscribe" action="<?php echo e(route('newsletter.store')); ?>" method="post" class="mt-6 flex max-w-sm scroll-mt-20 border border-krikkit-line">
                    <?php echo csrf_field(); ?>
                    <label class="sr-only" for="home-subscribe"><?php echo e(__('home.Subscribe to updates')); ?></label>
                    <input id="home-subscribe" type="email" name="email" required placeholder="<?php echo e($footer['subscribe_placeholder']); ?>" class="min-w-0 flex-1 bg-transparent px-3 py-2.5 text-sm text-krikkit-fg outline-none placeholder:text-krikkit-subtle">
                    <button type="submit" class="bg-accent px-4 text-sm font-medium text-accent-foreground"><?php echo e($footer['subscribe_label']); ?></button>
                </form>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('newsletter') === 'ok'): ?>
                    <p class="mt-3 text-sm text-krikkit-fg-soft"><?php echo e(__('home.You are on the list.')); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-3 text-sm text-red-400"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-krikkit-muted"><?php echo e($column['heading']); ?></p>
                    <div class="mt-4 flex flex-col gap-2 text-sm text-krikkit-muted">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $column['links'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php
                                $href = (string) ($link['href'] ?? '');
                                if ($href === 'blog' && ! $hasBlog) {
                                    continue;
                                }
                            ?>
                            <a href="<?php echo e($landing->href($href, '#')); ?>" class="hover:text-krikkit-fg"><?php echo e($link['label']); ?></a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-krikkit-muted"><?php echo e($footer['legal_heading']); ?></p>
                <div class="mt-4 flex flex-col gap-2 text-sm text-krikkit-muted">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($site->privacyPublished()): ?>
                        <a href="<?php echo e(route('privacy')); ?>" class="hover:text-krikkit-fg">Privacy</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($site->termsPublished()): ?>
                        <a href="<?php echo e(route('terms')); ?>" class="hover:text-krikkit-fg">Terms</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $legalExtra; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <span><?php echo e($item); ?></span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-between gap-3 px-6 py-6 text-xs text-krikkit-subtle sm:flex-row sm:items-center sm:px-10 lg:px-12">
            <p>© <?php echo e(now()->year); ?> <?php echo e($site->name()); ?>. All rights reserved.</p>
            <p class="flex gap-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $social; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($item['href'] ?? null)): ?>
                        <a href="<?php echo e($landing->href($item['href'], '#')); ?>" class="hover:text-krikkit-fg"><?php echo e($item['label']); ?></a>
                    <?php else: ?>
                        <span><?php echo e($item['label']); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </p>
        </div>
    </div>
</footer>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\home\partials\footer.blade.php ENDPATH**/ ?>