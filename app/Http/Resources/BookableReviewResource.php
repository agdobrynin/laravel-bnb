<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Review item',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'id', type: 'string', format: 'uuid'),
                    new OA\Property(property: 'description', description: 'Review description', type: 'string'),
                    new OA\Property(property: 'rating', description: 'Bookable rating', type: 'integer', maximum: 5, minimum: 1),
                    new OA\Property(property: 'createdAt', description: 'Review created date', type: 'string', format: 'date'),
                    new OA\Property(
                        property: 'user',
                        ref: UserReviewResource::class,
                        description: 'Reviewer',
                        type: 'object',
                        nullable: true,
                    ),
                ]
            ),
            minItems: 0,
        )
    ]
)]
class BookableReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'rating' => $this->rating,
            'createdAt' => (string)$this->created_at,
            'user' => $this->booking->user->id ? new UserReviewResource($this->booking->user) : null,
        ];
    }
}
