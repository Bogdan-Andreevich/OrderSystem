<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\PriceRowsController;


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
Route::put('/questions/{id}', [QuestionController::class, 'update']);
Route::get('/questions/{id}', [QuestionController::class, 'getQuestionById']);
Route::delete('/questions/{id}', [QuestionController::class, 'destroy']);
Route::post('/questions/{id}/answers', [QuestionController::class, 'answers']);

Route::get('/categories', [CategoriesController::class, 'create']);
Route::post('/categories', [CategoriesController::class, 'store']);
Route::delete('/categories/{id}', [CategoriesController::class, 'destroy']);

Route::get('/prices', [PriceController::class, 'create']);
Route::post('/prices', [PriceController::class, 'store']);
Route::put('/prices/{id}', [PriceController::class, 'update']);
Route::delete('/prices/{id}', [PriceController::class, 'destroy']);

Route::get('/price/rows', [PriceRowsController::class, 'create']);
Route::post('/price/rows', [PriceRowsController::class, 'store']);
Route::put('/price/rows/{id}', [PriceRowsController::class, 'update']);








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
