<?php

namespace Tests\Feature;

use App\Models\Bookable;
use App\Models\BookableCategory;
use App\Models\Booking;
use App\Models\PersonAddress;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class BookableReviewTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccessReviewList()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var BookableCategory $category */
        $category = BookableCategory::factory()->hasBookables()->create();
        /** @var Bookable $bookable */
        $bookable = $category->bookables()->first();
        $personAddress = PersonAddress::factory()->create();

        $bookings = Booking::factory(2)
            ->sequence(
                ['user_id' => null],
                ['user_id' => $user->id],
            )
            ->create([
                'bookable_id' => $bookable->id,
                'price' => 100,
                'person_address_id' => $personAddress->id,
            ]);

        Review::factory(2)
            ->sequence(
                ['booking_id' => $bookings[0]->id],
                ['booking_id' => $bookings[1]->id],
            )
            ->create(['bookable_id' => $bookable->id]);

        $response = $this->getJson('/api/bookables/' . $bookable->id . '/reviews');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJson(
                fn(AssertableJson $json) => $json->where(
                    'data.0.id',
                    fn(string $id) => Str::isUuid($id)
                )->whereType(
                    'data.0.description', 'string'
                )->whereType(
                    'data.0.rating', 'integer'
                )->whereType(
                    'data.0.createdAt', 'string'
                )->whereType(
                    'data.0.user', 'null'
                )->whereType(
                    'data.1.user', 'array'
                )->whereType(
                    'data.1.user.id', 'integer'
                )->whereType(
                    'data.1.user.name', 'string'
                )
                    ->etc()
            )
            ->assertJsonStructure([
                'data' => [['id', 'description', 'rating', 'createdAt', 'user']],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => [
                    'current_page',
                    'from',
                    'to',
                    'last_page',
                    'path',
                    'per_page',
                    'total',
                    'links' => [
                        ['active', 'label', 'url',]
                    ],
                ]
            ])
            ->assertJsonPath('meta.total', 2)
            ->assertJsonPath('meta.per_page', (int)config('bnb.review_per_page'));
    }

    public function testReviewsNotFoundByBookableId(): void
    {
        $this->getJson('/api/bookables/' . Str::uuid() . '/reviews')
            ->assertNotFound();
    }
}
