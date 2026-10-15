<?php if (isset($component)) { $__componentOriginaleb7c5f1b3726a932b5a1990f1d8d55cd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaleb7c5f1b3726a932b5a1990f1d8d55cd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.errors.shell','data' => ['code' => '419','title' => __('messages.Page expired'),'description' => __('messages.Your session timed out. Refresh the page and try again.'),'primaryHref' => url()->current(),'primaryLabel' => __('messages.Refresh page'),'secondaryHref' => route('login'),'secondaryLabel' => __('messages.Log in')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('errors.shell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['code' => '419','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('messages.Page expired')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('messages.Your session timed out. Refresh the page and try again.')),'primary-href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(url()->current()),'primary-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('messages.Refresh page')),'secondary-href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('login')),'secondary-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('messages.Log in'))]); ?>
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
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\errors\pageExpired\pageExpired.blade.php ENDPATH**/ ?>