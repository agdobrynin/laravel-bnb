<?php
declare(strict_types=1);

namespace App\ValueObject;

readonly class PriceBreakdownItemVO
{
    public int $totalPrice;
    public function __construct(public int $pricePerDay, public int $days)
    {
        $this->totalPrice = $pricePerDay * $this->days;
    }
}
