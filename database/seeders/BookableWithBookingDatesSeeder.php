<?php

namespace Database\Seeders;

use App\Dto\PriceBreakdownDto;
use App\Models\Bookable;
use App\Models\Booking;
use App\Models\PersonAddress;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class BookableWithBookingDatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        /** @var Collection $personalAddressCollection */
        $personalAddressCollection = PersonAddress::factory()->count(25)->create();

        Bookable::factory()
            ->count(50)
            ->create()
            ->each(function (Bookable $bookable) use ($personalAddressCollection) {
                /** @var Booking $booking */
                $booking = Booking::factory()->make();
                $priceBreakdown = new PriceBreakdownDto(
                    $bookable,
                    $booking->start->format('Y-m-d'),
                    $booking->end->format('Y-m-d')
                );
                $booking->price = $priceBreakdown->totalPrice;
                $personal = $personalAddressCollection->random();
                $booking->personAddress()->associate($personal);

                $bookings = collect([$booking]);

                for ($i = 0, $max = rand(3, 6); $i < $max; $i++) {
                    $start = Carbon::make($bookings->last()->end)->addDays(rand(2, 14));

                    $booking = new Booking();

                    $booking->start = $start;
                    $booking->end = (clone $start)->addDays(rand(4, 10));
                    $priceBreakdown = new PriceBreakdownDto(
                        $bookable,
                        $booking->start->format('Y-m-d'),
                        $booking->end->format('Y-m-d')
                    );
                    $booking->price = $priceBreakdown->totalPrice;
                    $personal = $personalAddressCollection->random();
                    $booking->personAddress()->associate($personal);

                    $bookings->push($booking);
                }

                $bookable->bookings()->saveMany($bookings);
            });
    }
}
