<?php

namespace App\Services;

use App\Models\User;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserService
{

    public function registerUser($data)
    {

        return User::query()->create($data);

    }



    public function getAllUsers()
    {
        return User::query()->with('plans')->get();
    }

    public function getUserById($userId)
    {
        return User::query()->with('plans')->findOrFail($userId);
    }

    public function editUser($userId, $userData)
    {
        $user = User::query()->findOrFail($userId);
        $user->update($userData);
        return $user;
    }

    public function loginUser($data)
    {
        $user = User::query()->where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)){
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $token = $user->createToken('AuthToken')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    public function logoutUser()
    {
        $user=Auth::user();
        $user->currentAccessToken()->delete();
    }

    public function changePassword($data)
    {
        $user = User::query()->where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['oldPassword'], $user->password)) {
            return response()->json(['error' => 'Invalid old password'], 401);
        }

        $user->update([
            'password' => Hash::make($data['newPassword']),
        ]);

        return response()->json(['message' => 'Password updated successfully']);
    }


public function returnTockenUser()
{
    return Auth::user();
}
public function choosePlan($userId, $planId)
{
    $user = User::findOrFail($userId);
    $plan = Plan::findOrFail($planId);

    $user->plan()->associate($plan); 
    $user->save();

    return $user;
}

}
