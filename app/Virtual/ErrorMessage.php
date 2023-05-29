<?php
declare(strict_types=1);

namespace App\Virtual;

use OpenApi\Attributes as OA;

#[OA\Schema(title: 'Error message')]
class ErrorMessage
{
    #[OA\Property]
    public string $message;
}
