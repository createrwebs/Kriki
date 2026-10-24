<?php

namespace App\Datastore;

use App\Models\User;

/** Customer SQL is never an approval mechanism, even with an AI-supplied ack. */
final class DatastoreRevisor
{
    public function revise(string $sql, bool $acknowledgeDestructive = false, string $kind = DatastoreKind::SUPABASE, ?User $user = null): array
    {
        return [
            'ok' => false, 'kind' => $kind, 'applied' => 0, 'destructive' => false,
            'needs_ack' => false, 'code' => 'schema_approval_required', 'statements' => [],
            'message' => 'Raw SQL changes are disabled. Review a project-scoped schema proposal and use the owner Apply action.',
        ];
    }
}
