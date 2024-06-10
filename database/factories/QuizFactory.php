<?php

namespace Database\Factories;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    public function definition()
    {
        return [
            'title' => $this->faker->word,
            'description' => $this->faker->sentence,
            //'language_id' => $this->faker->randomNumber,
        ];
    }
}
