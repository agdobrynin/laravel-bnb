<?php

declare(strict_types=1);

namespace App\Virtual\Models;

use OpenApi\Attributes as OA;

#[OA\Schema]
final class EmailPassword
{
    #[OA\Property(property: 'email', format: 'email')]
    public string $email;

    #[OA\Property]
    public string $password;
}
