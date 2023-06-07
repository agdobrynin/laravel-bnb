<?php

namespace Tests\Feature;

use App\Models\Bookable;
use App\Models\BookableCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class BookableTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $this->makeBookables();

        $response = $this->getJson('/api/bookables')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'description', 'price', 'price_weekend', 'category'],
                ],
                'links' => ['first', 'last', 'next', 'prev'],
                'meta' => [
                    'current_page',
                    'from',
                    'to',
                    'last_page',
                    'path',
                    'per_page',
                    'total',
                    'links' => [
                        '*' => ['active', 'label', 'url',]
                    ],
                ]
            ]);

        $bookableCount = Bookable::all(['id'])->count();

        $response->assertJsonCount(config('bnb.index_per_page'), 'data');
        $response->assertJsonPath('meta.per_page', config('bnb.index_per_page'));
        $response->assertJsonPath('meta.total', $bookableCount);

        $response->assertJson(function (AssertableJson $json) {
            $json->where('data.0.id', fn(string $id) => Str::isUuid($id))
                ->whereType('data.0.price', 'integer')
                ->whereType('data.0.price_weekend', 'integer');

            $json->where(
                'data.0.description',
                function (string $description) {
                    return Str::length($description) <= (config('bnb.index_description_short_length') + 3);
                }
            )->etc();
        });
    }

    public function testQueryParametersValidationError(): void
    {
        $response = $this->getJson(
            '/api/bookables?bookableCategoryId=abc&priceMin=abc&priceMax=abc&priceWeekendMin=abc&priceWeekendMax=abc'
        );

        $response->assertUnprocessable()
            ->assertJsonPath('errors.bookableCategoryId.0', 'The bookable category id must be a valid UUID.')
            ->assertJsonPath('errors.priceMin.0', 'The price min must be an integer.')
            ->assertJsonPath('errors.priceMax.0', 'The price max must be an integer.')
            ->assertJsonPath('errors.priceWeekendMin.0', 'The price weekend min must be an integer.')
            ->assertJsonPath('errors.priceWeekendMax.0', 'The price weekend max must be an integer.');
    }

    public function testFilter(): void
    {
        $this->makeBookables();

        $bookableCategory = BookableCategory::first();
        $priceMin = 1;
        $priceMax = 100;

        $bookableFilteredCount = Bookable::where('price', '<=', $priceMax)
            ->where('price', '>=', $priceMin)
            ->where('bookable_category_id', $bookableCategory->id)->count();

        $uri = '/api/bookables?priceMax=' . $priceMax . '&priceMin=' . $priceMin . '&bookableCategoryId=' . $bookableCategory->id;

        $response = $this->getJson($uri)
            ->assertOk()
            ->assertJsonPath('meta.total', $bookableFilteredCount)
            ->assertJsonPath('data.0.category', $bookableCategory->name)
            ->assertJson(function (AssertableJson $json) use ($priceMax) {
                $json->where('data.0.price', fn(int $price) => $price <= $priceMax)
                    ->etc();
            });

        $lastPage = $response->json('links.last');
        // get last page for check last item.
        $response = $this->getJson($lastPage)
            ->assertOk();

        $lastBookable = last($response->json('data'));
        $this->assertLessThanOrEqual($priceMax, $lastBookable['price']);
        $this->assertEquals($bookableCategory->name, $lastBookable['category']);
    }

    public function testShowSuccess(): void
    {
        /** @var BookableCategory $category */
        $category = BookableCategory::factory()->has(Bookable::factory())->create();
        /** @var Bookable $bookable */
        $bookable = $category->bookables()->first();

        $response = $this->getJson('/api/bookables/' . $bookable->id);

        $response->assertOk()
            ->assertJson(['data' => [
                'id' => $bookable->id,
                'title' => $bookable->title,
                'description' => $bookable->description,
                'price' => $bookable->price,
                'price_weekend' => $bookable->price_weekend,
                'category' => $category->name,
            ]]);
    }

    public function testShowNotFound(): void
    {
        $this->getJson('/api/bookables/' . Str::uuid())
            ->assertNotFound()
            ->assertJsonStructure(['message']);
    }

    protected function makeBookables(): void
    {
        BookableCategory::factory(3)->has(Bookable::factory(60))
            ->sequence(
                ['name' => 'First category'],
                ['name' => 'Second category'],
                ['name' => 'Other category'],
            )
            ->create();
    }
}
