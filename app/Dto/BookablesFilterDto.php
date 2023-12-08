<?php

declare(strict_types=1);

namespace App\Dto;

readonly class BookablesFilterDto
{
    public function __construct(
        public ?string $bookableCategoryId = null,
        public ?int $priceMin = null,
        public ?int $priceMax = null,
        public ?int $priceWeekendMin = null,
        public ?int $priceWeekendMax = null,
    ) {
    }
}
