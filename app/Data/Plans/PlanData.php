<?php

namespace App\Data\Plans;

use App\Enum\Currency;
use App\Models\Plan;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class PlanData extends Data
{
    public function __construct(
        public string $uuid,
        public string $name,
        public string $slug,
        public string $description,
        public Currency $currency,
        public int $priceCents,
        public bool $isActive,
        public int $sortOrder,
        public PlanLimitsData $limits,
        public ?string $createdAt,
        public ?string $updatedAt,
    ) {}

    public static function fromModel(Plan $plan): self
    {
        return new self(
            uuid: $plan->uuid,
            name: $plan->name,
            slug: $plan->slug,
            description: $plan->description,
            currency: $plan->currency,
            priceCents: $plan->price_cents,
            isActive: $plan->is_active,
            sortOrder: $plan->sort_order,
            limits: PlanLimitsData::from($plan->limits),
            createdAt: $plan->created_at?->toIso8601String(),
            updatedAt: $plan->updated_at?->toIso8601String(),
        );
    }
}
