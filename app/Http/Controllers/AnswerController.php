<?php

namespace App\Http\Controllers;

use App\Services\AnswerService;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    protected $answerService;

    public function __construct(AnswerService $answerService)
    {
        $this->answerService = $answerService;
    }

    public function createAnswer(Request $request)
    {
        $data = $request->validate([
            'question_id' => 'required|string',
            'answer' => 'required|string',
            'is_correct' => 'required|boolean',
            'language_id' => 'required|string|exists:languages,id',
        ]);

        return $this->answerService->createAnswer($data);
    }

    public function updateAnswer(Request $request, $answerId)
    {
        $data = $request->validate([
            'question_id' => 'string',
            'answer' => 'string',
            'is_correct' => 'boolean',
            'language_id' => 'string|exists:languages,id',
        ]);

        return $this->answerService->updateAnswer($answerId, $data);
    }

    public function deleteAnswer($answerId)
    {
        $this->answerService->deleteAnswer($answerId);
    }

    public function getAllAnswers()
    {
        return $this->answerService->getAllAnswers();
    }

    public function getAnswerById($answerId)
    {
        return $this->answerService->getAnswerById($answerId);
    }
}
