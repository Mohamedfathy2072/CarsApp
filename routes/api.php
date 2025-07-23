<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\BrandController;

use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\FavouriteController;
use App\Http\Controllers\Api\SavedSearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::apiResource('cars', CarController::class);
Route::apiResource('brands', BrandController::class);
Route::get('/contact-us', [ContactUsController::class, 'index']);
Route::post('/contact-us', [ContactUsController::class, 'store']);


Route::prefix('auth')->group(function () {
    Route::post('send-otp', [AuthController::class, 'sendOtp']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('login', [AuthController::class, 'login']);


    Route::middleware('auth:api')->group(function () {
        Route::post('update-profile', [AuthController::class, 'updateProfile']);
        Route::post('/favourites/toggle/{carId}', [FavouriteController::class, 'toggleFavourite']);
        Route::get('/favourites', [FavouriteController::class, 'myFavourites']);
        Route::delete('/favourites/clear', [FavouriteController::class, 'clearFavourites']);
        Route::get('me', [AuthController::class, 'me']);

        Route::post('complete-profile', [AuthController::class, 'updateProfile']);
        Route::post('set-password', [AuthController::class, 'setPassword']);
        Route::post('update-password', [AuthController::class, 'updatePassword']);


        Route::get('saved-searches', [SavedSearchController::class, 'index']);
        Route::post('saved-searches', [SavedSearchController::class, 'store']);
        Route::delete('saved-searches/{id}', [SavedSearchController::class, 'destroy']);
    });


});


