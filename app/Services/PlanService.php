<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Models\Plan;

class PlanService
{
    public function createPlan($data)
    {
        return Plan::query()->create($data);

    }

    public function deletePlan($planId)
    {
       return Plan::destroy($planId);
    }
}
