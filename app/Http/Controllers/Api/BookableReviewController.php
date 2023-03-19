<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookableReviewResource;
use App\Models\Bookable;

class BookableReviewController extends Controller
{
    public function __invoke(Bookable $bookable)
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
