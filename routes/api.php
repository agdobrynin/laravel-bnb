<?php

use App\Http\Controllers\Api\BookableAvailabilityController;
use App\Http\Controllers\Api\BookableCategoryController;
use App\Http\Controllers\Api\BookableController;
use App\Http\Controllers\Api\BookableReviewController;
use App\Http\Controllers\Api\BookingByReviewController;
use App\Http\Controllers\Api\BookingWithoutReview;
use App\Http\Controllers\Api\CalculateBookingController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserController;
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

Route::middleware('auth:sanctum')
    ->get('/user', UserController::class)
    ->name('user.info.api');

Route::get('bookables/categories', BookableCategoryController::class)
    ->name('bookables.categories.api');

Route::apiResource('bookables', BookableController::class)
    ->only(['index', 'show']);

Route::get('bookables/{bookable}/availability', BookableAvailabilityController::class)
    ->name('bookables.availability.show.api');

Route::get('bookables/{bookable}/calculate', CalculateBookingController::class)
    ->name('bookables.calculate.booking.api');

Route::get('bookables/{bookable}/reviews', BookableReviewController::class)
    ->name('bookables.reviews.index.api');

Route::apiResource('reviews', ReviewController::class)
    ->only(['show', 'store'])
    ->names('reviews.api');

Route::get('booking-by-review/{reviewKey}', BookingByReviewController::class)
    ->name('booking.by-review.show.api');

Route::post('checkout', CheckoutController::class)
    ->name('checkout.api');

Route::get('booking-without-review', BookingWithoutReview::class)
    ->middleware(['auth:sanctum'])
    ->name('booking.without.review.api');
