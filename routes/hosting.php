<?php

use App\Http\Controllers\Api\Hosting\DeploymentController;
use App\Http\Controllers\Api\Hosting\ProviderController;
use App\Http\Controllers\Api\Hosting\ScanController;
use App\Http\Controllers\Api\Hosting\SecretController;
use App\Http\Controllers\Api\Hosting\TokenController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Hosting API Routes
|--------------------------------------------------------------------------
|
| Adapted from Reflex's hosting CLI command structure:
|   reflex deploy        → POST   /hosting/{project}/deploy
|   reflex cloud apps    → GET    /hosting/{project}/deployments
|   reflex cloud secrets → GET/PUT/DELETE /hosting/{project}/secrets
|   reflex cloud scan    → POST   /hosting/{project}/scan
|   reflex cloud token   → GET/POST/DELETE /hosting/tokens
|   reflex cloud regions → GET    /hosting/providers/regions
|   reflex cloud vmtypes → GET    /hosting/providers/vmtypes
|
*/

// Lab is a first-party session UI. Keep CSRF protection from the web stack.
Route::prefix('hosting')->middleware(['auth:web', 'verified', 'throttle:60,1', \App\Http\Middleware\EnsureHostingReady::class])->group(function () {

    // --- API Tokens (user-scoped, not project-scoped) ---
    Route::prefix('tokens')->group(function () {
        Route::get('/', [TokenController::class, 'index'])->name('hosting.tokens.index');
        Route::post('/', [TokenController::class, 'store'])->name('hosting.tokens.store');
        Route::delete('/{token}', [TokenController::class, 'destroy'])->name('hosting.tokens.destroy');
    });

    // --- Provider metadata (global) ---
    Route::get('providers/regions', [ProviderController::class, 'regions'])->name('hosting.providers.regions');
    Route::get('providers/vmtypes', [ProviderController::class, 'vmTypes'])->name('hosting.providers.vmtypes');

    // --- Project-scoped routes ---
    Route::prefix('{project:uuid}')->group(function () {

        // Providers available for this project
        Route::get('providers', [ProviderController::class, 'index'])->name('hosting.providers.index');

        // Deployments
        Route::post('deploy', [DeploymentController::class, 'store'])->name('hosting.deploy');
        Route::get('deployments', [DeploymentController::class, 'index'])->name('hosting.deployments.index');
        Route::get('deployments/current', [DeploymentController::class, 'current'])->name('hosting.deployments.current');
        Route::get('deployments/{deployment}', [DeploymentController::class, 'show'])->name('hosting.deployments.show');
        Route::post('deployments/{deployment}/rollback', [DeploymentController::class, 'rollback'])->name('hosting.deployments.rollback');
        Route::get('deployments/{deployment}/logs', [DeploymentController::class, 'logs'])->name('hosting.deployments.logs');
        Route::post('deployments/{deployment}/stop', [DeploymentController::class, 'stop'])->name('hosting.deployments.stop');
        Route::post('deployments/{deployment}/start', [DeploymentController::class, 'start'])->name('hosting.deployments.start');

        // Secrets
        Route::get('secrets', [SecretController::class, 'index'])->name('hosting.secrets.index');
        Route::put('secrets', [SecretController::class, 'update'])->name('hosting.secrets.update');
        Route::delete('secrets', [SecretController::class, 'destroyAll'])->name('hosting.secrets.destroyAll');
        Route::delete('secrets/{key}', [SecretController::class, 'destroy'])->name('hosting.secrets.destroy');

        // Security scan
        Route::post('scan', [ScanController::class, 'store'])->name('hosting.scan');
    });
});
