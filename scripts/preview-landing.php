<?php

// Disposable presentation server only; never use this router for deployment.
if (PHP_SAPI !== 'cli-server') {
    http_response_code(404);
    exit;
}
if (! in_array($_SERVER['REQUEST_METHOD'], ['GET', 'HEAD'], true)) {
    http_response_code(405);
    header('Allow: GET, HEAD');
    echo 'Read-only presentation preview. Use the configured app for account actions.';
    return;
}
$publicRoot = realpath(__DIR__.'/../public');
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$asset = realpath($publicRoot.$path);
if ($asset !== false && str_starts_with($asset, $publicRoot.DIRECTORY_SEPARATOR)
    && is_file($asset) && in_array(strtolower(pathinfo($asset, PATHINFO_EXTENSION)), ['js', 'mjs', 'css', 'svg', 'png', 'jpg', 'jpeg', 'webp', 'ico', 'woff', 'woff2', 'ttf', 'map'], true)) {
    return false;
}
foreach ([
    'DB_CONNECTION' => 'sqlite', 'DB_DATABASE' => ':memory:', 'DB_URL' => '',
    'APP_ENV' => 'local', 'APP_NAME' => 'Krikkit', 'APP_DEBUG' => 'false',
    'APP_KEY' => 'base64:'.base64_encode(hash('sha256', 'krikkit-read-only-presentation-fixture-not-for-deployment', true)),
    'APP_URL' => 'http://127.0.0.1:'.($_SERVER['SERVER_PORT'] ?? 8891),
    'SESSION_DRIVER' => 'array', 'CACHE_STORE' => 'array', 'LOG_CHANNEL' => 'stderr',
    // Never inherit a real database/key from an operator's cached configuration.
    'APP_CONFIG_CACHE' => sys_get_temp_dir().'/krikkit-preview-no-config-'.bin2hex(random_bytes(12)).'.php',
    'APP_ROUTES_CACHE' => sys_get_temp_dir().'/krikkit-preview-no-routes-'.bin2hex(random_bytes(12)).'.php',
] as $key => $value) {
    putenv($key.'='.$value);
    $_ENV[$key] = $_SERVER[$key] = $value;
}
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->loadEnvironmentFrom('.env.local-preview-nonexistent');
$app->afterBootstrapping(Illuminate\Foundation\Bootstrap\BootProviders::class, function (): void {
    Illuminate\Support\Facades\View::share('localPresentationPreview', true);
});
$app->handleRequest(Illuminate\Http\Request::capture());
