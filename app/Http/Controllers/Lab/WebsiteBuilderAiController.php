<?php

namespace App\Http\Controllers\Lab;

use App\Ai\Data\ChatMessage;
use App\Ai\Data\ChatRequest;
use App\Ai\Lab\ThoughtBlockParser;
use App\Ai\ModelCatalog;
use App\Ai\ProviderFactory;
use App\Ai\Settings\AiSettingsRepository;
use App\Entitlement\EntitlementCatalog;
use App\Entitlement\EntitlementGate;
use App\Entitlement\Exceptions\EntitlementDeniedException;
use App\Entitlement\LabCreditMeter;
use App\Http\Controllers\Controller;
use App\Lab\Builder\BlueprintSchema;
use App\Lab\Builder\TemplateCatalog;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Normalizer;
use Throwable;

final class WebsiteBuilderAiController extends Controller
{
    public function edit(
        Request $request,
        BlueprintSchema $schema,
        TemplateCatalog $catalogService,
        ModelCatalog $models,
        ProviderFactory $providers,
        AiSettingsRepository $settings,
        EntitlementGate $entitlements,
        LabCreditMeter $credits,
    ): JsonResponse {
        abort_unless($request->user() instanceof User, 401);
        $request->validate([
            'prompt' => ['nullable', 'string', 'max:2000'],
            'scope' => ['sometimes', 'string', 'in:all,page,section'],
            'page_id' => ['nullable', 'uuid'],
            'section_id' => ['nullable', 'uuid'],
        ]);
        $catalog = $catalogService->all();
        $rawBlueprint = $request->input('blueprint');
        if (! is_array($rawBlueprint)) {
            throw ValidationException::withMessages(['blueprint' => 'Blueprint must be a valid JSON object.']);
        }

        $prompt = trim((string) $request->input('prompt', ''));
        if ($prompt === '') {
            $prompt = trim((string) ($rawBlueprint['summary'] ?? ''));
        }
        if ($prompt === '') {
            throw ValidationException::withMessages(['prompt' => 'กรุณาระบุคำสั่ง Prompt หรือรายละเอียดที่ต้องการให้ AI แก้ไข']);
        }
        $prompt = mb_substr($prompt, 0, 2000);

        $scope = (string) $request->input('scope', 'all');
        $pageId = $request->input('page_id') ? (string) $request->input('page_id') : null;
        $sectionId = $request->input('section_id') ? (string) $request->input('section_id') : null;

        $baseBlueprint = $schema->validate($rawBlueprint);
        // Validate actual selected identities before sending any content to a provider.
        $this->scopeBlueprint($baseBlueprint, $baseBlueprint, $scope, $pageId, $sectionId);

        $usedLlm = false;
        $candidate = null;

        try {
            $llmResult = $this->callLlm($prompt, $scope, $pageId, $sectionId, $baseBlueprint, $catalog, $models, $providers,
                $settings, $entitlements, $credits, $request->user());
            if (is_array($llmResult)) {
                $candidate = $this->sanitizeBlueprint($llmResult, $baseBlueprint, $catalog);
                $candidate = $this->scopeBlueprint($baseBlueprint, $candidate, $scope, $pageId, $sectionId);
                $candidate = $schema->validate($candidate);
                $usedLlm = true;
            }
        } catch (EntitlementDeniedException|QueryException $error) {
            throw $error;
        } catch (Throwable) {
            $candidate = null;
        }

        if ($candidate === null) {
            $smartEdited = $this->applySmartPromptEdits($baseBlueprint, $prompt, $scope, $pageId, $sectionId, $catalog);
            $candidate = $this->sanitizeBlueprint($smartEdited, $baseBlueprint, $catalog);
            $candidate = $this->scopeBlueprint($baseBlueprint, $candidate, $scope, $pageId, $sectionId);
            $candidate = $schema->validate($candidate);
        }

        return response()->json([
            'blueprint' => $candidate,
            'source' => $usedLlm ? 'ai' : 'smart-assistant',
            'message' => $usedLlm
                ? 'AI ปรับปรุงโครงสร้างและเนื้อหาหน้าเว็บตามคำสั่งเรียบร้อยแล้ว'
                : 'ตัวช่วยในเครื่องปรับแบบร่างตามคำสั่งแล้ว ครั้งนี้ไม่ได้ใช้ผลลัพธ์จาก AI ภายนอก',
        ])->header('Cache-Control', 'private, no-store');
    }

