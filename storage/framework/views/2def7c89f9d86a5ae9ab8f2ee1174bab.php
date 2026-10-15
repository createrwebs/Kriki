<?php
    use App\Finance\FinanceCopy;
    use App\Finance\Money;

    $seriesMax = max(1, collect($cards['series'])->max('value') ?: 1);
    $healthTotal = max(1, array_sum($cards['health']));
?>

<div class="space-y-8">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-lg font-semibold tracking-tight text-krikkit-fg"><?php echo e(__('dashboard.Finance')); ?></h1>
            <p class="mt-0.5 text-xs text-krikkit-muted"><?php echo e(__('dashboard.Revenue, collections, credit liability, and payment events.')); ?></p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <?php if (isset($component)) { $__componentOriginal768e8fc940702d540fb0480605850979 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal768e8fc940702d540fb0480605850979 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::dropdown','data' => ['align' => 'end']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'end']); ?>
                 <?php $__env->slot('trigger', null, []); ?> 
                    <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['type' => 'button','variant' => 'ghost','square' => true,'size' => 'sm','ariaLabel' => ''.e(__('dashboard.Export')).'','title' => ''.e(__('dashboard.Export')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'ghost','square' => true,'size' => 'sm','aria-label' => ''.e(__('dashboard.Export')).'','title' => ''.e(__('dashboard.Export')).'']); ?>
                        <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => 'arrow-down-tray','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-down-tray','class' => 'size-4']); ?>
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
                 <?php $__env->endSlot(); ?>
                 <?php $__env->slot('menu', null, []); ?> 
                    <?php if (isset($component)) { $__componentOriginal5dc871dce4923f548320c53d3ad5baf1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dc871dce4923f548320c53d3ad5baf1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::dropdown.item','data' => ['wire:click' => 'export(\'csv\')','xOn:click' => 'open = false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::dropdown.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'export(\'csv\')','x-on:click' => 'open = false']); ?>
                        <?php echo e(__('dashboard.CSV')); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dc871dce4923f548320c53d3ad5baf1)): ?>
<?php $attributes = $__attributesOriginal5dc871dce4923f548320c53d3ad5baf1; ?>
<?php unset($__attributesOriginal5dc871dce4923f548320c53d3ad5baf1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dc871dce4923f548320c53d3ad5baf1)): ?>
<?php $component = $__componentOriginal5dc871dce4923f548320c53d3ad5baf1; ?>
<?php unset($__componentOriginal5dc871dce4923f548320c53d3ad5baf1); ?>
<?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canAdvancedExport): ?>
                        <?php if (isset($component)) { $__componentOriginal5dc871dce4923f548320c53d3ad5baf1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dc871dce4923f548320c53d3ad5baf1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::dropdown.item','data' => ['wire:click' => 'export(\'tsv\')','xOn:click' => 'open = false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::dropdown.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'export(\'tsv\')','x-on:click' => 'open = false']); ?>
                            <?php echo e(__('dashboard.TSV')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dc871dce4923f548320c53d3ad5baf1)): ?>
<?php $attributes = $__attributesOriginal5dc871dce4923f548320c53d3ad5baf1; ?>
<?php unset($__attributesOriginal5dc871dce4923f548320c53d3ad5baf1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dc871dce4923f548320c53d3ad5baf1)): ?>
<?php $component = $__componentOriginal5dc871dce4923f548320c53d3ad5baf1; ?>
<?php unset($__componentOriginal5dc871dce4923f548320c53d3ad5baf1); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal5dc871dce4923f548320c53d3ad5baf1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dc871dce4923f548320c53d3ad5baf1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::dropdown.item','data' => ['wire:click' => 'export(\'json\')','xOn:click' => 'open = false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::dropdown.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'export(\'json\')','x-on:click' => 'open = false']); ?>
                            <?php echo e(__('dashboard.JSON')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dc871dce4923f548320c53d3ad5baf1)): ?>
