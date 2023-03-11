<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        if ($request->user()) {
            Validator::validate(
                ['verify-email'=> $request->user()->email_verified_at],
                ['verify-email' => 'required'],
                ['verify-email' => 'Please verify email']);
        }

        $data = $request->validate([
            'id' => 'required|size:36|unique:reviews',
            'description' => 'required|min:2',
            'rating' => 'required|in:1,2,3,4,5'
        ]);

        if ($booking = Booking::findByReviewKey($data['id'])) {

            if ($booking->user_id !== $request->user()?->id) {
                abort(403, 'Forbidden. Your are not owner this booking');
            }

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
