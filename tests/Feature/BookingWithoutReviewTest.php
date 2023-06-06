<?php

namespace Tests\Feature;

use App\Models\Bookable;
use App\Models\BookableCategory;
use App\Models\Booking;
use App\Models\PersonAddress;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingWithoutReviewTest extends TestCase
{
    use RefreshDatabase;

    public function testUnauthorized(): void
    {
        $this->getJson('/api/booking-without-review')
            ->assertUnauthorized();
    }

    public function testNoReview(): void
    {
        $this->actingAs(User::factory()->create())
            ->getJson('/api/booking-without-review')
            ->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJsonStructure([
                'data',
                'links' => ['first', 'last', 'next', 'prev',],
                'meta' => [
                    'current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total',
                    'links' => [['url', 'label', 'active',]],
                ],
            ]);
    }

    public function testTwoReview(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var BookableCategory $category */
        $category = BookableCategory::factory()
            ->has(Bookable::factory())
            ->create();
        /** @var Bookable $bookable */
        $bookable = $category->bookables()->first();

        /** @var Collection<int, Booking> $bookings */
        $bookings = Booking::factory(2, ['price' => 100])->make();
        $bookings->each(function (Booking $booking) use ($bookable, $user) {
            $booking->bookable()->associate($bookable);
            $booking->user()->associate($user);
            $booking->personAddress()->associate(PersonAddress::factory()->create());
            $booking->save();
        });
        // Return collection sorted by start date booking.
        $firstBooking = $bookings->sort(function (Booking $a, Booking $b) {
            $at = strtotime($a->start);
            $bt = strtotime($b->start);

            return $at === $bt ? 0 : ($at > $bt ? 1 : -1);
        })->first();

        $this->actingAs($user)
            ->getJson('/api/booking-without-review')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.total', 2)
            ->assertJsonPath(
                'data.0',
                [
                    'bookable' => [
                        'id' => $bookable->id,
                        'title' => $category->name . ': ' . $bookable->title,
                    ],
                    'start' => $firstBooking->start,
                    'end' => $firstBooking->end,
                    'price' => $firstBooking->price,
                    'reviewKey' => (string)$firstBooking->review_key,
                ]
            );
    }
}
