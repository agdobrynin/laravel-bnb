<?php
declare(strict_types=1);

namespace App\Dto;

readonly class PriceBreakdownItemDto
{
    public int $totalPrice;
    public function __construct(public int $pricePerDay, public int $days)
    {
        $this->totalPrice = $pricePerDay * $this->days;
    }
}
