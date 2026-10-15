<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value' => null,
    'placeholder' => null,
    'name' => null,
    'invalid' => false,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'value' => null,
    'placeholder' => null,
    'name' => null,
    'invalid' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $name = $name ?? $attributes->get('name');
    $initial = $value ?? $attributes->get('value') ?? '';
    $placeholder = $placeholder ?? __('dashboard.Write…');
    $rail = [
        [
            ['verb' => 'revert', 'title' => __('dashboard.Undo'), 'icon' => 'arrow-uturn-left'],
            ['verb' => 'replay', 'title' => __('dashboard.Redo'), 'icon' => 'arrow-uturn-right'],
        ],
        [
            ['verb' => 'promote', 'arg' => 'h1', 'title' => __('dashboard.Heading 1'), 'glyph' => 'H1'],
            ['verb' => 'promote', 'arg' => 'h2', 'title' => __('dashboard.Heading 2'), 'glyph' => 'H2'],
            ['verb' => 'promote', 'arg' => 'h3', 'title' => __('dashboard.Heading 3'), 'glyph' => 'H3'],
            ['verb' => 'promote', 'arg' => 'p', 'title' => __('dashboard.Paragraph'), 'glyph' => 'P'],
        ],
        [
            ['verb' => 'strong', 'title' => __('dashboard.Bold'), 'glyph' => 'B'],
            ['verb' => 'em', 'title' => __('dashboard.Italic'), 'glyph' => 'I'],
            ['verb' => 'ins', 'title' => __('dashboard.Underline'), 'glyph' => 'U'],
            ['verb' => 'del', 'title' => __('dashboard.Strike'), 'glyph' => 'S'],
            ['verb' => 'glow', 'title' => __('dashboard.Highlight'), 'glyph' => 'H'],
            ['verb' => 'raise', 'title' => __('dashboard.Superscript'), 'glyph' => 'x²'],
            ['verb' => 'lower', 'title' => __('dashboard.Subscript'), 'glyph' => 'x₂'],
        ],
        [
            ['verb' => 'bullets', 'title' => __('dashboard.Bullet list'), 'icon' => 'list-bullet'],
            ['verb' => 'numerals', 'title' => __('dashboard.Numbered list'), 'icon' => 'numbered-list'],
            ['verb' => 'deepen', 'title' => __('dashboard.Indent'), 'icon' => 'chevron-double-right'],
            ['verb' => 'lift', 'title' => __('dashboard.Outdent'), 'icon' => 'chevron-double-left'],
            ['verb' => 'cite', 'title' => __('dashboard.Quote'), 'icon' => 'chat-bubble-left'],
            ['verb' => 'mono', 'title' => __('dashboard.Code block'), 'icon' => 'code-bracket'],
        ],
        [
            ['verb' => 'start', 'title' => __('dashboard.Align left'), 'icon' => 'bars-3-bottom-left'],
            ['verb' => 'mid', 'title' => __('dashboard.Align center'), 'icon' => 'bars-3'],
            ['verb' => 'end', 'title' => __('dashboard.Align right'), 'icon' => 'bars-3-bottom-right'],
            ['verb' => 'spread', 'title' => __('dashboard.Justify'), 'icon' => 'bars-4'],
        ],
        [
            ['verb' => 'href', 'title' => __('dashboard.Link'), 'icon' => 'link'],
            ['verb' => 'detach', 'title' => __('dashboard.Unlink'), 'icon' => 'link-slash'],
            ['verb' => 'figure', 'title' => __('dashboard.Image'), 'icon' => 'photo'],
            ['verb' => 'rule', 'title' => __('dashboard.Divider'), 'icon' => 'minus'],
            ['verb' => 'grid', 'title' => __('dashboard.Table'), 'icon' => 'table-cells'],
            ['verb' => 'wipe', 'title' => __('dashboard.Clear format'), 'icon' => 'paint-brush'],
        ],
    ];
?>

<div
    wire:ignore
    x-data="krikkitInkDesk({ draft: <?php echo \Illuminate\Support\Js::from($initial)->toHtml() ?> })"
    x-init="awake()"
    <?php echo e($attributes->only('class')->class('w-full')); ?>

>
    <div
        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
            'overflow-hidden rounded-2xl border bg-krikkit-surface',
            $invalid ? 'border-red-400/70' : 'border-transparent focus-within:border-krikkit-muted/40',
        ]); ?>"
    >
        <div
            class="relative z-20 flex flex-wrap items-center gap-px rounded-t-2xl border-b border-krikkit-line bg-krikkit-soft/50 p-1"
            role="toolbar"
            aria-label="<?php echo e(__('dashboard.Formatting')); ?>"
        >
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $rail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cluster): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $loop->first): ?>
                    <span class="mx-0.5 hidden h-6 w-px self-center bg-krikkit-line sm:block" aria-hidden="true"></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $cluster; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalb9e144a5a161bf709488486003f38ac9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb9e144a5a161bf709488486003f38ac9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::tooltip','data' => ['content' => $key['title'],'position' => 'bottom']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::tooltip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($key['title']),'position' => 'bottom']); ?>
                        <button
                            type="button"
                            tabindex="-1"
                            class="inline-flex h-8 min-w-8 items-center justify-center rounded-lg px-2 text-[11px] font-semibold tracking-wide text-krikkit-muted hover:bg-krikkit-soft hover:text-krikkit-fg"
                            aria-label="<?php echo e($key['title']); ?>"
                            @mousedown.prevent
                            @click="<?php echo e($key['verb']); ?>(<?php if(isset($key['arg'])): ?><?php echo e(\Illuminate\Support\Js::from($key['arg'])); ?><?php endif; ?>)"
                        >
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($key['icon'])): ?>
                                <?php if (isset($component)) { $__componentOriginal2f88b3d15b59738674618f1a73b04e5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f88b3d15b59738674618f1a73b04e5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::icon','data' => ['name' => $key['icon'],'class' => 'size-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($key['icon']),'class' => 'size-3.5']); ?>
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
                            <?php else: ?>
                                <span><?php echo e($key['glyph']); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </button>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb9e144a5a161bf709488486003f38ac9)): ?>
