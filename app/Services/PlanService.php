<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Models\Plan;

class PlanService
{
    public function createPlan($data)
    {
        $plan = Plan::query()->create($data);

        return $plan;
    }
}
