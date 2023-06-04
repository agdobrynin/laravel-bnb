<?php

namespace Tests\Feature;

use App\Models\Bookable;
use App\Models\BookableCategory;
use App\Models\Booking;
use App\Models\PersonAddress;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class BookableAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    public function testAvailableBookingDates()
    {
        $bookable = $this->makeBookable();

        $from = Carbon::now()->addDay()->format('Y-m-d');
        $to = Carbon::now()->addDays(4)->format('Y-m-d');

        $response = $this->getJson(
            '/api/bookables/' . $bookable->id . '/availability?start=' . $from . '&end=' . $to
        );

        $response->assertOk()
            ->assertJsonStructure(['data'])
            ->assertJson(['data' => []]);
    }

    public function testNotAvailableBookingDates()
    {
        $bookable = $this->makeBookable();
        /** @var Booking $booking */
        $booking = Booking::factory()->make([
            'id' => Str::orderedUuid(),
            'review_key' => Str::orderedUuid(),
            'start' => Carbon::now()->addDay(),
            'end' => Carbon::now()->addDays(5),
        ]);
        $booking->bookable()->associate($bookable);
        $booking->personAddress()
            ->associate(PersonAddress::factory()->create());
        $booking->price = 100;
        $booking->saveQuietly();

        $from = Carbon::now()->format('Y-m-d');
        $to = Carbon::now()->addDay()->format('Y-m-d');

        $response = $this->getJson(
            '/api/bookables/' . $bookable->id . '/availability?start=' . $from . '&end=' . $to
        );

        $response->assertOk()
            ->assertJsonStructure(['data'])
            ->assertJson(['data' => [
                [
                    'start' => $booking->start->format('Y-m-d'),
                    'end' => $booking->end->format('Y-m-d'),
                ]
            ]]);
    }

    public function testNoValidInput(): void
    {
        $bookable = $this->makeBookable();

        $response = $this->getJson(
            '/api/bookables/' . $bookable->id . '/availability'
        );

        $response->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => ['start', 'end']
            ])
            ->assertJsonPath('errors.start.0', 'The start field is required.')
            ->assertJsonPath('errors.end.0', 'The end field is required.');

        $from = Carbon::now()->addDays(-1);

        $response = $this->getJson(
            '/api/bookables/' . $bookable->id . '/availability?start=' . $from->format('Y-m-d')
        );

        $response->assertUnprocessable()
            ->assertJsonPath('errors.start.0', 'The start must be a date after or equal to today.')
            ->assertJsonPath('errors.end.0', 'The end field is required.');

        $from = Carbon::now();

        $response = $this->getJson(
            '/api/bookables/' . $bookable->id . '/availability?start=' . $from . '&end=' . $from
        );

        $response->assertUnprocessable()
            ->assertJsonPath('errors.start.0', 'The start does not match the format Y-m-d.')
            ->assertJsonPath('errors.end.0', 'The end does not match the format Y-m-d.');
    }

    public function testNotFoundBookable(): void
    {
        $from = Carbon::now()->format('Y-m-d');
        $to = Carbon::now()->addDays(2)->format('Y-m-d');
        $uuid = Str::orderedUuid();

        $response = $this->getJson(
            '/api/bookables/' . $uuid . '/availability?start=' . $from . '&end=' . $to
        );

        $response->assertNotFound()
            ->assertJsonPath('message', 'Not found by id: ' . $uuid);
    }

    protected function makeBookable(): Bookable
    {
        /** @var BookableCategory $category */
        $category = BookableCategory::factory()->create();
        /** @var Bookable $bookable */
        $bookable = Bookable::factory()->make();
        $bookable->bookableCategory()
            ->associate($category)
            ->save();

        return $bookable;
    }
}
