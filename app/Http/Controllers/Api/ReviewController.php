<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|size:36|unique:reviews',
            'description' => 'required|min:2',
            'rating' => 'required|in:1,2,3,4,5'
        ]);

        if ($booking = Booking::findByReviewKey($data['id'])) {
            $booking->review_key = '';
            $booking->save();

            $review = Review::make($data);
            $review->bookable_id = $booking->bookable_id;
            $review->booking_id = $booking->id;

            $review->save();

            return new ReviewResource($review);
        }

        abort(404, 'Not found by review id');
    }

    public function show(string $reviewId): ReviewResource
    {
        return new ReviewResource(Review::find($reviewId));
    }
}
