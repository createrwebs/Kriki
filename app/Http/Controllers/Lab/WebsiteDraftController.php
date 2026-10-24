<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Lab\Builder\BlueprintSchema;
use App\Lab\Builder\TemplateCatalog;
use App\Models\User;
use App\Models\WebsiteDraft;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use JsonException;
use stdClass;

class WebsiteDraftController extends Controller
{
    public function catalog(TemplateCatalog $catalog): JsonResponse
    {
        return response()->json($catalog->all())->header('Cache-Control', 'private, no-store');
    }

    public function index(Request $request): JsonResponse
    {
        $this->requireStorage();
        $drafts = WebsiteDraft::where('user_id', $request->user()->id)
            ->orderByDesc('updated_at')->orderByDesc('id')->limit(20)->get()
            ->map(fn (WebsiteDraft $draft) => [
                'uuid' => $draft->uuid,
                'title' => $draft->blueprint['title'],
                'kind' => $draft->blueprint['kind'],
                'revision' => $draft->revision,
                'updated_at' => $draft->updated_at->toISOString(),
            ]);

        return response()->json(['drafts' => $drafts])->header('Cache-Control', 'private, no-store');
    }

    public function store(Request $request, BlueprintSchema $schema): JsonResponse
    {
        $this->requireStorage();
        $input = $this->input($request, ['request_id', 'blueprint']);
        Validator::make($input, [
            'request_id' => ['required', 'string', 'regex:/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/iD'],
        ])->validate();
        $input['request_id'] = strtolower($input['request_id']);
        $blueprint = $schema->validate($input['blueprint']);
        $hash = hash('sha256', json_encode($this->canonical($blueprint), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));

        // Serialize creation retries for one owner even when the request key has no row yet.
        [$draft, $created] = DB::transaction(function () use ($request, $input, $blueprint, $hash): array {
            User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
            $existing = WebsiteDraft::where('user_id', $request->user()->id)->where('client_request_id', $input['request_id'])->first();
            if ($existing) {
                if (! hash_equals($existing->creation_hash, $hash)) {
                    throw new HttpResponseException(response()->json(['message' => 'This request ID was already used for a different draft snapshot.'], 409)->header('Cache-Control', 'private, no-store'));
                }

                return [$existing, false];
            }
            $draft = (new WebsiteDraft)->forceFill([
                'uuid' => (string) Str::uuid(),
                'user_id' => $request->user()->id,
                'client_request_id' => $input['request_id'],
                'creation_hash' => $hash,
                'blueprint' => $blueprint,
                'revision' => 1,
            ]);
            $draft->save();

            return [$draft, true];
        });

        return response()->json(['draft' => $this->publicDraft($draft)], $created ? 201 : 200)->header('Cache-Control', 'private, no-store');
    }

    public function show(Request $request, string $uuid): JsonResponse
    {
        $this->requireStorage();
        $draft = WebsiteDraft::where('user_id', $request->user()->id)->where('uuid', strtolower($uuid))->firstOrFail();

        return response()->json(['draft' => $this->publicDraft($draft)])->header('Cache-Control', 'private, no-store');
    }

    public function update(Request $request, string $uuid, BlueprintSchema $schema): JsonResponse
    {
        $this->requireStorage();
        $draft = WebsiteDraft::where('user_id', $request->user()->id)->where('uuid', strtolower($uuid))->firstOrFail();
        $input = $this->input($request, ['expected_revision', 'blueprint']);
        Validator::make($input, [
            'expected_revision' => ['required', function ($attribute, $value, $fail): void {
                if (! is_int($value) || $value < 1) $fail('Expected a positive integer revision.');
            }],
        ])->validate();
        $blueprint = $schema->validate($input['blueprint']);

        // Keep the successful snapshot read under the update lock so another save cannot replace its response.
        return DB::transaction(function () use ($request, $draft, $input, $blueprint): JsonResponse {
            $changed = WebsiteDraft::whereKey($draft->id)->where('user_id', $request->user()->id)
                ->where('revision', $input['expected_revision'])->update([
                    'blueprint' => json_encode($blueprint, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
                    'revision' => DB::raw('revision + 1'),
                    'updated_at' => now(),
                ]);
            $draft->refresh();
            if ($changed === 0) {
                return response()->json(['message' => 'This draft has a newer saved revision. Reload it before saving again.', 'current_revision' => $draft->revision], 409)->header('Cache-Control', 'private, no-store');
            }

            return response()->json(['draft' => $this->publicDraft($draft)])->header('Cache-Control', 'private, no-store');
        });
    }

    private function requireStorage(): void
    {
        if (! Schema::hasTable('website_drafts')) {
            throw new HttpResponseException(response()->json(['message' => 'Draft storage is not available yet. Please try again later.'], 503)->header('Cache-Control', 'private, no-store'));
        }
    }

    private function publicDraft(WebsiteDraft $draft): array
    {
        return ['uuid' => $draft->uuid, 'revision' => $draft->revision, 'blueprint' => $draft->blueprint, 'updated_at' => $draft->updated_at->toISOString()];
    }

    private function canonical(array $value): array
    {
        if (! array_is_list($value)) ksort($value);
        foreach ($value as $key => $child) {
            if (is_array($child)) $value[$key] = $this->canonical($child);
        }

        return $value;
    }

    private function input(Request $request, array $keys): array
    {
        if (! $request->isJson()) throw ValidationException::withMessages(['request' => 'Send an application/json object.']);
        try {
            $wire = json_decode($request->getContent(), false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw ValidationException::withMessages(['request' => 'Send a valid JSON object.']);
        }
        if (! $wire instanceof stdClass || array_diff(array_keys(get_object_vars($wire)), $keys) !== []) {
            throw ValidationException::withMessages(['request' => 'Only the documented request fields are supported.']);
        }
        if (! ($wire->blueprint ?? null) instanceof stdClass) {
            throw ValidationException::withMessages(['blueprint' => 'Blueprint must be an object.']);
        }
        // Inspect wire types before associative decoding erases the distinction between {} and [].
        foreach (['shared', 'theme'] as $key) {
            if (isset($wire->blueprint->$key) && ! $wire->blueprint->$key instanceof stdClass) {
                throw ValidationException::withMessages(['blueprint.'.$key => 'Expected an object.']);
            }
        }
        if (isset($wire->blueprint->pages)) {
            if (! is_array($wire->blueprint->pages)) throw ValidationException::withMessages(['pages' => 'Expected a list.']);
            foreach ($wire->blueprint->pages as $page) {
                if (! $page instanceof stdClass) throw ValidationException::withMessages(['pages' => 'Expected page objects.']);
                if (isset($page->sections)) {
                    if (! is_array($page->sections)) throw ValidationException::withMessages(['pages' => 'Expected a sections list.']);
                    foreach ($page->sections as $section) {
                        if (! $section instanceof stdClass) throw ValidationException::withMessages(['pages' => 'Expected section objects.']);
                        if (isset($section->items)) {
                            if (! is_array($section->items)) throw ValidationException::withMessages(['pages' => 'Expected an items list.']);
                            foreach ($section->items as $item) {
                                if (! $item instanceof stdClass) throw ValidationException::withMessages(['pages' => 'Expected item objects.']);
                            }
                        }
                    }
                }
            }
        }

        return json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }
}
