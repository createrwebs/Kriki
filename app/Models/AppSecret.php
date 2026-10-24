<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Encrypted environment variable / secret for a Lab project.
 *
 * Adapted from Reflex's secrets.py:
 *   - Keys are normalized (UPPER_SNAKE_CASE)
 *   - Values are encrypted at rest
 *   - Never exposed in API responses (keys only)
 */
#[Fillable([
    'lab_project_id',
    'key',
    'value',
])]
#[Hidden(['value'])]
class AppSecret extends Model
{
    public function project(): BelongsTo
    {
        return $this->belongsTo(LabProject::class, 'lab_project_id');
    }

    protected function casts(): array
    {
        return [
            'value' => 'encrypted',
        ];
    }
}
