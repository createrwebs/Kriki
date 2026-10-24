<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabModuleInstallation extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['request_hash', 'idempotency_key'];
    protected function casts(): array { return ['revision' => 'integer', 'schema_version' => 'integer', 'enabled' => 'boolean', 'config' => 'array']; }
    public function getRouteKeyName(): string { return 'uuid'; }
    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
    public function project(): BelongsTo { return $this->belongsTo(LabProject::class, 'lab_project_id'); }
}
