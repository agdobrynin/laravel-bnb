<?php

namespace Tests\Feature;

use App\Mail\BookingMade;
use App\Models\Bookable;
use App\Models\BookableCategory;
use App\Models\Booking;
use App\Models\PersonAddress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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
        Mail::fake();

        /** @var User $user */
        $user = User::factory()->create();
        /** @var BookableCategory $category */
        $category = BookableCategory::factory()->has(
            Bookable::factory()
        )->create();
        /** @var Bookable $bookable */
        $bookable = $category->bookables()->first();

        /** @var Booking $booking */
        $booking = Booking::factory()->make([
            'price' => 100,
        ]);

        $booking->bookable()->associate($bookable);
        $booking->personAddress()->associate(PersonAddress::factory()->create());
        $booking->user()->associate($user);

        $booking->save();
        $reviewKey = $booking->review_key;
        $response = $this->actingAs($user)
            ->getJson('/api/booking-by-review/' . $reviewKey);

        $response->assertOk()
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
                        'id' => $bookable->id,
                        'title' => $bookable->title,
                        'category' => $bookable->bookableCategory()->first()->name,
                    ]
                ],
            ]);

        Mail::assertSent(BookingMade::class);
    }

    public function testGetBookingInfoByReviewNotFound()
    {
        $this->getJson('/api/booking-by-review/' . Str::uuid())
            ->assertNotFound();
    }

    public function testGetBookingInfoByReviewNotOwner()
    {
        Mail::fake();

        /** @var BookableCategory $category */
        $category = BookableCategory::factory()
            ->has(Bookable::factory())
            ->create();
        /** @var Bookable $bookable */
        $bookable = $category->bookables()->first();
        /** @var Booking $booking */
        $booking = Booking::factory(['price' => 100])->make();
        $booking->bookable()->associate($bookable);
        $booking->user()->associate(User::factory()->create());
        $booking->personAddress()->associate(PersonAddress::factory()->create());
        $booking->save();

        Mail::assertSent(BookingMade::class);

        $this->actingAs(User::factory()->create())
            ->getJson('/api/booking-by-review/' . $booking->review_key)
            ->assertForbidden();
    }
}
