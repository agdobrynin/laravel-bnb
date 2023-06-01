<?php
declare(strict_types=1);

namespace App\ValueObject;

use OpenApi\Attributes as OA;

#[OA\Schema]
readonly class PriceBreakdownItemVO
{
    #[OA\Property]
    public int $totalPrice;
    public function __construct(
        #[OA\Property(title: 'Price per daya')]
        public int $pricePerDay,
        #[OA\Property(title: 'How many days bookign appatment')]
        public int $days
    )
    {
        $this->totalPrice = $pricePerDay * $this->days;
    }
}
