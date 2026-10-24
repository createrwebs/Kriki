<?php

putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=:memory:');
require __DIR__.'/forms-bootstrap.php';

use App\Lab\Builder\BlueprintSource;
use App\Lab\Builder\TemplateCatalog;

formCheck(class_exists(BlueprintSource::class), 'Real blueprint source compiler exists');
$blueprint = (new TemplateCatalog)->blueprint('business', 'business-studio', 'แหล่งทดสอบ <script>alert(1)</script>', 'PRIVATE-BRIEF-MUST-NOT-EXPORT');
$blueprint['pages'][0]['sections'][0]['heading'] = 'Literal </script><script>alert(2)</script>';
$result = (new BlueprintSource)->compile($blueprint, '10000000-0000-4000-8000-000000000001', 7);
$files = $result['files'];
foreach (['package.json', 'index.html', 'vite.config.js', 'src/main.jsx', 'src/App.jsx', 'src/krikkit-site/SitePage.jsx', 'src/krikkit-site/site.css', 'src/krikkit-site/site.json', 'src/krikkit-site/source-manifest.json'] as $path) {
    formCheck(isset($files[$path]) && is_string($files[$path]) && $files[$path] !== '', 'Export contains real source '.$path);
}
formCheck($files['src/krikkit-site/SitePage.jsx'] === file_get_contents(resource_path('js/builder/SitePage.jsx')), 'Export uses exactly the preview renderer');
formCheck($files['src/krikkit-site/site.css'] === file_get_contents(resource_path('js/builder/site.css')), 'Export uses exactly the preview styles');
$public = json_decode($files['src/krikkit-site/site.json'], true, 512, JSON_THROW_ON_ERROR);
formCheck(!array_key_exists('summary', $public) && !str_contains(implode('', $files), 'PRIVATE-BRIEF-MUST-NOT-EXPORT'), 'Private brief never enters source/artifact');
formCheck($public['pages'] === $blueprint['pages'] && $public['theme'] === $blueprint['theme'], 'Exact edited pages and theme exported as data');
formCheck(!str_contains($files['index.html'], '<script>alert(1)</script>'), 'HTML title escaped');
formCheck(str_contains($files['index.html'], 'https://fonts.bunny.net/css?family=sora:400,500,600,700&amp;display=swap') || str_contains($files['index.html'], 'https://fonts.bunny.net/css?family=sora:400,500,600,700&display=swap'), 'Export loads the same selected Sora font as preview');
formCheck(!str_contains($files['src/App.jsx'], 'alert(2)') && str_contains($public['pages'][0]['sections'][0]['heading'], '<script>'), 'Text stays JSON data, never interpolated executable source');
$manifest = json_decode($files['src/krikkit-site/source-manifest.json'], true, 512, JSON_THROW_ON_ERROR);
formCheck($manifest['draft_revision'] === 7 && $manifest['blueprint_hash'] === hash('sha256', json_encode($blueprint, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)), 'Manifest binds exact saved snapshot/revision');
formCheck($result === (new BlueprintSource)->compile($blueprint, '10000000-0000-4000-8000-000000000001', 7), 'Compiler deterministic for identical snapshot');
if (getenv('BUILDER_SOURCE_FIXTURE_DIR')) {
    $target = getenv('BUILDER_SOURCE_FIXTURE_DIR');
    foreach ($files as $path => $body) {
        if (!is_dir(dirname($target.'/'.$path))) mkdir(dirname($target.'/'.$path), 0777, true);
        file_put_contents($target.'/'.$path, $body);
    }
}
formResult('blueprint real source compiler');
