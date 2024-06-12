<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            ['name' => 'Basic', 'price' => 29.99, 'max_quiz_attempts' => 3],
            ['name' => 'Premium', 'price' => 49.99, 'max_quiz_attempts' => 5],
            ['name' => 'Enterprise', 'price' => 99.99, 'max_quiz_attempts' => 10],
        ];

        // Insert sample data into the database
        foreach ($plans as $plan) {
            DB::table('plans')->insert($plan);
        }
    }
}
