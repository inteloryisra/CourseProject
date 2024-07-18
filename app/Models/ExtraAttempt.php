<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraAttempt extends Model
{
    use HasFactory;

    protected $keyType='string';


    protected $fillable = [
        'name',
        'amount',
        'extra_attempts',

    ];

}
