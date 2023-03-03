<?php
declare(strict_types=1);

namespace App\Dto;

use App\Models\Bookable;
use Carbon\CarbonPeriod;

readonly class PriceBreakdownDto
{
    public string $bookableId;
    public ?int $totalPrice;
    /**
     * @var PriceBreakdownItemDto[]
     */
    public ?array $breakdown;

    public function __construct(Bookable $bookable, public string $dateStart, public string $dateEnd)
    {
        $this->bookableId = $bookable->id;

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

        $totalPrice = 0;
        $breakdown = [];

        if ($weekendDayCount) {
            $weekendDto = new PriceBreakdownItemDto($bookable->price_weekend, $weekendDayCount);
            $breakdown['weekend'] = $weekendDto;
            $totalPrice += $weekendDto->totalPrice;
        }

        if ($regularDayCount) {
            $regularDto = new PriceBreakdownItemDto($bookable->price, $regularDayCount);
            $breakdown['regular'] = $regularDto;
            $totalPrice += $regularDto->totalPrice;
        }

        if ($totalPrice && $breakdown) {
            $this->totalPrice = $totalPrice;
            $this->breakdown = $breakdown;
        }
    }
}
