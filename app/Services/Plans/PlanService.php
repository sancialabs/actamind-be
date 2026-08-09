<?php

namespace App\Services\Plans;

use App\Models\Plan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PlanService
{
    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return Plan::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function create(array $data): Plan
    {
        return Plan::create($data);
    }

    public function findByUuid(string $uuid): Plan
    {
        return Plan::where('uuid', $uuid)->firstOrFail();
    }

    public function update(Plan $plan, array $data): Plan
    {
        if (array_key_exists('sort_order', $data)) {
            $this->reorder($plan, (int) $data['sort_order']);
            unset($data['sort_order']);
        }

        $plan->update($data);

        return $plan->refresh();
    }

    public function delete(Plan $plan): void
    {
        $plan->delete();
    }

    protected function reorder(Plan $plan, int $newSortOrder): void
    {
        DB::transaction(function () use ($plan, $newSortOrder) {
            $total = Plan::count();
            $newSortOrder = max(1, min($newSortOrder, $total));
            $oldSortOrder = $plan->sort_order;

            if ($newSortOrder === $oldSortOrder) {
                return;
            }

            if ($newSortOrder < $oldSortOrder) {
                Plan::whereBetween('sort_order', [$newSortOrder, $oldSortOrder - 1])
                    ->where('id', '!=', $plan->id)
                    ->increment('sort_order');
            } else {
                Plan::whereBetween('sort_order', [$oldSortOrder + 1, $newSortOrder])
                    ->where('id', '!=', $plan->id)
                    ->decrement('sort_order');
            }

            $plan->update(['sort_order' => $newSortOrder]);
        });
    }
}
