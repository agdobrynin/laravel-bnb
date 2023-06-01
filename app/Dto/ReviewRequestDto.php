<?php
declare(strict_types=1);

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema]
readonly class ReviewRequestDto
{
    public function __construct(
        #[OA\Property(format: 'uuid')]
        public string $id,
        #[OA\Property(minLength: 2)]
        public string $description,
        #[OA\Property(enum: [1, 2, 3, 4, 5])]
        public int    $rating,
    )
    {
    }
}
