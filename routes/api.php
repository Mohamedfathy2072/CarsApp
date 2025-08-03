<?php

use App\Helpers\CarInstallmentCalculator;
use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\BrandController;

use App\Http\Controllers\Api\CarInstallmentController;
use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\FacultyController;
use App\Http\Controllers\Api\FavouriteController;
use App\Http\Controllers\Api\FinancingRequestController;
use App\Http\Controllers\Api\GovernorateController;
use App\Http\Controllers\Api\HelpRequestController;
use App\Http\Controllers\Api\SavedSearchController;
use App\Http\Controllers\Api\StartAdController;
use App\Http\Controllers\Api\UniversityController;
use App\Http\Controllers\Api\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\NotificationController as ApiNotificationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('cars', CarController::class);
Route::apiResource('brands', BrandController::class);
Route::get('/contact-us', [ContactUsController::class, 'index']);
Route::post('/contact-us', [ContactUsController::class, 'store']);
// المحافظات والمناطق
Route::get('/governorates', [GovernorateController::class, 'index']);
Route::get('/areas', [AreaController::class, 'index']);
Route::post('complete-profile', [AuthController::class, 'completeRegistration']);
Route::get('universities', [UniversityController::class, 'universitiesOnly']);
Route::get('faculties', [FacultyController::class, 'index']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);
Route::get('quizzes', [QuizController::class, 'index']);
Route::post('suggest-cars', [QuizController::class, 'suggestCars']);
Route::post('/start-ad', [StartAdController::class, 'store']);
Route::get('/start-ad', [StartAdController::class, 'show']);
Route::post('Help-Request', [HelpRequestController::class, 'store']);

Route::post('calculate-car-installment', [CarInstallmentController::class, 'calculateInstallment']);

Route::apiResource('banners', BannerController::class);
Route::apiResource('videos', VideoController::class);


Route::prefix('auth')->group(function () {
    Route::post('send-otp', [AuthController::class, 'sendOtp']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('/update-phone', [AuthController::class, 'updatephone']);


    Route::middleware('auth:api')->group(function () {
        Route::get('notifications/user', [ApiNotificationController::class, 'getForUser']);
        Route::post('update-profile', [AuthController::class, 'updateProfile']);
        Route::post('/favourites/toggle/{carId}', [FavouriteController::class, 'toggleFavourite']);
        Route::get('/favourites', [FavouriteController::class, 'myFavourites']);
        Route::delete('/favourites/clear', [FavouriteController::class, 'clearFavourites']);
        Route::get('me', [AuthController::class, 'me']);

        Route::post('set-password', [AuthController::class, 'setPassword']);
        Route::post('update-password', [AuthController::class, 'updatePassword']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::delete('delete-account', [AuthController::class, 'deleteAccount']);

        Route::get('saved-searches', [SavedSearchController::class, 'index']);
        Route::post('saved-searches', [SavedSearchController::class, 'store']);
        Route::delete('saved-searches/{id}', [SavedSearchController::class, 'destroy']);


        Route::post('Help-Request', [HelpRequestController::class, 'store']);



        Route::post('/financing-requests', [FinancingRequestController::class, 'store']);
        Route::get('/requests', [FinancingRequestController::class, 'index']);
        Route::post('/cancel-requests', [FinancingRequestController::class, 'cancel']);



    });


});


