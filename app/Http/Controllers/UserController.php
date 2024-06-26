<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;


class UserController extends Controller
{

    public UserService $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }


    public function registerUser(Request $request)
    {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'password' => 'required|string|max:255',
                'role' => 'required|in:USER,ADMIN',
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
        $authType= $request->query("auth_type");
        if($authType === 'GOOGLE'){
            $data = $request->validate([
               'access_token' => 'required|string',
                'token_type' => 'required|string',
            ]);
        }else{
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }
        $user = $this->userService->loginUser($data, $authType);


        return response()->json(['user' => $user]);
    }

    public function logout(Request $request)
{
    $this->userService->logoutUser();

    return response()->json(['message' => 'Logged out successfully']);
}

public function changePassword(Request $request)
{
    $data = $request->validate([
        'email' => 'required|email',
        'oldPassword' => 'required|string|max:255',
        'newPassword' => 'required|string|max:255',
    ]);

    return $this->userService->changePassword($data);
}


public function returnTockenUser()
{
    return $this->userService->returnTockenUser();
}
public function choosePlan(Request $request, $planId)
{
    $user = $this->userService->choosePlan($planId);

    return response()->json(['message' => 'Plan chosen successfully', 'user' => $user]);
}

public function requestPasswordReset(Request $request)
{
    $data = $request->validate([
        'email' => 'required|email',
    ]);

    return $this->userService->requestPasswordReset($data);
}

public function resetPassword(Request $request)
{
    $data = $request->validate([
        'email' => 'required|email',
        'token' => 'required|string',
        'newPassword' => 'required|string|confirmed|min:6',
    ]);

    return $this->userService->resetPassword($data);
}

}
