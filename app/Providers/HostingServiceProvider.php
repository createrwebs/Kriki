<?php

namespace App\Providers;

use App\Hosting\DeploymentManager;
use App\Hosting\Providers\LocalProvider;
use App\Hosting\SecretManager;
use App\Hosting\SecurityScanner;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the hosting/deployment infrastructure.
 *
 * Adapted from Reflex's hosting_cli pattern (deployments.py):
 * a central registry that wires providers into the deployment manager.
 */
class HostingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SecretManager::class);
        $this->app->singleton(SecurityScanner::class);

        $this->app->singleton(DeploymentManager::class, function ($app) {
            $manager = new DeploymentManager;

            // Register the default local provider
            $manager->register($app->make(LocalProvider::class));

            // Future providers (cloud, gcp) register here:
            // $manager->register($app->make(CloudProvider::class));
            // $manager->register($app->make(GcpProvider::class));

            return $manager;
        });
    }

    public function boot(): void
    {
        //
    }
}
