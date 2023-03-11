<?php

namespace Database\Seeders;

use App\Models\Bookable;
use App\Models\Booking;
use App\Models\PersonAddress;
use App\Models\User;
use App\ValueObject\PriceBreakdownVO;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookingDatesSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        /** @var Collection $personalAddressCollection */
        $personalAddressCollection = PersonAddress::all();
        $userCollection = User::all(['id']);

        Bookable::all()->each(function (Bookable $bookable) use ($personalAddressCollection, $userCollection) {
            /** @var Booking $booking */
            $booking = Booking::factory()->make();
            $booking->id = Str::uuid();
            $booking->review_key = Str::uuid();

            $priceBreakdown = new PriceBreakdownVO(
                $bookable,
                $booking->start->format('Y-m-d'),
                $booking->end->format('Y-m-d')
            );
            $booking->price = $priceBreakdown->totalPrice;
            $personal = $personalAddressCollection->random();
            $booking->personAddress()->associate($personal);
            $booking->user()->associate($userCollection->random());

            $bookings = collect([$booking]);

            for ($i = 0, $max = rand(7, 14); $i < $max; $i++) {
                $start = Carbon::make($bookings->last()->end)->addDays(rand(2, 14));

                $booking = new Booking();
                $booking->id = Str::uuid();
                $booking->review_key = Str::uuid();

                $booking->start = $start;
                $booking->end = (clone $start)->addDays(rand(4, 10));
                $priceBreakdown = new PriceBreakdownVO(
                    $bookable,
                    $booking->start->format('Y-m-d'),
                    $booking->end->format('Y-m-d')
                );
                $booking->price = $priceBreakdown->totalPrice;
                $personal = $personalAddressCollection->random();
                $booking->personAddress()->associate($personal);
                $user = rand(0, 1) ?: null;
                $booking->user()->associate($user);

                $bookings->push($booking);
            }

            $bookable->bookings()->saveMany($bookings);
        });
    }
}
