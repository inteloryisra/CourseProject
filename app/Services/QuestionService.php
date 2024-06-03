<?php

namespace App\Services;

use App\Models\Question;

class QuestionService
{
    public function createQuestion($data)
    {
        return Question::query()->create($data);
    }

    public function updateQuestion($questionId, $data)
    {
        $question=Question::query()->findOrFail($questionId);
        $question->update($data);
        return $question;
    }
    public function deleteQuestion($questionId)
    {
        return Question::destroy($questionId);

    }

    public function getQuestionById($questionId)
    {
        return Question::query()->with('answers')->findOrFail($questionId);
    }

    public function getAllQuestions()
    {
        return Question::query()->get();
    }

}
