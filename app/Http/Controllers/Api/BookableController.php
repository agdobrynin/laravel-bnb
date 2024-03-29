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
use App\Virtual\Parameters\UuidPathParameter;
use App\Virtual\Response\HeaderSetCookieToken;
use App\Virtual\Response\HttpNotFoundResponse;
use App\Virtual\Response\HttpValidationErrorResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

class BookableController extends Controller
{
    #[OA\Get(
        path: '/api/bookables',
        summary: 'Show bookable list by filter and paginator',
        tags: ['Bookable'],
    )]
    /**
     * Path parameters defined in BookableIndexRequest::class
     */
    #[OA\QueryParameter(ref: '#/components/parameters/bookableCategoryIdInQuery')]
    #[OA\QueryParameter(ref: '#/components/parameters/priceMinInQuery')]
    #[OA\QueryParameter(ref: '#/components/parameters/priceMaxInQuery')]
    #[OA\QueryParameter(ref: '#/components/parameters/priceWeekendMinInQuery')]
    #[OA\QueryParameter(ref: '#/components/parameters/priceWeekendMaxInQuery')]
    #[OA\Response(
        response: 200,
        description: 'Success',
        headers: [new HeaderSetCookieToken],
        content: new OA\JsonContent(
            type: 'object',
            allOf: [
                new OA\Schema(ref: BookableIndexResource::class),
                new OA\Schema(ref: PaginateShort::class),
                new OA\Schema(ref: PaginateMeta::class),
            ],
        )
    )]
    #[HttpValidationErrorResponse(description: 'Validation error for input query parameters')]
    public function index(BookableIndexRequest $request): AnonymousResourceCollection
    {
        $filter = new BookablesFilterDto(...$request->validated());
        $perPage = config('bnb.index_per_page');

        $bookables = Bookable::displayByFilterWithCategory($filter)
            ->paginate($perPage)
            ->withQueryString();

        return BookableIndexResource::collection($bookables);
    }

    #[OA\Get(
        path: '/api/bookables/{bookable}',
        summary: 'Show bookable by bookable id',
        tags: ['Bookable'],
    )]
    #[UuidPathParameter(name: 'bookable', description: 'Bookable Id')]
    #[OA\Response(
        response: 200,
        description: 'Success',
        headers: [new HeaderSetCookieToken],
        content: new OA\JsonContent(ref: BookableShowResource::class),
    )]
    #[HttpNotFoundResponse(description: 'Not found bookable by id')]
    public function show(Bookable $bookable): JsonResource
    {
        return new BookableShowResource($bookable);
    }
}
