<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabForm extends Model
{
    protected $fillable = ['uuid', 'user_id', 'lab_project_id', 'supabase_project_ref', 'template', 'title', 'status', 'active_version'];

    public function getRouteKeyName(): string { return 'uuid'; }
    protected function casts(): array { return ['active_version' => 'integer']; }
    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
    public function project(): BelongsTo { return $this->belongsTo(LabProject::class, 'lab_project_id'); }
    public function versions(): HasMany { return $this->hasMany(LabFormVersion::class)->orderBy('version'); }
}
