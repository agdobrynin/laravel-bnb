<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'data',
            properties: [
                new OA\Property(property: 'id', description: 'Booking id', type: 'string', format: 'uuid'),
                new OA\Property(property: 'start', description: 'Booking start date', type: 'string', format: 'data'),
                new OA\Property(property: 'end', description: 'Booking end date', type: 'string', format: 'data'),
                new OA\Property(
                    property: 'user',
                    ref: ReviewerResource::class,
                    description: 'Reviver user or anonymous',
                    type: 'object',
                ),
                new OA\Property(
                    property: 'bookable',
                    ref: BookableByReviewKeyResource::class,
                    description: 'Info about Bookable object',
                    type: 'object',
                ),
            ],
            type: 'object',
        ),
    ]
)]
class BookingByReviewKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'start' => $this->start,
            'end' => $this->end,
            'user' => $this->user->id ? new ReviewerResource($this->user) : null,
            'bookable' => new BookableByReviewKeyResource($this->bookable),
        ];
    }
}
