<?php

namespace App\Http\Controllers\Api\Hosting;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API token management.
 *
 * Adapted from Reflex's auth.py + vmtypes_regions.py create-token:
 *   - POST   /tokens       → create a named token (shown once)
 *   - GET    /tokens       → list user's tokens (fingerprint only)
 *   - DELETE /tokens/{id}  → revoke
 */
class TokenController extends Controller
{
    /**
     * List the authenticated user's API tokens.
     */
    public function index(Request $request): JsonResponse
    {
        $tokens = ApiToken::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get()
            ->map->toApiArray()
            ->all();

        return response()->json(['tokens' => $tokens]);
    }

    /**
     * Create a new API token.
     * The plaintext token is returned ONLY in this response.
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'code' => 'hosting_tokens_unavailable',
            'message' => 'Hosting currently supports verified web sessions only. API token authentication is not available.',
        ], 501);
    }

    /**
     * Revoke (delete) an API token.
     */
    public function destroy(Request $request, ApiToken $token): JsonResponse
    {
        abort_unless($token->user_id === $request->user()->id, 403);

        $token->delete();

        return response()->json([
            'deleted' => true,
            'message' => 'Token revoked.',
        ]);
    }
}
