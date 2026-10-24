<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Lab\Modules\ModuleInstallations;
use App\Lab\Modules\SchemaProposals;
use App\Lab\Modules\ContactMessages;
use App\Lab\Modules\ModuleEmbed;
use App\Lab\Modules\NewsArticles;
use App\Models\LabChangeProposal;
use App\Models\LabModuleInstallation;
use App\Models\LabProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

final class LabModuleController extends Controller
{
    public function index(Request $request, LabProject $project, ModuleInstallations $modules): JsonResponse
    {
        return $this->respond($modules->listing($request->user(), $project));
    }

    public function capability(Request $request, LabProject $project, string $key, ModuleInstallations $modules): JsonResponse
    {
        $body = $this->body($request, ['enabled', 'expected_revision']);
        abort_unless(isset($body->enabled) && is_bool($body->enabled) && isset($body->expected_revision) && is_int($body->expected_revision) && $body->expected_revision >= 0, 422, 'Invalid module capability.');
        return $this->respond(['revision' => $modules->select($request->user(), $project, $key, $body->enabled, $body->expected_revision)]);
    }

    public function store(Request $request, LabProject $project, ModuleInstallations $modules): JsonResponse
    {
        $body = $this->body($request, ['key', 'expected_revision', 'config', 'idempotency_key']);
        Validator::make((array) $body, ['key' => ['required', 'string', 'max:48'], 'idempotency_key' => ['required', 'uuid']])->validate();
        abort_unless(isset($body->expected_revision) && is_int($body->expected_revision) && $body->expected_revision >= 0
            && isset($body->config) && $body->config instanceof \stdClass && count((array) $body->config) === 0, 422, 'Module config must be an empty object and revision an integer.');
        $result = $modules->prepare($request->user(), $project, $body->key, $body->expected_revision, [], $body->idempotency_key);
        return $this->respond(['installation' => $modules->payload($result['installation']), 'revision' => $result['project_revision']], $result['created'] ? 201 : 200);
    }

    public function state(Request $request, LabProject $project, LabModuleInstallation $module, ModuleInstallations $modules): JsonResponse
    {
        $body = $this->body($request, ['state', 'expected_revision']);
        abort_unless(isset($body->state) && is_string($body->state) && isset($body->expected_revision) && is_int($body->expected_revision) && $body->expected_revision >= 1, 422, 'Invalid module state.');
        return $this->respond(['installation' => $modules->payload($modules->setState($request->user(), $project, $module, $body->state, $body->expected_revision))]);
    }

    public function propose(Request $request, LabProject $project, LabModuleInstallation $module, SchemaProposals $proposals): JsonResponse
    {
        $body = $this->body($request, ['expected_revision']);
        abort_unless(isset($body->expected_revision) && is_int($body->expected_revision) && $body->expected_revision >= 1, 422, 'Invalid module revision.');
        $proposal = $proposals->prepare($request->user(), $project, $module, $body->expected_revision);
        return $this->respond(['proposal' => $proposals->detail($request->user(), $project, $proposal)], 201);
    }

    public function proposal(Request $request, LabProject $project, LabChangeProposal $proposal, SchemaProposals $proposals): JsonResponse
    {
        return $this->respond(['proposal' => $proposals->detail($request->user(), $project, $proposal)]);
    }

    public function applyProposal(Request $request, LabProject $project, LabChangeProposal $proposal, SchemaProposals $proposals): JsonResponse
    {
        $body = $this->body($request, ['impact_hash', 'expected_revision']);
        abort_unless(isset($body->impact_hash) && is_string($body->impact_hash) && preg_match('/^[a-f0-9]{64}$/D', $body->impact_hash)
            && isset($body->expected_revision) && is_int($body->expected_revision) && $body->expected_revision >= 1, 422, 'Invalid proposal approval.');
        return $this->respond(['receipt' => $proposals->apply($request->user(), $project, $proposal, $body->impact_hash, $body->expected_revision)]);
    }

    public function cancelProposal(Request $request, LabProject $project, LabChangeProposal $proposal, SchemaProposals $proposals): JsonResponse
    {
        $this->body($request, []);
        return $this->respond(['proposal' => $proposals->cancel($request->user(), $project, $proposal)]);
    }

    public function contacts(Request $request, LabProject $project, LabModuleInstallation $module, ContactMessages $contacts): JsonResponse
    {
        $filters = $request->validate(['status' => ['sometimes','in:new,read,archived']]);
        return $this->respond($contacts->listing($request->user(), $project, $module, $filters['status'] ?? null));
    }

    public function contactState(Request $request, LabProject $project, LabModuleInstallation $module, string $message, ContactMessages $contacts): JsonResponse
    {
        $body = $this->body($request, ['expected_version','status']);
        abort_unless(isset($body->expected_version) && is_int($body->expected_version) && isset($body->status) && is_string($body->status), 422, 'Invalid contact transition.');
        return $this->respond(['message' => $contacts->transition($request->user(), $project, $module, $message, $body->expected_version, $body->status)]);
    }

