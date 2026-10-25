<?php

// Set these before providers bootstrap: this suite never opens the configured app database.
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=:memory:');
require __DIR__.'/forms-bootstrap.php';

use App\Enums\UserStatus;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\AccessRole;
use App\Models\User;
use App\Models\UserBusinessProfile;
use App\Models\UserEntitlement;
use App\Notifications\ConfirmEmailAddress;
use App\Support\OpaqueEmailToken;
use App\Support\Site\SiteSettings;
use Deep42\Hitchhiker\Contracts\InstallationStateManager;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Cookie\CookieValuePrefix;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

Http::fake();
Notification::fake();
DB::statement('PRAGMA foreign_keys = ON');
formCheck(DB::connection()->getDriverName() === 'sqlite' && DB::connection()->getDatabaseName() === ':memory:', 'Fixture uses SQLite memory only');
formCheck((int) DB::selectOne('PRAGMA foreign_keys')->foreign_keys === 1, 'Fixture enforces foreign keys');

Schema::create('users', function (Blueprint $table): void {
    $table->id();
    $table->string('public_id')->unique();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->timestamps();
});

function signupRequest(array $extra = []): RegisterRequest
{
    $request = RegisterRequest::create('/register', 'POST', array_replace([
        'name' => 'Signup fixture',
        'email' => 'signup-fixture@example.invalid',
        'password' => 'Fixture-password-123',
        'password_confirmation' => 'Fixture-password-123',
    ], $extra));
    $request->setContainer(app())->setRedirector(app('redirect'));
    $request->validateResolved();

    return $request;
}

formCheck(signupRequest()->validated()['name'] === 'Signup fixture', 'Old signup payload remains valid');
formInvalid(fn () => signupRequest(['company_name' => str_repeat('ก', 256)]), 'Company256 rejected');
formCheck(signupRequest(['company_name' => str_repeat('ก', 255)])->validated()['company_name'] === str_repeat('ก', 255), 'Thai company255 accepted');
formCheck(signupRequest(['business_description' => str_repeat('ข', 2000)])->validated()['business_description'] === str_repeat('ข', 2000), 'Thai description2000 accepted');
formInvalid(fn () => signupRequest(['business_description' => str_repeat('ข', 2001)]), 'Description2001 rejected');
foreach (['-1', 'NaN', 'INF', '1e3', '12.345', '1000000000000', '1.', '.5', '+1', "12\n", '1,000', [], true, 12.5] as $amount) {
    formInvalid(fn () => signupRequest(['website_budget_amount' => $amount]), 'Invalid budget format/type rejected');
}
foreach (['0', '0.00', '1234.50', '999999999999.99'] as $amount) {
    formCheck(signupRequest(['website_budget_amount' => $amount, 'website_budget_currency' => 'USD'])->validated()['website_budget_amount'] === $amount, 'Exact decimal string accepted');
}
foreach (['EUR', 'usd', [], true, 123] as $currency) {
    formInvalid(fn () => signupRequest(['website_budget_currency' => $currency]), 'Unsupported currency/type rejected');
}
foreach (['company_name', 'business_description'] as $field) {
    formInvalid(fn () => signupRequest([$field => []]), 'Profile text must be a string');
}
foreach ([[], ['website_budget_currency' => ''], ['website_budget_currency' => null], ['website_budget_currency' => '   ']] as $extra) {
    formCheck(signupRequest($extra)->validated()['website_budget_currency'] === 'THB', 'Absent/empty currency defaults to THB');
}
$request = signupRequest([
    'company_name' => 'บริษัท fixture',
    'business_description' => 'Website fixture',
    'website_budget_amount' => '1234.50',
    'website_budget_currency' => 'USD',
    'user_id' => 999,
    'access_role_id' => 999,
    'status' => 'active',
    'preferences' => ['fixture' => true],
]);
formCheck(array_keys($request->authData()) === ['name', 'email', 'password'], 'Auth payload allowlist excludes profile and forged account fields');
formCheck($request->businessProfileData() === [
    'company_name' => 'บริษัท fixture',
    'business_description' => 'Website fixture',
    'website_budget_amount' => '1234.50',
    'website_budget_currency' => 'USD',
], 'Profile payload allowlist preserves exact strings and excludes owner');
formCheck(signupRequest()->businessProfileData() === [
    'company_name' => null,
    'business_description' => null,
    'website_budget_amount' => null,
    'website_budget_currency' => 'THB',
], 'Legacy profile payload has nullable fields and THB');
formCheck(signupRequest(['website_budget_amount' => null])->businessProfileData()['website_budget_amount'] === null, 'Null budget is accepted');
formInvalid(fn () => signupRequest(['password_confirmation' => 'different']), 'Existing password confirmation preserved');
formInvalid(fn () => signupRequest(['email' => 'not-email']), 'Existing email validation preserved');

