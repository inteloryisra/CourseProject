<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class EmailverificationToken extends Model implements Auditable
{
   use HasFactory, AuditableTrait;


   protected $fillable = [
       'user_id',
       'token',
       'created_at',
       'updated_at'
   ];

   protected $table = 'email_verification_tokens';
   public function user()
   {
       return $this->belongsTo(User::class);
   }


}
