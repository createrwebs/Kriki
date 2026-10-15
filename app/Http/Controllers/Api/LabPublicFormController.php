<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Lab\Forms\FormDatastore;
use App\Lab\Forms\FormSubmissionValidator;
use App\Models\LabForm;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabPublicFormController extends Controller
{
    public function show(LabForm $form): JsonResponse
    {
        $this->assertOpen($form);
        $version = $form->versions()->where('version', $form->active_version)->firstOrFail();
        return response()->json([
            'form_uuid' => $form->uuid, 'title' => $form->title, 'version' => $version->version,
            'definition' => $version->definition,
            'notice' => 'ส่งคำขอจองเพื่อให้ร้านตรวจสอบ ยังไม่ใช่การยืนยันโต๊ะว่าง',
        ])->header('Cache-Control', 'no-store');
    }

    public function store(Request $request, LabForm $form, FormSubmissionValidator $validator, FormDatastore $store): JsonResponse
    {
        abort_if(strlen($request->getContent()) > 16 * 1024, 413, 'Form payload is too large.');
        return DB::transaction(function () use ($request, $form, $validator, $store) {
            $locked = LabForm::query()->whereKey($form->id)->lockForUpdate()->firstOrFail();
            $key = $request->input('idempotency_key');
            if (is_string($key) && \Illuminate\Support\Str::isUuid($key)) {
                $receipt = $store->receipt($locked, $key);
                if ($receipt) {
                    // A committed request remains replayable after a version/state/time change.
                    $data = $validator->validate($request->all(), $receipt['definition_snapshot'], false);
                    abort_unless(hash_equals($receipt['payload_hash'], $this->payloadHash($data)), 409, 'This retry differs from the saved request.');
                    return response()->json(['reference' => $receipt['uuid'], 'status' => $receipt['status']])->header('Cache-Control', 'no-store');
                }
            }
            $this->assertOpen($locked);
            abort_unless($request->input('version') === $locked->active_version, 409, 'The form has changed. Reload and review your answers.');
            $version = $locked->versions()->where('version', $locked->active_version)->firstOrFail();
            $data = $validator->validate($request->all(), $version->definition);
            $hash = $this->payloadHash($data);
            $saved = $store->submit($locked, $data['core'], $data['answers'], $version->definition, $version->version, $data['idempotency_key'], $hash);
            return response()->json(['reference' => $saved['submission_uuid'], 'status' => $saved['status']], $saved['replayed'] ? 200 : 201)
                ->header('Cache-Control', 'no-store');
        });
    }

    private function assertOpen(LabForm $form): void
    {
        abort_unless($form->status === 'active' && $form->active_version !== null, 404);
        if (! app()->environment('local')) {
            abort_unless($form->project?->publication?->isLive() ?? false, 404);
        }
    }

    private function payloadHash(array $data): string
    {
        $canonical = ['version' => $data['version'], 'core' => $data['core'], 'answers' => $data['answers']];
        ksort($canonical['core']);
        return hash('sha256', json_encode($canonical, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION));
    }
}
