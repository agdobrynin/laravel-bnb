<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingDatesRequest;
use App\Models\Bookable;
use App\ValueObject\PriceBreakdownVO;
use App\Virtual\Parameters\UuidPathParameter;
use App\Virtual\Response\HeaderSetCookieToken;
use App\Virtual\Response\HttpNotFoundResponse;
use App\Virtual\Response\HttpValidationErrorResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CalculateBookingController extends Controller
{
    #[OA\Get(
        path: '/api/bookables/{bookable}/calculate',
        summary: 'Booking price calculate',
        tags: ['Booking']
    )]
    #[UuidPathParameter(name: 'bookable', description: 'Bookable Id')]
    /**
     * Describe parameters in BookingDatesRequest::class
     */
    #[OA\QueryParameter(description: 'Date start for booking', ref: '#/components/parameters/dateStartInQuery')]
    #[OA\QueryParameter(description: 'Date end for booking', ref: '#/components/parameters/dateEndInQuery')]
    #[OA\Response(
        response: 200,
        description: 'Success',
        headers: [new HeaderSetCookieToken],
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    properties: [
                        new OA\Property(
                            property: 'calculate',
                            ref: PriceBreakdownVO::class,
                        ),
                    ],
                    type: 'object',
                ),
            ],
        )
    )]
    #[HttpNotFoundResponse]
    #[HttpValidationErrorResponse]
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
