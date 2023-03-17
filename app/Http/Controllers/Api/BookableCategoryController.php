<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookableCategoriesResource;
use App\Models\BookableCategory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class BookableCategoryController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {
        $dictionary = Cache::remember(
            BookableCategory::class, (int)env('CACHE_TTL_DICTIONARY', 300),
            fn() => BookableCategory::all()
        );

        return BookableCategoriesResource::collection($dictionary);
    }
}
