<?php

namespace App\Ai\Support;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * Shared HTTP client for provider drivers.
 * Short connect timeout + optional no-proxy / IPv4 to avoid WSL / IDE proxy hangs
 * that surface in the browser as opaque "Failed to fetch".
 */
final class ProviderHttp
{
    /**
     * @param  array<string, mixed>  $config  Provider config slice
     */
    public static function make(array $config = [], bool $forStream = false): PendingRequest
    {
        $timeout = (int) ($config['timeout'] ?? config('ai.http.timeout', 120));
        $connect = (int) config('ai.http.connect_timeout', 15);

        $request = Http::acceptJson()
            ->asJson()
            ->timeout(max(1, $timeout))
            ->connectTimeout(max(1, $connect));

        if (config('ai.http.disable_proxy', true)) {
            $request = $request->withOptions(['proxy' => '']);
        }

        if (config('ai.http.force_ipv4', true)) {
            $request = $request->withOptions(['force_ip_resolve' => 'v4']);
        }

        if (ini_get('curl.cainfo') === '' && ini_get('openssl.cafile') === '') {
            $caBundle = 'C:\\MAMP\\bin\\apache\\bin\\cacert.pem';
            $request = $request->withOptions([
                'verify' => is_file($caBundle) ? $caBundle : false,
            ]);
        }

        return $request;
    }

    /**
     * Same client as make(), with the body left open for SSE.
     *
     * @param  array<string, mixed>  $config
     */
    public static function streaming(array $config = []): PendingRequest
    {
        return self::make($config, true)
            ->accept('text/event-stream')
            // Compressed SSE buffers whole deflate blocks before any frame can
            // be decoded — force identity so tokens stream byte-by-byte.
            ->withHeaders(['Accept-Encoding' => 'identity'])
            ->withOptions(['stream' => true]);
    }
}
