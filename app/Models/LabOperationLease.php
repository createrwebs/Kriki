<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabOperationLease extends Model
{
    protected $guarded = ['id'];
    protected function casts(): array { return ['expires_at' => 'immutable_datetime']; }
}
