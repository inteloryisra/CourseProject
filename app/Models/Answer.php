<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question_id',
        'answer',
        'is_correct'

    ];
    protected $hidden = [
        'is_correct',
    ];


    public function question()
    {
        return $this->belongsTo(Question::class);
    }

}
