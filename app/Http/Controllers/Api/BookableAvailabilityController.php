<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingDatesRequest;
use App\Http\Resources\BookingAvailabilityResource;
use App\Models\Bookable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookableAvailabilityController extends Controller
{
    public function __invoke(Bookable $bookable, BookingDatesRequest $request): AnonymousResourceCollection
    {
        $data = $request->validated();

        return BookingAvailabilityResource::collection($bookable->availableForDate($data['start'], $data['end']));
    }
}