<?php $attributes = $__attributesOriginal5dc871dce4923f548320c53d3ad5baf1; ?>
<?php unset($__attributesOriginal5dc871dce4923f548320c53d3ad5baf1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dc871dce4923f548320c53d3ad5baf1)): ?>
<?php $component = $__componentOriginal5dc871dce4923f548320c53d3ad5baf1; ?>
<?php unset($__componentOriginal5dc871dce4923f548320c53d3ad5baf1); ?>
<?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                 <?php $__env->endSlot(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal768e8fc940702d540fb0480605850979)): ?>
<?php $attributes = $__attributesOriginal768e8fc940702d540fb0480605850979; ?>
<?php unset($__attributesOriginal768e8fc940702d540fb0480605850979); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal768e8fc940702d540fb0480605850979)): ?>
<?php $component = $__componentOriginal768e8fc940702d540fb0480605850979; ?>
<?php unset($__componentOriginal768e8fc940702d540fb0480605850979); ?>
<?php endif; ?>
            <span class="rounded-md bg-krikkit-soft px-2 py-1 text-[11px] font-medium text-krikkit-fg-soft"><?php echo e($cards['month']); ?></span>
            <span class="rounded-md bg-krikkit-soft px-2 py-1 text-[11px] font-medium tabular-nums text-krikkit-fg-soft"><?php echo e($cards['currency']); ?></span>
        </div>
    </div>

    <?php echo $__env->make('livewire.dashboard.finance.options.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="grid gap-3 lg:grid-cols-3">
        <?php if (isset($component)) { $__componentOriginald99998d64b0875088b034c3416ebc71a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald99998d64b0875088b034c3416ebc71a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => ['class' => 'lg:col-span-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'lg:col-span-2']); ?>
            <div class="grid gap-6 sm:grid-cols-[minmax(0,1fr)_13rem] sm:items-end">
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.Collected this month')); ?></p>
                    <p class="mt-2 text-3xl font-semibold tabular-nums tracking-tight text-krikkit-fg"><?php echo e(Money::format($cards['collected'], $cards['currency'])); ?></p>
                    <p class="mt-1 flex items-center gap-2 text-[11px] text-krikkit-muted">
                        <?php echo $__env->make('livewire.dashboard.finance.partials.delta', ['delta' => $cards['collected_delta']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <span><?php echo e(__('dashboard.vs last month')); ?></span>
                    </p>
                </div>
                <div class="grid grid-cols-6 gap-2" aria-hidden="true">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $cards['series']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="flex flex-col items-center gap-1.5">
                            <div class="relative h-14 w-full">
                                <div class="absolute inset-x-0 bottom-0 mx-auto w-2.5 rounded-full bg-krikkit-fg/85" style="height: <?php echo e(max(14, (int) round(($point['value'] / $seriesMax) * 100))); ?>%"></div>
                            </div>
                            <span class="text-[10px] leading-none text-krikkit-subtle"><?php echo e($point['label']); ?></span>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.MRR')); ?></p>
            <p class="mt-2 text-3xl font-semibold tabular-nums tracking-tight text-krikkit-fg"><?php echo e(Money::format($cards['mrr'], $cards['currency'])); ?></p>
            <p class="mt-1 text-[11px] text-krikkit-muted"><?php echo e(number_format($cards['active'])); ?> <?php echo e(__('dashboard.active subscriptions')); ?></p>
            <p class="mt-3 text-[11px] text-krikkit-subtle"><?php echo e(__('dashboard.Active subscriptions, yearly divided by 12.')); ?></p>
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
    </div>

    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <?php if (isset($component)) { $__componentOriginald99998d64b0875088b034c3416ebc71a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald99998d64b0875088b034c3416ebc71a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.Net after API')); ?></p>
            <p class="mt-2 text-xl font-semibold tabular-nums text-krikkit-fg"><?php echo e(Money::format($cards['net'], $cards['currency'])); ?></p>
            <p class="mt-1 text-[11px] text-krikkit-muted">
                <?php echo e(__('dashboard.Collected :revenue · est. API :cost (:credits credits)', [
                    'revenue' => Money::format($cards['collected'], $cards['currency']),
                    'cost' => Money::format($cards['api_usd'], $cards['currency']),
                    'credits' => number_format($cards['credits_burned']),
                ])); ?>

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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.Failed charges')); ?></p>
            <p class="mt-2 text-xl font-semibold tabular-nums text-krikkit-fg"><?php echo e(number_format($cards['failed'])); ?></p>
            <p class="mt-1 flex items-center gap-2 text-[11px] text-krikkit-muted">
                <?php echo $__env->make('livewire.dashboard.finance.partials.delta', ['delta' => $cards['failed_delta']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <span><?php echo e(__('dashboard.Failed invoices this month.')); ?></span>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.Cancellations')); ?></p>
            <p class="mt-2 text-xl font-semibold tabular-nums text-krikkit-fg"><?php echo e(number_format($cards['canceled'])); ?></p>
            <p class="mt-1 flex items-center gap-2 text-[11px] text-krikkit-muted">
                <?php echo $__env->make('livewire.dashboard.finance.partials.delta', ['delta' => $cards['canceled_delta']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <span><?php echo e(__('dashboard.Canceled subscriptions this month.')); ?></span>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.Credit liability')); ?></p>
            <p class="mt-2 text-xl font-semibold tabular-nums text-krikkit-fg"><?php echo e(number_format($cards['credit_liability'])); ?></p>
            <p class="mt-1 text-[11px] text-krikkit-muted">
                <?php echo e(__('dashboard.Unused pack and grant credits.')); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cards['unlimited_holders'] > 0): ?>
                    · <?php echo e(__('dashboard.:count unlimited', ['count' => $cards['unlimited_holders']])); ?>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
    </div>

    <div class="grid gap-3 xl:grid-cols-2">
        <?php if (isset($component)) { $__componentOriginald99998d64b0875088b034c3416ebc71a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald99998d64b0875088b034c3416ebc71a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.Collections by method')); ?></p>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($cards['mix']) === 0): ?>
                <p class="mt-4 text-sm text-krikkit-muted"><?php echo e(__('dashboard.No collections this month.')); ?></p>
            <?php else: ?>
                <div class="mt-4 space-y-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $cards['mix']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div>
                            <div class="mb-1 flex items-center justify-between text-[11px]">
                                <span class="font-medium text-krikkit-fg"><?php echo e(FinanceCopy::driver($row['driver'])); ?></span>
                                <span class="tabular-nums text-krikkit-muted"><?php echo e(Money::format($row['amount'], $cards['currency'])); ?> · <?php echo e($row['share']); ?>%</span>
                            </div>
                            <div class="h-1.5 overflow-hidden rounded-full bg-krikkit-soft">
                                <div class="h-full rounded-full bg-krikkit-fg" style="width: <?php echo e(min(100, $row['share'])); ?>%"></div>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-krikkit-subtle"><?php echo e(__('dashboard.Invoice health')); ?></p>
            <div class="mt-4 grid grid-cols-4 gap-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['paid', 'open', 'failed', 'refunded']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div>
                        <p class="text-[10px] uppercase tracking-wide text-krikkit-muted"><?php echo e(__('dashboard.'.ucfirst($key))); ?></p>
                        <p class="mt-1 text-lg font-semibold tabular-nums text-krikkit-fg"><?php echo e(number_format($cards['health'][$key])); ?></p>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
            <div class="mt-4 flex h-1.5 overflow-hidden rounded-full bg-krikkit-soft">
                <div class="bg-teal-600" style="width: <?php echo e(($cards['health']['paid'] / $healthTotal) * 100); ?>%"></div>
                <div class="bg-blue-500" style="width: <?php echo e(($cards['health']['open'] / $healthTotal) * 100); ?>%"></div>
                <div class="bg-red-500" style="width: <?php echo e(($cards['health']['failed'] / $healthTotal) * 100); ?>%"></div>
                <div class="bg-zinc-400" style="width: <?php echo e(($cards['health']['refunded'] / $healthTotal) * 100); ?>%"></div>
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
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <section class="space-y-3">
            <div class="flex items-end justify-between gap-3">
                <h2 class="text-sm font-medium text-krikkit-fg"><?php echo e(__('dashboard.Recent invoices')); ?></h2>
                <a href="<?php echo e(route('dashboard.invoices.index')); ?>" wire:navigate class="text-[11px] text-krikkit-muted hover:text-krikkit-fg"><?php echo e(__('dashboard.View all')); ?></a>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoices->isEmpty()): ?>
                <div class="rounded-xl border border-dashed border-krikkit-line px-4 py-8 text-center text-sm text-krikkit-muted">
                    <?php echo e(__('dashboard.No invoices yet. Checkout and invoice.paid webhooks land here.')); ?>

                </div>
            <?php else: ?>
                <?php if (isset($component)) { $__componentOriginald9f9621c9792e26cb0a786db19dbfd95 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald9f9621c9792e26cb0a786db19dbfd95 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table','data' => ['fit' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['fit' => true]); ?>
                    <?php if (isset($component)) { $__componentOriginal8ea4f77adc03babec838eacd9b78df96 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ea4f77adc03babec838eacd9b78df96 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.columns','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.columns'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.User')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Amount')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => ['align' => 'right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right']); ?><?php echo e(__('dashboard.Status')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8ea4f77adc03babec838eacd9b78df96)): ?>
<?php $attributes = $__attributesOriginal8ea4f77adc03babec838eacd9b78df96; ?>
<?php unset($__attributesOriginal8ea4f77adc03babec838eacd9b78df96); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8ea4f77adc03babec838eacd9b78df96)): ?>
<?php $component = $__componentOriginal8ea4f77adc03babec838eacd9b78df96; ?>
<?php unset($__componentOriginal8ea4f77adc03babec838eacd9b78df96); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.rows','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.rows'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php $tone = FinanceCopy::invoice($invoice->status); ?>
                            <?php if (isset($component)) { $__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.row','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                    <a href="<?php echo e(route('dashboard.invoices.show', $invoice)); ?>" wire:navigate class="text-xs font-medium text-krikkit-fg hover:underline">
                                        <?php echo e($invoice->user?->name ?: __('dashboard.Unlinked')); ?>

                                    </a>
                                    <p class="text-[11px] text-krikkit-muted"><?php echo e($invoice->plan?->title ?: FinanceCopy::driver($invoice->driver)); ?> · <?php echo e($invoice->paid_at?->format('M j') ?: $invoice->created_at?->format('M j')); ?></p>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                    <span class="tabular-nums text-krikkit-fg"><?php echo e($invoice->formattedAmount()); ?></span>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => ['align' => 'right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right']); ?>
                                    <?php if (isset($component)) { $__componentOriginal0bc981f99055f90423de9ceafd4f5f18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::badge','data' => ['color' => $tone['color'],'size' => 'xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tone['color']),'size' => 'xs']); ?><?php echo e($tone['label']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $attributes = $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $component = $__componentOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f)): ?>
<?php $attributes = $__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f; ?>
<?php unset($__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f)): ?>
<?php $component = $__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f; ?>
<?php unset($__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f); ?>
<?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7)): ?>
<?php $attributes = $__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7; ?>
<?php unset($__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7)): ?>
<?php $component = $__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7; ?>
<?php unset($__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7); ?>
<?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald9f9621c9792e26cb0a786db19dbfd95)): ?>
<?php $attributes = $__attributesOriginald9f9621c9792e26cb0a786db19dbfd95; ?>
<?php unset($__attributesOriginald9f9621c9792e26cb0a786db19dbfd95); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald9f9621c9792e26cb0a786db19dbfd95)): ?>
<?php $component = $__componentOriginald9f9621c9792e26cb0a786db19dbfd95; ?>
<?php unset($__componentOriginald9f9621c9792e26cb0a786db19dbfd95); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </section>

        <section class="space-y-3">
            <div class="flex items-end justify-between gap-3">
                <h2 class="text-sm font-medium text-krikkit-fg"><?php echo e(__('dashboard.Recent events')); ?></h2>
                <a href="<?php echo e(route('dashboard.finance.events.index')); ?>" wire:navigate class="text-[11px] text-krikkit-muted hover:text-krikkit-fg"><?php echo e(__('dashboard.View all')); ?></a>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($events->isEmpty()): ?>
                <div class="rounded-xl border border-dashed border-krikkit-line px-4 py-8 text-center text-sm text-krikkit-muted">
                    <?php echo e(__('dashboard.No webhook events yet.')); ?>

                </div>
            <?php else: ?>
                <?php if (isset($component)) { $__componentOriginald9f9621c9792e26cb0a786db19dbfd95 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald9f9621c9792e26cb0a786db19dbfd95 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table','data' => ['fit' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['fit' => true]); ?>
                    <?php if (isset($component)) { $__componentOriginal8ea4f77adc03babec838eacd9b78df96 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ea4f77adc03babec838eacd9b78df96 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.columns','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.columns'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Event')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => ['align' => 'right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right']); ?><?php echo e(__('dashboard.Status')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8ea4f77adc03babec838eacd9b78df96)): ?>
