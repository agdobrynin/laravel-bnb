<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutSuccessResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'start' => $this->start,
            'end' => $this->end,
            'price' => $this->price,
            'bookable' => new CheckoutSuccessBookableResource($this->bookable)
        ];
    }
}
