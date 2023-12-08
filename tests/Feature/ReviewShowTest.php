<?php

namespace Tests\Feature;

use App\Models\Bookable;
use App\Models\BookableCategory;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReviewShowTest extends TestCase
{
    use RefreshDatabase;

    public function testReviewNotExist()
    {
        $this->getJson('/api/reviews/'.Str::uuid())
            ->assertOk()->assertJson(['data' => ['hasReview' => false]]);
    }

    public function testReviewExist()
    {
        $bookable = BookableCategory::factory()->has(
            Bookable::factory()
        )->create()->bookables->first();

        $review = Review::factory()->create(['bookable_id' => $bookable->id]);

        $this->getJson('/api/reviews/'.$review->id)
            ->assertOk()->assertJson(['data' => ['hasReview' => true]]);
    }
}