$migration = require __DIR__.'/../../database/migrations/2026_10_16_100000_create_user_business_profiles_table.php';
$migration->up();
formCheck(Schema::hasTable('user_business_profiles'), 'Actual additive migration creates profile table');
$legacy = User::create(['name' => 'Legacy fixture', 'email' => 'legacy@example.invalid', 'password' => 'fixture-password-123']);
formCheck($legacy->businessProfile === null, 'Legacy member works without a profile/backfill');
formCheck(! array_intersect(['company_name', 'business_description', 'website_budget_amount', 'website_budget_currency'], $legacy->getFillable()), 'User mass assignment does not expand into profile fields');
formCheck(! array_key_exists('business_profile', $legacy->fresh()->toArray()), 'User never auto-appends or eager-loads profile');
$owner = User::create(['name' => 'Owner fixture', 'email' => 'owner@example.invalid', 'password' => 'fixture-password-123']);
$profile = $owner->businessProfile()->create($request->businessProfileData());
$profile = $profile->fresh();
formCheck($profile->user_id === $owner->id && $profile->user->is($owner), 'Relationship assigns profile ownership');
formCheck(is_string($profile->website_budget_amount) && $profile->website_budget_amount === '1234.50', 'Decimal budget roundtrips as exact two-place string');
formCheck($profile->company_name === 'บริษัท fixture' && $profile->business_description === 'Website fixture', 'Company text persists');
formCheck(! array_key_exists('website_budget_amount', $profile->toArray()) && ! array_key_exists('website_budget_currency', $profile->toArray()), 'Default profile serialization hides budget and currency');
formCheck(! str_contains($profile->toJson(), 'website_budget_amount') && ! str_contains($profile->toJson(), 'website_budget_currency'), 'Default JSON serialization hides budget fields');
$loadedOwner = $owner->fresh()->load('businessProfile')->toArray();
formCheck(! array_key_exists('website_budget_amount', $loadedOwner['business_profile']) && ! array_key_exists('website_budget_currency', $loadedOwner['business_profile']), 'Explicit eager serialization still hides budget');
formCheck(! in_array('user_id', (new UserBusinessProfile)->getFillable(), true), 'Profile mass assignment excludes owner');
$attemptedOwner = new UserBusinessProfile(['user_id' => $legacy->id, 'company_name' => 'Forged owner fixture']);
formCheck($attemptedOwner->user_id === null, 'Untrusted mass assignment cannot choose owner');

