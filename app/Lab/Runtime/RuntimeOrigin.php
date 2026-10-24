<?php

namespace App\Lab\Runtime;

use Illuminate\Http\Request;

/** Runtime hosts carry no platform session and never route into the application. */
final class RuntimeOrigin
{
    public function suffix(): ?string
    {
        $configured = strtolower(trim((string) config('lab.runtime.guest_domain', '')));
        if (app()->environment('local') && $configured === '') return 'runtime.localhost';
        if (! preg_match('/^(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z]{2,}$/D', $configured)) return null;
        $platform = strtolower((string) parse_url(config('app.url'), PHP_URL_HOST));
        if ($platform === '' || $configured === $platform
            || str_ends_with($configured, '.'.$platform) || str_ends_with($platform, '.'.$configured)) return null;
        // Conservative site boundary without guessing public suffix rules. Equal
        // final two labels are denied (including sibling hosts and *.co.uk).
        // This may reject some otherwise separate sites; it never permits a
        // same registrable domain based on an incomplete suffix list.
        if (implode('.', array_slice(explode('.', $configured), -2))
            === implode('.', array_slice(explode('.', $platform), -2))) return null;
        // Session cookies scoped to a parent domain are not permitted here.
        if (filled(config('session.domain'))) return null;
        return $configured;
    }

    public function isRuntimeHost(string $host): bool
    {
        $suffix = $this->suffix();
        return $suffix !== null && (bool) preg_match('/^r-[a-f0-9]{32}\.'.preg_quote($suffix, '/').'$/D', strtolower($host));
    }

    public function isReservedHost(string $host): bool
    {
        if (strtolower($host) === strtolower((string) parse_url(config('app.url'), PHP_URL_HOST))) return false;
        $configured = strtolower(trim((string) config('lab.runtime.guest_domain', '')));
        $suffix = $configured !== '' ? $configured : (app()->environment('local') ? 'runtime.localhost' : '');
        return $suffix !== '' && ($host === $suffix || str_ends_with(strtolower($host), '.'.$suffix));
    }

    public function configuration(Request $request): ?array
    {
        $suffix = $this->suffix();
        if ($suffix === null || filled(config('session.domain'))) return null;
        $local = app()->environment('local') && $suffix === 'runtime.localhost';
        if ($local && ! in_array($request->getHost(), ['localhost', '127.0.0.1'], true)) return null;
        if (! $local && ! $request->isSecure()) return null;
        return [
            'host_suffix' => '.'.$suffix,
            'scheme' => $local ? 'http:' : 'https:',
            'port' => $local ? $request->getPort() : null,
        ];
    }

    public function parentOrigins(Request $request): array
    {
        if (app()->environment('local') && $this->suffix() === 'runtime.localhost') {
            return ['http://localhost:'.$request->getPort(), 'http://127.0.0.1:'.$request->getPort()];
        }
        $url = parse_url(config('app.url'));
        if (($url['scheme'] ?? '') !== 'https' || empty($url['host'])) return [];
        return ['https://'.$url['host'].(isset($url['port']) ? ':'.$url['port'] : '')];
    }
}
