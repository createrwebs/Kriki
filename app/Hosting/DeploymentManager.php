<?php

namespace App\Hosting;

use App\Hosting\Contracts\HostingProvider;
use App\Lab\LabProjectAccess;
use App\Models\Deployment;
use App\Models\LabProject;
use App\Models\User;
use RuntimeException;

/**
 * Orchestrates the full deployment lifecycle.
 *
 * Adapted from Reflex's cli.py deploy() flow:
 *   1. Resolve app/project → 2. Compile/export → 3. Upload artifact
 *   4. Create deployment record → 5. Dispatch to provider → 6. Watch status
 */
final class DeploymentManager
{
    /** @var array<string, HostingProvider> */
    private array $providers = [];

    public function register(HostingProvider $provider): void
    {
        $this->providers[$provider->key()] = $provider;
    }

    public function provider(string $key): HostingProvider
    {
        if (! isset($this->providers[$key])) {
            throw new RuntimeException("Unknown hosting provider: {$key}");
        }

        return $this->providers[$key];
    }

    /** @return list<HostingProvider> */
    public function providers(): array
    {
        return array_values($this->providers);
    }

    /** @return list<HostingProvider> */
    public function availableProviders(LabProject $project): array
    {
        return array_values(array_filter(
            $this->providers,
            fn (HostingProvider $p) => $p->available($project),
        ));
    }

    /**
     * Deploy a built artifact for a project.
     *
     * @param  array{provider?: string, region?: string, vmtype?: string, strategy?: string, description?: string, env?: array<string, string>, min_instances?: int, max_instances?: int, hostname?: string}  $options
     */
    public function deploy(LabProject $project, User $user, string $artifactPath, array $options = []): Deployment
    {
        LabProjectAccess::assertWritable($project, $user);
        $providerKey = $options['provider'] ?? 'local';
        $provider = $this->provider($providerKey);

        if (! $provider->available($project)) {
            throw new RuntimeException("Provider '{$providerKey}' is not available.");
        }

        $strategy = $options['strategy'] ?? Deployment::STRATEGY_IMMEDIATE;
        if (! in_array($strategy, Deployment::STRATEGIES, true)) {
            throw new RuntimeException("Invalid deployment strategy: {$strategy}");
        }

        $deployment = Deployment::query()->create([
            'lab_project_id' => $project->id,
            'user_id' => $user->id,
            'provider' => $providerKey,
            'version' => $this->nextVersion($project),
            'strategy' => $strategy,
            'region' => $options['region'] ?? null,
            'vmtype' => $options['vmtype'] ?? null,
            'hostname' => $options['hostname'] ?? null,
            'min_instances' => $options['min_instances'] ?? null,
            'max_instances' => $options['max_instances'] ?? null,
            'description' => $options['description'] ?? null,
            'status' => Deployment::STATUS_PENDING,
            // Uploads are temporary; local hosting has no retained rollback archive.
            'artifact_path' => null,
            'artifact_size' => filesize($artifactPath) ?: 0,
        ]);

        try {
            $deployment->update(['status' => Deployment::STATUS_BUILDING]);

            $result = $provider->deploy($project, $artifactPath, array_merge($options, [
                'deployment_id' => $deployment->id,
            ]));

            $deployment->update([
                'status' => Deployment::STATUS_LIVE,
                'provider_deployment_id' => $result['deployment_id'] ?? null,
                'url' => $result['url'] ?? null,
                'deployed_at' => now(),
            ]);

            Deployment::query()
                ->where('lab_project_id', $project->id)
                ->where('id', '!=', $deployment->id)
                ->where('status', Deployment::STATUS_LIVE)
                ->update(['status' => Deployment::STATUS_SUPERSEDED]);

            $this->log($deployment, 'info', 'Deployment completed successfully.');

            return $deployment;
        } catch (\Throwable $e) {
            $deployment->update([
                'status' => Deployment::STATUS_FAILED,
                'last_error' => 'Deployment failed.',
                'failed_at' => now(),
            ]);

            $this->log($deployment, 'error', 'Deployment failed.');

            throw $e;
        }
    }

