<?php

namespace App\Services;



use App\Models\Plan;
use App\Models\User;
use App\Models\Answer;
use App\Models\Language;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class QuizAttemptService
{
    protected $achievementService;

    public function __construct(AchievementService $achievementservice)
    {
        $this->achievementService = $achievementservice;
    }
    public function startQuiz($quizId, $data)
    {
        $user = Auth::user();


        $plan = Plan::query()->where('id', $user->plan_id)->first();


        if (!$plan) {
            return ['error' => 'No active plan found.'];
        }


        $attempts = QuizAttempt::where('quiz_id', $quizId)
                               ->where('user_id', $user->id)
                               ->count();

        if ($attempts >= $plan->max_quiz_attempts) {
            return ['error' => 'You have reached the maximum number of attempts for this quiz'];
        }


        $quizAttempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quizId,
            'language_id' => $data['language_id'],
            'score' => null,
        ]);

        return $quizAttempt;
    }

    public function submitAnswers($quizAttemptId, $data)
    {
        $quizAttempt = QuizAttempt::query()->findOrFail($quizAttemptId);
        $score = 0;
        $quizAttemptAnswers = [];

        $answerIds = array_column($data['answers'], 'answer_id');
        $answers = Answer::query()->whereIn('id', $answerIds)->get()->keyBy('id');

        DB::transaction(function () use ($quizAttempt, $data, &$score, &$quizAttemptAnswers, $answers) {
            foreach ($data['answers'] as $item) {
                $questionId = $item['question_id'];
                $answerId = $item['answer_id'];
                $answer = $answers[$answerId];

                $quizAttemptAnswers[] = [
                    'quiz_attempt_id' => $quizAttempt->id,
                    'question_id' => $questionId,
                    'answer_id' => $answer->id
                ];

                if ($answer->is_correct) {
                    $score++;
                }
            }

            QuizAttemptAnswer::query()->insert($quizAttemptAnswers);

            $quizAttempt->update(['score' => $score]);

            $this->achievementService->checkAndAwardAchievements($quizAttempt->user);
        });

        $previousAttempts = QuizAttempt::where('user_id', $quizAttempt->user_id)
                                       ->where('quiz_id', $quizAttempt->quiz_id)
                                       ->where('id', '!=', $quizAttempt->id)
                                       ->get();

        $highestScore = $previousAttempts->max('score');

        $resultMessage = $score >= 10 ? 'You have passed the test' : 'You have failed the test';
        $highScoreMessage = '';

        if ($score > $highestScore) {
            $highScoreMessage = "You have a new high score: $score";
            $highestScore = $score;
        }

        $user = $quizAttempt->user;
        $plan = Plan::query()->where('id', $user->plan_id)->first();
        $attempts = QuizAttempt::where('quiz_id', $quizAttempt->quiz_id)
                               ->where('user_id', $user->id)
                               ->count();

        if ($attempts == $plan->max_quiz_attempts) {
            return [
                'message' => 'You have finished all attempts.',
                'score' => $score,
                'highest_score' => $highestScore,
            ];
        }
        if ($highScoreMessage){
            return [
                'message' => $resultMessage,
                'score' => $score,
                'high_score_message' => $highScoreMessage,
            ];
        }

        return [
            'message' => $resultMessage,
            'score' => $score,
        ];
    }

    public function getQuizAttempt($quizAttemptId)
    {
        return QuizAttempt::query()->with(['quiz', 'user', 'answers'])->findOrFail($quizAttemptId);
}
}
