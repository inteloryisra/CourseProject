<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPlan extends Model
{
    use HasFactory;

    protected $keyType='string';


    protected $fillable = [
        'user_id',
        'plan_id',
    ];


    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plans() : BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

}
