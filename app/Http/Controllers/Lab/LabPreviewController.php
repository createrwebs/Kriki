<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Lab\LabProjectAccess;
use App\Lab\Runtime\RuntimePolicy;
use App\Models\LabProject;
use App\Support\Seo\PageSeo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Private full-page preview for a Lab project. Public hosting is Lab → Publish.
 */
class LabPreviewController extends Controller
{
    public function __invoke(Request $request, LabProject $project, PageSeo $seo, RuntimePolicy $runtime): Response|JsonResponse
    {
        LabProjectAccess::assertOwner($project, $request->user());

        $verdict = $runtime->verdict($project);
        if (! $verdict['can_execute']) {
            return $this->unavailable($request, $project, $verdict);
        }

        $seo->page([
            'title' => filled($project->title) ? $project->title : __('dashboard.Preview'),
        ]);

        return response()
            ->view('lab.preview.preview', [
                'project' => [
                    'uuid' => $project->uuid,
                    'title' => $project->title,
                ],
                'guestPath' => self::sanitizeGuestPath($request->query('path')),
            ])
            ->header('Cross-Origin-Opener-Policy', 'same-origin')
            ->header('Cross-Origin-Embedder-Policy', 'credentialless');
    }

    public function php(Request $request, LabProject $project, RuntimePolicy $runtime): Response|JsonResponse
    {
        LabProjectAccess::assertOwner($project, $request->user());
        if (($project->project_type ?: 'react') !== 'php') {
            abort(404);
        }

        // Block all resources on this legacy endpoint, including active HTML/SVG.
        // Ownership and path checks do not isolate executable customer code.
        return $this->unavailable($request, $project, $runtime->verdict($project));
    }

    private function unavailable(Request $request, LabProject $project, array $runtime): Response|JsonResponse
    {
        $response = $request->expectsJson()
            ? response()->json(['code' => 'runtime_unavailable', 'message' => $runtime['reason'], 'runtime' => $runtime], 423)
            : response()->view('lab.preview.unavailable', ['project' => $project, 'runtime' => $runtime], 423);

        $response->headers->set('Cache-Control', 'no-store, private');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Content-Security-Policy', "default-src 'none'; style-src 'unsafe-inline'; frame-ancestors 'self'; base-uri 'none'; form-action 'none'");

        return $response;
    }

    private static function sanitizeGuestPath(mixed $raw): string
    {
        $path = trim((string) $raw);
        if ($path === '') {
            return '/';
        }

        if (str_contains($path, '://') || str_starts_with($path, '//')) {
            return '/';
        }

        if (! str_starts_with($path, '/')) {
            $path = '/'.$path;
        }

        return $path;
    }
}
