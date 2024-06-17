<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Achievement::create([
            'name' => 'First Quiz Completed',
            'condition_type' => 'quiz_attempts_count',
            'condition_value' => 1,
        ]);

        Achievement::create([
            'name' => 'High Score',
            'condition_type' => 'quiz_high_score',
            'condition_value' => 10,
        ]);
    }
}
