<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

final class EnsureHostingReady
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('hosting.enabled', false)) {
            return response()->json(['code' => 'hosting_unavailable', 'message' => 'Experimental hosting is not enabled. Use the existing Publish workflow.'], 503);
        }

        foreach (['deployments', 'deployment_logs', 'app_secrets', 'api_tokens'] as $table) {
            if (! Schema::hasTable($table)) {
                return response()->json(['code' => 'hosting_not_ready', 'message' => 'Hosting setup is incomplete. An administrator must apply the hosting migrations.'], 503);
            }
        }

        return $next($request);
    }
}
