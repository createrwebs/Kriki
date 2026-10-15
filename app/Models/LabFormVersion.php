<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabFormVersion extends Model
{
    protected $fillable = ['lab_form_id', 'version', 'definition'];
    protected function casts(): array { return ['version' => 'integer', 'definition' => 'array']; }

    protected static function booted(): void
    {
        static::updating(static function (): void { throw new \LogicException('Form versions are immutable.'); });
        static::deleting(static function (): void { throw new \LogicException('Form versions must be retained.'); });
    }
}
