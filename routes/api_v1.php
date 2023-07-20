<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
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

Route::prefix('v1')->group(function () {

    // Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    //     // return response()->json(['user' => $request->user()], 200);
    // });

    // Route::get('/test',function(){
    //     dd('test');
    // });

    // Route::group(['middleware' => 'auth:sanctum'], function () {
    //     Route::post('/auth/sign-out', [AuthController::class, 'SignOut']);
    // });

    // Route Auth
    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::post('/register', 'createUser');
        Route::post('/login', 'LoginUser');
        Route::post('/email-verified', 'isEmailExists');
    });


    // SignOut Route
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::controller(AuthController::class)->prefix('auth')->group(function () {
            Route::post('/sign-out', 'SignOut');
        });

    });



});
