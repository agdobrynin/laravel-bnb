<?php

namespace App\Repositories;

use App\Models\PersonAddress;
use App\Repositories\Contracts\PersonAddressRepositoryInterface;

class PersonAddressRepository implements PersonAddressRepositoryInterface
{
    public function create(array $data): PersonAddress
    {
        return PersonAddress::create($data);
    }
}
