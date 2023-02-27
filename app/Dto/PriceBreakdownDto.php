<?php
declare(strict_types=1);

namespace App\Dto;

use App\Models\Bookable;
use Carbon\CarbonPeriod;

readonly class PriceBreakdownDto
{
    public ?PriceBreakdownItemDto $weekend;
    public ?PriceBreakdownItemDto $regular;

    public function __construct(Bookable $bookable, public string $dateStart, public string $dateEnd)
    {
        $carbonPeriod = CarbonPeriod::create($dateStart, $dateEnd);
        $weekendDayCount = 0;
        $regularDayCount = 0;

        foreach ($carbonPeriod as $date) {
            if ($date->isWeekend()) {
                $weekendDayCount++;
            } else {
                $regularDayCount++;
            }
        }

        if ($weekendDayCount) {
            $this->weekend = new PriceBreakdownItemDto($bookable->price_weekend, $weekendDayCount);
        }

        if ($regularDayCount) {
            $this->regular = new PriceBreakdownItemDto($bookable->price, $regularDayCount);
        }
    }
}
