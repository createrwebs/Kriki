<div class="mb-6">
    <h3 class="section-title"><?php echo e(__('installer::installer.admin_title')); ?></h3>
    <p class="section-subtitle"><?php echo e(__('installer::installer.admin_subtitle')); ?></p>
</div>

<div class="form-grid" x-data="{
        showPw: false,
        showPc: false,
        pw: '',
        pc: '',
        get score() {
            if (!this.pw) return 0;
            let s = 0;
            if (this.pw.length >= 8) s++;
            if (/[a-z]/.test(this.pw) && /[A-Z]/.test(this.pw)) s++;
            if (/\d/.test(this.pw)) s++;
            if (/[^a-zA-Z0-9]/.test(this.pw)) s++;
            return s;
        },
        get scoreClass() {
            if (this.score <= 1) return 'score-weak';
            if (this.score === 2) return 'score-fair';
            return 'score-strong';
        },
        get textLabel() {
            if (this.score === 0) return <?php echo \Illuminate\Support\Js::from(__('installer::installer.admin_password_strength_very_weak'))->toHtml() ?>;
            if (this.score === 1) return <?php echo \Illuminate\Support\Js::from(__('installer::installer.admin_password_strength_weak'))->toHtml() ?>;
            if (this.score === 2) return <?php echo \Illuminate\Support\Js::from(__('installer::installer.admin_password_strength_fair'))->toHtml() ?>;
            if (this.score === 3) return <?php echo \Illuminate\Support\Js::from(__('installer::installer.admin_password_strength_strong'))->toHtml() ?>;
            return <?php echo \Illuminate\Support\Js::from(__('installer::installer.admin_password_strength_very_strong'))->toHtml() ?>;
        }
    }">
    <div class="col-span-full">
        <label class="form-label"><?php echo e(__('installer::installer.admin_name')); ?></label>
        <input type="text" wire:model="state.name" class="form-input" placeholder="<?php echo e(__('installer::installer.admin_name_placeholder')); ?>">
    </div>

    <div class="col-span-full">
        <label class="form-label"><?php echo e(__('installer::installer.admin_email')); ?></label>
        <div class="form-input-wrapper">
            <div class="form-input-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <input type="email" wire:model="state.email" class="form-input form-input--with-icon" placeholder="<?php echo e(__('installer::installer.admin_email_placeholder')); ?>">
        </div>
    </div>

    <div>
        <label class="form-label"><?php echo e(__('installer::installer.admin_password')); ?></label>
        <div class="form-input-wrapper">
            <input :type="showPw ? 'text' : 'password'" wire:model="state.password" x-on:input="pw = $event.target.value" class="form-input form-input--with-icon-right" placeholder="<?php echo e(__('installer::installer.admin_password_placeholder')); ?>">
            <button type="button" class="form-input-icon-right" @click="showPw = !showPw" :title="<?php echo \Illuminate\Support\Js::from(__('installer::installer.admin_password_toggle'))->toHtml() ?>">
                <svg x-show="!showPw" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                <svg x-show="showPw" x-cloak style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 9.978 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
            </button>
        </div>
        <div class="pw-meter" x-show="pw && pw.length > 0" x-cloak>
            <template x-for="i in 4" :key="i">
                <div class="pw-meter-bar" :class="score >= i ? 'is-filled ' + scoreClass : ''"></div>
            </template>
        </div>
        <p class="pw-meter-label" x-show="pw && pw.length > 0" x-cloak :class="scoreClass" x-text="textLabel"></p>
    </div>
    <div>
        <label class="form-label"><?php echo e(__('installer::installer.admin_password_confirm')); ?></label>
        <div class="form-input-wrapper">
            <input :type="showPc ? 'text' : 'password'" wire:model="state.password_confirmation" x-on:input="pc = $event.target.value" class="form-input form-input--with-icon-right" placeholder="<?php echo e(__('installer::installer.admin_password_confirm_placeholder')); ?>">
            <button type="button" class="form-input-icon-right" @click="showPc = !showPc" :title="<?php echo \Illuminate\Support\Js::from(__('installer::installer.admin_password_toggle'))->toHtml() ?>">
                <svg x-show="!showPc" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                <svg x-show="showPc" x-cloak style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 9.978 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
            </button>
        </div>
        <p class="pw-mismatch" x-show="pc && pc.length > 0 && pw !== pc" x-cloak>
            <?php echo e(__('installer::installer.admin_password_mismatch')); ?>

        </p>
    </div>
</div>
<?php /**PATH D:\codea\krikkit-ai-website\vendor\deep42\hitchhiker\resources\views\steps\admin.blade.php ENDPATH**/ ?>