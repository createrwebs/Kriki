<?php

namespace App\Lab\Github;

use InvalidArgumentException;

/** Known credential material must not enter an imported workspace or AI snapshot. */
final class GithubImportContentPolicy
{
    public static function assertSafe(string $path, string $body): void
    {
        $leaf = strtolower(basename($path));
        $liveEnv = ($leaf === '.env' || str_starts_with($leaf, '.env.'))
            && !in_array($leaf, ['.env.example', '.env.sample', '.env.template'], true);
        $container = $liveEnv || in_array($leaf, ['credentials.json', 'service-account.json', 'service_account.json', '.netrc', '.git-credentials', '.npmrc', '.pypirc'], true)
            || preg_match('/\.(?:pem|key|p12|pfx)$/iD', $leaf) || preg_match('/^id_(?:rsa|dsa|ecdsa|ed25519)$/D', $leaf);
        if ($container) self::reject();
        if (!mb_check_encoding($body, 'UTF-8')) return; // Binary assets remain inert disk source.
        foreach ([
            '/-----BEGIN (?:RSA |EC |DSA |OPENSSH |ENCRYPTED )?PRIVATE KEY-----/',
            '/\b(?:gh[pousr]_[A-Za-z0-9]{30,}|github_pat_[A-Za-z0-9_]{40,}|sb_secret_[A-Za-z0-9_-]{20,}|sk_live_[A-Za-z0-9]{20,})\b/',
            '/\b(?:SUPABASE_SERVICE_ROLE_KEY|SUPABASE_SECRET_KEY|AWS_SECRET_ACCESS_KEY|service_role_key|private_key|client_secret)[\x22\x27]?\s*(?:=>|:|=)\s*[\x22\x27]?[A-Za-z0-9_+\/=-]{24,}/i',
        ] as $pattern) {
            $matched = preg_match($pattern, $body);
            if ($matched === false) throw new InvalidArgumentException('Source credential inspection could not complete. No source was changed.');
            if ($matched === 1) self::reject();
        }
        $matched = preg_match_all('/\b[A-Za-z0-9_-]{16,}\.([A-Za-z0-9_-]{16,})\.[A-Za-z0-9_-]{16,}\b/', $body, $tokens);
        if ($matched === false) throw new InvalidArgumentException('Source credential inspection could not complete. No source was changed.');
        foreach ($tokens[1] as $payload) {
            $decoded = base64_decode(strtr($payload, '-_', '+/'), true);
            $claims = json_decode($decoded === false ? '' : $decoded, true);
            if (is_array($claims) && in_array($claims['role'] ?? null, ['service_role', 'supabase_admin'], true)) self::reject();
        }
    }

    private static function reject(): never
    {
        throw new InvalidArgumentException('Import blocked: selected source contains credential material. Remove live credentials/secret files and review the source before retrying. Existing workspace source was preserved.');
    }
}
