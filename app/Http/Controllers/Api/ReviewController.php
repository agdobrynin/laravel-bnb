<?php

namespace App\Http\Controllers\Api;

use App\Dto\ReviewRequestDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Booking;
use App\Models\Review;
use App\Virtual\Parameters\UuidPathParameter;
use App\Virtual\Response\HeaderSetCookieToken;
use App\Virtual\Response\HttpErrorResponse;
use App\Virtual\Response\HttpNotFoundResponse;
use App\Virtual\Response\HttpValidationErrorResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class ReviewController extends Controller
{
    #[OA\Post(
        path: '/api/reviews',
        summary: 'Store new review for bookable object',
        security: [['sanctum' => []]],
        tags: ['Review'],
    )]
    #[OA\RequestBody(
        content: new OA\JsonContent(ref: ReviewRequestDto::class),
    )]
    #[HttpValidationErrorResponse]
    #[HttpNotFoundResponse(description: 'Review not found by review key')]
    #[HttpErrorResponse(code: 403, description: 'Your are not owner this review')]
    #[OA\Response(
        response: 201,
        description: 'Success',
        content: new OA\JsonContent(ref: ReviewResource::class),
    )]
    public function store(ReviewRequest $request)
    {
        if ($request->user()) {
            Validator::validate(
                ['verify-email'=> $request->user()->email_verified_at],
                ['verify-email' => 'required'],
                ['verify-email' => 'Please verify email']);
        }

        $dto = new ReviewRequestDto(...$request->validated());

        if ($booking = Booking::findByReviewKey($dto->id)) {

            if ($booking->user_id !== $request->user()?->id) {
                abort(403, 'Forbidden. Your are not owner this booking');
            }

            $booking->review_key = '';
            $booking->save();

            $review = Review::make((array)$dto);
            $review->bookable_id = $booking->bookable_id;
            $review->booking_id = $booking->id;

            $review->save();

            return new ReviewResource($review);
        }

        abort(404, 'Not found by review id');
    }

    #[OA\Get(
        path: '/api/reviews/{review}',
        summary: 'Check has review by review key.',
        security: [['sanctum' => []]],
        tags: ['Review'],
    )]
    #[UuidPathParameter(name: 'review', description: 'Review key')]
    #[OA\Response(
        response: 200,
        description: 'Success',
        headers: [new HeaderSetCookieToken],
        content: new OA\JsonContent(ref: ReviewResource::class)
    )]
    public function show(string $reviewId): JsonResource
    {
        return new ReviewResource(Review::find($reviewId));
    }
}
