<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuestionService;

class QuestionController extends Controller
{
    public QuestionService $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function createQuestion(Request $request){

        $data= $request->validate([
            'quiz_id' => 'required|string',
            'question' => 'required|string',
            'language_id' => 'string|string|exists:languages,id',
        ]);
        return $this->questionService->createQuestion($data);
    }

    public function updateQuestion(Request $request, $questionId)
    {
        $data = $request->validate([
            'quiz_id' => 'string',
            'question' => 'string',
            'language_id' => 'string|exists:languages,id',
        ]);

        return $this->questionService->updateQuestion($questionId, $data);
    }

    public function deleteQuestion($questionId)
    {
        $this->questionService->deleteQuestion($questionId);
    }

    public function getAllQuestions()
    {
        return $this->questionService->getAllQuestions();
    }

    public function getQuestionById($questionId)
    {
        return $this->questionService->getQuestionById($questionId);
    }

}
