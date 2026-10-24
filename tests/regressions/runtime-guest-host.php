<?php

putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=:memory:');
require __DIR__.'/forms-bootstrap.php';

use App\Http\Middleware\ServeLabRuntime;
use App\Lab\Runtime\RuntimeOrigin;
use Illuminate\Http\Request;

$environment = app()->environment();
$public = app()->publicPath();
$fixture = sys_get_temp_dir().'/krikkit-runtime-host-'.bin2hex(random_bytes(10));
mkdir($fixture.'/build/assets', 0700, true);
file_put_contents($fixture.'/build/manifest.json', json_encode(['resources/js/lab/guest/host.js' => ['file' => 'assets/guest-fixture.js']]));
file_put_contents($fixture.'/build/assets/guest-fixture.js', '/* trusted fixture */');
file_put_contents($fixture.'/__worker__.js', '/* trusted worker fixture */');
file_put_contents($fixture.'/__krikkit_lab_sw__.js', '/* trusted SW fixture */');
app()->usePublicPath($fixture);
app()->instance('env', 'local');
config(['lab.runtime.guest_domain' => '', 'session.domain' => null, 'app.url' => 'http://localhost:8892']);
$origins = new RuntimeOrigin;
$middleware = new ServeLabRuntime($origins);
$host = 'r-'.str_repeat('a', 32).'.runtime.localhost:8892';
$calls = 0;
$next = function () use (&$calls) { $calls++; return response('platform'); };

try {
    $response = $middleware->handle(Request::create('http://'.$host.'/__runtime-host__'), $next);
    formCheck($response->getStatusCode() === 200 && str_contains($response->getContent(), '/build/assets/guest-fixture.js'), 'Guest shell serves only trusted built entry');
    formCheck(!str_contains($response->getContent(), 'csrf') && !str_contains($response->getContent(), 'lab-bootstrap'), 'Guest shell has no owner/bootstrap/session material');
    formCheck(!$response->headers->has('Set-Cookie') && !$response->headers->has('Access-Control-Allow-Origin'), 'Guest shell sets neither cookies nor CORS');
    formCheck(str_contains($response->headers->get('Content-Security-Policy'), 'frame-ancestors http://localhost:8892 http://127.0.0.1:8892'), 'Parent origins are code-owned exact allowlist');
    formCheck(str_contains($response->headers->get('Content-Security-Policy'), "script-src 'self' 'unsafe-eval' 'wasm-unsafe-eval' https://esm.sh https://cdn.jsdelivr.net;"), 'Guest permits the pinned compiler module CDNs without arbitrary script origins');
    foreach (['/login', '/lab', '/api/hosting', '/.env', '/storage/test', '/build/manifest.json', '/build/assets/../manifest.json', '/__runtime-host__/other', '/__virtual__/sibling/5173'] as $path) {
        $denied = $middleware->handle(Request::create('http://'.$host.$path), $next);
        formCheck($denied->getStatusCode() === 404 && $denied->getContent() === 'Runtime resource unavailable.', 'Guest path cannot fall through: '.$path);
    }
    foreach (['POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'] as $method) {
        formCheck($middleware->handle(Request::create('http://'.$host.'/lab', $method), $next)->getStatusCode() === 405, 'Guest rejects '.$method);
    }
    foreach (['/__worker__.js', '/__krikkit_lab_sw__.js', '/build/assets/guest-fixture.js'] as $path) {
        $asset = $middleware->handle(Request::create('http://'.$host.$path, 'HEAD'), $next);
        formCheck($asset->getStatusCode() === 200 && str_starts_with($asset->headers->get('Content-Type'), 'text/javascript'), 'Fixed runtime asset is served: '.$path);
    }
    formCheck($calls === 0, 'No guest request reaches platform middleware');
    formCheck($middleware->handle(Request::create('http://runtime.localhost:8892/lab'), $next)->getStatusCode() === 404, 'Reserved suffix root cannot reach platform');
    formCheck($middleware->handle(Request::create('http://r-invalid.runtime.localhost:8892/lab'), $next)->getStatusCode() === 404, 'Malformed session host is denied');
    formCheck($middleware->handle(Request::create('http://localhost:8892/lab'), $next)->getContent() === 'platform' && $calls === 1, 'Platform host retains normal routing');
    $local = $origins->configuration(Request::create('http://localhost:8892/lab'));
    formCheck($local === ['host_suffix' => '.runtime.localhost', 'scheme' => 'http:', 'port' => 8892], 'Local guest configuration uses distinct runtime hostname');
    config(['session.domain' => '.localhost']);
    formCheck($origins->configuration(Request::create('http://localhost:8892/lab')) === null, 'Domain cookies disable runtime configuration');
    formCheck($middleware->handle(Request::create('http://'.$host.'/__runtime-host__'), $next)->getStatusCode() === 404, 'Domain cookies fail guest serving closed');
    app()->instance('env', 'production');
    config(['session.domain' => null, 'app.url' => 'https://app.example.com', 'lab.runtime.guest_domain' => '']);
    formCheck($origins->configuration(Request::create('https://app.example.com/lab')) === null, 'Production requires configured dedicated domain');
    foreach (['app.example.com', 'guest.app.example.com', 'example.com', 'guest.example.com', 'invalid/domain'] as $domain) {
        config(['lab.runtime.guest_domain' => $domain]);
        formCheck($origins->suffix() === null, 'Invalid/shared domain denied: '.$domain);
    }
    config(['lab.runtime.guest_domain' => 'guest.example.net']);
    formCheck($origins->configuration(Request::create('https://app.example.com/lab')) === ['host_suffix' => '.guest.example.net', 'scheme' => 'https:', 'port' => null], 'Separate HTTPS guest domain supported');
    formCheck($origins->configuration(Request::create('http://app.example.com/lab')) === null, 'Production runtime requires HTTPS');
    formCheck($origins->isRuntimeHost('r-'.str_repeat('b', 32).'.guest.example.net'), 'Guest sessions use separate hostnames');
    $proxy = new \Illuminate\Http\Middleware\TrustProxies;
    \Illuminate\Http\Middleware\TrustProxies::at(['127.0.0.1']);
    $forwarded = Request::create('http://r-'.str_repeat('b', 32).'.guest.example.net/__runtime-host__', 'GET', [], [], [], ['REMOTE_ADDR' => '127.0.0.1', 'HTTP_X_FORWARDED_PROTO' => 'https']);
    $proxied = $proxy->handle($forwarded, fn ($trusted) => $middleware->handle($trusted, $next));
    formCheck($proxied->getStatusCode() === 200, 'Trusted TLS termination reaches guest shell before sessions');
    $kernelMiddleware = app(\Illuminate\Contracts\Http\Kernel::class)->getGlobalMiddleware();
    formCheck(array_search(\Illuminate\Http\Middleware\TrustProxies::class, $kernelMiddleware, true) < array_search(ServeLabRuntime::class, $kernelMiddleware, true), 'Configured proxy middleware precedes guest routing');
    formResult('dedicated runtime host boundary');
} finally {
    app()->usePublicPath($public);
    app()->instance('env', $environment);
    foreach (['build/manifest.json', 'build/assets/guest-fixture.js', '__worker__.js', '__krikkit_lab_sw__.js'] as $file) unlink($fixture.'/'.$file);
    foreach (['build/assets', 'build', ''] as $directory) rmdir($fixture.($directory ? '/'.$directory : ''));
}
