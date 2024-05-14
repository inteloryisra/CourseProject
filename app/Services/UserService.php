<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function registerUser($userData)
    {

        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => bcrypt($userData['password']),
        ]);


        if ($user) {
            return [
                'success' => true,
                'user' => $user,
                'message' => 'User registered successfully!',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to register user.',
            ];
        }
    }
}
