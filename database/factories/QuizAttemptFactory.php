<?php

namespace Database\Factories;

use App\Models\QuizAttempt;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizAttemptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuizAttempt::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },
            'quiz_id' => function () {
                return \App\Models\Quiz::factory()->create()->id;
            },
            'language_id' => $this->faker->numberBetween(1, 5), 
            'score' => $this->faker->numberBetween(0, 100),
        ];
    }
}
