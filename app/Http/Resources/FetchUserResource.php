<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
