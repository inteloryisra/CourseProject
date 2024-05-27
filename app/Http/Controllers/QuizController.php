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
        ]);

        return $this->quizService->createQuiz($data);

    }

    public function updateQuiz(Request $request, $quizId)
    {
        $data= $request->validate([
            'title'=> 'string',
            'description'=> 'string',
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


}