    public function embed(Request $request, LabProject $project, LabModuleInstallation $module, ModuleEmbed $embed): JsonResponse
    {
        $body = $this->body($request, ['route','expected_app_hash']);
        abort_unless(isset($body->route) && is_string($body->route) && strlen($body->route) <= 250
            && isset($body->expected_app_hash) && is_string($body->expected_app_hash) && preg_match('/^[a-f0-9]{64}$/D', $body->expected_app_hash), 422, 'Invalid widget placement.');
        return $this->respond($embed->embed($request->user(), $project, $module, $body->route, $body->expected_app_hash));
    }

    public function news(Request $request, LabProject $project, LabModuleInstallation $module, NewsArticles $news): JsonResponse
    {
        $filters = $request->validate(['status' => ['sometimes','in:draft,published,archived']]);
        return $this->respond($news->listing($request->user(), $project, $module, $filters['status'] ?? null));
    }

    public function storeNews(Request $request, LabProject $project, LabModuleInstallation $module, NewsArticles $news): JsonResponse
    {
        $body = $this->body($request, ['idempotency_key','slug','title','excerpt','body','status'], 65536);
        $receipt = $news->create($request->user(), $project, $module, (array) $body);
        return $this->respond(['article' => $receipt], $receipt['replayed'] ? 200 : 201);
    }

    public function updateNews(Request $request, LabProject $project, LabModuleInstallation $module, string $article, NewsArticles $news): JsonResponse
    {
        $body = $this->body($request, ['expected_version','slug','title','excerpt','body','status'], 65536);
        return $this->respond(['article' => $news->update($request->user(), $project, $module, $article, (array) $body)]);
    }

    public function products(Request $request, LabProject $project, LabModuleInstallation $module, \App\Lab\Modules\CommerceStore $commerce): JsonResponse
    {
        return $this->respond($commerce->listProducts($request->user(), $project, $module));
    }

    public function storeProduct(Request $request, LabProject $project, LabModuleInstallation $module, \App\Lab\Modules\CommerceStore $commerce): JsonResponse
    {
        $body = $this->body($request, ['idempotency_key','sku','title','description','price_satang','active'], 16384);
        $receipt = $commerce->createProduct($request->user(), $project, $module, (array) $body);
        return $this->respond(['product' => $receipt], $receipt['replayed'] ? 200 : 201);
    }

    public function updateProduct(Request $request, LabProject $project, LabModuleInstallation $module, string $product, \App\Lab\Modules\CommerceStore $commerce): JsonResponse
    {
        $body = $this->body($request, ['expected_version','sku','title','description','price_satang','active'], 16384);
        return $this->respond(['product' => $commerce->updateProduct($request->user(), $project, $module, $product, (array) $body)]);
    }

    public function orders(Request $request, LabProject $project, LabModuleInstallation $module, \App\Lab\Modules\CommerceStore $commerce): JsonResponse
    {
        $filters = $request->validate(['status' => ['sometimes','in:awaiting_payment,pending_review,paid,rejected,cancelled']]);
        return $this->respond($commerce->listOrders($request->user(), $project, $module, $filters['status'] ?? null));
    }

    public function paymentSettings(Request $request, LabProject $project, LabModuleInstallation $module, \App\Lab\Modules\CommerceStore $commerce): JsonResponse
    {
        return $this->respond($commerce->getPaymentSettings($request->user(), $project, $module));
    }

    public function savePaymentSettings(Request $request, LabProject $project, LabModuleInstallation $module, \App\Lab\Modules\CommerceStore $commerce): JsonResponse
    {
        $body = $this->body($request, ['expected_version','recipient_type','recipient','payee_label','instructions','enabled'], 8192);
        return $this->respond($commerce->savePaymentSettings($request->user(), $project, $module, (array) $body));
    }

    public function reviewPaymentAttempt(Request $request, LabProject $project, LabModuleInstallation $module, string $attempt, \App\Lab\Modules\CommerceStore $commerce): JsonResponse
    {
        $body = $this->body($request, ['expected_version','decision','reason'], 4096);
        return $this->respond($commerce->reviewPaymentSlip($request->user(), $project, $module, $attempt, (array) $body));
    }

    private function body(Request $request, array $keys, int $maxBytes = 2048): \stdClass
    {
        abort_if(strlen($request->getContent()) > $maxBytes, 413, 'Module request is too large.');
        abort_unless($request->isJson(), 422, 'A JSON object is required.');
        try { $body = json_decode($request->getContent(), false, 16, JSON_THROW_ON_ERROR); }
        catch (\JsonException) { abort(422, 'Invalid JSON object.'); }
        abort_unless($body instanceof \stdClass && array_diff(array_keys((array) $body), $keys) === [], 422, 'Unexpected module request fields.');
        return $body;
    }

    private function respond(array $body, int $status = 200): JsonResponse
    {
        return response()->json($body, $status, ['Cache-Control' => 'private, no-store']);
    }
}
