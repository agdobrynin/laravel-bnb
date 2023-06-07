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
                    'links' => ['*' => ['url', 'label', 'active',]],
                ],
            ]);
    }

    public function testTwoReview(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var PersonAddress $address */
        $address = PersonAddress::factory()->create();

        $category = BookableCategory::factory()
            ->has(
                Bookable::factory()
                    ->has(
                        Booking::factory(2)
                            ->afterMaking(fn(Booking $booking) => $booking->personAddress()->associate($address))
                            ->afterMaking(fn(Booking $booking) => $booking->user()->associate($user))
                    )
            )
            ->create();

        $this->actingAs($user)
            ->getJson('/api/booking-without-review')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.total', 2)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['bookable' => ['id', 'title'], 'start', 'end', 'price', 'reviewKey']
                ]
            ])
            ->assertJson(function (AssertableJson $json) {
                $json->where('data.0.bookable.id', fn(string $id) => Str::isUuid($id));
                $json->whereType('data.0.bookable.title', 'string');
                $json->where('data.0.start', fn(string $date) => (bool)preg_match('/\d{4}-\d{2}-\d{2}/', $date));
                $json->where('data.0.end', fn(string $date) => (bool)preg_match('/\d{4}-\d{2}-\d{2}/', $date));
                $json->whereType('data.0.price', 'integer');
                $json->where('data.0.reviewKey', fn(string $id) => Str::isUuid($id));
                $json->etc();
            });
    }
}
