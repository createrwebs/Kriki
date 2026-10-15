<div class="mb-6">
    <h3 class="section-title"><?php echo e(__('installer::installer.environment_title')); ?></h3>
    <p class="section-subtitle"><?php echo e(__('installer::installer.environment_subtitle')); ?></p>
</div>

<div class="form-grid">
    <div class="col-span-full">
        <label class="form-label"><?php echo e(__('installer::installer.environment_connection_type')); ?></label>
        <select wire:model.live="state.connection"
            x-on:change="
                const port = { mysql: '3306', mariadb: '3306', pgsql: '5432', sqlsrv: '1433', sqlite: '' };
                $wire.state.port = port[$event.target.value] || '3306';
            "
            class="form-select">
            <option value="mysql">MySQL</option>
            <option value="mariadb">MariaDB</option>
            <option value="pgsql">PostgreSQL</option>
            <option value="sqlite">SQLite</option>
            <option value="sqlsrv">SQL Server</option>
        </select>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($state['connection'] ?? 'mysql') !== 'sqlite'): ?>
    <div>
        <label class="form-label"><?php echo e(__('installer::installer.environment_host')); ?></label>
        <input type="text" wire:model.live="state.host" class="form-input" placeholder="127.0.0.1">
    </div>
    <div>
        <label class="form-label"><?php echo e(__('installer::installer.environment_port')); ?></label>
        <input type="text" wire:model.live="state.port" class="form-input" placeholder="3306">
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="col-span-full">
        <label class="form-label"><?php echo e(__('installer::installer.environment_database_name')); ?></label>
        <div class="form-input-wrapper">
            <div class="form-input-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                </svg>
            </div>
            <input type="text" wire:model.live="state.database" class="form-input form-input--with-icon" placeholder="app">
        </div>
        <p class="form-hint"><?php echo e(__('installer::installer.environment_database_hint')); ?></p>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($state['connection'] ?? 'mysql') !== 'sqlite'): ?>
    <div>
        <label class="form-label"><?php echo e(__('installer::installer.environment_username')); ?></label>
        <input type="text" wire:model.live="state.username" class="form-input" placeholder="root">
    </div>
    <div>
        <label class="form-label"><?php echo e(__('installer::installer.environment_password')); ?></label>
        <input type="password" wire:model.live="state.password" class="form-input" placeholder="• • • • • • • •">
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = config('installer.environment_fields', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $envKey => $fieldConfig): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <?php
            $stateKey = $fieldConfig['state_key'] ?? strtolower($envKey);
            $type = $fieldConfig['type'] ?? 'text';
            $label = $fieldConfig['label'] ?? $envKey;
            $description = $fieldConfig['description'] ?? null;
        ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($type === 'checkbox'): ?>
            <div class="col-span-full form-divider">
                <label class="toggle-label">
                    <input type="checkbox" wire:model.live="state.<?php echo e($stateKey); ?>" class="toggle-input">
                    <div>
                        <span class="toggle-text"><?php echo e($label); ?></span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($description): ?>
                            <span class="toggle-desc"><?php echo e($description); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </label>
            </div>
        <?php elseif($type === 'select'): ?>
            <div class="<?php echo e(($type === 'textarea') ? 'col-span-full' : ''); ?>">
                <label class="form-label"><?php echo e($label); ?></label>
                <select wire:model="state.<?php echo e($stateKey); ?>" class="form-select">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $fieldConfig['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $optionLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <option value="<?php echo e($value); ?>"><?php echo e($optionLabel); ?></option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($description): ?>
                    <p class="form-hint"><?php echo e($description); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php else: ?>
            <div class="<?php echo e(($type === 'textarea') ? 'col-span-full' : ''); ?>">
                <label class="form-label"><?php echo e($label); ?></label>
                <input type="<?php echo e($type); ?>" wire:model="state.<?php echo e($stateKey); ?>" class="form-input" placeholder="<?php echo e($fieldConfig['placeholder'] ?? ''); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($description): ?>
                    <p class="form-hint"><?php echo e($description); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

    <div class="col-span-full">
        <button type="button" wire:click="testDatabase" wire:loading.attr="disabled" class="test-connection-btn">
            <span wire:loading.remove wire:target="testDatabase"><?php echo e(__('installer::installer.environment_test_connection')); ?></span>
            <span wire:loading wire:target="testDatabase"><?php echo e(__('installer::installer.environment_testing')); ?></span>
        </button>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($testConnectionResult): ?>
            <div class="msg-box">
                <span class="msg-box-icon <?php echo e($testConnectionResult['success'] ? 'msg-box-icon--ok' : 'msg-box-icon--fail'); ?>" aria-hidden="true">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($testConnectionResult['success']): ?>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    <?php else: ?>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </span>
                <span><?php echo e($testConnectionResult['message']); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\vendor\deep42\hitchhiker\resources\views\steps\environment.blade.php ENDPATH**/ ?>