<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Bookable;
use App\Models\Booking;
use App\Models\PersonAddress;
use App\Models\User;

interface BookingRepositoryInterface
{
    public function create(
        Bookable $bookable,
        string $dateForm,
        string $dateTo,
        int $totalPrice,
        PersonAddress $personAddress,
        ?User $user = null
    ): Booking;

    public function make(
        int $totalPrice,
        string $dateForm,
        string $dateTo
    ): Booking;
}
