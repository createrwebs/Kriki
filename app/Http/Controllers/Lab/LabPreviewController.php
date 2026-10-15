<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Lab\LabProjectAccess;
use App\Lab\SiteWorkspace;
use App\Models\LabProject;
use App\Support\Seo\PageSeo;
use Illuminate\Http\BinaryFileResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Private full-page preview for a Lab project. Public hosting is Lab → Publish.
 */
class LabPreviewController extends Controller
{
    public function __invoke(Request $request, LabProject $project, PageSeo $seo): Response
    {
        LabProjectAccess::assertOwner($project, $request->user());

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

    public function php(Request $request, LabProject $project, SiteWorkspace $workspace): Response|BinaryFileResponse
    {
        LabProjectAccess::assertOwner($project, $request->user());
        if (($project->project_type ?: 'react') !== 'php') {
            abort(404);
        }

        $root = realpath($workspace->ensure($project));
        $relative = trim((string) $request->query('path', 'index.php'));
        $relative = ltrim(str_replace('\\', '/', $relative), '/');
        if ($relative === '' || str_contains($relative, '..') || ! preg_match('/^[A-Za-z0-9._\/-]+$/', $relative)) {
            abort(404);
        }

        $absolute = realpath($root.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relative));
        if ($absolute === false || ! str_starts_with($absolute, $root.DIRECTORY_SEPARATOR) || ! is_file($absolute)) {
            abort(404);
        }

        if (strtolower(pathinfo($absolute, PATHINFO_EXTENSION)) !== 'php') {
            return response()->file($absolute);
        }

        $previousDirectory = getcwd();
        chdir($root);
        try {
            $render = static function (string $file): string {
                ob_start();
                try {
                    include $file;
                    return (string) ob_get_clean();
                } catch (\Throwable $e) {
                    ob_end_clean();
                    throw $e;
                }
            };
            $body = $render($absolute);
        } catch (\Throwable $e) {
            report($e);
            return response('PHP preview failed.', 500)->header('Content-Type', 'text/plain; charset=utf-8');
        } finally {
            if ($previousDirectory !== false) {
                chdir($previousDirectory);
            }
        }

        return response($body)->header('Content-Type', 'text/html; charset=utf-8');
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
