<section id="stories" class="scroll-mt-20 border-b border-krikkit-line">
    <div class="mx-auto max-w-7xl border-x border-krikkit-line bg-[radial-gradient(120%_80%_at_50%_0%,color-mix(in_oklab,var(--color-accent)_5%,transparent),transparent_60%)]">
        <div class="px-6 pt-16 md:px-12 md:pt-24">
            <p class="text-sm font-medium text-krikkit-muted"><?php echo e(__('home.What people make')); ?></p>
            <h2 class="mt-3 max-w-2xl text-3xl font-normal leading-tight tracking-tight text-krikkit-fg md:text-5xl">
                <?php echo e(__('home.Lab is used for the pages teams actually ship.')); ?>

            </h2>
        </div>
        <div class="mt-12 grid gap-px border-t border-krikkit-line bg-krikkit-line sm:grid-cols-2 lg:grid-cols-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                ['quote' => __('home.The brief stayed the source of truth. We stopped pasting screenshots into Slack.'), 'role' => __('home.Designer'), 'place' => __('home.Portfolio studio')],
                ['quote' => __('home.A shop grid came out of one prompt. We only opened files to tweak prices.'), 'role' => __('home.Shop owner'), 'place' => __('home.Boutique catalog')],
                ['quote' => __('home.Docs pages stopped being a separate repo. Preview was enough to review copy.'), 'role' => __('home.Writer'), 'place' => __('home.Product docs')],
                ['quote' => __('home.Event landing in an afternoon — date, speakers, tickets — then a domain.'), 'role' => __('home.Producer'), 'place' => __('home.Conference page')],
                ['quote' => __('home.We keep agency drafts in Lab until the client says ship. Files travel with the project.'), 'role' => __('home.Director'), 'place' => __('home.Studio bench')],
                ['quote' => __('home.Internal tools used to wait on a sprint. A brief got us a working first pass.'), 'role' => __('home.Operator'), 'place' => __('home.Ops console')],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $story): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <figure class="flex flex-col gap-6 bg-krikkit-canvas p-6 md:p-8">
                    <blockquote class="text-sm leading-relaxed text-krikkit-fg">“<?php echo e($story['quote']); ?>”</blockquote>
                    <figcaption class="mt-auto text-sm">
                        <p class="font-medium text-krikkit-fg"><?php echo e($story['role']); ?></p>
                        <p class="text-krikkit-muted"><?php echo e($story['place']); ?></p>
                    </figcaption>
                </figure>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>
</section>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\home\partials\stories.blade.php ENDPATH**/ ?>