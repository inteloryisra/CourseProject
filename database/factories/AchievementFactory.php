<?php

namespace Database\Factories;
use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;

class AchievementFactory extends Factory
{
    protected $model = Achievement::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'condition_type' => $this->faker->sentence,
            'condition_value' => $this->faker->numberBetween(1, 100),
        ];
    }
}
