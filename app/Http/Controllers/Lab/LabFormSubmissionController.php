<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Lab\Forms\FormDatastore;
use App\Lab\Forms\FormDefinitions;
use App\Models\LabForm;
use App\Models\LabProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LabFormSubmissionController extends Controller
{
    public function index(Request $request, LabProject $project, LabForm $form, FormDefinitions $forms, FormDatastore $store): JsonResponse
    {
        $forms->assertAccess($request->user(), $project, $form);
        $filters = $request->validate([
            'status' => ['sometimes', 'in:pending,confirmed,cancelled'],
            'date_from' => ['sometimes', 'date_format:Y-m-d'],
            'date_to' => ['sometimes', 'date_format:Y-m-d', 'after_or_equal:date_from'],
            'q' => ['sometimes', 'string', 'max:120'], 'cursor' => ['sometimes', 'string', 'max:300'],
        ]);
        return response()->json($store->listing($form, $filters));
    }

    public function show(Request $request, LabProject $project, LabForm $form, string $submission, FormDefinitions $forms, FormDatastore $store): JsonResponse
    {
        $forms->assertAccess($request->user(), $project, $form);
        abort_unless(\Illuminate\Support\Str::isUuid($submission), 404);
        return response()->json($store->detail($form, $submission));
    }

    public function update(Request $request, LabProject $project, LabForm $form, string $submission, FormDefinitions $forms, FormDatastore $store): JsonResponse
    {
        $forms->assertAccess($request->user(), $project, $form);
        abort_unless(\Illuminate\Support\Str::isUuid($submission), 404);
        $data = $request->validate(['expected_status' => ['required', 'in:pending,confirmed,cancelled'], 'status' => ['required', 'in:confirmed,cancelled']]);
        return response()->json($store->transition($form, $submission, $data['expected_status'], $data['status'], (int) $request->user()->id));
    }
}
