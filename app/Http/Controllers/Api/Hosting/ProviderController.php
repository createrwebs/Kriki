<?php

namespace App\Http\Controllers\Api\Hosting;

use App\Hosting\DeploymentManager;
use App\Http\Controllers\Controller;
use App\Lab\LabProjectAccess;
use App\Models\LabProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Provider / infrastructure metadata API.
 *
 * Adapted from Reflex's providers.py + vmtypes_regions.py:
 *   - GET /providers         → list available hosting providers
 *   - GET /providers/regions → list deployment regions
 *   - GET /providers/vmtypes → list compute sizes
 */
class ProviderController extends Controller
{
    public function __construct(
        private readonly DeploymentManager $manager,
    ) {}

    /**
     * List hosting providers available for a project.
     */
    public function index(Request $request, LabProject $project): JsonResponse
    {
        LabProjectAccess::assertOwner($project, $request->user());

        $providers = collect($this->manager->availableProviders($project))
            ->map(fn ($p) => [
                'key' => $p->key(),
                'name' => $p->displayName(),
                'regions' => $p->regions(),
                'vm_types' => $p->vmTypes(),
            ])
            ->all();

        return response()->json(['providers' => $providers]);
    }

    /**
     * List all regions across all providers.
     */
    public function regions(Request $request): JsonResponse
    {
        $regions = [];
        foreach ($this->manager->providers() as $provider) {
            foreach ($provider->regions() as $region) {
                $regions[] = array_merge($region, ['provider' => $provider->key()]);
            }
        }

        return response()->json(['regions' => $regions]);
    }

    /**
     * List all VM types across all providers.
     */
    public function vmTypes(Request $request): JsonResponse
    {
        $types = [];
        foreach ($this->manager->providers() as $provider) {
            foreach ($provider->vmTypes() as $type) {
                $types[] = array_merge($type, ['provider' => $provider->key()]);
            }
        }

        return response()->json(['vm_types' => $types]);
    }
}
