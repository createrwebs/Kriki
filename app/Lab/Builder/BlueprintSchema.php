<?php

namespace App\Lab\Builder;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use JsonException;
use Normalizer;

final class BlueprintSchema
{
    public function validate(array $blueprint): array
    {
        try {
            $json = json_encode($blueprint, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw ValidationException::withMessages(['blueprint' => 'Blueprint must contain valid JSON text.']);
        }
        if (strlen($json) > 128 * 1024) {
            throw ValidationException::withMessages(['blueprint' => 'Blueprint may not exceed 128 KiB of encoded JSON.']);
        }
        $keys = ['schema_version', 'kind', 'start_mode', 'template_id', 'title', 'summary', 'shared', 'theme', 'pages'];
        if (array_diff(array_keys($blueprint), $keys) !== []) {
            throw ValidationException::withMessages(['blueprint' => 'Blueprint contains unsupported fields.']);
        }

        $catalog = (new TemplateCatalog)->all();
        $exactInteger = static function ($attribute, $value, $fail): void {
            if (! is_int($value)) $fail('Expected an integer.');
        };
        $exactBoolean = static function ($attribute, $value, $fail): void {
            if (! is_bool($value)) $fail('Expected a boolean.');
        };
        $meaningfulText = static function ($attribute, $value, $fail): void {
            if (is_string($value) && preg_match('/^[\s\p{Z}\x{FEFF}\x{0000}]*$/uD', $value) === 1) {
                $fail('Enter text containing more than whitespace.');
            }
        };
        $uuidRule = 'regex:/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/iD';
        Validator::make($blueprint, [
            'schema_version' => ['required', $exactInteger, Rule::in([1])],
            'kind' => ['required', 'string', Rule::in(array_column($catalog['kinds'], 'id'))],
            'start_mode' => ['required', 'string', Rule::in(['template'])],
            'template_id' => ['required', 'string', Rule::in(array_column($catalog['templates'], 'id'))],
            'title' => ['required', 'string', 'max:120', $meaningfulText],
            'summary' => ['present', 'string', 'max:2000'],
            'shared' => ['required', 'array:brand,footer'],
            'shared.brand' => ['required', 'string', 'max:80', $meaningfulText],
            'shared.footer' => ['present', 'string', 'max:240'],
            'theme' => ['required', 'array:palette,font,radius'],
            'theme.palette' => ['required', 'string', Rule::in(array_keys($catalog['palettes']))],
            'theme.font' => ['required', 'string', Rule::in(array_keys($catalog['fonts']))],
            'theme.radius' => ['required', 'string', Rule::in(array_keys($catalog['radii']))],
            'pages' => ['required', 'array', 'list', 'min:1', 'max:5'],
            'pages.*' => ['required', 'array:id,title,route,navigation,sections'],
            'pages.*.id' => ['required', 'string', $uuidRule],
            'pages.*.title' => ['required', 'string', 'max:80', $meaningfulText],
            'pages.*.route' => ['required', 'string', 'max:160'],
            'pages.*.navigation' => ['required', $exactBoolean],
            'pages.*.sections' => ['required', 'array', 'list', 'min:1', 'max:12'],
            'pages.*.sections.*' => ['required', 'array:id,type,variant,heading,body,items'],
            'pages.*.sections.*.id' => ['required', 'string', $uuidRule],
            'pages.*.sections.*.type' => ['required', 'string', Rule::in(array_column($catalog['section_types'], 'id'))],
            'pages.*.sections.*.variant' => ['required', 'string'],
            'pages.*.sections.*.heading' => ['required', 'string', 'max:120', $meaningfulText],
            'pages.*.sections.*.body' => ['present', 'string', 'max:2000'],
            'pages.*.sections.*.items' => ['present', 'array', 'list', 'max:6'],
            'pages.*.sections.*.items.*' => ['required', 'array:id,title,body'],
            'pages.*.sections.*.items.*.id' => ['required', 'string', $uuidRule],
            'pages.*.sections.*.items.*.title' => ['required', 'string', 'max:120', $meaningfulText],
            'pages.*.sections.*.items.*.body' => ['present', 'string', 'max:500'],
        ])->validate();

        $template = collect($catalog['templates'])->firstWhere('id', $blueprint['template_id']);
        if (! in_array($blueprint['kind'], $template['kinds'], true)) {
            throw ValidationException::withMessages(['template_id' => 'Template is incompatible with this website kind.']);
        }
        $sectionTypes = array_column($catalog['section_types'], null, 'id');
        $reserved = ['lab', 'api', 'login', 'logout', 'register', 'dashboard', 'settings', 'projects', 'assets', 'build', 'storage', 'oauth'];
        $ids = [];
        $routes = [];
        $visible = false;
        $errors = [];
        foreach ($blueprint['pages'] as $pageIndex => $page) {
            $pagePath = 'pages.'.$pageIndex;
            $route = $page['route'];
            // Unicode combining marks are necessary for natural Thai paths. NFC is checked, never repaired.
            $segment = '[\p{L}\p{N}][\p{L}\p{N}\p{M}]*(?:-[\p{L}\p{N}][\p{L}\p{N}\p{M}]*)*';
            $validRoute = $route === '/' || (
                preg_match('~^/'.$segment.'(?:/'.$segment.')*$~uD', $route) === 1
                && mb_strtolower($route, 'UTF-8') === $route
                && Normalizer::isNormalized($route, Normalizer::FORM_C)
                && ! in_array(explode('/', $route)[1], $reserved, true)
            );
            if (! $validRoute || ($pageIndex === 0 && $route !== '/') || ($pageIndex !== 0 && $route === '/') || isset($routes[$route])) {
                $errors[$pagePath.'.route'] = 'Use a unique canonical route; Home must be first at / and reserved routes are unavailable.';
            }
            $routes[$route] = true;
            $visible = $visible || $page['navigation'];
            $idPaths = [$pagePath.'.id' => $page['id']];
            foreach ($page['sections'] as $sectionIndex => $section) {
                $sectionPath = $pagePath.'.sections.'.$sectionIndex;
                $idPaths[$sectionPath.'.id'] = $section['id'];
                if (! in_array($section['variant'], $sectionTypes[$section['type']]['variants'], true)) {
                    $errors[$sectionPath.'.variant'] = 'Choose a variant supported by this section type.';
                }
                foreach ($section['items'] as $itemIndex => $item) {
                    $idPaths[$sectionPath.'.items.'.$itemIndex.'.id'] = $item['id'];
                }
            }
            foreach ($idPaths as $path => $id) {
                $identity = strtolower($id);
                if (isset($ids[$identity])) $errors[$path] = 'IDs must be unique across every page, section, and item.';
                $ids[$identity] = true;
            }
        }
        if (! $visible) $errors['pages'] = 'At least one page must be visible in navigation.';
        if ($errors !== []) throw ValidationException::withMessages($errors);

        return $blueprint;
    }
}
