<?php
require __DIR__.'/../../vendor/autoload.php';
$app = require __DIR__.'/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use App\Datastore\SupabaseLinkBroker;
use App\Integrations\WorkspaceOauthAppStore;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

config([
    'database.default' => 'oauth_regression',
    'database.connections.oauth_regression' => ['driver' => 'sqlite', 'database' => ':memory:', 'foreign_key_constraints' => false],
    'cache.default' => 'array', 'session.driver' => 'array',
    'services.supabase.oauth.client_id' => 'fixture-client',
    'services.supabase.oauth.client_secret' => 'fixture-secret',
]);
DB::purge('oauth_regression');
Http::preventStrayRequests();
$user = new User;
$user->id = 99;
$checks = 0;
$failures = 0;
foreach ([
    ['local', 'http://localhost:8889', true],
    ['local', 'http://127.0.0.1:8889', true],
    ['local', 'http://[::1]:8889', true],
    ['local', 'http://localhost.evil.example', false],
    ['local', 'http://example.com', false],
    ['local', 'http://192.168.1.1', false],
    ['local', 'http://localhost@evil.example', false],
    ['local', 'http://evil.example@localhost', false],
    ['production', 'http://localhost:8889', false],
    ['production', 'http://127.0.0.1:8889', false],
    ['production', 'https://example.com', true],
    ['local', 'https://example.com', true],
] as [$environment, $origin, $allowed]) {
    $app['env'] = $environment;
    $callback = $origin.'/lab/oauth/supabase/return';
    config(['services.supabase.oauth.redirect' => $callback]);
    URL::forceRootUrl($origin);
    URL::forceScheme(parse_url($origin, PHP_URL_SCHEME));
    $broker = new SupabaseLinkBroker(new WorkspaceOauthAppStore);
    $normalized = (new WorkspaceOauthAppStore)->normalizeSupabaseSettings(['redirect_uri' => $callback]);
    try {
        $result = $broker->begin($user, '/lab', false);
        parse_str(parse_url($result['url'], PHP_URL_QUERY), $query);
        $ok = $allowed && ($normalized['redirect_uri'] ?? '') === $callback && $query['redirect_uri'] === $callback
            && $query['code_challenge_method'] === 'S256'
            && $query['state'] === $result['state']
            && ($broker->handshake($result['state'])['user_id'] ?? null) === 99;
        $broker->forgetHandshake($result['state']);
    } catch (RuntimeException $error) {
        $ok = ! $allowed && ($normalized['redirect_uri'] ?? '') === '' && str_contains($error->getMessage(), 'HTTPS');
    }
    $checks++;
    if (! $ok) {
        $failures++;
        echo "FAIL: {$environment} {$origin}\n";
    }
}
if ($failures) {
    echo "{$failures} failed / {$checks} callback checks\n";
    exit(1);
}
echo "PASS: {$checks} OAuth callback and PKCE/state checks.\n";
