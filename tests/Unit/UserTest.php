<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Plan;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

class UserTest extends TestCase
{
    public function testRegisterUser()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'testt111@example.com',
            'password' => 'password',
            'role' => 'USER',
        ];

        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(['id', 'name', 'email', 'role']);
    }

    public function testGetAllUsers()
    {
        $response = $this->getJson('/api/users');
        $response->assertStatus(200);
    }

    public function testGetUserById()
    {
        $user = User::factory()->create();

        $response = $this->getJson('/api/users/' . $user->id);
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'name', 'email', 'role' , 'plan_id']);
    }

    public function testEditUser()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'Updated Name',
            'email' => 'updatedd11@example.com',
        ];

        $response = $this->putJson('/api/users/' . $user->id, $data);
        $response->assertStatus(200);
        $response->assertJson(['name' => 'Updated Name', 'email' => 'updatedd11@example.com']);
    }

    public function testLoginUser()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);


        $userService = new UserService();


        $data = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];


        $response = $userService->loginUser($data);


        $this->assertArrayHasKey('user', $response);
        $this->assertArrayHasKey('token', $response);
        $this->assertEquals($user->id, $response['user']->id);

    }

    public function testLogoutUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Logged out successfully']);
    }

    public function testChangePassword()
    {
        $password = 'password';
        $user = User::factory()->create(['password' => Hash::make($password)]);

        $data = [
            'email' => $user->email,
            'oldPassword' => $password,
            'newPassword' => 'newpassword',
        ];

        $response = $this->putJson('/api/change-password', $data);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Password updated successfully']);
    }

    public function testReturnTokenUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/me');
        $response->assertStatus(200);
    }

    public function testChoosePlan()
    {
        $user = User::factory()->create();

        $plan = Plan::factory()->create(['max_quiz_attempts' => 3]);

        $userService = new UserService();

        $this->actingAs($user);

        $updatedUser = $userService->choosePlan($plan->id);

        $this->assertEquals($plan->id, $updatedUser->plan_id);
    }


}
