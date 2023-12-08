<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Items review without rating',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(
                        property: 'bookable',
                        properties: [
                            new OA\Property(property: 'id', type: 'string', format: 'uuid'),
                            new OA\Property(property: 'title', title: 'Bookable name with category', type: 'string'),
                        ],
                    ),
                    new OA\Property(property: 'start', title: 'Booking start date', type: 'string', format: 'date'),
                    new OA\Property(property: 'end', title: 'Booking end date', type: 'string', format: 'date'),
                    new OA\Property(property: 'price', title: 'Total cost', type: 'integer', minimum: 1),
                    new OA\Property(property: 'reviewKey', title: 'Key for review', type: 'string', format: 'uuid'),
                ],
            ),
        ),
    ]
)]
class BookingWithoutReviewResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'bookable' => [
                'id' => $this->bookable->id,
                'title' => $this->bookable->bookableCategory->name.': '.$this->bookable->title,
            ],
            'start' => $this->start,
            'end' => $this->end,
            'price' => $this->price,
            'reviewKey' => $this->review_key,
        ];
    }
}
