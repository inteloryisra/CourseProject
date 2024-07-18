<?php


namespace App\Services;

use App\Models\ExtraAttempt;
use App\Models\Purchase;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Support\Facades\Auth;

class PurchaseService
{
    public function createPurchase($data)
    {
        $user = Auth::user();

        $user_plan = UserPlan::query()->where('user_id',$user->id)->first();

        $attempt = ExtraAttempt::findOrFail($data['extra_attempts_id']);

        $extraAttempts = $attempt->extra_attempts;
        $newMaxAttempts = $user_plan->max_quiz_attempts + $extraAttempts;

        $user_plan->max_quiz_attempts = $newMaxAttempts;
        $user_plan->save();

        $purchase = new Purchase([
            'user_id' => $user->id,
            'extra_attempts_id' => $attempt->id,
        ]);

        $purchase->save();

        return $purchase;
    }


}