try {
    $owner->businessProfile()->create(['company_name' => 'Duplicate fixture']);
    formCheck(false, 'One profile per user is enforced');
} catch (QueryException $error) {
    formCheck(str_contains($error->getMessage(), 'UNIQUE constraint failed: user_business_profiles.user_id'), 'One profile per user is enforced');
}
try {
    DB::table('user_business_profiles')->insert(['user_id' => 999999, 'website_budget_currency' => 'THB']);
    formCheck(false, 'Nonexistent profile owner rejected');
} catch (QueryException $error) {
    formCheck(str_contains($error->getMessage(), 'FOREIGN KEY constraint failed'), 'Nonexistent profile owner rejected');
}
$defaultProfile = $legacy->businessProfile()->create([])->fresh();
formCheck($defaultProfile->website_budget_currency === 'THB' && $defaultProfile->website_budget_amount === null, 'Database defaults preserve optional budget and THB');
$owner->delete();
formCheck(! UserBusinessProfile::whereKey($profile->id)->exists(), 'Deleting owner cascades only their profile');
formCheck(UserBusinessProfile::whereKey($defaultProfile->id)->exists(), 'Other account profile survives cascade');
formInvalid(fn () => signupRequest(['email' => 'legacy@example.invalid']), 'Existing unique email rule preserved');
$migration->down();
formCheck(! Schema::hasTable('user_business_profiles') && Schema::hasTable('users') && User::count() === 1, 'Migration rollback removes only profile table');
formCheck(Notification::sentNotifications() === [], 'No notification transport is used');

// Extend the minimal Task 1 fixture for the real registration orchestration.
$migration->up();
(require __DIR__.'/../../database/migrations/2026_08_03_140000_create_access_control_tables.php')->up();
Schema::table('users', function (Blueprint $table): void {
    $table->string('status')->default('active');
    $table->timestamp('email_verified_at')->nullable();
    $table->text('preferences')->nullable();
    $table->rememberToken();
});
(require __DIR__.'/../../database/migrations/2026_08_15_231300_create_entitlement_tables.php')->up();
(require __DIR__.'/../../database/migrations/2026_08_16_001700_add_pack_pricing_to_entitlement_plans.php')->up();
$member = AccessRole::create(['slug' => 'member', 'title' => 'Member fixture']);
$ownerRole = AccessRole::create(['slug' => 'owner', 'title' => 'Owner fixture', 'locked' => true]);
Schema::create('site_settings', function (Blueprint $table): void {
    $table->id();
    $table->text('payload')->nullable();
    $table->timestamps();
});
$site = new SiteSettings;
app()->instance(SiteSettings::class, $site);
// Keep real web middleware, with installation/session/cache state entirely in memory.
app()->instance(InstallationStateManager::class, new class implements InstallationStateManager {
    public function isInstalled(): bool { return true; }
    public function markInstalled(): void { throw new RuntimeException('Fixture must not alter installation state'); }
});
config(['app.env' => 'local', 'app.debug' => false, 'session.driver' => 'array', 'cache.default' => 'array', 'logging.default' => 'null']);
app()->instance('env', 'local'); // CSRF must execute, never use the testing bypass.
app('session')->forgetDrivers();
app()->forgetInstance('session.store');
app('redirect')->setSession(app('session')->driver());
Auth::forgetGuards();
// The provider may resolve the limiter before the bootstrap helper sets array cache.
// Retain its actual registration policy while replacing the store for isolation.
$registerLimit = RateLimiter::limiter('register');
RateLimiter::swap(new Illuminate\Cache\RateLimiter(app('cache')->store('array')));
RateLimiter::for('register', $registerLimit);
$registered = [];
$logins = [];
// Observe actual events; notification transport is faked, preserving the verification listener.
Event::listen(Registered::class, function (Registered $event) use (&$registered): void {
    $registered[] = [
        'user_id' => $event->user->id,
        'transaction' => DB::transactionLevel(),
        'profile_exists' => $event->user->businessProfile()->exists(),
        'entitlement_exists' => $event->user->entitlement()->exists(),
        'authenticated' => Auth::check(),
    ];
});
Event::listen(Login::class, function (Login $event) use (&$logins): void {
    $logins[] = ['user_id' => $event->user->id, 'transaction' => DB::transactionLevel()];
});

