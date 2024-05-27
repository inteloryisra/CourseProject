<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuizAttemptService;

class QuizAttemptController extends Controller
{
    protected $quizAttemptService;

    public function __construct(QuizAttemptService $quizAttemptService)
    {
        $this->quizAttemptService = $quizAttemptService;
    }

    public function startQuiz(Request $request, $quizId)
{

    $quizAttempt = $this->quizAttemptService->startQuiz($quizId);
    return response()->json($quizAttempt, 201);
}

    public function submitAnswers(Request $request, $quizAttemptId)
    {
        $data = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer_id' => 'required|exists:answers,id',
        ]);

        $quizAttempt = $this->quizAttemptService->submitAnswers($quizAttemptId, $data);

        //dd($quizAttempt);

        return response()->json($quizAttempt, 200);
    }

    public function getQuizAttempt($quizAttemptId)
    {
        $quizAttempt = $this->quizAttemptService->getQuizAttempt($quizAttemptId);
        return response()->json($quizAttempt, 200);
    }
}