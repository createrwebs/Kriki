<div
    class="space-y-8"
    x-data="krikkitSettingsTheme({
        accents: <?php echo \Illuminate\Support\Js::from($accents)->toHtml() ?>,
        bases: <?php echo \Illuminate\Support\Js::from($bases)->toHtml() ?>,
        saved: <?php echo \Illuminate\Support\Js::from(['accent' => $accent, 'base' => $base, 'mode' => $mode])->toHtml() ?>,
    })"
    x-init="sync()"
>
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-krikkit-fg"><?php echo e(__('dashboard.Settings')); ?></h1>
        <p class="mt-0.5 text-xs text-krikkit-muted">
            <?php echo e(__('dashboard.Default look for guests and new accounts.')); ?>

        </p>
    </div>

    <?php echo $__env->make('livewire.dashboard.settings.options.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="space-y-10">
        <?php if (isset($component)) { $__componentOriginald99998d64b0875088b034c3416ebc71a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald99998d64b0875088b034c3416ebc71a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => ['padding' => false,'class' => 'space-y-5 !border-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'class' => 'space-y-5 !border-0']); ?>
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.Accent')); ?></p>
            <div class="flex flex-wrap gap-2">
                <template x-for="item in accents" :key="item.id">
                    <button
                        type="button"
                        class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-sm transition"
                        :class="accent === item.id
                            ? 'border-krikkit-fg bg-krikkit-soft text-krikkit-fg'
                            : 'border-krikkit-line text-krikkit-muted hover:border-krikkit-muted hover:text-krikkit-fg'"
                        @click="setAccent(item.id)"
                    >
                        <span class="size-3.5 shrink-0 rounded-full ring-1 ring-black/10 dark:ring-white/15" :style="accentSwatch(item.id)"></span>
                        <span class="font-medium" x-text="item.label"></span>
                    </button>
                </template>
            </div>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => ['padding' => false,'class' => 'space-y-4 !border-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'class' => 'space-y-4 !border-0']); ?>
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.Base')); ?></p>
            <div class="flex flex-wrap items-center gap-x-0 gap-y-3">
                <template x-for="(item, index) in bases" :key="item.id">
                    <div class="flex items-center">
                        <button
                            type="button"
                            class="relative size-9 rounded-lg border-2 transition"
                            :class="base === item.id
                                ? 'border-krikkit-fg'
                                : 'border-transparent hover:border-krikkit-line'"
                            :title="item.label"
                            :aria-label="item.label"
                            @click="setBase(item.id)"
                        >
                            <span
                                class="absolute inset-1 rounded-md ring-1 ring-black/10 dark:ring-white/15"
                                :style="baseSwatch(item.id)"
                            ></span>
                        </button>
                        <span
                            x-show="index < bases.length - 1"
                            class="mx-1 hidden h-px w-3 bg-krikkit-line sm:block"
                            aria-hidden="true"
                        ></span>
                    </div>
                </template>
            </div>
            <p class="text-xs text-krikkit-muted">
                <?php echo e(__('dashboard.Base ·')); ?> <span class="font-medium text-krikkit-fg-soft" x-text="baseLabel"></span>
            </p>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => ['padding' => false,'class' => 'space-y-4 !border-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'class' => 'space-y-4 !border-0']); ?>
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.Mode')); ?></p>
            <div class="inline-flex rounded-full border border-krikkit-line p-1">
                <button
                    type="button"
                    class="rounded-full px-3.5 py-1.5 text-sm font-medium transition"
                    :class="mode === 'light' ? 'bg-krikkit-soft text-krikkit-fg' : 'text-krikkit-muted hover:text-krikkit-fg'"
                    @click="setMode('light')"
                >
                    <?php echo e(__('dashboard.Light')); ?>

                </button>
                <button
                    type="button"
                    class="rounded-full px-3.5 py-1.5 text-sm font-medium transition"
                    :class="mode === 'dark' ? 'bg-krikkit-soft text-krikkit-fg' : 'text-krikkit-muted hover:text-krikkit-fg'"
                    @click="setMode('dark')"
                >
                    <?php echo e(__('dashboard.Dark')); ?>

                </button>
                <button
                    type="button"
                    class="rounded-full px-3.5 py-1.5 text-sm font-medium transition"
                    :class="mode === 'system' ? 'bg-krikkit-soft text-krikkit-fg' : 'text-krikkit-muted hover:text-krikkit-fg'"
                    @click="setMode('system')"
                >
                    <?php echo e(__('dashboard.System')); ?>

                </button>
            </div>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => ['padding' => false,'class' => 'space-y-2 !border-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'class' => 'space-y-2 !border-0']); ?>
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.Members')); ?></p>
            <?php if (isset($component)) { $__componentOriginal865e544359d7296e2e13038907c989a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal865e544359d7296e2e13038907c989a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::switch','data' => ['align' => 'right','class' => 'w-full justify-between','label' => __('dashboard.Lock member themes'),'wire:model' => 'lockMembers']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::switch'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right','class' => 'w-full justify-between','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Lock member themes')),'wire:model' => 'lockMembers']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal865e544359d7296e2e13038907c989a8)): ?>
<?php $attributes = $__attributesOriginal865e544359d7296e2e13038907c989a8; ?>
<?php unset($__attributesOriginal865e544359d7296e2e13038907c989a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal865e544359d7296e2e13038907c989a8)): ?>
<?php $component = $__componentOriginal865e544359d7296e2e13038907c989a8; ?>
<?php unset($__componentOriginal865e544359d7296e2e13038907c989a8); ?>
<?php endif; ?>
            <p class="text-xs text-krikkit-muted"><?php echo e(__('dashboard.When locked, personal theme picks are ignored and everyone uses this default.')); ?></p>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => ['padding' => false,'class' => 'space-y-4 !border-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'class' => 'space-y-4 !border-0']); ?>
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.Preview')); ?></p>
            <?php echo $__env->make('livewire.dashboard.settings.options.preview', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['type' => 'button','wire:loading.attr' => 'disabled','@click' => 'saveTheme()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:loading.attr' => 'disabled','@click' => 'saveTheme()']); ?>
                <?php echo e(__('dashboard.Save changes')); ?>

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
        </div>
    </div>
</div>

    <?php
        $__scriptKey = '2339964489-0';
        ob_start();
    ?>
<script>
    Alpine.data('krikkitSettingsTheme', (config) => {
        const lightAccents = new Set(['yellow', 'lime', 'amber'])

        return {
            accents: config.accents,
            bases: config.bases,
            accent: config.saved?.accent || 'base',
            base: config.saved?.base || 'neutral',
            mode: config.saved?.mode || 'system',

            get baseLabel() {
                return this.bases.find((item) => item.id === this.base)?.label ?? this.base
            },

            accentSwatch(id) {
                if (id === 'base') {
                    return 'background-color: var(--color-neutral-800)'
                }

                const shade = lightAccents.has(id) ? '400' : '500'

                return `background-color: var(--color-${id}-${shade})`
            },

            baseSwatch(id) {
                return `background-color: var(--color-${id}-500)`
            },

            resolvedMode() {
                if (this.mode === 'light' || this.mode === 'dark') {
                    return this.mode
                }

                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
            },

            sync() {
                this.accent = config.saved?.accent || 'base'
                this.base = config.saved?.base || 'neutral'
                this.mode = config.saved?.mode || 'system'
                this.apply()
            },

            apply() {
                window.krikkitTheme?.apply?.({
                    mode: this.resolvedMode(),
                    accent: this.accent,
                    base: this.base,
                    persist: true,
                })
            },

            setAccent(id) {
                this.accent = id
                this.apply()
            },

            setBase(id) {
                this.base = id
                this.apply()
            },

            setMode(next) {
                this.mode = next
                this.apply()
            },

            saveTheme() {
                this.apply()
                this.$wire.save(this.accent, this.base, this.mode)
            },
        }
    })
</script>
    <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\dashboard\settings\options\themes.blade.php ENDPATH**/ ?>