$request = signupRequest([
    'email' => 'registered@example.invalid',
    'company_name' => 'บริษัท signup fixture',
    'business_description' => 'Registration description fixture',
    'website_budget_amount' => '1234.50',
    'website_budget_currency' => 'USD',
    'user_id' => $legacy->id,
    'access_role_id' => $ownerRole->id,
    'status' => 'active',
    'preferences' => ['admin' => true],
]);
$request->setLaravelSession(app('session')->driver());
$request->session()->start();
$sessionId = $request->session()->getId();
$token = $request->session()->token();
$response = app(RegisteredUserController::class)->store($request, $site);
$signedUp = User::where('email', 'registered@example.invalid')->firstOrFail();
$savedProfile = $signedUp->businessProfile;
formCheck($savedProfile instanceof UserBusinessProfile, 'Registration persists the optional business profile');
formCheck($savedProfile->user_id === $signedUp->id && $savedProfile->user_id !== $legacy->id, 'Forged owner cannot override relationship ownership');
formCheck($signedUp->name === 'Signup fixture' && $signedUp->email === 'registered@example.invalid' && Hash::check('Fixture-password-123', $signedUp->password), 'Registration preserves identity and hashes password');
formCheck($signedUp->access_role_id === $member->id && $signedUp->status === UserStatus::Invited && $signedUp->email_verified_at === null && $signedUp->preferences === null, 'Server assigns invited/member account and ignores forged preferences');
formCheck($savedProfile->company_name === 'บริษัท signup fixture' && $savedProfile->business_description === 'Registration description fixture' && $savedProfile->website_budget_amount === '1234.50' && $savedProfile->website_budget_currency === 'USD', 'Registration stores all four profile fields with exact decimal');
formCheck(count($registered) === 1 && $registered[0] === ['user_id' => $signedUp->id, 'transaction' => 0, 'profile_exists' => true, 'entitlement_exists' => true, 'authenticated' => false], 'Registered fires once after all account rows commit and before login');
formCheck($logins === [['user_id' => $signedUp->id, 'transaction' => 0]] && Auth::id() === $signedUp->id, 'Login fires once after commit');
formCheck($request->session()->getId() !== $sessionId && $request->session()->token() !== $token, 'Registration regenerates session identifier and CSRF token');
formCheck($response->getStatusCode() === 302 && $response->getTargetUrl() === route('home'), 'Registration keeps existing home redirect');
formCheck(count(Notification::sent($signedUp, ConfirmEmailAddress::class)) === 1, 'Existing Registered listener requests one verification notification through fake transport');
formCheck(! array_key_exists('website_budget_amount', $savedProfile->toArray()) && ! array_key_exists('website_budget_currency', $savedProfile->toArray()) && ! array_key_exists('business_profile', $signedUp->fresh()->toArray()), 'Registration does not expose budget through default model serialization');

// A temporary dispatcher copy removes only the injected failure listener afterward.
Auth::guard('web')->forgetUser();
app('session')->driver()->flush();
$counts = [User::count(), UserBusinessProfile::count(), UserEntitlement::count(), count($registered), count($logins), count(Notification::sentNotifications())];
$dispatcher = UserBusinessProfile::getEventDispatcher();
UserBusinessProfile::setEventDispatcher(clone $dispatcher);
$failureObserved = [];
UserBusinessProfile::creating(function (UserBusinessProfile $profile) use (&$failureObserved): void {
    $failureObserved = [DB::transactionLevel(), User::whereKey($profile->user_id)->exists(), UserEntitlement::where('user_id', $profile->user_id)->exists(), Auth::guest()];
    throw new RuntimeException('Injected profile fixture failure');
});
try {
    $failingRequest = signupRequest(['email' => 'rollback@example.invalid']);
    $failingRequest->setLaravelSession(app('session')->driver());
    app(RegisteredUserController::class)->store($failingRequest, $site);
    formCheck(false, 'Profile failure propagates');
} catch (RuntimeException $error) {
    formCheck($error->getMessage() === 'Injected profile fixture failure', 'Profile failure propagates');
} finally {
    UserBusinessProfile::setEventDispatcher($dispatcher);
}
formCheck($failureObserved === [1, true, true, true], 'Failure occurs inside transaction after real User-created entitlement and before authentication');
formCheck($counts === [User::count(), UserBusinessProfile::count(), UserEntitlement::count(), count($registered), count($logins), count(Notification::sentNotifications())] && ! User::where('email', 'rollback@example.invalid')->exists() && Auth::guest() && DB::transactionLevel() === 0, 'Profile failure rolls back user/profile/default entitlement without Registered, login or notification');

