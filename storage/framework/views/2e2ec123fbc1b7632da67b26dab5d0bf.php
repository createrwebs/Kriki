<?php
    $avatarName = $username !== '' ? $username : __('dashboard.User');
    $avatarSrc = $avatar
        ? $avatar->temporaryUrl()
        : ($removeAvatar ? null : $avatarPreviewUrl);
    $hasAvatar = filled($avatarSrc);
?>

<?php if (isset($component)) { $__componentOriginala6f7a42ad28cb9611c11029d9afb33b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala6f7a42ad28cb9611c11029d9afb33b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.settings.frame','data' => ['section' => 'profile']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('settings.frame'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['section' => 'profile']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="space-y-8">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-krikkit-fg"><?php echo e(__('settings.Profile')); ?></h1>
            <p class="mt-1 text-sm text-krikkit-muted"><?php echo e(__('settings.Your name, photo, password, and two-factor.')); ?></p>
        </div>

        <?php echo $__env->make('livewire.settings.profile.options.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <form wire:submit="save" class="space-y-8">
            <?php if (isset($component)) { $__componentOriginald99998d64b0875088b034c3416ebc71a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald99998d64b0875088b034c3416ebc71a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => ['class' => 'space-y-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-4']); ?>
                <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-krikkit-subtle"><?php echo e(__('dashboard.Photo')); ?></p>
                <div class="relative inline-flex">
                    <label class="group relative cursor-pointer">
                        <?php if (isset($component)) { $__componentOriginal11129def8fa621557df03772d50fb352 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal11129def8fa621557df03772d50fb352 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::avatar','data' => ['name' => $avatarName,'src' => $avatarSrc,'size' => 'xl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($avatarName),'src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($avatarSrc),'size' => 'xl']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal11129def8fa621557df03772d50fb352)): ?>
<?php $attributes = $__attributesOriginal11129def8fa621557df03772d50fb352; ?>
<?php unset($__attributesOriginal11129def8fa621557df03772d50fb352); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11129def8fa621557df03772d50fb352)): ?>
<?php $component = $__componentOriginal11129def8fa621557df03772d50fb352; ?>
<?php unset($__componentOriginal11129def8fa621557df03772d50fb352); ?>
<?php endif; ?>
                        <span class="pointer-events-none absolute inset-0 flex items-center justify-center rounded-full bg-black/45 text-white opacity-0 transition group-hover:opacity-100" aria-hidden="true">
                            <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'arrow-up-tray','class' => 'size-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-up-tray','class' => 'size-6']); ?>
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
                        </span>
                        <span class="sr-only"><?php echo e(__('dashboard.Upload photo')); ?></span>
                        <input type="file" class="sr-only" accept="image/*" wire:model="avatar">
                    </label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasAvatar): ?>
                        <button
                            type="button"
                            wire:click="clearAvatar"
                            class="absolute -right-0.5 -top-0.5 inline-flex size-6 items-center justify-center rounded-full border border-krikkit-line/50 bg-krikkit-canvas text-krikkit-fg transition hover:bg-krikkit-soft"
                            aria-label="<?php echo e(__('dashboard.Remove photo')); ?>"
                        >
                            <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'x-mark','class' => 'size-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x-mark','class' => 'size-3.5']); ?>
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
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <?php if (isset($component)) { $__componentOriginal84c2540c43521235b601ee612603c1c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal84c2540c43521235b601ee612603c1c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field.error','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e($message); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $attributes = $__attributesOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__attributesOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $component = $__componentOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__componentOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald99998d64b0875088b034c3416ebc71a)): ?>
<?php $attributes = $__attributesOriginald99998d64b0875088b034c3416ebc71a; ?>
<?php unset($__attributesOriginald99998d64b0875088b034c3416ebc71a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald99998d64b0875088b034c3416ebc71a)): ?>
<?php $component = $__componentOriginald99998d64b0875088b034c3416ebc71a; ?>
<?php unset($__componentOriginald99998d64b0875088b034c3416ebc71a); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginald99998d64b0875088b034c3416ebc71a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald99998d64b0875088b034c3416ebc71a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => ['class' => 'space-y-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-4']); ?>
                <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-krikkit-subtle"><?php echo e(__('dashboard.Identity')); ?></p>
                <p class="text-xs text-krikkit-muted"><?php echo e($roleTitle); ?></p>
                <?php if (isset($component)) { $__componentOriginal33ee6131e877bec48f910ca20ac959e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33ee6131e877bec48f910ca20ac959e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => __('dashboard.Username')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Username'))]); ?>
                    <?php if (isset($component)) { $__componentOriginal79c913eebc08aa2f1af8927c57771386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79c913eebc08aa2f1af8927c57771386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::input','data' => ['size' => 'md','wire:model' => 'username','autocomplete' => 'username','invalid' => $errors->has('username')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','wire:model' => 'username','autocomplete' => 'username','invalid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->has('username'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal79c913eebc08aa2f1af8927c57771386)): ?>
<?php $attributes = $__attributesOriginal79c913eebc08aa2f1af8927c57771386; ?>
<?php unset($__attributesOriginal79c913eebc08aa2f1af8927c57771386); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal79c913eebc08aa2f1af8927c57771386)): ?>
<?php $component = $__componentOriginal79c913eebc08aa2f1af8927c57771386; ?>
<?php unset($__componentOriginal79c913eebc08aa2f1af8927c57771386); ?>
<?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <?php if (isset($component)) { $__componentOriginal84c2540c43521235b601ee612603c1c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal84c2540c43521235b601ee612603c1c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field.error','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e($message); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $attributes = $__attributesOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__attributesOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $component = $__componentOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__componentOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $attributes = $__attributesOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $component = $__componentOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__componentOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
                <div class="grid gap-4 sm:grid-cols-2">
                    <?php if (isset($component)) { $__componentOriginal33ee6131e877bec48f910ca20ac959e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33ee6131e877bec48f910ca20ac959e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => __('dashboard.Email')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Email'))]); ?>
                        <?php if (isset($component)) { $__componentOriginal79c913eebc08aa2f1af8927c57771386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79c913eebc08aa2f1af8927c57771386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::input','data' => ['size' => 'md','wire:model' => 'email','type' => 'email','autocomplete' => 'email','invalid' => $errors->has('email')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','wire:model' => 'email','type' => 'email','autocomplete' => 'email','invalid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->has('email'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal79c913eebc08aa2f1af8927c57771386)): ?>
<?php $attributes = $__attributesOriginal79c913eebc08aa2f1af8927c57771386; ?>
<?php unset($__attributesOriginal79c913eebc08aa2f1af8927c57771386); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal79c913eebc08aa2f1af8927c57771386)): ?>
<?php $component = $__componentOriginal79c913eebc08aa2f1af8927c57771386; ?>
<?php unset($__componentOriginal79c913eebc08aa2f1af8927c57771386); ?>
<?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <?php if (isset($component)) { $__componentOriginal84c2540c43521235b601ee612603c1c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal84c2540c43521235b601ee612603c1c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field.error','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e($message); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $attributes = $__attributesOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__attributesOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $component = $__componentOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__componentOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $attributes = $__attributesOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $component = $__componentOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__componentOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal33ee6131e877bec48f910ca20ac959e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33ee6131e877bec48f910ca20ac959e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => __('dashboard.Phone')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Phone'))]); ?>
                        <?php if (isset($component)) { $__componentOriginal79c913eebc08aa2f1af8927c57771386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79c913eebc08aa2f1af8927c57771386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::input','data' => ['size' => 'md','wire:model' => 'phone','type' => 'tel','autocomplete' => 'tel','invalid' => $errors->has('phone')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','wire:model' => 'phone','type' => 'tel','autocomplete' => 'tel','invalid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->has('phone'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal79c913eebc08aa2f1af8927c57771386)): ?>
<?php $attributes = $__attributesOriginal79c913eebc08aa2f1af8927c57771386; ?>
<?php unset($__attributesOriginal79c913eebc08aa2f1af8927c57771386); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal79c913eebc08aa2f1af8927c57771386)): ?>
<?php $component = $__componentOriginal79c913eebc08aa2f1af8927c57771386; ?>
<?php unset($__componentOriginal79c913eebc08aa2f1af8927c57771386); ?>
<?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <?php if (isset($component)) { $__componentOriginal84c2540c43521235b601ee612603c1c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal84c2540c43521235b601ee612603c1c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field.error','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e($message); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $attributes = $__attributesOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__attributesOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $component = $__componentOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__componentOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $attributes = $__attributesOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $component = $__componentOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__componentOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
                </div>
                <?php if (isset($component)) { $__componentOriginal33ee6131e877bec48f910ca20ac959e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33ee6131e877bec48f910ca20ac959e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field','data' => ['label' => __('dashboard.Country')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Country'))]); ?>
                    <?php if (isset($component)) { $__componentOriginal7a952551f265692c915c9af677c5ecd6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7a952551f265692c915c9af677c5ecd6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select','data' => ['size' => 'md','wire:model' => 'country','value' => $country,'searchable' => true,'placeholder' => ''.e(__('dashboard.Choose…')).'','invalid' => $errors->has('country')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','wire:model' => 'country','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($country),'searchable' => true,'placeholder' => ''.e(__('dashboard.Choose…')).'','invalid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->has('country'))]); ?>
                        <?php if (isset($component)) { $__componentOriginalbc5c56c7c0bb48775665c8519b14d96e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select.option','data' => ['value' => '','selected' => $country === '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select.option'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => '','selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($country === '')]); ?><?php echo e(__('dashboard.Choose…')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e)): ?>
