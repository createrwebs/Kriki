<?php

require __DIR__.'/managed-module-bootstrap.php';

use App\Ai\ModelCatalog;
use App\Ai\ProviderFactory;
use App\Ai\Settings\AiSettingsRepository;
use App\Http\Controllers\Lab\WebsiteBuilderAiController;
use App\Lab\Builder\BlueprintSchema;
use App\Lab\Builder\TemplateCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Auth::login($owner);
config(['ai.default_model' => 'offline-fixture', 'ai.providers' => []]);
$settings = new AiSettingsRepository;
$models = new ModelCatalog([
    'offline-fixture' => ['provider' => 'missing', 'api_model' => 'offline'],
], $settings);
$providers = new ProviderFactory([], [], $settings);
$controller = new WebsiteBuilderAiController;
$request = Request::create('/lab/builder/ai-plan', 'POST', [
    'kind' => 'business',
    'template_id' => 'business-studio',
    'title' => 'คลินิกตัวอย่าง',
    'prompt' => 'คลินิกสุขภาพสีเขียว เพิ่มฟอร์มจองคิว ข่าวสาร และหน้าใหม่ บริการ',
]);
$request->setUserResolver(fn () => $owner);
$response = app()->call([$controller, 'plan'], [
    'request' => $request,
    'schema' => new BlueprintSchema,
    'catalogService' => new TemplateCatalog,
    'models' => $models,
    'providers' => $providers,
    'settings' => $settings,
]);
$payload = $response->getData(true);

formCheck($response->getStatusCode() === 200, 'Offline planner returns a successful proposal');
formCheck($payload['source'] === 'smart-assistant', 'Planner reports truthful local fallback without a provider');
formCheck(isset($payload['plan']['pages']) && count($payload['plan']['pages']) >= 1, 'Planner returns page summaries');
formCheck(in_array('form', $payload['plan']['widgets'], true), 'Planner adds a supported form widget from the brief');
formCheck(in_array('news', $payload['plan']['widgets'], true), 'Planner adds a supported news widget from the brief');
formCheck((new BlueprintSchema)->validate($payload['blueprint']) === $payload['blueprint'], 'Planner proposal validates against the frozen blueprint schema');
formCheck($payload['blueprint']['title'] === 'คลินิกตัวอย่าง', 'Planner preserves the requested title');
formCheck(str_contains($payload['message'], 'ตรวจ'), 'Planner explains that the result needs owner review');
formCheck(App\Models\LabChangeProposal::count() === 0 && App\Models\LabModuleInstallation::count() === 0, 'Planner does not apply schemas or create modules');

formResult('Guided AI planner local proposal contract');
