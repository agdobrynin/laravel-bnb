<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Info about authenticated user',
    properties: [
        new OA\Property(property: 'email', type: 'string', format: 'email'),
        new OA\Property(property: 'name', description: 'Full user name', type: 'string'),
        new OA\Property(property: 'newReviewCount', description: 'Count of reviews waite user comment', type: 'integer'),
        new OA\Property(property: 'isVerified', description: 'User account is verified', type: 'boolean'),
    ],
)]
class FetchUserResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request)
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'newReviewCount' => $this->bookings()->where('review_key', '!=', '')->count(),
            'isVerified' => !is_null($this->email_verified_at),
        ];
    }
}
