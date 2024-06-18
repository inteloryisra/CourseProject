<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuizAttemptService;
use App\Models\Language;

class QuizAttemptController extends Controller
{
    protected $quizAttemptService;

    public function __construct(QuizAttemptService $quizAttemptService)
    {
        $this->quizAttemptService = $quizAttemptService;
    }

    public function startQuiz(Request $request, $quizId)
{
    $data = $request->validate([
        'language_id' => 'required|exists:languages,id',
    ]);

    $result = $this->quizAttemptService->startQuiz($quizId, $data);

    if (isset($result['error'])) {
        return response()->json(['error' => $result['error']], 403);
    }

    return response()->json($result, 201);
}


    public function submitAnswers(Request $request, $quizAttemptId)
    {
        $data = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer_id' => 'required|exists:answers,id',
        ]);

        $quizAttempt = $this->quizAttemptService->submitAnswers($quizAttemptId, $data);

        return response()->json($quizAttempt, 200);
    }

    public function getQuizAttempt($quizAttemptId)
    {
        $quizAttempt = $this->quizAttemptService->getQuizAttempt($quizAttemptId);

        return response()->json([
            'quiz_attempt' => $quizAttempt,
            'high_score' => $quizAttempt->high_score,
        ]);
    }
}
