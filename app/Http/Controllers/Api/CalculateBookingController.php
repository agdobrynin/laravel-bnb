<?php

namespace App\Http\Controllers\Api;

use App\Dto\PriceBreakdownDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookingDatesRequest;
use App\Models\Bookable;
use Illuminate\Http\JsonResponse;

class CalculateBookingController extends Controller
{
    public function __invoke(Bookable $bookable, BookingDatesRequest $request): JsonResponse
    {
        $data = $request->validated();
        $priceBreakdownDto = new PriceBreakdownDto($bookable, $data['start'], $data['end']);

        return response()->json([
            'data' => [
                 'breakdown' => $priceBreakdownDto,
            ],
        ]);
    }
}
