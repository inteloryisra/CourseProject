<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
   /**
    * Run the migrations.
    */
   public function up(): void
   {
       Schema::create('email_verification_tokens', function (Blueprint $table) {
           $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
           $table->foreignUuid('user_id')->references('id')->on('users')->onDelete('cascade');
           $table->string('token')->unique();
           $table->timestamps();
       });
   }


   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
       Schema::dropIfExists('email_verification_tokens');
   }
};
