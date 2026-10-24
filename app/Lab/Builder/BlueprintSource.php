<?php

namespace App\Lab\Builder;

final class BlueprintSource
{
    public const RENDERER_VERSION = 1;

    /** Generate only code-owned React source; customer text is data, never executable source. */
    public function compile(array $blueprint, string $draftUuid, int $revision): array
    {
        $blueprint = (new BlueprintSchema)->validate($blueprint);
        $hash = hash('sha256', $this->json($blueprint, false));
        $public = array_intersect_key($blueprint, array_flip(['kind', 'title', 'shared', 'theme', 'pages']));
        $catalog = array_intersect_key((new TemplateCatalog)->all(), array_flip(['palettes', 'fonts', 'radii']));
        $manifest = ['draft_uuid' => $draftUuid, 'draft_revision' => $revision, 'blueprint_hash' => $hash, 'renderer_version' => self::RENDERER_VERSION];
        $files = [
            'package.json' => $this->json([
                'name' => 'krikkit-generated-site', 'private' => true, 'version' => '1.0.0', 'type' => 'module',
                'scripts' => ['dev' => 'vite', 'build' => 'vite build', 'preview' => 'vite preview'],
                'dependencies' => ['react' => '19.1.1', 'react-dom' => '19.1.1'],
                'devDependencies' => ['vite' => '^7.0.0'],
            ]),
            'index.html' => '<!doctype html><html lang="th"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>'.e($blueprint['title']).'</title><link rel="preconnect" href="https://fonts.bunny.net"><link href="https://fonts.bunny.net/css?family=sora:400,500,600,700&amp;display=swap" rel="stylesheet"></head><body><div id="root"></div><script type="module" src="/src/main.jsx"></script></body></html>'."\n",
            'vite.config.js' => "export default { build: { target: 'es2022' } }\n",
            'src/main.jsx' => "import React from 'react'\nimport { createRoot } from 'react-dom/client'\nimport App from './App.jsx'\nimport './krikkit-site/site.css'\nimport './index.css'\ncreateRoot(document.getElementById('root')).render(<App />)\n",
            'src/App.jsx' => file_get_contents(resource_path('builder/generated-app.jsx')),
            'src/index.css' => "*{box-sizing:border-box}body{margin:0}button,input,textarea,select{font:inherit}h1,h2,h3,p{margin:0}a{color:inherit}#root>.bp-site{min-height:100vh;border:0;border-radius:0;font-size:16px}#root>.bp-site .bp-header,#root>.bp-site .bp-section,#root>.bp-site .bp-footer{padding-inline:max(24px,calc((100vw - 1100px)/2))}#root>.bp-site .bp-header nav a{font-size:14px}\n",
            'src/krikkit-site/SitePage.jsx' => file_get_contents(resource_path('js/builder/SitePage.jsx')),
            'src/krikkit-site/site.css' => file_get_contents(resource_path('js/builder/site.css')),
            'src/krikkit-site/site.json' => $this->json($public),
            'src/krikkit-site/catalog.json' => $this->json($catalog),
            'src/krikkit-site/source-manifest.json' => $this->json($manifest),
        ];

        if (preg_match_all('~/assets/builder-uploads/([a-f0-9\-]{36}\.(?:jpg|png|webp|gif|avif))~i', $files['src/krikkit-site/site.json'], $matches)) {
            foreach (array_unique($matches[1]) as $uploadFile) {
                $diskPath = public_path('assets/builder-uploads/'.$uploadFile);
                if (is_file($diskPath)) {
                    $files['public/assets/builder-uploads/'.$uploadFile] = (string) file_get_contents($diskPath);
                }
            }
        }

        return ['manifest' => $manifest, 'files' => $files];
    }

    private function json(array $value, bool $pretty = true): string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR | ($pretty ? JSON_PRETTY_PRINT : 0)).($pretty ? "\n" : '');
    }
}