<?php $attributes = $__attributesOriginal8ea4f77adc03babec838eacd9b78df96; ?>
<?php unset($__attributesOriginal8ea4f77adc03babec838eacd9b78df96); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8ea4f77adc03babec838eacd9b78df96)): ?>
<?php $component = $__componentOriginal8ea4f77adc03babec838eacd9b78df96; ?>
<?php unset($__componentOriginal8ea4f77adc03babec838eacd9b78df96); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.rows','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.rows'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php $tone = FinanceCopy::event($event->status); ?>
                            <?php if (isset($component)) { $__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.row','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                    <a href="<?php echo e(route('dashboard.finance.events.show', $event)); ?>" wire:navigate class="block truncate text-xs font-medium text-krikkit-fg hover:underline">
                                        <?php echo e($event->event_type); ?>

                                    </a>
                                    <p class="text-[11px] text-krikkit-muted"><?php echo e(FinanceCopy::driver($event->driver)); ?> · <?php echo e($event->created_at?->format('M j, H:i')); ?></p>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => ['align' => 'right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right']); ?>
                                    <?php if (isset($component)) { $__componentOriginal0bc981f99055f90423de9ceafd4f5f18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::badge','data' => ['color' => $tone['color'],'size' => 'xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tone['color']),'size' => 'xs']); ?><?php echo e($tone['label']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $attributes = $__attributesOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__attributesOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18)): ?>
