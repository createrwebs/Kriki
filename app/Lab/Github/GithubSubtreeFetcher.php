<?php

namespace App\Lab\Github;

use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Fetch a single directory prefix from GitHub via git trees + blobs (no full zipball).
 */
final class GithubSubtreeFetcher
{
    private const BLOB_POOL = 40;

    public function __construct(
        private readonly GithubApiClient $api = new GithubApiClient,
    ) {}

    /**
     * @return array<string, string> relative path (under $prefix stripped) → file contents
     */
    public function fetch(
        string $owner,
        string $repo,
        string $prefix,
        ?string $ref = null,
        ?string $accessToken = null,
        int $maxFiles = 2500,
        int $maxFileBytes = 2_097_152,
    ): array {
        $prefix = trim($prefix, '/');
        if ($prefix === '' || str_contains($prefix, '..') || preg_match('/[\\\\\x00-\x1f\x7f:%]/', $prefix)) {
            throw new RuntimeException(__('dashboard.Could not import repository.'));
        }
        $maxFiles = max(1, min(2500, $maxFiles));
        $maxFileBytes = max(1, min(2_097_152, $maxFileBytes));
        $maxExpanded = max(1, min(100 * 1024 * 1024, (int) config('lab.github.max_expanded_bytes', 100 * 1024 * 1024)));

        $started = microtime(true);
        $sha = $this->api->resolveCommitSha($owner, $repo, $ref, $accessToken);
        $tree = $this->api->fetchRecursiveTree($owner, $repo, $sha, $accessToken);

        $prefixSlash = $prefix.'/';
        $blobs = []; $seen = []; $expanded = 0;
        foreach ($tree as $entry) {
            $path = (string) ($entry['path'] ?? '');
            if ($path !== $prefix && ! str_starts_with($path, $prefixSlash)) {
                continue;
            }

            $relative = $path === $prefix
                ? ''
                : substr($path, strlen($prefixSlash));
            if ($relative === '' && $entry['type'] === 'tree') continue;
            if ($relative === '' || !mb_check_encoding($relative, 'UTF-8') || count(explode('/', $path)) > 32
                || preg_match('/[\\\\\x00-\x1f\x7f<>:"|?*]/', $relative) || preg_match('/%[a-f0-9]{2}/i', $relative)) throw new RuntimeException('The selected source contains an unsafe path.');
            $parts = explode('/', $relative); $current = '';
            foreach ($parts as $index => $part) {
                if ($part === '' || $part === '.' || $part === '..' || preg_match('/[. ]$/', $part)
                    || preg_match('/^(CON|PRN|AUX|NUL|COM[1-9]|LPT[1-9])(?:\.|$)/i', $part)) throw new RuntimeException('The selected source contains an unsafe path.');
                $current = $current === '' ? $part : $current.'/'.$part;
                $key = mb_strtolower($current);
                $directory = $index < count($parts) - 1 || $entry['type'] === 'tree';
                if (isset($seen[$key]) && ($seen[$key]['path'] !== $current || $seen[$key]['directory'] !== $directory
                    || (!$directory && $index === count($parts) - 1))) throw new RuntimeException('The selected source contains conflicting paths.');
                $seen[$key] = ['path' => $current, 'directory' => $directory];
            }
            if ($entry['type'] === 'tree') continue;
            if ($entry['type'] !== 'blob' || !in_array($entry['mode'], ['100644','100755'], true)) throw new RuntimeException('Symlinks and submodules are unsupported in source imports.');
            $size = $entry['size'];
            if ($size > $maxFileBytes) throw new RuntimeException('A selected source file exceeds the import size limit. Select a smaller root.');
            $expanded += $size;
            if ($expanded > $maxExpanded) throw new RuntimeException('The selected source exceeds the total import size limit.');

            $blobs[$relative] = ['sha' => $entry['sha'], 'size' => $size];
            if (count($blobs) > $maxFiles) {
                throw new RuntimeException(__('dashboard.Repository has too many files to import. Raise the max file count under API Integration → GitHub, or set Root directory (e.g. apps/www).'));
            }
        }

        if ($blobs === []) {
            throw new RuntimeException(__('dashboard.No importable files were found in the repository.'));
        }

        $files = $this->downloadBlobs($owner, $repo, $blobs, $accessToken, $maxFileBytes);

        Log::info('lab.github.subtree.ok', [
            'owner' => $owner,
            'repo' => $repo,
            'prefix' => $prefix,
            'ref' => $ref,
            'commit' => $sha,
            'blob_count' => count($blobs),
            'file_count' => count($files),
            'elapsed_ms' => (int) round((microtime(true) - $started) * 1000),
        ]);

        return $files;
    }

