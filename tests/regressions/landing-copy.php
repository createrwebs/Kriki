<?php

putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=:memory:');
require __DIR__.'/forms-bootstrap.php';

use App\Support\Site\LandingCopy;
use App\Support\Site\ProductCapabilities;
use App\Support\Site\SiteSettings;
use App\Models\SiteSetting;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Schema::create('site_settings', function (Blueprint $table) { $table->id(); $table->text('payload')->nullable(); $table->timestamps(); });
$saved = ['landing' => ['hero' => ['title' => 'ชื่อที่ผู้ดูแลเลือกไว้ <script>fixture</script>'], 'platform' => ['quote' => 'Operator-authored quote fixture', 'quote_name' => 'Fixture owner', 'quote_role' => 'Fixture team', 'stats' => ['Operator-authored stat fixture']], 'agents' => ['title' => 'ผลงานของเรา', 'items' => [['title' => 'ตัวอย่างเดิม', 'copy' => 'เนื้อหาเดิม', 'prompt' => 'prompt ที่บันทึกไว้']]]], 'unrelated' => ['keep' => 'unchanged']];
SiteSetting::create(['payload' => $saved]);
$site = new SiteSettings;
$landing = new LandingCopy($site);
app()->instance(SiteSettings::class, $site);
app()->instance(LandingCopy::class, $landing);
formCheck($landing->section('hero')['title'] === $saved['landing']['hero']['title'], 'Saved CMS headline takes precedence');
formCheck($landing->section('hero')['preview_label'] === LandingCopy::defaults()['hero']['preview_label'], 'Missing nested copy receives new default');
formCheck(count($landing->section('agents')['items']) === 1 && $landing->section('agents')['items'][0]['prompt'] === 'prompt ที่บันทึกไว้', 'Saved example list/prompt is not replaced by defaults');
formCheck(SiteSetting::first()->payload === $saved, 'Reading new defaults never writes or overwrites settings');
foreach (['javascript:alert(1)', 'JaVaScRiPt:alert(1)', '//untrusted.invalid', 'data:text/html,fixture', "/\\untrusted.invalid", "https://good.invalid\nfixture"] as $url) {
    formCheck($landing->href($url, '#safe') === '#safe', 'Unsafe CMS href receives safe fallback');
}
foreach (['#agents', '/features', 'https://example.invalid/path', 'mailto:fixture@example.invalid', 'tel:0800000000'] as $url) {
    formCheck($landing->href($url) === $url, 'Safe CMS href preserved');
}
formCheck($landing->href('pricing') === route('pricing'), 'Pricing uses real route');
formCheck($landing->mediaUrl('//untrusted.invalid', '/fallback') === '/fallback', 'Protocol-relative media rejected');
formCheck($landing->mediaUrl('javascript:alert(1)', '/fallback') === '/fallback', 'Unsafe media scheme rejected');
$features = collect((new ProductCapabilities)->publicFeatures())->keyBy('key');
formCheck($features['commerce']['status'] === 'planned', 'Payments/cart cannot be advertised available');
formCheck($features['forms']['status'] === 'limited' && $features['server-runtime']['status'] === 'planned', 'Forms and server runtime limitations public');

