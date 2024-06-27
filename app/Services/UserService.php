<?php

namespace App\Services;

use App\Models\User;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMail;
use App\Models\ForgetPasswordToken;

class UserService
{

    public function registerUser($data)
    {

        return User::query()->create($data);

    }

    public function getAllUsers()
    {
        return User::query()->get();
    }

    public function getUserById($userId)
    {
        return User::query()->findOrFail($userId);
    }

    public function editUser($userId, $userData)
    {
        $user = User::query()->findOrFail($userId);
        $user->update($userData);
        return $user;
    }

    public function loginUser($data, $authType)
    {
        if($authType ==='GOOGLE'){
            return $this->loginWithGoogle($data);
        }

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
    public function choosePlan($planId)
    {
        $user = Auth::user();
        $plan = Plan::findOrFail($planId);

        $user->update([
           'plan_id' => $plan->id,
        ]);

        return $user;
    }

    public function getGoogleUser($accessToken, $tokenType)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $tokenType,
        ])->get('https://www.googleapis.com/oauth2/v3/userinfo?access_token=' . $accessToken);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function loginWithGoogle($data)
    {
        $googleUser = $this->getGoogleUser($data['access_token'], $data['token_type']);

        $user = User::where('email', $googleUser['email'])->first();

        if ($user) {
            Auth::login($user);
        } else {
            $user = User::create([
                'name' => $googleUser['name'],
                'email' => $googleUser['email'],
                'password' => Hash::make(uniqid()),
            ]);

            Auth::login($user);
        }
        $token = $user->createToken('auth_token_' . $googleUser['email'] )->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    public function requestPasswordReset($email)
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $token = Str::random(64);
        ForgetPasswordToken::create([
            'email' => $email,
            'token' => $token,
        ]);

        Mail::to($email)->send(new PasswordResetMail($token));

        return response()->json(['message' => 'Password reset email sent']);
    }

    public function resetPassword($token, $newPassword)
    {
        $record = ForgetPasswordToken::where('token', $token)->first();
        if (!$record) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        $user = User::where('email', $record->email)->first();
        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        $record->delete();

        return response()->json(['message' => 'Password reset successfully']);
    }

}
