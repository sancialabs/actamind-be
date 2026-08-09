<?php

namespace App\Models;

use App\Enum\Currency;
use App\Models\Concerns\HasUuid;
use Database\Factories\PlanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'slug', 'description', 'currency', 'price_cents', 'is_active', 'sort_order', 'limits'])]
class Plan extends Model
{
    /** @use HasFactory<PlanFactory> */
    use HasFactory, HasUuid, SoftDeletes;

    protected function casts(): array
    {
        return [
            'currency' => Currency::class,
            'price_cents' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'limits' => 'array',
        ];
    }
}
