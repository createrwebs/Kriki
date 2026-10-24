<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $currency = $this->input('website_budget_currency');

        if ($currency === null || (is_string($currency) && trim($currency) === '')) {
            $this->merge(['website_budget_currency' => 'THB']);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'company_name' => ['nullable', 'string', 'max:255'],
            'business_description' => ['nullable', 'string', 'max:2000'],
            'website_budget_amount' => ['nullable', 'string', 'regex:/^\d{1,12}(\.\d{1,2})?$/D'],
            'website_budget_currency' => ['required', 'string', 'in:THB,USD'],
        ];
    }

    public function authData(): array
    {
        return $this->safe()->only(['name', 'email', 'password']);
    }

    public function businessProfileData(): array
    {
        return [
            'company_name' => $this->validated('company_name'),
            'business_description' => $this->validated('business_description'),
            'website_budget_amount' => $this->validated('website_budget_amount'),
            'website_budget_currency' => $this->validated('website_budget_currency'),
        ];
    }
}
