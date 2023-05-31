<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingByReviewKeyResource;
use App\Models\Booking;
use App\Virtual\Response\HttpErrorResponse;
use App\Virtual\Response\HttpNotFoundResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class BookingByReviewController extends Controller
{
    #[OA\Get(
        path: '/api/booking-by-review/{reviewKey}',
        summary: 'Get data for write review',
        security: [['sanctum' => []]],
        tags: ['Review']
    )]
    #[OA\PathParameter(name: 'reviewKey', schema: new OA\Schema(description: 'Review key', type: 'string', format: 'uuid'))]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: BookingByReviewKeyResource::class),
    )]
    #[HttpErrorResponse(code: 403, description: 'Not owner review')]
    #[HttpNotFoundResponse]
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
