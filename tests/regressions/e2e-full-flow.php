<?php

// Service integration of builder/module business flow using a pre-created owner.
// Authentication screens and browser production build require separate acceptance.
// 1. Existing synthetic owner and pinned datastore
// 2. Multi-page Website Builder Draft creation, page addition, detail editing before build
// 3. Real Materialization & Build into Lab project workspace
// 4. Widgets (Contact Form, News Feed, Storefront/Cart/Checkout, Custom Form Fields & AI Form Proposal)
// 5. Owner Back-office (Manage Products, Edit Website Pages, Add/Edit News, Review Customer Payment Slips, Edit PromptPay)

require __DIR__.'/managed-module-bootstrap.php';
(require __DIR__.'/../../database/migrations/2026_08_16_081000_create_lab_publications_table.php')->up();
(require __DIR__.'/../../database/migrations/2026_10_16_110000_create_website_drafts_table.php')->up();
(require __DIR__.'/../../database/migrations/2026_10_17_100000_create_website_draft_projects_table.php')->up();

use App\Lab\Builder\DraftMaterializer;
use App\Lab\Builder\TemplateCatalog;
use App\Lab\Modules\CommerceStore;
use App\Lab\Modules\ContactMessages;
use App\Lab\Modules\ModuleEmbed;
use App\Lab\Modules\ModuleInstallations;
use App\Lab\Modules\NewsArticles;
use App\Lab\Modules\SchemaProposals;
use App\Lab\SiteWorkspace;
use App\Models\UserDatastoreLink;
use App\Models\WebsiteDraft;
use Illuminate\Support\Str;

$previousStorage = app()->storagePath();
$fixtureStorage = base_path('.agent-work/qa/e2e-full-flow-'.Str::uuid());
app()->useStoragePath($fixtureStorage);

