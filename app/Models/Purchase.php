<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'extra_attempts_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
