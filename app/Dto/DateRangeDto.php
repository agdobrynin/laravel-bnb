<?php

declare(strict_types=1);

namespace App\Dto;

readonly class DateRangeDto
{
    public function __construct(public string $start, public string $end)
    {
    }
}