    private function scopeBlueprint(array $base, array $candidate, string $scope, ?string $pageId, ?string $sectionId): array
    {
        if ($scope === 'all') return $candidate;
        $pageIndex = array_search($pageId, array_column($base['pages'], 'id'), true);
        if ($pageIndex === false) throw ValidationException::withMessages(['page_id' => 'Choose an existing page for this edit.']);
        $candidatePage = collect($candidate['pages'])->first(fn(array $page) => strtolower($page['id']) === strtolower($pageId));
        if ($candidatePage === null) throw ValidationException::withMessages(['blueprint' => 'The edited result omitted the selected page.']);
        if ($scope === 'page') {
            $candidatePage['id'] = $base['pages'][$pageIndex]['id'];
            $base['pages'][$pageIndex] = $candidatePage;
            return $base;
        }
        $sectionIndex = array_search($sectionId, array_column($base['pages'][$pageIndex]['sections'], 'id'), true);
        if ($sectionIndex === false) throw ValidationException::withMessages(['section_id' => 'Choose an existing section on the selected page.']);
        $candidateSection = collect($candidatePage['sections'])->first(fn(array $section) => strtolower($section['id']) === strtolower($sectionId));
        if ($candidateSection === null) throw ValidationException::withMessages(['blueprint' => 'The edited result omitted the selected section.']);
        $candidateSection['id'] = $base['pages'][$pageIndex]['sections'][$sectionIndex]['id'];
        $base['pages'][$pageIndex]['sections'][$sectionIndex] = $candidateSection;
        return $base;
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $bytes = null;
        $ext = 'jpg';

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if (! $file || ! $file->isValid()) {
                throw ValidationException::withMessages(['image' => 'ไฟล์รูปภาพไม่ถูกต้อง']);
            }
            if ($file->getSize() > 8 * 1024 * 1024) {
                throw ValidationException::withMessages(['image' => 'ขนาดรูปภาพต้องไม่เกิน 8 MB']);
            }
            $bytes = file_get_contents($file->getRealPath());
        } else {
            $dataUrl = (string) $request->input('data_url', '');
            if (! preg_match('/^data:image\/(jpeg|jpg|png|webp|gif|avif);base64,(.+)$/is', $dataUrl, $matches)) {
                throw ValidationException::withMessages(['image' => 'รองรับเฉพาะไฟล์รูปภาพ JPG, PNG, WEBP, GIF หรือ AVIF']);
            }
            $decoded = base64_decode($matches[2], true);
            if ($decoded === false || strlen($decoded) > 8 * 1024 * 1024) {
                throw ValidationException::withMessages(['image' => 'ขนาดรูปภาพต้องไม่เกิน 8 MB']);
            }
            $bytes = $decoded;
        }

        if (! is_string($bytes) || $bytes === '') {
            throw ValidationException::withMessages(['image' => 'ไม่สามารถอ่านข้อมูลรูปภาพได้']);
        }

        $info = @getimagesizefromstring($bytes);
        if ($info === false || ! isset($info['mime'])) {
            throw ValidationException::withMessages(['image' => 'ไฟล์ที่อัปโหลดไม่ใช่รูปภาพที่ถูกต้อง']);
        }

