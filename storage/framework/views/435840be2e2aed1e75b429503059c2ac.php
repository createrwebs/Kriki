<?php
    use App\Finance\FinanceCopy;
    use App\Support\Workspace\WorkspacePulse;

    $pulsePanels = collect([$onlineHref, $turnsHref, $invoicesHref])->filter()->count();
    $cardCount = count($cards);
?>

<?php if (isset($component)) { $__componentOriginal1a6cca1fb3b05e19b47840b98800a235 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1a6cca1fb3b05e19b47840b98800a235 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.dashboard','data' => ['title' => __('dashboard.Overview'),'breadcrumbs' => [['label' => __('dashboard.Overview'), 'current' => true]],'wide' => true,'skeleton' => 'page']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.dashboard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('dashboard.Overview')),'breadcrumbs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => __('dashboard.Overview'), 'current' => true]]),'wide' => true,'skeleton' => 'page']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="space-y-8">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="min-w-0">
                <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.Overview')); ?></p>
                <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-krikkit-fg"><?php echo e($hello); ?></h1>
                <p class="mt-1.5 max-w-lg text-sm leading-relaxed text-krikkit-muted">
                    <?php echo e($workspace
                        ? __('dashboard.What moved across people, Lab chat, and billing.')
                        : __('dashboard.Your Lab, pack, and sign-in in one place.')); ?>

                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['href' => ''.e(route('lab')).'','navigate' => false,'size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('lab')).'','navigate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'size' => 'sm']); ?>
                    <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'lab','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'lab','class' => 'size-4']); ?>
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
                    <?php echo e(__('dashboard.Open Lab')); ?>

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
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($peopleHref): ?>
                    <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['href' => ''.e($peopleHref).'','variant' => 'outline','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e($peopleHref).'','variant' => 'outline','size' => 'sm']); ?>
                        <?php echo e(__('dashboard.People')); ?>

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
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cards !== []): ?>
            <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                'grid gap-3 sm:grid-cols-2',
                'xl:grid-cols-3' => $cardCount !== 4,
                'xl:grid-cols-4' => $cardCount === 4,
            ]); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($card['href'])): ?>
                        <a href="<?php echo e($card['href']); ?>" <?php if(! str_starts_with($card['href'], route('lab'))): ?> wire:navigate <?php endif; ?> class="rounded-xl bg-krikkit-surface p-5 transition hover:bg-krikkit-soft">
                    <?php else: ?>
                        <div class="rounded-xl bg-krikkit-surface p-5">
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e($card['label']); ?></p>
                        <p class="mt-3 truncate text-2xl font-semibold tabular-nums tracking-tight text-krikkit-fg"><?php echo e($card['value']); ?></p>
                        <p class="mt-1.5 text-[11px] text-krikkit-muted"><?php echo e($card['hint']); ?></p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($card['href'])): ?>
                        </a>
                    <?php else: ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="grid gap-3 lg:grid-cols-2">
            <section class="rounded-xl bg-krikkit-surface p-4 sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-sm font-medium text-krikkit-fg"><?php echo e(__('dashboard.Latest chat')); ?></h2>
                    <div class="flex items-center gap-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($attentionCount > 0): ?>
                            <a href="<?php echo e(route('dashboard.lab.turns')); ?>" wire:navigate class="text-[11px] font-medium text-red-600 hover:underline dark:text-red-400">
                                <?php echo e(__('dashboard.:count need a look', ['count' => $attentionCount])); ?>

                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <a
                            href="<?php echo e($chatHref); ?>"
                            <?php if($workspace): ?> wire:navigate <?php endif; ?>
                            class="text-[11px] text-krikkit-muted hover:text-krikkit-fg"
                        ><?php echo e(__('dashboard.View all')); ?></a>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($chats->isEmpty()): ?>
                    <p class="py-10 text-sm text-krikkit-muted"><?php echo e(__('dashboard.No Lab chat yet.')); ?></p>
                <?php else: ?>
                    <div class="mt-4 space-y-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $chats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php
                                $project = $message->project;
                                $fromLab = $message->role === 'assistant';
                                $href = $project
                                    ? ($workspace ? route('dashboard.lab.show', $project) : route('lab.show', $project))
                                    : $chatHref;
                            ?>
                            <a
                                href="<?php echo e($href); ?>"
                                <?php if($workspace): ?> wire:navigate <?php endif; ?>
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'flex gap-3 rounded-xl p-3 transition',
                                    'bg-krikkit-soft' => $fromLab,
                                    'hover:bg-krikkit-soft' => ! $fromLab,
                                ]); ?>"
                            >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fromLab): ?>
                                    <span class="mt-0.5 inline-flex size-7 shrink-0 items-center justify-center rounded-full bg-accent/15 text-accent-content">
                                        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'lab','class' => 'size-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'lab','class' => 'size-3.5']); ?>
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
                                <?php else: ?>
                                    <?php if (isset($component)) { $__componentOriginal11129def8fa621557df03772d50fb352 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal11129def8fa621557df03772d50fb352 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::avatar','data' => ['name' => $project?->user?->name,'src' => $project?->user?->avatarUrl(),'size' => 'xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($project?->user?->name),'src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($project?->user?->avatarUrl()),'size' => 'xs']); ?>
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
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-baseline justify-between gap-3">
                                        <p class="truncate text-xs font-medium text-krikkit-fg">
                                            <?php echo e(WorkspacePulse::speaker($message)); ?>

                                            <span class="font-normal text-krikkit-subtle"><?php echo e($project?->title ?: __('dashboard.Untitled')); ?></span>
                                        </p>
                                        <time class="shrink-0 text-[11px] text-krikkit-subtle"><?php echo e($message->created_at?->diffForHumans(short: true)); ?></time>
                                    </div>
                                    <p class="mt-1 text-sm leading-snug text-krikkit-fg-soft"><?php echo e(WorkspacePulse::snippet($message->content)); ?></p>
                                </div>
                            </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </section>

            <section class="rounded-xl bg-krikkit-surface p-4 sm:p-5">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($workspace): ?>
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="text-sm font-medium text-krikkit-fg"><?php echo e(__('dashboard.Latest people')); ?></h2>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($peopleHref): ?>
                            <a href="<?php echo e($peopleHref); ?>" wire:navigate class="text-[11px] text-krikkit-muted hover:text-krikkit-fg"><?php echo e(__('dashboard.View all')); ?></a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($people->isEmpty()): ?>
                        <p class="py-10 text-sm text-krikkit-muted"><?php echo e(__('dashboard.No people yet.')); ?></p>
                    <?php else: ?>
                        <ul class="mt-3 space-y-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $people; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $person): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <li>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()?->allows('users.revise')): ?>
                                        <a href="<?php echo e(route('dashboard.users.edit', $person)); ?>" wire:navigate class="flex items-center gap-3 rounded-xl px-2 py-2.5 transition hover:bg-krikkit-soft">
                                    <?php else: ?>
                                        <div class="flex items-center gap-3 rounded-xl px-2 py-2.5">
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if (isset($component)) { $__componentOriginal11129def8fa621557df03772d50fb352 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal11129def8fa621557df03772d50fb352 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::avatar','data' => ['name' => $person->name,'src' => $person->avatarUrl(),'size' => 'xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($person->name),'src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($person->avatarUrl()),'size' => 'xs']); ?>
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
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-xs font-medium text-krikkit-fg"><?php echo e($person->name); ?></p>
                                            <p class="truncate text-[11px] text-krikkit-muted"><?php echo e($person->email); ?></p>
                                        </div>
                                        <div class="shrink-0 text-right">
                                            <p class="text-[11px] text-krikkit-muted"><?php echo e($person->accessRole?->title ?: __('dashboard.Member')); ?></p>
                                            <p class="text-[11px] text-krikkit-subtle"><?php echo e($person->created_at?->diffForHumans(short: true)); ?></p>
                                        </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()?->allows('users.revise')): ?>
                                        </a>
                                    <?php else: ?>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </li>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </ul>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php else: ?>
                    <h2 class="text-sm font-medium text-krikkit-fg"><?php echo e(__('dashboard.Profile')); ?></h2>
                    <p class="mt-1 text-[11px] text-krikkit-muted"><?php echo e(__('dashboard.Name, password, and two-factor live under Profile.')); ?></p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['href' => ''.e(route('dashboard.profile.index')).'','variant' => 'outline','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('dashboard.profile.index')).'','variant' => 'outline','size' => 'sm']); ?>
                            <?php echo e(__('dashboard.Profile')); ?>

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
                        <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['href' => ''.e(route('dashboard.profile.password')).'','variant' => 'outline','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('dashboard.profile.password')).'','variant' => 'outline','size' => 'sm']); ?>
                            <?php echo e(__('dashboard.Password')); ?>

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
                        <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['href' => ''.e(route('dashboard.profile.two-factor')).'','variant' => 'outline','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('dashboard.profile.two-factor')).'','variant' => 'outline','size' => 'sm']); ?>
                            <?php echo e(__('dashboard.Two-factor')); ?>

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
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </section>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pulsePanels > 0): ?>
            <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                'grid gap-3',
                'lg:grid-cols-2' => $pulsePanels === 2,
                'lg:grid-cols-3' => $pulsePanels >= 3,
            ]); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($onlineHref): ?>
                    <section class="rounded-xl bg-krikkit-surface p-4 sm:p-5">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-sm font-medium text-krikkit-fg"><?php echo e(__('dashboard.Signed in')); ?></h2>
                            <a href="<?php echo e($onlineHref); ?>" wire:navigate class="text-[11px] text-krikkit-muted hover:text-krikkit-fg"><?php echo e(__('dashboard.View all')); ?></a>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($online->isEmpty()): ?>
                            <p class="py-10 text-sm text-krikkit-muted"><?php echo e(__('dashboard.Nobody is signed in.')); ?></p>
                        <?php else: ?>
                            <ul class="mt-3 space-y-1">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $online; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php $person = $session->user; ?>
                                    <li>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()?->allows('users.revise') && $person): ?>
                                            <a href="<?php echo e(route('dashboard.users.edit', $person)); ?>" wire:navigate class="flex items-center gap-3 rounded-xl px-2 py-2.5 transition hover:bg-krikkit-soft">
                                        <?php else: ?>
                                            <div class="flex items-center gap-3 rounded-xl px-2 py-2.5">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if (isset($component)) { $__componentOriginal11129def8fa621557df03772d50fb352 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal11129def8fa621557df03772d50fb352 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::avatar','data' => ['name' => $person?->name,'src' => $person?->avatarUrl(),'size' => 'xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($person?->name),'src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($person?->avatarUrl()),'size' => 'xs']); ?>
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
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-xs font-medium text-krikkit-fg"><?php echo e($person?->name); ?></p>
                                                <p class="truncate text-[11px] text-krikkit-muted"><?php echo e($person?->accessRole?->title ?: __('dashboard.Member')); ?></p>
                                            </div>
                                            <p class="shrink-0 text-[11px] text-krikkit-subtle"><?php echo e($session->lastActivityAt()->diffForHumans(short: true)); ?></p>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()?->allows('users.revise') && $person): ?>
                                            </a>
                                        <?php else: ?>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </li>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </ul>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </section>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($turnsHref): ?>
                    <section class="rounded-xl bg-krikkit-surface p-4 sm:p-5">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-sm font-medium text-krikkit-fg"><?php echo e(__('dashboard.Needs a look')); ?></h2>
                            <a href="<?php echo e($turnsHref); ?>" wire:navigate class="text-[11px] text-krikkit-muted hover:text-krikkit-fg"><?php echo e(__('dashboard.View all')); ?></a>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flagged->isEmpty()): ?>
                            <p class="py-10 text-sm text-krikkit-muted"><?php echo e(__('dashboard.All turns look fine.')); ?></p>
                        <?php else: ?>
                            <div class="mt-3 space-y-1">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $flagged; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php
                                        $message = $row['message'];
                                        $project = $message->project;
                                        $kind = $row['kind'];
                                        $status = \App\Lab\LabTurnHealth::statusLabel($message, $kind);
                                        $href = $project ? route('dashboard.lab.show', $project) : $turnsHref;
                                    ?>
                                    <a href="<?php echo e($href); ?>" wire:navigate class="flex items-start gap-3 rounded-xl px-2 py-2.5 transition hover:bg-krikkit-soft">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center justify-between gap-3">
                                                <p class="truncate text-xs font-medium text-krikkit-fg"><?php echo e($project?->title ?: __('dashboard.Untitled')); ?></p>
                                                <?php if (isset($component)) { $__componentOriginal0bc981f99055f90423de9ceafd4f5f18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::badge','data' => ['size' => 'xs','color' => $kind === 'stuck' ? 'amber' : 'red']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'xs','color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($kind === 'stuck' ? 'amber' : 'red')]); ?><?php echo e($status); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $attributes = $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $component = $__componentOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
                                            </div>
                                            <p class="mt-1 truncate text-[11px] text-krikkit-muted"><?php echo e($project?->user?->name ?: __('dashboard.Unassigned')); ?> · <?php echo e($message->updated_at?->diffForHumans(short: true)); ?></p>
                                        </div>
                                    </a>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </section>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoicesHref): ?>
                    <section class="rounded-xl bg-krikkit-surface p-4 sm:p-5">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-sm font-medium text-krikkit-fg"><?php echo e(__('dashboard.Recent invoices')); ?></h2>
                            <a href="<?php echo e($invoicesHref); ?>" wire:navigate class="text-[11px] text-krikkit-muted hover:text-krikkit-fg"><?php echo e(__('dashboard.View all')); ?></a>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoices->isEmpty()): ?>
                            <p class="py-10 text-sm text-krikkit-muted"><?php echo e(__('dashboard.No invoices yet.')); ?></p>
                        <?php else: ?>
                            <ul class="mt-3 space-y-1">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php $tone = FinanceCopy::invoice($invoice->status); ?>
                                    <li>
                                        <a href="<?php echo e(route('dashboard.invoices.show', $invoice)); ?>" wire:navigate class="flex items-center gap-3 rounded-xl px-2 py-2.5 transition hover:bg-krikkit-soft">
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-xs font-medium tabular-nums text-krikkit-fg"><?php echo e($invoice->formattedAmount()); ?></p>
                                                <p class="truncate text-[11px] text-krikkit-muted"><?php echo e($invoice->user?->name ?: __('dashboard.Unlinked')); ?> · <?php echo e($invoice->plan?->title ?: FinanceCopy::driver($invoice->driver)); ?></p>
                                            </div>
                                            <?php if (isset($component)) { $__componentOriginal0bc981f99055f90423de9ceafd4f5f18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::badge','data' => ['size' => 'xs','color' => $tone['color']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'xs','color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tone['color'])]); ?><?php echo e($tone['label']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $attributes = $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $component = $__componentOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
                                        </a>
                                    </li>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </ul>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </section>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="grid gap-3 lg:grid-cols-2">
            <section class="rounded-xl bg-krikkit-surface p-4 sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-sm font-medium text-krikkit-fg">
                        <?php echo e($workspace ? __('dashboard.Recent projects') : __('dashboard.Your projects')); ?>

                    </h2>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($projectsHref): ?>
                        <a href="<?php echo e($projectsHref); ?>" wire:navigate class="text-[11px] text-krikkit-muted hover:text-krikkit-fg"><?php echo e(__('dashboard.View all')); ?></a>
                    <?php elseif(! $workspace): ?>
                        <a href="<?php echo e(route('lab')); ?>" class="inline-flex items-center gap-1.5 text-[11px] text-krikkit-muted hover:text-krikkit-fg">
                            <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'lab','class' => 'size-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'lab','class' => 'size-3.5']); ?>
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
                            <?php echo e(__('dashboard.Open Lab')); ?>

                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($projects->isEmpty()): ?>
                    <p class="py-10 text-sm text-krikkit-muted">
                        <?php echo e($workspace ? __('dashboard.No Lab projects yet.') : __('dashboard.No projects yet. Open Lab to start one.')); ?>

                    </p>
                <?php else: ?>
                    <ul class="mt-3 space-y-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <li>
                                <a
                                    href="<?php echo e($workspace ? route('dashboard.lab.show', $project) : route('lab.show', $project)); ?>"
                                    <?php if($workspace): ?> wire:navigate <?php endif; ?>
                                    class="flex items-center gap-3 rounded-xl px-2 py-2.5 transition hover:bg-krikkit-soft"
                                >
                                    <?php if (isset($component)) { $__componentOriginal11129def8fa621557df03772d50fb352 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal11129def8fa621557df03772d50fb352 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::avatar','data' => ['name' => $project->user?->name,'src' => $project->user?->avatarUrl(),'size' => 'xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($project->user?->name),'src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($project->user?->avatarUrl()),'size' => 'xs']); ?>
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
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-xs font-medium text-krikkit-fg"><?php echo e($project->title ?: __('dashboard.Untitled')); ?></p>
                                        <p class="truncate text-[11px] text-krikkit-muted">
                                            <?php echo e($project->user?->name ?: __('dashboard.Unassigned')); ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->isFrozen()): ?>
                                                · <?php echo e(__('dashboard.Frozen')); ?>

                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </p>
                                    </div>
                                    <p class="shrink-0 text-[11px] text-krikkit-subtle"><?php echo e($project->updated_at?->diffForHumans(short: true)); ?></p>
                                </a>
                            </li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </ul>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </section>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subscriptionsHref): ?>
                <section class="rounded-xl bg-krikkit-surface p-4 sm:p-5">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="text-sm font-medium text-krikkit-fg"><?php echo e(__('dashboard.Subscriptions')); ?></h2>
                        <a href="<?php echo e($subscriptionsHref); ?>" wire:navigate class="text-[11px] text-krikkit-muted hover:text-krikkit-fg"><?php echo e(__('dashboard.View all')); ?></a>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subscriptions->isEmpty()): ?>
                        <p class="py-10 text-sm text-krikkit-muted"><?php echo e(__('dashboard.No subscriptions yet.')); ?></p>
                    <?php else: ?>
                        <ul class="mt-3 space-y-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <?php $tone = FinanceCopy::subscription($row->status); ?>
                                <li>
                                    <a href="<?php echo e(route('dashboard.finance.subscriptions.show', $row)); ?>" wire:navigate class="flex items-center gap-3 rounded-xl px-2 py-2.5 transition hover:bg-krikkit-soft">
                                        <?php if (isset($component)) { $__componentOriginal11129def8fa621557df03772d50fb352 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal11129def8fa621557df03772d50fb352 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::avatar','data' => ['name' => $row->user?->name,'src' => $row->user?->avatarUrl(),'size' => 'xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($row->user?->name),'src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($row->user?->avatarUrl()),'size' => 'xs']); ?>
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
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-xs font-medium text-krikkit-fg"><?php echo e($row->user?->name ?: __('dashboard.Unlinked')); ?></p>
                                            <p class="truncate text-[11px] text-krikkit-muted"><?php echo e($row->plan?->title ?: '—'); ?> · <?php echo e(FinanceCopy::interval($row->interval)); ?></p>
                                        </div>
                                        <?php if (isset($component)) { $__componentOriginal0bc981f99055f90423de9ceafd4f5f18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::badge','data' => ['size' => 'xs','color' => $tone['color']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'xs','color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tone['color'])]); ?><?php echo e($tone['label']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $attributes = $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $component = $__componentOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
                                    </a>
                                </li>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </ul>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </section>
            <?php elseif($ownPack): ?>
                <section class="rounded-xl bg-krikkit-surface p-4 sm:p-5">
                    <h2 class="text-sm font-medium text-krikkit-fg"><?php echo e(__('dashboard.Your pack')); ?></h2>
                    <p class="mt-1 text-[11px] text-krikkit-muted"><?php echo e(__('dashboard.Your current plan.')); ?></p>
                    <div class="mt-4 space-y-3">
                        <div class="rounded-xl bg-krikkit-soft px-3 py-3">
                            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.Pack')); ?></p>
                            <p class="mt-1.5 truncate text-sm font-medium text-krikkit-fg"><?php echo e($ownPack['title']); ?></p>
                        </div>
                        <div class="rounded-xl bg-krikkit-soft px-3 py-3">
                            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.This period')); ?></p>
                            <p class="mt-1.5 truncate text-sm font-medium tabular-nums text-krikkit-fg"><?php echo e($ownPack['remaining']); ?></p>
                            <p class="mt-1 text-[11px] text-krikkit-muted"><?php echo e($ownPack['hint']); ?></p>
                        </div>
                        <div class="rounded-xl bg-krikkit-soft px-3 py-3">
                            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.Two-factor')); ?></p>
                            <p class="mt-1.5 text-sm font-medium text-krikkit-fg"><?php echo e($ownPack['twoFactor']); ?></p>
                        </div>
                    </div>
                </section>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1a6cca1fb3b05e19b47840b98800a235)): ?>
<?php $attributes = $__attributesOriginal1a6cca1fb3b05e19b47840b98800a235; ?>
<?php unset($__attributesOriginal1a6cca1fb3b05e19b47840b98800a235); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1a6cca1fb3b05e19b47840b98800a235)): ?>
<?php $component = $__componentOriginal1a6cca1fb3b05e19b47840b98800a235; ?>
<?php unset($__componentOriginal1a6cca1fb3b05e19b47840b98800a235); ?>
<?php endif; ?>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\dashboard\home\home.blade.php ENDPATH**/ ?>