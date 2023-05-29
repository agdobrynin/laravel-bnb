<?php

namespace App\Http\Controllers\Api;

use App\Dto\BookablesFilterDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookableIndexRequest;
use App\Http\Resources\BookableIndexResource;
use App\Http\Resources\BookableShowResource;
use App\Models\Bookable;
use App\Virtual\PaginateMeta;
use App\Virtual\PaginateShort;
use App\Virtual\Response\NotFoundErrorResponse;
use App\Virtual\Response\ValidationErrorResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class BookableController extends Controller
{
    #[OA\Get(
        path: '/bookables',
        description: 'Show bookable list by filter and paginator',
        tags: ['Bookable'],
    )]
    // Path parameters defined in BookableIndexRequest::class
    #[OA\PathParameter(ref: '#/components/parameters/bookableCategoryId')]
    #[OA\PathParameter(ref: '#/components/parameters/priceMin')]
    #[OA\PathParameter(ref: '#/components/parameters/priceMax')]
    #[OA\PathParameter(ref: '#/components/parameters/priceWeekendMin')]
    #[OA\PathParameter(ref: '#/components/parameters/priceWeekendMax')]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(
            type: 'object',
            allOf: [
                new OA\Schema(ref: BookableIndexResource::class),
                new OA\Schema(ref: PaginateShort::class),
                new OA\Schema(ref: PaginateMeta::class),
            ],
        )
    )]
    #[ValidationErrorResponse(description: 'Validation error for input query parameters')]
    public function index(BookableIndexRequest $request): AnonymousResourceCollection
    {
        $filter = new BookablesFilterDto(...$request->validated());

        return BookableIndexResource::collection(
            Bookable::filter($filter)
                ->with(['bookableCategory'])
                ->priceLowToHi()
                ->latest()
                ->paginate(env('PAGINATION_BOOKABLE_LIST_PER_PAGE', 12))
                ->withQueryString()
        );
    }

    #[OA\Get(
        path: '/bookables/{bookable}',
        description: 'Show bookable by bookable id',
        tags: ['Bookable'],
    )]
    #[OA\PathParameter(ref: '#/components/parameters/bookable')]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: BookableShowResource::class),
    )]
    #[NotFoundErrorResponse(description: 'Not found bookable by id')]
    public function show(Bookable $bookable): BookableShowResource
    {
        return new BookableShowResource($bookable);
    }
}
