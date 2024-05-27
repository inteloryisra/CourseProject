<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PlanController;
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
Route::post('/register', [UserController::class, 'registerUser']);
Route::put('/users/{userId}', [UserController::class, 'editUser']);
Route::post('/login', [UserController::class, 'loginUser']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [UserController::class, 'returnTockenUser'])->middleware('auth:sanctum');
Route::put('/change-password', [UserController::class,'changePassword']);
Route::post('/create-plan', [PlanController::class, 'createPlan'])->middleware('auth:sanctum', 'admin');
Route::get('/users/{user}/choose-plan/{planId}', [UserController::class, 'choosePlan'])->middleware('auth:sanctum');






