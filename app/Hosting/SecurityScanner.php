<?php

namespace App\Hosting;

use App\Models\LabProject;
use Illuminate\Support\Facades\File;
use RuntimeException;
use ZipArchive;

/**
 * Security scanner for project source code.
 * Adapted from Reflex's scan.py: zip source → scan → report violations.
 */
final class SecurityScanner
{
    /** @var list<string> */
    private const SKIP_DIRS = [
        '.git', '.next', '.nuxt', '.venv', '.web', '__pycache__',
        'build', 'dist', 'node_modules', 'out', 'vendor', 'venv',
        '.krikkit', '.krikkit-tx', '.mypy_cache', '.pytest_cache',
        '.ruff_cache', '.cache', '.turbo', '.vercel',
    ];

    private const MAX_FILE_BYTES = 1_000_000;
    private const MAX_ZIP_BYTES = 50 * 1024 * 1024;

    /** @var list<string> */
    private const SCANNABLE_EXTENSIONS = [
        'php', 'js', 'jsx', 'ts', 'tsx', 'html', 'vue', 'svelte',
        'py', 'rb', 'go', 'rs', 'java', 'c', 'cpp', 'cs', 'sh',
        'env', 'yml', 'yaml', 'json', 'xml', 'sql',
    ];

    /** @var list<array{pattern: string, severity: string, title: string, recommendation: string}> */
    private const RULES = [
        ['pattern' => '/\beval\s*\(/i', 'severity' => 'critical', 'title' => 'Use of eval()', 'recommendation' => 'Replace eval() with JSON.parse() or a sandboxed expression parser.'],
        ['pattern' => '/\bdangerouslySetInnerHTML\b/', 'severity' => 'high', 'title' => 'dangerouslySetInnerHTML usage', 'recommendation' => 'Sanitize HTML with DOMPurify before injecting.'],
        ['pattern' => '/\bdocument\.write\s*\(/i', 'severity' => 'high', 'title' => 'document.write() usage', 'recommendation' => 'Use DOM manipulation methods instead.'],
        ['pattern' => '/(?:api[_-]?key|secret[_-]?key|password|token)\s*[:=]\s*["\'][^"\']{8,}["\']/i', 'severity' => 'critical', 'title' => 'Hardcoded secret detected', 'recommendation' => 'Move secrets to environment variables or a secrets manager.'],
        ['pattern' => '/\b(?:exec|system|passthru|shell_exec|popen|proc_open)\s*\(/i', 'severity' => 'critical', 'title' => 'Command execution function', 'recommendation' => 'Avoid shell execution with user input.'],
        ['pattern' => '/\b(?:mysql_query|mysqli_query)\s*\(\s*["\'].*\$/i', 'severity' => 'critical', 'title' => 'Potential SQL injection', 'recommendation' => 'Use prepared statements or an ORM query builder.'],
        ['pattern' => '/\binnerHTML\s*=\s*[^;]*\+/i', 'severity' => 'high', 'title' => 'innerHTML with concatenation', 'recommendation' => 'Use textContent for plain text or sanitize HTML.'],
        ['pattern' => '/\b(?:http|ftp):\/\/[^"\'\s]+\b/i', 'severity' => 'low', 'title' => 'Insecure URL (HTTP/FTP)', 'recommendation' => 'Use HTTPS for all external resources.'],
        ['pattern' => '/\b(?:cors|access-control-allow-origin)\s*[:=]\s*["\']?\*/i', 'severity' => 'medium', 'title' => 'Wildcard CORS policy', 'recommendation' => 'Restrict CORS to specific trusted domains.'],
        ['pattern' => '/\b(?:localStorage|sessionStorage)\s*\.\s*setItem\s*\([^)]*(?:token|password|secret|key)/i', 'severity' => 'medium', 'title' => 'Sensitive data in browser storage', 'recommendation' => 'Use httpOnly cookies for tokens.'],
        ['pattern' => '/\bnew\s+Function\s*\(/i', 'severity' => 'high', 'title' => 'Dynamic code via Function()', 'recommendation' => 'Avoid constructing functions from strings.'],
        ['pattern' => '/\b(?:chmod|chown)\s+777\b/i', 'severity' => 'medium', 'title' => 'World-writable permissions', 'recommendation' => 'Use restrictive file permissions (644/755).'],
    ];


