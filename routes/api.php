<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\API\V1\Auth\AuthController;
use \App\Http\Controllers\API\V1\OnboardProcessController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------

*/

Route::get('/',function(){
    return "permission denied";
});

Route::group(['prefix' => 'v1'], function () {

    /*User*/
    Route::group(['prefix' => 'user'], function () {

        /*Register*/
        Route::post('register', [AuthController::class, 'register'])->name('user.register');

        /*login*/
        Route::post('login', [AuthController::class, 'login'])->name('user.login');

        /*Logout*/
        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::post('logout', [AuthController::class, 'logout'])->name('user.logout');
        });


    });


    /*ExcelFile*/
    Route::group(['prefix'=>'generate','middleware' => 'auth:sanctum'], function () {
        Route::post('chart', [OnboardProcessController::class, 'getChart'])->name('generate.chart');

    });


});
