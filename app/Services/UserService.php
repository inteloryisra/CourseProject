<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserService
{

    public function registerUser($data)
    {
        $user = User::query()->create($data);

        return $user;
    }



    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserById($userId)
    {
        return User::findOrFail($userId);
    }

    public function editUser($userId, $userData)
    {
        $user = User::findOrFail($userId);
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
        $user = User::where('email', $data['email'])->first();

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

}
