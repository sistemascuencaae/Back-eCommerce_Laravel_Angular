<?php

use App\Http\Controllers\Ecommerce\Cart\CartShopController;
use App\Http\Controllers\Ecommerce\HomeController;
use App\Http\Controllers\Ecommerce\Sale\SaleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cupones\CuponesController;
use App\Http\Controllers\Discount\DiscountController;
use App\Http\Controllers\Ecommerce\Client\AddressUserController;
use App\Http\Controllers\JWTController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Product\CategorieController;
use App\Http\Controllers\Product\ProductGController;
use App\Http\Controllers\Product\ProductImagensController;
use App\Http\Controllers\Product\ProductSizeColorController;
use App\Http\Controllers\Slider\SliderController;

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

    Route::group(['prefix' => 'inventario'], function () {
        Route::post('/add', [ProductSizeColorController::class, 'store']);
        Route::put('/update_size/{id}', [ProductSizeColorController::class, 'update_size']);
        Route::delete('/delete_size/{id}', [ProductSizeColorController::class, 'destroy_size']);
        // Rutas de las sub dimensiones o sub inventarios
        Route::put('/update/{id}', [ProductSizeColorController::class, 'update']);
        Route::delete('/delete/{id}', [ProductSizeColorController::class, 'destroy']);
    });
});

Route::group(['prefix' => 'sliders'], function ($router) {
    Route::get('/all', [SliderController::class, 'index']);
    Route::post('/add', [SliderController::class, 'store']);
    Route::post('/update/{id}', [SliderController::class, 'update']);
    Route::delete('/delete/{id}', [SliderController::class, 'destroy']);
});

Route::group(['prefix' => 'cupones'], function ($router) {
    Route::get('/all', [CuponesController::class, 'index']);
    Route::get('/config_all', [CuponesController::class, 'config_all']);
    Route::post('/add', [CuponesController::class, 'store']);
    Route::post('/update/{id}', [CuponesController::class, 'update']);
    Route::delete('/delete/{id}', [CuponesController::class, 'destroy']);
    Route::get('/show/{id}', [CuponesController::class, 'show']);
});

Route::group(['prefix' => 'descuentos'], function ($router) {
    Route::get("/all", [DiscountController::class, 'index']);
    Route::get("/show/{id}", [DiscountController::class, 'show']);
    Route::post("/add", [DiscountController::class, 'store']);
    Route::put("/update/{id}", [DiscountController::class, 'update']);
    Route::delete("/delete/{id}", [DiscountController::class, 'destroy']);
});

Route::group(["prefix" => "ecommerce"], function ($router) {
    Route::get("home", [HomeController::class, 'home']);
    Route::get("detail-product/{slug}", [HomeController::class, 'detail_product']);

    Route::group(["prefix" => "cart"], function () {
        Route::resource("add", CartShopController::class);
        Route::get("applycupon/{cupon}", [CartShopController::class, 'apply_cupon']);
    });

    Route::group(["prefix" => "checkout"], function () {
        Route::resource("address_user", AddressUserController::class);
        Route::post("sale", [SaleController::class, 'store']);
    });

});

// Route::get("sale_mail/{id}","Ecommerce\Sale\SaleController@send_email");
Route::get("sale_mail/{id}", [SaleController::class, 'send_email']);