<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\LikeController;

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


Route::controller(VacancyController::class)->group(function () {
    Route::get('vacancies', 'index');
    Route::get('vacancies/{vacancy}', 'show');
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('vacancies', 'store');
        Route::patch('vacancies/{vacancy}', 'update');
        Route::delete('vacancies/{vacancy}', 'destroy');
    });
});

Route::controller(ResponseController::class)->middleware('auth:sanctum')->group(function () {
    Route::post('responses', 'store');
    Route::delete('responses/{response}', 'destroy');
});

Route::controller(LikeController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('likes/vacancies', 'indexVacancies');
    Route::get('likes/users', 'indexUsers');
    Route::post('likes/vacancies/{vacancy}', 'storeVacancy');
    Route::post('likes/users/{user}', 'storeUser');
});

//Route::apiResources([
//    'vacancies' => VacancyController::class
//]);