<?php $component = $__componentOriginal0bc981f99055f90423de9ceafd4f5f18; ?>
<?php unset($__componentOriginal0bc981f99055f90423de9ceafd4f5f18); ?>
<?php endif; ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f)): ?>
<?php $attributes = $__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f; ?>
<?php unset($__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f)): ?>
<?php $component = $__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f; ?>
<?php unset($__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f); ?>
<?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7)): ?>
<?php $attributes = $__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7; ?>
<?php unset($__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7)): ?>
<?php $component = $__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7; ?>
<?php unset($__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7); ?>
<?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald9f9621c9792e26cb0a786db19dbfd95)): ?>
<?php $attributes = $__attributesOriginald9f9621c9792e26cb0a786db19dbfd95; ?>
<?php unset($__attributesOriginald9f9621c9792e26cb0a786db19dbfd95); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald9f9621c9792e26cb0a786db19dbfd95)): ?>
<?php $component = $__componentOriginald9f9621c9792e26cb0a786db19dbfd95; ?>
<?php unset($__componentOriginald9f9621c9792e26cb0a786db19dbfd95); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </section>
    </div>

    <section class="space-y-3">
        <div class="flex items-end justify-between gap-3">
            <h2 class="text-sm font-medium text-krikkit-fg"><?php echo e(__('dashboard.Recent grants')); ?></h2>
            <a href="<?php echo e(route('dashboard.credits.index')); ?>" wire:navigate class="text-[11px] text-krikkit-muted hover:text-krikkit-fg"><?php echo e(__('dashboard.View all')); ?></a>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($grants->isEmpty()): ?>
            <div class="rounded-xl border border-dashed border-krikkit-line px-4 py-8 text-center text-sm text-krikkit-muted">
                <?php echo e(__('dashboard.No credit grants yet. Manual grants and top-ups appear here.')); ?>

            </div>
        <?php else: ?>
            <?php if (isset($component)) { $__componentOriginald9f9621c9792e26cb0a786db19dbfd95 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald9f9621c9792e26cb0a786db19dbfd95 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table','data' => ['fit' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['fit' => true]); ?>
                <?php if (isset($component)) { $__componentOriginal8ea4f77adc03babec838eacd9b78df96 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ea4f77adc03babec838eacd9b78df96 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.columns','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.columns'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.User')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Amount')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Kind')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.column','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('dashboard.Granted by')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $attributes = $__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__attributesOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6)): ?>
