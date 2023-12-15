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

Route::middleware('auth:sanctum')
    ->get('/user', UserController::class)
    ->name('user.info.api');

Route::get('bookables/categories', BookableCategoryController::class)
    ->name('bookables.categories.api');

Route::apiResource('bookables', BookableController::class)
    ->only(['index', 'show']);

Route::prefix('bookables/{bookable}')->name('bookables.')->group(function () {
    Route::get('/availability', BookableAvailabilityController::class)
        ->name('availability.show.api');

    Route::get('/calculate', CalculateBookingController::class)
        ->name('calculate.booking.api');

    Route::get('/reviews', BookableReviewController::class)
        ->name('reviews.index.api');
});

Route::apiResource('reviews', ReviewController::class)
    ->only(['show', 'store'])
    ->names('reviews.api');

Route::name('booking.')->group(function () {
    Route::get('booking-by-review/{reviewKey}', BookingByReviewController::class)
        ->name('by-review.show.api');

    Route::get('booking-without-review', BookingWithoutReview::class)
        ->middleware(['auth:sanctum'])
        ->name('without.review.api');
});

Route::post('checkout', CheckoutController::class)
    ->name('checkout.api');
