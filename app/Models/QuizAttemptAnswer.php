<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttemptAnswer extends Model
{
    use HasFactory;

/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quiz_attempt_id',
        'question_id',
        'answer_id',
    ];
    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class);
    }
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }

}
