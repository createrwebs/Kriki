<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="min-h-screen bg-krikkit-canvas">
        <header class="border-b border-krikkit-line bg-krikkit-surface">
            <div class="mx-auto flex max-w-5xl items-center justify-between gap-4 px-6 py-4">
                <a href="<?php echo e(route('pricing')); ?>" class="text-sm font-semibold tracking-tight text-krikkit-fg"><?php echo e($site->name()); ?></a>
                <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-krikkit-subtle"><?php echo e(__('dashboard.Secure checkout')); ?></p>
            </div>
        </header>

        <main class="mx-auto grid max-w-5xl gap-8 px-6 py-10 lg:grid-cols-5 lg:py-16">
            <section class="border border-krikkit-line bg-krikkit-surface p-6 sm:p-8 lg:col-span-3">
                <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-krikkit-subtle"><?php echo e(__('dashboard.Order summary')); ?></p>
                <div class="mt-4 flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-semibold tracking-tight text-krikkit-fg"><?php echo e($plan->title); ?></h1>
                        <p class="mt-2 text-sm leading-relaxed text-krikkit-muted"><?php echo e($plan->summary); ?></p>
                    </div>
                    <a href="<?php echo e(route('pricing')); ?>" class="shrink-0 text-sm text-krikkit-muted underline hover:text-krikkit-fg"><?php echo e(__('dashboard.Change pack')); ?></a>
                </div>

                <div class="mt-8 inline-flex items-center gap-1 border border-krikkit-line bg-krikkit-soft/40 p-1">
                    <a
                        href="<?php echo e(route('checkout.start', $plan)); ?>?interval=monthly"
                        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                            'px-4 py-1.5 text-sm font-medium transition',
                            'bg-krikkit-fill text-krikkit-on-fill' => $interval === 'monthly',
                            'text-krikkit-muted hover:text-krikkit-fg' => $interval !== 'monthly',
                        ]); ?>"
                    >
                        <?php echo e(__('home.Monthly')); ?>

                    </a>
                    <a
                        href="<?php echo e(route('checkout.start', $plan)); ?>?interval=yearly"
                        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                            'px-4 py-1.5 text-sm font-medium transition',
                            'bg-krikkit-fill text-krikkit-on-fill' => $interval === 'yearly',
                            'text-krikkit-muted hover:text-krikkit-fg' => $interval !== 'yearly',
                        ]); ?>"
                    >
                        <?php echo e(__('home.Yearly')); ?>

                    </a>
                </div>

                <dl class="mt-8 divide-y divide-krikkit-line border-y border-krikkit-line text-sm">
                    <div class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-krikkit-muted"><?php echo e($plan->title); ?> · <?php echo e($interval === 'yearly' ? __('home.Yearly') : __('home.Monthly')); ?></dt>
                        <dd class="font-medium text-krikkit-fg"><?php echo e($price); ?></dd>
                    </div>
                    <div class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-krikkit-muted"><?php echo e(__('dashboard.Billed today')); ?></dt>
                        <dd class="text-lg font-semibold text-krikkit-fg"><?php echo e($price); ?></dd>
                    </div>
                </dl>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($features !== []): ?>
                    <div class="mt-8">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-krikkit-subtle"><?php echo e(__('dashboard.Included in this pack')); ?></p>
                        <ul class="mt-4 space-y-2.5">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <li class="flex items-start gap-2.5 text-sm text-krikkit-fg">
                                    <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'check','class' => 'mt-0.5 size-4 text-accent-content']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check','class' => 'mt-0.5 size-4 text-accent-content']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2f88b3d15b59738674618f1a73b04e5e)): ?>
<?php $attributes = $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e; ?>
<?php unset($__attributesOriginal2f88b3d15b59738674618f1a73b04e5e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2f88b3d15b59738674618f1a73b04e5e)): ?>
<?php $component = $__componentOriginal2f88b3d15b59738674618f1a73b04e5e; ?>
<?php unset($__componentOriginal2f88b3d15b59738674618f1a73b04e5e); ?>
<?php endif; ?>
                                    <span><?php echo e($feature); ?></span>
                                </li>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </section>

            <aside class="lg:col-span-2">
                <section class="border border-krikkit-line bg-krikkit-surface p-6 sm:p-8">
                    <h2 class="text-lg font-semibold tracking-tight text-krikkit-fg"><?php echo e(__('dashboard.Payment')); ?></h2>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($account !== ''): ?>
                        <p class="mt-2 text-sm text-krikkit-muted"><?php echo e($account); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="mt-6 space-y-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <form method="GET" action="<?php echo e(route('checkout.start', $plan)); ?>">
                                <input type="hidden" name="interval" value="<?php echo e($interval); ?>">
                                <input type="hidden" name="driver" value="<?php echo e($row['driver']); ?>">
                                <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['type' => 'submit','class' => 'w-full !rounded-none','variant' => $row['driver'] === 'stripe' ? 'primary' : 'outline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','class' => 'w-full !rounded-none','variant' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($row['driver'] === 'stripe' ? 'primary' : 'outline')]); ?>
                                    <?php echo e(__('dashboard.Pay with :method', ['method' => $row['title']])); ?>

                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $attributes = $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $component = $__componentOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
                            </form>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::callout','data' => ['tone' => 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::callout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'warning']); ?>
                                <?php if (isset($component)) { $__componentOriginal617e293475666fd5160886d17e8563d4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal617e293475666fd5160886d17e8563d4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::callout.text','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::callout.text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Checkout is not configured.')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal617e293475666fd5160886d17e8563d4)): ?>
<?php $attributes = $__attributesOriginal617e293475666fd5160886d17e8563d4; ?>
<?php unset($__attributesOriginal617e293475666fd5160886d17e8563d4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal617e293475666fd5160886d17e8563d4)): ?>
<?php $component = $__componentOriginal617e293475666fd5160886d17e8563d4; ?>
<?php unset($__componentOriginal617e293475666fd5160886d17e8563d4); ?>
<?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05)): ?>
<?php $attributes = $__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05; ?>
<?php unset($__attributesOriginalf1b8ec3c1d8423162ce33c91eac96d05); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05)): ?>
<?php $component = $__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05; ?>
<?php unset($__componentOriginalf1b8ec3c1d8423162ce33c91eac96d05); ?>
<?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <p class="mt-6 flex items-start gap-2 text-xs leading-relaxed text-krikkit-muted">
                        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'lock-closed','class' => 'mt-0.5 size-3.5 shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'lock-closed','class' => 'mt-0.5 size-3.5 shrink-0']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2f88b3d15b59738674618f1a73b04e5e)): ?>
<?php $attributes = $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e; ?>
<?php unset($__attributesOriginal2f88b3d15b59738674618f1a73b04e5e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2f88b3d15b59738674618f1a73b04e5e)): ?>
<?php $component = $__componentOriginal2f88b3d15b59738674618f1a73b04e5e; ?>
<?php unset($__componentOriginal2f88b3d15b59738674618f1a73b04e5e); ?>
<?php endif; ?>
                        <span><?php echo e(__('dashboard.You will finish payment on a secure page.')); ?></span>
                    </p>
                </section>

                <p class="mt-4 text-sm text-krikkit-muted">
                    <a href="<?php echo e(route('pricing')); ?>" class="underline hover:text-krikkit-fg"><?php echo e(__('dashboard.Back to pricing')); ?></a>
                </p>
            </aside>
        </main>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\checkout\checkout.blade.php ENDPATH**/ ?>