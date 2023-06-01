<?php

namespace App\Http\Controllers\Api;

use App\Dto\DateRangeDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookingDatesRequest;
use App\Http\Resources\BookingAvailabilityResource;
use App\Models\Bookable;
use App\Virtual\Parameters\UuidPathParameter;
use App\Virtual\Response\HeaderSetCookieToken;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

class BookableAvailabilityController extends Controller
{
    #[OA\Get(
        path: '/api/bookables/{bookable}/availability',
        summary: 'Check abilities date for booking',
        tags: ['Booking'],
    )]
    #[UuidPathParameter(name: 'bookable', description: 'Bookable Id')]
    /**
     * Describe parameters in BookingDatesRequest::class
     */
    #[OA\QueryParameter(description: 'Date start for ability booking', ref: '#/components/parameters/dateStartInQuery')]
    #[OA\QueryParameter(description: 'Date end for ability booking', ref: '#/components/parameters/dateEndInQuery')]
    #[OA\Response(
        response: 200,
        description: 'Dates are not available for booking. If the list is empty, then the booking is available',
        headers: [new HeaderSetCookieToken],
        content: new OA\JsonContent(ref: BookingAvailabilityResource::class)
    )]
    public function __invoke(Bookable $bookable, BookingDatesRequest $request): JsonResource
    {
        $dto = new DateRangeDto(...$request->validated());

        return BookingAvailabilityResource::collection($bookable->availableForDate($dto->start, $dto->end));
    }
}
