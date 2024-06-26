<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    /**
     * The primary key type.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected $fillable = [
        'question_id',
        'answer',
        'is_correct',
        'language_id',

    ];
    protected $hidden = [
        'is_correct',
    ];


    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

}
