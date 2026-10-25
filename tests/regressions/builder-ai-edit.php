<?php
require __DIR__.'/managed-module-bootstrap.php';

use App\Ai\Drivers\OpenAiCompatibleDriver;
use App\Ai\ModelCatalog;
use App\Ai\ProviderFactory;
use App\Ai\Settings\AiSettingsRepository;
use App\Entitlement\EntitlementCatalog;
use App\Entitlement\EntitlementGate;
use App\Http\Controllers\Lab\WebsiteBuilderAiController;
use App\Lab\Builder\TemplateCatalog;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

Schema::create('usage_ledgers', function (Blueprint $table) {
    $table->id(); $table->foreignId('user_id'); $table->string('code'); $table->string('window_key'); $table->unsignedBigInteger('consumed')->default(0); $table->timestamps();
    $table->unique(['user_id','code','window_key']);
});
$plan->grants()->create(['code'=>EntitlementCatalog::LAB_CREDITS,'kind'=>'quota','ceiling'=>100,'window'=>'monthly']);
Auth::login($owner);
$base = 'http://127.0.0.1:4905/ai';
Http::allowStrayRequests([$base.'/*']);
config(['ai.default_model'=>'fixture','ai.providers'=>['fictional'=>['api_key'=>'fictional-not-a-vendor-key','driver'=>'openai_compatible','base_url'=>$base.'/v1']], 'ai.credits.model_coefficients.fixture'=>1]);
$settings = new AiSettingsRepository;
$models = new ModelCatalog(['fixture'=>['provider'=>'fictional','api_model'=>'fixture-blueprint']], $settings);
$providers = new ProviderFactory(config('ai.providers'), ['openai_compatible'=>OpenAiCompatibleDriver::class], $settings);
$controller = new WebsiteBuilderAiController;
$blueprint = (new TemplateCatalog)->blueprint('business','business-studio','Fictional unchanged brand','Private fictional design brief');
formCheck(count($blueprint['pages']) > 1 && count($blueprint['pages'][0]['sections']) > 1, 'Fixture has multiple pages and sections');
$invoke = function (array $changes = []) use ($controller,$models,$providers,$blueprint,$owner) {
    $request = Request::create('/lab/builder/ai-edit','POST', array_replace(['blueprint'=>$blueprint,'prompt'=>'Improve fictional heading','scope'=>'all'], $changes));
    $request->setUserResolver(fn()=>$owner);
    return app()->call([$controller,'edit'], ['request'=>$request,'models'=>$models,'providers'=>$providers])->getData(true);
};
$calls = fn()=>Http::get($base.'/__qa/journal')->throw()->json('calls');
$used = fn()=>app(EntitlementGate::class)->used($owner,EntitlementCatalog::LAB_CREDITS);
Http::post($base.'/__qa/reset')->throw();
$all = $invoke();
formCheck($all['source']==='ai' && $calls()===1, 'Configured Builder actually calls compatible provider through HTTP');
formCheck($all['blueprint']['title']==='Fictional provider global change', 'Actual valid AI response used');
formCheck($used()===1, 'Successful provider response uses existing credit ledger');
formCheck($blueprint['title']==='Fictional unchanged brand', 'Caller snapshot preserved');
foreach (['page','section'] as $scope) {
    $response = $invoke(['scope'=>$scope,'page_id'=>$blueprint['pages'][0]['id'],'section_id'=>$blueprint['pages'][0]['sections'][0]['id']]);
    formCheck($response['source']==='ai', 'Real provider response for '.$scope);
    $next = $response['blueprint'];
    foreach (array_diff(array_keys($blueprint),['pages']) as $key) formCheck($next[$key]===$blueprint[$key], 'Global '.$key.' preserved for '.$scope);
    formCheck(array_slice($next['pages'],1)===array_slice($blueprint['pages'],1), 'Other pages preserved for '.$scope);
    formCheck($next['pages'][0]['id']===$blueprint['pages'][0]['id'], 'Page identity preserved for '.$scope);
    formCheck($next['pages'][0]['sections'][0]['heading']==='Fictional provider heading', 'Selected content changed for '.$scope);
    if ($scope==='section') {
        foreach (['title','route','navigation'] as $key) formCheck($next['pages'][0][$key]===$blueprint['pages'][0][$key], 'Page '.$key.' preserved');
        formCheck(array_slice($next['pages'][0]['sections'],1)===array_slice($blueprint['pages'][0]['sections'],1), 'Sibling sections preserved');
    }
}
$before = $calls();
foreach ([['scope'=>'invalid'],['scope'=>'page'],['scope'=>'page','page_id'=>$sibling->uuid],['scope'=>'section','page_id'=>$blueprint['pages'][0]['id']],['scope'=>'section','page_id'=>$blueprint['pages'][0]['id'],'section_id'=>$blueprint['pages'][1]['sections'][0]['id']]] as $bad) {
    formInvalid(fn()=>$invoke($bad),'Invalid/mismatched target rejected');
}
formCheck($calls()===$before, 'Invalid scope and targets fail before network');
$beforeCredits = $used();
$missing = $invoke(['prompt'=>'fixture-missing-target','scope'=>'section','page_id'=>$blueprint['pages'][0]['id'],'section_id'=>$blueprint['pages'][0]['sections'][0]['id']]);
formCheck($missing['source']==='smart-assistant' && $used()===$beforeCredits+1, 'Provider deleting target falls back and consumed usage is settled');
formCheck(array_slice($missing['blueprint']['pages'][0]['sections'],1)===array_slice($blueprint['pages'][0]['sections'],1), 'Missing target fallback preserves siblings');
$beforeCredits = $used();
$invalid = $invoke(['prompt'=>'fixture-invalid-json']);
formCheck($invalid['source']==='smart-assistant', 'Malformed provider content uses truthful local fallback');
formCheck($used()===$beforeCredits+1, 'Already consumed invalid response usage still settled');
$beforeCredits = $used();
$failed = $invoke(['prompt'=>'fixture-provider-error','scope'=>'section','page_id'=>$blueprint['pages'][0]['id'],'section_id'=>$blueprint['pages'][0]['sections'][0]['id']]);
formCheck($failed['source']==='smart-assistant' && $used()===$beforeCredits, 'Failed provider has no returned usage charge');
formCheck($failed['blueprint']['theme']===$blueprint['theme'] && $failed['blueprint']['summary']===$blueprint['summary'], 'Fallback keeps global settings in section scope');
$fallback = $invoke(['prompt'=>'fixture-provider-error สีทอง เพิ่มหน้าใหม่ ข่าว สมัคร','scope'=>'page','page_id'=>$blueprint['pages'][0]['id']]);
formCheck(count($fallback['blueprint']['pages'])===count($blueprint['pages']) && $fallback['blueprint']['theme']===$blueprint['theme'], 'Fallback scoped page cannot add another page or change theme');
formCheck(array_slice($fallback['blueprint']['pages'],1)===array_slice($blueprint['pages'],1), 'Fallback other pages exact');
$plan->grants()->where('code',EntitlementCatalog::LAB_CREDITS)->update(['ceiling'=>$used()]);
$owner->unsetRelation('entitlement');
$before = $calls();
$expect(fn()=>$invoke(),402,'Exhausted quota denied');
formCheck($calls()===$before, 'Exhausted credit prevents vendor request');
config(['ai.providers.fictional.api_key'=>'']);
$beforeCredits = $used();
$local = $invoke(['scope'=>'section','page_id'=>$blueprint['pages'][0]['id'],'section_id'=>$blueprint['pages'][0]['sections'][0]['id'],'prompt'=>'สีทอง เปลี่ยนชื่อร้านเป็น ทดสอบ เพิ่มฟอร์ม']);
formCheck($local['source']==='smart-assistant' && $used()===$beforeCredits && $calls()===$before, 'No provider uses local editor without quota spend or network');
formCheck($local['blueprint']['theme']===$blueprint['theme'] && $local['blueprint']['shared']===$blueprint['shared'], 'Local selected-section edit preserves global brand and theme');
formCheck(array_slice($local['blueprint']['pages'][0]['sections'],1)===array_slice($blueprint['pages'][0]['sections'],1), 'Local selected-section edit preserves sibling sections');
formCheck(App\Models\LabChangeProposal::count()===0 && App\Models\LabModuleInstallation::count()===0, 'Editing does not apply schemas or create modules');
formResult('Builder AI provider, credit and exact scope contract through Postman HTTP');
