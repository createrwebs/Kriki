<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabChangeProposal extends Model
{
    protected $guarded = ['id'];
    protected function casts(): array { return ['payload' => 'array', 'receipt' => 'array', 'base_version' => 'integer', 'expires_at' => 'immutable_datetime', 'applied_at' => 'immutable_datetime']; }
    protected static function booted(): void
    {
        static::updating(function (self $proposal) {
            if ($proposal->isDirty(['uuid', 'user_id', 'lab_project_id', 'lab_module_installation_id', 'kind', 'base_version', 'payload', 'impact_hash', 'expires_at'])) {
                throw new \LogicException('Schema proposal approval material is immutable.');
            }
        });
    }
    public function getRouteKeyName(): string { return 'uuid'; }
}
