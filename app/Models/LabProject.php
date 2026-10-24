<?php

namespace App\Models;

use App\Lab\SiteWorkspace;
use App\Models\Deployment;
use App\Models\AppSecret;
use App\Support\ConstrainedUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['uuid', 'title', 'notes', 'user_id', 'kit_version', 'stack', 'project_type', 'workspace_status', 'credits_spent', 'frozen_at', 'opened_at', 'starred_at'])]
class LabProject extends Model
{
    protected static function booted(): void
    {
        static::creating(function (LabProject $project): void {
            if (blank($project->uuid)) {
                $project->uuid = ConstrainedUuid::v4();
            }

            if ($project->opened_at === null) {
                $project->opened_at = now();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(LabMessage::class)->orderBy('id');
    }

    public function publication(): HasOne
    {
        return $this->hasOne(LabPublication::class);
    }

    public function deployments(): HasMany
    {
        return $this->hasMany(Deployment::class)->orderByDesc('created_at');
    }

    public function secrets(): HasMany
    {
        return $this->hasMany(AppSecret::class);
    }

    public function githubRemote(): HasOne
    {
        return $this->hasOne(LabProjectRemote::class)->where('driver', LabProjectRemote::DRIVER_GITHUB);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'credits_spent' => 'integer',
            'frozen_at' => 'datetime',
            'opened_at' => 'datetime',
            'starred_at' => 'datetime',
        ];
    }

    public function markOpened(): void
    {
        $stamp = now();

        static::query()->whereKey($this->getKey())->toBase()->update(['opened_at' => $stamp]);

        $this->opened_at = $stamp;
    }

    public function isStarred(): bool
    {
        return $this->starred_at !== null;
    }

    public function coverUrl(): ?string
    {
        return app(SiteWorkspace::class)->coverUrl($this);
    }

    public function isFrozen(): bool
    {
        return $this->frozen_at !== null;
    }

    public function freeze(): void
    {
        if ($this->isFrozen()) {
            return;
        }

        $this->forceFill(['frozen_at' => now()])->save();
    }

    public function unfreeze(): void
    {
        if (! $this->isFrozen()) {
            return;
        }

        $this->forceFill(['frozen_at' => null])->save();
    }

    public function recordCreditSpend(int $amount): void
    {
        if ($amount <= 0) {
            return;
        }

        $this->increment('credits_spent', $amount);
    }

    /**
     * @return array{
     *     uuid: string,
     *     title: ?string,
     *     notes: ?string,
     *     messages: list<array{role: string, content: string, metadata: ?array<string, mixed>, created_at: ?string}>
     * }
     */
    public function toLabBootstrap(): array
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'notes' => $this->notes,
            'kit_version' => $this->kit_version,
            'stack' => $this->stack,
            'workspace_status' => $this->workspace_status,
            'project_type' => $this->project_type ?: 'react',
            'github' => $this->githubRemote?->toLabPayload(),
            'messages' => $this->messages
                ->map(fn (LabMessage $message) => [
                    'role' => $message->role,
                    'content' => $message->content,
                    'metadata' => is_array($message->metadata) ? $message->metadata : null,
                    'created_at' => $message->created_at?->toIso8601String(),
                ])
                ->values()
                ->all(),
        ];
    }
}
