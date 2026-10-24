<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Lab\Builder\DraftMaterializer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use JsonException;
use stdClass;

final class WebsiteProjectController extends Controller
{
    public function __invoke(Request $request, string $uuid, DraftMaterializer $materializer): JsonResponse
    {
        abort_unless(Schema::hasTable('website_drafts') && Schema::hasTable('website_draft_projects'), 503, 'Website project storage is not available yet.');
        abort_if(strlen($request->getContent()) > 512, 413, 'Project request is too large.');
        if (!$request->isJson()) throw ValidationException::withMessages(['request' => 'Send an application/json object.']);
        try { $input = json_decode($request->getContent(), false, 32, JSON_THROW_ON_ERROR); }
        catch (JsonException) { throw ValidationException::withMessages(['request' => 'Send a valid JSON object.']); }
        if (!$input instanceof stdClass || array_keys(get_object_vars($input)) !== ['expected_revision']
            || !is_int($input->expected_revision) || $input->expected_revision < 1) {
            throw ValidationException::withMessages(['expected_revision' => 'Send only the positive integer saved draft revision.']);
        }
        [$project, $source, $created] = $materializer->create($request->user(), $uuid, $input->expected_revision);

        return response()->json([
            'project' => ['uuid' => $project->uuid, 'title' => $project->title, 'workspace_url' => '/lab/'.$project->uuid.'/workspace'],
            'source' => $source,
        ], $created ? 201 : 200)->header('Cache-Control', 'private, no-store');
    }
}
