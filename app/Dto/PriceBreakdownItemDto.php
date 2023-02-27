<?php
declare(strict_types=1);

namespace App\Dto;

readonly class PriceBreakdownItemDto
{
    public function __construct(public int $pricePerDay, public int $days)
    {
    }
}
