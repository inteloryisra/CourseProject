<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class EmailverificationToken extends Model
{
   use HasFactory;


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
