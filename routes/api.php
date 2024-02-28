<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/questions', [QuestionController::class, 'create']);
Route::post('/questions', [QuestionController::class, 'store']);

Route::post('/reply', [QuestionController::class, 'answers']);








//// GET /api/questions: Список всех вопросов
//Route::get('/questions', [QuestionController::class, 'index']);
//
//// POST /api/questions: Создание нового вопроса
//Route::post('/questions', [QuestionController::class, 'store']);
//
//// GET /api/questions/{question}: Просмотр одного вопроса
//Route::get('/questions/{question}', [QuestionController::class, 'show']);
//
//// PUT /api/questions/{question}: Обновление вопроса
//Route::put('/questions/{question}', [QuestionController::class, 'update']);
//
//// DELETE /api/questions/{question}: Удаление вопроса
//Route::delete('/questions/{question}', [QuestionController::class, 'destroy']);


//Route::prefix('/questions')->group(function () {
//    Route::get('/', [QuestionController::class, 'index']);
//    Route::post('/', [QuestionController::class, 'store']);
//
//    // Routes for a single question
//    Route::prefix('/{question}')->group(function () {
//        Route::get('/', [QuestionController::class, 'show']);
//        Route::put('/', [QuestionController::class, 'update']);
//        Route::delete('/', [QuestionController::class, 'destroy']);
//
//        // Answer Routes (Nested within a question)
//        Route::prefix('/answers')->group(function () {
//            Route::get('/', [AnswerController::class, 'index']);
//            Route::post('/', [AnswerController::class, 'store']);
//
//            // Routes for a single answer
//            Route::prefix('/{answer}')->group(function () {
//                Route::get('/', [AnswerController::class, 'show']);
//                Route::put('/', [AnswerController::class, 'update']);
//                Route::delete('/', [AnswerController::class, 'destroy']);
//            });
//        });
//    });
//});
