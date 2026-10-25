<?php

namespace App\Lab\Publish;

use Illuminate\Support\Facades\File;
use InvalidArgumentException;
use RuntimeException;
use Throwable;
use ZipArchive;

/**
 * Deploy a browser-built production artifact (zip of dist/) to the live host root.
 */
final class LabPublishArtifactDeployer
{
    /** @var list<string> */
    private const DIST_FOLDERS = ['dist', 'build', 'out'];

    public function deploy(string $zipPath, string $destination, ?callable $validate = null): void
    {
        if (! is_file($zipPath)) {
            throw new InvalidArgumentException(__('dashboard.Upload a production build archive.'));
        }

        $maxBytes = max(1, (int) config('lab.publish.artifact_max_bytes', 50 * 1024 * 1024));
        $size = filesize($zipPath);
        if ($size === false || $size > $maxBytes) {
            throw new InvalidArgumentException(__('dashboard.The build archive is too large.'));
        }

        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException(__('dashboard.ZIP support is required to publish Lab sites.'));
        }

        $extractRoot = sys_get_temp_dir().DIRECTORY_SEPARATOR.'krikkit-pub-'.bin2hex(random_bytes(8));
        File::ensureDirectoryExists($extractRoot);

        $zip = new ZipArchive;
        $opened = $zip->open($zipPath);

        if ($opened !== true) {
            File::deleteDirectory($extractRoot);

            throw new InvalidArgumentException(__('dashboard.Could not read the build archive.'));
        }

        try {
            $this->assertSafeZipEntries($zip, $extractRoot);
            if (! $zip->extractTo($extractRoot)) {
                throw new RuntimeException(__('dashboard.Could not extract the build archive.'));
            }
        } catch (Throwable $e) {
            $zip->close();
            File::deleteDirectory($extractRoot);

            throw $e instanceof RuntimeException || $e instanceof InvalidArgumentException
                ? $e
                : new RuntimeException(__('dashboard.Could not extract the build archive.'), previous: $e);
        }

        $zip->close();

        $stage = $destination.'.stage-'.bin2hex(random_bytes(8));
        $backup = $destination.'.previous-'.bin2hex(random_bytes(8));
        $promoted = false;
        try {
            $siteRoot = $this->resolveSiteRoot($extractRoot);
            $this->assertPublishableSite($siteRoot);
            File::ensureDirectoryExists(dirname($destination));
            if (!File::copyDirectory($siteRoot, $stage)) throw new RuntimeException('Could not stage the production build.');
            $this->assertPublishableSite($stage);
            if ($validate !== null) $validate($stage);
            if (is_dir($destination) && !rename($destination, $backup)) throw new RuntimeException('Could not preserve the previous deployment.');
            try {
                if (!rename($stage, $destination)) throw new RuntimeException('Could not promote the staged build.');
                $promoted = true;
            } catch (Throwable $error) {
                if (is_dir($backup) && !is_dir($destination)) rename($backup, $destination);
                throw $error;
            }
        } finally {
            File::deleteDirectory($extractRoot);
            if (is_dir($stage)) File::deleteDirectory($stage);
            if ($promoted && is_dir($backup)) File::deleteDirectory($backup);
        }

        if (! is_file($destination.DIRECTORY_SEPARATOR.'index.html')) {
            if (is_dir($destination)) {
                File::deleteDirectory($destination);
            }

            throw new RuntimeException(__('dashboard.The build output is missing index.html.'));
        }
    }

    private function assertSafeZipEntries(ZipArchive $zip, string $extractRoot): void
    {
        $maxFiles = max(1, (int) config('lab.publish.artifact_max_files', 5000));
        $count = $zip->numFiles;
        $expanded = 0; $names = [];

        if ($count > $maxFiles) {
            throw new InvalidArgumentException(__('dashboard.The build archive contains too many files.'));
        }

        $extractReal = realpath($extractRoot);
        if ($extractReal === false) {
            throw new RuntimeException(__('dashboard.Could not prepare a temporary folder for the build.'));
        }

        for ($i = 0; $i < $count; $i++) {
            $name = $zip->getNameIndex($i);
            if (! is_string($name) || $name === '') {
                continue;
            }

            $normalized = str_replace('\\', '/', $name);
            if (str_starts_with($normalized, '/') || preg_match('/(^|\/)\.{1,2}(\/|$)|^[a-zA-Z]:|\x00/', $normalized)) {
                throw new InvalidArgumentException(__('dashboard.The build archive contains unsafe paths.'));
            }
            foreach (explode('/', rtrim($normalized, '/')) as $component) {
                if ($component === '' || preg_match('/[<>:"|?*\x00-\x1f]|[. ]$/', $component)
                    || preg_match('/^(CON|PRN|AUX|NUL|COM[1-9]|LPT[1-9])(?:\.|$)/i', $component)) {
                    throw new InvalidArgumentException(__('dashboard.The build archive contains unsafe paths.'));
                }
            }
            $operatingSystem = 0; $attributes = 0;
            $zip->getExternalAttributesIndex($i, $operatingSystem, $attributes);
            if ((($attributes >> 16) & 0170000) === 0120000) throw new InvalidArgumentException('The build archive contains a symbolic link.');
            if (str_ends_with($normalized, '/')) continue;
            $canonical = strtolower(preg_replace('~/+~', '/', $normalized));
            if (isset($names[$canonical])) throw new InvalidArgumentException('The build archive contains duplicate paths.');
            $names[$canonical] = true;
            $expanded += (int) ($zip->statIndex($i)['size'] ?? 0);
            if ($expanded > (int) config('lab.publish.artifact_max_expanded_bytes', 200 * 1024 * 1024)) throw new InvalidArgumentException('The expanded build archive is too large.');

            $target = $extractReal.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $normalized);
            $parent = dirname($target);
            File::ensureDirectoryExists($parent);
            $parentReal = realpath($parent);
            if ($parentReal === false || ! str_starts_with($parentReal, $extractReal)) {
                throw new InvalidArgumentException(__('dashboard.The build archive contains unsafe paths.'));
            }
        }
    }

    private function resolveSiteRoot(string $extractRoot): string
    {
        foreach (self::DIST_FOLDERS as $folder) {
            $candidate = $extractRoot.DIRECTORY_SEPARATOR.$folder;
            if (is_dir($candidate) && is_file($candidate.DIRECTORY_SEPARATOR.'index.html')) {
                return $candidate;
            }
        }

        $entries = array_values(array_filter(
            scandir($extractRoot) ?: [],
            static fn (string $name): bool => $name !== '.' && $name !== '..',
        ));

        if (count($entries) === 1) {
            $only = $extractRoot.DIRECTORY_SEPARATOR.$entries[0];
            if (is_dir($only)) {
                foreach (self::DIST_FOLDERS as $folder) {
                    $nested = $only.DIRECTORY_SEPARATOR.$folder;
                    if (is_dir($nested) && is_file($nested.DIRECTORY_SEPARATOR.'index.html')) {
                        return $nested;
                    }
                }

                if (is_file($only.DIRECTORY_SEPARATOR.'index.html')) {
                    return $only;
                }
            }
        }

        if (is_file($extractRoot.DIRECTORY_SEPARATOR.'index.html')) {
            return $extractRoot;
        }

        throw new RuntimeException(__('dashboard.The build finished but no dist/ (or build/) folder was found.'));
    }

    private function assertPublishableSite(string $siteRoot): void
    {
        if (! is_file($siteRoot.DIRECTORY_SEPARATOR.'index.html')) {
            throw new RuntimeException(__('dashboard.The build output is missing index.html.'));
        }
    }
}