function signupGuestSession(): void
{
    app('session')->driver()->invalidate();
    app('session')->driver()->regenerateToken();
    Auth::guard('web')->forgetUser();
}

function signupHttp(string $path, string $method = 'GET', array $data = [], string $ip = '192.0.2.10', bool $csrf = true, string $accept = 'application/json'): array
{
    $session = app('session')->driver();
    $session->start();
    if ($method === 'POST' && $csrf) {
        $data['_token'] = $session->token();
    }
    // Cookie preparation must not age validation flash data before the real request.
    $session->reflash();
    $session->save();
    $cookieName = config('session.cookie');
    $encrypter = app('encrypter');
    $cookie = $encrypter->encrypt(CookieValuePrefix::create($cookieName, $encrypter->getKey()).$session->getId(), false);
    $request = Request::create($path, $method, $data, [$cookieName => $cookie], [], ['HTTP_ACCEPT' => $accept, 'REMOTE_ADDR' => $ip]);
    $kernel = app(Kernel::class);
    $response = $kernel->handle($request);

    return [$response, $request];
}

signupGuestSession();
$payload = ['name' => 'HTTP signup fixture', 'email' => 'legacy-http@example.invalid', 'password' => 'Fixture-password-123', 'password_confirmation' => 'Fixture-password-123'];
$beforeHttpSession = app('session')->driver()->getId();
[$httpResponse, $httpRequest] = signupHttp('/register', 'POST', $payload);
$httpUser = User::where('email', $payload['email'])->first();
formCheck($httpResponse->getStatusCode() === 302 && $httpResponse->headers->get('Location') === route('home') && $httpUser instanceof User && Auth::id() === $httpUser->id, 'Legacy signup passes real HTTP kernel and authenticates with existing redirect');
formCheck($httpRequest->session()->getId() !== $beforeHttpSession, 'HTTP signup regenerates session');
formCheck($httpUser->businessProfile->company_name === null && $httpUser->businessProfile->business_description === null && $httpUser->businessProfile->website_budget_amount === null && $httpUser->businessProfile->website_budget_currency === 'THB', 'Legacy HTTP signup creates empty optional profile with THB');
formCheck(count($registered) === 2 && count($logins) === 2 && $registered[1]['transaction'] === 0 && $logins[1]['transaction'] === 0, 'HTTP signup events also occur exactly once after commit');
[$blockedLab] = signupHttp('/lab');
formCheck($blockedLab->getStatusCode() === 403 && ! $httpUser->fresh()->hasVerifiedEmail(), 'Unverified signup cannot bypass real verified middleware');
[$guestOnly] = signupHttp('/register');
formCheck($guestOnly->getStatusCode() === 302 && $guestOnly->headers->get('Location') === route('home'), 'Authenticated member remains blocked by registration guest middleware');

