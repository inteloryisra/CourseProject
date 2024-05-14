<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    public UserService $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }


    public function register(Request $request)
    {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'password' => 'required|string|max:255',
            ]);

           return $this->userService->registerUser($validatedData);
    }
}
