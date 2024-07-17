<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\QuizService;

class QuizController extends Controller
{
    public QuizService $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function createQuiz(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'language_id' => 'required|string|exists:languages,id',
            'time_limit' => 'required|integer',
        ]);

        return $this->quizService->createQuiz($data);

    }

    public function updateQuiz(Request $request, $quizId)
    {
        $data= $request->validate([
            'title'=> 'string',
            'description'=> 'string',
            'language_id' => 'string|exists:languages,id',
            'time_limit' => 'integer',
        ]);

        return $this->quizService->updateQuiz($quizId, $data);

    }

    public function deleteQuiz($quizId)
    {
        $this->quizService->deleteQuiz($quizId);
    }

    public function getQuizById($quizId)
    {
        return $this->quizService->getQuizById($quizId);
    }

    public function getAllQuizzes()
    {
        return $this->quizService->getAllQuizzes();
    }

    public function getQuizByLanguage($language_id)
    {
        $quizzes = $this->quizService->getQuizByLanguage($language_id);

        return response()->json($quizzes, 200);
    }
    public function attachCategories(Request $request, $quizId)
    {
        $data = $request->validate([
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $this->quizService->attachCategories($quizId, $data['category_ids']);

        return response()->json(['message' => 'Categories attached successfully'], 200);
    }

    public function detachCategories(Request $request, $quizId)
    {
        $data = $request->validate([
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $this->quizService->detachCategories($quizId, $data['category_ids']);

        return response()->json(['message' => 'Categories detached successfully'], 200);
    }

    public function getQuizzesByCategory($categoryId)
    {
        $quizzes = $this->quizService->getQuizzesByCategory($categoryId);
        return response()->json($quizzes, 200);
    }

}
