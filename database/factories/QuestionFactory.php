<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition()
    {
        return [
            'quiz_id' => Quiz::factory(), 
            'question' => $this->faker->sentence,
            'language_id' => Language::factory(),
        ];
    }
}
