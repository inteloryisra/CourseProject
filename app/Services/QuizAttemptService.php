<?php

namespace App\Services;

use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuizAttemptService
{
    public function startQuiz($quizId)
    {
        $user=Auth::user();
        $quizAttempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quizId,
            'score' => null
        ]);

        return $quizAttempt;
    }

    public function submitAnswers($quizAttemptId, $data)
{
    $quizAttempt = QuizAttempt::query()->findOrFail($quizAttemptId);
    $score = 0;

    DB::transaction(function () use ($quizAttempt, $data, &$score) {
        foreach ($data['answers'] as $item) {
            $questionId = $item['question_id'];
            $answerId = $item['answer_id'];

            $question = Question::findOrFail($questionId);
            $answer = Answer::findOrFail($answerId);

            QuizAttemptAnswer::create([
                'quiz_attempt_id' => $quizAttempt->id,
                'question_id' => $questionId,
                'answer_id' => $answerId
            ]);

            if ($answer->is_correct) {
                $score++;
            }
        }

        $quizAttempt->update(['score' => $score]);
    });

    return "Your score is $score";
}



    public function getQuizAttempt($quizAttemptId)
    {
        return QuizAttempt::with(['quiz', 'user', 'answers'])->findOrFail($quizAttemptId);
    }
}
