<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//auth
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:api');
Route::post('/refresh',[AuthController::class,'refresh'])->middleware('auth:api');

//public category routes
Route::get('/categories',[CategoryController::class,'index']);
Route::get('/categories/{id}',[CategoryController::class,'show']);


//public product routes
Route::get('/products',[ProductController::class,'index']);
Route::get('/products/search',[ProductController::class,'search']);
Route::get('/products/filter',[ProductController::class,'filter']);
Route::get('/products/{id}',[ProductController::class,'show']);


//loged in users
Route::middleware('auth:api')->group(function(){

    //cart
    Route::get('/cart', [CartItemController::class, 'index']);
    Route::post('/cart', [CartItemController::class, 'store']);
    Route::delete('/cart/clear', [CartItemController::class, 'clear']);
    Route::post('/cart/{id}', [CartItemController::class, 'update']);
    Route::delete('/cart/{id}', [CartItemController::class, 'destroy']);


    //wishlist
    Route::get('/wishlist',[WishlistController::class,'index']);
    Route::post('/wishlist/{productId}',[WishlistController::class,'store']);
    Route::delete('/wishlist/{productId}',[WishlistController::class,'destroy']);

});


//admins only
Route::prefix('admin')->middleware(['auth:api','isAdmin'])->group(function(){

    //categories
    Route::post('/categories/create',[CategoryController::class,'store']);
    Route::post('/categories/update/{id}',[CategoryController::class,'update']);
    Route::delete('/categories/delete/{id}',[CategoryController::class,'destroy']);

    //products
    Route::post('/products/create',[ProductController::class,'store']);
    Route::post('/products/update/{id}',[ProductController::class,'update']);
    Route::delete('/products/delete/{id}',[ProductController::class,'destroy']);


    //users
    Route::get('/users',[UserController::class,'index']);
    Route::get('/users/{id}',[UserController::class,'show']);
    Route::delete('/users/{id}',[UserController::class,'destroy']);


});
