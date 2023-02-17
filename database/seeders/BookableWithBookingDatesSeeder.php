<?php

namespace Database\Seeders;

use App\Models\Bookable;
use App\Models\Booking;
use Carbon\Carbon;
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
        Bookable::factory()
            ->count(50)
            ->create()
            ->each(function (Bookable $bookable) {
                $booking = Booking::factory()->make();
                $bookings = collect([$booking]);

                for ($i = 0, $max = rand(3, 6); $i < $max; $i++) {
                    $start = Carbon::make($bookings->last()->end)->addDays(rand(2, 14));

                    $booking = new Booking();

                    $booking->start = $start;
                    $booking->end = (clone $start)->addDays(rand(4, 10));

                    $bookings->push($booking);
                }

                $bookable->booking()->saveMany($bookings);
            });
    }
}
