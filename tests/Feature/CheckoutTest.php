<?php

namespace Tests\Feature;

use App\Mail\BookingMade;
use App\Models\Bookable;
use App\Models\BookableCategory;
use App\Models\Booking;
use App\Models\PersonAddress;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function testCheckoutValidationError(): void
    {
        $data = [];

        $this->postJson('/api/checkout', $data)
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'bookings', 'person.first_name', 'person.last_name', 'person.address', 'person.email',
                ],
            ])
            ->assertJson(function (AssertableJson $json) {
                $json->where('errors', function (Collection $fields) {
                    return preg_grep('/field is required/', $fields['person.first_name'])
                        && preg_grep('/field is required/', $fields['person.last_name'])
                        && preg_grep('/field is required/', $fields['person.address'])
                        && preg_grep('/field is required/', $fields['person.email'])
                        && preg_grep('/field is required/', $fields['bookings']);
                });

                $json->etc();
            });
    }

    public function testCheckoutValidationPersonError(): void
    {
        $data = [
            'person' => [
                'first_name' => 'a',
                'last_name' => 'a',
                'email' => 'asas',
                'address' => 'short',
            ],
        ];

        $this->postJson('/api/checkout', $data)
            ->assertUnprocessable()
            ->assertJson(function (AssertableJson $json) {
                $json->where('errors', function (Collection $fields) {
                    return (bool) preg_grep('/must be a valid email address/', $fields['person.email']);
                })->where('errors', function (Collection $fields) {
                    return (bool) preg_grep('/must be at least 2 characters/', $fields['person.first_name']);
                })->where('errors', function (Collection $fields) {
                    return (bool) preg_grep('/must be at least 2 characters/', $fields['person.last_name']);
                });
                $json->where('errors', function (Collection $fields) {
                    return (bool) preg_grep('/must be at least 10 characters/', $fields['person.address']);
                });
                $json->etc();
            });
    }

    public function testBookingDateValidationError(): void
    {
        $bookable = $this->makeBookableWithBooking();

        $data = [
            'bookings' => [
                [
                    'start' => '20',
                    'end' => '20',
                    'bookable_id' => $bookable->id,
                ],
            ],
        ];

        $this->postJson('/api/checkout', $data)
            ->assertUnprocessable()
            ->assertJson(function (AssertableJson $json) {
                $json->where('errors', function (Collection $fields) {
                    return preg_grep('/does not match the format Y-m-d/', $fields['bookings.0.start'])
                        && preg_grep('/must be a date after or equal to today/', $fields['bookings.0.start']);
                })->where('errors', function (Collection $fields) {
                    return preg_grep('/does not match the format Y-m-d/', $fields['bookings.0.end'])
                        && preg_grep('/must be a date after/', $fields['bookings.0.end']);
                })->etc();
            });
    }

    public function testBookableNotFound(): void
    {
        $this->postJson('/api/checkout', ['bookings' => [['bookable_id' => Str::uuid()]]])
            ->assertNotFound();
    }

    public function testCheckoutUnavailableDates(): void
    {
        $bookable = $this->makeBookableWithBooking();
        ['start' => $start, 'end' => $end] = $bookable->bookings()->first();
        $data = [
            'person' => ['first_name' => 'Ivan', 'last_name' => 'Ivanov', 'address' => 'My address here', 'email' => 'a@a.com'],
            'bookings' => [
                ['start' => $start, 'end' => $end, 'bookable_id' => $bookable->id],
            ],
        ];

        $this->postJson('/api/checkout', $data)
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Object is not available for given dates');
    }

    public function testCheckoutSuccess(): void
    {
        Mail::fake();

        BookableCategory::factory()
            ->has(Bookable::factory())
            ->create();

        $bookable = Bookable::all()->first();
        $start = Carbon::now()->addDay()->format('Y-m-d');
        $end = Carbon::now()->addDays(2)->format('Y-m-d');

        $data = [
            'person' => [
                'first_name' => 'Ivan',
                'last_name' => 'Ivanov',
                'address' => 'My address here',
                'email' => 'a@a.com',
                'phone' => null,
            ],
            'bookings' => [
                ['start' => $start, 'end' => $end, 'bookable_id' => $bookable->id],
            ],
        ];

        $this->postJson('/api/checkout', $data)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [['start', 'end', 'price', 'reviewKey', 'bookable' => ['id', 'title']]],
            ])
            ->assertJson(function (AssertableJson $json) {
                $json->where('data.0.start', fn (string $date) => (bool) preg_match('/\d{4}-\d{2}-\d{2}/', $date))
                    ->where('data.0.end', fn (string $date) => (bool) preg_match('/\d{4}-\d{2}-\d{2}/', $date))
                    ->whereType('data.0.price', 'integer')
                    ->where('data.0.reviewKey', fn (string $id) => Str::isUuid($id))
                    ->where('data.0.bookable.id', fn (string $id) => Str::isUuid($id))
                    ->whereType('data.0.bookable.title', 'string');
            });

        Mail::assertSent(BookingMade::class);
    }

    protected function makeBookableWithBooking(): Bookable
    {
        return BookableCategory::factory()
            ->has(
                Bookable::factory()
                    ->has(
                        Booking::factory()
                            ->afterMaking(
                                function (Booking $booking) {
                                    $booking->personAddress()->associate(PersonAddress::factory()->create());
                                    $booking->start = Carbon::now()->addDay();
                                    $booking->end = Carbon::now()->addDays(2);
                                }
                            )
                    )
            )
            ->create()
            ->bookables->first();
    }
}
