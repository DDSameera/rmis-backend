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
    Route::group(['prefix' => 'user'], function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
    });


    /*Applicant*/
    Route::apiResources('applicant',ApplicantController::class);
});