        $mimeMap = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'image/avif' => 'avif',
        ];
        if (! isset($mimeMap[$info['mime']])) {
            throw ValidationException::withMessages(['image' => 'รองรับเฉพาะไฟล์รูปภาพ JPG, PNG, WEBP, GIF หรือ AVIF']);
        }
        $ext = $mimeMap[$info['mime']];

        $dir = public_path('assets/builder-uploads');
        if (! is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        $filename = Str::uuid()->toString().'.'.$ext;
        file_put_contents($dir.DIRECTORY_SEPARATOR.$filename, $bytes);

        return response()->json([
            'url' => '/assets/builder-uploads/'.$filename,
            'filename' => $filename,
            'width' => $info[0] ?? null,
            'height' => $info[1] ?? null,
        ], 201)->header('Cache-Control', 'private, no-store');
    }

    public function destroyDraft(Request $request, string $uuid): JsonResponse
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable('website_drafts')) {
            return response()->json(['message' => 'Draft storage is not available yet.'], 503)->header('Cache-Control', 'private, no-store');
        }
        $draft = \App\Models\WebsiteDraft::where('user_id', $request->user()->id)
            ->where('uuid', strtolower($uuid))
            ->firstOrFail();

        if (\Illuminate\Support\Facades\Schema::hasTable('website_draft_projects')) {
            \Illuminate\Support\Facades\DB::table('website_draft_projects')
                ->where('website_draft_id', $draft->id)
                ->delete();
        }
        $draft->delete();

        return response()->json([
            'deleted' => true,
            'uuid' => $draft->uuid,
        ])->header('Cache-Control', 'private, no-store');
    }

    public function listProjects(Request $request): JsonResponse
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable('lab_projects')) {
            return response()->json(['projects' => []])->header('Cache-Control', 'private, no-store');
        }
        $projects = \App\Models\LabProject::where('user_id', $request->user()->id)
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->limit(25)
            ->get()
            ->map(fn (\App\Models\LabProject $p) => [
                'uuid' => $p->uuid,
                'title' => (string) ($p->title ?: 'Untitled'),
                'project_type' => (string) ($p->project_type ?: 'react'),
                'workspace_url' => '/lab/'.$p->uuid.'/workspace',
                'chat_url' => '/lab/'.$p->uuid,
                'updated_at' => $p->updated_at?->toISOString(),
            ])
            ->values();

        return response()->json(['projects' => $projects])->header('Cache-Control', 'private, no-store');
    }

    public function destroyProject(Request $request, string $uuid, \App\Lab\StudioProjectDesk $desk): JsonResponse
    {
        $desk->delete($request->user(), strtolower($uuid));

        return response()->json([
            'deleted' => true,
            'uuid' => strtolower($uuid),
        ])->header('Cache-Control', 'private, no-store');
    }

    public function findDraftForProject(Request $request, string $uuid): JsonResponse
    {
        $project = \App\Models\LabProject::where('user_id', $request->user()->id)
            ->where('uuid', strtolower($uuid))
            ->firstOrFail();

        $binding = null;
        if (\Illuminate\Support\Facades\Schema::hasTable('website_draft_projects') && \Illuminate\Support\Facades\Schema::hasTable('website_drafts')) {
            $binding = \Illuminate\Support\Facades\DB::table('website_draft_projects')
                ->join('website_drafts', 'website_drafts.id', '=', 'website_draft_projects.website_draft_id')
                ->where('website_draft_projects.lab_project_id', $project->id)
                ->select('website_drafts.uuid', 'website_drafts.revision')
                ->first();
        }

        return response()->json([
            'found' => (bool) $binding,
            'draft_uuid' => $binding?->uuid,
            'draft_revision' => $binding?->revision,
            'project_uuid' => $project->uuid,
        ])->header('Cache-Control', 'private, no-store');
    }

    private function callLlm(
        string $prompt,
        string $scope,
        ?string $pageId,
        ?string $sectionId,
        array $baseBlueprint,
        array $catalog,
        ModelCatalog $models,
        ProviderFactory $providers,
        AiSettingsRepository $settings,
        EntitlementGate $entitlements,
        LabCreditMeter $credits,
        User $actor,
    ): ?array {
        $modelId = $models->defaultId();
        $model = $models->get($modelId);
        if (! $model->available || ! $settings->isEnabled($model->provider)) {
            foreach ($models->all(true) as $candidateModel) {
                if ($settings->isEnabled($candidateModel->provider)) {
                    $model = $candidateModel;
                    break;
                }
            }
        }
        if (! $model->available || ! $settings->isEnabled($model->provider)) {
            return null;
        }
        $entitlements->assertQuota($actor, EntitlementCatalog::LAB_CREDITS);

        $system = <<<'SYS'
You are an expert Thai & English Website Blueprint Architect for Krikkit Builder.
Your job is to modify the provided website `blueprint` JSON according to the user's instruction (`prompt`) and return ONLY the updated `blueprint` JSON object (no markdown fences, no commentary).

STRICT SCHEMA RULES:
1. Top-level keys MUST be: `schema_version` (integer 1), `kind`, `start_mode` ("template"), `template_id`, `title` (<=120 chars), `summary` (<=2000 chars), `shared` ({`brand`, `footer`}), `theme` ({`palette`, `font`, `radius`}), `pages` (1 to 5 pages).
2. Allowed `theme.palette`: "forest" (green/nature/clinic), "indigo" (blue/purple/modern/tech), "sand" (warm/gold/cream/editorial/store).
3. Allowed `theme.font`: "sans", "serif".
4. Allowed `theme.radius`: "soft", "square".
5. `pages[0].route` MUST be "/". Other pages must have unique lowercase routes like "/services", "/products", "/about", "/contact", "/news".
6. Each page has `sections` (1 to 12 sections). Allowed `section.type` and `section.variant`:
   - type "hero": variant "split" or "centered"
   - type "feature-grid": variant "default"
   - type "cards": variant "default"
   - type "text": variant "default"
   - type "cta": variant "default"
7. Each section has `id` (UUID), `type`, `variant`, `heading` (<=120 chars), `body` (<=2000 chars), and `items` (0 to 6 items, each `{id, title, body}`).
8. PRESERVE existing image tags inside `section.body` such as `[Hero Image: ...]` or `[Section Image: ...]` unless the user explicitly asks to remove or replace them.
9. WIDGET CONVENTIONS:
   - Custom Form Widget: set section `type` to "cta", include `[widget:form]` in `heading`, and make each item in `items` a form field where `title` is the field label and `body` starts with `[type:text]`, `[type:tel]`, `[type:email]`, `[type:select] ตัวเลือก: A, B, C`, `[type:date]`, `[type:file]`, or `[type:textarea]`.
   - News/Articles Widget: set section `type` to "cards", include `[widget:news]` in `heading`, and populate `items` with news headlines & summaries.
   - Product/Cart/Pricing Widget: set section `type` to "cards", include `[widget:cart]` in `heading`, and populate `items` with product names + prices in `title` and details in `body`.
SYS;

        $scopeInstruction = match ($scope) {
            'section' => "Focus your edits primarily on section ID `{$sectionId}` on page ID `{$pageId}`, while keeping other pages and sections intact.",
            'page' => "Focus your edits primarily on page ID `{$pageId}`, while keeping other pages intact unless the user explicitly asks to add/rename pages or change global theme.",
            default => "Apply edits across the website blueprint (theme, brand, pages, sections, and widgets) as requested.",
        };

        $userContent = "USER INSTRUCTION:\n{$prompt}\n\nSCOPE:\n{$scopeInstruction}\n\nCURRENT BLUEPRINT JSON:\n".json_encode($baseBlueprint, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $driver = $providers->make($model->provider);
        $response = $driver->complete(new ChatRequest(
            apiModel: $model->apiModel,
            system: $system,
            messages: [
                ChatMessage::user($userContent),
            ],
            options: [
                'temperature' => 0.4,
                'max_tokens' => 4096,
                'timeout' => 28,
                'prompt_cache_floor' => false,
            ],
        ));
        $usage = is_array($response->meta['usage'] ?? null) ? $response->meta['usage'] : [];
        $entitlements->settle($actor, EntitlementCatalog::LAB_CREDITS, $credits->quote($usage, $model->id, $response->toolCalls));

        $parsedThought = (new ThoughtBlockParser)->parse($response->content);
        $rawText = trim((string) ($parsedThought['content'] ?? $response->content));
        $rawText = preg_replace('/^```(?:json)?\s*|\s*```$/isu', '', $rawText) ?? $rawText;

        $firstBrace = strpos($rawText, '{');
        $lastBrace = strrpos($rawText, '}');
        if ($firstBrace === false || $lastBrace === false || $lastBrace <= $firstBrace) {
            return null;
        }
        $jsonSlice = substr($rawText, $firstBrace, $lastBrace - $firstBrace + 1);
        $decoded = json_decode($jsonSlice, true);
        if (! is_array($decoded)) {
            return null;
        }
        if (isset($decoded['blueprint']) && is_array($decoded['blueprint'])) {
            $decoded = $decoded['blueprint'];
        }

        // Also run smart widget/theme enrichments if the user explicitly asked for a form/news/cart/theme and LLM missed the tag
        return $this->applySmartPromptEdits($decoded, $prompt, $scope, $pageId, $sectionId, $catalog, true);
    }

    private function applySmartPromptEdits(
        array $blueprint,
        string $prompt,
        string $scope,
        ?string $pageId,
        ?string $sectionId,
        array $catalog,
        bool $lightTouch = false,
    ): array {
        $next = $blueprint;
        $lower = mb_strtolower($prompt, 'UTF-8');

        // 1. Theme palette adjustments (mapped to catalog palettes: forest, indigo, sand)
        if (preg_match('/(สีน้ำเงิน|สีคราม|สีม่วง|indigo|blue|purple|เทคโนโลยี|tech|โมเดิร์น)/u', $lower)) {
            $next['theme']['palette'] = 'indigo';
        } elseif (preg_match('/(สีทอง|สีครีม|น้ำตาล|ชมพู|อบอุ่น|หรูหรา|พรีเมียม|sand|warm|gold|คาเฟ่|ร้านอาหาร|โรงแรม|ดอกไม้)/u', $lower)) {
            $next['theme']['palette'] = 'sand';
        } elseif (preg_match('/(สีเขียว|เขียวมิ้นต์|forest|teal|green|สุขภาพ|ธรรมชาติ|คลินิก)/u', $lower)) {
            $next['theme']['palette'] = 'forest';
        }

        if (preg_match('/(ฟอนต์มีหัว|หรูหรา|ทางการ|serif|editorial)/u', $lower)) {
            $next['theme']['font'] = 'serif';
        } elseif (preg_match('/(ฟอนต์ทันสมัย|อ่านง่าย|sans)/u', $lower)) {
            $next['theme']['font'] = 'sans';
        }

        // 2. Brand / Website Title updates if explicitly mentioned like ชื่อแบรนด์ "..." or เปลี่ยนชื่อเว็บเป็น ...
        if (! $lightTouch && preg_match('/(?:เปลี่ยนชื่อ(?:เว็บ|แบรนด์|ร้าน)?เป็น|ชื่อแบรนด์|ชื่อร้าน)\s*["“]?([^"”\n,]{2,50})["”]?/u', $prompt, $brandMatch)) {
            $newBrand = trim($brandMatch[1]);
            if ($newBrand !== '') {
                $next['title'] = mb_substr($newBrand, 0, 120);
                $next['shared']['brand'] = mb_substr($newBrand, 0, 80);
            }
        }

        // Determine target page index
        $targetPageIdx = 0;
        if ($pageId !== null) {
            foreach ($next['pages'] as $idx => $p) {
                if (($p['id'] ?? '') === $pageId) {
                    $targetPageIdx = $idx;
                    break;
                }
            }
        }

        // 3. Add a new page if requested (and pages < 5)
        if ($scope === 'all' && preg_match('/(เพิ่มหน้า|สร้างหน้าใหม่|มีหน้า)\s*([^\n,.]{2,30})/u', $prompt, $pageMatch) && count($next['pages']) < 5) {
            $requestedTitle = trim($pageMatch[2]);
            $alreadyExists = false;
            foreach ($next['pages'] as $p) {
                if (mb_strtolower(trim($p['title'] ?? ''), 'UTF-8') === mb_strtolower($requestedTitle, 'UTF-8')) {
                    $alreadyExists = true;
                    break;
                }
            }
            if (! $alreadyExists && $requestedTitle !== '') {
                $slug = preg_replace('/[^\p{L}\p{N}\p{M}]+/u', '-', mb_strtolower($requestedTitle, 'UTF-8')) ?? 'page';
                $slug = trim($slug, '-');
                if ($slug === '' || in_array($slug, ['lab', 'api', 'login', 'logout', 'register', 'dashboard', 'settings', 'projects', 'assets', 'build', 'storage', 'oauth'], true)) {
                    $slug = 'page-'.(count($next['pages']) + 1);
                }
                $next['pages'][] = [
                    'id' => (string) Str::uuid(),
                    'title' => mb_substr($requestedTitle, 0, 80),
                    'route' => '/'.mb_substr($slug, 0, 60),
                    'navigation' => true,
                    'sections' => [
                        [
                            'id' => (string) Str::uuid(),
                            'type' => 'hero',
                            'variant' => 'split',
                            'heading' => mb_substr($requestedTitle.' — '.$next['shared']['brand'], 0, 120),
                            'body' => 'รายละเอียดและข้อมูลสำหรับหน้า '.$requestedTitle.' ที่ออกแบบให้ตรงกับความต้องการของลูกค้า',
                            'items' => [
                                ['id' => (string) Str::uuid(), 'title' => 'ข้อมูลครบถ้วนชัดเจน', 'body' => 'อัปเดตรายละเอียดล่าสุดพร้อมให้บริการ'],
                                ['id' => (string) Str::uuid(), 'title' => 'ติดต่อสอบถามได้ทันที', 'body' => 'ทีมงานพร้อมดูแลและให้คำปรึกษาอย่างรวดเร็ว'],
                            ],
                        ],
                    ],
                ];
                $targetPageIdx = count($next['pages']) - 1;
            }
        }

        // 4. Check if user wants a Form Widget added or updated
        $wantsForm = (bool) preg_match('/(ฟอร์ม|แบบฟอร์ม|form|จองคิว|ลงทะเบียน|สมัคร|แจ้งชำระเงิน|ติดต่อเรา|เพิ่ม\s*field|เพิ่มช่อง)/iu', $prompt);
        if ($wantsForm) {
            $hasFormOnTarget = false;
            foreach ($next['pages'][$targetPageIdx]['sections'] as &$sec) {
                if (str_contains(($sec['heading'] ?? '').' '.($sec['body'] ?? ''), '[widget:form]')) {
                    $hasFormOnTarget = true;
                    if (! $lightTouch) {
                        $sec['items'] = $this->buildRequestedFormFields($prompt, $sec['items'] ?? []);
                    }
                    break;
                }
            }
            unset($sec);

            if (! $hasFormOnTarget && count($next['pages'][$targetPageIdx]['sections']) < 12) {
                $formHeading = 'แบบฟอร์มติดต่อและลงทะเบียน [widget:form]';
                if (preg_match('/(จองคิว|นัดหมาย)/u', $prompt)) {
                    $formHeading = 'แบบฟอร์มจองคิวและนัดหมายบริการ [widget:form]';
                } elseif (preg_match('/(สมัครงาน|ร่วมงาน)/u', $prompt)) {
                    $formHeading = 'แบบฟอร์มใบสมัครงานออนไลน์ [widget:form]';
                } elseif (preg_match('/(ชำระเงิน|แจ้งโอน|แนบสลิป)/u', $prompt)) {
                    $formHeading = 'แบบฟอร์มแจ้งชำระเงินและแนบสลิป [widget:form]';
                }
                $next['pages'][$targetPageIdx]['sections'][] = [
                    'id' => (string) Str::uuid(),
                    'type' => 'cta',
                    'variant' => 'default',
                    'heading' => $formHeading,
                    'body' => 'กรอกข้อมูลด้านล่างเพื่อให้ทีมงานติดต่อกลับหรือยืนยันรายการของคุณทันที',
                    'items' => $this->buildRequestedFormFields($prompt, []),
                ];
            }
        }

        // 5. Check if user wants News / Articles Widget
        $wantsNews = (bool) preg_match('/(ข่าว|บทความ|ประชาสัมพันธ์|อัปเดต|news|blog)/iu', $prompt);
        if ($wantsNews) {
            $hasNewsOnTarget = false;
            foreach ($next['pages'][$targetPageIdx]['sections'] as $sec) {
                if (str_contains(($sec['heading'] ?? '').' '.($sec['body'] ?? ''), '[widget:news]')) {
                    $hasNewsOnTarget = true;
                    break;
                }
            }
            if (! $hasNewsOnTarget && count($next['pages'][$targetPageIdx]['sections']) < 12) {
                $next['pages'][$targetPageIdx]['sections'][] = [
                    'id' => (string) Str::uuid(),
                    'type' => 'cards',
                    'variant' => 'default',
                    'heading' => 'ข่าวสารและบทความอัปเดตล่าสุด [widget:news]',
                    'body' => 'ติดตามข่าวประชาสัมพันธ์ โปรโมชั่นประจำเดือน และสาระน่ารู้จาก '.$next['shared']['brand'],
                    'items' => [
                        ['id' => (string) Str::uuid(), 'title' => 'อัปเดตบริการและสิทธิพิเศษใหม่ล่าสุดประจำเดือนนี้', 'body' => 'ยกระดับมาตรฐานการให้บริการพร้อมโปรโมชั่นพิเศษสำหรับลูกค้าทุกท่าน'],
                        ['id' => (string) Str::uuid(), 'title' => 'เคล็ดลับและคำแนะนำจากทีมผู้เชี่ยวชาญของ '.$next['shared']['brand'], 'body' => 'เจาะลึกวิธีเลือกใช้บริการและดูแลรักษาอย่างถูกวิธีเพื่อความคุ้มค่าสูงสุด'],
                        ['id' => (string) Str::uuid(), 'title' => 'ขยายเวลาทำการและช่องทางให้คำปรึกษาออนไลน์ 24 ชม.', 'body' => 'สะดวกสบายยิ่งขึ้นด้วยระบบจองคิวและสั่งซื้อผ่านหน้าเว็บไซต์ได้ทันที'],
                    ],
                ];
            }
        }

        // 6. Check if user wants Products / Cart / Pricing Widget
        $wantsCart = (bool) preg_match('/(สินค้า|ตะกร้า|ราคา|แพ็กเกจ|สั่งซื้อ|ชำระเงิน|promptpay|เมนู|คอร์ส|บริการเด่น)/iu', $prompt);
        if ($wantsCart) {
            $hasCartOnTarget = false;
            foreach ($next['pages'][$targetPageIdx]['sections'] as $sec) {
                if (str_contains(($sec['heading'] ?? '').' '.($sec['body'] ?? ''), '[widget:cart]')) {
                    $hasCartOnTarget = true;
                    break;
                }
            }
            if (! $hasCartOnTarget && count($next['pages'][$targetPageIdx]['sections']) < 12) {
                $next['pages'][$targetPageIdx]['sections'][] = [
                    'id' => (string) Str::uuid(),
                    'type' => 'cards',
                    'variant' => 'default',
                    'heading' => 'รายการสินค้าและแพ็กเกจยอดนิยม [widget:cart]',
                    'body' => 'เลือกสินค้าหรือแพ็กเกจที่ต้องการลงตะกร้า พร้อมรองรับการชำระเงินผ่าน QR PromptPay',
                    'items' => [
                        ['id' => (string) Str::uuid(), 'title' => 'แพ็กเกจเริ่มต้น (Starter Package) — ฿990', 'body' => 'เหมาะสำหรับผู้เริ่มต้น ครบจบในเซ็ตเดียวพร้อมรับประกันคุณภาพ'],
                        ['id' => (string) Str::uuid(), 'title' => 'แพ็กเกจขายดี (Best Seller) — ฿1,890', 'body' => 'คุ้มค่าที่สุด พร้อมบริการดูแลพิเศษและจัดส่งฟรีทั่วประเทศ'],
                        ['id' => (string) Str::uuid(), 'title' => 'แพ็กเกจพรีเมียม (VIP Exclusive) — ฿2,990', 'body' => 'บริการระดับพรีเมียมแบบครบวงจร พร้อมทีมผู้เชี่ยวชาญดูแลส่วนตัว'],
                    ],
                ];
            }
        }

        // 7. If LLM wasn't used ($lightTouch === false), also update the target section/page copy so the user immediately sees their prompt reflected in the headings/body!
        if (! $lightTouch) {
            $cleanSummaryPrompt = trim(preg_replace('/\s+/u', ' ', $prompt) ?? $prompt);
            $next['summary'] = mb_substr($cleanSummaryPrompt, 0, 2000);

            if ($scope === 'section' && $sectionId !== null) {
                foreach ($next['pages'][$targetPageIdx]['sections'] as &$sec) {
                    if (($sec['id'] ?? '') === $sectionId) {
                        $imageTag = $this->extractImageTag($sec['body'] ?? '');
                        $sec['heading'] = mb_substr($this->deriveHeadingFromPrompt($prompt, $sec['heading'] ?? $next['shared']['brand']), 0, 120);
                        $sec['body'] = mb_substr(trim($cleanSummaryPrompt.($imageTag ? "\n".$imageTag : '')), 0, 2000);
                        break;
                    }
                }
                unset($sec);
            } else {
                // Update Hero section of the target page while preserving its Hero Image tag
                if (! empty($next['pages'][$targetPageIdx]['sections'][0])) {
                    $hero = &$next['pages'][$targetPageIdx]['sections'][0];
                    $imageTag = $this->extractImageTag($hero['body'] ?? '');
                    if (! $wantsForm && ! $wantsNews && ! $wantsCart) {
                        $hero['heading'] = mb_substr($this->deriveHeadingFromPrompt($prompt, $hero['heading']), 0, 120);
                    }
                    $newBody = $cleanSummaryPrompt;
                    if ($imageTag !== '' && ! str_contains($newBody, $imageTag)) {
                        $newBody .= "\n".$imageTag;
                    }
                    $hero['body'] = mb_substr(trim($newBody), 0, 2000);
                    unset($hero);
                }
            }
        }

        return $next;
    }

    private function buildRequestedFormFields(string $prompt, array $existingItems): array
    {
        $fields = [
            ['id' => (string) Str::uuid(), 'title' => 'ชื่อ - นามสกุล', 'body' => '[type:text] กรอกชื่อและนามสกุลของคุณ'],
            ['id' => (string) Str::uuid(), 'title' => 'เบอร์โทรศัพท์ติดต่อ', 'body' => '[type:tel] 08X-XXX-XXXX'],
        ];

        if (preg_match('/(อีเมล|email)/iu', $prompt) || count($fields) < 3) {
            $fields[] = ['id' => (string) Str::uuid(), 'title' => 'อีเมล (Email)', 'body' => '[type:email] name@example.com'];
        }
        if (preg_match('/(วันที่|นัดหมาย|จองคิว|วันเกิด|เวลา|date)/iu', $prompt)) {
            $fields[] = ['id' => (string) Str::uuid(), 'title' => 'วันที่ต้องการนัดหมาย / ใช้บริการ', 'body' => '[type:date] เลือกวันที่สะดวก'];
        }
        if (preg_match('/(สาขา|บริการ|แพ็กเกจ|หัวข้อ|ตัวเลือก|เลือก)/iu', $prompt)) {
            $fields[] = ['id' => (string) Str::uuid(), 'title' => 'เลือกบริการ / หัวข้อที่สนใจ', 'body' => '[type:select] ตัวเลือก: บริการหลักยอดนิยม, แพ็กเกจพรีเมียม, ขอใบเสนอราคา, ติดต่อสอบถามทั่วไป'];
        }
        if (preg_match('/(สลิป|ไฟล์|รูปภาพ|เรซูเม่|อัปโหลด|แนบ|upload|file)/iu', $prompt)) {
            $fields[] = ['id' => (string) Str::uuid(), 'title' => 'แนบไฟล์เอกสาร / สลิปโอนเงิน', 'body' => '[type:file] อัปโหลดไฟล์รูปภาพหรือ PDF'];
        }
        $fields[] = ['id' => (string) Str::uuid(), 'title' => 'รายละเอียดเพิ่มเติม', 'body' => '[type:textarea] ระบุรายละเอียดหรือความต้องการเพิ่มเติมของคุณ'];

        return array_slice($fields, 0, 6);
    }

    private function extractImageTag(string $body): string
    {
        if (preg_match('/\[(?:Hero|Section)\s+Image:\s*[^\]]+\]/i', $body, $m)) {
            return $m[0];
        }

        return '';
    }

    private function deriveHeadingFromPrompt(string $prompt, string $fallback): string
    {
        $firstLine = trim( explode("\n", trim($prompt))[0] ?? '' );
        $firstLine = preg_replace('/^(?:ช่วย|ให้\s*ai|สั่ง\s*ai|ปรับแก้|แก้ไข|เปลี่ยน|เพิ่ม)\s*/iu', '', $firstLine) ?? $firstLine;
        $firstLine = trim($firstLine);

        return $firstLine !== '' ? mb_substr($firstLine, 0, 110) : $fallback;
    }

    private function sanitizeBlueprint(array $candidate, array $fallback, array $catalog): array
    {
        $validKinds = array_column($catalog['kinds'], 'id');
        $validTemplates = array_column($catalog['templates'], null, 'id');
        $validPalettes = array_keys($catalog['palettes']);
        $validFonts = array_keys($catalog['fonts']);
        $validRadii = array_keys($catalog['radii']);
        $sectionTypes = array_column($catalog['section_types'], null, 'id');
        $reservedRoutes = ['lab', 'api', 'login', 'logout', 'register', 'dashboard', 'settings', 'projects', 'assets', 'build', 'storage', 'oauth'];

        $kind = isset($candidate['kind']) && in_array($candidate['kind'], $validKinds, true)
            ? $candidate['kind']
            : ($fallback['kind'] ?? 'business');

        $templateId = isset($candidate['template_id'], $validTemplates[$candidate['template_id']])
            && in_array($kind, $validTemplates[$candidate['template_id']]['kinds'], true)
            ? $candidate['template_id']
            : ($fallback['template_id'] ?? 'business-studio');

        if (! isset($validTemplates[$templateId]) || ! in_array($kind, $validTemplates[$templateId]['kinds'], true)) {
            foreach ($catalog['templates'] as $tpl) {
                if (in_array($kind, $tpl['kinds'], true)) {
                    $templateId = $tpl['id'];
                    break;
                }
            }
        }

        $usedIds = [];
        $claimUuid = static function (mixed $rawId) use (&$usedIds): string {
            $str = is_string($rawId) ? strtolower(trim($rawId)) : '';
            if (! preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/D', $str) || isset($usedIds[$str])) {
                do {
                    $str = strtolower((string) Str::uuid());
                } while (isset($usedIds[$str]));
            }
            $usedIds[$str] = true;

            return $str;
        };

        $nonBlank = static function (mixed $val, string $default, int $max): string {
            $str = is_string($val) ? trim($val) : '';
            if ($str === '' || preg_match('/^[\s\p{Z}\x{FEFF}\x{0000}]*$/uD', $str) === 1) {
                $str = $default;
            }

            return mb_substr($str, 0, $max);
        };

        $rawPages = isset($candidate['pages']) && is_array($candidate['pages']) && count($candidate['pages']) > 0
            ? array_values($candidate['pages'])
            : (isset($fallback['pages']) && is_array($fallback['pages']) ? array_values($fallback['pages']) : []);

        $rawPages = array_slice($rawPages, 0, 5);
        $pages = [];
        $usedRoutes = ['/' => true];

        foreach ($rawPages as $pIdx => $rawPage) {
            if (! is_array($rawPage)) {
                continue;
            }
            $pageId = $claimUuid($rawPage['id'] ?? null);
            $pageTitle = $nonBlank($rawPage['title'] ?? null, $pIdx === 0 ? 'หน้าแรก' : 'หน้า '.($pIdx + 1), 80);

            if ($pIdx === 0) {
                $route = '/';
            } else {
                $rawRoute = is_string($rawPage['route'] ?? null) ? trim($rawPage['route']) : '';
                $rawRoute = mb_strtolower($rawRoute, 'UTF-8');
                if (class_exists(Normalizer::class)) {
                    $normalized = Normalizer::normalize($rawRoute, Normalizer::FORM_C);
                    if (is_string($normalized)) {
                        $rawRoute = $normalized;
                    }
                }
                $segment = '[\p{L}\p{N}][\p{L}\p{N}\p{M}]*(?:-[\p{L}\p{N}][\p{L}\p{N}\p{M}]*)*';
                $firstSeg = explode('/', ltrim($rawRoute, '/'))[0] ?? '';
                if (
                    $rawRoute === ''
                    || $rawRoute === '/'
                    || isset($usedRoutes[$rawRoute])
                    || in_array($firstSeg, $reservedRoutes, true)
                    || preg_match('~^/'.$segment.'(?:/'.$segment.')*$~uD', $rawRoute) !== 1
                ) {
                    $fallbackRoute = '/page-'.($pIdx + 1);
                    while (isset($usedRoutes[$fallbackRoute])) {
                        $fallbackRoute .= '-x';
                    }
                    $route = $fallbackRoute;
                } else {
                    $route = mb_substr($rawRoute, 0, 160);
                }
                $usedRoutes[$route] = true;
            }

            $rawSections = isset($rawPage['sections']) && is_array($rawPage['sections']) && count($rawPage['sections']) > 0
                ? array_slice(array_values($rawPage['sections']), 0, 12)
                : [
                    [
                        'type' => 'hero',
                        'variant' => 'split',
                        'heading' => $pageTitle,
                        'body' => '',
                        'items' => [],
                    ],
                ];

            $sections = [];
            foreach ($rawSections as $sIdx => $rawSec) {
                if (! is_array($rawSec)) {
                    continue;
                }
                $secId = $claimUuid($rawSec['id'] ?? null);
                $secType = isset($rawSec['type'], $sectionTypes[$rawSec['type']]) ? $rawSec['type'] : 'text';
                $allowedVariants = $sectionTypes[$secType]['variants'];
                $secVariant = isset($rawSec['variant']) && in_array($rawSec['variant'], $allowedVariants, true)
                    ? $rawSec['variant']
                    : $allowedVariants[0];

                $secBody = is_string($rawSec['body'] ?? null) ? mb_substr($rawSec['body'], 0, 2000) : '';
                // Preserve image tag from fallback section if AI accidentally dropped it
                $fallbackSecBody = $fallback['pages'][$pIdx]['sections'][$sIdx]['body'] ?? '';
                $fallbackImageTag = $this->extractImageTag((string) $fallbackSecBody);
                if ($fallbackImageTag !== '' && $this->extractImageTag($secBody) === '') {
                    $secBody = mb_substr(trim($secBody."\n".$fallbackImageTag), 0, 2000);
                }

                $rawItems = isset($rawSec['items']) && is_array($rawSec['items'])
                    ? array_slice(array_values($rawSec['items']), 0, 6)
                    : [];
                $items = [];
                foreach ($rawItems as $iIdx => $rawItem) {
                    if (! is_array($rawItem)) {
                        continue;
                    }
                    $items[] = [
                        'id' => $claimUuid($rawItem['id'] ?? null),
                        'title' => $nonBlank($rawItem['title'] ?? null, 'รายการที่ '.($iIdx + 1), 120),
                        'body' => is_string($rawItem['body'] ?? null) ? mb_substr($rawItem['body'], 0, 500) : '',
                    ];
                }

                $sections[] = [
                    'id' => $secId,
                    'type' => $secType,
                    'variant' => $secVariant,
                    'heading' => $nonBlank($rawSec['heading'] ?? null, $pageTitle, 120),
                    'body' => $secBody,
                    'items' => $items,
                ];
            }

            $pages[] = [
                'id' => $pageId,
                'title' => $pageTitle,
                'route' => $route,
                'navigation' => is_bool($rawPage['navigation'] ?? null) ? $rawPage['navigation'] : true,
                'sections' => $sections,
            ];
        }

        if (! collect($pages)->contains(fn (array $p) => $p['navigation'] === true)) {
            $pages[0]['navigation'] = true;
        }

        $palette = isset($candidate['theme']['palette']) && in_array($candidate['theme']['palette'], $validPalettes, true)
            ? $candidate['theme']['palette']
            : ($fallback['theme']['palette'] ?? 'sand');
        $font = isset($candidate['theme']['font']) && in_array($candidate['theme']['font'], $validFonts, true)
            ? $candidate['theme']['font']
            : ($fallback['theme']['font'] ?? 'sans');
        $radius = isset($candidate['theme']['radius']) && in_array($candidate['theme']['radius'], $validRadii, true)
            ? $candidate['theme']['radius']
            : ($fallback['theme']['radius'] ?? 'soft');

        return [
            'schema_version' => 1,
            'kind' => $kind,
            'start_mode' => 'template',
            'template_id' => $templateId,
            'title' => $nonBlank($candidate['title'] ?? null, $fallback['title'] ?? 'My Website', 120),
            'summary' => is_string($candidate['summary'] ?? null) ? mb_substr($candidate['summary'], 0, 2000) : ($fallback['summary'] ?? ''),
            'shared' => [
                'brand' => $nonBlank($candidate['shared']['brand'] ?? null, $fallback['shared']['brand'] ?? 'Brand', 80),
                'footer' => is_string($candidate['shared']['footer'] ?? null) ? mb_substr($candidate['shared']['footer'], 0, 240) : ($fallback['shared']['footer'] ?? ''),
            ],
            'theme' => [
                'palette' => $palette,
                'font' => $font,
                'radius' => $radius,
            ],
            'pages' => $pages,
        ];
    }
}
