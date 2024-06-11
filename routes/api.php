<?php

use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\QuizAttemptController;
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
Route::get('/plans', [PlanController::class,'getAllPlans']);
Route::post('/create-plan', [PlanController::class, 'createPlan'])->middleware('auth:sanctum', 'admin');
Route::delete('/plans/{planId}', [PlanController::class, 'deletePlan'])->middleware('auth:sanctum', 'admin');
Route::post('/users/choose-plan/{planId}', [UserController::class, 'choosePlan'])->middleware('auth:sanctum');

Route::get('/quizzes',[QuizController::class,'getAllQuizzes']);
Route::post('/create-quiz',[QuizController::class,'createQuiz'])->middleware('auth:sanctum', 'admin');
Route::put('/quizzes/{quizId}',[QuizController::class,'updateQuiz'])->middleware('auth:sanctum', 'admin');
Route::get('/quizzes/{quizId}',[QuizController::class,'getQuizById']);
Route::delete('/quizzes/{quizId}', [QuizController::class, 'deleteQuiz'])->middleware('auth:sanctum', 'admin');
Route::post('/create-question', [QuestionController::class, 'createQuestion']);
Route::put('/questions/{questionId}', [QuestionController::class, 'updateQuestion']);
Route::delete('/questions/{questionId}', [QuestionController::class, 'deleteQuestion']);
Route::get('/questions', [QuestionController::class, 'getAllQuestions']);
Route::get('/questions/{questionId}', [QuestionController::class, 'getQuestionById']);
Route::post('/create-answer', [AnswerController::class, 'createAnswer']);
Route::put('/answers/{answerId}', [AnswerController::class, 'updateAnswer']);
Route::delete('/answers/{answerId}', [AnswerController::class, 'deleteAnswer']);
Route::get('/answers', [AnswerController::class, 'getAllAnswers']);
Route::get('/answers/{answerId}', [AnswerController::class, 'getAnswerById']);
Route::post('/quiz-attempts/start/{quizId}', [QuizAttemptController::class, 'startQuiz'])->middleware('auth:sanctum');
Route::post('/quiz-attempts/{quizAttemptId}/submit-answers', [QuizAttemptController::class, 'submitAnswers'])->middleware('auth:sanctum');
Route::get('/quiz-attempts/{quizAttemptId}', [QuizAttemptController::class, 'getQuizAttempt']);
Route::get('/quizzes/language/{language_id}', [QuizController::class, 'getQuizByLanguage']);









