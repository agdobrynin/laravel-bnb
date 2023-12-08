<?php

namespace Database\Seeders;

use App\Models\Bookable;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewForBookablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bookable::query()->cursor()->each(function (Bookable $bookable) {
            $reviews = [];

            foreach ($bookable->bookings as $booking) {
                $reviews[] = Review::factory()->make([
                    'booking_id' => $booking->id,
                    'id' => $booking->review_key,
                ]);

                $booking->review_key = '';
            }

            $bookable->bookings()->saveMany($bookable->bookings);
            $bookable->reviews()->saveMany($reviews);
        });
    }
}
