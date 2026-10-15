<?php if (isset($component)) { $__componentOriginaleb7c5f1b3726a932b5a1990f1d8d55cd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaleb7c5f1b3726a932b5a1990f1d8d55cd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.errors.shell','data' => ['code' => '403','title' => __('messages.Access denied'),'description' => __('messages.You do not have permission to open this page.'),'secondaryHref' => auth()->check() ? route('lab') : route('login'),'secondaryLabel' => auth()->check() ? __('messages.Open Lab') : __('messages.Log in')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('errors.shell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['code' => '403','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('messages.Access denied')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('messages.You do not have permission to open this page.')),'secondary-href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->check() ? route('lab') : route('login')),'secondary-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->check() ? __('messages.Open Lab') : __('messages.Log in'))]); ?>
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
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\errors\forbidden\forbidden.blade.php ENDPATH**/ ?>