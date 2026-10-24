<?php

namespace App\Hosting\Providers;

use App\Hosting\Contracts\HostingProvider;
use App\Lab\Publish\LabPublishArtifactDeployer;
use App\Lab\Publish\LabPublishConfig;
use App\Lab\Runtime\RuntimePolicy;
use App\Models\LabProject;
use App\Models\LabPublication;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Self-hosted provider: extracts the build artifact to local storage
 * and serves it via the existing subdomain/custom-domain routing.
 *
 * Static files only; this does not provision servers or run backend code.
 */
final class LocalProvider implements HostingProvider
{
    public function __construct(
        private readonly LabPublishArtifactDeployer $deployer,
        private readonly LabPublishConfig $config,
    ) {}

    public function key(): string
    {
        return 'local';
    }

    public function displayName(): string
    {
        return 'Krikkit static hosting';
    }

    public function deploy(LabProject $project, string $artifactPath, array $options = []): array
    {
        if (! $this->available($project) || $project->isFrozen()) {
            throw new RuntimeException('Static publishing is unavailable for this project.');
        }
        if (($options['strategy'] ?? 'immediate') !== 'immediate'
            || ! empty($options['env']) || ! empty($options['hostname'])
            || isset($options['min_instances']) || isset($options['max_instances'])
            || ! in_array($options['region'] ?? 'local', ['local', null], true)
            || ! in_array($options['vmtype'] ?? 'shared', ['shared', null], true)) {
            throw new RuntimeException('Local hosting supports immediate static replacement only, without runtime secrets or compute options.');
        }
        $publication = LabPublication::query()
            ->where('lab_project_id', $project->id)
            ->first();

        if ($publication === null) {
            throw new RuntimeException('No publication record found. Publish the project first.');
        }
        if ($publication->kind === LabPublication::KIND_CUSTOM && ! $publication->customHostVerified()) {
            throw new RuntimeException('Verify the custom domain before publishing.');
        }
        if ($publication->status !== LabPublication::STATUS_BUILDING) {
            throw new RuntimeException('Start a publish build before uploading an artifact.');
        }

        $destination = $publication->liveRoot();
        File::ensureDirectoryExists(dirname($destination));

        // Deploy the artifact (zip → extract → validate index.html)
        $this->deployer->deploy($artifactPath, $destination);

        $deploymentId = (string) Str::ulid();
        $url = $this->resolveUrl($publication);

        // Mark publication as live
        $publication->forceFill([
            'status' => LabPublication::STATUS_LIVE,
            'published_at' => now(),
            'last_error' => null,
        ])->save();

        return [
            'deployment_id' => $deploymentId,
            'status' => 'live',
            'url' => $url,
        ];
    }

    public function status(string $deploymentId): array
    {
        // Local deployments are synchronous — always live once deployed.
        return [
            'status' => 'live',
            'progress' => 100,
            'message' => 'Deployed to local storage.',
        ];
    }

    public function logs(string $deploymentId, int $limit = 100): array
    {
        return [
            [
                'timestamp' => now()->toIso8601String(),
                'level' => 'info',
                'message' => 'Local deployment completed successfully.',
            ],
        ];
    }

    public function stop(string $deploymentId): bool
    {
        // Local hosting doesn't support stop — the site is served from disk.
        return false;
    }

    public function start(string $deploymentId): bool
    {
        return false;
    }

    public function rollback(string $deploymentId, string $targetVersion): bool
    {
        // Rollback is handled by the DeploymentManager via versioned archives.
        return false;
    }

    public function regions(): array
    {
        return [
            ['id' => 'local', 'name' => 'This Server'],
        ];
    }

    public function vmTypes(): array
    {
        return [];
    }

    public function available(LabProject $project): bool
    {
        return $this->config->enabled() && (new RuntimePolicy)->verdict($project)['can_execute'];
    }

    private function resolveUrl(LabPublication $publication): ?string
    {
        if ($publication->kind === LabPublication::KIND_CUSTOM && filled($publication->custom_host)) {
            return $this->config->customUrl($publication->custom_host);
        }

        if (filled($publication->subdomain)) {
            return $this->config->subdomainUrl($publication->subdomain);
        }

        return null;
    }
}
