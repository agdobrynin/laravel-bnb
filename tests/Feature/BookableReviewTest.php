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
        $category = BookableCategory::factory()
            ->has(
                Bookable::factory()
                    ->has(
                        Booking::factory(3)
                            ->sequence(
                                ['user_id' => null],
                                ['user_id' => $user->id],
                            )
                            ->afterMaking(function (Booking $booking) {
                                $booking->personAddress()->associate(PersonAddress::factory()->create());
                            })
                    )
            )->create();

        Booking::all()->each(function (Booking $booking) {
           Review::factory()->create([
               'booking_id' => $booking->id,
               'bookable_id' => $booking->bookable()->first('id'),
           ]);
        });

        $response = $this->getJson('/api/bookables/' . $category->bookables[0]->id . '/reviews');

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJson(
                function (AssertableJson $json) {
                    $json->where('data.0.id', fn(string $id) => Str::isUuid($id));
                    $json->whereType('data.0.description', 'string');
                    $json->whereType('data.0.rating', 'integer');
                    $json->whereType('data.0.createdAt', 'string');
                    $json->whereType('data.0.user', 'null');
                    $json->whereType('data.1.user', 'array');
                    $json->whereType('data.1.user.id', 'integer');
                    $json->whereType('data.1.user.name', 'string');
                    $json->etc();
                }
            )
            ->assertJsonStructure([
                'data' => ['*' => ['id', 'description', 'rating', 'createdAt', 'user']],
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
            ->assertJsonPath('meta.total', 3)
            ->assertJsonPath('meta.per_page', (int)config('bnb.review_per_page'));
    }

    public function testReviewsNotFoundByBookableId(): void
    {
        $this->getJson('/api/bookables/' . Str::uuid() . '/reviews')
            ->assertNotFound();
    }
}
