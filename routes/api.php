<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('/users', [UserController::class,'getAllUsers']);
Route::get('/users/{userId}', [UserController::class, 'getUserById']);
Route::post('/register', [UserController::class, 'register']);
Route::put('/users/{userId}', [UserController::class, 'editUser']);
Route::post('/login', [UserController::class, 'loginUser']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::put('/users/{userId}/change-password', [UserController::class, 'changePassword'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'returnTockenUser']);




