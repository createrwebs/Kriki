<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Lab\Forms\FormDefinitions;
use App\Lab\Forms\FormEmbed;
use App\Lab\Forms\FormProposal;
use App\Lab\SiteWorkspace;
use App\Lab\LabProjectAccess;
use App\Models\LabForm;
use App\Models\LabProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LabFormController extends Controller
{
    public function index(Request $request, LabProject $project, FormDefinitions $forms): JsonResponse
    {
        LabProjectAccess::assertOwner($project, $request->user());
        $items = LabForm::query()->where('lab_project_id', $project->id)->where('user_id', $request->user()->id)->latest('id')->get();
        return response()->json(['forms' => $items->map(fn ($form) => $forms->payload($form))]);
    }

    public function store(Request $request, LabProject $project, FormDefinitions $forms): JsonResponse
    {
        $data = $request->validate(['title' => ['required', 'string', 'max:120']]);
        return response()->json($forms->payload($forms->create($request->user(), $project, $data['title'])), 201);
    }

    public function show(Request $request, LabProject $project, LabForm $form, FormDefinitions $forms): JsonResponse
    {
        return response()->json($forms->detail($request->user(), $project, $form));
    }

    public function saveVersion(Request $request, LabProject $project, LabForm $form, FormDefinitions $forms): JsonResponse
    {
        $data = $request->validate(['expected_version' => ['required', 'integer', 'min:1'], 'definition' => ['required', 'array']]);
        $forms->saveVersion($request->user(), $project, $form, $data['expected_version'], $data['definition']);
        return response()->json($forms->payload($form->fresh()));
    }

    public function state(Request $request, LabProject $project, LabForm $form, FormDefinitions $forms): JsonResponse
    {
        $data = $request->validate(['state' => ['required', 'in:draft,active,closed'], 'expected_version' => ['required', 'integer', 'min:1']]);
        return response()->json($forms->payload($forms->setState($request->user(), $project, $form, $data['state'], $data['expected_version'])));
    }

    public function propose(Request $request, LabProject $project, LabForm $form, FormProposal $proposals): JsonResponse
    {
        $data = $request->validate(['expected_version' => ['required', 'integer', 'min:1'], 'operations' => ['present', 'array', 'max:100']]);
        return response()->json($proposals->propose($request->user(), $project, $form, $data['expected_version'], $data['operations']));
    }

    public function embed(Request $request, LabProject $project, LabForm $form, FormDefinitions $forms, FormEmbed $embed, SiteWorkspace $workspace): JsonResponse
    {
        $forms->assertAccess($request->user(), $project, $form, true);
        $configPath = $workspace->root($project).'/src/krikkit-forms/config.json';
        if (is_file($configPath)) {
            $existing = json_decode($workspace->read($project, 'src/krikkit-forms/config.json'), true);
            abort_unless(($existing['formUuid'] ?? null) === $form->uuid, 409, 'Another form is already embedded in this site.');
        }
        $files = $embed->files($form);
        return response()->json(['paths' => $workspace->commitAtomic($project, $files), 'files' => $files]);
    }
}
