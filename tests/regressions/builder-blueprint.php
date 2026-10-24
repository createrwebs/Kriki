<?php

// Isolate providers before bootstrap; no configured application database is opened.
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=:memory:');
require __DIR__.'/forms-bootstrap.php';

use App\Lab\Builder\BlueprintSchema;
use App\Lab\Builder\TemplateCatalog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

Http::fake();
Notification::fake();
formCheck(class_exists(TemplateCatalog::class) && class_exists(BlueprintSchema::class), 'Builder catalog and blueprint schema are implemented');
$catalog = new TemplateCatalog;
$schema = new BlueprintSchema;
$data = $catalog->all();
formCheck($data === json_decode(file_get_contents(resource_path('builder/catalog.json')), true, 512, JSON_THROW_ON_ERROR), 'Catalog comes from fixed shared JSON resource');
formCheck(array_keys($data) === ['schema_version', 'kinds', 'palettes', 'fonts', 'radii', 'section_types', 'templates'], 'Catalog has the frozen shape');
formCheck($data['schema_version'] === 1 && array_column($data['kinds'], 'id') === ['business', 'ecommerce', 'portfolio', 'blog', 'other'], 'All five kinds are available');
formCheck(array_column($data['templates'], 'id') === ['business-studio', 'portfolio-editorial', 'blog-journal', 'storefront-demo'], 'Four original template packages');
formCheck(array_keys($data['palettes']) === ['forest', 'indigo', 'sand'] && array_keys($data['fonts']) === ['sans', 'serif'] && array_keys($data['radii']) === ['soft', 'square'], 'Theme allowlists match shared contract');
formCheck(array_column($data['section_types'], 'id') === ['hero', 'feature-grid', 'text', 'cta', 'cards'], 'Five supported section types');
foreach ($data['palettes'] as $palette) {
    formCheck(array_keys($palette) === ['label', 'background', 'surface', 'text', 'accent'], 'Palette has fixed token keys');
    foreach (['background', 'surface', 'text', 'accent'] as $token) {
        formCheck((bool) preg_match('/^#[0-9a-f]{6}$/i', $palette[$token]), 'Palette uses trusted hex values');
    }
}
foreach ($data['templates'] as $template) {
    foreach ($template['pages'] as $page) {
        formCheck(array_keys($page) === ['title', 'route', 'sections'], 'Catalog page has no ID before factory');
        foreach ($page['sections'] as $section) {
            formCheck(array_keys($section) === ['type', 'variant', 'heading', 'body', 'items'], 'Catalog section has no ID before factory');
            foreach ($section['items'] as $item) formCheck(array_keys($item) === ['title', 'body'], 'Catalog item has no ID before factory');
        }
    }
    formCheck((bool) preg_match('/[ก-๙]/u', $template['description']) && str_contains($template['description'], 'ตัวอย่าง'), 'Original Thai template is clearly a demo');
    foreach ($template['kinds'] as $kind) {
        $blueprint = $catalog->blueprint($kind, $template['id'], 'เว็บไซต์ตัวอย่าง', 'สรุปที่เจ้าของแก้ไข');
        formCheck($schema->validate($blueprint) === $blueprint, 'Every compatible template validates without transformation');
        formCheck($blueprint['kind'] === $kind && $blueprint['template_id'] === $template['id'] && $blueprint['start_mode'] === 'template', 'Factory preserves selected kind and template');
        formCheck($blueprint['title'] === 'เว็บไซต์ตัวอย่าง' && $blueprint['summary'] === 'สรุปที่เจ้าของแก้ไข', 'Factory preserves title and summary');
        formCheck($blueprint['shared'] === ['brand' => 'เว็บไซต์ตัวอย่าง', 'footer' => 'ข้อมูลและเนื้อหาตัวอย่างสมมติ'] && $blueprint['theme'] === ['palette' => 'forest', 'font' => 'sans', 'radius' => 'soft'], 'Factory provides shared and theme defaults');
        formCheck($blueprint['pages'][0]['route'] === '/' && $blueprint['pages'][0]['title'] === 'Home' && count($blueprint['pages']) <= 5, 'Factory starts with Home and bounded page count');
        $ids = [];
        foreach ($blueprint['pages'] as $page) {
            $ids[] = $page['id'];
            foreach ($page['sections'] as $section) {
                $ids[] = $section['id'];
                foreach ($section['items'] as $item) $ids[] = $item['id'];
            }
        }
        formCheck(count($ids) === count(array_unique($ids)) && count(array_filter($ids, fn ($id) => Str::isUuid($id))) === count($ids), 'Factory assigns globally unique UUIDs at every level');
        formCheck($schema->validate($blueprint)['pages'] === $blueprint['pages'], 'Validation keeps identities and order stable');
        formCheck($catalog->blueprint($kind, $template['id'], 'เว็บไซต์ตัวอย่าง')['pages'][0]['id'] !== $blueprint['pages'][0]['id'], 'New factory invocation assigns fresh IDs');
    }
}