<?php $component = $__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6; ?>
<?php unset($__componentOriginal6efd9a4926281a7e1feb9ff0a620bbb6); ?>
<?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8ea4f77adc03babec838eacd9b78df96)): ?>
<?php $attributes = $__attributesOriginal8ea4f77adc03babec838eacd9b78df96; ?>
<?php unset($__attributesOriginal8ea4f77adc03babec838eacd9b78df96); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8ea4f77adc03babec838eacd9b78df96)): ?>
<?php $component = $__componentOriginal8ea4f77adc03babec838eacd9b78df96; ?>
<?php unset($__componentOriginal8ea4f77adc03babec838eacd9b78df96); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.rows','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.rows'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $grants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.row','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <p class="text-xs font-medium text-krikkit-fg"><?php echo e($grant->user?->name ?: '—'); ?></p>
                                <p class="text-[11px] text-krikkit-muted"><?php echo e($grant->reason ?: $grant->plan?->title); ?></p>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <span class="tabular-nums text-krikkit-fg"><?php echo e($grant->amount > 0 ? '+'.$grant->amount : $grant->amount); ?></span>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <span class="text-krikkit-muted"><?php echo e(FinanceCopy::creditKind($grant->kind)); ?></span>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal2e1126941101f12b00b0d935b9734080 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e1126941101f12b00b0d935b9734080 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <span class="text-krikkit-muted"><?php echo e($grant->actor?->name ?: '—'); ?></span>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $attributes = $__attributesOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__attributesOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e1126941101f12b00b0d935b9734080)): ?>
