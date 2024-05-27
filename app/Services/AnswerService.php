<?php

namespace App\Services;

use App\Models\Answer;

class AnswerService
{
    public function createAnswer($data)
    {
        return Answer::query()->create($data);
    }

    public function updateAnswer($answerId, $data)
    {
        $answer = Answer::findOrFail($answerId);
        $answer->update($data);
        return $answer;
    }

    public function deleteAnswer($answerId)
    {
        $answer = Answer::findOrFail($answerId);
        $answer->delete();
    }

    public function getAnswerById($answerId)
    {
        return Answer::findOrFail($answerId);
    }

    public function getAllAnswers()
    {
        return Answer::all();
    }
}
