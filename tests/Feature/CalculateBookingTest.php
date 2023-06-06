<?php

namespace Tests\Feature;

use App\Models\Bookable;
use App\Models\BookableCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CalculateBookingTest extends TestCase
{
    use RefreshDatabase;

    public function testCalculateSuccess(): void
    {
        $bookable = $this->makeBookable();

        // Dates include weekdays from Tuesday to Sunday
        $dateFrom = '2023-06-06';
        $dateTo = '2023-06-11';

        $response = $this->getJson('/api/bookables/' . $bookable->id . '/calculate?start=' . $dateFrom . '&end=' . $dateTo);

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->where(
                    'data.calculate.bookableId',
                    fn(string $id) => Str::isUuid($id)
                )
                    ->whereType('data.calculate.totalPrice', 'integer')
                    ->whereType('data.calculate.breakdown.weekend.totalPrice', 'integer')
                    ->whereType('data.calculate.breakdown.weekend.pricePerDay', 'integer')
                    ->whereType('data.calculate.breakdown.weekend.days', 'integer')
                    ->whereType('data.calculate.breakdown.regular.totalPrice', 'integer')
                    ->whereType('data.calculate.breakdown.regular.pricePerDay', 'integer')
                    ->whereType('data.calculate.breakdown.regular.days', 'integer')
                    ->etc();
            })
            ->assertJsonPath('data.calculate.dateStart', $dateFrom)
            ->assertJsonPath('data.calculate.dateEnd', $dateTo);
    }

    public function testCalculateNotFoundBookable(): void
    {
        $dateFrom = '2023-01-01';
        $dateTo = '2023-01-02';

        $this->getJson('/api/bookables/' . Str::uuid() . '/calculate?start=' . $dateFrom . '&end=' . $dateTo)
            ->assertNotFound();
    }

    public function testCalculateValidationErrorInputParameters(): void
    {
        $bookable = $this->makeBookable();

        $dateFrom = '2023';
        $dateTo = '2023';

        $this->getJson('/api/bookables/' . $bookable->id . '/calculate?start=' . $dateFrom . '&end=' . $dateTo)
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message', 'errors' => ['start' => [], 'end' => []]
            ])
            ->assertJsonPath('errors.start.0', 'The start does not match the format Y-m-d.')
            ->assertJsonPath('errors.end.0', 'The end does not match the format Y-m-d.')
            ->assertJsonPath('errors.end.1', 'The end must be a date after start.');
    }

    protected function makeBookable(): Bookable
    {
        return BookableCategory::factory()
            ->has(Bookable::factory())
            ->create()
            ->bookables()
            ->first();
    }
}
