<?php

require __DIR__.'/forms-bootstrap.php';

use App\Support\Readiness\ProductionReadiness;
use Illuminate\Support\Facades\Artisan;

$readiness = app(ProductionReadiness::class);
$baseline = $readiness->inspect();
formCheck(isset($baseline['checks'], $baseline['summary']), 'Readiness report has stable top-level shape');
formCheck(count($baseline['checks']) >= 10, 'Readiness report covers runtime and product gates');
formCheck(! str_contains(json_encode($baseline), 'API_KEY'), 'Readiness report does not expose secret names or values');

$originalModel = config('ai.default_model');
config(['ai.default_model' => 'missing-fixture-model']);
$invalidModel = $readiness->inspect();
$modelCheck = collect($invalidModel['checks'])->firstWhere('key', 'ai.default_model');
formCheck($modelCheck['status'] === 'fail', 'Unknown default model fails closed');
config(['ai.default_model' => $originalModel]);

$originalKey = config('app.key');
config(['app.key' => null]);
$missingKey = $readiness->inspect();
$keyCheck = collect($missingKey['checks'])->firstWhere('key', 'app.key');
formCheck($keyCheck['status'] === 'fail', 'Missing application key is a critical failure');
config(['app.key' => $originalKey]);

config(['app.key' => 'base64:'.base64_encode(str_repeat('x', 32)), 'ai.default_model' => 'claude-sonnet-5']);
$restored = $readiness->inspect();
$restoredModel = collect($restored['checks'])->firstWhere('key', 'ai.default_model');
formCheck($restoredModel['status'] === 'pass', 'Catalog model restores to a passing readiness check');

config(['ai.default_model' => 'gpt-6.1-sol']);
$dottedModel = $readiness->inspect();
$dottedCheck = collect($dottedModel['checks'])->firstWhere('key', 'ai.default_model');
formCheck($dottedCheck['status'] === 'pass', 'Model IDs containing dots resolve without path parsing');

$commandExit = Artisan::call('krikkit:readiness', ['--json' => true]);
formCheck($commandExit === 0 && str_contains(Artisan::output(), '"checks"'), 'Readiness command emits JSON and exits successfully');

formResult('Production readiness contract');
