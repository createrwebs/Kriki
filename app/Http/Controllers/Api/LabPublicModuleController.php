<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Lab\Modules\ContactMessages;
use App\Lab\Modules\NewsArticles;
use App\Models\LabModuleInstallation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class LabPublicModuleController extends Controller
{
    public function contact(Request $request, LabModuleInstallation $module, ContactMessages $contacts): JsonResponse
    {
        abort_unless($request->isJson(), 422, 'A JSON object is required.');
        try { $body = json_decode($request->getContent(), false, 16, JSON_THROW_ON_ERROR); }
        catch (\JsonException) { abort(422, 'Invalid JSON object.'); }
        abort_unless($body instanceof \stdClass, 422, 'A JSON object is required.');
        $receipt = $contacts->submit($module, (array) $body);
        return response()->json(['reference' => $receipt['reference'], 'status' => $receipt['status']], $receipt['replayed'] ? 200 : 201, ['Cache-Control' => 'no-store']);
    }

    public function news(Request $request, LabModuleInstallation $module, NewsArticles $news): JsonResponse
    {
        abort_unless(array_diff(array_keys($request->query()), ['slug']) === [], 422, 'Unexpected news query parameters.');
        $slug = $request->query('slug');
        abort_unless($slug === null || is_string($slug), 422, 'Invalid news slug.');
        return response()->json($news->publicFeed($module, $slug), 200, ['Cache-Control' => 'no-store']);
    }

    public function storefront(Request $request, LabModuleInstallation $module, \App\Lab\Modules\CommerceStore $commerce): JsonResponse
    {
        abort_unless($request->query() === [], 422, 'Unexpected storefront query parameters.');
        return response()->json($commerce->publicStorefront($module), 200, ['Cache-Control' => 'no-store']);
    }

    public function createOrder(Request $request, LabModuleInstallation $module, \App\Lab\Modules\CommerceStore $commerce): JsonResponse
    {
        abort_if(strlen($request->getContent()) > 16384, 413, 'Order request is too large.');
        abort_unless($request->isJson(), 422, 'A JSON object is required.');
        try { $body = json_decode($request->getContent(), true, 16, JSON_THROW_ON_ERROR); }
        catch (\JsonException) { abort(422, 'Invalid JSON object.'); }
        abort_unless(is_array($body) && !array_is_list($body), 422, 'A JSON object is required.');
        $receipt = $commerce->publicCreateOrder($module, $body);
        return response()->json($receipt, $receipt['replayed'] ? 200 : 201, ['Cache-Control' => 'no-store']);
    }

    public function showOrder(Request $request, LabModuleInstallation $module, string $order, \App\Lab\Modules\CommerceStore $commerce): JsonResponse
    {
        $token = (string) ($request->header('X-Krikkit-Order-Token') ?? $request->query('token', ''));
        return response()->json($commerce->publicGetOrder($module, $order, $token), 200, ['Cache-Control' => 'no-store']);
    }

    public function submitOrderSlip(Request $request, LabModuleInstallation $module, string $order, \App\Lab\Modules\CommerceStore $commerce): JsonResponse
    {
        abort_if(strlen($request->getContent()) > 2600000, 413, 'Payment slip upload is too large.');
        abort_unless($request->isJson(), 422, 'A JSON object is required.');
        try { $body = json_decode($request->getContent(), true, 16, JSON_THROW_ON_ERROR); }
        catch (\JsonException) { abort(422, 'Invalid JSON object.'); }
        abort_unless(is_array($body) && !array_is_list($body), 422, 'A JSON object is required.');
        $token = (string) ($request->header('X-Krikkit-Order-Token') ?? '');
        $receipt = $commerce->publicSubmitSlip($module, $order, $token, $body);
        return response()->json($receipt, $receipt['replayed'] ? 200 : 201, ['Cache-Control' => 'no-store']);
    }
}
