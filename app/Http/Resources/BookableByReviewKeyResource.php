<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'id', description: 'Bookable id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'category', description: 'Bookable category name', type: 'string'),
        new OA\Property(property: 'title', description: 'Bookable name', type: 'string'),
    ]
)]
class BookableByReviewKeyResource extends JsonResource
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
            'category' => $this->bookableCategory->name,
        ];
    }
}
