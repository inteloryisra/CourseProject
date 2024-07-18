<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExtraAttempt;

class ExtraAttemptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $extra_attempts = [
            ['name' => 'Basic', 'amount' => 5.99, 'extra_attempts' => 3],
            ['name' => 'Premium', 'amount' => 7.99, 'extra_attempts' => 5],
            ['name' => 'Enterprise', 'amount' => 9.99, 'extra_attempts' => 10],
        ];


        foreach ($extra_attempts as $extra_attempt) {
            ExtraAttempt::create($extra_attempt);
        }
    }
}
