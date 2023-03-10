<?php

namespace App\Http\Controllers\Api;

use App\ValueObject\PriceBreakdownVO;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookingDatesRequest;
use App\Models\Bookable;
use Illuminate\Http\JsonResponse;

class CalculateBookingController extends Controller
{
    public function __invoke(Bookable $bookable, BookingDatesRequest $request): JsonResponse
    {
        $data = $request->validated();
        $priceBreakdown = new PriceBreakdownVO($bookable, $data['start'], $data['end']);

        return response()->json([
            'data' => [
                 'calculate' => $priceBreakdown,
            ],
        ]);
    }
}