$base = $catalog->blueprint('business', 'business-studio', 'เว็บไซต์ตัวอย่าง');
function builderInvalid(array $blueprint, string $field, string $label): void
{
    try {
        (new BlueprintSchema)->validate($blueprint);
    } catch (ValidationException $error) {
        formCheck(array_key_exists($field, $error->errors()), $label.' has precise field error '.$field);
        return;
    }
    formCheck(false, $label);
}
function builderSet(array $blueprint, string $path, mixed $value): array
{
    data_set($blueprint, $path, $value);
    return $blueprint;
}

foreach (['kind' => 'unknown', 'template_id' => 'missing', 'start_mode' => 'ai', 'schema_version' => 2, 'title' => '', 'summary' => null] as $field => $value) {
    builderInvalid(builderSet($base, $field, $value), $field, 'Invalid root value rejected');
}
foreach ([['business', 'portfolio-editorial'], ['ecommerce', 'business-studio'], ['blog', 'storefront-demo'], ['unknown', 'business-studio']] as [$kind, $template]) {
    formInvalid(fn () => $catalog->blueprint($kind, $template, 'Fixture'), 'Factory rejects unknown/incompatible selection');
}
formInvalid(fn () => $catalog->blueprint('business', 'business-studio', ''), 'Factory validates supplied title');
formInvalid(fn () => $catalog->blueprint('business', 'business-studio', str_repeat('ก', 121)), 'Factory validates title length');
formInvalid(fn () => $catalog->blueprint('business', 'business-studio', 'Fixture', str_repeat('ก', 2001)), 'Factory validates summary length');
formCheck(mb_strlen($catalog->blueprint('business', 'business-studio', str_repeat('ก', 120))['shared']['brand']) === 80, 'Long valid title creates bounded brand default');

