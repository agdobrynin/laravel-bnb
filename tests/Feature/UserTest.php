<?php

namespace Tests\Feature;

use App\Mail\BookingMade;
use App\Models\Bookable;
use App\Models\BookableCategory;
use App\Models\Booking;
use App\Models\PersonAddress;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    public function testUnauthorized()
    {
        $response = $this->getJson('/api/user');

        $response->assertUnauthorized();
    }

    public function testUserInfoDefault()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson('/api/user');

        $response->assertOk()
            ->assertJsonStructure([
                'email',
                'name',
                'newReviewCount',
                'isVerified',
            ])
            ->assertJson([
                'email' => $user->email,
                'name' => $user->name,
                'newReviewCount' => 0,
                'isVerified' => true,
            ]);
    }

    public function testUserInfoWithSomeData()
    {
        Mail::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $category = BookableCategory::factory()->create();
        /** @var Bookable $bookable */
        $bookable = Bookable::factory()->make();
        $bookable->bookableCategory()->associate($category);
        $bookable->save();

        /** @var Booking $booking */
        $booking = Booking::factory()->make([
            'start' => Carbon::now()->addDay()->format('Y-m-d'),
            'end' => Carbon::now()->addDays(3)->format('Y-m-d'),
        ]);
        $booking->bookable()->associate($bookable);
        $booking->personAddress()
            ->associate(PersonAddress::factory()->create());
        $booking->user()->associate($user);
        $booking->price = 100;
        $booking->save();

        /** @var Review $review */
        $review = Review::factory()->make();
        $review->bookable()->associate($bookable);
        $review->booking()->associate($booking);
        $review->save();

        $response = $this->actingAs($user)
            ->getJson('/api/user');

        $response->assertOk()
            ->assertJson([
                'email' => $user->email,
                'name' => $user->name,
                'newReviewCount' => 1,
                'isVerified' => true,
            ]);

        Mail::assertSent(BookingMade::class);
    }
}
