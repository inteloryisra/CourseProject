<?php

namespace App\Services;

use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Language;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuizAttemptService
{
    public function startQuiz($quizId, $data)
    {
        $user = Auth::user();
        $quizAttempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quizId,
            'language_id' => $data['language_id'],
            'score' => null
        ]);

        return $quizAttempt;
    }

    public function countUserAttempts($quizId)
   {
       $user = Auth::user();
       return QuizAttempt::where('quiz_id', $quizId)
                         ->where('user_id', $user->id)
                         ->count();
   }

   public function submitAnswers($quizAttemptId, $data)
   {
      $quizAttempt = QuizAttempt::query()->findOrFail($quizAttemptId);
      $score = 0;


      DB::transaction(function () use ($quizAttempt, $data, &$score) {
          foreach ($data['answers'] as $item) {
              $questionId = $item['question_id'];
              $answerId = $item['answer_id'];


              $question = Question::query()->findOrFail($questionId);
              $answer = Answer::query()->findOrFail($answerId);


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
}
