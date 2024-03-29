<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookableReviewResource;
use App\Models\Bookable;
use App\Virtual\PaginateMeta;
use App\Virtual\PaginateShort;
use App\Virtual\Parameters\UuidPathParameter;
use App\Virtual\Response\HeaderSetCookieToken;
use App\Virtual\Response\HttpNotFoundResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class BookableReviewController extends Controller
{
    #[OA\Get(
        path: '/api/bookables/{bookable}/reviews',
        summary: 'Get bookable list review with pagination',
        tags: ['Review'],
    )]
    #[UuidPathParameter(name: 'bookable', description: 'Bookable Id')]
    #[OA\Response(
        response: 200,
        description: 'Success',
        headers: [new HeaderSetCookieToken],
        content: new OA\JsonContent(
            type: 'object',
            allOf: [
                new OA\Schema(ref: BookableReviewResource::class),
                new OA\Schema(ref: PaginateShort::class),
                new OA\Schema(ref: PaginateMeta::class),
            ],
        ),
    )]
    #[HttpNotFoundResponse(description: 'Not found bookable by id')]
    public function __invoke(Bookable $bookable): AnonymousResourceCollection
    {
        $perPage = config('bnb.review_per_page');

        return BookableReviewResource::collection(
            $bookable->reviews()
                ->latest()
                ->with('booking.user')
                ->paginate($perPage)
                ->withPath(sprintf('/bookable/%s', $bookable->id))
                ->withQueryString()
        );
    }
}
