<?php

declare(strict_types=1);

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema]
readonly class CheckoutBookingDto
{
    public function __construct(
        #[OA\Property(description: 'From date booking')]
        public string $start,
        #[OA\Property(description: 'To date booking')]
        public string $end,
        #[OA\Property(description: 'Bookable Id', format: 'uuid')]
        public string $bookable_id,
    ) {
    }
}
