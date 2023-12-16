<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingByReviewRequest;
use App\Http\Resources\BookingByReviewKeyResource;
use App\Models\Booking;
use App\Virtual\Parameters\UuidPathParameter;
use App\Virtual\Response\HeaderSetCookieToken;
use App\Virtual\Response\HttpErrorResponse;
use App\Virtual\Response\HttpNotFoundResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

class BookingByReviewController extends Controller
{
    #[OA\Get(
        path: '/api/booking-by-review/{reviewKey}',
        summary: 'Get data for write review',
        security: [['sanctum' => []]],
        tags: ['Review']
    )]
    #[UuidPathParameter(name: 'reviewKey', description: 'Review key')]
    #[OA\Response(
        response: 200,
        description: 'Success',
        headers: [new HeaderSetCookieToken],
        content: new OA\JsonContent(ref: BookingByReviewKeyResource::class),
    )]
    #[HttpErrorResponse(code: 403, description: 'Not owner review')]
    #[HttpNotFoundResponse]
    public function __invoke(Booking $booking, BookingByReviewRequest $request): JsonResource
    {
        return  new BookingByReviewKeyResource($booking);
    }
}
