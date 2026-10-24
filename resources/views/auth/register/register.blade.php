<x-layouts.guest :title="__('messages.Register')">
    <x-auth.heading :copy="__('messages.Start from a brief. Lab writes the rest.')">
        {{ __('messages.Create account') }}
    </x-auth.heading>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf
        <x-form.input :label="__('messages.Name')" name="name" autocomplete="name" :placeholder="__('messages.Jane Doe')" />
        <x-form.input :label="__('messages.Email')" type="email" name="email" autocomplete="username" :value="request('email')" :placeholder="__('messages.you@example.com')" />
        <x-form.input :label="__('messages.Password')" type="password" name="password" autocomplete="new-password" :value="''" :placeholder="__('messages.••••••••')" />
        <x-form.input :label="__('messages.Confirm password')" type="password" name="password_confirmation" autocomplete="new-password" :value="''" :placeholder="__('messages.••••••••')" />

        @php
            // Rejected arrays/booleans can be flashed by validation; only strings belong in text controls.
            $companyName = old('company_name');
            $businessDescription = old('business_description');
            $budgetAmount = old('website_budget_amount');
            $budgetCurrency = old('website_budget_currency', 'THB');
        @endphp

        <fieldset class="min-w-0 space-y-5 border-t border-krikkit-line pt-6">
            <legend class="float-left mb-2 w-full text-sm font-medium text-krikkit-fg">
                {{ __('Company details') }}
                <span class="ml-1 text-xs font-normal text-krikkit-muted">({{ __('ไม่บังคับ') }})</span>
            </legend>
            <p class="clear-both text-xs leading-5 text-krikkit-muted">{{ __('กรอกเท่าที่พร้อม หรือเว้นว่างไว้ได้ ข้อมูลนี้เก็บในโปรไฟล์บัญชีของคุณ') }}</p>

            <div class="space-y-1.5">
                <krikkit:field.label for="company_name">{{ __('Company name') }}</krikkit:field.label>
                <krikkit:input size="lg" id="company_name" name="company_name" autocomplete="organization" maxlength="255"
                    :value="is_string($companyName) ? $companyName : ''" :invalid="$errors->has('company_name')"
                    :aria-invalid="$errors->has('company_name') ? 'true' : 'false'"
                    :aria-describedby="$errors->has('company_name') ? 'company_name-help company_name-error' : 'company_name-help'" />
                <p id="company_name-help" class="text-xs leading-5 text-krikkit-muted">{{ __('ชื่อบริษัท แบรนด์ หรือชื่อที่ใช้ทำงาน ไม่จำเป็นต้องมีบริษัท') }}</p>
                @error('company_name')
                    <krikkit:field.error id="company_name-error">{{ $message }}</krikkit:field.error>
                @enderror
            </div>

            <div class="space-y-1.5">
                <krikkit:field.label for="business_description">{{ __('Business description') }}</krikkit:field.label>
                <krikkit:textarea id="business_description" name="business_description" rows="3" maxlength="2000"
                    :invalid="$errors->has('business_description')"
                    :aria-invalid="$errors->has('business_description') ? 'true' : 'false'"
                    :aria-describedby="$errors->has('business_description') ? 'business_description-help business_description-error' : 'business_description-help'">{{ is_string($businessDescription) ? $businessDescription : '' }}</krikkit:textarea>
                <p id="business_description-help" class="text-xs leading-5 text-krikkit-muted">{{ __('เล่าว่าคุณทำอะไร และต้องการเว็บไซต์เพื่ออะไร ไม่เกิน 2,000 ตัวอักษร') }}</p>
                @error('business_description')
                    <krikkit:field.error id="business_description-error">{{ $message }}</krikkit:field.error>
                @enderror
            </div>

            <div class="grid min-w-0 grid-cols-1 gap-4 sm:grid-cols-[minmax(0,1fr)_minmax(0,7rem)]">
                <div class="min-w-0 space-y-1.5">
                    <krikkit:field.label for="website_budget_amount">{{ __('Website budget') }}</krikkit:field.label>
                    <krikkit:input size="lg" type="number" id="website_budget_amount" name="website_budget_amount" min="0" step="0.01" max="999999999999.99" inputmode="decimal"
                        :value="is_string($budgetAmount) ? $budgetAmount : ''" :invalid="$errors->has('website_budget_amount')"
                        :aria-invalid="$errors->has('website_budget_amount') ? 'true' : 'false'"
                        :aria-describedby="$errors->has('website_budget_amount') ? 'website_budget_amount-help website_budget_amount-error' : 'website_budget_amount-help'" />
                    <p id="website_budget_amount-help" class="text-xs leading-5 text-krikkit-muted">{{ __('งบทำเว็บไซต์ ไม่ใช่ค่าแพ็กเกจหรือเครดิต AI') }}</p>
                    @error('website_budget_amount')
                        <krikkit:field.error id="website_budget_amount-error">{{ $message }}</krikkit:field.error>
                    @enderror
                </div>

                <div class="min-w-0 space-y-1.5">
                    <krikkit:field.label for="website_budget_currency">{{ __('Currency') }}</krikkit:field.label>
                    <select id="website_budget_currency" name="website_budget_currency"
                        @class(['h-12 w-full min-w-0 rounded-full border bg-krikkit-surface px-4 text-base text-krikkit-fg outline-none transition', 'border-red-400/70 focus:border-red-500' => $errors->has('website_budget_currency'), 'border-transparent focus:border-krikkit-muted/40' => ! $errors->has('website_budget_currency')])
                        aria-invalid="{{ $errors->has('website_budget_currency') ? 'true' : 'false' }}"
                        aria-describedby="website_budget_currency-help{{ $errors->has('website_budget_currency') ? ' website_budget_currency-error' : '' }}">
                        <option value="THB" @selected($budgetCurrency !== 'USD')>THB</option>
                        <option value="USD" @selected($budgetCurrency === 'USD')>USD</option>
                    </select>
                    <p id="website_budget_currency-help" class="text-xs leading-5 text-krikkit-muted">{{ __('ไม่แปลงค่าเงินอัตโนมัติ') }}</p>
                    @error('website_budget_currency')
                        <krikkit:field.error id="website_budget_currency-error">{{ $message }}</krikkit:field.error>
                    @enderror
                </div>
            </div>

            <p class="text-xs leading-5 text-krikkit-muted">{{ __('ไม่ส่งงบให้ AI อัตโนมัติ และการกรอกงบไม่เรียกเก็บเงิน') }}</p>
        </fieldset>

        <krikkit:button type="submit" class="w-full">{{ __('messages.Register') }}</krikkit:button>
    </form>

    <p class="mt-8 border-t border-krikkit-line pt-5 text-sm text-krikkit-muted">
        {{ __('messages.Already registered?') }}
        <a href="{{ route('login') }}" class="font-medium text-krikkit-fg transition hover:text-accent-content">{{ __('messages.Log in') }}</a>
    </p>
</x-layouts.guest>