<?php $attributes = $__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e; ?>
<?php unset($__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc5c56c7c0bb48775665c8519b14d96e)): ?>
<?php $component = $__componentOriginalbc5c56c7c0bb48775665c8519b14d96e; ?>
<?php unset($__componentOriginalbc5c56c7c0bb48775665c8519b14d96e); ?>
<?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalbc5c56c7c0bb48775665c8519b14d96e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::select.option','data' => ['value' => $code,'selected' => $country === $code]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::select.option'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($code),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($country === $code)]); ?><?php echo e($label); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e)): ?>
<?php $attributes = $__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e; ?>
<?php unset($__attributesOriginalbc5c56c7c0bb48775665c8519b14d96e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc5c56c7c0bb48775665c8519b14d96e)): ?>
<?php $component = $__componentOriginalbc5c56c7c0bb48775665c8519b14d96e; ?>
<?php unset($__componentOriginalbc5c56c7c0bb48775665c8519b14d96e); ?>
<?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7a952551f265692c915c9af677c5ecd6)): ?>
<?php $attributes = $__attributesOriginal7a952551f265692c915c9af677c5ecd6; ?>
<?php unset($__attributesOriginal7a952551f265692c915c9af677c5ecd6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7a952551f265692c915c9af677c5ecd6)): ?>
<?php $component = $__componentOriginal7a952551f265692c915c9af677c5ecd6; ?>
<?php unset($__componentOriginal7a952551f265692c915c9af677c5ecd6); ?>
<?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <?php if (isset($component)) { $__componentOriginal84c2540c43521235b601ee612603c1c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal84c2540c43521235b601ee612603c1c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::field.error','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::field.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e($message); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $attributes = $__attributesOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__attributesOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal84c2540c43521235b601ee612603c1c4)): ?>
<?php $component = $__componentOriginal84c2540c43521235b601ee612603c1c4; ?>
<?php unset($__componentOriginal84c2540c43521235b601ee612603c1c4); ?>
<?php endif; ?>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $attributes = $__attributesOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__attributesOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal33ee6131e877bec48f910ca20ac959e1)): ?>
<?php $component = $__componentOriginal33ee6131e877bec48f910ca20ac959e1; ?>
<?php unset($__componentOriginal33ee6131e877bec48f910ca20ac959e1); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald99998d64b0875088b034c3416ebc71a)): ?>
<?php $attributes = $__attributesOriginald99998d64b0875088b034c3416ebc71a; ?>
<?php unset($__attributesOriginald99998d64b0875088b034c3416ebc71a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald99998d64b0875088b034c3416ebc71a)): ?>
<?php $component = $__componentOriginald99998d64b0875088b034c3416ebc71a; ?>
<?php unset($__componentOriginald99998d64b0875088b034c3416ebc71a); ?>
<?php endif; ?>

            <div class="flex justify-end">
                <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit']); ?><?php echo e(__('dashboard.Save changes')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $attributes = $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__attributesOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c)): ?>
<?php $component = $__componentOriginal7fec36a6f23868b32bea9c1cbede699c; ?>
<?php unset($__componentOriginal7fec36a6f23868b32bea9c1cbede699c); ?>
<?php endif; ?>
            </div>
        </form>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala6f7a42ad28cb9611c11029d9afb33b9)): ?>
<?php $attributes = $__attributesOriginala6f7a42ad28cb9611c11029d9afb33b9; ?>
<?php unset($__attributesOriginala6f7a42ad28cb9611c11029d9afb33b9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala6f7a42ad28cb9611c11029d9afb33b9)): ?>
<?php $component = $__componentOriginala6f7a42ad28cb9611c11029d9afb33b9; ?>
<?php unset($__componentOriginala6f7a42ad28cb9611c11029d9afb33b9); ?>
<?php endif; ?>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\settings\profile\profile.blade.php ENDPATH**/ ?>