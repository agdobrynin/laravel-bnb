<?php

use App\Http\Controllers\Api\BookableAvailabilityController;
use App\Http\Controllers\Api\BookableCategoryController;
use App\Http\Controllers\Api\BookableController;
use App\Http\Controllers\Api\CalculateBookingController;
use App\Http\Controllers\Api\BookableReviewController;
use App\Http\Controllers\Api\BookingByReviewController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Resources\FetchUserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return new FetchUserResource($request->user());
});

Route::get('bookables/categories', BookableCategoryController::class)
    ->name('bookables.categories');

Route::apiResource('bookables', BookableController::class)
    ->only(['index', 'show']);

Route::get('bookables/{bookable}/availability', BookableAvailabilityController::class)
    ->name('bookables.availability.show');

Route::get('bookables/{bookable}/calculate', CalculateBookingController::class)
    ->name('bookables.calculate.booking');

Route::get('bookables/{bookable}/reviews', BookableReviewController::class)
    ->name('bookables.reviews.index');

Route::apiResource('reviews', ReviewController::class)
    ->only(['show', 'store']);

Route::get('booking-by-review/{reviewKey}', BookingByReviewController::class)
    ->name('booking.by-review.show');

Route::post('checkout', CheckoutController::class)
    ->name('checkout');
