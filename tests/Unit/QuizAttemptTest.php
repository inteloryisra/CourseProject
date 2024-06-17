<?php

namespace Tests\Unit;

use App\Models\Language;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Models\Plan;
use App\Services\AchievementService;
use App\Services\QuizAttemptService;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class QuizAttemptTest extends TestCase
{

    public function testStartQuiz()
    {
        $user = User::factory()->create();
        $plan = Plan::factory()->create(['max_quiz_attempts' => 3]);
        $user->update(['plan_id' => $plan->id]);
        $language = Language::factory()->create();
        $quiz = Quiz::factory()->create();


        $this->actingAs($user);


        $achievement = new AchievementService();
        $service = new QuizAttemptService($achievement);

        $data = ['language_id' => $language->id];
        $result = $service->startQuiz($quiz->id, $data);


        $this->assertInstanceOf(QuizAttempt::class, $result);

        $this->assertEquals($user->id, $result->user_id);
        $this->assertEquals($quiz->id, $result->quiz_id);
        $this->assertEquals($language->id, $result->language_id);
        $this->assertNull($result->score);
    }


}