$mail = Notification::sent($httpUser, ConfirmEmailAddress::class)->first()->toMail($httpUser);
$verificationUrl = $mail->actionUrl;
[$verified] = signupHttp($verificationUrl);
formCheck($verified->getStatusCode() === 302 && $verified->headers->get('Location') === route('home') && $httpUser->fresh()->hasVerifiedEmail() && $httpUser->fresh()->status === UserStatus::Active, 'Existing signed opaque verification activates invited account and keeps redirect');
$countAfterVerification = [User::count(), UserBusinessProfile::count(), UserEntitlement::count()];
[$repeatVerification] = signupHttp($verificationUrl);
formCheck($repeatVerification->getStatusCode() === 302 && $countAfterVerification === [User::count(), UserBusinessProfile::count(), UserEntitlement::count()], 'Repeated verification does not create accounts or profiles');
[$invalidVerification] = signupHttp(URL::temporarySignedRoute('verification.verify', now()->addMinutes(5), ['token' => OpaqueEmailToken::seal(['id' => $legacy->id, 'email' => sha1($legacy->email)])]));
formCheck($invalidVerification->getStatusCode() === 403, 'Signed verification token still enforces account ownership');
$httpUser->businessProfile()->update(['company_name' => 'Private company fixture', 'business_description' => 'Private business description fixture', 'website_budget_amount' => '1234.50', 'website_budget_currency' => 'USD']);
Schema::create('lab_projects', function (Blueprint $table): void {
    $table->id();
    $table->foreignId('user_id')->constrained();
});
[$lab] = signupHttp('/lab');
formCheck($lab->getStatusCode() === 200 && str_contains($lab->getContent(), 'id="lab-bootstrap"'), 'Verified member still reaches actual Lab bootstrap');
formCheck(! str_contains($lab->getContent(), 'website_budget_amount') && ! str_contains($lab->getContent(), 'website_budget_currency'), 'Lab HTML/bootstrap never includes private budget fields');
formCheck(str_contains($lab->getContent(), 'Private company fixture') && str_contains($lab->getContent(), 'Private business description fixture'), 'Existing owner-only business name/description prefill remains available without budget');
$legacy->forceFill(['email_verified_at'=>now(), 'status'=>UserStatus::Active])->save();
$legacy->businessProfile()->updateOrCreate([], ['company_name'=>'Other owner company fixture', 'business_description'=>'Other owner description fixture']);
signupGuestSession(); Auth::guard('web')->login($legacy);
[$otherLab] = signupHttp('/lab');
formCheck($otherLab->getStatusCode() === 200 && !str_contains($otherLab->getContent(),'Private company fixture') && !str_contains($otherLab->getContent(),'Private business description fixture') && str_contains($otherLab->getContent(),'Other owner company fixture'), 'Lab prefill reads only the current owner profile across account changes');

signupGuestSession();
$beforeDenied = [User::count(), UserBusinessProfile::count(), UserEntitlement::count(), count($registered), count($logins)];
[$duplicate] = signupHttp('/register', 'POST', $payload, '192.0.2.11');
app('session')->driver()->start(); // Restore Laravel's error bag from JSON session serialization.
formCheck($duplicate->getStatusCode() === 302 && app('session')->driver()->get('errors')?->has('email'), 'Duplicate email returns existing session validation error through HTTP kernel');
[$noCsrf] = signupHttp('/register', 'POST', array_replace($payload, ['email' => 'csrf@example.invalid']), '192.0.2.12', false);
formCheck($noCsrf->getStatusCode() === 419, 'Real CSRF middleware rejects missing token');
$site->put('general', ['allow_registration' => false]);
[$disabledGet] = signupHttp('/register');
[$disabledPost] = signupHttp('/register', 'POST', array_replace($payload, ['email' => 'disabled@example.invalid']), '192.0.2.13');
formCheck($disabledGet->getStatusCode() === 404 && $disabledPost->getStatusCode() === 404, 'Registration-disabled setting blocks real GET and POST');
$site->put('general', ['allow_registration' => true]);
for ($attempt = 1; $attempt <= 6; $attempt++) {
    [$limited] = signupHttp('/register', 'POST', [], '192.0.2.14');
    formCheck($limited->getStatusCode() === ($attempt <= 5 ? 302 : 429), 'Real registration throttle preserves five attempts per minute');
}
formCheck($beforeDenied === [User::count(), UserBusinessProfile::count(), UserEntitlement::count(), count($registered), count($logins)] && Auth::guest(), 'Rejected HTTP requests create no account rows or auth events');
[$unauthenticatedLab] = signupHttp('/lab');
formCheck($unauthenticatedLab->getStatusCode() === 302 && $unauthenticatedLab->headers->get('Location') === route('login'), 'Lab still requires authentication through real HTTP middleware');
formCheck(Http::recorded()->isEmpty(), 'No HTTP provider is called');

function signupDocument(string $html): DOMXPath
{
    $document = new DOMDocument;
    $document->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_NOERROR | LIBXML_NOWARNING);

    return new DOMXPath($document);
}

