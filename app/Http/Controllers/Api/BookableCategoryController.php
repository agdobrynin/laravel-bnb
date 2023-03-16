<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookableCategoriesResource;
use App\Models\BookableCategory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookableCategoryController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {
        return BookableCategoriesResource::collection(BookableCategory::all());
    }
}
