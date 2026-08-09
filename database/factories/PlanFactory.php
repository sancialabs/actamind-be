<?php

namespace Database\Factories;

use App\Enum\Currency;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    protected $model = Plan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->unique()->word()),
            'slug' => fake()->unique()->slug(2),
            'description' => fake()->sentence(),
            'currency' => Currency::Dollar,
            'price_cents' => fake()->numberBetween(0, 5000),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 10),
            'limits' => [
                'journal_entries' => null,
                'notes' => null,
                'attachments_mb' => 500,
                'reminders' => 10,
                'pomodoro' => null,
                'kanban_boards' => 3,
                'kanban_tasks' => 100,
                'publishing_platform' => false,
            ],
        ];
    }
}
