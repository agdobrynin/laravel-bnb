<?php

namespace App\Http\Controllers\Api;

use App\Dto\BookablesFilterDto;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookableIndexResource;
use App\Http\Resources\BookableShowResource;
use App\Models\Bookable;
use Illuminate\Http\Request;

class BookableController extends Controller
{
    public function index(Request $request)
    {
        $filter = new BookablesFilterDto(
            $request->get('bookableCategoryId'),
            $request->get('priceMin'),
            $request->get('priceMax'),
            $request->get('priceWeekendMin'),
            $request->get('priceWeekendMax'),
        );

        return BookableIndexResource::collection(
            Bookable::filter($filter)
                ->with(['bookableCategory'])
                ->priceLowToHi()
                ->latest()
                ->paginate(12)
                ->onEachSide(1)
                ->withQueryString()
        );
    }

    public function show(Bookable $bookable)
    {
        return new BookableShowResource($bookable);
    }
}
