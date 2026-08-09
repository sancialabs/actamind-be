<?php

use App\Models\Plan;
use Illuminate\Support\Str;

function validPlanPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Pro',
        'slug' => 'pro',
        'description' => 'For power users.',
        'currency' => 'USD',
        'price_cents' => 999,
        'is_active' => true,
        'sort_order' => 1,
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
    ], $overrides);
}

test('index returns a paginated list of plans', function () {
    Plan::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/plans');

    $response->assertOk();
    expect($response->json('data.data'))->toHaveCount(3);
});

test('store creates a plan with a generated uuid', function () {
    $response = $this->postJson('/api/v1/plans', validPlanPayload());

    $response->assertCreated();
    expect($response->json('data.uuid'))->not->toBeNull();
    $this->assertDatabaseHas('plans', ['slug' => 'pro']);
});

test('store rejects a duplicate slug', function () {
    Plan::factory()->create(['slug' => 'pro']);

    $response = $this->postJson('/api/v1/plans', validPlanPayload());

    $response->assertStatus(422);
});

test('uuid cannot be set via mass assignment on store', function () {
    $spoofedUuid = '11111111-1111-1111-1111-111111111111';

    $response = $this->postJson('/api/v1/plans', validPlanPayload(['uuid' => $spoofedUuid]));

    $response->assertCreated();
    expect($response->json('data.uuid'))->not->toBe($spoofedUuid);
});

test('show returns a plan by uuid', function () {
    $plan = Plan::factory()->create();

    $response = $this->getJson("/api/v1/plans/{$plan->uuid}");

    $response->assertOk();
    expect($response->json('data.uuid'))->toBe($plan->uuid);
});

test('show returns 404 for the numeric id', function () {
    $plan = Plan::factory()->create();

    $response = $this->getJson("/api/v1/plans/{$plan->id}");

    $response->assertNotFound();
});

test('show returns 404 for a random uuid', function () {
    $response = $this->getJson('/api/v1/plans/'.Str::uuid());

    $response->assertNotFound();
});

test('show returns 404 for a non-uuid path segment', function () {
    $this->getJson('/api/v1/plans/not-a-uuid')->assertNotFound();
});

test('update returns 404 for a non-uuid path segment', function () {
    $this->patchJson('/api/v1/plans/not-a-uuid', ['name' => 'Updated'])->assertNotFound();
});

test('destroy returns 404 for a non-uuid path segment', function () {
    $this->deleteJson('/api/v1/plans/not-a-uuid')->assertNotFound();
});

test('update applies a partial change', function () {
    $plan = Plan::factory()->create(['name' => 'Original']);

    $response = $this->patchJson("/api/v1/plans/{$plan->uuid}", ['name' => 'Updated']);

    $response->assertOk();
    expect($response->json('data.name'))->toBe('Updated');
    $this->assertDatabaseHas('plans', ['id' => $plan->id, 'name' => 'Updated']);
});

test('update applies a full replacement', function () {
    $plan = Plan::factory()->create(['slug' => 'free']);

    $response = $this->putJson("/api/v1/plans/{$plan->uuid}", validPlanPayload(['slug' => 'free']));

    $response->assertOk();
    expect($response->json('data.name'))->toBe('Pro');
});

test('destroy removes the plan', function () {
    $plan = Plan::factory()->create();

    $this->deleteJson("/api/v1/plans/{$plan->uuid}")->assertOk();

    $this->getJson("/api/v1/plans/{$plan->uuid}")->assertNotFound();
    $this->assertSoftDeleted('plans', ['id' => $plan->id]);
});

test('update shifts other plans to keep sort_order sequential', function () {
    $plans = collect(range(1, 4))->map(
        fn (int $position) => Plan::factory()->create(['sort_order' => $position])
    );

    $moved = $plans->firstWhere('sort_order', 4);

    $this->patchJson("/api/v1/plans/{$moved->uuid}", ['sort_order' => 2])->assertOk();

    $ordered = Plan::query()->orderBy('sort_order')->get(['id', 'sort_order']);

    expect($ordered->pluck('sort_order')->all())->toBe([1, 2, 3, 4]);
    expect($ordered->firstWhere('sort_order', 2)->id)->toBe($moved->id);
});

test('update clamps an out-of-range sort_order to the last position', function () {
    $plans = collect(range(1, 3))->map(
        fn (int $position) => Plan::factory()->create(['sort_order' => $position])
    );

    $first = $plans->firstWhere('sort_order', 1);

    $response = $this->patchJson("/api/v1/plans/{$first->uuid}", ['sort_order' => 99]);

    $response->assertOk();
    expect($response->json('data.sort_order'))->toBe(3);

    $ordered = Plan::query()->orderBy('sort_order')->pluck('sort_order');
    expect($ordered->all())->toBe([1, 2, 3]);
});
