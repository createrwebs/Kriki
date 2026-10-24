<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['company_name', 'business_description', 'website_budget_amount', 'website_budget_currency'])]
#[Hidden(['website_budget_amount', 'website_budget_currency'])]
class UserBusinessProfile extends Model
{
    protected function casts(): array
    {
        return [
            'website_budget_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
