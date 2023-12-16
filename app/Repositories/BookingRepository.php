<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Bookable;
use App\Models\Booking;
use App\Models\PersonAddress;
use App\Models\User;

class BookingRepository implements Contracts\BookingRepositoryInterface
{
    public function create(
        Bookable $bookable,
        string $dateForm,
        string $dateTo,
        int $totalPrice,
        PersonAddress $personAddress,
        ?User $user = null
    ): Booking {
        $booking = $this->make($totalPrice, $dateForm, $dateTo);

        $booking->bookable()->associate($bookable);
        $booking->personAddress()->associate($personAddress);

        if ($user) {
            $booking->user()->associate($user);
        }

        $booking->save();

        return $booking;
    }

    public function make(int $totalPrice, string $dateForm, string $dateTo): Booking
    {
        return Booking::make(['price' => $totalPrice, 'start' => $dateForm, 'end' => $dateTo]);
    }
}
