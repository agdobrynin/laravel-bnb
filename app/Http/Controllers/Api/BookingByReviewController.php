<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingByReviewKeyResource;
use App\Models\Booking;

class BookingByReviewController extends Controller
{
    public function __invoke(string $reviewKey)
    {
        if ($booking = Booking::findByReviewKey($reviewKey)) {
            return  new BookingByReviewKeyResource($booking);
        }

        abort(404, 'Booking not found by review key');
    }
}
