<?php

namespace Database\Seeders;

use App\Enum\Currency;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Seed the Plans table.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Get started with core productivity tools at no cost.',
                'currency' => Currency::Dollar,
                'price_cents' => 0,
                'is_active' => true,
                'sort_order' => 1,
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
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'For power users who need more room and Publishing Platform access.',
                'currency' => Currency::Dollar,
                // TODO: confirm actual price with product before launch
                'price_cents' => 999,
                'is_active' => true,
                'sort_order' => 2,
                'limits' => [
                    'journal_entries' => null,
                    'notes' => null,
                    'attachments_mb' => 5120,
                    'reminders' => null,
                    'pomodoro' => null,
                    'kanban_boards' => 10,
                    'kanban_tasks' => null,
                    'publishing_platform' => true,
                ],
            ],
            [
                'name' => 'MindForge',
                'slug' => 'mindforge',
                'description' => 'The full Actamind experience with no limits and maximum storage.',
                'currency' => Currency::Dollar,
                // TODO: confirm actual price with product before launch
                'price_cents' => 2999,
                'is_active' => true,
                'sort_order' => 3,
                'limits' => [
                    'journal_entries' => null,
                    'notes' => null,
                    'attachments_mb' => 25600,
                    'reminders' => null,
                    'pomodoro' => null,
                    'kanban_boards' => null,
                    'kanban_tasks' => null,
                    'publishing_platform' => true,
                ],
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
