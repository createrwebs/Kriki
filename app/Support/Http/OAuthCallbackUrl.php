<?php

namespace App\Support\Http;

final class OAuthCallbackUrl
{
    public static function isAllowed(string $url): bool
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $parts = parse_url($url);
        if (! is_array($parts) || isset($parts['user']) || isset($parts['pass']) || isset($parts['fragment'])) {
            return false;
        }

        $scheme = strtolower($parts['scheme'] ?? '');
        if ($scheme === 'https') {
            return true;
        }

        return app()->environment('local')
            && $scheme === 'http'
            && in_array(strtolower($parts['host'] ?? ''), ['localhost', '127.0.0.1', '[::1]'], true);
    }
}
