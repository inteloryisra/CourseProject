<?php

namespace App\Services;

use App\Models\Quiz;

class QuizService
{
    public function createQuiz($data)
    {
        return Quiz::query()->create($data);
    }

    public function updateQuiz($quizId, $data)
    {
        $quiz= Quiz::findOrFail($quizId);
        $quiz->update($data);
        return $quiz;
    }

    public function deleteQuiz($quizId)
    {
        $quiz= Quiz::findOrFail($quizId);
        $quiz->delete();
    }

    public function getQuizById($quizId)
    {
        return Quiz::query()->findOrFail($quizId);
    }

    public function getAllQuizzes()
    {
        return Quiz::all();
    }
}