    /**
     * Scan a project's source code for security issues.
     *
     * @return array{violations: list<array{file: string, line: int, severity: string, title: string, recommendation: string}>, files_scanned: int, scanned_at: string}
     */
    public function scan(LabProject $project, string $sourceRoot): array
    {
        if (! is_dir($sourceRoot)) {
            throw new RuntimeException("Source directory not found: {$sourceRoot}");
        }

        $violations = [];
        $filesScanned = 0;

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveCallbackFilterIterator(
                new \RecursiveDirectoryIterator($sourceRoot, \FilesystemIterator::SKIP_DOTS),
                function (\SplFileInfo $current) {
                    if ($current->isLink()) {
                        return false;
                    }
                    if ($current->isDir()) {
                        return ! in_array($current->getFilename(), self::SKIP_DIRS, true);
                    }
                    return $current->isFile() && $current->getSize() <= self::MAX_FILE_BYTES;
                },
            ),
        );

        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            $extension = strtolower($file->getExtension());
            if (! in_array($extension, self::SCANNABLE_EXTENSIONS, true)) {
                continue;
            }

            $filesScanned++;
            $relativePath = str_replace('\\', '/', $file->getPathname());
            $relativePath = substr($relativePath, strlen(str_replace('\\', '/', $sourceRoot)) + 1);

            $content = @file_get_contents($file->getPathname());
            if ($content === false) {
                continue;
            }

            $lines = explode("\n", $content);
            foreach (self::RULES as $rule) {
                foreach ($lines as $lineNum => $line) {
                    if (preg_match($rule['pattern'], $line)) {
                        $violations[] = [
                            'file' => $relativePath,
                            'line' => $lineNum + 1,
                            'severity' => $rule['severity'],
                            'title' => $rule['title'],
                            'recommendation' => $rule['recommendation'],
                        ];
                    }
                }
            }
        }

        $severityOrder = ['critical' => 0, 'high' => 1, 'medium' => 2, 'low' => 3];
        usort($violations, fn ($a, $b) => ($severityOrder[$a['severity']] ?? 9) <=> ($severityOrder[$b['severity']] ?? 9));

        return [
            'violations' => $violations,
            'files_scanned' => $filesScanned,
            'scanned_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Zip the project source for upload to an external scanner.
     * Adapted from Reflex's _zip_app_source() in scan.py.
     */
    public function zipSource(string $sourceRoot): string
    {
        if (! is_dir($sourceRoot)) {
            throw new RuntimeException("Source directory not found: {$sourceRoot}");
        }

        $tmpDir = storage_path('app/tmp');
        File::ensureDirectoryExists($tmpDir);
        $zipPath = $tmpDir.'/scan-'.bin2hex(random_bytes(8)).'.zip';

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Could not create scan archive.');
        }

        $fileCount = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveCallbackFilterIterator(
                new \RecursiveDirectoryIterator($sourceRoot, \FilesystemIterator::SKIP_DOTS),
                function (\SplFileInfo $current) {
                    if ($current->isLink()) {
                        return false;
                    }
                    if ($current->isDir()) {
                        return ! in_array($current->getFilename(), self::SKIP_DIRS, true);
                    }
                    return $current->isFile() && $current->getSize() <= self::MAX_FILE_BYTES;
                },
            ),
        );

        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            $relative = str_replace('\\', '/', $file->getPathname());
            $relative = substr($relative, strlen(str_replace('\\', '/', $sourceRoot)) + 1);
            $zip->addFile($file->getPathname(), $relative);
            $fileCount++;
        }

        $zip->close();

        if ($fileCount === 0) {
            File::delete($zipPath);
            throw new RuntimeException('No reviewable source files found.');
        }

        $size = filesize($zipPath);
        if ($size !== false && $size > self::MAX_ZIP_BYTES) {
            File::delete($zipPath);
            throw new RuntimeException('Project source is too large to scan (over 50 MB).');
        }

        return $zipPath;
    }
}
