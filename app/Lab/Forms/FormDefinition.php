<?php

namespace App\Lab\Forms;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class FormDefinition
{
    public function validate(array $definition, ?array $previous = null): array
    {
        $plainText = static function ($attribute, $value, $fail): void {
            if (is_string($value) && preg_match('/<[^>]*>|javascript\s*:/iu', $value)) {
                $fail('Use plain text only.');
            }
        };
        Validator::make(['definition' => $definition], [
            'definition' => ['required', 'array:template,fields'],
            'definition.template' => ['required', 'in:table_booking'],
            'definition.fields' => ['required', 'array', 'min:5', 'max:100'],
            'definition.fields.*' => ['required', 'array:id,label,type,required,archived,options'],
            'definition.fields.*.id' => ['required', 'string', 'distinct:strict', 'max:64'],
            'definition.fields.*.label' => ['required', 'string', 'min:1', 'max:120', $plainText],
            'definition.fields.*.type' => ['required', 'in:text,textarea,number,select,checkbox,datetime,integer'],
            'definition.fields.*.required' => ['required', function ($a, $v, $fail) { if (! is_bool($v)) $fail('Expected boolean.'); }],
            'definition.fields.*.archived' => ['required', function ($a, $v, $fail) { if (! is_bool($v)) $fail('Expected boolean.'); }],
            'definition.fields.*.options' => ['sometimes', 'array', 'min:1', 'max:30'],
            'definition.fields.*.options.*' => ['array:id,label'],
            'definition.fields.*.options.*.id' => ['required', 'string', 'regex:/^[a-zA-Z0-9_-]{1,64}$/'],
            'definition.fields.*.options.*.label' => ['required', 'string', 'max:120', $plainText],
        ])->validate();

        $core = array_column(BookingTemplate::definition()['fields'], null, 'id');
        $fields = $definition['fields'];
        if (! array_is_list($fields)) {
            throw ValidationException::withMessages(['definition.fields' => 'Expected a field list.']);
        }
        $byId = array_column($fields, null, 'id');
        foreach ($core as $id => $field) {
            if (! isset($byId[$id]) || $byId[$id]['type'] !== $field['type']
                || $byId[$id]['required'] !== $field['required'] || $byId[$id]['archived']) {
                throw ValidationException::withMessages(['definition.fields' => 'Core booking fields cannot be removed or change type/requirements.']);
            }
        }
        $activeCustom = 0;
        foreach ($fields as $index => $field) {
            if (! isset($core[$field['id']])) {
                if (! preg_match('/^field_[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12}$/i', $field['id'])
                    || ! in_array($field['type'], ['text', 'textarea', 'number', 'select', 'checkbox'], true)) {
                    throw ValidationException::withMessages(["definition.fields.{$index}" => 'Invalid custom field ID or type.']);
                }
                $activeCustom += $field['archived'] ? 0 : 1;
            }
            if ($field['type'] === 'select') {
                $options = $field['options'] ?? [];
                if ($options === [] || ! array_is_list($options) || count(array_unique(array_column($options, 'id'))) !== count($options)) {
                    throw ValidationException::withMessages(["definition.fields.{$index}.options" => 'Select options need unique IDs.']);
                }
            } elseif (isset($field['options'])) {
                throw ValidationException::withMessages(["definition.fields.{$index}.options" => 'Options are only allowed on select fields.']);
            }
        }
        if ($activeCustom > 20) {
            throw ValidationException::withMessages(['definition.fields' => 'Maximum 20 active custom fields.']);
        }
        foreach ($previous['fields'] ?? [] as $field) {
            if (! isset($byId[$field['id']]) || $byId[$field['id']]['type'] !== $field['type']) {
                throw ValidationException::withMessages(['definition.fields' => 'Archive existing fields; use a new ID for a different type.']);
            }
        }

        return $definition;
    }
}
