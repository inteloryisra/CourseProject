<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class ForgetPasswordToken extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'user_id',
        'email',
        'token',
        'created_at',
        'updated_at'
    ];


}
