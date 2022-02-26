<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\V1\Auth\UserController;
use \App\Http\Controllers\Api\V1\Auth\AuthenticationController;
use \App\Http\Controllers\Api\V1\Product\ProductController;
use \App\Http\Controllers\Api\V1\Order\OrderController;

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
Route::get('/test', function(){ return response(["message" =>"test"], 200); });

Route::prefix('/v1')->group(function () {
    Route::post('/users', [UserController::class, 'create']);
    Route::post('/auth/token', [AuthenticationController::class, 'login']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/auth/logout', [AuthenticationController::class, 'logout']);

        /** Product */
        Route::controller(ProductController::class)->group(function (){
            Route::post('/products', 'create');
            Route::get('/products', 'list');
        });

        /** Order */
        Route::controller(OrderController::class)->group(function (){
            Route::post('/orders', 'create');
            Route::get('/orders', 'list');
        });
    });
});

