<?php

namespace App\Hosting\Contracts;

use App\Models\LabProject;

/**
 * Abstraction over hosting backends (local, cloud, GCP).
 *
 * Inspired by Reflex's provider pattern: reflex_cli/v2/providers.py
 * Each provider implements the same lifecycle so the DeploymentManager
 * can orchestrate without knowing the backend.
 */
interface HostingProvider
{
    /**
     * Unique provider key (e.g. 'local', 'cloud', 'gcp').
     */
    public function key(): string;

    /**
     * Human-readable display name.
     */
    public function displayName(): string;

    /**
     * Deploy a built artifact to this provider.
     *
     * @param  LabProject  $project  The project being deployed.
     * @param  string  $artifactPath  Absolute path to the zip archive.
     * @param  array<string, mixed>  $options  Provider-specific options (region, vmtype, etc.)
     * @return array{deployment_id: string, status: string, url?: string}
     */
    public function deploy(LabProject $project, string $artifactPath, array $options = []): array;

    /**
     * Query the current status of a deployment.
     *
     * @return array{status: string, progress?: int, message?: string, url?: string}
     */
    public function status(string $deploymentId): array;

    /**
     * Fetch recent logs for a deployment or running app.
     *
     * @return list<array{timestamp: string, level: string, message: string}>
     */
    public function logs(string $deploymentId, int $limit = 100): array;

    /**
     * Stop a running deployment.
     */
    public function stop(string $deploymentId): bool;

    /**
     * Start a stopped deployment.
     */
    public function start(string $deploymentId): bool;

    /**
     * Rollback to a previous deployment version.
     */
    public function rollback(string $deploymentId, string $targetVersion): bool;

    /**
     * Available regions for this provider.
     *
     * @return list<array{id: string, name: string, country?: string}>
     */
    public function regions(): array;

    /**
     * Available VM/compute types.
     *
     * @return list<array{id: string, name: string, cpu: int, ram_gb: float}>
     */
    public function vmTypes(): array;

    /**
     * Whether this provider is available for the given project/user.
     */
    public function available(LabProject $project): bool;
}
