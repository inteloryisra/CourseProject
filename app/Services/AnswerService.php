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
        $answer = Answer::query()->findOrFail($answerId);
        $answer->update($data);
        return $answer;
    }

    public function deleteAnswer($answerId)
    {
        return Answer::destroy($answerId);

    }

    public function getAnswerById($answerId)
    {
        return Answer::query()->findOrFail($answerId);
    }

    public function getAllAnswers()
    {
        return Answer::query()->get();
    }
}
