<?php

namespace Tests\Feature;

use App\Models\BookableCategory;
use Database\Seeders\BookableCategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class BookableCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function testEmptyCategory()
    {
        $response = $this->getJson('/api/bookables/categories');
        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function testCategory()
    {
        $this->seed(BookableCategorySeeder::class);
        $categories = BookableCategory::all();

        $response = $this->getJson('/api/bookables/categories');
        $response->assertOk()
            ->assertJsonStructure([
                'data' => ['*' => ['id', 'name']],
            ])
            ->assertJsonCount($categories->count(), 'data')
            ->assertJson(
                function (AssertableJson $json) {
                    $json->where('data.0.id', fn (string $id) => Str::isUuid($id));
                    $json->whereType('data.0.name', 'string');
                }
            );
    }
}
