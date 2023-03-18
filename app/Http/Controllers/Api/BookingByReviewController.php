<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingByReviewKeyResource;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingByReviewController extends Controller
{
    public function __invoke(string $reviewKey, Request $request)
    {
        if ($booking = Booking::findByReviewKey($reviewKey)) {
            if ($booking->user && $request->user()?->id !== $booking->user_id) {
                abort(403, 'Your are not owner this review');
            }

            return  new BookingByReviewKeyResource($booking);
        }

        abort(404, 'Booking not found by review key');
    }
}
