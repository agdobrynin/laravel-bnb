<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookableCategoriesResource;
use App\Models\BookableCategory;
use App\Virtual\Response\HeaderSetCookieToken;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use OpenApi\Attributes as OA;

class BookableCategoryController extends Controller
{
    #[OA\Get(
        path: '/api/bookables/categories',
        summary: 'Get bookable categories',
        tags: ['Bookable'],
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        headers: [new HeaderSetCookieToken],
        content: new OA\JsonContent(ref: BookableCategoriesResource::class)
    )]
    public function __invoke(): JsonResource
    {
        $ttl = (int) config('bnb.cache_ttl_category');

        $dictionary = Cache::remember(
            BookableCategory::class,
            $ttl,
            static fn () => BookableCategory::all()
        );

        return BookableCategoriesResource::collection($dictionary);
    }
}
