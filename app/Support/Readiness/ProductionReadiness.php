<?php

namespace App\Support\Readiness;

use Illuminate\Support\Arr;

final class ProductionReadiness
{
    /**
     * @return array{ok: bool, checks: list<array{key: string, status: string, severity: string, message: string}>, summary: array{pass: int, warning: int, fail: int}}
     */
    public function inspect(): array
    {
        $checks = [
            $this->phpVersion(),
            $this->applicationKey(),
            $this->applicationUrl(),
            $this->debugMode(),
            $this->requiredExtensions(),
            $this->defaultModel(),
            $this->providerCredential(),
            $this->demoMode(),
            $this->queue(),
            $this->cache(),
            $this->session(),
            $this->mail(),
            $this->storage(),
            $this->publishing(),
            $this->businessWrites(),
            $this->hosting(),
            $this->credentialArtifacts(),
        ];

        $summary = [
            'pass' => count(array_filter($checks, static fn (array $check): bool => $check['status'] === 'pass')),
            'warning' => count(array_filter($checks, static fn (array $check): bool => $check['status'] === 'warning')),
            'fail' => count(array_filter($checks, static fn (array $check): bool => $check['status'] === 'fail')),
        ];

        return [
            'ok' => $summary['fail'] === 0,
            'checks' => $checks,
            'summary' => $summary,
        ];
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function phpVersion(): array
    {
        $required = '8.4.1';

        return version_compare(PHP_VERSION, $required, '>=')
            ? $this->pass('runtime.php', 'PHP '.PHP_VERSION.' satisfies the locked dependency requirement.')
            : $this->fail('runtime.php', 'PHP '.PHP_VERSION.' is below the required '.$required.'.');
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function applicationKey(): array
    {
        $key = trim((string) config('app.key', ''));
        if ($key === '') {
            return $this->fail('app.key', 'APP_KEY is missing.');
        }

        $decoded = str_starts_with($key, 'base64:')
            ? base64_decode(substr($key, 7), true)
            : $key;

        return is_string($decoded) && strlen($decoded) === 32
            ? $this->pass('app.key', 'Application key is configured with a valid AES-256 key length.')
            : $this->fail('app.key', 'APP_KEY must decode to exactly 32 bytes for the configured cipher.');
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function applicationUrl(): array
    {
        $url = trim((string) config('app.url', ''));
        $environment = (string) config('app.env', 'production');

        $parsed = $url === '' ? false : parse_url($url);
        if (! is_array($parsed) || empty($parsed['host'])) {
            return $this->fail('app.url', 'APP_URL is missing.');
        }

        if ($environment === 'production' && (($parsed['scheme'] ?? '') !== 'https')) {
            return $this->fail('app.url', 'Production APP_URL must use HTTPS.');
        }

        return $this->pass('app.url', 'APP_URL is configured for '.$environment.'.');
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function debugMode(): array
    {
        if ((bool) config('app.debug', false) && (string) config('app.env') === 'production') {
            return $this->fail('app.debug', 'APP_DEBUG must be false in production.');
        }

        return (bool) config('app.debug', false)
            ? $this->warning('app.debug', 'APP_DEBUG is enabled outside production.')
            : $this->pass('app.debug', 'Debug mode is disabled.');
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function requiredExtensions(): array
    {
        $required = ['curl', 'fileinfo', 'mbstring', 'openssl', 'PDO'];
        $missing = array_values(array_filter($required, static fn (string $extension): bool => ! extension_loaded($extension)));

        return $missing === []
            ? $this->pass('runtime.extensions', 'Required PHP extensions are loaded.')
            : $this->fail('runtime.extensions', 'Missing PHP extensions: '.implode(', ', $missing).'.');
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function defaultModel(): array
    {
        $model = (string) config('ai.default_model', '');
        $catalog = (array) config('ai.models', []);

        if ($model === '' || ! array_key_exists($model, $catalog)) {
            return $this->fail('ai.default_model', 'AI_DEFAULT_MODEL does not exist in the configured model catalog.');
        }

        $provider = (string) Arr::get($catalog[$model], 'provider', '');
        $providers = (array) config('ai.providers', []);

        return array_key_exists($provider, $providers)
            ? $this->pass('ai.default_model', 'Default AI model is present with provider '.$provider.'.')
            : $this->fail('ai.default_model', 'Default AI model references an unknown provider.');
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function demoMode(): array
    {
        return (bool) config('ai.demo_when_unkeyed', false)
            ? $this->fail('ai.demo_when_unkeyed', 'Demo replies are enabled; production must fail closed when no provider key exists.')
            : $this->pass('ai.demo_when_unkeyed', 'Unkeyed AI providers fail closed.');
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function providerCredential(): array
    {
        $model = (string) config('ai.default_model', '');
        $models = (array) config('ai.models', []);
        $provider = (string) (($models[$model]['provider'] ?? ''));
        $key = (string) config('ai.providers.'.$provider.'.api_key', '');

        return filled($key)
            ? $this->pass('ai.provider_key', 'Default AI provider has an environment key.')
            : $this->warning('ai.provider_key', 'Default AI provider has no environment key; this env-only check cannot verify an encrypted workspace credential.');
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function queue(): array
    {
        $driver = (string) config('queue.default', '');

        if ($driver === 'sync') {
            return (string) config('app.env') === 'production'
                ? $this->fail('runtime.queue', 'QUEUE_CONNECTION=sync cannot process long-running production work.')
                : $this->warning('runtime.queue', 'QUEUE_CONNECTION=sync is suitable only for isolated local checks.');
        }

        return $this->pass('runtime.queue', 'Queue driver is '.$driver.'.');
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function cache(): array
    {
        $store = (string) config('cache.default', '');

        return in_array($store, ['array', 'null'], true)
            ? $this->warning('runtime.cache', 'Cache store '.$store.' is not shared across workers.')
            : $this->pass('runtime.cache', 'Cache store is '.$store.'.');
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function session(): array
    {
        $driver = (string) config('session.driver', '');

        if ($driver === 'array') {
            return (string) config('app.env') === 'production'
                ? $this->fail('runtime.session', 'SESSION_DRIVER=array cannot persist authenticated sessions.')
                : $this->warning('runtime.session', 'SESSION_DRIVER=array is suitable only for isolated local checks.');
        }

        return $this->pass('runtime.session', 'Session driver is '.$driver.'.');
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function mail(): array
    {
        $mailer = (string) config('mail.default', '');

        return in_array($mailer, ['log', 'array'], true)
            ? $this->warning('runtime.mail', 'MAIL_MAILER='.$mailer.' does not deliver user mail.')
            : $this->pass('runtime.mail', 'Mail driver is '.$mailer.'.');
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function storage(): array
    {
        $paths = [storage_path(), base_path('bootstrap/cache')];
        $missing = array_values(array_filter($paths, static fn (string $path): bool => ! is_writable($path)));

        return $missing === []
            ? $this->pass('runtime.storage', 'Storage and bootstrap/cache are writable.')
            : $this->fail('runtime.storage', 'Directories are not writable: '.implode(', ', $missing).'.');
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function publishing(): array
    {
        $environment = (string) config('app.env', 'production');
        $domain = trim((string) config('lab.publish_domain', ''));

        if ($environment === 'production' && $domain === '') {
            return $this->warning('publish.domain', 'LAB_PUBLISH_DOMAIN is not configured; public publishing cannot be verified.');
        }

        return $this->pass('publish.domain', 'Publish domain configuration is present or local mode is active.');
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function businessWrites(): array
    {
        return config('lab-modules.business_writes_enabled') === true
            ? $this->warning('modules.business_writes', 'Business writes are enabled; external isolation acceptance is required before launch.')
            : $this->warning('modules.business_writes', 'Business writes remain closed until Supabase, RLS, Storage and browser gates pass.');
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function hosting(): array
    {
        return config('hosting.enabled') === true
            ? $this->warning('hosting.experimental', 'Experimental hosting is enabled; verify migration, concurrency and origin gates.')
            : $this->pass('hosting.experimental', 'Experimental hosting is disabled.');
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function credentialArtifacts(): array
    {
        $artifacts = glob(base_path('*.pem')) ?: [];
        if (is_file(base_path('SERVER-CREDENTIALS.md'))) {
            $artifacts[] = base_path('SERVER-CREDENTIALS.md');
        }

        return $artifacts === []
            ? $this->pass('security.credential_artifacts', 'No credential artifacts were found at the project root.')
            : $this->warning('security.credential_artifacts', 'Credential artifacts exist in the project root; remove them from deployment packages and rotate exposed credentials.');
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function pass(string $key, string $message): array
    {
        return ['key' => $key, 'status' => 'pass', 'severity' => 'info', 'message' => $message];
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function warning(string $key, string $message): array
    {
        return ['key' => $key, 'status' => 'warning', 'severity' => 'warning', 'message' => $message];
    }

    /** @return array{key: string, status: string, severity: string, message: string} */
    private function fail(string $key, string $message): array
    {
        return ['key' => $key, 'status' => 'fail', 'severity' => 'critical', 'message' => $message];
    }
}
