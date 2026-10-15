<?php

require_once __DIR__.'/../../vendor/autoload.php';
$app = require __DIR__.'/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
set_exception_handler(static function (Throwable $error): void {
    fwrite(STDERR, $error::class.': '.$error->getMessage().PHP_EOL);
    exit(1);
});
config([
    'database.default' => 'forms_regression',
    'database.connections.forms_regression' => ['driver' => 'sqlite', 'database' => ':memory:', 'foreign_key_constraints' => false],
    'cache.default' => 'array', 'session.driver' => 'array',
]);
Illuminate\Support\Facades\DB::purge('forms_regression');
Illuminate\Support\Facades\Http::preventStrayRequests();

$formChecks = 0;
function formCheck(bool $condition, string $label): void
{
    global $formChecks;
    if (! $condition) {
        throw new RuntimeException('FAIL: '.$label);
    }
    $formChecks++;
}

function formInvalid(callable $operation, string $label): void
{
    try {
        $operation();
    } catch (Illuminate\Validation\ValidationException) {
        formCheck(true, $label);
        return;
    }
    formCheck(false, $label);
}

function formResult(string $suite): void
{
    global $formChecks;
    echo "PASS: {$suite} ({$formChecks} checks).\n";
}
