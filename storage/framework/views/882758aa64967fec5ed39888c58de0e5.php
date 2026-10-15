<section class="relative flex min-h-[calc(100dvh-3.5rem)] flex-col justify-center px-4 py-10 sm:px-6 lg:px-10">
    <div class="relative mx-auto w-full max-w-3xl text-center">
        <h1 class="text-3xl font-semibold tracking-tight text-krikkit-fg sm:text-4xl">
            <?php echo e(__('studio.What will you build today?')); ?>

        </h1>
        <p class="mt-3 text-sm text-krikkit-muted sm:text-base">
            <?php echo e(__('studio.Describe a site or app. Lab will write the files.')); ?>

        </p>

        <form
            class="mt-8 text-left"
            x-data="studioComposer"
            x-on:studio-seed-prompt.window="onSeed($event)"
            @submit.prevent="goToLab()"
        >
            <div class="rounded-2xl border border-krikkit-line bg-krikkit-surface p-3.5 sm:p-4">
                <label class="sr-only" for="studio-brief"><?php echo e(__('studio.Ask Lab to build…')); ?></label>
                <textarea
                    id="studio-brief"
                    rows="3"
                    x-model="brief"
                    placeholder="<?php echo e(__('studio.Ask Lab to build…')); ?>"
                    class="min-h-[72px] w-full resize-none bg-transparent text-sm leading-relaxed text-krikkit-fg outline-none placeholder:text-krikkit-subtle"
                    x-on:keydown.enter="if (! $event.shiftKey) { $event.preventDefault(); goToLab() }"
                ></textarea>

                <div class="mt-2 space-y-2" x-show="files.length > 0" x-cloak>
                    <div class="flex max-w-full flex-wrap gap-2">
                        <template x-for="row in files" :key="row.id">
                            <div class="group relative h-20 w-20 shrink-0 overflow-hidden rounded-xl border border-krikkit-line bg-krikkit-soft">
                                <img
                                    x-show="row.image"
                                    :src="row.url"
                                    :alt="row.name"
                                    class="h-full w-full object-cover"
                                >
                                <div x-show="! row.image" class="flex h-full w-full flex-col items-center justify-between px-1.5 py-1.5">
                                    <p class="w-full truncate px-0.5 text-center text-[10px] font-medium leading-tight text-krikkit-fg" x-text="row.name" :title="row.name"></p>
                                    <p class="text-[9px] leading-none text-krikkit-muted" x-text="row.bytes"></p>
                                </div>
                                <button
                                    type="button"
                                    class="absolute top-1 right-1 inline-flex h-5 w-5 items-center justify-center rounded-md bg-krikkit-surface text-krikkit-fg"
                                    @click="removeFile(row.id)"
                                    :aria-label="'<?php echo e(__('studio.Remove file')); ?> ' + row.name"
                                >
                                    <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'x','class' => 'size-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'size-3']); ?>
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
                            </div>
                        </template>
                    </div>
                </div>

                <input
                    type="file"
                    class="hidden"
                    multiple
                    x-ref="fileInput"
                    :accept="accept"
                    @change="onFilesSelected($event)"
                >

                <div class="mt-2 flex items-center justify-between gap-3">
                    <?php echo $__env->make('livewire.studio.partials.composerPlus', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['type' => 'submit','size' => 'sm','square' => true,'ariaLabel' => ''.e(__('studio.Open Lab')).'','navigate' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','size' => 'sm','square' => true,'aria-label' => ''.e(__('studio.Open Lab')).'','navigate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
                        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'arrow-up','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-up','class' => 'size-4']); ?>
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
        </form>

        <div id="studio-seed-chips" wire:ignore>
            
            <div class="mt-6 flex w-full justify-center">
                <div class="flex w-max max-w-full flex-nowrap items-center gap-2 overflow-hidden">
                    <span class="inline-flex h-8 shrink-0 items-center gap-2 rounded-full border border-krikkit-line px-3.5">
                        <span class="size-3.5 animate-pulse rounded bg-krikkit-soft" aria-hidden></span>
                        <span class="h-2.5 w-14 animate-pulse rounded-full bg-krikkit-soft" aria-hidden></span>
                    </span>
                    <span class="inline-flex h-8 shrink-0 items-center gap-2 rounded-full border border-krikkit-line px-3.5">
                        <span class="size-3.5 animate-pulse rounded bg-krikkit-soft" aria-hidden></span>
                        <span class="h-2.5 w-16 animate-pulse rounded-full bg-krikkit-soft" aria-hidden></span>
                    </span>
                    <span class="inline-flex h-8 shrink-0 items-center gap-2 rounded-full border border-krikkit-line px-3.5">
                        <span class="size-3.5 animate-pulse rounded bg-krikkit-soft" aria-hidden></span>
                        <span class="h-2.5 w-12 animate-pulse rounded-full bg-krikkit-soft" aria-hidden></span>
                    </span>
                    <span class="inline-flex h-8 shrink-0 items-center gap-2 rounded-full border border-krikkit-line px-3.5">
                        <span class="size-3.5 animate-pulse rounded bg-krikkit-soft" aria-hidden></span>
                        <span class="h-2.5 w-14 animate-pulse rounded-full bg-krikkit-soft" aria-hidden></span>
                    </span>
                    <span class="inline-flex h-8 w-8 shrink-0 animate-pulse rounded-full border border-krikkit-line bg-krikkit-soft" aria-hidden></span>
                </div>
            </div>
        </div>

        <p class="mt-5">
            <a
                href="<?php echo e(route('lab', ['import' => 'github'])); ?>"
                data-no-navigate
                class="inline-flex items-center gap-1.5 text-xs font-medium text-krikkit-muted transition hover:text-krikkit-fg"
            >
                <?php echo e(__('studio.or import from GitHub')); ?>

                <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'github','class' => 'size-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'github','class' => 'size-3.5']); ?>
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
            </a>
        </p>
    </div>
</section>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\studio\partials\hero.blade.php ENDPATH**/ ?>