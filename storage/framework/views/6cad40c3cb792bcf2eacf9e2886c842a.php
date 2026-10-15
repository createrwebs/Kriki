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

    <div
        x-data="{ menu: false }"
        x-on:keydown.escape.window="menu = false"
        class="min-h-screen bg-krikkit-canvas text-krikkit-fg"
    >
        <?php echo $__env->make('home.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <main>
            <section class="border-b border-krikkit-line">
                <div class="relative mx-auto max-w-7xl overflow-hidden border-x border-krikkit-line">
                    <div class="pointer-events-none absolute inset-0 krikkit-home-dots" aria-hidden="true"></div>
                    <div class="relative grid gap-12 px-6 py-20 sm:px-10 sm:py-28 lg:grid-cols-12 lg:px-12 lg:py-32">
                        <div class="lg:col-span-5">
                            <span class="inline-flex items-center gap-2 border border-krikkit-line bg-krikkit-soft px-3 py-1.5">
                                <span class="size-1.5 bg-accent"></span>
                                <span class="text-[11px] font-semibold uppercase tracking-[0.15em] text-krikkit-fg-soft"><?php echo e(__('home.Contact')); ?></span>
                            </span>
                            <h1 class="mt-6 max-w-md text-3xl font-medium leading-[1.15] tracking-tight text-krikkit-fg sm:text-5xl">
                                <?php echo e(__('home.Tell us what you need')); ?>

                            </h1>
                            <p class="mt-5 max-w-md text-sm leading-relaxed text-krikkit-muted sm:text-base">
                                <?php echo e(__('home.Write to us about Agency, custom packs, or anything else on the desk.')); ?>

                            </p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pack): ?>
                                <p class="mt-6 max-w-md border border-krikkit-line bg-krikkit-soft px-4 py-3 text-sm text-krikkit-fg-soft">
                                    <?php echo e(__('home.This note is about the :pack pack.', ['pack' => $pack->title])); ?>

                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="lg:col-span-7">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('contact') === 'ok'): ?>
                                <p class="border border-krikkit-line bg-krikkit-soft px-5 py-4 text-sm text-krikkit-fg/80">
                                    <?php echo e(__('home.Thanks — we will write back.')); ?>

                                </p>
                            <?php else: ?>
                                <form method="post" action="<?php echo e(route('contact.store')); ?>" class="space-y-5">
                                    <?php echo csrf_field(); ?>
                                    <div class="hidden" aria-hidden="true">
                                        <label for="contact-website"><?php echo e(__('home.Company')); ?></label>
                                        <input id="contact-website" type="text" name="website" tabindex="-1" autocomplete="off">
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pack): ?>
                                        <input type="hidden" name="pack" value="<?php echo e($pack->public_id); ?>">
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <div>
                                        <label for="contact-name" class="mb-2 block text-[11px] font-semibold uppercase tracking-[0.14em] text-krikkit-muted"><?php echo e(__('messages.Name')); ?></label>
                                        <input
                                            id="contact-name"
                                            type="text"
                                            name="name"
                                            value="<?php echo e($name); ?>"
                                            required
                                            maxlength="120"
                                            autocomplete="name"
                                            class="w-full border border-krikkit-line bg-transparent px-4 py-3 text-sm text-krikkit-fg outline-none placeholder:text-krikkit-subtle focus:border-krikkit-muted"
                                        >
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div>
                                        <label for="contact-email" class="mb-2 block text-[11px] font-semibold uppercase tracking-[0.14em] text-krikkit-muted"><?php echo e(__('dashboard.Email')); ?></label>
                                        <input
                                            id="contact-email"
                                            type="email"
                                            name="email"
                                            value="<?php echo e($email); ?>"
                                            required
                                            maxlength="255"
                                            autocomplete="email"
                                            class="w-full border border-krikkit-line bg-transparent px-4 py-3 text-sm text-krikkit-fg outline-none placeholder:text-krikkit-subtle focus:border-krikkit-muted"
                                        >
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div>
                                        <label for="contact-body" class="mb-2 block text-[11px] font-semibold uppercase tracking-[0.14em] text-krikkit-muted"><?php echo e(__('dashboard.Message')); ?></label>
                                        <textarea
                                            id="contact-body"
                                            name="body"
                                            required
                                            maxlength="5000"
                                            rows="8"
                                            class="w-full resize-y border border-krikkit-line bg-transparent px-4 py-3 text-sm text-krikkit-fg outline-none placeholder:text-krikkit-subtle focus:border-krikkit-muted"
                                        ><?php echo e($body); ?></textarea>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <button type="submit" class="inline-flex items-center gap-2 bg-accent px-6 py-3 text-sm font-medium text-accent-foreground transition hover:opacity-90">
                                        <?php echo e(__('home.Send message')); ?>

                                        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'chevron-right','class' => 'size-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right','class' => 'size-3.5']); ?>
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
                                    </button>
                                </form>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <?php echo $__env->make('home.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\contact\contact.blade.php ENDPATH**/ ?>