    /**
     * @param  array<string, array{sha: string, size: int}>  $relativeToSha
     * @return array<string, string>
     */
    private function downloadBlobs(
        string $owner,
        string $repo,
        array $relativeToSha,
        ?string $accessToken,
        int $maxFileBytes,
    ): array {
        $files = [];
        $chunks = array_chunk($relativeToSha, self::BLOB_POOL, true);
        $api = GithubHttp::apiBase();
        $timeout = max(1, (int) config('lab.github.timeout', 60));

        foreach ($chunks as $chunk) {
            $responses = Http::pool(function (Pool $pool) use ($chunk, $owner, $repo, $accessToken, $api, $timeout) {
                foreach ($chunk as $relative => $blob) {
                    $maximumBody = (int) ceil($blob['size'] * 4 / 3) + 131072;
                    $req = $pool->as($relative)
                        ->accept('application/vnd.github+json')
                        ->withHeaders([
                            'X-GitHub-Api-Version' => '2022-11-28',
                            'User-Agent' => config('lab.github.user_agent', 'Krikkit-Lab'),
                        ])
                        ->timeout($timeout)
                        ->withOptions(['progress' => static function ($total, $downloaded) use ($maximumBody): void {
                            if ($downloaded > $maximumBody || $total > $maximumBody) throw new RuntimeException('GitHub blob response exceeds the source limit.');
                        }]);

                    if (filled($accessToken)) {
                        $req = $req->withToken($accessToken);
                    }

                    $req->get(
                        $api.'/repos/'.rawurlencode($owner).'/'.rawurlencode($repo).'/git/blobs/'.rawurlencode($blob['sha'])
                    );
                }
            });

            foreach ($chunk as $relative => $blob) {
                $response = $responses[$relative] ?? null;
                if (!$response instanceof Response || !$response->successful()) throw new RuntimeException('Could not download every selected source file. Retry the import; no source was changed.');
                if (strlen($response->body()) > (int) ceil($blob['size'] * 4 / 3) + 131072) throw new RuntimeException('GitHub blob response exceeds the source limit.');

                /** @var array<string, mixed> $payload */
                $payload = $response->json() ?? [];
                if (!is_array($payload) || ($payload['encoding'] ?? '') !== 'base64' || !is_string($payload['content'] ?? null)
                    || ($payload['sha'] ?? null) !== $blob['sha']) throw new RuntimeException('GitHub returned invalid source blob metadata.');

                $decoded = base64_decode(str_replace("\n", '', (string) $payload['content']), true);
                if ($decoded === false || strlen($decoded) > $maxFileBytes || strlen($decoded) !== $blob['size']
                    || ($payload['size'] ?? null) !== $blob['size']
                    || hash('sha1', 'blob '.strlen($decoded)."\0".$decoded) !== $blob['sha']) throw new RuntimeException('GitHub source content does not match the selected tree. Retry the import; no source was changed.');

                $files[$relative] = $decoded;
            }
        }

        if ($files === []) {
            throw new RuntimeException(__('dashboard.No importable files were found in the repository.'));
        }

        return $files;
    }
}
