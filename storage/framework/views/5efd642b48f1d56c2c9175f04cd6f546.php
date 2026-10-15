<?php
    use App\Support\Ui\Pulse;
    use Illuminate\Support\Js;

    $pulses = [];

    foreach ($errors->all() as $copy) {
        $pulses[] = Pulse::craft($copy, null, 'fail');
    }

    if (is_array($testConnectionResult ?? null) && filled($testConnectionResult['message'] ?? null)) {
        $ok = (bool) ($testConnectionResult['success'] ?? false);
        $pulses[] = Pulse::craft(
            (string) $testConnectionResult['message'],
            null,
            $ok ? 'ok' : 'fail',
        );
    }

    $queued = session('installer.pulse');
    if (is_array($queued) && filled($queued['copy'] ?? null)) {
        $pulses[] = $queued;
    }

    $signatures = array_map(
        static fn (array $packet): string => ($packet['tone'] ?? '').'|'.($packet['copy'] ?? ''),
        $pulses,
    );
    $pulseKey = $signatures === [] ? 'idle' : md5(implode("\n", $signatures));
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pulses !== []): ?>
    <div
        <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'installer-pulse-'.e($pulseKey).''; ?>wire:key="installer-pulse-<?php echo e($pulseKey); ?>"
        class="sr-only"
        aria-hidden="true"
        x-data
        x-init="
            const packets = <?php echo e(Js::from($pulses)); ?>;
            const sig = <?php echo e(Js::from($pulseKey)); ?>;
            if (window.__krikkitInstallerPulseSig === sig) {
                return;
            }
            window.__krikkitInstallerPulseSig = sig;
            packets.forEach((packet) => {
                window.dispatchEvent(new CustomEvent(<?php echo e(Js::from(Pulse::EVENT)); ?>, { detail: packet }));
            });
        "
    ></div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\codea\krikkit-ai-website\resources\views\installer\partials\pulses.blade.php ENDPATH**/ ?>