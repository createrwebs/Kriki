<?php

namespace App\Lab\Github;

use Illuminate\Support\Facades\File;
use InvalidArgumentException;
use RuntimeException;
use ZipArchive;

/** Validate and stream untrusted source archives without executing their contents. */
final class GithubSourceArchive
{
    public function extract(string $path): string
    {
        if (!class_exists(ZipArchive::class)) throw new RuntimeException('ZIP support is required to import from GitHub.');
        if (!is_file($path) || filesize($path) > min(25 * 1024 * 1024, LabGithubImportLimits::maxZipBytes())) throw new InvalidArgumentException('The GitHub archive is too large or unavailable.');
        $rawNames = $this->rawNames($path);
        $zip = new ZipArchive;
        if ($zip->open($path, ZipArchive::CHECKCONS) !== true) throw new InvalidArgumentException('Could not read the GitHub repository archive.');
        $root = null; $complete = false;
        try {
            $maxExpanded = max(1, min(100 * 1024 * 1024, (int) config('lab.github.max_expanded_bytes', 100 * 1024 * 1024)));
            $entries = $this->entries($zip, $maxExpanded, $rawNames);
            $root = sys_get_temp_dir().DIRECTORY_SEPARATOR.'krikkit-gh-'.bin2hex(random_bytes(12));
            if (!mkdir($root, 0700)) throw new RuntimeException('Could not prepare the source extraction directory.');
            $expanded = 0;
            foreach ($entries as [$name, $size, $directory]) {
                $destination = $root.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $name);
                if ($directory) { File::ensureDirectoryExists($destination); continue; }
                File::ensureDirectoryExists(dirname($destination));
                $input = $zip->getStream($name);
                if ($input === false) throw new RuntimeException('Could not read a source archive entry.');
                $output = fopen($destination, 'xb');
                if ($output === false) { fclose($input); throw new RuntimeException('Could not create a source archive entry.'); }
                $actual = 0;
                try {
                    while (!feof($input)) {
                        $chunk = fread($input, 64 * 1024);
                        if ($chunk === false || ($chunk === '' && !feof($input))) throw new RuntimeException('Could not decode a source archive entry.');
                        $actual += strlen($chunk); $expanded += strlen($chunk);
                        if ($actual > $size || $expanded > $maxExpanded) throw new InvalidArgumentException('The expanded source archive is too large.');
                        if ($chunk !== '' && fwrite($output, $chunk) !== strlen($chunk)) throw new RuntimeException('Could not write a source archive entry.');
                    }
                    if ($actual !== $size) throw new InvalidArgumentException('The source archive has an invalid entry length.');
                } finally { fclose($input); fclose($output); }
            }
            $complete = true;
            return $root;
        } finally {
            $zip->close();
            if (!$complete && $root !== null && is_dir($root)) File::deleteDirectory($root);
        }
    }

    /** @return list<array{string,int,bool}> */
    private function entries(ZipArchive $zip, int $maxExpanded, array $rawNames): array
    {
        $maxFiles = min(2500, LabGithubImportLimits::maxFiles());
        $maxFileBytes = max(1, min(2 * 1024 * 1024, (int) config('lab.github.max_file_bytes', 2 * 1024 * 1024)));
        if ($zip->numFiles === 0 || $zip->numFiles > $maxFiles * 2 || $zip->numFiles !== count($rawNames)) throw new InvalidArgumentException('The source archive contains too many entries or is empty.');
        $entries = []; $paths = []; $expanded = 0; $files = 0;
        for ($index = 0; $index < $zip->numFiles; $index++) {
            $stat = $zip->statIndex($index);
            if ($stat === false) throw new InvalidArgumentException('Invalid source archive entry.');
            $name = $stat['name'];
            if ($name !== $rawNames[$index]) throw new InvalidArgumentException('The source archive contains an ambiguous filename encoding.');
            $directory = str_ends_with($name, '/');
            $parts = explode('/', $directory ? substr($name, 0, -1) : $name);
            if (count($parts) > 32 || !mb_check_encoding($name, 'UTF-8') || str_contains($name, '\\') || preg_match('/%[a-f0-9]{2}/i', $name)) throw new InvalidArgumentException('The source archive contains an unsafe path.');
            $prefix = '';
            foreach ($parts as $offset => $part) {
                if ($part === '' || $part === '.' || $part === '..' || in_array(strtolower($part), ['.git', '.githooks'], true)
                    || preg_match('/[<>:"|?*\x00-\x1f]|[. ]$/', $part) || preg_match('/^(CON|PRN|AUX|NUL|COM[1-9]|LPT[1-9])(?:\.|$)/i', $part)) throw new InvalidArgumentException('The source archive contains an unsafe path.');
                $prefix .= ($prefix === '' ? '' : '/').$part;
                $canonical = mb_strtolower($prefix, 'UTF-8');
                $explicit = $offset === count($parts) - 1;
                $isDirectory = !$explicit || $directory;
                if (isset($paths[$canonical])) {
                    [$original, $wasDirectory, $wasExplicit] = $paths[$canonical];
                    if ($original !== $prefix || $wasDirectory !== $isDirectory || ($explicit && $wasExplicit)) throw new InvalidArgumentException('The source archive contains colliding paths.');
                    $explicit = $explicit || $wasExplicit;
                }
                $paths[$canonical] = [$prefix, $isDirectory, $explicit];
            }
            $system = 0; $attributes = 0;
            if (!$zip->getExternalAttributesIndex($index, $system, $attributes)) throw new InvalidArgumentException('Could not inspect source archive entry type.');
            $type = ($attributes >> 16) & 0170000;
            if (!in_array($type, [0, 0100000, 0040000], true) || ($type !== 0 && ($type === 0040000) !== $directory)) throw new InvalidArgumentException('The source archive contains a link or unsupported file type.');
            $size = (int) $stat['size'];
            $compressed = (int) $stat['comp_size'];
            if ($size < 0 || $size > $maxFileBytes || ($directory && $size !== 0) || ($size > 0 && $size > max(1, $compressed) * 100)
                || ($stat['encryption_method'] ?? 0) !== 0) throw new InvalidArgumentException('The source archive contains an oversized, compressed or encrypted entry.');
            $expanded += $size;
            if (!$directory && ++$files > $maxFiles) throw new InvalidArgumentException('The source archive contains too many files.');
            if ($expanded > $maxExpanded) throw new InvalidArgumentException('The expanded source archive is too large.');
            $entries[] = [$name, $size, $directory];
        }
        if ($files === 0) throw new InvalidArgumentException('The source archive has no files.');
        return $entries;
    }

    /** libzip exposes NUL-truncated names; check the original header bytes as well. */
    private function rawNames(string $path): array
    {
        $bytes = file_get_contents($path, false, null, 0, 25 * 1024 * 1024 + 1);
        if ($bytes === false || strlen($bytes) > 25 * 1024 * 1024) throw new InvalidArgumentException('The source archive is unavailable or too large.');
        $end = null;
        for ($candidate = strlen($bytes) - 22; $candidate >= max(0, strlen($bytes) - 65557); $candidate--) {
            if (substr($bytes, $candidate, 4) === "PK\x05\x06" && unpack('vsize', substr($bytes, $candidate + 20, 2))['size'] === strlen($bytes) - $candidate - 22) {
                $end = $candidate;
                break;
            }
        }
        if ($end === null) throw new InvalidArgumentException('Invalid ZIP directory.');
        $directory = unpack('vdisk/vstart/vdisk_count/vcount/Vsize/Voffset/vcomment', substr($bytes, $end + 4, 18));
        if ($directory['disk'] !== 0 || $directory['start'] !== 0 || $directory['disk_count'] !== $directory['count']
            || $directory['count'] > 5000 || $end + 22 + $directory['comment'] !== strlen($bytes)
            || $directory['offset'] + $directory['size'] !== $end) throw new InvalidArgumentException('Unsupported or inconsistent ZIP directory.');
        $cursor = $directory['offset']; $names = [];
        for ($i = 0; $i < $directory['count']; $i++) {
            if ($cursor + 46 > $end || substr($bytes, $cursor, 4) !== "PK\x01\x02") throw new InvalidArgumentException('Invalid ZIP directory entry.');
            $lengths = unpack('vname/vextra/vcomment', substr($bytes, $cursor + 28, 6));
            $next = $cursor + 46 + $lengths['name'] + $lengths['extra'] + $lengths['comment'];
            if ($next > $end) throw new InvalidArgumentException('Invalid ZIP entry bounds.');
            $name = substr($bytes, $cursor + 46, $lengths['name']);
            if ($name === '' || str_contains($name, "\0")) throw new InvalidArgumentException('The source archive contains an unsafe filename.');
            $local = unpack('Voffset', substr($bytes, $cursor + 42, 4))['offset'];
            if ($local + 30 > $directory['offset'] || substr($bytes, $local, 4) !== "PK\x03\x04") throw new InvalidArgumentException('Invalid ZIP local header.');
            $localLengths = unpack('vname/vextra', substr($bytes, $local + 26, 4));
            if ($local + 30 + $localLengths['name'] + $localLengths['extra'] > $directory['offset']
                || substr($bytes, $local + 30, $localLengths['name']) !== $name) throw new InvalidArgumentException('ZIP local and directory names differ.');
            $names[] = $name;
            $cursor = $next;
        }
        if ($cursor !== $end) throw new InvalidArgumentException('Invalid ZIP directory length.');
        return $names;
    }
}
