<?php

namespace Tests\Feature;

use App\Models\Bookable;
use App\Models\BookableCategory;
use App\Models\Booking;
use App\Models\PersonAddress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ReviewStoreTest extends TestCase
{
    use RefreshDatabase;

    public function testStoreValidationError()
    {
        $data = [];

        $this->postJson('/api/reviews', $data)
            ->assertUnprocessable()
            ->assertJsonStructure(['message', 'errors' => ['id', 'description', 'rating']]);
    }

    public function testStoreValidationErrorWrongValues()
    {
        $data = [
            'id' => Str::uuid()->toString(),
            'rating' => -1,
            'description' => 'a',
        ];

        $this->postJson('/api/reviews', $data)
            ->assertUnprocessable()
            ->assertJson(function (AssertableJson $json) {
                $json->where('errors.description.0', fn (string $error) => str_contains($error, 'must be at least 2 characters'))
                    ->where('errors.rating', fn (string $error) => str_contains($error, 'The selected rating is invalid'))
                    ->etc();
            })
            ->assertJsonStructure(['message', 'errors' => ['description', 'rating']]);
    }

    public function testStoreValidationErrorUnverifiedEmail()
    {
        $data = [
            'id' => Str::uuid()->toString(),
            'rating' => 5,
            'description' => 'small description here',
        ];

        $user = User::factory()->create(['email_verified_at' => null]);

        $this->actingAs($user)->postJson('/api/reviews', $data)
            ->assertUnprocessable()
            ->assertJson(function (AssertableJson $json) {
                $json->where('errors.verify-email.0', 'Please verify email')
                    ->etc();
            })
            ->assertJsonStructure(['message', 'errors' => ['verify-email']]);
    }

    public function testStoreNotFoundByReviewKey()
    {
        $data = [
            'id' => Str::uuid()->toString(),
            'rating' => 5,
            'description' => 'small description here',
        ];

        $this->postJson('/api/reviews', $data)
            ->assertNotFound();
    }

    public function testStoreForbidden()
    {
        $booking = $this->makeBooking(null);

        $data = [
            'id' => $booking->review_key,
            'rating' => 5,
            'description' => 'small description here',
        ];

        $this->actingAs(User::factory()->create())->postJson('/api/reviews', $data)
            ->assertForbidden();
    }

    public function testStoreSuccess()
    {
        $user = User::factory()->create();
        $booking = $this->makeBooking($user);

        $data = [
            'id' => $booking->review_key,
            'rating' => 5,
            'description' => 'small description here',
        ];

        $this->actingAs($user)->postJson('/api/reviews', $data)
            ->assertCreated()
            ->assertJsonStructure(['data' => ['hasReview']]);
    }

    protected function makeBooking(?User $user): Booking
    {
        return BookableCategory::factory()->has(
            Bookable::factory()->has(
                Booking::factory()->afterMaking(
                    function (Booking $booking) use ($user) {
                        $booking->personAddress()->associate(PersonAddress::factory()->create());
                        $booking->user()->associate($user);
                    }
                )
            )
        )->create()->bookables->first()->bookings->first();
    }
}
