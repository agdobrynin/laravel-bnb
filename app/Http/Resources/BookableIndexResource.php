<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Bookable item',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'id', type: 'string', format: 'uuid'),
                    new OA\Property(property: 'title', type: 'string'),
                    new OA\Property(property: 'description', description: 'Short description', type: 'string', maximum: 80),
                    new OA\Property(property: 'price', description: 'Regular price per day', type: 'integer', minimum: 1),
                    new OA\Property(property: 'price_weekend', description: 'Weekend price per day', type: 'integer', minimum: 1),
                    new OA\Property(property: 'category', description: 'Bookable category', type: 'string'),
                ]
            ),
            minItems: 0,
        ),
    ])]
class BookableIndexResource extends JsonResource
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
            'description' => Str::limit($this->description, config('bnb.index_description_short_length')),
            'price' => $this->price,
            'price_weekend' => $this->price_weekend,
            'category' => $this->bookableCategory->name,
        ];
    }
}
