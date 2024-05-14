<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function registerUser($data)
    {
        return User::query()->create($data);
    }
}