    /**
     * Rollback to a previous deployment version.
     * Adapted from Reflex's apps.py rollback command.
     */
    public function rollback(Deployment $deployment, User $user): Deployment
    {
        if (! $deployment->canRollback()) {
            throw new RuntimeException('This provider does not support rollback.');
        }
        $project = $deployment->project;
        if ($project === null) {
            throw new RuntimeException('Deployment has no associated project.');
        }
        LabProjectAccess::assertWritable($project, $user);

        $target = Deployment::query()
            ->where('lab_project_id', $project->id)
            ->where('id', '!=', $deployment->id)
            ->whereIn('status', [Deployment::STATUS_LIVE, Deployment::STATUS_SUPERSEDED])
            ->orderByDesc('deployed_at')
            ->first();

        if ($target === null) {
            throw new RuntimeException('No previous deployment to rollback to.');
        }

        $provider = $this->provider($deployment->provider);

        $rollback = Deployment::query()->create([
            'lab_project_id' => $project->id,
            'user_id' => $user->id,
            'provider' => $deployment->provider,
            'version' => $this->nextVersion($project),
            'strategy' => Deployment::STRATEGY_IMMEDIATE,
            'region' => $target->region,
            'vmtype' => $target->vmtype,
            'hostname' => $target->hostname,
            'description' => "Rollback to v{$target->version}",
            'status' => Deployment::STATUS_PENDING,
            'rollback_from_deployment_id' => $deployment->id,
            'rollback_to_deployment_id' => $target->id,
        ]);

        try {
            $rollback->update(['status' => Deployment::STATUS_BUILDING]);

            $success = $provider->rollback(
                $deployment->provider_deployment_id ?? (string) $deployment->id,
                (string) $target->version,
            );

            if ($success) {
                $rollback->update([
                    'status' => Deployment::STATUS_LIVE,
                    'url' => $target->url,
                    'deployed_at' => now(),
                ]);
                $deployment->update(['status' => Deployment::STATUS_SUPERSEDED]);
                $this->log($rollback, 'info', "Rolled back to v{$target->version}.");
            } else {
                throw new RuntimeException('Provider rejected the rollback.');
            }

            return $rollback;
        } catch (\Throwable $e) {
            $rollback->update([
                'status' => Deployment::STATUS_FAILED,
                'last_error' => 'Rollback failed.',
                'failed_at' => now(),
            ]);
            $this->log($rollback, 'error', 'Rollback failed.');

            throw $e;
        }
    }

    /**
     * Get deployment history for a project.
     * Adapted from Reflex's apps.py history command.
     */
    public function history(LabProject $project, int $perPage = 20)
    {
        return Deployment::query()
            ->where('lab_project_id', $project->id)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    /** Get the current live deployment for a project. */
    public function current(LabProject $project): ?Deployment
    {
        return Deployment::query()
            ->where('lab_project_id', $project->id)
            ->where('status', Deployment::STATUS_LIVE)
            ->orderByDesc('deployed_at')
            ->first();
    }

    /**
     * Fetch logs for a deployment.
     * @return list<array{timestamp: string, level: string, message: string}>
     */
    public function logs(Deployment $deployment, int $limit = 100): array
    {
        $provider = $this->provider($deployment->provider);

        return $provider->logs(
            $deployment->provider_deployment_id ?? (string) $deployment->id,
            $limit,
        );
    }

    /** Stop a running deployment. */
    public function stop(Deployment $deployment): bool
    {
        $provider = $this->provider($deployment->provider);
        $success = $provider->stop($deployment->provider_deployment_id ?? (string) $deployment->id);

        if ($success) {
            $deployment->update(['status' => Deployment::STATUS_STOPPED]);
            $this->log($deployment, 'warn', 'Deployment stopped.');
        }

        return $success;
    }

    /** Start a stopped deployment. */
    public function start(Deployment $deployment): bool
    {
        $provider = $this->provider($deployment->provider);
        $success = $provider->start($deployment->provider_deployment_id ?? (string) $deployment->id);

        if ($success) {
            $deployment->update(['status' => Deployment::STATUS_LIVE]);
            $this->log($deployment, 'info', 'Deployment started.');
        }

        return $success;
    }

    /** Record a log entry for a deployment. */
    public function log(Deployment $deployment, string $level, string $message): void
    {
        $deployment->logs()->create([
            'level' => $level,
            'message' => $message,
            'timestamp' => now(),
        ]);
    }

    /** Determine the next version number for a project. */
    private function nextVersion(LabProject $project): int
    {
        $max = Deployment::query()
            ->where('lab_project_id', $project->id)
            ->max('version');

        return ($max ?? 0) + 1;
    }
}

