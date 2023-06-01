<?php
declare(strict_types=1);

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema]
readonly class CheckoutPersonDto
{
    public function __construct(
        #[OA\Property]
        public string $first_name,
        #[OA\Property]
        public string $last_name,
        #[OA\Property]
        public string $address,
        #[OA\Property(pattern: '^([0-9]{6,11})$')]
        public ?string $phone,
        #[OA\Property(format: 'email')]
        public string $email,
    )
    {
    }
}