signupGuestSession();
[$registerPage] = signupHttp('/register');
formCheck($registerPage->getStatusCode() === 200, 'Guest registration page renders through actual HTTP kernel');
$document = signupDocument($registerPage->getContent());
foreach (['company_name', 'business_description', 'website_budget_amount', 'website_budget_currency'] as $field) {
    $control = $document->query('//*[@name="'.$field.'"]')->item(0);
    formCheck($control instanceof DOMElement, 'Registration renders optional profile control '.$field);
    formCheck($control->getAttribute('id') === $field && $document->query('//label[@for="'.$field.'"]')->length === 1, 'Profile control has an associated visible label '.$field);
    formCheck(! $control->hasAttribute('required'), 'Profile control is optional '.$field);
    formCheck(str_contains($control->getAttribute('aria-describedby'), $field.'-help') && $document->query('//*[@id="'.$field.'-help"]')->length === 1, 'Profile control is associated with its help '.$field);
}
$amount = $document->query('//input[@name="website_budget_amount"]')->item(0);
formCheck($amount->getAttribute('type') === 'number' && $amount->getAttribute('min') === '0' && $amount->getAttribute('step') === '0.01' && $amount->getAttribute('max') === '999999999999.99', 'Budget input exposes exact nonnegative decimal constraints');
formCheck($document->query('//input[@name="company_name" and @maxlength="255"]')->length === 1 && $document->query('//textarea[@name="business_description" and @maxlength="2000"]')->length === 1, 'Company and description expose server-aligned length constraints');
formCheck($document->query('//select[@name="website_budget_currency"]/option')->length === 2 && $document->query('//select[@name="website_budget_currency"]/option[@value="THB" and @selected]')->length === 1 && $document->query('//select[@name="website_budget_currency"]/option[@value="USD"]')->length === 1, 'Native currency select supports THB default and USD');
formCheck($document->query('//fieldset/legend')->length === 1 && str_contains($document->query('//fieldset/legend')->item(0)->textContent, 'ไม่บังคับ'), 'Company details remain one optional fieldset');
formCheck(str_contains($registerPage->getContent(), 'งบทำเว็บไซต์ ไม่ใช่ค่าแพ็กเกจหรือเครดิต AI') && str_contains($registerPage->getContent(), 'ไม่ส่งงบให้ AI อัตโนมัติ') && str_contains($registerPage->getContent(), 'ไม่เรียกเก็บเงิน'), 'Budget help explains website budget, AI privacy and no charge');
formCheck($document->query('//form[@method="POST" and @action="'.route('register').'"]/input[@name="_token"]')->length === 1, 'Registration preserves form action and CSRF token');
formCheck(array_map(fn ($node) => $node->getAttribute('name'), iterator_to_array($document->query('//form//input[@name="name" or @name="email" or @name="password" or @name="password_confirmation"]'))) === ['name', 'email', 'password', 'password_confirmation'], 'Existing account fields retain their order');

