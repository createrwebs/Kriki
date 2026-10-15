<?php
    $retryAfter = null;
    if (isset($exception) && method_exists($exception, 'getHeaders')) {
        $headers = $exception->getHeaders();
        $retryAfter = isset($headers['Retry-After']) ? (int) $headers['Retry-After'] : null;
    }

    $description = $retryAfter && $retryAfter > 0
        ? __('messages.You hit a short rate limit. Wait :seconds seconds, then try again.', ['seconds' => $retryAfter])
        : __('messages.You hit a short rate limit. Wait a minute, then try again.');
?>

<?php if (isset($component)) { $__componentOriginaleb7c5f1b3726a932b5a1990f1d8d55cd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaleb7c5f1b3726a932b5a1990f1d8d55cd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.errors.shell','data' => ['code' => '429','title' => __('messages.Too many requests'),'description' => $description,'primaryHref' => url()->previous() !== url()->current() ? url()->previous() : route('home'),'primaryLabel' => __('messages.Go back'),'secondaryHref' => auth()->check() ? route('lab') : null,'secondaryLabel' => auth()->check() ? __('messages.Open Lab') : null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('errors.shell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['code' => '429','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('messages.Too many requests')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($description),'primary-href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(url()->previous() !== url()->current() ? url()->previous() : route('home')),'primary-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('messages.Go back')),'secondary-href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->check() ? route('lab') : null),'secondary-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->check() ? __('messages.Open Lab') : null)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaleb7c5f1b3726a932b5a1990f1d8d55cd)): ?>
<?php $attributes = $__attributesOriginaleb7c5f1b3726a932b5a1990f1d8d55cd; ?>
<?php unset($__attributesOriginaleb7c5f1b3726a932b5a1990f1d8d55cd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaleb7c5f1b3726a932b5a1990f1d8d55cd)): ?>
<?php $component = $__componentOriginaleb7c5f1b3726a932b5a1990f1d8d55cd; ?>
<?php unset($__componentOriginaleb7c5f1b3726a932b5a1990f1d8d55cd); ?>
<?php endif; ?>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\errors\tooMany\tooMany.blade.php ENDPATH**/ ?>