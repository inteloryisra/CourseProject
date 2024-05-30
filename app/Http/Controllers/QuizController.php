<?php

namespace App\Http\Controllers;

use App\Services\QuizService;
use Illuminate\Http\Request;

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
            'language_id' => 'required|integer|exists:languages,id',
        ]);

        return $this->quizService->createQuiz($data);

    }

    public function updateQuiz(Request $request, $quizId)
    {
        $data= $request->validate([
            'title'=> 'string',
            'description'=> 'string',
            'language_id' => 'integer|exists:languages,id',
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

    public function getQuizByLanguage(Request $request, $language_id)
{
    $quizzes = $this->quizService->getQuizByLanguage($language_id);

    return response()->json($quizzes, 200);
}

}
