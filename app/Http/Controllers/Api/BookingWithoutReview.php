<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingWithoutReviewResource;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookingWithoutReview extends Controller
{
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        return BookingWithoutReviewResource::collection(
            Booking::query()
                ->where('review_key', '!=', '')
                ->where('user_id', $request->user()->id)
                ->with('bookable.bookableCategory')
                ->orderBy('start')
                ->paginate(env('PAGINATION_BOOKING_WITHOUT_REVIEW_PER_PAGE', 10))
                ->withQueryString()
        );
    }
}
