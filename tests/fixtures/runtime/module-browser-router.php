<?php

// Opt-in loopback test server only. Not loaded by public/index.php or application routes.
if (PHP_SAPI !== 'cli-server' || !in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1','::1'], true)) { http_response_code(403); exit; }
$repo = dirname(__DIR__, 3);
$fixture = json_decode(file_get_contents($repo.'/.agent-work/qa/module-browser-config.json'), true);
if (!is_array($fixture) || !str_starts_with(str_replace('\\','/',$fixture['database']), str_replace('\\','/',$repo).'/.agent-work/qa/module-browser-')) { http_response_code(503); exit; }
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($path === '/brand/createai-logo.jpg') return false;
if (preg_match('~^/build/assets/[a-zA-Z0-9_.-]+\.(?:css|woff2?|ttf)$~D', $path ?? '') && is_file($repo.'/public'.$path)) return false;
if (str_starts_with($_SERVER['HTTP_HOST'] ?? '', 'fictional.localhost:')) {
    $asset = preg_match('~^/assets/[a-zA-Z0-9_.-]+\.(?:js|css)$~D', $path);
    // Match the production publication's extensionless SPA fallback for direct routes.
    $page = is_string($path) && !str_contains($path, '..') && !str_contains($path, '\\') && !str_contains(basename($path), '.');
    if ($asset || $page) {
        $file = $fixture['source'].'/dist'.($asset ? $path : '/index.html');
        if (is_file($file)) { header('Content-Type: '.match (pathinfo($file, PATHINFO_EXTENSION)) { 'js' => 'text/javascript', 'css' => 'text/css', default => 'text/html; charset=UTF-8' }); readfile($file); exit; }
    }
    http_response_code(404); exit;
}
if (in_array($path, ['/__qa-owner.js','/__qa-owner.css'], true)) {
    $file = $repo.'/.agent-work/qa'.($path === '/__qa-owner.js' ? '/module-owner.js' : '/module-owner.css');
    header('Content-Type: '.($path === '/__qa-owner.js' ? 'text/javascript' : 'text/css')); readfile($file); exit;
}
putenv('DB_CONNECTION=sqlite'); putenv('DB_DATABASE='.$fixture['database']);
putenv('APP_URL=http://localhost:8896'); putenv('APP_ENV=local'); putenv('CACHE_STORE=array'); putenv('SESSION_DRIVER=file'); putenv('LOG_CHANNEL=null');
require $repo.'/vendor/autoload.php';
$app = require $repo.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => $fixture['database'], 'app.url' => 'http://localhost:8896', 'app.debug' => false,
    'session.driver' => 'file', 'session.files' => $fixture['storage'].'/sessions', 'session.cookie' => 'fictional_module_qa', 'session.domain' => null,
    'services.supabase.api_url' => 'http://127.0.0.1:4900/supabase', 'lab-modules.business_writes_enabled' => true]);
Illuminate\Support\Facades\DB::purge('sqlite');
$app->useStoragePath($fixture['storage']);
$app->instance(Deep42\Hitchhiker\Contracts\InstallationStateManager::class, new class implements Deep42\Hitchhiker\Contracts\InstallationStateManager {
    public function isInstalled(): bool { return true; }
    public function markInstalled(): void { throw new RuntimeException('No installation mutation'); }
});
Illuminate\Support\Facades\Route::middleware('web')->get('/__qa', function () use ($fixture) {
    $csrf = e(csrf_token());
    $css = e(Illuminate\Support\Facades\Vite::asset('resources/css/app.css'));
    $html = '<!doctype html><html lang="th"><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="'.$csrf.'"><title>Fictional module owner QA</title><link rel="stylesheet" href="'.$css.'"><link rel="stylesheet" href="/__qa-owner.css"><link rel="stylesheet" href="https://fonts.bunny.net/css?family=sora:400,500,600,700|noto-sans-thai:400,500,600,700&amp;display=swap"><body style="margin:0">';
    if (!auth()->check()) return response($html.'<h1>Local fictional account</h1><form method="POST" action="/__qa-login"><input type="hidden" name="_token" value="'.$csrf.'"><label>Email<input name="email" type="email"></label><label>Password<input name="password" type="password"></label><button>Sign in to QA</button></form></body></html>');
    $json = json_encode(['projectUuid' => $fixture['project_uuid']], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    return response($html.'<p style="margin:0;padding:8px 16px;font:12px sans-serif;background:#e8efe8;color:#314f3e">Local fictional QA · SQLite account + PostgreSQL memory · No real account, payment or Supabase changed.</p><div id="root" style="height:calc(100dvh - 32px)"></div><script type="application/json" id="qa-config">'.$json.'</script><script type="module" src="/__qa-owner.js"></script></body></html>');
});
Illuminate\Support\Facades\Route::middleware('web')->post('/__qa-login', function (Illuminate\Http\Request $request) {
    abort_unless(Illuminate\Support\Facades\Auth::attempt($request->only('email','password')), 403);
    $request->session()->regenerate(); return redirect('/__qa');
});
$app->handleRequest(Illuminate\Http\Request::capture());
