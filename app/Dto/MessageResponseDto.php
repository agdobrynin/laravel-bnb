<?php
declare(strict_types=1);

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(title: 'Message')]
readonly class MessageResponseDto
{
    public function __construct(
        #[OA\Property]
        public string $message
    )
    {
    }
}
