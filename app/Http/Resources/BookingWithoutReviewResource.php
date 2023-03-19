<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingWithoutReviewResource extends JsonResource
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
            'bookable' => [
                'id' => $this->bookable->id,
                'title' => $this->bookable->bookableCategory->name.': '. $this->bookable->title,
            ],
            'start' => $this->start,
            'end' => $this->end,
            'price' => $this->price,
            'reviewKey' => $this->review_key,
        ];
    }
}
