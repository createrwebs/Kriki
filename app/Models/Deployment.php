<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A single deployment of a Lab project.
 *
 * Adapted from Reflex's deployment model:
 *   - versioned (v1, v2, v3…)
 *   - strategy-aware (immediate, rolling, bluegreen, canary)
 *   - provider-agnostic (local, cloud, gcp)
 *   - rollback-capable (tracks rollback_from / rollback_to)
 */
#[Fillable([
    'lab_project_id',
    'user_id',
    'provider',
    'provider_deployment_id',
    'version',
    'strategy',
    'region',
    'vmtype',
    'hostname',
    'min_instances',
    'max_instances',
    'description',
    'status',
    'url',
    'artifact_path',
    'artifact_size',
    'last_error',
    'rollback_from_deployment_id',
    'rollback_to_deployment_id',
    'deployed_at',
    'failed_at',
])]
class Deployment extends Model
{
    // --- Statuses (mirrors Reflex deployment lifecycle) ---
    public const STATUS_PENDING = 'pending';
    public const STATUS_BUILDING = 'building';
    public const STATUS_LIVE = 'live';
    public const STATUS_FAILED = 'failed';
    public const STATUS_STOPPED = 'stopped';
    public const STATUS_SUPERSEDED = 'superseded';

    // --- Strategies (from Reflex deploy.py --strategy) ---
    public const STRATEGY_IMMEDIATE = 'immediate';
    public const STRATEGY_ROLLING = 'rolling';
    public const STRATEGY_BLUEGREEN = 'bluegreen';
    public const STRATEGY_CANARY = 'canary';

    public const STRATEGIES = [
        self::STRATEGY_IMMEDIATE,
        self::STRATEGY_ROLLING,
        self::STRATEGY_BLUEGREEN,
        self::STRATEGY_CANARY,
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(LabProject::class, 'lab_project_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(DeploymentLog::class);
    }

    public function rollbackFrom(): BelongsTo
    {
        return $this->belongsTo(self::class, 'rollback_from_deployment_id');
    }

    public function rollbackTo(): BelongsTo
    {
        return $this->belongsTo(self::class, 'rollback_to_deployment_id');
    }

    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'min_instances' => 'integer',
            'max_instances' => 'integer',
            'artifact_size' => 'integer',
            'deployed_at' => 'datetime',
            'failed_at' => 'datetime',
        ];
    }

    public function isLive(): bool
    {
        return $this->status === self::STATUS_LIVE;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function canRollback(): bool
    {
        return $this->provider !== 'local'
            && in_array($this->status, [self::STATUS_LIVE, self::STATUS_SUPERSEDED], true);
    }

    /**
     * Serialize for API responses.
     *
     * @return array<string, mixed>
     */
    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'version' => $this->version,
            'provider' => $this->provider,
            'strategy' => $this->strategy,
            'region' => $this->region,
            'vmtype' => $this->vmtype,
            'hostname' => $this->hostname,
            'status' => $this->status,
            'url' => $this->url,
            'description' => $this->description,
            'artifact_size' => $this->artifact_size,
            'last_error' => $this->last_error,
            'can_rollback' => $this->canRollback(),
            'deployed_at' => $this->deployed_at?->toIso8601String(),
            'failed_at' => $this->failed_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'user' => $this->user?->only(['id', 'name', 'email']),
        ];
    }
}
