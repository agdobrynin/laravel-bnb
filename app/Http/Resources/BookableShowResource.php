<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(properties: [
    new OA\Property(
        property: 'data',
        type: 'array',
        items: new OA\Items(
            properties: [
                new OA\Property(property: 'id', type: 'string', format: 'uuid'),
                new OA\Property(property: 'title', type: 'string'),
                new OA\Property(property: 'description', description: 'Short description', type: 'string'),
                new OA\Property(property: 'price', description: 'Regular price per day', type: 'integer', minimum: 1),
                new OA\Property(property: 'price_weekend', description: 'Weekend price per day', type: 'integer', minimum: 1),
                new OA\Property(property: 'category', description: 'Bookable category', type: 'string'),
            ]
        ),
        maxItems: 1,
        minItems: 1,
    ),
])]
class BookableShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'price_weekend' => $this->price_weekend,
            'category' => $this->bookableCategory->name,
        ];
    }
}
