<?php

namespace App\Lab\Modules;

use App\Datastore\DatastoreHttp;
use App\Models\LabModuleInstallation;
use App\Models\LabProject;

final class SupabaseModuleInstaller
{
    public function __construct(private readonly ModuleInstallations $modules) {}

    public function install(LabProject $project, LabModuleInstallation $module): array
    {
        $plane = $this->modules->plane($module);
        abort_unless(($plane['can_revise'] ?? false) && filled($plane['console_token'] ?? null), 503, 'Reconnect Supabase with database write permission.');
        $sql = ModuleSchema::sql($module->key);
        try {
            $response = DatastoreHttp::make(30)->withToken($plane['console_token'])->acceptJson()
                ->post(rtrim(config('services.supabase.api_url', 'https://api.supabase.com'), '/').'/v1/projects/'.rawurlencode($module->supabase_project_ref).'/database/query', ['query' => $sql, 'read_only' => false]);
        } catch (\Throwable) { abort(503, 'Module schema storage is temporarily unavailable.'); }
        $marker = $response->json();
        abort_unless($response->successful() && is_array($marker) && count($marker) === 1
            && ($marker[0]['krikkit_schema_key'] ?? null) === $module->key
            && ($marker[0]['schema_version'] ?? null) === ModuleCatalog::get($module->key)['schema_version']
            && ($marker[0]['verified'] ?? null) === true, 503, 'Module schema could not be verified. Check permissions and schema compatibility.');
        try {
            $response = DatastoreHttp::make()->withHeaders(['apikey' => $plane['steward_token'], 'Authorization' => 'Bearer '.$plane['steward_token']])->acceptJson()
                ->post(rtrim($plane['host_url'], '/').'/rest/v1/rpc/krikkit_register_'.$module->key, ['p_project_uuid' => $project->uuid, 'p_module_uuid' => $module->uuid]);
        } catch (\Throwable) { abort(503, 'Module registration is temporarily unavailable. Retry this approved operation.'); }
        $binding = $response->json();
        abort_unless($response->successful() && is_array($binding) && count($binding) === 1
            && ($binding[0]['project_uuid'] ?? null) === $project->uuid && ($binding[0]['module_uuid'] ?? null) === $module->uuid
            && ($binding[0]['schema_version'] ?? null) === ModuleCatalog::get($module->key)['schema_version'], 503, 'Module registration could not be verified. Retry this approved operation.');
        if ($module->key === 'manual_payments') app(PrivateSlipStorage::class)->verifyBucket($module);
        return ['project_uuid' => $project->uuid, 'module_uuid' => $module->uuid, 'schema_version' => $binding[0]['schema_version'], 'verified' => true];
    }
}
