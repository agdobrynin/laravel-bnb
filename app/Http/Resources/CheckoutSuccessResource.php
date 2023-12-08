<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    required: ['data'],
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                required: ['start', 'end', 'price', 'reviewKey', 'bookable'],
                properties: [
                    new OA\Property(property: 'start', type: 'string', format: 'date'),
                    new OA\Property(property: 'end', type: 'string', format: 'date'),
                    new OA\Property(property: 'price', type: 'integer', minimum: 1),
                    new OA\Property(property: 'reviewKey', description: 'Review key for this booking', type: 'string', format: 'uuid'),
                    new OA\Property(
                        property: 'bookable',
                        ref: CheckoutSuccessBookableResource::class,
                        description: 'Info about bookable'
                    ),
                ],
                minItems: 1,
            ),
        ),
    ]
)]
class CheckoutSuccessResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'start' => $this->start,
            'end' => $this->end,
            'price' => $this->price,
            'reviewKey' => $this->review_key,
            'bookable' => new CheckoutSuccessBookableResource($this->bookable),
        ];
    }
}
