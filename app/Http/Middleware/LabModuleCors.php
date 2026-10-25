<?php

namespace App\Http\Middleware;

use App\Lab\Publish\LabPublishConfig;
use App\Models\LabModuleInstallation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class LabModuleCors
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->is('api/lab-widgets/*')) return $next($request);
        $maxBytes = $request->isMethod('POST') && preg_match('~^api/lab-widgets/[^/]+/orders/[^/]+/slips$~D', $request->path()) ? \App\Lab\Modules\SlipImage::MAX_JSON_BYTES : 20 * 1024;
        $tooLarge = strlen($request->getContent()) > $maxBytes;
        $origin = $request->header('Origin');
        $module = LabModuleInstallation::query()->where('uuid', $request->segment(3))->first();
        if (!$module || !is_string($origin) || !$this->allowsOrigin($origin, $module)) return response()->json(['message' => 'This widget origin is not allowed.'], 403, ['Cache-Control' => 'no-store']);
        try { $response = $tooLarge ? response()->json(['message' => 'Widget request is too large.'], 413) : ($request->isMethod('OPTIONS') ? response('', 204) : $next($request)); }
        catch (\Throwable $error) { $response = app(\Illuminate\Contracts\Debug\ExceptionHandler::class)->render($request, $error); }
        $response->headers->set('Access-Control-Allow-Origin', $origin);
        $response->headers->set('Vary', 'Origin', false);
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, X-Krikkit-Order-Token');
        $response->headers->set('Cache-Control', 'no-store');
        $response->headers->remove('Access-Control-Allow-Credentials');
        return $response;
    }

    public function allowsOrigin(string $origin, LabModuleInstallation $module): bool
    {
        $config = app(LabPublishConfig::class);
        $app = parse_url(config('app.url'));
        $appOrigin = ($app['scheme'] ?? '').'://'.($app['host'] ?? '').(isset($app['port']) ? ':'.$app['port'] : '');
        if (app()->environment('local') && $origin === $appOrigin) return true;
        $publication = $module->project?->publication;
        if (!$publication?->isLive()) return false;
        if ($publication->kind === 'custom') return $publication->customHostVerified() && $origin === 'https://'.$publication->custom_host;
        if (app()->environment('local') && $config->appHost() === 'localhost') return $origin === $config->subdomainUrl($publication->subdomain);
        return $origin === 'https://'.$publication->subdomain.'.'.$config->parentDomain();
    }
}
