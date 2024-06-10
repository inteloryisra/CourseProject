<?php

namespace App\Http\Controllers;

use App\Enums\UserRoles;
use Illuminate\Http\Request;
use App\Services\PlanService;
use Illuminate\Support\Facades\Auth;



class PlanController extends Controller
{
    public PlanService $planService;
    public function __construct(PlanService $planService)
    {
        $this->planService = $planService;
    }

    public function createPlan(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== UserRoles::ADMIN) {
            return response()->json(['error' => 'Only admins can add plans.'], 403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $plan = $this->planService->createPlan($data);

        return response()->json(['message' => 'Plan created successfully', 'plan' => $plan], 201);
    }

    public function deletePlan($planId)
    {
        $this->planService->deletePlan($planId);
    }
}
