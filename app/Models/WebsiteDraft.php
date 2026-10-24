<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['blueprint'])]
#[Hidden(['id', 'user_id', 'client_request_id', 'creation_hash', 'created_at'])]
class WebsiteDraft extends Model
{
    protected function casts(): array
    {
        return ['blueprint' => 'array', 'revision' => 'integer'];
    }
}
