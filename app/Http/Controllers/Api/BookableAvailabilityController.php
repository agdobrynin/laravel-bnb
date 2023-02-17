<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingAvailabilityResource;
use App\Models\Bookable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookableAvailabilityController extends Controller
{
    public function __invoke(Request $request, Bookable $bookable): AnonymousResourceCollection
    {
        $data = $request->validate([
            'start' => 'required|date_format:Y-m-d|after_or_equal:today',
            'end' => 'required|date_format:Y-m-d|after:start'
        ]);

        return BookingAvailabilityResource::collection($bookable->availableForDate($data['start'], $data['end']));
    }
}
