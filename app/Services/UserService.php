<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function registerUser($data)
    {
        return User::query()->create($data);
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
}
