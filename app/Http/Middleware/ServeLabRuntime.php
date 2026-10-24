<?php

namespace App\Http\Middleware;

use App\Lab\Runtime\RuntimeOrigin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ServeLabRuntime
{
    public function __construct(private readonly RuntimeOrigin $origins) {}

    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower($request->getHost());
        if (! $this->origins->isReservedHost($host)) return $next($request);
        if (! $this->origins->isRuntimeHost($host) || filled(config('session.domain'))) return $this->unavailable(404);
        if (! in_array($request->method(), ['GET', 'HEAD'], true)) return $this->unavailable(405);
        if (! app()->environment('local') && ! $request->isSecure()) return $this->unavailable(404);

        $path = $request->getPathInfo();
        if ($path === '/__runtime-host__') {
            $manifest = json_decode((string) @file_get_contents(public_path('build/manifest.json')), true);
            $entry = $manifest['resources/js/lab/guest/host.js'] ?? null;
            if (! is_array($entry) || ! isset($entry['file']) || ! preg_match('~^assets/[a-zA-Z0-9_.-]+\.js$~D', $entry['file'])) return $this->unavailable(503);
            $parents = $this->origins->parentOrigins($request);
            $json = json_encode(['parent_origins' => $parents], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR);
            $script = htmlspecialchars('/build/'.$entry['file'], ENT_QUOTES, 'UTF-8');
            $html = '<!doctype html><html><head><meta charset="utf-8"><title>Lab isolated runtime</title></head><body><div id="terminal-sink"></div><script type="application/json" id="runtime-host-config">'.$json.'</script><script type="module" src="'.$script.'"></script></body></html>';
            $response = response($html)->header('Content-Type', 'text/html; charset=UTF-8');
            // The guest compiler loads its version-pinned WASM adapters from these
            // two CDNs. This applies only to the cookie-free execution origin.
            $response->headers->set('Content-Security-Policy', "default-src 'none'; script-src 'self' 'unsafe-eval' 'wasm-unsafe-eval' https://esm.sh https://cdn.jsdelivr.net; worker-src 'self' blob:; connect-src 'self' https:; frame-ancestors ".implode(' ', $parents)."; base-uri 'none'; form-action 'none'");
        } else {
            $allowed = in_array($path, ['/__worker__.js', '/__krikkit_lab_sw__.js'], true)
                || (bool) preg_match('~^/build/assets/[a-zA-Z0-9_.-]+\.(?:js|css|wasm|woff2?)$~D', $path);
            if (! $allowed) return $this->unavailable(404);
            $file = realpath(public_path(ltrim($path, '/')));
            $root = realpath(public_path());
            if ($file === false || $root === false || ! is_file($file) || ! str_starts_with($file, $root.DIRECTORY_SEPARATOR)) return $this->unavailable(404);
            $mime = match (strtolower(pathinfo($file, PATHINFO_EXTENSION))) {
                'js' => 'text/javascript; charset=UTF-8', 'css' => 'text/css; charset=UTF-8',
                'wasm' => 'application/wasm', 'woff' => 'font/woff', 'woff2' => 'font/woff2',
            };
            $response = response()->file($file, ['Content-Type' => $mime]);
            if ($path === '/__krikkit_lab_sw__.js') $response->headers->set('Service-Worker-Allowed', '/');
        }
        return $this->headers($response);
    }

    private function unavailable(int $status): Response
    {
        return $this->headers(response('Runtime resource unavailable.', $status)->header('Content-Type', 'text/plain; charset=UTF-8'));
    }

    private function headers(Response $response): Response
    {
        $response->headers->set('Cache-Control', 'no-store');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'no-referrer');
        $response->headers->set('Cross-Origin-Resource-Policy', 'cross-origin');
        $response->headers->set('Cross-Origin-Embedder-Policy', 'credentialless');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->remove('Set-Cookie');
        return $response;
    }
}
