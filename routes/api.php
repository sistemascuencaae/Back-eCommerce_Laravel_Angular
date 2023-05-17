<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Product\CategorieController;
use App\Http\Controllers\Product\ProductGController;
use App\Http\Controllers\Product\ProductImagensController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'users'], function ($router) {
    // Route::post('/register', "JWTController@register"); //Asi se declara en laravel 7
    Route::post('/register', [JWTController::class, 'register']); //Asi se declara en laravel 8 para adelante
    Route::post('/login', [JWTController::class, 'loginAdmin']);
    Route::post('/login_ecommerce', [JWTController::class, 'loginEcommerce']);
    Route::post('/logout', [JWTController::class, 'logout']);
    Route::post('/refresh', [JWTController::class, 'refresh']);
    Route::post('/profile', [JWTController::class, 'profile']);

    //Otro grupo de routes
    Route::group(['prefix' => 'admin'], function () {
        Route::post('/register', [UserController::class, 'store']);
        Route::get('/all', [UserController::class, 'index']);
        Route::put('/update/{id}', [UserController::class, 'update']);
        Route::delete('/delete/{id}', [UserController::class, 'destroy']);
    });
});

Route::group(['prefix' => 'products'], function ($router) {
    Route::get('/get_info_categories', [ProductGController::class, 'get_info_categories']);
    Route::post('/add', [ProductGController::class, 'store']);
    Route::get('/all', [ProductGController::class, 'index']);
    Route::get('/show_product/{id}', [ProductGController::class, 'show']);
    Route::post('/update/{id}', [ProductGController::class, 'update']);

    Route::group(['prefix' => 'categories'], function () {
        Route::post('/add', [CategorieController::class, 'store']);
        Route::get('/all', [CategorieController::class, 'index']);
        Route::post('/update/{id}', [CategorieController::class, 'update']);
        Route::delete('/delete/{id}', [CategorieController::class, 'destroy']);
    });

    Route::group(['prefix' => 'imgs'], function () {
        Route::post('/add', [ProductImagensController::class, 'store']);
        Route::delete('/delete/{id}', [ProductImagensController::class, 'destroy']);
    });
});