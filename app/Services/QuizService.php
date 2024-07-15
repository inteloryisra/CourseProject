<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\Category;

class QuizService
{
    public function createQuiz($data)
    {
        return Quiz::query()->create($data);
    }

    public function updateQuiz($quizId, $data)
    {
        $quiz= Quiz::query()->findOrFail($quizId);
        $quiz->update($data);
        return $quiz;
    }

    public function deleteQuiz($quizId)
    {
       return Quiz::destroy($quizId);
    }

    public function getQuizById($quizId)
    {
        return Quiz::query()->with('questions.answers')->findOrFail($quizId);
    }

    public function getAllQuizzes()
    {
        return Quiz::query()->get();
    }

    public function getQuizByLanguage($languageId)
    {
        return Quiz::query()->where('language_id',$languageId)->with('questions.answers')->get();
    }

    public function attachCategories($quizId, $categoryIds)
    {
        $quiz = Quiz::findOrFail($quizId);
        $quiz->categories()->attach($categoryIds);
    }

    public function detachCategories($quizId, $categoryIds)
    {
        $quiz = Quiz::findOrFail($quizId);
        $quiz->categories()->detach($categoryIds);
    }

    public function getQuizzesByCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $quizzes = $category->quizzes()->get();
        return $quizzes;
    }
}