$kernel = app(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(Request::create('http://localhost:8889/features'));
formCheck($response->getStatusCode() === 200, 'Features page renders through full HTTP kernel');
$html = $response->getContent();
formCheck(str_contains($html, 'Operator-authored quote fixture') && str_contains($html, 'Fixture owner') && str_contains($html, 'Operator-authored stat fixture'), 'Existing operator quote/speaker/stat remain visible without Apply');
formCheck(str_contains($html, 'ชื่อที่ผู้ดูแลเลือกไว้ &lt;script&gt;fixture&lt;/script&gt;') && ! str_contains($html, '<script>fixture</script>'), 'CMS headline escaped in actual HTML');
foreach (['main', 'overview', 'platform', 'agents', 'walkthrough', 'integrations', 'pricing', 'questions', 'start'] as $id) {
    formCheck(str_contains($html, 'id="'.$id.'"'), 'Landing section/CTA target exists: '.$id);
}
formCheck(! str_contains($html, 'picsum.photos') && ! str_contains($html, 'Studio lead') && ! str_contains($html, 'data-ascii-hexdump'), 'No placeholder photos, invented quote or autoplay canvas');
formCheck(str_contains($html, 'data-capability="commerce" data-status="planned"'), 'Public HTML keeps payment readiness qualifier');
formCheck(str_contains($html, 'sales-mobile-menu') && str_contains($html, 'aria-controls="sales-faq-0"'), 'Menu and FAQ expose accessible relationships');
formCheck(str_contains($html, '/features'), 'Product canonical identifies features route');
$guestHome = $kernel->handle(Request::create('http://localhost:8889/'));
formCheck($guestHome->getStatusCode() === 200 && str_contains($guestHome->getContent(), 'krikkit-sales'), 'Guest home remains public landing');
$user = new App\Models\User; $user->forceFill(['id' => 901]);
Auth::guard('web')->setUser($user);
app()->instance(App\Livewire\Studio\StudioComponent::class, new class extends App\Livewire\Studio\StudioComponent { public function __invoke() { return response('studio-fixture'); } });
$authHome = $kernel->handle(Request::create('http://localhost:8889/'));
formCheck($authHome->getContent() === 'studio-fixture', 'Authenticated home still dispatches Studio, not sales page');
$authFeatures = $kernel->handle(Request::create('http://localhost:8889/features'));
formCheck($authFeatures->getStatusCode() === 200 && str_contains($authFeatures->getContent(), 'krikkit-sales'), 'Signed-in visitor can intentionally view product page');
Auth::guard('web')->forgetUser();
$site->put('general', ['maintenance' => true]);
$maintenance = $kernel->handle(Request::create('http://localhost:8889/features'));
formCheck($maintenance->getStatusCode() === 503, 'Maintenance is preserved for public product route');
formCheck(SiteSetting::first()->payload['landing'] === $saved['landing'] && SiteSetting::first()->payload['unrelated'] === $saved['unrelated'], 'Settings updates preserve all saved landing and unrelated values');

Illuminate\Support\Facades\Gate::define('settings.revise', fn (?App\Models\User $actor) => $actor?->id === 901);
$editor = new App\Livewire\Dashboard\Landing\LandingComponent;
try { $editor->previewSalesCopy(); formCheck(false, 'Guest cannot propose CMS reset'); }
catch (Illuminate\Auth\Access\AuthorizationException) { formCheck(true, 'Guest cannot propose CMS reset'); }
try { $editor->applySalesCopy(); formCheck(false, 'Guest cannot apply CMS reset'); }
catch (Illuminate\Auth\Access\AuthorizationException) { formCheck(true, 'Guest cannot apply CMS reset'); }
Auth::guard('web')->setUser($user);
$record = SiteSetting::first(); $payload = $record->payload;
$payload['landing']['walkthrough'] = ['ascii_source' => 'landing/fixture.png', 'tabs' => [['image' => 'landing/fixture.webp']]];
$record->payload = $payload; $record->save();
$beforeProposal = $record->payload;
$editor->previewSalesCopy();
formCheck(SiteSetting::first()->payload === $beforeProposal, 'Preview is read-only and never applies new copy');
$comparisonData = $editor->render($landing)->getData()['copyComparison'];
formCheck(count($comparisonData) === count(LandingCopy::defaults()) && in_array('prompt ที่บันทึกไว้', $comparisonData['agents']['current'], true) && in_array('pricing', $comparisonData['header']['proposed'], true), 'Copy proposal compares every section including prompt and links');
$editor->applySalesCopy();
$afterApply = SiteSetting::first()->payload;
formCheck($afterApply['landing']['hero']['title'] === LandingCopy::defaults()['hero']['title'], 'Explicit CMS Apply updates copy');
formCheck($afterApply['landing_previous'] === $beforeProposal['landing'], 'Explicit Apply retains exact previous copy backup');
formCheck($afterApply['landing']['walkthrough']['ascii_source'] === 'landing/fixture.png' && $afterApply['landing']['walkthrough']['tabs'][0]['image'] === 'landing/fixture.webp', 'Apply preserves uploaded media references');
formCheck($afterApply['unrelated'] === $saved['unrelated'], 'Apply preserves unrelated settings');
formCheck($editor->hero['title'] === LandingCopy::defaults()['hero']['title'] && ! $editor->copyProposalOpen, 'CMS editor refreshes to applied text');
$editor->previewSalesCopy();
$record = SiteSetting::first(); $payload = $record->payload; $payload['landing']['hero']['title'] = 'Concurrent editor'; $record->payload = $payload; $record->save();
try { $editor->applySalesCopy(); formCheck(false, 'Stale proposal cannot overwrite concurrent edit'); }
catch (Symfony\Component\HttpKernel\Exception\HttpException $error) { formCheck($error->getStatusCode() === 409 && SiteSetting::first()->payload['landing']['hero']['title'] === 'Concurrent editor', 'Stale proposal conflicts without overwriting copy'); }
$editor->agents = LandingCopy::defaults()['agents'];
$clean = new ReflectionMethod($editor, 'cleanAgents');
formCheck($clean->invoke($editor)['items'][0]['prompt'] === LandingCopy::defaults()['agents']['items'][0]['prompt'], 'Normal CMS save retains new prompt field');
$record = SiteSetting::first(); $payload = $record->payload;
$payload['landing']['platform']['quote'] = 'We went from a sentence to a page we could click through before the meeting ended.';
$payload['landing']['platform']['quote_name'] = 'Studio lead';
$payload['landing']['platform']['quote_role'] = 'Independent practice';
$record->payload = $payload; $record->save();
$freshSite = new SiteSettings;
app()->instance(SiteSettings::class, $freshSite);
app()->instance(LandingCopy::class, new LandingCopy($freshSite));
$placeholderPage = $kernel->handle(Request::create('http://localhost:8889/features'));
formCheck($placeholderPage->getStatusCode() === 200 && ! str_contains($placeholderPage->getContent(), 'Studio lead'), 'Old bundled sample testimonial is not presented as real operator proof');
formResult('landing copy and HTTP presentation');
