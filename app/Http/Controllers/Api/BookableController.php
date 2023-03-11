<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookableIndexResource;
use App\Http\Resources\BookableShowResource;
use App\Models\Bookable;

class BookableController extends Controller
{
    public function index()
    {
        return BookableIndexResource::collection(
            Bookable::query()
                ->paginate(10)
                ->onEachSide(1)
                ->withQueryString()
        );
    }

    public function show(Bookable $bookable)
    {
        return new BookableShowResource($bookable);
    }
}
