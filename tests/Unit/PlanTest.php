<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\PlanService;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlanTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePlan()
    {

        $data = [
            'name' => 'Test Plan',
            'price' => 9.99,
            'max_quiz_attempts' => 3,

        ];


        $planService = new PlanService();


        $createdPlan = $planService->createPlan($data);


        $this->assertInstanceOf(Plan::class, $createdPlan);


        $this->assertEquals($data['name'], $createdPlan->name);
        $this->assertEquals($data['price'], $createdPlan->price);
        $this->assertEquals($data['max_quiz_attempts'], $createdPlan->max_quiz_attempts);

        $this->assertDatabaseHas('plans', $data);
    }
}
