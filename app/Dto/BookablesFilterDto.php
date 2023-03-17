<?php
declare(strict_types=1);

namespace App\Dto;

readonly class BookablesFilterDto
{
    public function __construct(
        public ?string $bookableCategoryId,
        public ?int $priceMin,
        public ?int $priceMax,
        public ?int $priceWeekendMin,
        public ?int $priceWeekendMax
    )
    {
    }
}
