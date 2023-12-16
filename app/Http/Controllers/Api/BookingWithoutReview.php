<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingWithoutReviewResource;
use App\Models\Booking;
use App\Virtual\PaginateMeta;
use App\Virtual\PaginateShort;
use App\Virtual\Response\HeaderSetCookieToken;
use App\Virtual\Response\HttpErrorResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class BookingWithoutReview extends Controller
{
    #[OA\Get(
        path: '/api/booking-without-review',
        summary: 'Get user\'s review without rating',
        tags: ['Review', 'User']
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        headers: [new HeaderSetCookieToken],
        content: new OA\JsonContent(
            type: 'object',
            allOf: [
                new OA\Schema(ref: BookingWithoutReviewResource::class),
                new OA\Schema(ref: PaginateShort::class),
                new OA\Schema(ref: PaginateMeta::class),
            ],
        ),
    )]
    #[HttpErrorResponse(code: 401, description: 'Unauthorized')]
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $perPage = config('without_review_per_page');
        $bookingsWithoutReview = Booking::withoutReviewByUser($request->user())
            ->paginate($perPage)
            ->withQueryString();

        return BookingWithoutReviewResource::collection($bookingsWithoutReview);
    }
}
