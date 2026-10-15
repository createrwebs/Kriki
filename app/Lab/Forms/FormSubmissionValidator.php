<?php

namespace App\Lab\Forms;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class FormSubmissionValidator
{
    public function validate(array $body, array $definition, bool $requireFuture = true): array
    {
        $plainText = static function ($attribute, $value, $fail): void {
            if (is_string($value) && preg_match('/<[^>]*>|javascript\s*:/iu', $value)) $fail('Use plain text only.');
        };
        $integer = static function ($attribute, $value, $fail): void { if (! is_int($value)) $fail('Expected an integer.'); };
        $rules = [
            'body' => ['required', 'array:version,core,answers,idempotency_key,website'],
            'body.version' => ['required', 'integer', 'min:1'],
            'body.idempotency_key' => ['required', 'uuid'],
            'body.website' => ['nullable', 'string', 'size:0'],
            'body.core' => ['required', 'array:customer_name,phone,requested_at,party_size,notes'],
            'body.core.customer_name' => ['required', 'string', 'max:120', $plainText],
            'body.core.phone' => ['required', 'string', 'regex:/^\+?[0-9][0-9 ()-]{7,19}$/'],
            'body.core.requested_at' => ['required', 'string', 'regex:/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}(?::\d{2}(?:\.\d+)?)?(?:Z|[+-]\d{2}:\d{2})$/', 'date', ...($requireFuture ? ['after:now'] : [])],
            'body.core.party_size' => ['required', $integer, 'integer', 'min:1', 'max:20'],
            'body.core.notes' => ['nullable', 'string', 'max:2000', $plainText],
            'body.answers' => ['present', 'array'],
        ];
        $custom = array_filter($definition['fields'], fn ($field) => str_starts_with($field['id'], 'field_') && ! $field['archived']);
        $allowed = array_column($custom, 'id');
        if (isset($body['answers']) && ! is_array($body['answers'])) {
            throw ValidationException::withMessages(['answers' => 'Expected an answer object.']);
        }
        if (array_diff(array_keys($body['answers'] ?? []), $allowed)) {
            throw ValidationException::withMessages(['answers' => 'Unknown or archived field. Reload the form.']);
        }
        foreach ($custom as $field) {
            $path = 'body.answers.'.$field['id'];
            $rule = [$field['required'] ? 'required' : 'nullable'];
            $rule = array_merge($rule, match ($field['type']) {
                'text' => ['string', 'max:500', $plainText],
                'textarea' => ['string', 'max:2000', $plainText],
                'number' => [static function ($a, $value, $fail) { if (! (is_int($value) || is_float($value)) || ! is_finite((float) $value)) $fail('Expected a finite number.'); }],
                'checkbox' => [static function ($a, $value, $fail) { if (! is_bool($value)) $fail('Expected boolean.'); }, ...($field['required'] ? ['accepted'] : [])],
                'select' => ['string', \Illuminate\Validation\Rule::in(array_column($field['options'], 'id'))],
            });
            $rules[$path] = $rule;
        }
        try {
            Validator::make(['body' => $body], $rules)->validate();
        } catch (ValidationException $error) {
            $messages = [];
            foreach ($error->errors() as $key => $value) $messages[preg_replace('/^body\.?/', '', $key) ?: 'form'] = $value;
            throw ValidationException::withMessages($messages);
        }
        $core = $body['core'];
        $core['customer_name'] = trim($core['customer_name']);
        $core['phone'] = trim($core['phone']);
        $core['notes'] = trim($core['notes'] ?? '');
        $core['requested_at'] = Carbon::parse($core['requested_at'])->utc()->toIso8601String();
        $answers = $body['answers'];
        ksort($answers);
        return ['version' => (int) $body['version'], 'core' => $core, 'answers' => $answers, 'idempotency_key' => $body['idempotency_key']];
    }
}
