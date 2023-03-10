<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookableReviewResource;
use App\Models\Bookable;

class BookableReviewController extends Controller
{
    public function __invoke(Bookable $bookable)
    {
        return BookableReviewResource::collection(
            $bookable->reviews()
                ->latest()
                ->with('booking.user')
                ->paginate(5)
                ->withPath(sprintf('/bookable/%s', $bookable->id))
                ->withQueryString()
        );
    }
}
