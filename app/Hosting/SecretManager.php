<?php

namespace App\Hosting;

use App\Models\AppSecret;
use App\Models\LabProject;
use Illuminate\Support\Facades\Crypt;

/**
 * Manages environment variables / secrets per project.
 *
 * Adapted from Reflex's secrets.py:
 *   - list: show secret keys (never values)
 *   - update: set key=value pairs
 *   - delete: remove a secret by key
 *   - resolve: merge stored secrets with inline env for deployment
 *
 * Values are encrypted at rest using Laravel's Crypt facade.
 */
final class SecretManager
{
    /**
     * List secret keys for a project (values are never exposed).
     *
     * @return list<string>
     */
    public function keys(LabProject $project): array
    {
        return AppSecret::query()
            ->where('lab_project_id', $project->id)
            ->orderBy('key')
            ->pluck('key')
            ->all();
    }

    /**
     * Get all secrets (decrypted) for a project.
     * Internal use only — never expose in API responses.
     *
     * @return array<string, string>
     */
    public function all(LabProject $project): array
    {
        $secrets = AppSecret::query()
            ->where('lab_project_id', $project->id)
            ->get();

        $result = [];
        foreach ($secrets as $secret) {
            $result[$secret->key] = $this->decrypt($secret->value);
        }

        return $result;
    }

    /**
     * Set or update secrets for a project.
     *
     * @param  array<string, string>  $vars  key => value pairs
     * @return list<string>  The keys that were updated
     */
    public function update(LabProject $project, array $vars): array
    {
        $updated = [];

        foreach ($vars as $key => $value) {
            $key = $this->normalizeKey($key);
            if ($key === '') {
                continue;
            }

            AppSecret::query()->updateOrCreate(
                ['lab_project_id' => $project->id, 'key' => $key],
                ['value' => $this->encrypt((string) $value)],
            );

            $updated[] = $key;
        }

        return $updated;
    }

    /**
     * Delete a secret by key.
     */
    public function delete(LabProject $project, string $key): bool
    {
        $key = $this->normalizeKey($key);

        return AppSecret::query()
            ->where('lab_project_id', $project->id)
            ->where('key', $key)
            ->delete() > 0;
    }

    /**
     * Delete all secrets for a project.
     */
    public function deleteAll(LabProject $project): int
    {
        return AppSecret::query()
            ->where('lab_project_id', $project->id)
            ->delete();
    }

    /**
     * Resolve the full environment for a deployment.
     *
     * Merges stored secrets with any inline env vars passed at deploy time.
     * Inline vars take precedence (like Reflex's --env overriding --envfile).
     *
     * @param  array<string, string>  $inline  Env vars passed with the deploy request
     * @return array<string, string>
     */
    public function resolveForDeployment(LabProject $project, array $inline = []): array
    {
        $stored = $this->all($project);

        // Normalize inline keys
        $normalizedInline = [];
        foreach ($inline as $key => $value) {
            $normalized = $this->normalizeKey($key);
            if ($normalized !== '') {
                $normalizedInline[$normalized] = (string) $value;
            }
        }

        return array_merge($stored, $normalizedInline);
    }

    /**
     * Parse a .env file content into key => value pairs.
     *
     * @return array<string, string>
     */
    public function parseEnvFile(string $content): array
    {
        $vars = [];
        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments and empty lines
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            // Parse KEY=VALUE
            $pos = strpos($line, '=');
            if ($pos === false) {
                continue;
            }

            $key = $this->normalizeKey(substr($line, 0, $pos));
            $value = trim(substr($line, $pos + 1));

            // Strip surrounding quotes
            if (strlen($value) >= 2) {
                $first = $value[0];
                $last = $value[strlen($value) - 1];
                if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                    $value = substr($value, 1, -1);
                }
            }

            if ($key !== '') {
                $vars[$key] = $value;
            }
        }

        return $vars;
    }

    /**
     * Normalize a secret key: uppercase, alphanumeric + underscore only.
     */
    private function normalizeKey(string $key): string
    {
        $key = strtoupper(trim($key));
        $key = preg_replace('/[^A-Z0-9_]/', '_', $key) ?? '';

        return trim($key, '_');
    }

    private function encrypt(string $value): string
    {
        return Crypt::encryptString($value);
    }

    private function decrypt(string $value): string
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Throwable) {
            return '';
        }
    }
}
