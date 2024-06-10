<?php

namespace Tests\Unit;

use App\Models\Language;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class QuizAttemptTest extends TestCase
{

    public function testStartAttempt()
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create();
        $language = Language::factory()->create();

        $this->actingAs($user, 'sanctum');

        $data = [
            'user_id'=> $user->id,
            'quiz_id'=> $quiz->id,
            'language_id'=>$language->id,
            'score'=>null,
        ];

        $response = $this->postJson("/api/quiz-attempts/start/{$quiz->id}", $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('quiz_attempts', [
            'user_id'=> $user->id,
            'quiz_id'=> $quiz->id,
            'language_id'=>$language->id,
            'score'=>null,
        ]);
    }

}