<?php $attributes = $__attributesOriginalb9e144a5a161bf709488486003f38ac9; ?>
<?php unset($__attributesOriginalb9e144a5a161bf709488486003f38ac9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb9e144a5a161bf709488486003f38ac9)): ?>
<?php $component = $__componentOriginalb9e144a5a161bf709488486003f38ac9; ?>
<?php unset($__componentOriginalb9e144a5a161bf709488486003f38ac9); ?>
<?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        <div
            x-show="ask.visible"
            x-cloak
            class="flex flex-wrap items-center gap-2 border-b border-krikkit-line px-3 py-2"
        >
            <input
                type="url"
                x-model="ask.href"
                x-ref="ask"
                placeholder="<?php echo e(__('dashboard.https://')); ?>"
                class="h-9 min-w-0 flex-1 rounded-full border-transparent bg-krikkit-canvas px-3.5 text-sm text-krikkit-fg outline-none placeholder:text-krikkit-subtle"
                @keydown.enter.prevent="sealAsk()"
                @keydown.escape.prevent="dismissAsk()"
            >
            <?php if (isset($component)) { $__componentOriginal7fec36a6f23868b32bea9c1cbede699c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fec36a6f23868b32bea9c1cbede699c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['type' => 'button','size' => 'sm','@click' => 'sealAsk()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','size' => 'sm','@click' => 'sealAsk()']); ?><?php echo e(__('dashboard.Apply')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::button','data' => ['type' => 'button','size' => 'sm','variant' => 'ghost','@click' => 'dismissAsk()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','size' => 'sm','variant' => 'ghost','@click' => 'dismissAsk()']); ?><?php echo e(__('dashboard.Cancel')); ?> <?php echo $__env->renderComponent(); ?>
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

        <div class="relative">
            <div
                x-ref="desk"
                class="min-h-72 whitespace-pre-wrap px-4 py-3 text-sm leading-relaxed text-krikkit-fg outline-none
                    [&_h1]:mb-3 [&_h1]:text-2xl [&_h1]:font-semibold
                    [&_h2]:mb-2 [&_h2]:text-xl [&_h2]:font-semibold
                    [&_h3]:mb-2 [&_h3]:text-lg [&_h3]:font-semibold
                    [&_p]:mb-3 [&_ul]:mb-3 [&_ul]:list-disc [&_ul]:pl-5
                    [&_ol]:mb-3 [&_ol]:list-decimal [&_ol]:pl-5
                    [&_blockquote]:mb-3 [&_blockquote]:border-l-2 [&_blockquote]:border-krikkit-line [&_blockquote]:pl-3 [&_blockquote]:text-krikkit-muted
                    [&_pre]:mb-3 [&_pre]:overflow-x-auto [&_pre]:rounded-lg [&_pre]:bg-krikkit-soft [&_pre]:px-3 [&_pre]:py-2 [&_pre]:font-mono [&_pre]:text-xs
                    [&_code]:rounded [&_code]:bg-krikkit-soft [&_code]:px-1 [&_code]:font-mono [&_code]:text-xs
                    [&_mark]:rounded-sm [&_mark]:bg-krikkit-soft [&_mark]:text-krikkit-fg
                    [&_a]:text-accent-content [&_a]:underline [&_a]:hover:text-krikkit-fg
                    [&_img]:max-w-full [&_img]:rounded-lg
                    [&_table]:mb-3 [&_table]:w-full [&_table]:border-collapse
                    [&_th]:border [&_th]:border-krikkit-line [&_th]:px-2 [&_th]:py-1 [&_th]:text-left
                    [&_td]:border [&_td]:border-krikkit-line [&_td]:px-2 [&_td]:py-1
                    [&_hr]:my-4 [&_hr]:border-krikkit-line"
                contenteditable="true"
                role="textbox"
                aria-multiline="true"
                aria-placeholder="<?php echo e($placeholder); ?>"
                @input="emit()"
                @paste="ingestPlain($event)"
                @keydown="foldMarks($event)"
                @keydown.mod.z.prevent="revert()"
                @keydown.mod.shift.z.prevent="replay()"
                @keydown.mod.b.prevent="strong()"
                @keydown.mod.i.prevent="em()"
                @keydown.mod.u.prevent="ins()"
            ></div>
            <p
                x-show="blank"
                x-cloak
                class="pointer-events-none absolute left-4 top-3 text-sm text-krikkit-subtle"
            ><?php echo e($placeholder); ?></p>
        </div>

        <input
            x-ref="vault"
            type="hidden"
            <?php if($name): ?> name="<?php echo e($name); ?>" <?php endif; ?>
            value="<?php echo e($initial); ?>"
            <?php echo e($attributes->whereStartsWith('wire:')); ?>

        >
    </div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\krikkit\editor.blade.php ENDPATH**/ ?>