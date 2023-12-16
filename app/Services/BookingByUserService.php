<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\CheckoutRequestDto;
use App\Models\User;
use App\Repositories\Contracts\BookableRepositoryInterface;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\PersonAddressRepositoryInterface;
use App\ValueObject\PriceBreakdownVO;
use Illuminate\Support\Collection;

class BookingByUserService
{
    public function __construct(
        protected PersonAddressRepositoryInterface $personAddressRepository,
        protected BookableRepositoryInterface $bookableRepository,
        protected BookingRepositoryInterface $bookingRepository,
    ) {
    }

    /**
     * @template TValue of \App\Models\Booking
     *
     * @return \Illuminate\Support\Collection<int, TValue>
     */
    public function handle(CheckoutRequestDto $dto, ?User $user): Collection
    {
        $personAddress = $this->personAddressRepository->create((array) $dto->person);

        $bookingCollection = collect();

        foreach ($dto->bookings as $checkoutBookingDto) {
            $bookable = $this->bookableRepository->findWithCategory($checkoutBookingDto->bookable_id);
            $priceBreakdown = new PriceBreakdownVO($bookable, $checkoutBookingDto->start, $checkoutBookingDto->end);

            $booking = $this->bookingRepository->create(
                $bookable,
                $checkoutBookingDto->start,
                $checkoutBookingDto->end,
                $priceBreakdown->totalPrice,
                $personAddress,
                $user,
            );

            $bookingCollection->add($booking);
        }

        return $bookingCollection;
    }
}
