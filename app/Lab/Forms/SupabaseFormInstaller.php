<?php

namespace App\Lab\Forms;

use App\Datastore\DatastoreHttp;

final class SupabaseFormInstaller
{
    public function install(array $plane): void
    {
        abort_unless($plane['can_revise'] && $plane['console_token'] !== '', 503, 'Reconnect Supabase with database write permission.');
        try {
            $response = DatastoreHttp::make(30)->withToken($plane['console_token'])
                ->post(rtrim(config('services.supabase.api_url', 'https://api.supabase.com'), '/').'/v1/projects/'.$plane['project_ref'].'/database/query', [
                    'query' => file_get_contents(database_path('sql/lab-forms/0001_initial.sql')),
                ]);
        } catch (\Throwable) {
            abort(503, 'Could not reach Supabase to prepare form storage.');
        }
        abort_unless($response->successful(), 503, 'Could not prepare form storage. Check database permissions and existing table compatibility.');
    }
}
