<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\API\V1\Auth\AuthController;
use \App\Http\Controllers\API\V1\ApplicantController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------

*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'v1'], function () {

    /*User*/
    Route::group(['prefix' => 'user', 'middleware' => ['throttle:five_max_login_attempt_per_min']], function () {
        Route::post('register', [AuthController::class, 'register'])->name('user.register');
        Route::post('login', [AuthController::class, 'login'])->name('user.login');
        Route::middleware(['auth:sanctum'])->post('logout', [AuthController::class, 'logout'])->name('user.logout');
    });


    /*Applicant*/
    Route::middleware(['auth:sanctum', 'token.ability'])->apiResource('applicant', ApplicantController::class);

});
