<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VacancyController;

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

//Route::apiResources([
//    'vacancies' => VacancyController::class
//]);
