<?php

namespace App\Http\Controllers;

use App\Data\Plans\PlanData;
use App\Http\Requests\Plans\StorePlanRequest;
use App\Http\Requests\Plans\UpdatePlanRequest;
use App\Models\Plan;
use App\Services\Plans\PlanService;
use Spatie\LaravelData\PaginatedDataCollection;

class PlansController extends Controller
{
    public function __construct(private readonly PlanService $planService) {}

    public function index()
    {
        $plans = $this->planService->list();

        return $this->success(PlanData::collect($plans, PaginatedDataCollection::class));
    }

    public function store(StorePlanRequest $request)
    {
        $plan = $this->planService->create($request->validated());

        return $this->success(PlanData::fromModel($plan), 'Plan created successfully.', 201);
    }

    public function show(Plan $plan)
    {
        return $this->success(PlanData::fromModel($plan));
    }

    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $plan = $this->planService->update($plan, $request->validated());

        return $this->success(PlanData::fromModel($plan), 'Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        $this->planService->delete($plan);

        return $this->success(null, 'Plan deleted successfully.');
    }
}
