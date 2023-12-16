<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\PersonAddress;

interface PersonAddressRepositoryInterface
{
    public function create(array $data): PersonAddress;
}
