<?php

use App\Http\Controllers\ChoiceController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/question/upload', [QuestionController::class, 'upload']);
Route::get('/questions', [QuestionController::class, 'get']);
Route::get('/question/{question}', [QuestionController::class, 'single']);
Route::post('/question/{question}', [QuestionController::class, 'addChoice']);
Route::patch('/question/{question}', [QuestionController::class, 'update']);
Route::delete('/question/{question}', [QuestionController::class, 'drop']);
Route::delete('/choice/{choiceId}', [ChoiceController::class, 'drop']);
Route::patch('/choice/{choiceId}', [ChoiceController::class, 'update']);
