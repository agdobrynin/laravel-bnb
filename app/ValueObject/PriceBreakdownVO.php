<?php
declare(strict_types=1);

namespace App\ValueObject;

use App\Models\Bookable;
use Carbon\CarbonPeriod;

readonly class PriceBreakdownVO
{
    public string $bookableId;
    public ?int $totalPrice;
    /**
     * @var PriceBreakdownItemVO[]
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
            $weekendVO = new PriceBreakdownItemVO($bookable->price_weekend, $weekendDayCount);
            $breakdown['weekend'] = $weekendVO;
            $totalPrice += $weekendVO->totalPrice;
        }

        if ($regularDayCount) {
            $regularVO = new PriceBreakdownItemVO($bookable->price, $regularDayCount);
            $breakdown['regular'] = $regularVO;
            $totalPrice += $regularVO->totalPrice;
        }

        if ($totalPrice && $breakdown) {
            $this->totalPrice = $totalPrice;
            $this->breakdown = $breakdown;
        }
    }
}