$script = '<script>fixture</script>';
$invalidProfile = [
    'company_name' => $script.str_repeat('ก', 256),
    'business_description' => $script.str_repeat('ข', 2001),
    'website_budget_amount' => '"'.$script,
    'website_budget_currency' => $script,
];
[$invalidProfilePost] = signupHttp('/register', 'POST', array_replace($payload, ['email' => 'invalid-profile@example.invalid'], $invalidProfile), '192.0.2.20');
app('session')->driver()->start();
formCheck($invalidProfilePost->getStatusCode() === 302 && ! app('session')->driver()->hasOldInput('password') && ! app('session')->driver()->hasOldInput('password_confirmation'), 'Profile validation redirects without flashing either password');
foreach ($invalidProfile as $field => $value) {
    formCheck(app('session')->driver()->getOldInput($field) === $value && app('session')->driver()->get('errors')->has($field), 'Profile validation retains rejected input and its error '.$field);
}
[$errorPage] = signupHttp('/register');
formCheck($errorPage->getStatusCode() === 200 && ! str_contains($errorPage->getContent(), $script) && str_contains($errorPage->getContent(), '&lt;script&gt;fixture&lt;/script&gt;'), 'Profile validation redisplay escapes script markup');
$document = signupDocument($errorPage->getContent());
formCheck($document->query('//input[@name="company_name"]')->item(0)->getAttribute('value') === $invalidProfile['company_name'] && $document->query('//textarea[@name="business_description"]')->item(0)->textContent === $invalidProfile['business_description'] && $document->query('//input[@name="website_budget_amount"]')->item(0)->getAttribute('value') === $invalidProfile['website_budget_amount'], 'Escaped company, description and budget retain their exact text');
foreach (array_keys($invalidProfile) as $field) {
    $control = $document->query('//*[@name="'.$field.'"]')->item(0);
    formCheck($control->getAttribute('aria-invalid') === 'true' && str_contains($control->getAttribute('aria-describedby'), $field.'-error') && $document->query('//*[@id="'.$field.'-error" and @role="alert"]')->length === 1, 'Rejected profile control exposes an associated error '.$field);
}
formCheck($document->query('//input[@type="password" and @value=""]')->length === 2, 'Error page renders both password controls empty');

signupGuestSession();
[$emptyBudgetPost] = signupHttp('/register', 'POST', array_replace($payload, ['email' => 'not-email', 'website_budget_amount' => '', 'website_budget_currency' => 'USD']), '192.0.2.21');
[$emptyBudgetPage] = signupHttp('/register');
$document = signupDocument($emptyBudgetPage->getContent());
formCheck($emptyBudgetPost->getStatusCode() === 302 && $emptyBudgetPage->getStatusCode() === 200 && $document->query('//input[@name="website_budget_amount"]')->item(0)->getAttribute('value') === '' && $document->query('//select[@name="website_budget_currency"]/option[@value="USD" and @selected]')->length === 1, 'Validation redisplay preserves USD even with an empty optional amount');

foreach ([['nested' => $script], true] as $invalidType) {
    signupGuestSession();
    $typedProfile = array_fill_keys(array_keys($invalidProfile), $invalidType);
    [$typedPost] = signupHttp('/register', 'POST', array_replace($payload, ['email' => 'typed-profile@example.invalid'], $typedProfile), $invalidType === true ? '192.0.2.23' : '192.0.2.22');
    [$typedPage] = signupHttp('/register');
    formCheck($typedPost->getStatusCode() === 302 && $typedPage->getStatusCode() === 200, 'Invalid profile array/boolean input redisplays without a view exception');
    $document = signupDocument($typedPage->getContent());
    foreach (array_keys($invalidProfile) as $field) {
        $control = $document->query('//*[@name="'.$field.'"]')->item(0);
        formCheck($control->getAttribute('aria-invalid') === 'true' && ($field === 'website_budget_currency' || ($control->tagName === 'textarea' ? $control->textContent === '' : $control->getAttribute('value') === '')), 'Invalid profile type has an empty safe control and validation error '.$field);
    }
}

signupGuestSession();
$httpProfile = ['company_name' => 'บริษัท HTTP fixture', 'business_description' => 'เว็บไซต์ fixture', 'website_budget_amount' => '0.00', 'website_budget_currency' => 'USD'];
[$profilePost] = signupHttp('/register', 'POST', array_replace($payload, ['email' => 'profile-http@example.invalid'], $httpProfile), '192.0.2.24');
$profileUser = User::where('email', 'profile-http@example.invalid')->first();
formCheck($profilePost->getStatusCode() === 302 && $profilePost->headers->get('Location') === route('home') && $profileUser instanceof User && Auth::id() === $profileUser->id, 'Signup with new profile fields succeeds through real HTTP kernel and retains redirect');
formCheck($profileUser->businessProfile->only(array_keys($httpProfile)) === $httpProfile, 'HTTP signup persists all four optional profile fields exactly');
formCheck(Http::recorded()->isEmpty(), 'Profile form requests call no external provider');
formResult('signup business profile storage, validation, atomic registration and form');