<?php $component = $__componentOriginal2e1126941101f12b00b0d935b9734080; ?>
<?php unset($__componentOriginal2e1126941101f12b00b0d935b9734080); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f)): ?>
<?php $attributes = $__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f; ?>
<?php unset($__attributesOriginal0ef8bf3dda682b2c0beb435bb5a9f92f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f)): ?>
<?php $component = $__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f; ?>
<?php unset($__componentOriginal0ef8bf3dda682b2c0beb435bb5a9f92f); ?>
<?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7)): ?>
<?php $attributes = $__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7; ?>
<?php unset($__attributesOriginal8554b89d3cfb6d15a9f4267b52cf6aa7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7)): ?>
<?php $component = $__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7; ?>
<?php unset($__componentOriginal8554b89d3cfb6d15a9f4267b52cf6aa7); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald9f9621c9792e26cb0a786db19dbfd95)): ?>
<?php $attributes = $__attributesOriginald9f9621c9792e26cb0a786db19dbfd95; ?>
<?php unset($__attributesOriginald9f9621c9792e26cb0a786db19dbfd95); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald9f9621c9792e26cb0a786db19dbfd95)): ?>
<?php $component = $__componentOriginald9f9621c9792e26cb0a786db19dbfd95; ?>
<?php unset($__componentOriginald9f9621c9792e26cb0a786db19dbfd95); ?>
<?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\livewire\dashboard\finance\finance.blade.php ENDPATH**/ ?>