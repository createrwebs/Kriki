<?php

namespace App\Lab\Forms;

use App\Models\LabForm;
use App\Models\LabProject;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class FormProposal
{
    public function __construct(private readonly FormDefinitions $forms, private readonly FormDefinition $rules) {}

    public function propose(User $owner, LabProject $project, LabForm $form, int $expectedVersion, array $operations): array
    {
        $this->forms->assertAccess($owner, $project, $form, true);
        $latest = $this->forms->payload($form);
        abort_unless($latest['latest_version'] === $expectedVersion, 409, 'The form changed. Reload before proposing changes.');
        Validator::make(['operations' => $operations], [
            'operations' => ['present', 'array', 'max:100'],
            'operations.*' => ['required', 'array:op,id,field,label,required,ids'],
            'operations.*.op' => ['required', 'in:add,label,required,reorder,archive'],
            'operations.*.ids' => ['sometimes', 'array'],
            'operations.*.ids.*' => ['required', 'string', 'max:64'],
        ])->validate();
        $previous = $latest['definition'];
        $next = $previous;
        $summary = [];
        foreach ($operations as $operation) {
            $op = $operation['op'];
            if ($op === 'add') {
                Validator::make($operation, ['field' => ['required', 'array:id,label,type,required,archived,options']])->validate();
                $field = $operation['field'];
                $field['id'] ??= 'field_'.Str::uuid();
                $field['required'] ??= false;
                $field['archived'] ??= false;
                $next['fields'][] = $field;
                $summary[] = 'เพิ่มช่อง: '.($field['label'] ?? '');
            } elseif ($op === 'reorder') {
                $ids = $operation['ids'] ?? null;
                $currentIds = array_column($next['fields'], 'id');
                if (! is_array($ids) || count($ids) !== count($currentIds) || count(array_unique($ids, SORT_REGULAR)) !== count($ids) || array_diff($currentIds, $ids) || array_diff($ids, $currentIds)) {
                    throw ValidationException::withMessages(['operations' => 'Reorder must contain every field ID once.']);
                }
                $map = array_column($next['fields'], null, 'id');
                $next['fields'] = array_map(fn ($id) => $map[$id], $ids);
                $summary[] = 'จัดลำดับช่อง';
            } else {
                $index = array_search($operation['id'] ?? null, array_column($next['fields'], 'id'), true);
                if ($index === false) throw ValidationException::withMessages(['operations' => 'Unknown field ID.']);
                $key = $op === 'archive' ? 'archived' : $op;
                $next['fields'][$index][$key] = $op === 'archive' ? true : ($operation[$op] ?? null);
                $summary[] = ($op === 'archive' ? 'ซ่อนช่อง: ' : 'แก้ช่อง: ').$next['fields'][$index]['label'];
            }
            $next = $this->rules->validate($next, $previous);
        }
        return ['form_uuid' => $form->uuid, 'base_version' => $expectedVersion, 'definition' => $next, 'summary' => $summary];
    }
}
