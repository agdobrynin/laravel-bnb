<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Items with dates are not available',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'start', type: 'string', format: 'date'),
                    new OA\Property(property: 'end', type: 'string', format: 'date'),
                ],
            ),
            minItems: 0,
        ),
    ]
)]
class BookingAvailabilityResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'start' => $this->start,
            'end' => $this->end,
        ];
    }
}
