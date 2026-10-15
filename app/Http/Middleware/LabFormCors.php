<?php

namespace App\Http\Middleware;

use App\Models\LabForm;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LabFormCors
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->is('api/lab-forms/*')) return $next($request);
        if (strlen($request->getContent()) > 16 * 1024) return response()->json(['message' => 'Form payload is too large.'], 413);
        $origin = $request->header('Origin');
        if ($origin !== null) {
            $form = LabForm::query()->where('uuid', $request->segment(3))->first();
            if (! $form || ! $this->allowsOrigin($origin, $form)) return response()->json(['message' => 'This origin is not allowed.'], 403);
        }
        if ($request->isMethod('OPTIONS')) {
            $response = response('', 204);
        } else {
            try { $response = $next($request); }
            catch (\Throwable $error) {
                $response = app(\Illuminate\Contracts\Debug\ExceptionHandler::class)->render($request, $error);
            }
        }
        if ($origin !== null) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Vary', 'Origin', false);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept');
        }
        return $response;
    }

    public function allowsOrigin(string $origin, LabForm $form): bool
    {
        $appUrl = parse_url(config('app.url'));
        $appOrigin = ($appUrl['scheme'] ?? '').'://'.($appUrl['host'] ?? '').(isset($appUrl['port']) ? ':'.$appUrl['port'] : '');
        if (app()->environment('local') && $origin === $appOrigin) return true;
        $publication = $form->project?->publication;
        if (! $publication?->isLive()) return false;
        $publishConfig = app(\App\Lab\Publish\LabPublishConfig::class);
        if (app()->environment('local') && $publishConfig->appHost() === 'localhost' && $publication->kind === 'subdomain') {
            return $origin === $publishConfig->subdomainUrl($publication->subdomain);
        }
        $host = $publication->kind === 'custom'
            ? ($publication->customHostVerified() ? $publication->custom_host : null)
            : $publication->subdomain.'.'.$publishConfig->parentDomain();
        return $host && $origin === 'https://'.$host;
    }
}
