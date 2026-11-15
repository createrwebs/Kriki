<?php

namespace App\Lab\Builder;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class TemplateCatalog
{
    public function all(): array
    {
        return json_decode(file_get_contents(resource_path('builder/catalog.json')), true, 512, JSON_THROW_ON_ERROR);
    }

    public function blueprint(string $kind, string $templateId, string $title, string $summary = ''): array
    {
        $catalog = $this->all();
        if (! in_array($kind, array_column($catalog['kinds'], 'id'), true)) {
            throw ValidationException::withMessages(['kind' => 'Choose a supported website kind.']);
        }
        $template = collect($catalog['templates'])->firstWhere('id', $templateId);
        if ($template === null || ! in_array($kind, $template['kinds'], true)) {
            throw ValidationException::withMessages(['template_id' => 'Choose a template compatible with this website kind.']);
        }

        $pages = [];
        foreach ($template['pages'] as $page) {
            $sections = [];
            foreach ($page['sections'] as $section) {
                $items = [];
                foreach ($section['items'] as $item) {
                    $items[] = ['id' => (string) Str::uuid(), ...$item];
                }
                $sections[] = ['id' => (string) Str::uuid(), ...$section, 'items' => $items];
            }
            $pages[] = ['id' => (string) Str::uuid(), 'title' => $page['title'], 'route' => $page['route'], 'navigation' => true, 'sections' => $sections];
        }

        return (new BlueprintSchema)->validate([
            'schema_version' => 1,
            'kind' => $kind,
            'start_mode' => 'template',
            'template_id' => $templateId,
            'title' => $title,
            'summary' => $summary,
            'shared' => ['brand' => mb_substr($title, 0, 80), 'footer' => 'ข้อมูลและเนื้อหาตัวอย่างสมมติ'],
            'theme' => ['palette' => 'forest', 'font' => 'sans', 'radius' => 'soft'],
            'pages' => $pages,
        ]);
    }

    /** Code-owned mapping from a visual template to owner-reviewed modules. */
    public function integrationPlan(string $templateId): array
    {
        $templates = array_column($this->all()['templates'], null, 'id');
        abort_unless(isset($templates[$templateId]), 422, 'Unknown website template.');
        $plans = [
            'business-studio' => [['module' => 'contact', 'route' => '/contact', 'label' => 'ฟอร์มติดต่อ']],
            'portfolio-editorial' => [['module' => 'contact', 'route' => '/contact', 'label' => 'ฟอร์มติดต่อ']],
            'blog-journal' => [
                ['module' => 'news', 'route' => '/articles', 'label' => 'ข่าวสารและบทความ'],
                ['module' => 'contact', 'route' => '/contact', 'label' => 'ฟอร์มติดต่อ'],
            ],
            'storefront-demo' => [
                ['module' => 'catalog', 'route' => '/products', 'label' => 'สินค้า'],
                ['module' => 'orders', 'route' => '/products', 'label' => 'ตะกร้าและคำสั่งซื้อ'],
                ['module' => 'manual_payments', 'route' => '/products', 'label' => 'PromptPay และตรวจสลิป'],
            ],
        ];
        return [
            'version' => 1,
            'template_id' => $templateId,
            'setup_mode' => 'owner_reviewed_modules',
            'modules' => $plans[$templateId] ?? [],
            'notes' => ['เตรียมโมดูลและตรวจ schema ก่อนฝังวิดเจ็ต', 'การเปิดรับข้อมูลจริงต้องผ่าน readiness gate ของโปรเจกต์'],
        ];
    }
}
