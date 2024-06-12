<?php

namespace App\Services;



use App\Models\Plan;
use App\Models\User;
use App\Models\Answer;
use App\Models\Language;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class QuizAttemptService
{
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

        $quiz = Quiz::findOrFail($quizId);
        $quizAttempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quizId,
            'language_id' => $data['language_id'],
            'score' => null,
            'level' => $quiz->level,
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
        });

        $result = $score >= 10 ? 'You have passed the test' : 'You have failed the test';
        return [
            'message' => $result,
            'score' => $score
        ];
    }


    public function getQuizAttempt($quizAttemptId)
    {
        return QuizAttempt::query()->with(['quiz', 'user', 'answers'])->findOrFail($quizAttemptId);
    }
    public function useJoker($quizAttemptId, $questionId)
    {
        $quizAttempt = QuizAttempt::query()->findOrFail($quizAttemptId);

        if ($quizAttempt->quiz->level !== 'EASY') {
            return ['error' => 'Jokers can only be used in EASY quizzes.'];
        }

        $question = Question::findOrFail($questionId);
        $answers = $question->answers;

        if ($answers->count() < 2) {
            return ['error' => 'Not enough answers to use 50/50 joker.'];
        }

        $correctAnswer = $answers->firstWhere('is_correct', true);
        $incorrectAnswers = $answers->where('is_correct', false)->random(1);

        return [
            'answers' => [
                $correctAnswer,
                $incorrectAnswers->first(),
            ],
        ];
    }

    public function getHint($quizAttemptId, $questionId)
{
    $quizAttempt = QuizAttempt::query()->findOrFail($quizAttemptId);

    if ($quizAttempt->quiz->level !== 'MEDIUM') {
        return ['error' => 'Hints can only be used in MEDIUM quizzes.'];
    }

    $offset = rand(0, 3);
    $length = rand(0, 3);

    if( $length-$offset === 1 || $length===0 || ($offset===0 && $length===0)){
        $offset = rand(0, 3);
        $length = rand(0, 3);
    }

    $hint = substr(
        Question::findOrFail($questionId)
        ->answers
        ->firstWhere('is_correct', true)
        ->answer
        , $offset, $length);

    return [
        'hint' => $hint,
    ];

}
}
