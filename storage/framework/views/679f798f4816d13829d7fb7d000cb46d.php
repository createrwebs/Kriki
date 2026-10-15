<?php
    $site = app(\App\Support\Site\SiteSettings::class);
    $landing = app(\App\Support\Site\LandingCopy::class);
    $hasBlog = \App\Support\Content\PublicIndex::blogIsLive();
    $hasPages = \App\Support\Content\PublicIndex::pagesAreLive();
    $walkTabs = $landing->walkTabs();
?>

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
        x-data="{
            menu: false,
            stack: 0,
            walk: 0,
            walkView: 0,
            walkGen: 0,
            walkMs: 5000,
            walkFade: false,
            walkTimer: null,
            walkCopy: <?php echo e(Illuminate\Support\Js::from($walkTabs)); ?>,
            faq: -1,
            setWalk(index) {
                if (index === this.walk) {
                    this.walkGen++
                    return
                }

                clearTimeout(this.walkTimer)
                this.walk = index
                this.walkGen++
                this.walkFade = true
                this.walkTimer = setTimeout(() => {
                    this.walkView = index
                    this.$nextTick(() => { this.walkFade = false })
                }, 180)
            },
            advanceWalk(from) {
                if (this.walk !== from) {
                    return
                }

                if (this.walkCopy.length === 0) {
                    return
                }

                this.setWalk((from + 1) % this.walkCopy.length)
            },
            destroy() {
                clearTimeout(this.walkTimer)
            },
        }"
        x-on:keydown.escape.window="menu = false"
        class="min-h-screen bg-krikkit-canvas text-krikkit-fg"
    >
        <?php echo $__env->make('home.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <main>
            <?php echo $__env->make('home.partials.hero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('home.partials.problem', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('home.partials.platform', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('home.partials.agents', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('home.partials.walkthrough', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('home.partials.integrations', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('home.partials.faq', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('home.partials.cta', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\home\home.blade.php ENDPATH**/ ?>