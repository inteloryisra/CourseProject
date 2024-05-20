<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;


class UserController extends Controller
{

    public UserService $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }


    public function register(Request $request)
    {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'password' => 'required|string|max:255',
            ]);

            return $this->userService->registerUser($data);
    }

    public function getAllUsers()
    {
        return $this->userService->getAllUsers();
    }

    public function getUserById($userId)
    {
        return $this->userService->getUserById($userId);
    }

    public function editUser(Request $request, $userId)
    {
        $data = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email',
            'password' => 'string|max:255',
        ]);

        return $this->userService->editUser($userId, $data);
    }

    public function loginUser(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = $this->userService->loginUser($data);


        return response()->json(['user' => $user]);
    }

    public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Logged out successfully']);
}

public function changePassword(Request $request, $userId)
{
    $data = $request->validate([
        'oldPassword' => 'required|string|max:255',
        'newPassword' => 'required|string|max:255',
    ]);

    return $this->userService->changePassword($userId, $data['oldPassword'], $data['newPassword']);
}

public function returnTockenUser()
{
    return $this->userService->returnTockenUser();
}



}