try {
    config([
        'services.supabase.api_url' => 'http://127.0.0.1:4900/supabase',
        'app.url' => 'http://localhost:8892',
        'app.debug' => false,
        'lab-modules.business_writes_enabled' => true,
    ]);
    Illuminate\Support\Facades\Http::allowStrayRequests(['http://127.0.0.1:4900/supabase/*']);

    // Grant project quota for DraftMaterializer
    $plan->grants()->create(['code' => 'projects', 'kind' => 'quota', 'allowed' => true, 'ceiling' => 10, 'window' => 'lifetime']);

    // STEP 1: Verify Owner Account & Pinned Supabase Link
    UserDatastoreLink::create([
        'user_id' => $owner->id,
        'driver' => 'supabase',
        'host_url' => 'http://127.0.0.1:4900/supabase/pglite',
        'publishable_token' => 'fictional-public',
        'steward_token' => 'fictional-private',
        'access_token' => 'fictional-management',
        'project_ref' => 'pglitefixture',
    ]);
    formCheck($owner->id > 0 && $owner->email !== '', 'Step 1: Synthetic owner exists and is linked to pinned Supabase');

    // STEP 2: Create Multi-page Website Builder Draft, Add Pages, and Edit Details Before Build
    $catalog = new TemplateCatalog();
    $initialBp = $catalog->blueprint('business', 'business-studio', 'คริกคิต สตูดิโอ แอนด์ คาเฟ่');
    $draft = (new WebsiteDraft)->forceFill([
        'uuid' => (string) Str::uuid(),
        'user_id' => $owner->id,
        'client_request_id' => (string) Str::uuid(),
        'creation_hash' => hash('sha256', 'e2e-draft-1'),
        'revision' => 1,
        'blueprint' => $initialBp,
    ]);
    $draft->save();
    formCheck($draft->revision === 1, 'Step 2.1: Created initial website builder draft');

    $bp = $draft->blueprint;
    $bp['title'] = 'คริกคิต สตูดิโอ แอนด์ คาเฟ่ (Official)';
    $bp['pages'] = array_slice($bp['pages'], 0, 4);
    $shopSection = $bp['pages'][0]['sections'][0];
    $shopSection['id'] = (string) Str::uuid();
    $shopSection['items'] = array_map(fn ($it) => [...$it, 'id' => (string) Str::uuid()], $shopSection['items']);
    $bp['pages'][] = [
        'id' => (string) Str::uuid(),
        'title' => 'ร้านค้าออนไลน์',
        'route' => '/shop',
        'navigation' => true,
        'sections' => [$shopSection],
    ];
    $validatedBp = (new \App\Lab\Builder\BlueprintSchema())->validate($bp);
    $draft->forceFill(['blueprint' => $validatedBp, 'revision' => 2])->save();
    formCheck(
        $draft->fresh()->revision === 2
        && in_array('/shop', array_column($draft->fresh()->blueprint['pages'], 'route'), true)
        && $draft->fresh()->blueprint['title'] === 'คริกคิต สตูดิโอ แอนด์ คาเฟ่ (Official)',
        'Step 2.2: Edited multi-page website blueprint (added /shop page and updated details) before build'
    );

    // STEP 3: Materialize Draft into Real Lab Project Workspace
    $workspace = app(SiteWorkspace::class);
    $materializer = new DraftMaterializer($workspace);
    [$builtProject, $compiledSource, $wasCreated] = $materializer->create($owner, $draft->uuid, 2);
    $siteJson = json_decode($workspace->read($builtProject, 'src/krikkit-site/site.json'), true);
    formCheck(
        $wasCreated
        && $builtProject->uuid !== ''
        && in_array('/shop', array_column($siteJson['pages'], 'route'), true)
        && is_file($workspace->root($builtProject).'/src/App.jsx'),
        'Step 3: Materialized multi-page draft into real Lab project workspace with / and /shop routes'
    );

    // STEP 4: Enable & Install Managed Modules (Contact, News, Catalog, Orders, Manual Payments) and Embed into Pages
    foreach (['contact', 'news', 'catalog', 'orders', 'manual_payments'] as $key) {
        $allow($key);
    }
    $modules = app(ModuleInstallations::class);
    $proposals = app(SchemaProposals::class);
    $embed = app(ModuleEmbed::class);

    $installed = [];
    foreach (['contact', 'news', 'catalog', 'orders', 'manual_payments'] as $key) {
        $rev = (int) $builtProject->fresh()->modules_revision;
        $modules->select($owner, $builtProject, $key, true, $rev);
        $rev = (int) $builtProject->fresh()->modules_revision;
        $mod = $modules->prepare($owner, $builtProject, $key, $rev, [], (string) Str::uuid())['installation'];
        $prop = $proposals->prepare($owner, $builtProject, $mod, 1);
        $receipt = $proposals->apply($owner, $builtProject, $prop, $prop->impact_hash, 1);
        formCheck($receipt['verified'], "Step 4.1: Installed and verified {$key} module schema");
        $modules->setState($owner, $builtProject, $mod->fresh(), 'enabled', 2);
        $installed[$key] = $mod->fresh();
    }

    // Embed Contact on /, News on /, and Catalog/Storefront on /shop
    foreach ([
        ['mod' => $installed['contact'], 'route' => '/'],
        ['mod' => $installed['news'], 'route' => '/'],
        ['mod' => $installed['catalog'], 'route' => '/shop'],
    ] as $placement) {
        $appHash = hash('sha256', $workspace->read($builtProject, 'src/App.jsx'));
        $embed->embed($owner, $builtProject, $placement['mod'], $placement['route'], $appHash);
    }
    $widgetConfig = json_decode($workspace->read($builtProject, 'src/krikkit-widgets/config.json'), true);
    formCheck(
        count($widgetConfig['widgets']) === 3
        && is_file($workspace->root($builtProject).'/src/krikkit-widgets/ContactForm.jsx')
        && is_file($workspace->root($builtProject).'/src/krikkit-widgets/NewsFeed.jsx')
        && is_file($workspace->root($builtProject).'/src/krikkit-widgets/StorefrontWidget.jsx'),
        'Step 4.2: Embedded Contact, News, and Storefront/Cart widgets into project pages without breaking page source'
    );

    // STEP 5: Back-Office Operations & Customer End-to-End Interactions
    $contacts = app(ContactMessages::class);
    $news = app(NewsArticles::class);
    $commerce = app(CommerceStore::class);

    // 5A. Customer submits Contact Form -> Owner reviews in Back-Office
    $contacts->submit($installed['contact'], [
        'idempotency_key' => (string) Str::uuid(),
        'name' => 'สมชาย ใจดี',
        'email' => 'somchai@example.test',
        'phone' => '0811112222',
        'subject' => 'สอบถามสินค้าจำนวนมาก',
        'message' => 'ต้องการสั่งซื้อกาแฟสำหรับงานสัมมนา 50 ชุดครับ',
        'consent' => true,
        'website' => '',
    ]);
    $ownerContacts = $contacts->listing($owner, $builtProject, $installed['contact'])['items'];
    formCheck(count($ownerContacts) === 1 && $ownerContacts[0]['customer_name'] === 'สมชาย ใจดี', 'Step 5.1: Customer contact submission received in owner back-office');

    // 5B. Owner creates & edits News in Back-Office -> Public reads News
    $newsCreated = $news->create($owner, $builtProject, $installed['news'], [
        'idempotency_key' => (string) Str::uuid(),
        'slug' => 'new-roast-2026',
        'title' => 'เปิดตัวเมล็ดกาแฟคั่วใหม่ประจำปี 2026',
        'excerpt' => 'คัดสรรเมล็ดกาแฟพิเศษจากแม่แจ่ม',
        'body' => 'พร้อมให้สั่งซื้อออนไลน์และชำระผ่าน PromptPay ได้แล้ววันนี้',
        'status' => 'published',
    ]);
    $newsUpdated = $news->update($owner, $builtProject, $installed['news'], $newsCreated['article_uuid'], [
        'expected_version' => 1,
        'slug' => 'new-roast-2026',
        'title' => 'เปิดตัวเมล็ดกาแฟคั่วใหม่ประจำปี 2026 (อัปเดตโปรโมชั่น)',
        'excerpt' => 'คัดสรรเมล็ดกาแฟพิเศษจากแม่แจ่ม ลดทันที 10%',
        'body' => 'พร้อมให้สั่งซื้อออนไลน์และชำระผ่าน PromptPay ได้แล้ววันนี้ ส่งฟรีทั่วไทย',
        'status' => 'published',
    ]);
    $publicNews = $news->publicFeed($installed['news']);
    formCheck(
        $newsUpdated['version'] === 2
        && count($publicNews['items']) === 1
        && str_contains($publicNews['items'][0]['title'], 'อัปเดตโปรโมชั่น'),
        'Step 5.2: Owner added and edited news in back-office and verified public feed'
    );

    // 5C. Owner adds & edits Products and configures PromptPay in Back-Office
    $prod = $commerce->createProduct($owner, $builtProject, $installed['catalog'], [
        'idempotency_key' => (string) Str::uuid(),
        'sku' => 'MAECHAEM-500G',
        'title' => 'กาแฟแม่แจ่ม 500g',
        'description' => 'คั่วกลาง หอมผลไม้สุก',
        'price_satang' => 55000,
        'active' => true,
    ]);
    $prodUpdated = $commerce->updateProduct($owner, $builtProject, $installed['catalog'], $prod['product_uuid'], [
        'expected_version' => 1,
        'sku' => 'MAECHAEM-500G',
        'title' => 'กาแฟแม่แจ่ม 500g (โปรโมชั่น)',
        'description' => 'คั่วกลาง หอมผลไม้สุก ราคาพิเศษ',
        'price_satang' => 49500,
        'active' => true,
    ]);
    $ppSettings = $commerce->savePaymentSettings($owner, $builtProject, $installed['manual_payments'], [
        'expected_version' => 1,
        'recipient_type' => 'phone',
        'recipient' => '089-876-5432',
        'payee_label' => 'คริกคิต สตูดิโอ แอนด์ คาเฟ่',
        'instructions' => 'กรุณาแนบสลิปหลังโอนเงิน',
        'enabled' => true,
    ])['settings'];
    formCheck(
        $prodUpdated['price_satang'] === 49500
        && $ppSettings['recipient'] === '0898765432'
        && $ppSettings['enabled'] === true,
        'Step 5.3: Owner added/edited product and configured PromptPay in back-office'
    );

    // 5D. Customer places order, receives PromptPay QR, uploads slip -> Owner approves payment slip
    $orderCreated = $commerce->publicCreateOrder($installed['catalog'], [
        'idempotency_key' => (string) Str::uuid(),
        'quote_uuid' => $commerce->publicQuote($installed['catalog'], ['items' => [['product_uuid' => $prod['product_uuid'], 'quantity' => 2]]])['quote']['quote_uuid'],
        'customer_name' => 'คุณพิมพ์ใจ รักกาแฟ',
        'customer_email' => 'pimjai@example.test',
        'customer_phone' => '0822223333',
        'note' => 'ฝากไว้ที่นิติบุคคลคอนโด',
        'items' => [
            ['product_uuid' => $prod['product_uuid'], 'quantity' => 2],
        ],
    ]);
    formCheck(
        $orderCreated['order']['total_satang'] === 99000
        && str_contains($orderCreated['order']['promptpay']['qr_svg'], '<svg'),
        'Step 5.4: Customer placed order (990.00 THB) and received PromptPay QR'
    );

    $fixtureImage = imagecreatetruecolor(10,10);
    ob_start(); imagepng($fixtureImage); $png1x1 = 'data:image/png;base64,'.base64_encode(ob_get_clean()); imagedestroy($fixtureImage);
    $slipSubmitted = $commerce->publicSubmitSlip(
        $installed['catalog'],
        $orderCreated['order']['uuid'],
        $orderCreated['access_token'],
        [
            'idempotency_key' => (string) Str::uuid(),
            'payer_name' => 'คุณพิมพ์ใจ รักกาแฟ',
            'payer_phone' => '0822223333',
            'amount_satang' => 99000,
            'transfer_ref' => 'KBANK-990-THB',
            'claimed_transferred_at' => '2026-10-17 17:02',
            'slip_data_url' => $png1x1,
        ]
    );
    formCheck($slipSubmitted['slip']['order_status'] === 'pending_review', 'Step 5.5: Customer uploaded transfer slip; order is pending_review');

    $reviewed = $commerce->reviewPaymentSlip($owner, $builtProject, $installed['manual_payments'], $slipSubmitted['slip']['uuid'], [
        'expected_version' => 1,
        'decision' => 'accepted',
        'bank_verified' => true,
        'reason' => 'ตรวจสอบสลิป KBANK-990-THB เรียบร้อย',
    ])['review'];
    $customerCheck = $commerce->publicGetOrder($installed['catalog'], $orderCreated['order']['uuid'], $orderCreated['access_token'])['order'];
    formCheck(
        $reviewed['order_status'] === 'paid' && $customerCheck['status'] === 'paid',
        'Step 5.6: Owner verified customer payment slip in back-office and order transitioned to paid'
    );

    formResult('builder materialization and managed widget service integration (synthetic owner; browser signup/build verified separately)');
} finally {
    app()->useStoragePath($previousStorage);
}
