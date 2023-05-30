<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookableReviewResource;
use App\Models\Bookable;
use App\Virtual\PaginateMeta;
use App\Virtual\PaginateShort;
use App\Virtual\Response\NotFoundErrorResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class BookableReviewController extends Controller
{
    #[OA\Get(
        path: '/bookables/{bookable}/reviews',
        summary: 'Get bookable list review with pagination',
        tags: ['Review'],
    )]
    #[OA\PathParameter(ref: '#/components/parameters/bookable')]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(
            type: 'object',
            allOf: [
                new OA\Schema(ref: BookableReviewResource::class),
                new OA\Schema(ref: PaginateShort::class),
                new OA\Schema(ref: PaginateMeta::class),
            ],
        ),
    )]
    #[NotFoundErrorResponse(description: 'Not found bookable by id')]
    public function __invoke(Bookable $bookable): AnonymousResourceCollection
    {
        $perPage = env('PAGINATION_REVIEW_LIST_PER_PAGE', 5);

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
