<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'data',
            properties: [
                new OA\Property(property: 'hasReview', description: 'Has review by review key', type: 'boolean'),
            ],
            type: 'object',
        ),
    ],
)]
class ReviewResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'hasReview' => (bool) ($this->id ?? null),
        ];
    }
}