$objects = [
    '' => ['schema_version', 'kind', 'start_mode', 'template_id', 'title', 'summary', 'shared', 'theme', 'pages'],
    'shared' => ['brand', 'footer'], 'theme' => ['palette', 'font', 'radius'],
    'pages.0' => ['id', 'title', 'route', 'navigation', 'sections'],
    'pages.0.sections.0' => ['id', 'type', 'variant', 'heading', 'body', 'items'],
    'pages.0.sections.1.items.0' => ['id', 'title', 'body'],
];
foreach ($objects as $path => $keys) {
    $object = $path === '' ? $base : data_get($base, $path);
    formCheck(is_array($object), 'Fixture covers object '.$path);
    foreach ($keys as $key) {
        $copy = $object;
        unset($copy[$key]);
        builderInvalid($path === '' ? $copy : builderSet($base, $path, $copy), ltrim($path.'.'.$key, '.'), 'Every key is required');
    }
    foreach (['user_id', 'project_id', 'html', 'css', 'url', 'modules'] as $key) {
        $copy = $object + [$key => 'forged'];
        builderInvalid($path === '' ? $copy : builderSet($base, $path, $copy), $path === '' ? 'blueprint' : $path, 'Unknown fields reject at every object level');
    }
}
foreach (['title' => 120, 'summary' => 2000, 'shared.brand' => 80, 'shared.footer' => 240, 'pages.0.title' => 80, 'pages.0.sections.0.heading' => 120, 'pages.0.sections.0.body' => 2000, 'pages.0.sections.1.items.0.title' => 120, 'pages.0.sections.1.items.0.body' => 500] as $path => $max) {
    $valid = builderSet($base, $path, str_repeat('ก', $max));
    formCheck($schema->validate($valid) === $valid, 'Thai maximum string length accepted '.$path);
    builderInvalid(builderSet($base, $path, str_repeat('ก', $max + 1)), $path, 'Maximum string length enforced');
    foreach ([null, true, 1, 1.5, []] as $value) builderInvalid(builderSet($base, $path, $value), $path, 'Text has exact string type');
    if (! in_array($path, ['summary', 'shared.footer', 'pages.0.sections.0.body', 'pages.0.sections.1.items.0.body'], true)) builderInvalid(builderSet($base, $path, ''), $path, 'Nonempty text required');
}
foreach (['1', 1.0, true, null] as $value) builderInvalid(builderSet($base, 'schema_version', $value), 'schema_version', 'Version must be exact integer');
foreach (['title', 'shared.brand', 'pages.0.title', 'pages.0.sections.0.heading', 'pages.0.sections.1.items.0.title'] as $path) {
    foreach (["\u{00A0}", "\u{0085}", "\u{FEFF}", "\u{1680}", "\u{2000}", "\u{200A}", "\u{2028}", "\u{2029}", "\u{202F}", "\u{205F}", "\u{3000}", " \t\u{00A0}\u{0085}\u{FEFF}", "\0", "\0 ", "\0\u{00A0}\u{FEFF}"] as $blank) {
        builderInvalid(builderSet($base, $path, $blank), $path, 'Required text rejects Unicode whitespace-only input');
    }
    $valid = builderSet($base, $path, " \u{00A0}ข้อความ\u{00A0} ");
    formCheck($schema->validate($valid) === $valid, 'Unicode whitespace surrounding real text remains unchanged');
    $valid = builderSet($base, $path, "\0ข้อความ\0");
    formCheck($schema->validate($valid) === $valid, 'NUL surrounding real text remains unchanged');
}
foreach (['summary', 'shared.footer', 'pages.0.sections.0.body', 'pages.0.sections.1.items.0.body'] as $path) {
    $valid = builderSet($base, $path, " \t\u{00A0}\u{0085}\u{FEFF}");
    formCheck($schema->validate($valid) === $valid, 'Optional text preserves Unicode whitespace-only content');
    $valid = builderSet($base, $path, "\0 \u{00A0}\u{FEFF}");
    formCheck($schema->validate($valid) === $valid, 'Optional text preserves NUL and Unicode whitespace unchanged');
}
foreach (['true', '0', 0, 1, null] as $value) builderInvalid(builderSet($base, 'pages.0.navigation', $value), 'pages.0.navigation', 'Navigation must be exact boolean');
foreach (['theme.palette' => 'red', 'theme.font' => 'url(evil)', 'theme.radius' => 8, 'pages.0.sections.0.type' => 'script', 'pages.0.sections.0.variant' => 'default', 'pages.0.sections.1.variant' => 'split'] as $path => $value) builderInvalid(builderSet($base, $path, $value), $path, 'Unsupported type/token/variant rejected');
foreach (['pages', 'pages.0.sections', 'pages.0.sections.0.items'] as $path) {
    foreach ([null, 'array', true, ['named' => data_get($base, $path.'.0')]] as $value) builderInvalid(builderSet($base, $path, $value), $path, 'Collections require JSON lists');
}
builderInvalid(builderSet($base, 'pages', []), 'pages', 'At least one page');
builderInvalid(builderSet($base, 'pages.0.sections', []), 'pages.0.sections', 'At least one section per page');
foreach (['pages' => 6, 'pages.0.sections' => 13, 'pages.0.sections.1.items' => 7] as $path => $count) builderInvalid(builderSet($base, $path, array_fill(0, $count, data_get($base, $path.'.0'))), $path, 'Collection count limit');
foreach (['pages.0.id', 'pages.0.sections.0.id', 'pages.0.sections.1.items.0.id'] as $path) {
    builderInvalid(builderSet($base, $path, 'not-uuid'), $path, 'Every ID is UUID');
    if ($path !== 'pages.0.id') builderInvalid(builderSet($base, $path, $base['pages'][0]['id']), $path, 'IDs globally unique across levels');
    foreach ([
        '00000000-0000-0000-0000-000000000000',
        '12345678-abcd-0000-8000-0123456789ab',
        '12345678-abcd-9000-8000-0123456789ab',
        '12345678-abcd-4000-0000-0123456789ab',
        '12345678-abcd-4000-7000-0123456789ab',
        '12345678-abcd-4000-c000-0123456789ab',
        'ffffffff-ffff-ffff-ffff-ffffffffffff',
        '{12345678-abcd-4000-8000-0123456789ab}',
        'urn:uuid:12345678-abcd-4000-8000-0123456789ab',
        "12345678-abcd-4000-8000-0123456789ab\n",
    ] as $id) builderInvalid(builderSet($base, $path, $id), $path, 'Nil or non-RFC UUID shape rejected');
    for ($version = 1; $version <= 8; $version++) {
        foreach (['8', '9', 'a', 'b'] as $variant) {
            $id = '12345678-abcd-'.$version.'000-'.$variant.'000-0123456789ab';
            foreach ([$id, strtoupper($id)] as $case) {
                $valid = builderSet($base, $path, $case);
                formCheck($schema->validate($valid) === $valid, 'RFC UUID version/variant and case preserve exact identity '.$path);
            }
        }
    }
}
builderInvalid(builderSet($base, 'pages.0.sections.0.id', strtoupper($base['pages'][0]['id'])), 'pages.0.sections.0.id', 'UUID uniqueness ignores case');
$noneVisible = $base;
foreach ($noneVisible['pages'] as &$page) $page['navigation'] = false;
unset($page);
builderInvalid($noneVisible, 'pages', 'At least one page appears in navigation');
builderInvalid(builderSet($base, 'pages.0.route', '/home'), 'pages.0.route', 'Home route is first');
builderInvalid(builderSet($base, 'pages.1.route', '/'), 'pages.1.route', 'Home route occurs only once');
builderInvalid(builderSet($base, 'pages.2.route', $base['pages'][1]['route']), 'pages.2.route', 'Duplicate route fails without rename');
foreach (['lab', 'api', 'login', 'logout', 'register', 'dashboard', 'settings', 'projects', 'assets', 'build', 'storage', 'oauth'] as $reserved) builderInvalid(builderSet($base, 'pages.1.route', '/'.$reserved.'/example'), 'pages.1.route', 'Reserved first segment rejected');
foreach (['home', '/About', '/ABC', '/École', '/foo/', '//foo', '/foo//bar', '/foo.bar', '/..', '/%61pi', '/a?b', '/a#b', '/a\\b', '/-foo', '/foo-', '/foo--bar', '/<script>', '/javascript:alert', "/foo\n", "/cafe\u{0301}", "/\u{0301}a", '/'.str_repeat('a', 160)] as $route) builderInvalid(builderSet($base, 'pages.1.route', $route), 'pages.1.route', 'Malformed route rejected unchanged');
foreach (['/บทความ', '/เกี่ยวกับ', '/café', '/サービス', '/services/design-2', '/apiary', '/a/lab', '/'.str_repeat('a', 159)] as $route) {
    $valid = builderSet($base, 'pages.1.route', $route);
    formCheck($schema->validate($valid) === $valid, 'Canonical lowercase Unicode route accepted '.$route);
}
$literal = builderSet($base, 'pages.0.sections.0.body', '<script>alert("demo")</script>');
formCheck($schema->validate($literal) === $literal, 'Model-looking HTML is retained only as escaped renderable text');
$oversize = $base;
for ($i = 0; $i < 12; $i++) {
    $section = $base['pages'][0]['sections'][1];
    $section['id'] = (string) Str::uuid();
    $section['body'] = str_repeat('ก', 2000);
    $section['items'] = [];
    for ($j = 0; $j < 6; $j++) $section['items'][] = ['id' => (string) Str::uuid(), 'title' => str_repeat('ก', 120), 'body' => str_repeat('ก', 500)];
    $oversize['pages'][0]['sections'][$i] = $section;
}
formCheck(strlen(json_encode($oversize, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)) > 131072, 'Oversize fixture exceeds 128 KiB despite individual valid field limits');
builderInvalid($oversize, 'blueprint', 'Total encoded JSON limit enforced');
$boundary = $base;
foreach ($boundary['pages'] as &$page) {
    $page['sections'] = [];
    for ($i = 0; $i < 12; $i++) {
        $items = [];
        for ($j = 0; $j < 6; $j++) $items[] = ['id' => (string) Str::uuid(), 'title' => 'Item', 'body' => ''];
        $page['sections'][] = ['id' => (string) Str::uuid(), 'type' => 'cards', 'variant' => 'default', 'heading' => 'Section', 'body' => '', 'items' => $items];
    }
}
unset($page);
$remaining = 131072 - strlen(json_encode($boundary, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));
foreach ($boundary['pages'] as &$page) {
    foreach ($page['sections'] as &$section) {
        $length = min($remaining, 2000);
        $section['body'] = str_repeat('x', $length);
        $remaining -= $length;
    }
    unset($section);
}
unset($page);
formCheck($remaining === 0 && strlen(json_encode($boundary, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)) === 131072, 'Boundary fixture is exactly 128 KiB');
formCheck($schema->validate($boundary) === $boundary, 'Exactly 128 KiB with five pages/twelve sections/six items validates unchanged');
$boundary['pages'][4]['sections'][11]['items'][5]['body'] = 'x';
builderInvalid($boundary, 'blueprint', '128 KiB plus one byte rejects');
$invalidUtf8 = builderSet($base, 'summary', "\xB1\x31");
builderInvalid($invalidUtf8, 'blueprint', 'Invalid JSON Unicode rejects cleanly');
formCheck(Http::recorded()->isEmpty() && Notification::sentNotifications() === [], 'No HTTP or notification transport is used');
formResult('builder catalog and strict blueprint');
