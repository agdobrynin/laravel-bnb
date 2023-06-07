<?php

namespace Tests\Feature;

use App\Models\Bookable;
use App\Models\BookableCategory;
use App\Models\Booking;
use App\Models\PersonAddress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class BookingByReviewKeyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get info about booking by review key in table bookings.
     */
    public function testGetBookingInfoByReviewKeyWithUserSuccess()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $booking = $this->makeBooking($user);

        $this->actingAs($user)
            ->getJson('/api/booking-by-review/' . $booking->review_key)
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $booking->id,
                    'start' => $booking->start,
                    'end' => $booking->end,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'bookable' => [
                        'id' => $booking->bookable->id,
                        'title' => $booking->bookable->title,
                        'category' => $booking->bookable->bookableCategory->name,
                    ]
                ],
            ]);
    }

    public function testGetBookingInfoByReviewNotFound()
    {
        $this->getJson('/api/booking-by-review/' . Str::uuid())
            ->assertNotFound();
    }

    public function testGetBookingInfoByReviewNotOwner()
    {
        $booking = $this->makeBooking(User::factory()->create());

        $this->actingAs(User::factory()->create())
            ->getJson('/api/booking-by-review/' . $booking->review_key)
            ->assertForbidden();
    }

    protected function makeBooking(?User $user): Booking
    {
        return BookableCategory::factory()
            ->has(
                Bookable::factory()
                    ->has(
                        Booking::factory()
                            ->afterMaking(fn(Booking $booking) => $booking->user()->associate($user))
                            ->afterMaking(
                                fn(Booking $booking) => $booking->personAddress()->associate(PersonAddress::factory()->create())
                            )
                    )
            )
            ->create()->bookables->first()->bookings->first();
    }
}
