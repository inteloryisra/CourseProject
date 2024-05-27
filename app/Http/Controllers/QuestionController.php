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
            'quiz_id' => 'required|integer',
            'question' => 'required|string',
        ]);
        return $this->questionService->createQuestion($data);
    }

    public function updateQuestion(Request $request, $questionId)
    {
        $data = $request->validate([
            'quiz_id' => 'integer',
            'question' => 'string',
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