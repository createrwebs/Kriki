<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A single log line from a deployment.
 *
 * Adapted from Reflex's apps.py logs command:
 *   - timestamp [LEVEL] message
 *   - Supports streaming (follow mode)
 */
#[Fillable([
    'deployment_id',
    'level',
    'message',
    'timestamp',
])]
class DeploymentLog extends Model
{
    public const UPDATED_AT = null;

    public function deployment(): BelongsTo
    {
        return $this->belongsTo(Deployment::class);
    }

    protected function casts(): array
    {
        return [
            'timestamp' => 'datetime',
        ];
    }

    /**
     * Format as a log line (like Reflex's _format_log_record).
     */
    public function formatted(): string
    {
        $level = strtoupper($this->level ?? 'info');
        $ts = $this->timestamp?->format('Y-m-d H:i:s') ?? '';

        return "{$ts} [{$level}] {$this->message}";
    }

    /**
     * @return array<string, mixed>
     */
    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'level' => $this->level,
            'message' => $this->message,
            'timestamp' => $this->timestamp?->toIso8601String(),
            'formatted' => $this->formatted(),
        ];
    }
}
