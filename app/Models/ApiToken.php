<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * API token for programmatic access to the hosting API.
 *
 * Adapted from Reflex's auth.py token_command:
 *   - create-token: generate a named token with expiry
 *   - token --print: show the stored token
 *   - token --clear: revoke
 *
 * Tokens are hashed at rest; the plaintext is shown once on creation.
 */
#[Fillable([
    'user_id',
    'name',
    'token_hash',
    'expires_at',
    'last_used_at',
    'abilities',
])]
#[Hidden(['token_hash'])]
class ApiToken extends Model
{
    /** @var list<string> */
    public const DEFAULT_ABILITIES = ['deploy', 'apps:read', 'secrets:write', 'logs:read'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'last_used_at' => 'datetime',
            'abilities' => 'array',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function can(string $ability): bool
    {
        $abilities = $this->abilities ?? [];

        return in_array('*', $abilities, true) || in_array($ability, $abilities, true);
    }

    /**
     * Create a new token, returning the plaintext (shown once).
     *
     * @return array{model: self, plaintext: string}
     */
    public static function generate(User $user, string $name, ?int $expiresInDays = 90, array $abilities = []): array
    {
        $plaintext = 'krik_'.Str::random(48);

        $model = static::query()->create([
            'user_id' => $user->id,
            'name' => $name,
            'token_hash' => hash('sha256', $plaintext),
            'expires_at' => $expiresInDays ? now()->addDays($expiresInDays) : null,
            'abilities' => $abilities ?: self::DEFAULT_ABILITIES,
        ]);

        return ['model' => $model, 'plaintext' => $plaintext];
    }

    /**
     * Find a token by its plaintext value.
     */
    public static function findByPlaintext(string $plaintext): ?self
    {
        $hash = hash('sha256', $plaintext);

        return static::query()
            ->where('token_hash', $hash)
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->first();
    }

    /**
     * Fingerprint for display (never show the full token).
     */
    public function fingerprint(): string
    {
        return 'sha256:'.substr($this->token_hash, 0, 16);
    }

    /**
     * @return array<string, mixed>
     */
    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'fingerprint' => $this->fingerprint(),
            'abilities' => $this->abilities,
            'expires_at' => $this->expires_at?->toIso8601String(),
            'last_used_at' => $this->last_used_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
