<?php

namespace Tests\Feature;

use App\Models\Bookable;
use App\Models\BookableCategory;
use App\Models\Booking;
use App\Models\PersonAddress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $this->actingAs($user)
            ->getJson('/api/user')
            ->assertOk()
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

    public function testUserWithNewReviewForMadeBookings()
    {
        /** @var User $user */
        $user = User::factory()->create();

        BookableCategory::factory()
            ->has(
                Bookable::factory()
                    ->has(
                        Booking::factory(2)
                            ->state([
                                'person_address_id' => PersonAddress::factory()->create()->id,
                                'user_id' => $user->id,
                            ])
                    )
            )
            ->create();

        $response = $this->actingAs($user)
            ->getJson('/api/user')
            ->assertOk()
            ->assertJson([
                'email' => $user->email,
                'name' => $user->name,
                'newReviewCount' => 2,
                'isVerified' => true,
            ]);
    }
}
