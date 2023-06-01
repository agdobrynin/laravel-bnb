<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    required: ['id', 'title'],
    properties: [
        new OA\Property(property: 'id', description: 'Bookable Id',type: 'string', format: 'uuid'),
        new OA\Property(property: 'title', description: 'Bookable name',type: 'string'),
    ]
)]
class CheckoutSuccessBookableResource extends JsonResource
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
        ];
    }
}
