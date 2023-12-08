<?php

declare(strict_types=1);

namespace App\ValueObject;

use App\Models\Bookable;
use Carbon\CarbonPeriod;
use OpenApi\Attributes as OA;

#[OA\Schema]
readonly class PriceBreakdownVO
{
    #[OA\Property(format: 'uuid')]
    public string $bookableId;

    #[OA\Property]
    public ?int $totalPrice;

    #[OA\Property(
        property: 'breakdown',
        properties: [
            new OA\Property(
                property: 'weekend',
                ref: PriceBreakdownItemVO::class,
                title: 'Price for weekend days',
                nullable: true,
            ),
            new OA\Property(
                property: 'regular',
                ref: PriceBreakdownItemVO::class,
                title: 'Price for regular days',
                nullable: true,
            ),
        ],
        nullable: true,
    )]
    public ?array $breakdown;

    public function __construct(
        Bookable $bookable,
        #[OA\Property(format: 'date')]
        public string $dateStart,
        #[OA\Property(format: 'date')]
        public string $dateEnd
    ) {
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
