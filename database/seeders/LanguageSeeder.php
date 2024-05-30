<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $languages = [
            ['name' => 'Albanian', 'code' => 'sq'],
            ['name' => 'Macedonian', 'code' => 'mk'],
            ['name' => 'English', 'code' => 'en'],

        ];

        
        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
